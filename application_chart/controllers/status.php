<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');
	}

	public function index()
	{			
		if($this->session->userdata('is_login')){
			$data['cate'] = 'status';
			$this->load->view('head',$data);
			
			$data['list'] = $this->all_list->status_list();
			$data['pj_id'] = '';

			$this->load->view('status_view',$data);		
			$this->load->view('footer');			
		}else{
			redirect('/');
		}		
	}

	public function done($usr_id){
		if($this->session->userdata('is_login')){						
			$cate['cate'] = 'status';
			$this->load->view('head',$cate);			
			//$data['cate'] = 'status';
			$data['list'] = $this->all_list->doneList($usr_id);
			//$data['memName'] = $this->all_list->memName($usr_id);
			$name = $this->all_list->memName($usr_id);
			$data['memName'] = $name->name;
			$data['editor_id'] = $usr_id;
			$data['cate'] = 'status';
			$data['pj_id'] = '';

			$this->load->view('donelist_view',$data);
			$this->load->view('footer');
		}else{
			redirect('/');
		}	
	}

	public function pj_done($pj_id,$usr_id){
		if($this->session->userdata('is_login')){						
			$cate['cate'] = 'status';
			$this->load->view('head',$cate);			
			
			$data['list'] = $this->all_list->pj_doneList($pj_id,$usr_id);
			//$data['memName'] = $this->all_list->memName($usr_id);
			$name = $this->all_list->memName($usr_id);
			$data['memName'] = $name->name;
			$data['editor_id'] = $usr_id;

			$this->load->view('donelist_view',$data);
			$this->load->view('footer');
		}else{
			redirect('/');
		}	
	}

	// public function all_done(){
	// 	if($this->session->userdata('is_login')){						
	// 		$cate['cate'] = 'status';
	// 		$this->load->view('head',$cate);			
	// 		//$data['cate'] = 'status';
	// 		$data['list'] = $this->all_list->all_done();			
	// 		$data['memName'] = '';
			
	// 		$data['cate'] = 'all';
	// 		$this->load->view('donelist_view',$data);
	// 		$this->load->view('footer');
	// 	}else{
	// 		redirect('/');
	// 	}	
	// }

	public function todo($usr_id){
		if($this->session->userdata('is_login')){
			$cate['cate'] = 'status';
			$this->load->view('head',$cate);			
			
			//$data['todo_list'] = $this->all_list->todoList($usr_id);
			$name = $this->all_list->memName($usr_id);
			$data['memName'] = $name->name;
			$data['editor_id'] = $usr_id;
			$data['pj_id'] = '';

			$this->load->view('todolist_view',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}	
	}

	public function todo_list(){
		if($this->session->userdata('is_login')){
			$edi_id = $this->input->post('edi_id');
			$cate = $this->input->post('cate');
			$pj_id = $this->input->post('pj_id');

			if($cate == 'edi_pj_todo'){
				$json['todo_list'] = $this->all_list->eid_pj_todoList($edi_id,$pj_id);
			}else{
				$json['todo_list'] = $this->all_list->todoList($edi_id);	
			}						
			$json['editor_id'] = $edi_id;			
		}else{
			redirect('/');
		}	
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
	
	public function other_todo_list(){
		if($this->session->userdata('is_login')){
			$last_num = $this->input->post('last_num');
			$usr_id = $this->input->post('usr_id');
			$pj_id = $this->input->post('pj_id');
			$cate = $this->input->post('cate');

			if($cate == 'edi_pj_todo'){
				$json['other_todo_list'] = $this->all_list->edi_other_todoList($usr_id,$pj_id,$last_num);				
			}else if($cate == 'pj_mem_history'){
				$json['other_history_list'] = $this->all_list->edi_other_todoList($usr_id,$pj_id,$last_num);				
			}else{
				$json['other_todo_list'] = $this->all_list->other_todoList($usr_id,$last_num);				
			}			
			
		}else{
			redirect('/');
		}	
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}


	
	public function pj_todo($pj_id,$usr_id){
		if($this->session->userdata('is_login')){
			$cate['cate'] = 'status';
			$this->load->view('head',$cate);			
			
			$data['todo_list'] = $this->all_list->pj_todoList($pj_id,$usr_id);
			//$data['memName'] = $this->all_list->memName($usr_id);
			$name = $this->all_list->memName($usr_id);
			$data['memName'] = $name->name;

			$this->load->view('todolist_view',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}	
	}

	// public function all_todo(){
	// 	if($this->session->userdata('is_login')){
	// 		$cate['cate'] = 'status';
	// 		$this->load->view('head',$cate);			
			
	// 		//$data['todo_list'] = $this->all_list->all_todo();
	// 		$data['memName'] = '';
	// 		$data['editor_id'] = '';
	// 		$data['pj_id'] = '';
	// 		$data['cate'] = 'all';

	// 		$this->load->view('todolist_view',$data);		
	// 		$this->load->view('footer');					
	// 	}else{
	// 		redirect('/');
	// 	}	
	// }

	// public function all_history(){
	// 	if($this->session->userdata('is_login')){			
	// 		$data['cate'] = 'status';
	// 		$this->load->view('head',$data);

	// 		$data['cate'] = 'all';
	// 		$data['mem_list'] = $this->all_list->all_history();
	// 		$data['memName'] = '';
	// 		$data['pj_id'] = '';

	// 		$this->load->view('essay_list',$data);		
	// 		$this->load->view('footer');					
	// 	}else{
	// 		redirect('/');
	// 	}
	// }	

	public function each_mem_list($usr_id){
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'status';
			$this->load->view('head',$data);

			$data['cate'] = 'mem_list';
			$data['mem_list'] = $this->all_list->memList($usr_id);
			$data['memName'] = $this->all_list->memName($usr_id);
			$data['editor_id'] = $usr_id;
			$data['pj_id'] = '';

			$this->load->view('essay_list',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function pj_each_mem_list($pj_id,$usr_id){
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'status';
			$this->load->view('head',$data);

			$data['cate'] = 'mem_list';
			$data['mem_list'] = $this->all_list->pj_memList($pj_id,$usr_id);
			$data['memName'] = $this->all_list->memName($usr_id);
			$data['editor_id'] = $usr_id;

			$this->load->view('essay_list',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function board($cate,$usr_id){
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'status';
			$this->load->view('head',$data);			

			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;			
			// $limit = ($page - 1) * $list;		

			//$data['cate'] = 'mem_list';
			//$data['mem_list'] = $this->all_list->memList($usr_id);
			//$data['memName'] = $this->all_list->memName($usr_id);
			//$data['editor_id'] = $usr_id;			
			$memname = $this->all_list->memName($usr_id);			
			$data['memName'] = $memname->name;
			$data['editor_id'] = $usr_id;
			
			$data['cate'] = $cate;

			if($this->session->userdata('classify') == 0) { //admin			
				$this->load->view('board',$data);		
			}else{ // Editor
				$this->load->view('essay_list',$data);			
			}
			
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function page_list(){
		if($this->session->userdata('is_login')){			
			$page = $this->input->post('page');
			$list = $this->input->post('list');			
			$editor_id = $this->input->post('editor_id');			
			$cate = $this->input->post('cate');

			$limit = ($page - 1) * $list;		
						
			if($cate == 'history'){
				$history_totalcount = $this->all_list->history_totalcount($editor_id); 
				$json['history_totalcount'] = $history_totalcount->count;				
				$json['history_list'] = $this->all_list->admin_history($editor_id,$page,$limit,$list); // history							
			}elseif($cate == 'todo'){
				$history_totalcount = $this->all_list->todo_totalcount($editor_id); 
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->admin_todo($editor_id,$page,$limit,$list); // history							
			}elseif($cate == 'done'){
				$history_totalcount = $this->all_list->comp_totalcount($editor_id); 
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->admin_done($editor_id,$page,$limit,$list); // history							
			}elseif($cate == 'edi_todo'){
				$history_totalcount = $this->all_list->edi_todo_totalcount($editor_id); 
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->edi_todo($editor_id,$page,$limit,$list); // history							
			}
			$json['page'] = $page;
			$json['classify'] = $this->session->userdata('classify');
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
}
?>