<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'errorchk.php';
class Service extends CI_Controller {

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

				$services = $this->all_list->active_task($usr_id);
				//var_dump($services);

				$data['all_usr'] = '';
				$data['cate'] = 'service';
				$data['usr_id'] = $usr_id;
				$data['services'] = $services;
				$this->load->view('/service_view/index',$data);		
					
			}	
			$this->load->view('footer');					
		}else{
			redirect('/');
		}		
	}	

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

	// first draft_save and do discss
	public function discuss($service_name = 'writing'){ //error 신고 입력! DB--> error_essay //0
		if($this->session->userdata('is_login')){
			$token = $this->input->POST('token');			
			$essay_id 	 = $this->input->POST('data_id');

			log_message('error', "w_discuss id : $essay_id");

			$draft_dic = $this->get_service_data();
			$draft_dic['draft'] = 1;
			$draft_dic['submit'] = 0;

			$result = $this->service_list->insert_service_data($draft_dic);
			
			log_message('error', 'discuss -> draft_save :' . $result);
			
			if ($service_name == 'museprep') {
				$this->curl->ssl(FALSE);
				$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);
				log_message('error', "[DEBUG] musebase w_discuss editing/discuss id : " . $essay_id);
				$access = $this->curl->simple_post(MUSE_PREP_URL. 'editor/editing/discuss', array('token'=>$token, 'id'=>$essay_id), $curl_options);
				log_message('error', "[DEBUG] musebase w_discuss result : " . $access);
			} else {
				if (IS_SSL) {
					$this->curl->ssl(FALSE);
				}
				$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);
				log_message('error', "[DEBUG] w_discuss editing/discuss id : " . $essay_id);
				$access = $this->curl->simple_post(EDGE_WRITING_URL. 'editor/editing/discuss', array('token'=>$token, 'id'=>$essay_id), $curl_options);
				log_message('error', "[DEBUG] w_discuss result : " . $access);
			}

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

		$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);

		if (IS_SSL) {
			$this->curl->ssl(FALSE);
		}		
		$access = $this->curl->simple_post(EDGE_WRITING_URL. 'editor/auth', array('secret'=>$secret,'email'=>$email), $curl_options);

		log_message('error', '[DEBUG] editor/auth result = ' . $access);

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
			//$this->curl->ssl(FALSE);
			if (IS_SSL) {
				$this->curl->ssl(FALSE);
			}
			$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);
			log_message('error', '[DEBUG] editor/get token = ' . $token);
			$result_data = $this->curl->simple_post(EDGE_WRITING_URL . 'editor/get', array('token'=>$token), $curl_options);

			log_message('error', '[DEBUG] editor/get result_data = ' . $result_data);


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

	public function get_museprep()
	{
		$secret = 'isdyf3584MjAI419BPuJ5V6X3YT3rU3C';
		$email = $this->session->userdata('email');

		$this->curl->ssl(FALSE);
		$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);

		/***
		if (IS_SSL) {
			$this->curl->ssl(FALSE);
		}
		***/		
		$access = $this->curl->simple_post(MUSE_PREP_URL. 'editor/auth', array('secret'=>$secret,'email'=>$email), $curl_options);

		log_message('error', '[DEBUG] museprep :: editor/auth result = ' . $access);

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
			/***
			if (IS_SSL) {
				$this->curl->ssl(FALSE);
			}
			***/
			$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);
			log_message('error', '[DEBUG] museprep :: editor/get token = ' . $token);
			$result_data = $this->curl->simple_post(MUSE_PREP_URL . 'editor/get', array('token'=>$token), $curl_options);

			log_message('error', '[DEBUG] museprep :: editor/get result_data = ' . $result_data);


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


	public function auth(){
		$token = $this->input->post('token');
		$w_id = $this->input->post('w_id');
		$service_name = $this->input->post('service_name');

		if (IS_SSL) {
			$this->curl->ssl(FALSE);
		}	
		$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);
		log_message('error', '[DEBUG] editor/start token = ' . $token);
		log_message('error', '[DEBUG] editor/start id = ' . $w_id);
		$access = $this->curl->simple_post(EDGE_WRITING_URL . 'editor/editing/start', array('token'=>$token,'id'=>$w_id), $curl_options);

		log_message('error', '[DEBUG] editing/start result : ' . $access);

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

	public function auth_museprep(){
		$token = $this->input->post('token');
		$w_id = $this->input->post('w_id');

		$this->curl->ssl(FALSE);

		$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);
		log_message('error', '[DEBUG] museprep editing/start token = ' . $token);
		log_message('error', '[DEBUG] museprep editing/start id = ' . $w_id);
		$access = $this->curl->simple_post(MUSE_PREP_URL . 'editor/editing/start', array('token'=>$token,'id'=>$w_id), $curl_options);

		log_message('error', '[DEBUG] museprep editing/start result : ' . $access);

		$json['result'] = $access;						

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

	public function writing()
	{			
		if($this->session->userdata('is_login')){
			$cate['cate'] = 'service';
			$this->load->view('head',$cate);

			$usr_id = $this->session->userdata('id');	

			$data['all_usr'] = '';
			$data['cate'] = 'service';
			$data['usr_id'] = $usr_id;
			$this->load->view('/service_view/writing',$data);		
					
			$this->load->view('footer');					
		}else{
			redirect('/');
		}		
	}

	public function museprep()
	{			
		if($this->session->userdata('is_login')){
			$cate['cate'] = 'service';
			$this->load->view('head',$cate);

			$usr_id = $this->session->userdata('id');	

			$data['all_usr'] = '';
			$data['cate'] = 'service';
			$data['usr_id'] = $usr_id;
			$this->load->view('/service_view/museprep',$data);		
					
			$this->load->view('footer');					
		}else{
			redirect('/');
		}		
	}	

	public function service_editor($service_name = 'writing'){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'service';
			$this->load->view('head',$data);

			//$service_name = "writing";
			$row = $this->all_list->get_serviceId_num($service_name);
			$type = $row->id;  // get service_id

			/** service_view/index 에서 form으로 전송된값 **/
			$token = $this->input->post('token');
			$w_id = $this->input->post('w_id'); // Writing service data_id.
			$title = $this->input->post('title');
			$writing = $this->input->post('writing');	
			log_message('error', '[DEBUG] writing -> writing : ' . $writing);		
			$kind_id = $this->input->post('kind_id');			
			$word_count = $this->input->post('word_count');
			$start_date = $this->input->post('start_date');
			$price_kind = $this->input->post('price_kind');
			$orig_id = $this->input->post('orig_id');
			$reason = $this->input->post('reason');
			$user_file = $this->input->post('user_file');


			$data['tag_templet'] = $this->all_list->get_tag($kind_id);
			$data['score_templet'] = $this->all_list->get_scores_temp($kind_id);
			//var_dump($data['score_templet']);		

			$data['templet'] = $this->get_templet_ele($kind_id);
			

			$essay = $this->service_list->get_one_essay_by_essayid('service', $w_id, $type);

			//log_message('error', 'w_id : ' . $w_id . ', type : '. $type);
			if ($essay)
			{
				//log_message('error', 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
				$scoring = $essay->scoring;
				$kind = $essay->kind;
				$score2 = $essay->score2;		

				$score1 = $this->score_pattern_replace($scoring);			
				$data['score1'] = $score1;	

				$score2 = $this->score_pattern_replace($score2);
				$data['score2'] = $score2;	

				$data['kind'] = $kind;
				$data['kind_name'] = strtoupper($essay->kind_name);
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

				$data['edit_writing'] = preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>",$editing);

				$convert = str_replace('"', '&quot',$essay->raw_txt); 
				$convert = str_replace('’', "'",$convert);
				$convert = str_replace('“', '"',$convert);
				$convert = str_replace('”', '"',$convert);
				$data['raw_writing'] = $convert;
				$data['re_raw_writing'] = preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>", $convert);
				$data['discuss'] = $essay->discuss;
				$data['writing'] = preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>",$editing);
				$data['id'] = $w_id;
				//$data['token'] = '';
				$tagging = str_replace('"','',preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>", $essay->tagging));
				$data['error_chk'] = 'N'; // Y or N
				$data['tagging'] = $tagging;
				$data['critique'] = $essay->critique;
				$data['type'] = $type;
				$data['pj_id'] = '';
				$data['word_count'] = $essay->word_count;	
				$data['submit'] = $essay->submit;
				$data['draft'] = $essay->draft;
				$data['price_kind'] = $essay->price_kind;
				$data['start_date'] = $essay->start_date;
				$data['orig_id'] = $essay->orig_essay_id;
				$convert = str_replace('"', '&quot',$essay->reason); 
				$convert = str_replace('’', "'",$convert);
				$convert = str_replace('“', '"',$convert);
				$convert = str_replace('”', '"',$convert);
				$data['reason'] = $convert;
				$data['user_file'] = $essay->user_file;
				$data['download_link'] = EDGE_WRITING_URL . "download/file/" . $user_file;
				$data['filename'] = $essay->filename;
			}
			else
			{
				/***
				$type = $row->id;  // get service_id

				$token = $this->input->post('token');
				$w_id = $this->input->post('w_id'); // Writing service data_id.
				$title = $this->input->post('title');
				$writing = $this->input->post('writing');	
				log_message('error', '[DEBUG] writing -> writing : ' . $writing);		
				$kind_id = $this->input->post('kind_id');			
				$word_count = $this->input->post('word_count');
				$start_date = $this->input->post('start_date');
				$price_kind = $this->input->post('price_kind');
				$orig_id = $this->input->post('orig_id');
				$reason = $this->input->post('reason');
				$user_file = $this->input->post('user_file');
				***/

				$dic = array();
				$dic['usr_id']		= $this->session->userdata('id');
				$dic['w_id']			= $w_id;
				$dic['title']			= $this->db->escape($title);

				$writing = $this->br2nl(strip_tags($writing, '<p><br>'));
				$writing = $this->p2nl($writing);

				$dic['raw_writing']		= $this->db->escape($writing);
				$dic['editing']		= $this->db->escape($writing);
				$dic['tagging']		= $this->db->escape($writing);
				$dic['critique']		= "''";
				$dic['score1']		= "''";
				$dic['score2']		= "''";
				$dic['word_count']		= $word_count;
				$dic['time']			= 0;
				$dic['kind']			= $kind_id;
				$dic['type']			= $type;
				$dic['start_date']		= $start_date;
				$dic['price_kind']		= $this->db->escape($price_kind);
				$dic['orig_essay_id']	= $orig_id;
				$dic['reason']		= $this->db->escape($reason);
				$dic['user_file']		= $user_file;
				$dic['draft'] = 1;
				$dic['submit'] = 0;

				$result = $this->service_list->insert_service_data($dic);
			



				//log_message('error', 'ccccccccccccccccccccccccccccccccc');
				//$score1 = $this->score_pattern_replace($scoring);			
				$data['score1'] = '';	

				//$score2 = $this->score_pattern_replace($score2);
				$data['score2'] = '';				

				$data['title'] = $title;

				//$writing = $this->br2nl(strip_tags($writing, '<p><br>'));
				//$writing = $this->p2nl($writing);

				$convert = str_replace('’', "'",$writing);
				$convert = str_replace('“', '"',$convert);
				$convert = str_replace('”', '"',$convert);
				$convert = str_replace('"', '&quot;',$convert); 
				$data['raw_writing'] = $convert;
				//log_message('error', "[DEBUG] raw_writing = " . $data['raw_writing'] );
				
				
				//$convert = str_replace('’', "'",$convert);
				//$convert = str_replace('“', '"',$convert);
				//$convert = str_replace('”', '"',$convert);
				
				//$data['writing'] = preg_replace("/[\n\r]/","<br>", $convert);
				$data['writing'] = preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>", $convert);

				$data['re_raw_writing'] = preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>", $convert); //Tagging 할때 쓰임! All clear
				$data['critique'] = '';
				$data['kind'] = $kind_id;
				$data['kind_name'] = strtoupper($this->all_list->get_kind_name($kind_id)->kind);
				//$data['conf'] = true;
				$data['error_chk'] = 'N'; // Y or N
				$data['submit'] = '0'; // 1 or 2
				$data['discuss'] = 'Y'; // Y or N
				$data['time'] = 0;
				$data['cate'] = 'writing';
				$data['word_count'] = $word_count;
				$data['edit_writing'] = '';
				$data['tagging'] = preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>", $convert);
				$data['start_date'] = $start_date;
				$data['price_kind'] = $price_kind;
				$data['orig_id'] = $orig_id;
				$data['reason'] = $reason;
				$data['user_file'] = $user_file;
				$data['download_link'] = EDGE_WRITING_URL . "download/file/" . $user_file;
				$data['filename'] = "";
				//$data['classify'] = 'new';				
			}

			if ($service_name == 'museprep') {
				$temp = explode("|||", $data['title']);
				if (count($temp) == 2) {
					$data['question'] = $temp[0];
					$data['passage'] = $temp[1];
				} else {
					$data['question'] = $data['title'];
					$data['passage'] = "";
				}
			}

			$data['token'] = $token;
			$data['id'] = $w_id; // Writing service data_id.			
			$data['type'] = $type;
			$data['pj_id'] = '';
			$data['service_name'] = $service_name;
			if ($data['orig_id'] == 0 || $data['id'] == $data['orig_id']) {
				$data['re_submit'] = 'No';
			} else {
				$data['re_submit'] = 'Yes';
			}

			if ($service_name == "writing") {
				$this->load->view('/editor/writing_editor',$data);
			} else if ($service_name == "museprep") {
				$this->load->view('/editor/writing_editor',$data);					
			} else {
				$this->load->view('/editor/editor',$data);
			}		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	function get_service_data() {
		$usr_id = $this->session->userdata('id');
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
		$start_date = $this->input->POST('start_date');
		$price_kind = $this->db->escape($this->input->POST('price_kind') );
		$orig_id = $this->input->POST('orig_id');
		$reason = $this->db->escape($this->input->POST('reason'));
		$user_file = $this->input->POST('user_file');


		log_message('error', 'price_kind = ' . $price_kind);

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
		$dic['start_date']		= $start_date;
		$dic['price_kind']		= $price_kind;
		$dic['orig_essay_id']	= $orig_id;
		$dic['reason']		= $reason;
		$dic['user_file']		= $user_file;

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

			$draft_dic = $this->get_service_data();
			$draft_dic['draft'] = 1;
			$draft_dic['submit'] = 0;

			$result = $this->service_list->insert_service_data($draft_dic);
			
			log_message('error', 'draft_save :' . $result);
			
			$json['status'] = $result;			
		}else{
			redirect('/');
		}		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));	
	}

	public function w_submit($service_name = 'writing'){
		if($this->session->userdata('is_login')){
			//log_message('error', "w_submit called!!!");

			$token = $this->input->POST('token');

			$submit_dic = $this->get_service_data();
			$submit_dic['draft']			= 1;
			$submit_dic['submit']		= 0;

			/***
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
			***/

			// first, draft save
			$query_res = $this->service_list->insert_service_data($submit_dic);
			//$query_res = $this->all_list->local_save($usr_id,$w_id,$raw_writing,$editing,$tagging,$critique,$title,$kind,$score1,$score2,$word_count,$time,$type);		

			//$query_res = true;
			//log_message('error', print_r($query_res, 1));

			if($query_res){ // True or false
				$data_id = $query_res;
				log_message('error', '[DEBUG] w_submit   insert_id = ' . $data_id);

				// Error chk
				$errorchk_class = new Errorchk;
				$error_chk = $errorchk_class->error_chk('once',$data_id,$submit_dic['type']);

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
					//$submit_dic['editing'] = mysql_real_escape_string($errorchk_class->garbageTag_replace($submit_dic['editing']) );
					
					// finally, if no error is dectected, submit save
					//$submit_dic['submit']	= 1;
					//$query_res = $this->service_list->insert_service_data($submit_dic);
					$update_dic = array();
					$update_dic['essay_id'] = $submit_dic['w_id'];
					$update_dic['service_id'] = $submit_dic['type'];
					$update_dic['submit'] = 1;
					$query_res = $this->service_list->update_service_data($update_dic);
		 
					$essays = $this->service_list->get_essay($data_id, $submit_dic['type']);

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
					$data["id"] = $submit_dic['w_id'];
					$data["editing"] = $essays[0]->editing;
					if ($completed_editing != "")
					{
						$data["done"] = $completed_editing;
					}
					else
					{
						$data["done"] = $submit_dic['editing'];
					}
					$data["critique"] = $essays[0]->critique;
					$data["date"] = date("Y-m-d H:i:s", time());

					if ($service_name == 'museprep') {
						$data["score"] = json_decode($essays[0]->scoring, true);
					} else {
						$data["score"] = $essays[0]->scoring;
					}
					
					$filename = $essays[0]->filename;
					$fileNameParts   = explode( ".", $filename );

					$data["file"] = $fileNameParts[0];

					$sending_data["status"]	= true;
					$sending_data["data"] = $data;

					$json_data = json_encode($sending_data);
					log_message('error', $json_data);

					//$access = $this->curl->simple_post('https://edgewriting.net/editor/editing/done', array('token'=>$token, 'id'=>$w_id, 'editing'=>$this->input->POST('editing'), 'critique'=>$this->input->POST('critique')));
					//log_message('error', $json_data);
					//log_message('error', "token : $token");
					if ($service_name == 'museprep') {
						$this->curl->ssl(FALSE);
						$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);
						log_message('error', '[DEBUG] museprep edtiting/done token : ' . $token);
						log_message('error', '[DEBUG] museprep edtiting/done id : ' . $submit_dic['w_id']);
						$access = $this->curl->simple_post(MUSE_PREP_URL . 'editor/editing/done', array('token'=>$token, 'id'=>$submit_dic['w_id'], 'data'=>$json_data), $curl_options);
						log_message('error', '[DEBUG] museprep edtiting/done result : ' . $access);
					} else {
						if (IS_SSL) {
							$this->curl->ssl(FALSE);
						}
						$curl_options = array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3);
						log_message('error', '[DEBUG] edtiting/done token : ' . $token);
						log_message('error', '[DEBUG] edtiting/done id : ' . $submit_dic['w_id']);
						$access = $this->curl->simple_post(EDGE_WRITING_URL . 'editor/editing/done', array('token'=>$token, 'id'=>$submit_dic['w_id'], 'data'=>$json_data), $curl_options);
						log_message('error', '[DEBUG] edtiting/done result : ' . $access);
					}


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
		} else{
			redirect('/');
		}
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

			$totalcount = $this->service_list->get_service_export_count($service_id,$year,$month);
			$json['data_count'] = $totalcount->count;
			$json['data_list'] = $this->service_list->get_service_export_data($service_id,$year,$month,$limit,$page_list);
			
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

	public function download(){ //0
		$year = $this->input->get_post('year');
		$month = $this->input->get_post('month');

		$data = file_get_contents('./csv/'.$year.$month.".csv"); // Read the file's contents
		$name = $year.$month.'.csv';	
		force_download($name,$data);
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

	function br2nl($string) 
	{ 
	    //return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string); 
	    return preg_replace(array("/\<br(\s*)?\/?\>/i","/\<\/br(\s*)?\/?\>/iU"), 
	                        array("\n","\n"), 
	                        $string); 
	} 

	// p 태그가 존재하면, p 태그 이외의 줄바꿈은 제거하고, paragraph 처리만 수행함.
	function p2nl ($str) { 
	    preg_match_all('#<p (.*?)>(.*?)</p>#is', $str, $p_matches);

	    if (count($p_matches[0]) < 1) {
	    	return $str;
	    }

	    $str = preg_replace("/[\n\r]/"," ", $str);

	    return preg_replace(array("/<p[^>]*>/iU","/<\/p[^>]*>/iU"), 
	                        array("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;","\n\n"), 
	                        $str); 

	}

}
?>