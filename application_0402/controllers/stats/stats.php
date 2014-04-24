<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');								
	}

	public function get_data(){
		if($this->session->userdata('is_login')){			
			$pj_id = $this->input->post('pj_id');
			$json['data'] = $this->all_list->stats_data($pj_id);						
			
		}else{
			redirect('/');
		}	
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
}

