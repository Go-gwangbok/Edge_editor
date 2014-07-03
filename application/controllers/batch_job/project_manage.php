<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_Manage extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('batch_job');
	}

	public function copy_project_data($source_pj_id, $dest_pj_id){
		
		$result = $this->batch_job->copy_project_data($source_pj_id, $dest_pj_id);

		$json['status'] = $result;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}	
}
?>