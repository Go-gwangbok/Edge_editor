<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Share extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');	
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('download');		
		$this->load->dbutil();				
	}

	public function page_list(){ //0
		if($this->session->userdata('is_login')){						
			$page = $this->input->post('page');
			$list = $this->input->post('list');
			$pj_id = $this->input->post('pj_id');				
			$cate = $this->input->post('cate');
			$editor_id = $this->input->post('editor_id');							

			$limit = ($page - 1) * $list;				

			// if($cate == 'todo' || $cate == 'draft'){
			// 	$total_count = $this->all_list->pj_todo_totalcount($pj_id,$editor_id); 
			// 	$json['total_count'] = $total_count->count;
			// 	$json['list'] = $this->all_list->admin_pj_todo($pj_id,$editor_id,$page,$limit,$list); 
			// }
			if($cate == 'share'){
				$history_totalcount = $this->all_list->pj_share_totalcount($pj_id,$editor_id); 
				$json['total_count'] = $history_totalcount->count;
				$json['list'] = $this->all_list->admin_pj_share($pj_id,$editor_id,$page,$limit,$list); // history							
			}		
			$json['edi'] = $editor_id;			
			$json['page'] = $page;
			$json['cate'] = $cate;
			$json['classify'] = $this->session->userdata('classify');

		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function share_proc(){
		if($this->session->userdata('is_login')){			
			$share_data = $this->input->post('share_data');
			$select_mem = $this->input->post('select_mem');
			$editor_id = $this->input->post('editor_id');
			$pj_id = $this->input->post('pj_id');

			$result = $this->all_list->share($editor_id,$pj_id,$select_mem,$share_data);
			$json['result'] = $result;
			//$json['result'] = true;			
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
}
?>