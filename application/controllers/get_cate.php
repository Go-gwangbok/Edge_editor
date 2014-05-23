<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get_cate extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');		
	}

	function index()
	{			
		$service_id  = $this->input->get_post('id');
		$cate = $this->input->get_post('cate');
		return $this->all_list->get_cate($service_id,$cate);
	}
}