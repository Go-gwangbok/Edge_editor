<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Text extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');		
	}

	public function todo($essay_id,$type){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'todolist';
			$this->load->view('head',$data);
			//$usr_id = $this->session->userdata('id');

			$datas = $this->all_list->getEssay($essay_id,$type);				

			$data['time'] = $datas->time;
			$data['title'] = str_replace('"', '', $datas->prompt);
			$writing = preg_replace("/[\n\r]/","<br>", $datas->raw_txt);
			$data['writing'] = str_replace('"', '', $writing);

			$raw_sen = $datas->raw_txt;
			$convert = str_replace('"', '', $raw_sen); 
			$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);

			$data['id'] = $datas->essay_id;
			$data['token'] = '';
			$data['kind'] = ''; // ex) toefl, essay, toeic
			$data['type'] = $datas->type;
			$data['conf'] = false;
			$data['error_chk'] = $datas->chk;
			$classify = $this->session->userdata('classify');
			
			$data['cate'] = 'todo';
			
			$data['discuss'] = $datas->discuss;
			$this->load->view('editor',$data);		
			$this->load->view('footer');					

			// $classify = $this->session->userdata('classify');
			// if($classify == 1){
			// 	$data['cate'] = 'todolist';
			// 	$this->load->view('head',$data);
			// 	$usr_id = $this->session->userdata('id');

			// 	$datas = $this->all_list->getEssay($usr_id,$essay_id,$type);				
			// 	$data['time'] = $datas->time;
			// 	$data['title'] = str_replace('"', '', $datas->prompt);
			// 	$writing = preg_replace("/[\n\r]/","<br>", $datas->raw_txt);
			// 	$data['writing'] = str_replace('"', '', $writing);

			// 	$raw_sen = $datas->raw_txt;
			// 	$convert = str_replace('"', '', $raw_sen); 
			// 	$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);

			// 	$data['id'] = $datas->essay_id;
			// 	$data['token'] = '';
			// 	$data['kind'] = ''; // ex) toefl, essay, toeic
			// 	$data['type'] = $datas->type;
			// 	$data['conf'] = false;
			// 	$data['cate'] = 'todo';
			// 	$this->load->view('editor',$data);		
			// 	$this->load->view('footer');					
			// }else{				
			// 	$this->load->view('head');
			// 	//echo 'instruct';
			// 	$data['cate'] = 'status';
			// 	$data['list'] = $this->all_list->new_getList();
			// 	$data['count'] = $this->all_list->count();

			// 	$this->load->view('essay_list',$data);		
			// 	$this->load->view('footer');					
			// }	

		}else{
			redirect('/');
		}
	}

	public function draft($essay_id,$type){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'draft';
			$this->load->view('head',$data);
			$usr_id = $this->session->userdata('id');
			
			$datas = $this->all_list->draftEssay($usr_id,$essay_id,$type);
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
			//$data['tagging'] = str_replace('"','',preg_replace("/[\n\r]/","<br>", $datas->tagging));
			$tagging = str_replace('"','',preg_replace("/[\n\r]/","<br>", $datas->tagging));
			// $patterns = array("(<IN>)","(<TR>)","(<TS>)","(<MO1>)","(<MO2>)","(<BO1>)","(<BO2>)","(<SI1>)","(<SI2>)","(<EX>)","(<CO>)","(<MI1>)","(<MI2>)",
			// 					"(</IN>)","(</TR>)","(</TS>)","(</MO1>)","(</MO2>)","(</BO1>)","(</BO2>)","(</SI1>)","(</SI2>)","(</EX>)","(</CO>)","(</MI1>)","(</MI2>)");
			// $replace = array("&lt;IN&gt;","&lt;TR&gt;","&lt;TS&gt;","&lt;MO1&gt;","&lt;MO2&gt;","&lt;BO1&gt;","&lt;BO2&gt;","&lt;SI1&gt;","&lt;SI2&gt;","&lt;EX&gt;","&lt;CO&gt;","&lt;MI1&gt;","&lt;MI2&gt;",
			// 					"&lt;/IN&gt;","&lt;/TR&gt;","&lt;/TS&gt;","&lt;/MO1&gt;","&lt;/MO2&gt;","&lt;/BO1&gt;","&lt;/BO2&gt;","&lt;/SI1&gt;","&lt;/SI2&gt;","&lt;/EX&gt;","&lt;/CO&gt;","&lt;/MI1&gt;","&lt;/MI2&gt;");

			// $data['tagging'] = preg_replace($patterns, $replace, $tagging);
			$data['tagging'] = $tagging;
			$data['critique'] = $datas->critique;
			$data['type'] = $type;
			$data['conf'] = false;
			$data['error_chk'] = $datas->chk;
			$data['cate'] = 'draft';
			$data['submit'] = $datas->submit;
			$data['draft'] = $datas->draft;
			
			$this->load->view('editor',$data);		
			$this->load->view('footer');		
		}else{
			redirect('/');
		}
	}

	public function draft_save(){ //<br>을 다시 \r\n으로
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

			$json['status'] = $result;
			$this->output->set_content_type('application/json')->set_output(json_encode($json));	
		}else{
			redirect('/');
		}
	}

	public function edit($essay_id,$type){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'mydone';
			$this->load->view('head',$data);
			$usr_id = $this->session->userdata('id');
			
			$rows = $this->all_list->editEssay($usr_id,$essay_id,$type);

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
			
			$this->load->view('editor',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function admin_eachdone($editor_id,$essay_id,$type){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'admin';
			$this->load->view('head',$data);			
			
			$rows = $this->all_list->editEssay($editor_id,$essay_id,$type);

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
			$data['raw_writing'] = $rows->raw_txt;
			$data['re_raw_writing'] = '';			
			
			$tagging = str_replace('"','',preg_replace("/[\n\r]/","<br>", $rows->tagging));
			// $patterns = array("(<IN>)","(<TR>)","(<TS>)","(<BO1>)","(<BO2>)","(<BO3>)","(<BO4>)","(<SI1>)","(<SI2>)","(<SI3>)","(<SI4>)","(<EX>)","(<CO>)","(<MI1>)","(<MI2>)","(<MI3>)","(<MI4>)",
			// 					"(</IN>)","(</TR>)","(</TS>)","(</BO1>)","(</BO2>)","(</BO3>)","(</BO4>)","(</SI1>)","(</SI2>)","(</SI3>)","(</SI4>)","(</EX>)","(</CO>)","(</MI1>)","(</MI2>)","(</MI3>)","(</MI4>)");
			// $replace = array("<span class='in' tag='IN'>&lt;IN&gt;","&lt;TR&gt;","&lt;TS&gt;","&lt;BO1&gt;","&lt;BO2&gt;","&lt;BO3&gt;","&lt;BO4&gt;","&lt;SI1&gt;","&lt;SI2&gt;","&lt;SI3&gt;","&lt;SI4&gt;","&lt;EX&gt;","&lt;CO&gt;","&lt;MI1&gt;","&lt;MI2&gt;","&lt;MI3&gt;","&lt;MI4&gt;",
			// 					"&lt;/IN&gt;</span>","&lt;/TR&gt;","&lt;/TS&gt;","&lt;/BO1&gt;","&lt;/BO2&gt;","&lt;/BO3&gt;","&lt;/BO4&gt;","&lt;/SI1&gt;","&lt;/SI2&gt;","&lt;/SI3&gt;","&lt;/SI4&gt;","&lt;/EX&gt;","&lt;/CO&gt;","&lt;/MI1&gt;","&lt;/MI2&gt;","&lt;/MI3&gt;","&lt;/MI4&gt;");

			// $data['tagging'] = preg_replace($patterns, $replace, $tagging);
			$data['tagging'] = $tagging;
			
			$data['writing'] = '';
			$data['critique'] = str_replace('"','',$rows->critique);
			$data['id'] = $rows->id;
			$data['token'] = '';
			$data['kind'] = ''; // ex) toefl, essay, toeic
			$data['type'] = $rows->type;
			$data['conf'] = false;
			$data['cate'] = 'admin';
			
			$this->load->view('editor',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function editsubmit(){
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

	public function adjust(){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'writing';
			$this->load->view('head',$data);

			$token = $this->input->post('token');
			$w_id = $this->input->post('w_id');
			$title = $this->input->post('title');
			$writing = $this->input->post('writing');
			$critique = $this->input->post('critique');		
			$kind = $this->input->post('kind');

			$data['title'] = str_replace('"', '', $title);
			$data['writing'] = str_replace('"', '', $writing);

			$convert = str_replace('"', '', $writing); 
			$data['re_raw_writing'] = preg_replace("/[\n\r]/","<br>", $convert);
			$data['token'] = $token;
			$data['id'] = $w_id;
			$data['critique'] = $critique;
			$data['kind'] = $kind;
			$data['type'] = '';
			$data['conf'] = true;

			$this->load->view('editor',$data);		
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
