<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'project.php';
class Error extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');	
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
	}

	public function export_error($pjid){ 
		if($this->session->userdata('is_login')){			
			$cate['cate'] = 'project';
			$this->load->view('head',$cate);				
			
			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;						

			$name = $this->all_list->pj_name($pjid);				
			$data['pjName'] = $name->name;			
			$data['pj_id'] = $pjid;			
			$data['cate'] = 'error_export';

			$this->load->view('/error/export_errorlist',$data);		
			$this->load->view('footer');
		}
		else
		{
			redirect('/');
		}
	}

	function get_error_data(){ 
		$pj_id = $this->input->post('pj_id');

		if($this->session->userdata('is_login')){			
			$pj_id = $this->input->post('pj_id');				
			$cate = $this->input->post('cate');
			$page = $this->input->post('page');
			
			$page_list = 20;			
			$limit = ($page - 1) * $page_list;					

			$totalcount = $this->all_list->export_error_count($pj_id);										
			$json['data_count'] = $totalcount->count;
			$json['data_list'] = $this->all_list->get_export_error_data($pj_id,$limit,$page_list);

			$json['pj_id'] = $pj_id;					
			$json['page'] = $page;
			$json['page_list'] = $page_list;			
			$json['cate'] = $cate;			

		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));


	}

	function error_edit($editor_id,$essay_id,$type,$pj_id){
		if($this->session->userdata('is_login')){
			// “
			$data['cate'] = 'admin';
			$this->load->view('head',$data);			
			
			$rows = $this->all_list->get_completed($editor_id,$essay_id,$type);
			$editing  = trim($rows->editing);						
			$editing = preg_replace('/<span[^>]+\>/i','',$editing); //span 테그 제거!
			$editing = preg_replace('/<font[^>]+\>/i','',$editing); //font 테그 제거!							
			$editing = str_replace('</font>', '',$editing);
			$editing = str_replace('</span>', '',$editing);
			
			
			$int = preg_match_all('/\/\//', $editing, $matches); //  '//' count			
			
			$obj_project = new Project;
			$u_count = $obj_project->getTextBetweenTags('cou','u', $editing); // <u>태그</u> 사이에 있는 값 가져오기!				
			//$u_tag = $obj_project->getTextBetweenTags('conf','u', $editing); // <u>태그</u> 사이에 있는 값 가져오기!				
			$b_tag = $obj_project->getTextBetweenTags('conf','b', $editing); // <u>태그</u> 사이에 있는 값 가져오기!				

			$error_list = array();

			array_push($error_list, '// count : '.$int);
			array_push($error_list, '&lt;u&gt; tag count : '.count($u_count));

			if($int != count($u_count)){
				array_push($error_list, 'not match count');
			}

			foreach ($u_count as $value) {				
				$match = preg_match('/\/\/\/\//', $value); // <u>문자////문자</u> -- Error
				
				preg_match_all('/\/\//', $value, $matche_count);
				$front = substr($value, 0,2);
				$back = substr($value, -2);						

				if($front == '//' || $back == '//' || $match == 1){
					array_push($error_list, $value);

				}elseif (count($matche_count[0]) != 1) {
					if(strlen($value) == 0){
						array_push($error_list, '&ltu&gt&lt/u&gt');
					}elseif(count($matche_count[0]) == 1){
						array_push($error_list, '&ltu&gt &lt/u&gt');
					}else{
						array_push($error_list, '&ltu&gt'.$value.'&lt/u&gt');
					}					
				}				
			}						

			foreach ($b_tag as $value) {								
				preg_match_all('/\/\//', $value, $matche_count);				
				
				if(count($matche_count[0]) > 0 ){
					array_push($error_list, '&ltb&gt'.$value);
				}				
			}	

			$overlaptag = $obj_project->getarraybetween($editing,'<u>','</u>');

			foreach ($overlaptag as $value) {
				preg_match_all('/<strike>/', $value, $stri_count);
				preg_match_all('/<b>/', $value, $b_count);

				if(count($stri_count[0]) > 0 || count($b_count[0]) > 0){
					array_push($error_list, $value);											
				}										
			}

			$data['error_list'] = $error_list;
			$data['editing'] = $editing;
			$data['cate'] = 'error';
			$data['pj_id'] = $pj_id;
			$data['essay_id'] = $essay_id;
			
			$this->load->view('/error/error_edit_view',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}		
	}

	public function error_chk(){
		if($this->session->userdata('is_login')){

			$editing = $this->input->post('data');
			$essay_id = $this->input->post('essay_id');

			$editing = preg_replace('/<span[^>]+\>/i','',$editing); //span 테그 제거!
			$editing = preg_replace('/<font[^>]+\>/i','',$editing); //font 테그 제거!							
			$editing = str_replace('</font>', '',$editing);
			$editing = str_replace('</span>', '',$editing);
			
			$int = preg_match_all('/\/\//', $editing, $matches); //  '//' count			
			
			$obj_project = new Project;
			$u_count = $obj_project->getTextBetweenTags('cou','u', $editing); // <u>태그</u> 사이에 있는 값 가져오기!				
			$u_tag = $obj_project->getTextBetweenTags('conf','u', $editing); // <u>태그</u> 사이에 있는 값 가져오기!			
			$b_tag = $obj_project->getTextBetweenTags('conf','b', $editing); // <u>태그</u> 사이에 있는 값 가져오기!				

			$error_list = array();

			if($int != count($u_count)){
				array_push($error_list, 'not match count');
			}
			
			foreach ($u_tag as $value) {				
				$match = preg_match('/\/\/\/\//', $value); // <u>문자////문자</u> -- Error
				
				preg_match_all('/\/\//', $value, $matche_count);
				$front = substr($value, 0,2);
				$back = substr($value, -2);
				
				if($front == '//' || $back == '//' || $match == 1){
					array_push($error_list, $value);
				}elseif (count($matche_count[0]) != 1) {
					array_push($error_list, '&ltu&gt &lt/u&gt');
				}				
			}						

			foreach ($b_tag as $value) {								
				preg_match_all('/\/\//', $value, $matche_count);				
				
				if(count($matche_count[0]) > 0 ){
					array_push($error_list, $value);
				}				
			}

			if(count($error_list) > 0){ // call back				
				$json['essay_id'] = $essay_id;		
			}else{ // DB update
				$editing = mysql_real_escape_string($editing);
				$update = $this->all_list->editing_update($essay_id,$editing);								
				if($update){
					$obj_project_reupdate = $obj_project->member_list('once',$essay_id);					
					$json['update'] = $obj_project_reupdate;					
				}else{
					$json['update'] = $update;	
				}
				
			}			
		}else{
			redirect('/');
		}
		$json['result'] = $error_list;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));	
	}

	public function error_yes(){
		if($this->session->userdata('is_login')){			
			$essay_id = $this->input->post('essay_id');			

			$result = $this->all_list->error_yes($essay_id);

			$json['result'] = $result;
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function error_return(){ 
		if($this->session->userdata('is_login')){			
			$essay_id = $this->input->post('essay_id');

			$result = $this->all_list->error_return($essay_id);
			$json['result'] = $result;
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}
}	