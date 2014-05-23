<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'errorchk.php';
class Service extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
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
				$data['all_usr'] = '';
				$data['cate'] = 'service';
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
			$result = $this->all_list->service_month_data($yen,$type_id);			
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

	public function get_writing()
	{
		$secret = 'isdyf3584MjAI419BPuJ5V6X3YT3rU3C';
		$email = $this->session->userdata('email');		
		$access = $this->curl->simple_post('http://54.248.103.31/editor/auth', array('secret'=>$secret,'email'=>$email));

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
			$result_data = $this->curl->simple_post('http://54.248.103.31/editor/get', array('token'=>$token));


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

		$access = $this->curl->simple_post('http://54.248.103.31/editor/editing/start', array('token'=>$token,'id'=>$w_id));

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

	public function writing(){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'service';
			$this->load->view('head',$data);

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
			//$score1 = $this->score_pattern_replace($scoring);			
			$data['score1'] = '';	

			//$score2 = $this->score_pattern_replace($score2);
			$data['score2'] = '';				

			$data['title'] = $title;
			$data['raw_writing'] = $writing;
			$data['writing'] = $writing;
			$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $writing); //Tagging 할때 쓰임! All clear
			$data['token'] = $token;
			$data['id'] = $w_id; // Writing service data_id.			
			$data['critique'] = '';
			$data['kind'] = $kind_id;
			$data['type'] = '2';
			//$data['conf'] = true;
			$data['error_chk'] = 'N'; // Y or N
			$data['submit'] = '0'; // 1 or 2
			$data['discuss'] = 'Y'; // Y or N
			$data['time'] = 0;
			$data['cate'] = 'writing';
			$data['pj_id'] = '';
			$data['word_count'] = $word_count;
			$data['edit_writing'] = '';
			$data['tagging'] = $writing;
			//$data['classify'] = 'new';

			$this->load->view('/editor/editor',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
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
		//$query_res = $this->all_list->local_save($usr_id,$w_id,$raw_writing,$editing,$tagging,$critique,$title,$kind,$score1,$score2,$word_count,$time,$type);		
		
		$data["id"] = $w_id;
		$data["editing"] = $editing;
		$data["done"] = $raw_writing;
		$data["critique"] = $critique;
		$data["socre"] = $score1;
		$data["date"] = date("Y-m-d H:i:s", time());

		$sending_data["status"]	= true;
		$sending_data["data"] = $data;

		$json_data = json_encode($sending_data);
		log_message('error', $json_data);

		//return;

		$query_res = true;

		if($query_res){ // True or false
			// Error chk
			// $errorchk_class = new Errorchk;
			// $error_chk = $errorchk_class->error_chk('once',$w_id,$type);

			//$access = $this->curl->simple_post('http://54.248.103.31/editor/editing/done', array('token'=>$token, 'id'=>$w_id, 'editing'=>$this->input->POST('editing'), 'critique'=>$this->input->POST('critique')));
			log_message('error', $json_data);
			log_message('error', "token : $token");
			$access = $this->curl->simple_post('http://54.248.103.31/editor/editing/done', array('token'=>$token, 'id'=>$w_id, 'data'=>$json_data));
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
			//$json['error_chk'] = $error_chk;	
		}else{
			$json['result'] = 'localdb';
		}				
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

	public function export($month,$year){
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'service';
			$this->load->view('head',$data);

			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;						

			$row = $this->all_list->service_export_total_count($month,$year);
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
			$page = $this->input->post('page');
			$year = $this->input->post('year');			
			$month = $this->input->post('month');
			
			$json['year'] = $year;								
			$page_list = 20;			
			$limit = ($page - 1) * $page_list;					

			$totalcount = $this->all_list->get_service_export_count($year,$month);
			$json['data_count'] = $totalcount->count;
			$json['data_list'] = $this->all_list->get_service_export_data($year,$month,$limit,$page_list);
			
			$json['page'] = $page;
			$json['page_list'] = $page_list;			
		}else{
			redirect('/');
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));	
	}

	function service_export_errorlist(){
		if($this->session->userdata('is_login')){						
			$page = $this->input->post('page');
			$year = $this->input->post('year');			
			$month = $this->input->post('month');
			
			$json['year'] = $year;								
			$page_list = 20;			
			$limit = ($page - 1) * $page_list;					

			$totalcount = $this->all_list->get_service_error_count($month,$year);
			$json['data_count'] = $totalcount->error_count;
			$json['data_list'] = $this->all_list->get_service_error_list($month,$year,$limit,$page_list);
			
			$json['page'] = $page;
			$json['page_list'] = $page_list;			
		}else{
			redirect('/');
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));	

	}

	function all_export(){ // 0
		if($this->session->userdata('is_login')){						
			$month = $this->input->get_post('month');			
			$year = $this->input->get_post('year');			

			$query = $this->db->query("SELECT prompt,ex_editing
					FROM adjust_data
					WHERE sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 00:00:00'
					AND essay_id !=0						
					and type != 'musedata'				
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

}
?>