<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->model('service_list');	
	}

	public function get_project_list() {
		$project_list = $this->all_list->get_project_completed_count();

		$data_list = array();
		foreach($project_list as $project) {
			$data = array();
			$data['pj_id'] = $project->pj_id;
			$data['count'] = $project->count;
		}

	}

	public function musedata(){	
		$pj_id = $this->input->GET('pj_id');
		$start_id = $this->input->GET('start_id');
		$limit = $this->input->GET('limit');

		if (!is_numeric($pj_id) || $pj_id < 1) {
			$json['status'] = false;
			$json['error_message'] = "invalid pj_id";
			$this->output->set_content_type('application/json')->set_output(json_encode($json));
			return;
		}
		if (!is_numeric($start_id) || $start_id < 1) {
			$start_id = 0;
		}

		if (!is_numeric($limit) || $limit <= 1) {
			$limit = 0;
		}

		log_message('error', '[DEBUG] export/musedata pj_id : ' . $pj_id);
		log_message('error', '[DEBUG] export/musedata start_id : ' . $start_id);
		log_message('error', '[DEBUG] export/musedata limit : ' . $limit);

		$essay_list = $this->all_list->export_musedata($pj_id, $start_id, $limit);				
		$json['status'] = true;
		$count = count($essay_list);
		$json['count'] = $count;
		log_message('error', '[DEBUG] export/musedata result count : ' . $count);
		$data_list = array();
		if ($count > 0) {
			foreach($essay_list as $essay) {
				$data = array();
				$data['id'] = $essay->id;
				$data['pj_id'] = $essay->pj_id;
				$data['prompt'] = $essay->prompt;
				$data['raw_txt'] = $essay->raw_txt;
				$data['ex_editing'] = $essay->ex_editing;
				$data['critique'] = $essay->critique;
				$data['kind_name'] = strtoupper($essay->kind_name);
				$data['score1'] = $essay->scoring;
				$data['score2'] = $essay->score2;
				$data['type'] = "musedata";
				$data['word_count'] = $essay->word_count;
				$data_list[] = $data;
			}
		}
		$json['data'] = $data_list;

		$download_fname = "musedata_$pj_id.json";

		$this->output->set_header('Content-disposition: attachment; filename='.$download_fname);
		$this->output->set_content_type('application/json')->set_output(json_encode($json));		
	}

	public function service(){
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