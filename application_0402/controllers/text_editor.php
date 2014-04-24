<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once 'musedata/project.php';
require_once 'errorchk.php';

class Text_editor extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');		
	}

	public function todo($essay_id,$type,$pj_id){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'musedata';
			$this->load->view('head',$data);
			$datas = $this->all_list->getEssay($essay_id,$type);				
			$pjname = $this->all_list->getproject_name($pj_id);				

			$data['time'] = $datas->time;
			$data['title'] = str_replace('"', '', $datas->prompt);
			$writing = preg_replace("/[\n\r]/","<br>", $datas->raw_txt);
			$data['writing'] = str_replace('"', '', $writing);

			$convert = str_replace('"', '',$datas->raw_txt); 
			$convert = str_replace('“', '"',$convert);
			$convert = str_replace('”', '"',$convert);
			$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);

			$data['id'] = $datas->essay_id;
			$data['token'] = '';
			$data['kind'] = ''; // ex) toefl, essay, toeic
			$data['type'] = $datas->type;
			$data['conf'] = false;
			$data['submit'] = $datas->submit;
			$data['error_chk'] = $datas->chk;
			$classify = $this->session->userdata('classify');			
			
			$data['cate'] = 'todo';			
			$data['discuss'] = $datas->discuss;
			$data['pj_id'] = $pj_id;
			$data['pjname'] = $pjname->name;
			
			$this->load->view('editor',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function tbd($essay_id,$type,$pj_id){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'musedata';
			$this->load->view('head',$data);
			$usr_id = $this->session->userdata('id');
			
			$datas = $this->all_list->draftEssay($usr_id,$essay_id,$type);
			$pjname = $this->all_list->getproject_name($pj_id);				
			$scoring = $datas->scoring;
			$scoring = json_decode($scoring,true);
			//print_r($scoring);
			$data['time'] = $datas->time;
			$data['ibc'] = $scoring['ibc'];
			$data['thesis'] = $scoring['thesis'];
			$data['topic'] = $scoring['topic'];
			$data['coherence'] = $scoring['coherence'];
			$data['transition'] = $scoring['transition'];
			$data['mi'] = $scoring['mi'];
			$data['si'] = $scoring['si'];
			$data['style'] = $scoring['style'];
			$data['usage'] = $scoring['usage'];

			$data['title'] = str_replace('"', '', $datas->prompt);
						
			$data['edit_writing'] = str_replace('"','',$datas->editing);
			$data['raw_writing'] = $datas->raw_txt;
			
			$raw_sen = $datas->raw_txt;
			$convert = str_replace('"', '', $raw_sen); 			
			$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);
			$data['discuss'] = $datas->discuss;
			$data['writing'] = '';
			$data['id'] = $datas->id;
			$data['token'] = '';
			$data['kind'] = ''; // ex) toefl, essay, toeic			
			
			$tagging = str_replace('"','',preg_replace("/[\n\r]/","<br>", $datas->tagging));
			
			$data['tagging'] = $tagging;
			$data['critique'] = $datas->critique;
			$data['type'] = $type;
			$data['conf'] = false;
			$data['error_chk'] = $datas->chk;
			$data['cate'] = 'tbd';
			$data['submit'] = $datas->submit;
			$data['draft'] = $datas->draft;
			$data['pjname'] = $pjname->name;
			$data['pj_id'] = $pj_id;			
			
			$this->load->view('editor',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function draft($essay_id,$type,$pj_id){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'musedata';
			$this->load->view('head',$data);
			$usr_id = $this->session->userdata('id');
			
			$datas = $this->all_list->draftEssay($usr_id,$essay_id,$type);
			$pjname = $this->all_list->getproject_name($pj_id);				
			$scoring = $datas->scoring;
			$scoring = json_decode($scoring,true);
			//print_r($scoring);
			$data['time'] = $datas->time;
			$data['ibc'] = $scoring['ibc'];
			$data['thesis'] = $scoring['thesis'];
			$data['topic'] = $scoring['topic'];
			$data['coherence'] = $scoring['coherence'];
			$data['transition'] = $scoring['transition'];
			$data['mi'] = $scoring['mi'];
			$data['si'] = $scoring['si'];
			$data['style'] = $scoring['style'];
			$data['usage'] = $scoring['usage'];

			$data['title'] = str_replace('"', '', $datas->prompt);
						
			$data['edit_writing'] = str_replace('"','',$datas->editing);		

			$convert = str_replace('"', '',$datas->raw_txt); 
			$convert = str_replace('“', '"',$convert);
			$convert = str_replace('”', '"',$convert);
			$data['raw_writing'] = $convert;
			$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);
			$data['discuss'] = $datas->discuss;
			$data['writing'] = '';
			$data['id'] = $datas->id;
			$data['token'] = '';
			$data['kind'] = ''; // ex) toefl, essay, toeic			
			
			$tagging = str_replace('"','',preg_replace("/[\n\r]/","<br>", $datas->tagging));
			
			$data['tagging'] = $tagging;
			$data['critique'] = $datas->critique;
			$data['type'] = $type;
			$data['conf'] = false;
			$data['error_chk'] = $datas->chk;
			$data['cate'] = 'draft';
			$data['submit'] = $datas->submit;
			$data['draft'] = $datas->draft;
			$data['pjname'] = $pjname->name;
			$data['pj_id'] = $pj_id;			
			
			$this->load->view('editor',$data);		
			$this->load->view('footer');		
		}else{
			redirect('/');
		}
	}

	// Submit
	public function completed($essay_id,$type,$pj_id){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'musedata';
			$this->load->view('head',$data);		
			$usr_id = $this->session->userdata('id');

			$rows = $this->all_list->get_completed($essay_id,$type);			
			$pjname = $this->all_list->getproject_name($pj_id);				

			$data['time'] = $rows->time;
			$scoring = $rows->scoring;
			$scoring = json_decode($scoring,true);
			//print_r($scoring);
			$data['ibc'] = $scoring['ibc'];
			$data['thesis'] = $scoring['thesis'];
			$data['topic'] = $scoring['topic'];
			$data['coherence'] = $scoring['coherence'];
			$data['transition'] = $scoring['transition'];
			$data['mi'] = $scoring['mi'];
			$data['si'] = $scoring['si'];
			$data['style'] = $scoring['style'];
			$data['usage'] = $scoring['usage'];
			
			$data['title'] = str_replace('"', '', $rows->prompt);
			$data['edit_writing'] = $rows->editing;	

			$raw_sen = $rows->raw_txt;
			$convert = str_replace('"', '', $raw_sen); 
			$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);
			
			$data['tagging'] = preg_replace("/[\n\r]/","<br>", $rows->tagging);
			$data['writing'] = '';
			$data['critique'] = $rows->critique;
			$data['id'] = $rows->id;
			$data['token'] = '';
			$data['kind'] = ''; // ex) toefl, essay, toeic
			$data['type'] = $rows->type;
			$data['conf'] = false;
			$data['discuss'] = $rows->discuss;
			$data['error_chk'] = $rows->chk;
			$data['submit'] = $rows->submit;
			$data['draft'] = $rows->draft;
			$data['pjname'] = $pjname->name;
			$data['pj_id'] = $pj_id;
			$data['cate'] = 'com';
			
			$this->load->view('editor',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function draft_save(){ //<br>을 다시 \r\n으로 // 0
		if($this->session->userdata('is_login')){
			$usr_id = $this->session->userdata('id');
			$essay_id = $this->input->POST('essay_id');			
			$type = $this->input->POST('type');			
			$time = $this->input->post('time');
			$editing = mysql_real_escape_string(trim($this->input->POST('editing')));
			$critique = mysql_real_escape_string(trim($this->input->POST('critique')));
			$tagging = mysql_real_escape_string(trim($this->input->POST('tagging')));

			$json['ibc'] = $this->input->POST('ibc');			
			$json['thesis'] = $this->input->POST('thesis');			
			$json['topic'] = $this->input->POST('topic');			
			$json['coherence'] = $this->input->POST('coherence');			
			$json['transition'] = $this->input->POST('transition');			
			$json['mi'] = $this->input->POST('mi');			
			$json['si'] = $this->input->POST('si');			
			$json['style'] = $this->input->POST('style');			
			$json['usage'] = $this->input->POST('usage');			
			$scoring = json_encode($json);	

			$result = $this->all_list->draft($usr_id,$essay_id,$editing,$critique,$tagging,$type,$scoring,$time);

			$json['status'] = $result;			
		}else{
			redirect('/');
		}		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));	
	}

	public function submit(){
		if($this->session->userdata('is_login')){			
			$usr_id = $this->session->userdata('id');

			$pj_id = $this->input->POST('pj_id');			
			$essay_id = $this->input->POST('essay_id');			
			$type = $this->input->POST('type');			
			$time = $this->input->post('time');
			$editing = mysql_real_escape_string(trim($this->input->POST('editing')));
			$critique = mysql_real_escape_string(trim($this->input->POST('critique')));
			$tagging = mysql_real_escape_string(trim($this->input->POST('tagging')));			

			$json['ibc'] = $this->input->POST('ibc');			
			$json['thesis'] = $this->input->POST('thesis');			
			$json['topic'] = $this->input->POST('topic');			
			$json['coherence'] = $this->input->POST('coherence');			
			$json['transition'] = $this->input->POST('transition');			
			$json['mi'] = $this->input->POST('mi');			
			$json['si'] = $this->input->POST('si');			
			$json['style'] = $this->input->POST('style');			
			$json['usage'] = $this->input->POST('usage');			
			$scoring = json_encode($json);				

			$result = $this->all_list->submit($usr_id,$essay_id,$editing,$critique,$tagging,$type,$scoring,$time);
			if($result){	
				$errorchk_class = new Errorchk;
				$error_chk = $errorchk_class->error_chk('once',$essay_id,$type);
				$json['error_chk'] = $error_chk;	
				if($error_chk == 'true'){ 
					$json['status'] = $error_chk;
				}else{ // false 라면 DB error_count 에 1을 증가 시킨다.
					$error_count_up = $this->all_list->error_count_up($usr_id,$pj_id);
					//$json['status'] = $error_count_up;						
					$json['status'] = true;						
				}
			}else{
				$json['status'] = $result;	
			}			
			//$json['status'] = $result;	
			
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));	
	}

/* ======================================================================================================================== 
   Admin	*/ 

	public function comp($essay_id,$type){
		if($this->session->userdata('is_login')){				
			$head['cate'] = 'musedata';		
			$this->load->view('head',$head);							
			
			$rows = $this->all_list->get_admin_comp($essay_id,$type);
			
			if($rows == false){
				$this->load->view('404',$data);															
			}else{
				$data['time'] = $rows->time;
				$scoring = $rows->scoring;
				$scoring = json_decode($scoring,true);
				//print_r($scoring);
				$data['ibc'] = $scoring['ibc'];
				$data['thesis'] = $scoring['thesis'];
				$data['topic'] = $scoring['topic'];
				$data['coherence'] = $scoring['coherence'];
				$data['transition'] = $scoring['transition'];
				$data['mi'] = $scoring['mi'];
				$data['si'] = $scoring['si'];
				$data['style'] = $scoring['style'];
				$data['usage'] = $scoring['usage'];
				
				$data['title'] = str_replace('"', '', $rows->prompt);
				$data['edit_writing'] = str_replace('"','',$rows->editing);
				//$data['raw_writing'] = $rows->raw_txt;				
				$convert = str_replace('"', '',$rows->raw_txt);
				$convert = str_replace('’', "'",$convert);
				$convert = str_replace('“', '"',$convert);
				$convert = str_replace('”', '"',$convert);
				$data['raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);			
				$data['word_count'] = $rows->word_count;
				$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);			
				
				$tagging = str_replace('"','',preg_replace("/[\n\r]/","<br>", $rows->tagging));			
				$data['tagging'] = $tagging;
				
				$data['writing'] = '';
				$data['critique'] = str_replace('"','',$rows->critique);
				$data['id'] = $rows->id;
				$data['token'] = '';
				$data['kind'] = ''; // ex) toefl, essay, toeic
				$data['type'] = $rows->type;
				$data['conf'] = false;
				$data['pj_id'] = $rows->pj_id;
				$data['time'] = $rows->time;
				$data['cate'] = 'admin_export';
				//$data['cate'] = $cate;
				$data['essay_id'] = $essay_id;
				$data['usr_id'] = $rows->usr_id;			
				
				$this->load->view('admin_editor',$data);	

				//$this->load->view('service_view/service_editor',$data);										
			}
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function service_comp($classify,$essay_id,$type,$month,$year){
		if($this->session->userdata('is_login')){				
			$head['cate'] = 'service';		
			$this->load->view('head',$head);							
			
			$rows = $this->all_list->get_admin_comp($essay_id,$type);

			switch ($month) {
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
			$data['str_month'] = $str_month;
			$data['month'] = $month;
			$data['year'] = $year;
			
			if($rows == false){
				$this->load->view('404',$data);															
			}else{
				$data['time'] = $rows->time;
				$scoring = $rows->scoring;
				$scoring = json_decode($scoring,true);
				//print_r($scoring);
				$data['ibc'] = $scoring['ibc'];
				$data['thesis'] = $scoring['thesis'];
				$data['topic'] = $scoring['topic'];
				$data['coherence'] = $scoring['coherence'];
				$data['transition'] = $scoring['transition'];
				$data['mi'] = $scoring['mi'];
				$data['si'] = $scoring['si'];
				$data['style'] = $scoring['style'];
				$data['usage'] = $scoring['usage'];
				
				$data['title'] = str_replace('"', '', $rows->prompt);
				$data['edit_writing'] = str_replace('"','',$rows->editing);
				//$data['raw_writing'] = $rows->raw_txt;

				$convert = str_replace('"', '',$rows->raw_txt); 
				$convert = str_replace('“', '"',$convert);
				$convert = str_replace('”', '"',$convert);
				$data['raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);			
				$data['word_count'] = $rows->word_count;
				$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);			
				
				$tagging = str_replace('"','',preg_replace("/[\n\r]/","<br>", $rows->tagging));			
				$data['tagging'] = $tagging;
				
				$data['writing'] = '';
				$data['critique'] = str_replace('"','',$rows->critique);
				$data['id'] = $rows->id;
				$data['token'] = '';
				$data['kind'] = ''; // ex) toefl, essay, toeic
				$data['type'] = $rows->type;
				$data['conf'] = false;
				$data['pj_id'] = $rows->pj_id;
				$data['time'] = $rows->time;
				$data['cate'] = $type;
				$data['classify'] = $classify;
				$data['essay_id'] = $essay_id;
				$data['usr_id'] = $rows->usr_id;			

				$this->load->view('service_view/service_editor',$data);										
			}
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function essays($cate,$essay_id,$type){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'musedata';
			$this->load->view('head',$data);					
			
			$rows = $this->all_list->get_admin_comp($essay_id,$type);

			$data['time'] = $rows->time;
			$scoring = $rows->scoring;
			$scoring = json_decode($scoring,true);
			//print_r($scoring);
			$data['ibc'] = $scoring['ibc'];
			$data['thesis'] = $scoring['thesis'];
			$data['topic'] = $scoring['topic'];
			$data['coherence'] = $scoring['coherence'];
			$data['transition'] = $scoring['transition'];
			$data['mi'] = $scoring['mi'];
			$data['si'] = $scoring['si'];
			$data['style'] = $scoring['style'];
			$data['usage'] = $scoring['usage'];
			
			$data['title'] = str_replace('"', '', $rows->prompt);
			$data['edit_writing'] = str_replace('"','',$rows->editing);
			//$data['raw_writing'] = $rows->raw_txt;

			$convert = str_replace('"', '',$rows->raw_txt); 
			$convert = str_replace('“', '"',$convert);
			$convert = str_replace('”', '"',$convert);
			$data['raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);			
			$data['word_count'] = $rows->word_count;
			$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);			
			
			$tagging = str_replace('"','',preg_replace("/[\n\r]/","<br>", $rows->tagging));			
			$data['tagging'] = $tagging;
			
			$data['writing'] = '';
			$data['critique'] = str_replace('"','',$rows->critique);
			$data['id'] = $rows->id;
			$data['token'] = '';
			$data['kind'] = ''; // ex) toefl, essay, toeic
			$data['type'] = $rows->type;
			$data['conf'] = false;
			$data['pj_id'] = $rows->pj_id;
			$data['time'] = $rows->time;
			$data['cate'] = $cate;
			$data['essay_id'] = $essay_id;			
			$data['usr_id'] = $rows->usr_id;			
			
			$this->load->view('admin_editor',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function admin_draft_save(){ //<br>을 다시 \r\n으로 // 0
		if($this->session->userdata('is_login')){			
			$table_id = $this->input->POST('essay_id');
			$time = $this->input->post('time');
			$editing = mysql_real_escape_string(trim($this->input->POST('editing')));
			$critique = mysql_real_escape_string(trim($this->input->POST('critique')));
			$tagging = mysql_real_escape_string(trim($this->input->POST('tagging')));

			$type = $this->input->POST('type');			
			$json['ibc'] = $this->input->POST('ibc');			
			$json['thesis'] = $this->input->POST('thesis');			
			$json['topic'] = $this->input->POST('topic');			
			$json['coherence'] = $this->input->POST('coherence');			
			$json['transition'] = $this->input->POST('transition');			
			$json['mi'] = $this->input->POST('mi');			
			$json['si'] = $this->input->POST('si');			
			$json['style'] = $this->input->POST('style');			
			$json['usage'] = $this->input->POST('usage');			
			$scoring = json_encode($json);	
			$time = $this->input->post('time');

			$result = $this->all_list->admin_tbd_draft($table_id,$editing,$critique,$tagging,$type,$scoring,$time);

			$json['status'] = $result;			
		}else{
			redirect('/');
		}		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));	
	}

	public function admin_submit(){
		if($this->session->userdata('is_login')){			
			$usr_id = $this->session->userdata('id');

			$table_id = $this->input->POST('essay_id');			
			$type = $this->input->POST('type');			
			$time = $this->input->post('time');
			$editing = mysql_real_escape_string(trim($this->input->POST('editing')));
			$critique = mysql_real_escape_string(trim($this->input->POST('critique')));
			$tagging = mysql_real_escape_string(trim($this->input->POST('tagging')));			

			$json['ibc'] = $this->input->POST('ibc');			
			$json['thesis'] = $this->input->POST('thesis');			
			$json['topic'] = $this->input->POST('topic');			
			$json['coherence'] = $this->input->POST('coherence');			
			$json['transition'] = $this->input->POST('transition');			
			$json['mi'] = $this->input->POST('mi');			
			$json['si'] = $this->input->POST('si');			
			$json['style'] = $this->input->POST('style');			
			$json['usage'] = $this->input->POST('usage');			
			$scoring = json_encode($json);	
			
			$result = $this->all_list->admin_tbd_submit($usr_id,$table_id,$editing,$critique,$tagging,$type,$scoring,$time);

			$json['status'] = $result;
			$this->output->set_content_type('application/json')->set_output(json_encode($json));	
		}else{
			redirect('/');
		}
	}

	public function editsubmit(){ //0
		if($this->session->userdata('is_login')){			
			$usr_id = $this->session->userdata('id');

			$essay_id = $this->input->POST('essay_id');			
			$type = $this->input->POST('type');			
			$editing = mysql_real_escape_string(trim($this->input->POST('editing')));
			$critique = mysql_real_escape_string(trim($this->input->POST('critique')));
			$tagging = mysql_real_escape_string(trim($this->input->POST('tagging')));

			$json['ibc'] = $this->input->POST('ibc');			
			$json['thesis'] = $this->input->POST('thesis');			
			$json['topic'] = $this->input->POST('topic');			
			$json['coherence'] = $this->input->POST('coherence');			
			$json['transition'] = $this->input->POST('transition');			
			$json['mi'] = $this->input->POST('mi');			
			$json['si'] = $this->input->POST('si');			
			$json['style'] = $this->input->POST('style');			
			$json['usage'] = $this->input->POST('usage');			
			$scoring = json_encode($json);	
			
			$result = $this->all_list->editsubmit($usr_id,$essay_id,$editing,$critique,$tagging,$type,$scoring);

			$json['status'] = $result;
			$this->output->set_content_type('application/json')->set_output(json_encode($json));	
		}else{
			redirect('/');
		}
	}

	public function error($essay_id,$type,$pj_id){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'musedata';
			$this->load->view('head',$data);		
			//$usr_id = $this->session->userdata('id');

			$rows = $this->all_list->get_error_Essay($essay_id,$type);			
			$pjname = $this->all_list->getproject_name($pj_id);				

			$data['time'] = $rows->time;
			$scoring = $rows->scoring;
			$scoring = json_decode($scoring,true);
			//print_r($scoring);
			$data['ibc'] = $scoring['ibc'];
			$data['thesis'] = $scoring['thesis'];
			$data['topic'] = $scoring['topic'];
			$data['coherence'] = $scoring['coherence'];
			$data['transition'] = $scoring['transition'];
			$data['mi'] = $scoring['mi'];
			$data['si'] = $scoring['si'];
			$data['style'] = $scoring['style'];
			$data['usage'] = $scoring['usage'];
			
			$data['title'] = str_replace('"', '', $rows->prompt);
			$data['edit_writing'] = $rows->editing;				

			$convert = str_replace('"', '',$rows->raw_txt); 
			$convert = str_replace('“', '"',$convert);
			$convert = str_replace('”', '"',$convert);
			$data['raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);			
			$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);
			
			$data['tagging'] = preg_replace("/[\n\r]/","<br>", $rows->tagging);
			$data['writing'] = '';
			$data['critique'] = $rows->critique;
			$data['id'] = $rows->id;
			$data['token'] = '';
			$data['kind'] = ''; // ex) toefl, essay, toeic
			$data['type'] = $rows->type;
			$data['conf'] = false;
			$data['discuss'] = $rows->discuss;
			$data['error_chk'] = $rows->chk;
			$data['submit'] = $rows->submit;
			$data['draft'] = $rows->draft;
			$data['pjname'] = $pjname->name;
			$data['pj_id'] = $pj_id;
			$data['cate'] = 'error';
			$data['essay_id'] = $essay_id;
			$data['usr_id'] = $rows->usr_id;
			$data['word_count'] = $rows->word_count;
			
			$this->load->view('admin_editor',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}

	}

	

	// public function alldone($id){
	// 	if($this->session->userdata('is_login')){
	// 		$data['cate'] = 'admin';
	// 		$this->load->view('head',$data);			
			
	// 		$rows = $this->all_list->alldone_essay($id);

	// 		$scoring = $rows->scoring;
	// 		$scoring = json_decode($scoring,true);
	// 		//print_r($scoring);
	// 		$data['ibc'] = $scoring['ibc'];
	// 		$data['thesis'] = $scoring['thesis'];
	// 		$data['topic'] = $scoring['topic'];
	// 		$data['coherence'] = $scoring['coherence'];
	// 		$data['transition'] = $scoring['transition'];
	// 		$data['mi'] = $scoring['mi'];
	// 		$data['si'] = $scoring['si'];
	// 		$data['style'] = $scoring['style'];
	// 		$data['usage'] = $scoring['usage'];
			
	// 		$data['title'] = str_replace('"', '', $rows->prompt);
	// 		$data['edit_writing'] = str_replace('"','',$rows->editing);
	// 		$data['raw_writing'] = $rows->raw_txt;
	// 		$data['re_raw_writing'] = '';			
	// 		$data['tagging'] = str_replace('"','',preg_replace("/[\n\r]/","<br>", $rows->tagging));
	// 		$data['writing'] = '';
	// 		$data['critique'] = $rows->critique;
	// 		$data['id'] = $rows->id;
	// 		$data['token'] = '';
	// 		$data['kind'] = ''; // ex) toefl, essay, toeic
	// 		$data['type'] = $rows->type;
	// 		$data['conf'] = false;
	// 		$data['cate'] = 'admin';
			
	// 		$this->load->view('editor',$data);		
	// 		$this->load->view('footer');					
	// 	}else{
	// 		redirect('/');
	// 	}
	// }

	// public function all_history($id){
	// 	if($this->session->userdata('is_login')){
	// 		$data['cate'] = 'admin';
	// 		$this->load->view('head',$data);			
			
	// 		$rows = $this->all_list->alldone_essay($id);

	// 		$scoring = $rows->scoring;
	// 		$scoring = json_decode($scoring,true);
	// 		//print_r($scoring);
	// 		$data['ibc'] = $scoring['ibc'];
	// 		$data['thesis'] = $scoring['thesis'];
	// 		$data['topic'] = $scoring['topic'];
	// 		$data['coherence'] = $scoring['coherence'];
	// 		$data['transition'] = $scoring['transition'];
	// 		$data['mi'] = $scoring['mi'];
	// 		$data['si'] = $scoring['si'];
	// 		$data['style'] = $scoring['style'];
	// 		$data['usage'] = $scoring['usage'];
			
	// 		$data['title'] = str_replace('"', '', $rows->prompt);
	// 		$data['edit_writing'] = $rows->editing;
	// 		$data['raw_writing'] = $rows->raw_txt;
	// 		$data['re_raw_writing'] = '';			
	// 		$data['tagging'] = preg_replace("/[\n\r]/","<br>", $rows->tagging);
	// 		$data['writing'] = '';
	// 		$data['critique'] = $rows->critique;
	// 		$data['id'] = $rows->id;
	// 		$data['token'] = '';
	// 		$data['kind'] = ''; // ex) toefl, essay, toeic
	// 		$data['type'] = $rows->type;
	// 		$data['conf'] = false;
	// 		$data['cate'] = 'admin';
			
	// 		$this->load->view('editor',$data);		
	// 		$this->load->view('footer');					
	// 	}else{
	// 		redirect('/');
	// 	}
	// }	
}
?>
