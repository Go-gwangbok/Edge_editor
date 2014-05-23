<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sign extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->model('joindb');
		$this->load->helper('email');
	}

	public function sign_in_proc(){				
		$email = $this->input->POST('email');		
		$pass = $this->input->POST('pass');	
		
		if(empty($email) || empty($pass)){
			$result = 'empty';
		}else{
			$row = $this->joindb->sign_in($email,$pass);		
			$row_count = count($row);
			
			if($row_count != 0){
				$id = $row->id;	
				$name = $row->name;				
				$classify = $row->classify;
				$conf = $row->conf;				
				if($conf == 1){ // admin이 아직 승인을 내리지 안은상태!
					$result = 'wait';
				}else{ // admin 승인이 통과된 상태! 정상 로그인!
					$get_cate = $this->joindb->get_cate($id);

					foreach ($get_cate as $value) {
						$cate = $value->type;
						if($cate == 'musedata'){
							$this->session->set_userdata('musedata','true');
						}else{
							$this->session->set_userdata('service','true');
						}
					}
					$this->session->set_userdata('is_login',true);
					$this->session->set_userdata('nickname', $name);				
					$this->session->set_userdata('id', $id);			
					$this->session->set_userdata('classify',$classify);
					$this->session->set_userdata('email',$email);					
					$result = 'true';	
				}				
			}else{			
				$result = 'false';
			}	
		}		

		$json['status'] = $result;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));		
	}	

	public function logout(){
		$this->session->sess_destroy();
		redirect('/');
	}
}
?>