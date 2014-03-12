<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');	
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
	}

	public function index(){
		if($this->session->userdata('is_login')){
			if($this->session->userdata('classify') == 0){ //admin
				$data['cate'] = 'notice';
				$this->load->view('head',$data);

				$data['list'] = $this->all_list->notice_all();

				$this->load->view('notice_list',$data);		
				$this->load->view('footer');			
			}else{ //editor
				$data['cate'] = 'notice';
				$this->load->view('head',$data);
				$data['list'] = $this->all_list->notice_all();

				$this->load->view('notice_list',$data);		
				$this->load->view('footer');			
			}			
		}else{
			redirect('/');
		}	
	}

	public function notice_list(){
		if($this->session->userdata('is_login')){
			$data['cate'] = 'notice';
			$this->load->view('head',$data);
			//$data['list'] = $this->all_list->status_list();

			$this->load->view('notice_write',$data);		
			$this->load->view('footer');			
		}else{
			redirect('/');
		}
	}
	
	// notice_detail
	public function contents($id){
		if($this->session->userdata('is_login')){
			if($this->session->userdata('classify') == 0){ //admin
				$data['cate'] = 'notice';
				$this->load->view('head',$data);

				$data['list'] = $this->all_list->get_notice($id);

				$this->load->view('notice_detail',$data);		
				$this->load->view('footer');			
			}else{ //editor
				$data['cate'] = 'notice';
				$this->load->view('head',$data);
				$data['list'] = $this->all_list->get_notice($id);

				$this->load->view('notice_detail',$data);		
				$this->load->view('footer');			
			}
			
		}else{
			redirect('/');
		}	
	}

	public function notice_save(){
		$title = mysql_real_escape_string($this->input->post('title'));
		$cont = mysql_real_escape_string($this->input->post('cont'));

		$result = $this->all_list->notice_save($title,$cont);

		$json['status'] = $result;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));		
	}

	public function notice_del($id){
		$result = $this->all_list->notice_del($id);
		if($result){
			redirect('/notice/notice_list');
		}else{
			echo "<script>
					alert('DB Error all_list notice_del');
					</script>";
		}
	}

}