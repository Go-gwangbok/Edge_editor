<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Neweditor extends CI_Controller {

	function __construct(){
		parent::__construct();		
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');
	}

	public function index()
	{
		if($this->session->userdata('is_login')){
			$data['cate'] = 'new_editor';
			$this->load->view('head',$data);
			
			$data['get_editor'] = $this->all_list->get_newEditor();		
			$this->load->view('neweditor_view',$data);		
			$this->load->view('footer');			
		}else{
			redirect('/');
		}

	}

	public function conf($id){
		$data['cate'] = 'new_editor';
		$this->load->view('head',$data);
		
		$result = $this->all_list->conform($id);		
		if($result){
			redirect('/neweditor');	
		}else{
			echo '<script>
					alert(Conform_Error DB all_list-> conform);
					</script>';
		}
		
	}

	public function del($id){
		$data['cate'] = 'new_editor';
		$this->load->view('head',$data);
		
		$result = $this->all_list->new_editordel($id);		
		if($result){
			redirect('/neweditor');	
		}else{
			echo '<script>
					alert(Conform_Error DB all_list-> conform);
					</script>';
		}
		
	}
}