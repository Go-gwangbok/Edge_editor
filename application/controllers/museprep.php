<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'errorchk.php';
class Museprep extends CI_Controller {

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

	function view_essay($data_id){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'service';
			$this->load->view('head',$data);

			$service_name = "museprep";

			$service_id = $this->get_service_id();
			

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
				$editing = str_replace('’', "'",$editing);

				$editing = str_replace('“', '&quot',$editing);
				$editing = str_replace('”', '&quot',$editing);

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

			$temp = explode("|||", $data['title']);
			if (count($temp) == 2) {
				$data['question'] = $temp[0];
				$data['passage'] = $temp[1];
			} else {
				$data['question'] = $data['title'];
				$data['passage'] = "";
			}

			$data['service_name'] = $service_name;
			$data['service_id'] = $service_id;

			$this->load->view('/editor/writing_editor',$data);
			$this->load->view('footer');
		}else{
			redirect('/');
		}					
	}

	function get_service_id() {
		$service_name = "museprep";
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
		$premium['title'] = str_replace('"', '&quot;', $rows->prompt);
		$raw_txt = $this->br2nl(strip_tags($rows->raw_txt, '<p><br>'));
		$raw_txt = $this->p2nl($raw_txt);

		$convert = str_replace('"', '&quot;',$raw_txt); 
		$convert = str_replace('’', "'",$convert);
		/***
		$convert = str_replace('“', '"',$convert);
		$convert = str_replace('”', '"',$convert);
		***/
		$convert = str_replace('“', '&quot;',$convert);
		$convert = str_replace('”', '&quot;',$convert);
		//”
		//$convert = str_replace('”', '"',$convert);
		$premium['raw_writing'] = $convert;
		$premium['re_raw_writing'] = preg_replace("#(\\\r\\\n|\\\r|\\\n)#","<br>", $convert);

		$premium['done'] = str_replace('"', '&quot', $rows->done);

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
		$premium['reason'] = str_replace('"', '&quot;', $rows->reason);
		$filename = explode( ".", $rows->filename);
		$premium['filename'] = $filename[0];

		$premium['user_file'] = $rows->user_file;
		$premium['download_link'] = EDGE_WRITING_URL . "download/file/" . $rows->user_file;

		return $premium;
	}

	/*************************/

	function get_templet_ele($kind){
		return $this->all_list->get_templet_ele(2, $kind);
	}

	function score_pattern_replace($data_obj){
		$pattern = array('({)','(})','(")');
		$replace = array('','','');
		return preg_replace($pattern, $replace, $data_obj);			
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