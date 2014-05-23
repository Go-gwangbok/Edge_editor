<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HeadDoneCount extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');	
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
	}

	public function index($id)
	{
		$count = $this->all_list->HeadDoneCount($id);
		return $count->count;
	}	
}		