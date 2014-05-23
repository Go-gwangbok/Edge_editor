<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');	
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
	}

	public function index()
	{			
		if($this->session->userdata('is_login')){
			$classify = $this->session->userdata('classify');

			if($classify == 1){ // editor
				$cate['cate'] = 'project';
				$this->load->view('head',$cate);

				$usr_id = $this->session->userdata('id');
				//$list['list'] = $this->all_list->essayList($usr_id);
				$data['pjlist'] = $this->all_list->editor_pjlist($usr_id);
				$data['all_usr'] = '';
				
				$data['cate'] = 'editor_project';

				$this->load->view('project_view',$data);		
				$this->load->view('footer');					
			}else{	// admin
				$data['cate'] = 'project';
				$this->load->view('head',$data);
				
				$data['cate'] = 'project';
				$data['pjlist'] = $this->all_list->pjlist();
				$data['all_usr'] = $this->all_list->all_usr();

				$this->load->view('project_view',$data);		
				$this->load->view('footer');					
			}	
		}else{
			redirect('/');
		}		
	}

	public function new_pj(){
		if($this->session->userdata('is_login')){		
			$data['cate'] = 'project';
			$this->load->view('head',$data);			

			$data['mem_list'] = $this->all_list->all_usr();
			$this->load->view('new_project_view',$data);		
			$this->load->view('footer');	
		}else{
			redirect('/');
		}
	}

	public function create_pj(){
		$name = $this->db->escape($this->input->post('name'));		
		$disc = $this->db->escape($this->input->post('disc'));
		$mem_list = $this->input->post('mem_list');
		
		$result = $this->all_list->create_pj($name,$disc,$mem_list);
		if($result){
			$json['result'] = $result;
		}else{
			$json['result'] = $result;
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}	

	public function distribute($id){		
		if($this->session->userdata('is_login')){		
			$data['cate'] = 'disc';
			$this->load->view('head',$data);			
			
			$data['list'] = $this->all_list->new_essayList($id);
			$data['count'] = $this->all_list->count($id);
			$data['pj_name'] = $this->all_list->pj_name($id);
			$data['pj_id'] = $id;
			$data['error'] = '';
			$data['edi'] = $this->all_list->modal_editors($id);

			$this->load->view('new_list',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function status($id) {			
		if($this->session->userdata('is_login')){
			$data['cate'] = 'project';
			$this->load->view('head',$data);
			
			$cate = 'error';
			$list = $this->all_list->pj_status_list($id);
			$data['list'] = $list;
			$data['pj_name'] = $list[0]->pj_name;
			//$data['error_count'] = $list[0]->error_count;
			$data['error_count'] = $this->all_list->error_count($id);			
			$data['add_users'] = $this->all_list->add_userslist($id);
			$data['pj_id'] = $id; // project id.
			$data['cate'] = 'pj_status';
			$this->load->view('status_view',$data);		
			$this->load->view('footer');			
		}else{
			redirect('/');
		}		
	}

	public function del_project(){
		if($this->session->userdata('is_login')){
			$pj_id = $this->input->post('pj_id');
			$result = $this->all_list->del_project($pj_id);
			$json['result'] = $result;

		}else{
			redirect('/');
		}		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function add_users(){
		if($this->session->userdata('is_login')){
			$pj_id = $this->input->post('pj_id');
			$users = $this->input->post('users'); // , 로 구분되어서 온다!
			
			$result = $this->all_list->add_users($pj_id,$users);

			$json['result'] = $result;
		}else{
			redirect('/');
		}		

		$this->output->set_content_type('application/json')->set_output(json_encode($json));

	}

	public function del_user(){
		if($this->session->userdata('is_login')){
			$pj_id = $this->input->post('pj_id');
			$usr_id = $this->input->post('usr_id'); // , 로 구분되어서 온다!
			
			$result = $this->all_list->del_user($pj_id,$usr_id);

			$json['result'] = $result;
		}else{
			redirect('/');
		}		

		$this->output->set_content_type('application/json')->set_output(json_encode($json));

	}

	public function import($id){		
		if($this->session->userdata('is_login')){		
			$cate['cate'] = 'disc';
			$this->load->view('head',$cate);			
			
			$data['list'] = $this->all_list->new_essayList($id);
			$data['count'] = $this->all_list->count($id);
			$data['pj_name'] = $this->all_list->pj_name($id);
			$data['pj_id'] = $id;
			$data['error'] = '';
			$data['cate'] = 'import';
			$data['edi'] = $this->all_list->modal_editors($id);

			$this->load->view('new_list',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function pj_todo($pj_id,$usr_id){
		if($this->session->userdata('is_login')){
			$cate['cate'] = 'essay_list';
			$this->load->view('head',$cate);					
			
			//$data['todo_list'] = $this->all_list->pj_todoList($pj_id,$usr_id);
			$name = $this->all_list->usr_pj_name($usr_id,$pj_id);
			$data['memName'] = $name->usr_name;
			$data['pjName'] = $name->pj_name;
			$data['cate'] = 'pj_todo';
			$data['editor_id'] = $usr_id;
			$data['pj_id'] = $pj_id;

			$this->load->view('todolist_view',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}	
	}	

	public function edi_pj_todo($pj_id){
		if($this->session->userdata('is_login')){
			$cate['cate'] = 'status';
			$this->load->view('head',$cate);
			$usr_id = $this->session->userdata('id');
			
			//$data['todo_list'] = $this->all_list->eid_pj_todoList($pj_id,$usr_id);
			$name = $this->all_list->usr_pj_name($usr_id,$pj_id);
			$data['memName'] = $name->usr_name;
			$data['pjName'] = $name->pj_name;
			$data['cate'] = 'edi_pj_todo';
			$data['editor_id'] = $usr_id;
			$data['pj_id'] = $pj_id;

			$this->load->view('todolist_view',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}	
	}

	public function pj_each_mem_list($pj_id,$usr_id,$page){
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'project';
			$this->load->view('head',$data);			
			$list = 20; // 한페이지에 보요질 갯수.			
			
			$limit = ($page - 1) * $list;
			$history_totalcount = $this->all_list->pj_history_totalcount($pj_id,$usr_id); 
			$data['history_totalcount'] = $history_totalcount->count;
			$data['history_list'] = $this->all_list->admin_pj_memList($pj_id,$usr_id,$page,$limit,$list); // history			
			$data['page'] = $page;
			$data['list'] = $list;

			$name = $this->all_list->usr_pj_name($usr_id,$pj_id);
			$data['memName'] = $name->usr_name;
			$data['pjName'] = $name->pj_name;
			$data['editor_id'] = $usr_id;
			$data['pj_id'] = $pj_id;
			$data['cate'] = 'pj_mem_history';

			if($this->session->userdata('classify') == 0) { //admin
				$this->load->view('history',$data);		
			}else{ // Editor
				$this->load->view('essay_list',$data);			
			}
			
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function pj_done($pj_id,$usr_id){
		if($this->session->userdata('is_login')){						
			$cate['cate'] = 'status';
			$this->load->view('head',$cate);			
			
			//$data['list'] = $this->all_list->pj_doneList($pj_id,$usr_id);			
			$name = $this->all_list->usr_pj_name($usr_id,$pj_id);
			$data['memName'] = $name->usr_name;
			$data['pjName'] = $name->pj_name;
			$data['editor_id'] = $usr_id;
			$data['pj_id'] = $pj_id;
			$data['cate'] = 'pj_done';

			$this->load->view('donelist_view',$data);
			$this->load->view('footer');
		}else{
			redirect('/');
		}	
	}	

	// public function edi_pj_done($pj_id,$usr_id){
	// 	if($this->session->userdata('is_login')){						
	// 		$cate['cate'] = 'status';
	// 		$this->load->view('head',$cate);						
			
	// 		$name = $this->all_list->usr_pj_name($usr_id,$pj_id);			
	// 		$data['pjName'] = $name->pj_name;
	// 		$data['editor_id'] = $usr_id;
	// 		$data['pj_id'] = $pj_id;
	// 		$data['cate'] = 'edi_pj_done';

	// 		$this->load->view('donelist_view',$data);
	// 		$this->load->view('footer');
	// 	}else{
	// 		redirect('/');
	// 	}	
	// }	

	// public function admin_pj_history(){
	// 	if($this->session->userdata('is_login')){						
	// 		$usr_id = $this->input->post('usr_id');
	// 		$pj_id = $this->input->post('pj_id');

	// 		$history_list = $this->all_list->admin_pj_memList($pj_id,$usr_id); // history
	// 		//$list_count = $this->all_list->editor_pj_list_count($pj_id,$usr_id);

	// 		$json['done_list'] = $history_list;			

	// 	}else{
	// 		redirect('/');
	// 	}	
	// 	$this->output->set_content_type('application/json')->set_output(json_encode($json));
	// }	

	public function board($cate,$pj_id,$usr_id){
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'project';
			$this->load->view('head',$data);			

			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;			
			// $limit = ($page - 1) * $list;					

			if($cate == 'share'){
				$data['add_users'] = $this->all_list->share_userslist($usr_id,$pj_id);
			}

			$name = $this->all_list->usr_pj_name($usr_id,$pj_id);	
			$data['memName'] = $name->usr_name;
			$data['pjName'] = $name->pj_name;
			$data['editor_id'] = $usr_id;
			$data['pj_id'] = $pj_id;
			$data['cate'] = $cate;

			$this->load->view('pjboard',$data);					
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function page_list(){
		if($this->session->userdata('is_login')){			
			$page = $this->input->post('page');
			$list = $this->input->post('list');
			$pj_id = $this->input->post('pj_id');				
			$cate = $this->input->post('cate');
			$editor_id = $this->input->post('editor_id');				
			

			$limit = ($page - 1) * $list;		

			//$json['history_list'] = $this->all_list->admin_pj_memList($pj_id,$editor_id,$page,$limit,$list); // history							
			if($cate == 'history'){
				$history_totalcount = $this->all_list->pj_history_totalcount($pj_id,$editor_id); 
				$json['history_totalcount'] = $history_totalcount->count;				
				$json['history_list'] = $this->all_list->admin_pj_history($pj_id,$editor_id,$page,$limit,$list); // history							
			}elseif($cate == 'todo'){
				$history_totalcount = $this->all_list->pj_todo_totalcount($pj_id,$editor_id); 
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->admin_pj_todo($pj_id,$editor_id,$page,$limit,$list); // history							
			}elseif($cate == 'done'){
				$history_totalcount = $this->all_list->pj_comp_totalcount($pj_id,$editor_id); 
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->admin_pj_done($pj_id,$editor_id,$page,$limit,$list); // history							
			}elseif($cate == 'share'){
				$history_totalcount = $this->all_list->pj_share_totalcount($pj_id,$editor_id); 
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->admin_pj_share($pj_id,$editor_id,$page,$limit,$list); // history							
			}elseif($cate == 'error'){
				$history_totalcount = $this->all_list->error_count($pj_id);
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->errorlist($pj_id,$limit,$list);
			}elseif($cate == 'discuss'){
				$history_totalcount = $this->all_list->discuss_count($pj_id,$editor_id);
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->discuss_list($pj_id,$editor_id,$limit,$list);
			}elseif($cate == 'export'){
				$history_totalcount = $this->all_list->export_count($pj_id);
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->export_list($pj_id,$limit,$list);
			}
			$json['page'] = $page;
			$json['cate'] = $cate;
			$json['classify'] = $this->session->userdata('classify');

		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function sorting(){
		if($this->session->userdata('is_login')){			
			$page = $this->input->post('page');
			$list = $this->input->post('list');
			$pj_id = $this->input->post('pj_id');				
			$cate = $this->input->post('cate');
			$editor_id = $this->input->post('editor_id');					

			$limit = ($page - 1) * $list;			
			
			$history_totalcount = $this->all_list->sorting_export_count($pj_id,$editor_id);
			$json['history_totalcount'] = $history_totalcount->count;
			$json['history_list'] = $this->all_list->sorting_export_list($pj_id,$editor_id,$limit,$list);
			
			$json['page'] = $page;
			$json['cate'] = $cate;
			$json['classify'] = $this->session->userdata('classify');

		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function share(){
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

	public function error(){ //error 신고 입력! DB--> error_essay
		if($this->session->userdata('is_login')){			
			$essay_id = $this->input->post('essay_id');
			$usr_id = $this->session->userdata('id');			

			$result = $this->all_list->error_proc($essay_id,$usr_id);
			$json['result'] = $result;
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function errorlist($pj_id){
		if($this->session->userdata('is_login')){			
			$cate['cate'] = 'project';
			$this->load->view('head',$cate);			
			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;			
			// $limit = ($page - 1) * $list;								

			$name = $this->all_list->pj_name($pj_id);				
			$data['pjName'] = $name->name;			
			$data['pj_id'] = $pj_id;
			$data['cate'] = 'error';

			$this->load->view('errorboard',$data);					
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
	}

	public function essay_error_confirm(){
		if($this->session->userdata('is_login')){			
			$essay_id = $this->input->post('essay_id');

			$result = $this->all_list->error_essay_chk($essay_id);
			$json['result'] = $result;
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function discuss(){ //error 신고 입력! DB--> error_essay
		if($this->session->userdata('is_login')){			
			$essay_id = $this->input->post('essay_id');
			$usr_id = $this->session->userdata('id');			

			$result = $this->all_list->discuss_proc($essay_id,$usr_id);
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

	public function export($pj_id){
		if($this->session->userdata('is_login')){			
			$cate['cate'] = 'project';
			$this->load->view('head',$cate);			
			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;			
			// $limit = ($page - 1) * $list;								

			$name = $this->all_list->pj_name($pj_id);				
			$data['pjName'] = $name->name;			
			$data['pj_id'] = $pj_id;
			$data['cate'] = 'export';						

			$this->load->view('export_view',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}
		//$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function member_list(){
		if($this->session->userdata('is_login')){			
			$pj_id = $this->input->post('pj_id');

			$result = $this->all_list->exportmembers($pj_id);				
			$json['result'] = $result;			
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));

	}

}
?>