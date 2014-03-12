<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Done extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');	
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
	}

	public function index()
	{			
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'mydone';
			$this->load->view('head',$data);					

			//$list['list'] = $this->all_list->essayList($usr_id);
			
			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;			
			$data['pj_id'] = '';
			$data['editor_id'] = $this->session->userdata('id');			
			
			//$data['list'] = $this->all_list->doneList($usr_id);
			// $data['memName'] = $this->all_list->memName($usr_id);
			// $data['cate'] = 'edi_all';
			// $data['editor_id'] = $usr_id;
			// $data['pj_id'] = '';

			$this->load->view('donelist_view',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}		
	}	

	public function done(){
		if($this->session->userdata('is_login')){			
			$usr_id = $this->session->userdata('id');
			$data['cate'] = 'mydone';
			$this->load->view('head',$data);			
			//$data['list'] = $this->all_list->doneList($usr_id);
			$data['memName'] = $this->all_list->memName($usr_id);
			$data['cate'] = 'edi_all';
			$data['editor_id'] = $usr_id;
			$data['pj_id'] = '';

			$this->load->view('donelist_view',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}	
	}	

	public function donelist(){
		if($this->session->userdata('is_login')){			
			$usr_id = $this->input->post('usr_id');
			$json['cate'] = 'mydone';			
			$json['done_list'] = $this->all_list->doneList($usr_id);
			$json['memName'] = $this->all_list->memName($usr_id);			
		}else{
			redirect('/');
		}	
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}	

	public function edi_pj_donelist(){
		if($this->session->userdata('is_login')){			
			$usr_id = $this->input->post('usr_id');
			$pj_id = $this->input->post('pj_id');

			$json['done_list'] = $this->all_list->eid_pj_doneList($usr_id,$pj_id);
			
		}else{
			redirect('/');
		}	
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}	

	public function other_donelist(){
		if($this->session->userdata('is_login')){			
			$usr_id = $this->session->userdata('id');			
			$last_num = $this->input->post('last_num');
			
			$json['other_done_list'] = $this->all_list->other_donelist($usr_id,$last_num);			
			$json['last_num'] = $last_num;

		}else{
			redirect('/');
		}	
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function other_edi_pj_donelist(){
		if($this->session->userdata('is_login')){			
			$usr_id = $this->input->post('edi_id');
			$last_num = $this->input->post('last_num');
			$pj_id = $this->input->post('pj_id');
			
			$json['other_done_list'] = $this->all_list->edi_other_doneList($usr_id,$pj_id,$last_num);			
			$json['last_num'] = $last_num;

		}else{
			redirect('/');
		}	
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function status_donelist(){
		if($this->session->userdata('is_login')){			
			$edi_id = $this->input->post('edi_id');			
			$last_num = $this->input->post('last_num');
			
			$json['other_done_list'] = $this->all_list->other_donelist($edi_id,$last_num);			
			$json['last_num'] = $last_num;

		}else{
			redirect('/');
		}	
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}	
}
?>