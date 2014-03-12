<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');	
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
	}

	public function id($pj_id,$usr_id,$page) {		
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'project';
			$this->load->view('head',$data);

			$data['list'] = $this->all_list->admin_pj_memList($pj_id,$usr_id); // history
			//$list_count = $this->all_list->editor_pj_list_count($pj_id,$usr_id);

			//$json['done_list'] = $history_list;			
						
			//$data['mem_list'] = $this->all_list->pj_memList($pj_id,$usr_id);
			$name = $this->all_list->usr_pj_name($usr_id,$pj_id);
			//$data['memName'] = $this->all_list->memName($usr_id);
			$data['memName'] = $name->usr_name;
			$data['pjName'] = $name->pj_name;
			$data['editor_id'] = $usr_id;
			$data['pj_id'] = $pj_id;
			$data['cate'] = 'page';

			if($this->session->userdata('classify') == 0) { //admin
				$this->load->view('history',$data);		
			}else{ // Editor
				$this->load->view('essay_list',$data);			
			}
			
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}
}	