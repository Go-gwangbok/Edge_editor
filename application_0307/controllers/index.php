<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

	function __construct(){
		parent::__construct();		
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');
	}

	public function index()
	{	
		$data['cate'] = '';
		$this->load->view('head',$data);

		$data['notice'] = $this->all_list->notice();
		$data['recent_notice'] = $this->all_list->recent_notice();
		$data['usr_list'] = $this->all_list->all_usr();
		$this->load->view('index',$data);		
		$this->load->view('footer');			
	}

	public function chart(){
		
		if($this->session->userdata('is_login')){
			if($this->session->userdata('classify') == 0){ //admin				
				$data['cate'] = 'index';
				$this->load->view('head',$data);
				$json['chart'] = $this->all_list->chart_num();		
				$json['new_editor'] = $this->all_list->conf_newEditor();		
				
			}else{ //editor
				$id = $this->session->userdata('id');
				$data['cate'] = 'index';
				$this->load->view('head',$data);
				$json['chart'] = $this->all_list->editor_chart_num($id);		
			}
		}		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));		
	}
}
?>