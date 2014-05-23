<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');		
	}

	public function dis_proc(){	

		$cou = $this->input->POST('cou');
		$pj_id = $this->input->post('pj_id');
		$result = $this->all_list->distribute($cou,$pj_id);				
		$json['pj_id'] = $pj_id;
		$json['status'] = $result;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));		
	}

	public function mem_dis_proc(){
		$sentence_num = $this->input->post('sentences');
		$mem_id = $this->input->post('select_mem');
		$pj_id = $this->input->post('pj_id');

		//$sentence_num = explode(',', $sentence_num_array);

		$result = $this->all_list->mem_sentence($mem_id,$sentence_num,$pj_id);
		$json['pj_id'] = $pj_id;
		$json['result'] = $result;		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));		
	}

	public function equal_distribute(){	
		$total_essay_count = $this->input->POST('total_essay_count');
		$pj_id = $this->input->post('pj_id');
		$result = $this->all_list->equal_distribute($total_essay_count,$pj_id);				
		
		$json['status'] = $result;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));		
	}

	public function import_mem_dis_proc(){
		$sentence_num = $this->input->post('sentences');
		$mem_id = $this->input->post('select_mem');
		$pj_id = $this->input->post('pj_id');
		
		$result = $this->all_list->import_mem_sentence($mem_id,$sentence_num,$pj_id);
		//$result = $this->all_list->get_muse_detecting_count($mem_id,$sentence_num,$pj_id);	        
		
		$json['result'] = $result;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
}