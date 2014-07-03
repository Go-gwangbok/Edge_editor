<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'errorchk.php';
class Writing extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->model('service_list');
		$this->load->helper('url');	
		$this->load->helper('file');

		$this->load->helper(array('form', 'url'));
		$this->load->helper('download');		
		$this->load->dbutil();	
		//$this->load->library('email');
		//$this->load->library('eng_month');			
	}

	function eng_month($int_month){
		switch ($int_month) {
			case '01' : $str_month = 'January'; break;
            case '02' : $str_month = 'February'; break;
            case '03' : $str_month = 'March'; break;
            case '04' : $str_month = 'April'; break;
            case '05' : $str_month = 'May'; break;
            case '06' : $str_month = 'June'; break;
            case '07' : $str_month = 'July'; break;
            case '08' : $str_month = 'August'; break;
            case '09' : $str_month = 'September'; break;
            case '10' : $str_month = 'October'; break;
            case '11' : $str_month = 'November'; break;
            case '12' : $str_month = 'December'; break;
		}
		return $str_month;
	}

	public function index()
	{			
		if($this->session->userdata('is_login')){
			$classify = $this->session->userdata('classify');

			if($classify == 0){ // Admin
				$data['cate'] = 'service';
				$this->load->view('head',$data);				
				
				$data['services'] = $this->all_list->get_service_list();				
				$this->load->view('/service_view/admin_service_index',$data);					
			}else{	// Editor
				$cate['cate'] = 'service';
				$this->load->view('head',$cate);

				$usr_id = $this->session->userdata('id');								
				$data['all_usr'] = '';
				$data['cate'] = 'service';
				$this->load->view('/service_view/index',$data);		
					
			}	
			$this->load->view('footer');					
		}else{
			redirect('/');
		}		
	}	

	public function get_premium()
	{
		//$secret = 'isdyf3584MjAI419BPuJ5V6X3YT3rU3C';
		$email = $this->session->userdata('email');

		//$last_id = 1;
		$last_id = $this->service_list->get_max_premium_essay_id();

		//print $last_id;
		//$this->curl->ssl(FALSE);
		//$access = $this->curl->simple_post('https://edgewriting.net/editor/get_premium', array('secret'=>$secret,'email'=>$email,'last_id'=>$last_id));
		//$this->curl->option(CURLOPT_CONNECTTIMEOU, 1);
		if (IS_SSL) {
			$this->curl->ssl(FALSE);
		}
		$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);
		$access = $this->curl->simple_post(EDGE_WRITING_URL. 'editor/get_premium', array('token'=>WRITING_PREMIUM_SECRET_KET,'email'=>$email,'last_id'=>$last_id), $curl_options);
		
		log_message('error', '[DEBUG] get_premium result = ' . $access);

		// $access = '{
		// 		    "status": true,
		// 		    "data": [
		//                       {"id":"69","kind_id":"5","title":"asdasda","writing":"asdadasd","date":"2014-05-15 19:21:01","words":"1","price_kind":"premium","orig_id":"0","reason":""}
		// 		        
		// 		    }
		// 		}';

		    //	[id] => 70
		    //	[kind_id] => 2
		    //	[title] => asdasd
		    //	[writing] => asdasasd asdad
		    //	[date] => 2014-05-21 21:24:47
		    //	[words] => 2
		    //	[price_kind] => premium
		    //	[orig_id] => 0
		    //	[reason] => 
		

		$access_status = json_decode($access, true);		
		
		if($access_status['status']){
			$premiumList = $access_status['data'];

			if (count($premiumList) > 0) {
				$service_name = "writing";
				$row = $this->all_list->get_serviceId_num($service_name);
				$service_id = $row->id;
			}

			foreach ($premiumList as $premium) {
				//print_r($premium);

				$service_dic = array();
				$service_dic['usr_id']		= 0;
				$service_dic['w_id']		= $premium['id'];
				$service_dic['title']			= $this->db->escape($premium['title']);
				$service_dic['raw_writing']	= $this->db->escape($premium['writing']);
				$service_dic['editing']		= $this->db->escape("");
				$service_dic['tagging']		= $this->db->escape("");
				$service_dic['critique']		= $this->db->escape("");
				$service_dic['score1']		= $this->db->escape("");
				$service_dic['score2']		= $this->db->escape("");
				$service_dic['word_count']	= $premium['words'];
				$service_dic['time']		= 0;
				$service_dic['kind']		= $premium['kind_id'];
				$service_dic['type']		= $service_id;
				$service_dic['price_kind']		= $this->db->escape($premium['price_kind']);
				//$service_dic['orig_essay_id']	= $premium['orig_id'];
				if ($premium['orig_id'] == 0) {
					$service_dic['orig_essay_id']	= $premium['id'];
				} else {
					$service_dic['orig_essay_id']	= $premium['orig_id'];
				}
				$service_dic['reason']		= $this->db->escape($premium['reason']);
				$service_dic['start_date']		= $premium['date'];
				$service_dic['draft']		= 0;
				$service_dic['submit']		= 0;

				$query_res = $this->service_list->insert_service_data($service_dic);

				if (!$query_res) {
					$access_status['status'] = false;
					break;
				}
			}
		}

		//$json['result'] = $access;

		//$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}	

	function premium(){
		if($this->session->userdata('is_login')){
			$classify = $this->session->userdata('classify');

			if($classify == 0){ // Admin
				
				$data['cate'] = 'service';
				$this->load->view('head',$data);
				
				$this->get_premium();	

				$page = 1;		
				$data['page'] = $page;
				$list = 20; // 한페이지에 보요질 갯수.			
				$data['list'] = $list;

				$service_name = "writing";
				$data['service_name'] = $service_name;

				$row = $this->all_list->get_serviceId_num($service_name);
				$service_id = $row->id;
				$data['service_id'] = $service_id;

				$this->load->view('/service_view/premium_list',$data);
				$this->load->view('footer');
			}
			else {
				redirect('/');
			}

		}else{
			redirect('/');
		}					
	}

	function list_premium(){
		if($this->session->userdata('is_login')){
			$classify = $this->session->userdata('classify');

			if($classify == 0){ // Admin
				$page = $this->input->post('page');
				$page_list = $this->input->post('list');
				$service_id = $this->input->post('service_id');

				//$page_list = 20;			
				$limit = ($page - 1) * $page_list;	
				$price_kind = 'premium';				

				$totalcount = $this->service_list->get_service_pricekindCount($service_id, $price_kind);
				$json['total_count'] = $totalcount->count;
				$json['list'] = $this->service_list->get_service_pricekindData($service_id,$price_kind, $limit,$page_list);
				
				$json['page'] = $page;
				$json['page_list'] = $page_list;
			}
			else {
				redirect('/');
			}

		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	function view_premium($data_id){
		if($this->session->userdata('is_login')){
			$classify = $this->session->userdata('classify');

			if($classify == 0){ // Admin
				$data['cate'] = 'service';
				$this->load->view('head',$data);

				$service_name = "writing";
				$data['service_name'] = $service_name;

				$row = $this->all_list->get_serviceId_num($service_name);
				$service_id = $row->id;
				$data['service_id'] = $service_id;

				$rows = $this->service_list->get_one_essay_by_essayid($data['cate'],$data_id, $service_id);

				if ($rows == false) {
					return false;
				} else {
					$premium =  $this->pretreatment_premium($rows);

					$usrs = $this->all_list->get_editors_by_task($rows->type);

					$editor_list = array();
					foreach($usrs as $usr) {
						$editor = array();
						$editor['id'] = $usr->usr_id;
						$editor['name'] = $usr->usr_name;

						$editor_list[] = $editor;
					}

					$data['premium'] = $premium;
					$data['editor_list'] = $editor_list;
				}

				$this->load->view('/service_view/premium_view',$data);
				$this->load->view('footer');
				//$this->output->set_content_type('application/json')->set_output(json_encode($data));
			}
			else {
				redirect('/');
			}

		}else{
			redirect('/');
		}					
	}

	function view_essay($data_id){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'service';
			$this->load->view('head',$data);

			$service_name = "writing";
			$data['service_name'] = $service_name;

			$row = $this->all_list->get_serviceId_num($service_name);
			$service_id = $row->id;
			$data['service_id'] = $service_id;

			$rows = $this->service_list->get_one_essay_by_essayid($data['cate'],$data_id);

			if ($rows == false) {
				return false;
			} else {
				$data =  $this->pretreatment_premium($rows);

				$data['tag_templet'] = $this->all_list->get_tag($rows->kind);
				$data['score_templet'] = $this->all_list->get_scores_temp($rows->kind);		

				$data['templet'] = $this->get_templet_ele($rows->kind);
				$data['error_chk'] = 'N';
				$data['discuss'] = $rows->discuss;
				
				$editing = $rows->editing;
				$start_doubble_quotationConfirm = substr($editing,0,1);
				$end_doubble_quotationConfirm = substr($editing,-1);
				if($start_doubble_quotationConfirm == '"'){
					$editing = substr($editing, 1);
				}

				if($end_doubble_quotationConfirm == '"'){
					$editing = substr($editing, 0,-1);
				}
				$editing = str_replace('"', '&quot',$editing); 
				$data['edit_writing'] = preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>",$editing);
				$data['writing'] =  $data['edit_writing'];
				$tagging = str_replace('"','',preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>", $rows->tagging));
				$data['tagging'] = $tagging;
				$data['critique'] = $rows->critique;

				$data['score1'] = $this->score_pattern_replace($rows->scoring);	
				$data['score2'] = $this->score_pattern_replace($rows->score2);
				$data['time'] = $rows->time;
				$data['token'] = '';

				$data['cate'] = 'com';



			}
			//var_dump($data);

			$this->load->view('/editor/writing_editor',$data);
			$this->load->view('footer');
		}else{
			redirect('/');
		}					
	}


	function assign_editor_to_premium() {
		if($this->session->userdata('is_login')){
			$classify = $this->session->userdata('classify');

			if($classify == 0){ // Admin
				$draft_dic['essay_id'] = $this->input->POST('essay_id');	
				$draft_dic['usr_id'] = $this->input->POST('usr_id');	
				$draft_dic['service_id'] = $this->input->POST('service_id');
				$draft_dic['draft'] = 1;
				$draft_dic['submit'] = 0;

				log_message('error', '[DEBUG] assign_editor_to_premium essay_id :' . $draft_dic['essay_id']);
				log_message('error', '[DEBUG] assign_editor_to_premium usr_id :' . $draft_dic['usr_id']);

				$usr_info = $this->all_list->get_user($draft_dic['usr_id']);
				$email = $usr_info->email;
				$draft_dic['name'] = $usr_info->name;

				log_message('error', "[DEBUG] start_premium email : " . $email);

				if (IS_SSL) {
					$this->curl->ssl(FALSE);
				}
				$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);	
				$access = $this->curl->simple_post(EDGE_WRITING_URL .'editor/editing/start_premium', array('token'=>WRITING_PREMIUM_SECRET_KET,  'id'=>$draft_dic['essay_id'], 'email'=>$email));

				log_message('error', "[DEBUG] start_premium result : " . $access);

				$access_status = json_decode($access, true);

				if (!$access_status['status']) {
					$json['status'] = $access_status['status'];
					$json['error_msg'] = $access_status['error']['message'];

					//$access_status['error']['message'];
				} else {
					//$result = $this->send_email_to_editor($email, $draft_dic);
					$result = $this->send_email_to_editor("eric@akaon.com", $draft_dic);
					
					log_message('error', '[DEBUG] assign_editor_to_premium send_mail result :' . $this->email->print_debugger());
					log_message('error', '[DEBUG] assign_editor_to_premium send_mail result :' . $result);
					if (!$result) {
						$json['status'] = false;
						$json['error_msg'] = "send mail fail";
					} else {
						$result = $this->service_list->update_service_data($draft_dic);
						log_message('error', '[DEBUG] assign_editor_to_premium :' . $result);
						$json['status'] = $result;
						if (!$result) {
							$json['error_msg'] = "DB Update fail";
						}
					}									
				}	
			}
			else {
				redirect('/');
			}
		}else{
			redirect('/');
		}		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	function submit_premium() {
		if($this->session->userdata('is_login')){
			$classify = $this->session->userdata('classify');

			if($classify == 0){ // Admin
				$submit_dic['essay_id'] = $this->input->POST('essay_id');	
				$submit_dic['service_id'] = $this->input->POST('service_id');
				$submit_dic['done'] = $this->db->escape($this->input->POST('done'));
				$done_str = $this->input->POST('done');
				$submit_dic['draft'] = 1;
				$submit_dic['submit'] = 1;

				if (strlen($submit_dic['done']) < 10) {
					$json['status'] = false;
					$this->output->set_content_type('application/json')->set_output(json_encode($json));
					return;
				}
				log_message('error', 'essay_id :' . $submit_dic['essay_id']);

				$rows = $this->service_list->get_one_essay_by_essayid('service',$submit_dic['essay_id']);

				$filename = $rows->filename;
				$fileNameParts   = explode( ".", $filename );

				$usr_info = $this->all_list->get_user($rows->usr_id);
				$email = $usr_info->email;

				$data = array();
				$data["id"] = $submit_dic['essay_id'];
				$data["file"] = $fileNameParts[0];
				$data["editing"] = "";
				$data["done"] = $done_str;
				$data["critique"] = "";
				$data["score"] = "";
				$data["date"] = date("Y-m-d H:i:s", time());

				$sending_data["status"]	= true;
				$sending_data["data"] = $data;

				$json_data = json_encode($sending_data);

				log_message('error', "[DEBUG] done_premium request : " . $json_data);

				if (IS_SSL) {
					$this->curl->ssl(FALSE);
				}
				$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);	
				$access = $this->curl->simple_post(EDGE_WRITING_URL .'editor/editing/done_premium', array('token'=>WRITING_PREMIUM_SECRET_KET, 'id'=>$submit_dic['essay_id'], 'email'=>$email, 'data'=>$json_data));

				log_message('error', "[DEBUG] done_premium result : " . $access);

				$access_status = json_decode($access, true);

				if ($access_status['status']) {
					$result = $this->service_list->update_service_data($submit_dic);
					log_message('error', 'assign_editor_to_premium :' . $result);
					
					$json['status'] = $result;
				} else {
					$json['status'] = $access_status['status'];
				}
			}
			else {
				redirect('/');
			}
		}else{
			redirect('/');
		}		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	function get_related_servicedata(){
		if($this->session->userdata('is_login')){
			$classify = $this->session->userdata('classify');

			if($classify == 0){ // Admin
				$orig_essay_id = $this->input->POST('orig_essay_id');
				$essay_id = $this->input->POST('essay_id');	
				$service_id = $this->input->POST('service_id');

				log_message('error', 'essay_id :' . $orig_essay_id);

				$result = $this->service_list->get_related_servicedata($service_id, $essay_id, $orig_essay_id);
				$json['count'] = count($result);
				$json['list'] = $result;
			}
			else {
				redirect('/');
			}

		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	function get_service_id() {
		$service_name = "writing";
		$row = $this->all_list->get_serviceId_num($service_name);
		return $row->id;
	}

	function essay_list() {
		if($this->session->userdata('is_login')){						
			$page = $this->input->post('page');
			$list = $this->input->post('list');
			$cate = $this->input->post('cate');
			$editor_id = $this->input->post('editor_id');

			if ($cate != "com") {
				redirect('/');
			}

			$limit = ($page - 1) * $list;

			$service_id = $this->get_service_id();				

			if ($cate == 'com'){
				$total_count = $this->service_list->service_comp_totalcount($service_id,$editor_id); 
				$json['total_count'] = $total_count->count;
				$json['list'] = $this->service_list->get_service_comp($service_id,$editor_id,$page,$limit,$list); // history							
			}
			
			// $export_total = $this->all_list->exporttotal_count($pj_id);				
			// $json['export_total'] = $export_total->count;				
			//$json['pj_id'] = $pj_id;
			$json['edi'] = $editor_id;			
			$json['page'] = $page;
			$json['cate'] = $cate;
			$json['classify'] = $this->session->userdata('classify');

		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}



	/*************************/

	function serviceType($service_name){
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'service';
			$this->load->view('head',$data);				

			$row = $this->all_list->get_serviceId_num($service_name);
			$service_id = $row->id;
			$data['type_id'] = $service_id;			
			$data['service_name'] = $service_name;
			$data['all_year'] = $this->all_list->service_all_year_data($service_id);
			
			
			$this->load->view('/service_view/service_type_view',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}					
	}

	function get_service_month_data(){
		if($this->session->userdata('is_login')){
			$yen = $this->input->post('yen');			
			$type_id = $this->input->post('type_id');
			$result = $this->service_list->service_month_data($yen,$type_id);			
			$json['data'] = $result;			
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	function enter($service_name,$month,$year){ // 0
		if($this->session->userdata('is_login')){
			$data['cate'] = 'service';
			$this->load->view('head',$data);

			$str_month = $this->eng_month($month); // 숫자 달을 영문으로 변경하는 함수!
			$data['str_month'] = $str_month;			
			$data['int_month'] = $month;			
			$data['year'] = $year;		
			$data['service_name'] = $service_name;

			$row = $this->all_list->get_serviceId_num($service_name);
			$service_id = $row->id;
			$data['service_id'] = $service_id;			

			//$data['cate'] = 'writing';
			$this->load->view('/service_view/service_enter_view',$data);		
			$this->load->view('footer');			
		}else{
			redirect('/');
		}				
	}

	function get_enter_users(){
		if($this->session->userdata('is_login')){			
			$service_id = $this->input->post('service_id');		
			$year = $this->input->post('year');		
			$month = $this->input->post('month');		

			$json['memlist'] = $this->all_list->get_enter_users($service_id,$year,$month);
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	function get_enter_data(){
		if($this->session->userdata('is_login')){			
			$service_id = $this->input->post('service_id');		

			$list = $this->all_list->get_service_datalist($service_id);
			$json['memlist'] = $list;

		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function discuss(){ //error 신고 입력! DB--> error_essay //0
		if($this->session->userdata('is_login')){
			$token = $this->input->POST('token');			
			$essay_id 	 = $this->input->POST('data_id');

			log_message('error', "w_discuss id : $essay_id");
			$this->curl->ssl(FALSE);
			$access = $this->curl->simple_post('https://edgewriting.net/editor/editing/discuss', array('token'=>$token, 'id'=>$essay_id));
			log_message('error', "w_discuss result : " . $access);


			$result = $this->all_list->discuss_service_proc($essay_id);
			$json['result'] = $access;
		}else{
			redirect('/service');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}


	public function get_writing()
	{
		$secret = 'isdyf3584MjAI419BPuJ5V6X3YT3rU3C';
		$email = $this->session->userdata('email');
		$this->curl->ssl(FALSE);		
		$access = $this->curl->simple_post('https://edgewriting.net/editor/auth', array('secret'=>$secret,'email'=>$email));

		log_message('error', 'access = ' . $access);

		// $access = '{
		// 		    "status": true,
		// 		    "data": {
		// 		        "token": "~~~"
		// 		    }
		// 		}';		

		$access_status = json_decode($access, true);		
		
		if($access_status['status']){
			
			// success -> token
			$token = $access_status['data']['token'];

			log_message('error', "token : $token");
			
			// re_curl
			$this->curl->ssl(FALSE);
			$result_data = $this->curl->simple_post('https://edgewriting.net/editor/get', array('token'=>$token));

			log_message('error', 'result_data = ' . $result_data);


			//log_message('error', "result_data : $result_data");
			/** "is_24hr":"1" == true  "0" == 'false'   "is_critique":"1" == true  "0" == 'false' **/
			
			// $result_data = '{
			// 			    "status": true,
			// 			    "data": 
			// 		        {
			// 		            "id": 1,
			// 		            "kind": "essay",
			// 		            "is_24hr":"1",
			// 		            "is_critique": "0",
			// 		            "title": "Which is better for children to grow up in the countryside or in a big city.personally disagree with the former idea since children in the urban area can acquire the better educational conditions as well as improve their capability through positive competition",
			// 		            "writing": " personally disagree with the former idea since children in the urban area can acquire the better educational conditions as well as improve their capability through positive competition",					            
			// 		            "date": "2014-03-12 15:06:03"
			// 		        }						   
			// 			}';		
			
			$json['result'] = $result_data;	
			$json['access'] = $access;

		}else{ //status = false
			$json['result'] = $access;
			$json['access'] = $access;

		}		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function download($service_id, $essay_id, $filename)
	{
		if (!isset($service_id) || !isset($essay_id) || !isset($filename) ) {
			$data['message'] = "Invalid request";
			$this->load->view('/error/alert_n_history_back',$data);
			return;
		}

		if ($service_id == "" || $essay_id == "" || $filename == "" ) {
			//echo "invalid request";
			$data['message'] = "Invalid request";
			$this->load->view('/error/alert_n_history_back',$data);
			return;
		}

		if(!isset($_SERVER['HTTP_USER_AGENT']))return false;

		$essay = $this->service_list->get_one_essay_by_essayid('service', $essay_id);

		if (!$essay) {
			$data['message'] = "Invalid essay_id";
			$this->load->view('/error/alert_n_history_back',$data);
			return;
		}

		$fileNameParts = explode( ".", $essay->filename);
		if ($filename != $fileNameParts[0]) {
			$data['message'] = "Invalid filename";
			$this->load->view('/error/alert_n_history_back',$data);
			return;
		}

		$full_path = "./uploads/tmp/".$filename . ".". $fileNameParts[1];
		$file_size = filesize($full_path);
		//$encoded_filename = urlencode($full_path);
		$download_fname = "EDGE_Writing_$essay_id.doc";

		if (is_file("$full_path")) {
			if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 5.5")) {   
				header("Content-Type: doesn/matter");   
				header("Content-Length: ".$file_size);   
				header("Content-Disposition: filename=$download_fname");   
				header("Content-Transfer-Encoding: binary");   
				header("Pragma: no-cache");   
				header("Expires: 0");   
			} else {   
				header("Content-type: file/unknown");   
				header("Content-Disposition: attachment; filename=$download_fname");   
				header("Content-Transfer-Encoding: binary");   
				header("Content-Length: ".$file_size);   
				header("Content-Description: PHP3 Generated Data");   
				header("Pragma: no-cache");   
				header("Expires: 0");   
			}   
		   
			$fp = fopen("$full_path", "r");   
			if (!fpassthru($fp))   
			fclose($fp);   
		} else {
			$data['message'] = "File does not exist";
			$this->load->view('/error/alert_n_history_back',$data);
			return;
		}
	}

	private function pretreatment_premium($rows)
	{
		$premium['id'] = $rows->id;
		$premium['essay_id'] = $rows->essay_id;
		$premium['orig_essay_id'] = $rows->orig_essay_id;
		$premium['orig_id'] = $rows->orig_essay_id;
		if ($rows->orig_essay_id == 0 || $rows->orig_essay_id == $rows->essay_id) {
			$premium['re_submit'] = "No";
		}
		else {
			$premium['re_submit'] = "Yes";
		}
		$premium['kind'] = "";
		if (isset ($rows->kind_name)) {
			$premium['kind'] = strtoupper($rows->kind_name);
			$premium['kind_name'] = strtoupper($rows->kind_name);
		}
		$premium['title'] = str_replace('"', '&quot', $rows->prompt);
		$convert = str_replace('"', '&quot',$rows->raw_txt); 
		$convert = str_replace('’', "'",$convert);
		$convert = str_replace('“', '"',$convert);
		$convert = str_replace('”', '"',$convert);
		//”
		$convert = str_replace('”', '"',$convert);
		$premium['raw_writing'] = $convert;
		$premium['re_raw_writing'] = preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>", $convert);

		$premium['done'] = nl2br(str_replace('"', '&quot', $rows->done));

		$premium['word_count'] = $rows->word_count;
		$premium['type'] = $rows->type;
		$premium['usr_id'] = $rows->usr_id;
		log_message('error', 'usr_id = ' . $rows->usr_id);
		if ($rows->usr_id > 0) {
			$usr_info = $this->all_list->get_user($rows->usr_id);
			$premium['name'] = $usr_info->name;
			log_message('error', 'usr_name = ' . $premium['name']);
		}
		$premium['draft'] = $rows->draft;
		$premium['submit'] = $rows->submit;
		if ($rows->submit == 1) {
			$premium['status'] = "Done";
		} else if ($rows->draft == 1) {
			$premium['status'] = "In Progress";
		} else {
			$premium['status'] = "ToDo";
		}
		$premium['price_kind'] = $rows->price_kind;
		$premium['sub_date'] = $rows->sub_date;
		$premium['start_date'] = $rows->start_date;
		$premium['reason'] = str_replace('"', '&quot', $rows->reason);
		$filename = explode( ".", $rows->filename);
		$premium['filename'] = $filename[0];

		return $premium;
	}

	private function send_email_to_editor($to, $dic)
	{
		$essay_id = $dic['essay_id'];
		$service_id = $dic['service_id'];

		$essay = $this->all_list->get_one_essay_by_essayid('service', $essay_id);

		if (!$essay) {
			return false;
		}



		$premium = $this->pretreatment_premium($essay);
		log_message('error', '[DEBUG] premium name : ' . $dic['name'] . '###');

		$filename1 = "./uploads/premium_essay_$essay_id.txt";
		$fp = fopen($filename1, "w");
		if ($fp) {
			fputs($fp, $premium['raw_writing']);
			fclose($fp);
		}
		

		$url = 'https://edgewritings.com';
		$style = 'font-size:14px; color:#777; margin-right: 13px; text-decoration:none;'; 

		$table_style = "border-collapse:collapse;border-spacing:0px;width:100%;margin-bottom:20px;border:1px solid rgb(221,221,221)";
		$td_style1 = "width:80px;padding:8px;line-height:1.428571429;vertical-align:top;border:1px solid rgb(221,221,221);background-color:rgb(249,249,249)";
		$td_style2 = "padding:8px;line-height:1.428571429;vertical-align:top;border:1px solid rgb(221,221,221)";

		if ($premium['re_submit'] == 'Yes') {
			$reason_str = '<tr>
				<td style="' . $td_style1  . '"><b>Reason</b></td>
				<td style="' . $td_style2  . '">' . $premium['reason'] . '</td>
				</tr>';

			$related_essay_list = $this->service_list->get_related_servicedata($service_id, $essay_id, $premium['orig_essay_id']);

		} else {
			$reason_str = "";
		}

		$message = '
		<table background="'.$url.'/images/bar_email.png" style="background-repeat:no-repeat;" width="100%"; height="100%";  id="wrapper">
		<tbody>
		<tr>
		<td style="margin-left:10px;">
		<div style="margin-left: 30px; margin-top:-6px;"> 
		<a href="'.$url.'"><img src="'.$url.'/images/logo_s.png"></a>

		<p style="margin-top:30px; font-size:14px;">
		Dear ' . $dic['name'] . ' <br>
		Here is a request for a Premium service essay. <br>
		Please read through and follow the PREMIUM Editing Guidelines before proceeding onto the essay. <br>
		Remember that you must send the essay with its corrections to the admin within 40 hrs of the date in order to keep the deadline with our client. Thank you for your great work! <br>
		</p>

		<p style="margin-top:30px; font-size:18px;"><b>EDGE Writings Premium Essay</b></p>

		<table style="' . $table_style  . '">
		<tbody>
		<tr>
			<td style="' . $td_style1  . '"><b>Prompt</b></td>
			<td style="' . $td_style2  . '">' . $premium['title'] . '</td>
		</tr>
		<tr>
			<td style="' . $td_style1  . '"><b>KIND</b></td>
			<td style="' . $td_style2  . '">' . $premium['kind'] . '</td>
		</tr>
		<tr>
			<td style="' . $td_style1  . '"><b>Date</b></td>
			<td style="' . $td_style2  . '">' . $premium['start_date'] . '</td>
		</tr>
		<tr>
			<td style="' . $td_style1  . '"><b>Writing</b></td>
			<td style="' . $td_style2  . '">' . $premium['re_raw_writing'] . '</td>
		</tr>
		' . $reason_str . '
		</tbody>
		</table>';

		if ( $premium['re_submit'] == 'Yes'  && count($related_essay_list) > 0 ) {
			$message .= '<div>';

			foreach ($related_essay_list as $related_essay) {

				$r_premium = $this->pretreatment_premium($related_essay);

				$r_reason_str = "";
				if ($r_premium['essay_id'] == $premium['orig_essay_id']) {
					$r_title = "ORIGIN ESSEY";
					$r_re_submit = "No";
				} else {
					$r_title = "RE-COMMITED ESSEY";
					$r_re_submit = "Yes";

					$r_reason_str = '<tr>
						<td style="' . $td_style1  . '"><b>Reason</b></td>
						<td style="' . $td_style2  . '">' . $r_premium['reason'] . '</td>
						</tr>';
				}

				$download_str = "";
				if ($r_premium['filename'] != "") {
					$download_link = "http://localhost/writing/download/$service_id/" . $r_premium['essay_id'] . "/" . $r_premium['filename'] . "/"; 
					$download_str = '<a href="' . $download_link.'">File Download</a>';
				}



				$message .= '<div style="margin-bottom:3px;border:1px solid rgb(221,221,221);border-top-left-radius:4px;border-top-right-radius:4px;border-bottom-right-radius:4px;border-bottom-left-radius:4px;overflow:hidden">
<div style="padding:10px 15px;border-bottom-width:0px;border-color:rgb(221,221,221);border-top-right-radius:3px;border-top-left-radius:3px;background-color:rgb(245,245,245)"><h4 style="font-weight:500;line-height:1.1;color:inherit;margin-top:0px;margin-bottom:0px;font-size:16px">
<b>' . $r_title . '</b></h4></div><div style="color:rgb(255,0,221);min-height:auto">
<div style="padding:15px;border-top-width:1px;border-top-style:solid;border-top-color:rgb(221,221,221);color:rgb(51,51,51)">
<table style="border-collapse:collapse;border-spacing:0px;width:100%;margin-bottom:20px;border:1px solid rgb(221,221,221);background-color:transparent">
<tbody>
<tr>
  <tr>
	<td style="' . $td_style1  . '"><b>Prompt</b></td>
	<td style="' . $td_style2  . '">' . $related_essay->prompt . '</td>
  </tr>
		<tr>
			<td style="' . $td_style1  . '"><b>Date</b></td>
			<td style="' . $td_style2  . '">' . $r_premium['start_date'] . '</td>
		</tr>
		<tr>
			<td style="' . $td_style1  . '"><b>DocFile</b></td>
			<td style="' . $td_style2  . '">' . $download_str . '</td>
		</tr>
		<tr>
			<td style="' . $td_style1  . '"><b>Writing</b></td>
			<td style="' . $td_style2  . '">' . $r_premium['re_raw_writing'] . '</td>
		</tr>
		' . $r_reason_str . '
		</tbody></table></div></div>
</div>';
			}
			$message .= '</div>';

		}

		$message .= '
		<p style="margin-top:60px; font-size:13px; color:#555; margin-bottom:3px;">CATEGORY</p>
		<a class="cate_pointer" href="'.$url.'/cate/testprep" style="'.$style.'">TEST PREP</a><span style="'.$style.'">|</span><a class="cate_pointer" href="'.$url.'/cate/academic" style="'.$style.'">ACADEMIC</a><span style="'.$style.'">|</span><a class="cate_pointer" style="'.$style.'" href="'.$url.'/cate/admission">ADMISSION</a><span style="'.$style.'">|</span><a class="cate_pointer" style="'.$style.'" href="'.$url.'/cate/free">LIFE WRITING</a><span style="'.$style.'">|</span><a class="cate_pointer" style="'.$style.'" href="'.$url.'/cate/premium">PREMIUM</a>
		<p class="text-muted credit" style="font-size:12px;">EDGE Writings &nbsp;<a href="http://akaon.com/">AKAON.COM</a> &nbsp;Copyright 2014</p>
		</div>
		</td>
		</tr>
		</tbody>
		</table>
		';


		/** **/
		$email_setting = Array(
		    'protocol' => 'smtp',
		    'smtp_host' => 'ssl://smtp.gmail.com',
		    'smtp_port' => 465,
		    'smtp_user' => 'editors@edgewritings.com', // change it to yours
		    'smtp_pass' => 'aka5377201', // change it to yours
		    'smtp_timeout' => 10,
		    'mailtype' => 'html'
		); 

		//$this->email->initialize($email_setting);
		$this->load->library('email', $email_setting);
		$this->email->set_newline("\r\n");
		

		$this->email->from('editors@edgewritings.com', 'EDGE Writing');
		$this->email->to($to);
		$this->email->bcc('bettychoi@akaprep.com'); 
		$this->email->set_mailtype("html");
		$url = 'https://edgewritings.com';
		$style = 'font-size:14px; color:#777; margin-right: 13px; text-decoration:none;';  
		$bg_imageUrl = "url('".$url."'/images/bar_email.png);"; 

		$this->email->subject("EDGE Writings Premium Essay");
		$this->email->message($message); 
		$this->email->attach($filename1);
		return $this->email->send();
	}

	public function auth(){
		$token = $this->input->post('token');
		$w_id = $this->input->post('w_id');	

		$this->curl->ssl(FALSE);	
		$access = $this->curl->simple_post('https://edgewriting.net/editor/editing/start', array('token'=>$token,'id'=>$w_id));

		log_message('error', 'service/auth result : ' . $access);

		// $access = '{
		// 		    "status": true,
		// 		    "data": {
				        
		// 		    }
		// 		}';		

		$json['result'] = $access;						
		// $conform = json_decode($access,true);		
		
		// if($conform['status']){
		//	$json['result'] = $access;						
		// }else{			
		// 	$json['result'] = $access;			
		// }

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	function get_templet_ele($kind){
		return $this->all_list->get_templet_ele(2, $kind);
	}

	function score_pattern_replace($data_obj){
		$pattern = array('({)','(})','(")');
		$replace = array('','','');
		return preg_replace($pattern, $replace, $data_obj);			
	}

	public function writing(){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'service';
			$this->load->view('head',$data);

			$type = 2;

			/** service_view/index 에서 form으로 전송된값 **/
			$token = $this->input->post('token');
			$w_id = $this->input->post('w_id'); // Writing service data_id.
			$title = $this->input->post('title');
			$writing = $this->input->post('writing');			
			$kind_id = $this->input->post('kind_id');			
			$word_count = $this->input->post('word_count');

			$data['tag_templet'] = $this->all_list->get_tag($kind_id);
			$data['score_templet'] = $this->all_list->get_scores_temp($kind_id);		

			$data['templet'] = $this->get_templet_ele($kind_id);

			$essay = $this->all_list->get_one_essay_by_essayid('service', $w_id);

			if ($essay)
			{
				$scoring = $essay->scoring;
				$kind = $essay->kind;
				$score2 = $essay->score2;		

				$score1 = $this->score_pattern_replace($scoring);			
				$data['score1'] = $score1;	

				$score2 = $this->score_pattern_replace($score2);
				$data['score2'] = $score2;	

				$data['kind'] = $kind;
				$data['time'] = $essay->time;
				$data['title'] = str_replace('"', '&quot', $essay->prompt);
				$data['cate'] = 'writing';	
				
				// <p>  </p> 간혹 테스트하다가 태그로 감싸진 것이 있다. 그럴경우 detection 표시가 안된다!
				$editing = str_replace('<p>', '', trim($essay->editing));
				$editing = str_replace('</p>', '', $editing);
				$start_doubble_quotationConfirm = substr($editing,0,1);
				$end_doubble_quotationConfirm = substr($editing,-1);
				if($start_doubble_quotationConfirm == '"'){
					$editing = substr($editing, 1);
				}

				if($end_doubble_quotationConfirm == '"'){
					$editing = substr($editing, 0,-1);
				}
				$editing = str_replace('"', '&quot',$editing); 

				$data['edit_writing'] = preg_replace("/[\n\r]/","<br>",$editing);

				$convert = str_replace('"', '&quot',$essay->raw_txt); 
				$convert = str_replace('’', "'",$convert);
				$convert = str_replace('“', '"',$convert);
				$convert = str_replace('”', '"',$convert);
				$data['raw_writing'] = $convert;
				$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);
				$data['discuss'] = $essay->discuss;
				$data['writing'] = preg_replace("/[\n\r]/","<br>",$editing);
				$data['id'] = $w_id;
				//$data['token'] = '';
				$tagging = str_replace('"','',preg_replace("/[\n\r]/","<br>", $essay->tagging));
				$data['error_chk'] = 'N'; // Y or N
				$data['tagging'] = $tagging;
				$data['critique'] = $essay->critique;
				$data['type'] = $type;
				$data['pj_id'] = '';
				$data['word_count'] = $essay->word_count;	
				$data['submit'] = $essay->submit;
				$data['draft'] = $essay->draft;
			}
			else
			{
				//$score1 = $this->score_pattern_replace($scoring);			
				$data['score1'] = '';	

				//$score2 = $this->score_pattern_replace($score2);
				$data['score2'] = '';				

				$data['title'] = $title;
				$data['raw_writing'] = $writing;
				$data['writing'] = $writing;
				$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $writing); //Tagging 할때 쓰임! All clear
				$data['critique'] = '';
				$data['kind'] = $kind_id;
				//$data['conf'] = true;
				$data['error_chk'] = 'N'; // Y or N
				$data['submit'] = '0'; // 1 or 2
				$data['discuss'] = 'Y'; // Y or N
				$data['time'] = 0;
				$data['cate'] = 'writing';
				$data['word_count'] = $word_count;
				$data['edit_writing'] = '';
				$data['tagging'] = $writing;
				//$data['classify'] = 'new';				
			}

			$data['token'] = $token;
			$data['id'] = $w_id; // Writing service data_id.			
			$data['type'] = $type;
			$data['pj_id'] = '';


			$this->load->view('/editor/editor',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	function get_data() {
		$usr_id = $this->session->userdata('id');
		$token = $this->input->POST('token');
		$w_id = $this->input->POST('data_id');	
		$time = $this->input->POST('time');							
		$type = $this->input->POST('type');

		$title = $this->db->escape($this->input->POST('title'));
		$editing = $this->db->escape($this->input->POST('editing'));
		$raw_writing = $this->db->escape($this->input->POST('raw_writing'));
		$critique = $this->db->escape($this->input->POST('critique'));
		$tagging = $this->db->escape($this->input->POST('tagging'));		
		
		$score1 = $this->db->escape($this->input->post('score1'));		
		$score2 = $this->db->escape($this->input->post('score2'));		
		$kind = $this->input->POST('kind');
		$word_count = $this->input->POST('word_count');

		//local DB save
		$dic = array();
		$dic['usr_id']		= $usr_id;
		$dic['w_id']			= $w_id;
		$dic['title']			= $title;
		$dic['raw_writing']		= $raw_writing;
		$dic['editing']		= $editing;
		$dic['tagging']		= $tagging;
		$dic['critique']		= $critique;
		$dic['score1']		= $score1;
		$dic['score2']		= $score2;
		$dic['word_count']		= $word_count;
		$dic['time']			= $time;
		$dic['kind']			= $kind;
		$dic['type']			= $type;

		return $dic;
	} 

	public function draft_save(){ //<br>을 다시 \r\n으로 // 0
		if($this->session->userdata('is_login')){
			/***			
			$essay_id = $this->input->POST('data_id');						
			$time = $this->input->post('time');
			$score1 = $this->input->post('score1');
			$score2 = $this->input->post('score2');
			$editing = mysql_real_escape_string(trim($this->input->POST('editing')));
			log_message('error', 'essay_id :' . $essay_id);
			log_message('error', 'draft_save :' . $editing);
			$critique = mysql_real_escape_string(trim($this->input->POST('critique')));
			$tagging = mysql_real_escape_string(trim($this->input->POST('tagging')));			

			$result = $this->all_list->draft_servicedata($essay_id,$editing,$critique,$tagging,$score1,$score2,$time);
			**/

			$draft_dic = $this->get_data();
			$draft_dic['draft'] = 1;
			$draft_dic['submit'] = 0;

			$result = $this->all_list->insert_service_data($draft_dic);
			
			log_message('error', 'draft_save :' . $result);
			
			$json['status'] = $result;			
		}else{
			redirect('/');
		}		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));	
	}

	public function w_submit(){
		log_message('error', "w_submit called!!!");

		$usr_id = $this->session->userdata('id');
		$token = $this->input->POST('token');
		$w_id = $this->input->POST('data_id');	
		$time = $this->input->POST('time');							
		$type = $this->input->POST('type');

		$title = $this->db->escape($this->input->POST('title'));
		$editing = $this->db->escape($this->input->POST('editing'));
		$raw_writing = $this->db->escape($this->input->POST('raw_writing'));
		$critique = $this->db->escape($this->input->POST('critique'));
		$tagging = $this->db->escape($this->input->POST('tagging'));		
		
		$score1 = $this->db->escape($this->input->post('score1'));		
		$score2 = $this->db->escape($this->input->post('score2'));		
		$kind = $this->input->POST('kind');
		$word_count = $this->input->POST('word_count');

		//local DB save
		$dic = array();
		$dic['usr_id']		= $usr_id;
		$dic['w_id']			= $w_id;
		$dic['title']			= $title;
		$dic['raw_writing']		= $raw_writing;
		$dic['editing']		= $editing;
		$dic['tagging']		= $tagging;
		$dic['critique']		= $critique;
		$dic['score1']		= $score1;
		$dic['score2']		= $score2;
		$dic['word_count']		= $word_count;
		$dic['time']			= $time;
		$dic['kind']			= $kind;
		$dic['type']			= $type;
		$dic['draft']			= 1;
		$dic['submit']		= 0;

		// first, draft save
		$query_res = $this->all_list->insert_service_data($dic);
		//$query_res = $this->all_list->local_save($usr_id,$w_id,$raw_writing,$editing,$tagging,$critique,$title,$kind,$score1,$score2,$word_count,$time,$type);		

		//$query_res = true;
		//log_message('error', print_r($query_res, 1));

		if($query_res){ // True or false
			$data_id = $query_res;
			log_message('error', '[DEBUG] w_submit   insert_id = ' . $data_id);

			// Error chk
			$errorchk_class = new Errorchk;
			$error_chk = $errorchk_class->error_chk('once',$data_id,$type);

			//log_message('error', '[DEBUG] w_submit   error_chk = ' . $error_chk);

			if ($error_chk != 'true') 
			{
				$json['result'] = 'error_chk';
				$json['error_chk'] = $error_chk;

				//log_message('error', '[DEBUG] w_submit error_chk : ' . $error_chk);

				//$this->output->set_content_type('application/json')->set_output(json_encode($json));

				//exit();
			}
			else
			{
				// finally, if no error is dectected, submit save
				$dic['submit']	= 1;
				$query_res = $this->all_list->insert_service_data($dic);
	 
				$essays = $this->all_list->get_essay($data_id, $type);

				$completed_editing = "";
				if (count($essays) > 0)
				{
					$ex_editing = $essays[0]->ex_editing;
					log_message('error', '[DEBUG] w_submit ex_editing = ' . $ex_editing);

					if ($ex_editing != "")
					{
						$completed_editing = $this->get_completed_editing($ex_editing);
					}
				}

				$data = array();
				$data["id"] = $w_id;
				$data["editing"] = $editing;
				if ($completed_editing != "")
				{
					$data["done"] = $completed_editing;
				}
				else
				{
					$data["done"] = $editing;
				}
				$data["critique"] = $critique;
				$data["score"] = $score1;
				$data["date"] = date("Y-m-d H:i:s", time());

				$sending_data["status"]	= true;
				$sending_data["data"] = $data;

				$json_data = json_encode($sending_data);
				log_message('error', $json_data);

				//$access = $this->curl->simple_post('https://edgewriting.net/editor/editing/done', array('token'=>$token, 'id'=>$w_id, 'editing'=>$this->input->POST('editing'), 'critique'=>$this->input->POST('critique')));
				log_message('error', $json_data);
				log_message('error', "token : $token");
				$this->curl->ssl(FALSE);
				$access = $this->curl->simple_post('https://edgewriting.net/editor/editing/done', array('token'=>$token, 'id'=>$w_id, 'data'=>$json_data));
				log_message('error', $access);

				// $access = '{
				// 		    "status": true,
				// 		    "data": {
						        
				// 		    }
				// 		}';		

				$conform = json_decode($access,true);		
				if($conform['status']){
					$json['result'] = true;	
					
				}else{			
					$json['result'] = 'curl';							
				}
			}

			//$json['error_chk'] = $error_chk;	
		}else{
			$json['result'] = 'localdb';
		}
		log_message('error', '[DEBUG] w_submit json : ' . json_encode($json));

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function member_enter($service_name,$month,$year,$usr_id){ // 0
		if($this->session->userdata('is_login')){			
			$cate['cate'] = 'service';
			$this->load->view('head',$cate);			
			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;						

			$str_month = $this->eng_month($month); // 숫자 달을 영문으로 변경하는 함수!
			$usr_name = $this->all_list->get_user($usr_id);
			$data['name'] = $usr_name->name;

			$data['str_month'] = $str_month;
			$data['int_month'] = $month;
			$data['year'] = $year;
			$data['usr_id'] = $usr_id;
			$data['service_name'] = $service_name;

			$row = $this->all_list->get_serviceId_num($service_name);
			$service_id = $row->id;
			$data['service_id'] = $service_id;			

			$this->load->view('/service_view/member_enter_view',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}		
	}

	public function get_service_member_comp(){
		if($this->session->userdata('is_login')){			
			$page = $this->input->post('page');
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$usr_id = $this->input->post('usr_id');
			$service_id = $this->input->post('service_id');

			$page_list = 20;			
			$limit = ($page - 1) * $page_list;					

			$totalcount = $this->all_list->get_service_mem_completedCount($year,$month,$usr_id,$service_id);
			$json['data_count'] = $totalcount->count;
			$json['data_list'] = $this->all_list->get_service_mem_completedData($usr_id,$year,$month,$service_id,$limit,$page_list);
			
			$json['page'] = $page;
			$json['page_list'] = $page_list;						
		}else{
			redirect('/');
		}	
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function export($service_name, $month, $year){
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'service';
			$this->load->view('head',$data);

			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;

			$service_info = $this->service_list->get_serviceId_num($service_name);
			$service_id = $service_info->id;

			$row = $this->service_list->service_export_total_count($service_id, $month,$year);
			$data['service_name'] = $service_name;
			$data['service_id'] = $service_id;
			$data['total'] = $row->count;
			$data['export_count'] = $row->export_count;			
			$data['str_month'] = $str_month = $this->eng_month($month);
			$data['month'] = $month;
			$data['year'] = $year;
			$data['cate'] = 'service_export';

			$this->load->view('/service_view/service_export_view',$data);		
			$this->load->view('footer');								
		}else{
			redirect('/');
		}			
	}

	public function get_export_data(){
		if($this->session->userdata('is_login')){
			$service_id = $this->input->post('service_id');
			$page = $this->input->post('page');
			$year = $this->input->post('year');			
			$month = $this->input->post('month');
			
			$json['year'] = $year;								
			$page_list = 20;			
			$limit = ($page - 1) * $page_list;					

			$totalcount = $this->all_list->get_service_export_count($service_id,$year,$month);
			$json['data_count'] = $totalcount->count;
			$json['data_list'] = $this->all_list->get_service_export_data($service_id,$year,$month,$limit,$page_list);
			
			$json['page'] = $page;
			$json['page_list'] = $page_list;			
		}else{
			redirect('/');
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));	
	}

	function service_export_errorlist(){
		if($this->session->userdata('is_login')){
			$service_id = $this->input->post('service_id');
			$page = $this->input->post('page');
			$year = $this->input->post('year');			
			$month = $this->input->post('month');
			
			$json['year'] = $year;								
			$page_list = 20;			
			$limit = ($page - 1) * $page_list;					

			$totalcount = $this->service_list->get_service_error_count($service_id,$month,$year);
			$json['data_count'] = $totalcount->error_count;
			$json['data_list'] = $this->service_list->get_service_error_list($service_id,$month,$year,$limit,$page_list);
			
			$json['page'] = $page;
			$json['page_list'] = $page_list;			
		}else{
			redirect('/');
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));	

	}

	function all_export(){ // 0
		if($this->session->userdata('is_login')){
			$service_id = $this->input->post('service_id');
			$month = $this->input->get_post('month');			
			$year = $this->input->get_post('year');			

			$query = $this->db->query("SELECT prompt,ex_editing
					FROM service_data
					WHERE sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 00:00:00'
					AND essay_id !=0						
					and type = '$service_id'			
					AND submit = 1
					and ex_editing != ''
					and active = 0");
			
			$delimiter = ":::";
			$newline = "\r\n";

			$result = $this->dbutil->csv_from_result($query, $delimiter, $newline);

			if (!write_file('./csv/'.$year.$month.'.csv', $result)){
			     $json['result'] = false;
			}else{
			    $data = file_get_contents('./csv/'.$year.$month.".csv"); // Read the file's contents				
				if(strlen($data) > 0){
					$json['result'] = true;					
				}else{
					$json['result'] = false;	
				}				
			}			
			
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));		
	}

	function table_merge(){
		$merge = $this->all_list->table_merge();
		$json['result'] = $merge;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	function get_completed_editing($str) {
		$patterns = array("!<del(.*?)<\/del>!is", 
					"!<ins>!is", 
					"!</ins>!is", 
					"/<mod[^>]+\>/i", 
					"!</mod>!is");

		$replace = array("", 
					"", 
					"", 
					"", 
					"");

		$str = preg_replace($patterns, $replace, $str);
		log_message('error', "[debug] get_completed_editing => $str");

		return $str;
	}

	public function tbd($service_name,$month,$year){ // 0
		if($this->session->userdata('is_login')){
			$data['cate'] = 'service';
			$this->load->view('head',$data);			

			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;							

			$row = $this->all_list->get_serviceId_num($service_name);
			$service_id = $row->id;

			$str_month = $this->eng_month($month); // 숫자 달을 영문으로 변경하는 함수!
			$data['str_month'] = $str_month;
			$data['int_month'] = $month;
			$data['year'] = $year;
			$data['service_name'] = $service_name;
			$data['service_id'] = $service_id;

			$this->load->view('/service_view/tbd_view',$data);

			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function get_service_tbd(){
		if($this->session->userdata('is_login')){			
			$page = $this->input->post('page');
			$month = $this->input->post('month');
			$year = $this->input->post('year');
			$service_id = $this->input->post('service_id');

			$page_list = 20;			
			$limit = ($page - 1) * $page_list;					

			$totalcount = $this->service_list->get_service_tbdCount($year,$month,$service_id);
			$json['total_count'] = $totalcount->count;
			$json['list'] = $this->service_list->get_service_tbdData($year,$month,$service_id,$limit,$page_list);
			
			$json['page'] = $page;
			$json['page_list'] = $page_list;						
		}else{
			redirect('/');
		}	
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}


}
?>