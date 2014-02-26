<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('joinDb');
		$this->load->helper('email');
	}

	public function index(){
		
		$data['cate'] = 'sign_up';
		$this->load->view('head',$data);
		$this->load->view('signup_view');		
		$this->load->view('footer');				
	}	

	public function sign_up()
	{			
		$name = $this->input->POST('name');
		$email = $this->input->POST('email');
		$pass = $this->input->POST('pass');

		if(empty($name) || empty($email) || empty($pass)){
			$result = 'empty';
		}elseif(!valid_email($email)){
			$result = 'email';
		}else{
			$result = $this->joinDb->sign_up($name,$email,$pass);	
			//$result = 'true';
		}		
		$json['status'] = $result;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}		
}
?>