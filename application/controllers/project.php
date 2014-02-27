<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');	
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('download');		
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
			}elseif($cate == 'discuss'){
				$history_totalcount = $this->all_list->discuss_count($pj_id,$editor_id);
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->discuss_list($pj_id,$editor_id,$limit,$list);
			}elseif($cate == 'error'){
				$history_totalcount = $this->all_list->error_count($pj_id);
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->errorlist($pj_id,$limit,$list);
			}elseif($cate == 'export'){
				$done_id = $this->input->post('done_id');				
				$json['done_id'] = $done_id;
				$history_totalcount = $this->all_list->export_count($pj_id);				
				$json['history_totalcount'] = $history_totalcount->count;
				$json['history_list'] = $this->all_list->export_list($pj_id,$limit,$list);				
			}elseif($cate == 'error_export'){
				$json['history_totalcount'] = $this->all_list->export_error_count($editor_id);				
				$json['pj_id'] = $pj_id;				
			}
			
			$export_total = $this->all_list->exporttotal_count($pj_id);				
			$json['export_total'] = $export_total->count;				

			$json['edi'] = $editor_id;			
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
			$all_essay_id = $this->all_list->sorting_export_allessay_id($pj_id,$editor_id);

			$export_total = $this->all_list->exporttotal_count($pj_id);				
			$json['export_total'] = $export_total->count;				
			
			$essay_id_array = array();
			foreach ($all_essay_id as $value) {
				$essay_id = $value->essay_id;
				array_push($essay_id_array, $essay_id);
			}

			$json['all_essay_id'] = $essay_id_array;
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

	public function getTextBetweenTags($option,$tag, $html, $strict=0) {
	    /*** a new dom object ***/
	    $dom = new domDocument;

	    /*** load the html into the object ***/
	    if($strict==1)
	    {
	        $dom->loadXML($html);
	    }
	    else
	    {
	        $dom->loadHTML($html);
	    }

	    /*** discard white space ***/
	    $dom->preserveWhiteSpace = false;

	    /*** the tag by its tag name ***/
	    $content = $dom->getElementsByTagname($tag);

	    /*** the array to return ***/
	    $out = array();

	    // $option == cou or conf
	    if($option == 'cou'){
	    	foreach ($content as $item)
		    {
		        /*** add node value to the out array ***/		        
	        	$value = $item->nodeValue;
	        	$out[] = $value;	
		    }

	    }elseif($option == 'conf'){
	    	foreach ($content as $item)
		    {
		        /*** add node value to the out array ***/
		        if($item->nodeValue != ''){
		        	//$value = '<u>'.$item->nodeValue.'</u>';
		        	$value = $item->nodeValue;

		        	$out[] = $value;	
		        }
		        
		    }	
	    }
	    
	    /*** return the results ***/
	    return $out;
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

	public function getarraybetween($original_text, $needle1, $needle2) {
		$text_array = explode($needle1, $original_text);
		for($i=1; $i<sizeof($text_array); $i++) {
			$arr[] = trim(substr($text_array[$i], 0, strpos($text_array[$i], $needle2)));
		}
		return $arr;
	}	

	public function member_list(){
		if($this->session->userdata('is_login')){			
			$pj_id = $this->input->post('pj_id');			
			$err_id = array();
			//$err_utag = array();
			$done_data = array();		

			$update = true;
			$all_id = $this->all_list->all_essayid($pj_id); // 완료된 모든 에세이를 가지고 온다!
			// $aa = 11063;
			// $all_id = $this->all_list->memchkExportget_essay($aa); // 완료된 모든 에세이를 가지고 온다!

			foreach ($all_id as $values) {
				$editing = $values->editing;
				$id = $values->essay_id;
				$ex_editing = $values->ex_editing; //export Editing

				//<s style="-webkit-box-sizing: border-box; ">
				//<u style="-webkit-box-sizing: border-box; color: rgb(47, 157, 39); ">
				//<br style="-webkit-box-sizing: border-box; ">
				//<b style="-webkit-box-sizing: border-box; font-weight: bold; color: rgb(1, 0, 255); ">
				//<b style=""-webkit-box-sizing: border-box; font-weight: bold; "">
				$string = str_replace('<s style="-webkit-box-sizing: border-box; ">', '<strike>',$editing);
				$string = str_replace('<u style="-webkit-box-sizing: border-box; color: rgb(47, 157, 39); ">', '<u>',$string);
				$string = str_replace('<br style="-webkit-box-sizing: border-box; ">', '<br>',$string);
				$string = str_replace('<b style="-webkit-box-sizing: border-box; font-weight: bold; color: rgb(1, 0, 255); ">', '<b>',$string);
				$string = str_replace('<b style="-webkit-box-sizing: border-box; font-weight: bold; ">', '<b>',$string);
				$string = str_replace('&nbsp;', ' ',$string);

				$patterns = array('(<s>)','(</s>)'); // <s> 태그는 <strike> 태그가 오류난 것이다! 이것을 <strike>로 돌려줘야 한다!
				$replace = array("<strike>","</strike>");
				$editing = preg_replace($patterns, $replace, $string);	// result = ["<mod>to//that</mod>", "<mod>investing money//city investments</mod>"]

				$data = mysql_real_escape_string($editing);
				$this->all_list->editing_update($id,$data);				

				$s_tagmatch = preg_match('/<s>/',$editing);
				
				if($s_tagmatch > 0){
					array_push($err_id, $id);					
				}							
				
				if($ex_editing == ''){ //DB에 ex_editing 이 없다면 confirm 한다!
					$editing = eregi_replace('</?(span)[^>]*>','',$editing); //span 테그 제거!
					$editing = eregi_replace('</?(font)[^>]*>','',$editing); //font 테그 제거!							

					$int = preg_match_all('/\/\//', $editing, $matches); //  '//' count			
					$content = $this->getTextBetweenTags('cou','u', $editing); // <u>태그</u> 사이에 있는 값 가져오기!									
					
					$match = preg_match('/\/\/\/\//', $editing); // <u>문자////문자</u> -- Error

					$slash_error = array();

					$other_tagconfirm = $this->getarraybetween($editing,'<u>','</u>');

					foreach ($other_tagconfirm as $value) {
						preg_match_all('/<strike>/', $value, $stri_count);
						preg_match_all('/<b>/', $value, $b_count);

						if(count($stri_count[0]) > 0 || count($b_count[0]) > 0){
							array_push($slash_error, $id);						
							break;
						}						
					}

					foreach ($content as $value) { // Error sentence chk
						preg_match_all('/\/\//', $value, $matche_count);						
						if(count($matche_count[0]) != 1){
							array_push($slash_error, $id);
							break;
						}
					}

					if($int != count($content) || $match == 1 || count($slash_error) > 0){ // <u> 태그와 // 태그 카운터가 같아야 한다! 아니면 에러!				
						// Error											
						array_push($err_id, $id);						
					}else{ // Done
						$editing = str_replace("’", "'", $editing);
						//$json['int'] = $editing;						
						$done_content = $this->getTextBetweenTags('conf','u', $editing); // <u>태그</u> 사이에 있는 값 가져오기!										
						
						foreach ($done_content as $value) {														
							$explode = explode('//', $value); 							
							//$del_mod = eregi_replace('<u>','',$explode[0]); // first word.
							$editing = str_replace('<u>'.$explode[0].'//', '<mod target = '.$explode[0].'>', $editing);							
							$editing = preg_replace('/<\/u>/','</mod>',$editing); // second word.				
						}			
						$patterns = array("(<strike>)","(</strike>)","(<b>)","(</b>)");
						$replace = array("<del>","</del>","<ins>","</ins>");
						$editing = preg_replace($patterns, $replace, $editing);	// result = ["<mod>to//that</mod>", "<mod>investing money//city investments</mod>"]
						
						// 마지막에 replace가 되지 않으면, error로 판별한다! 
						preg_match_all('/<u>/', $editing, $not_replace);
						if(count($not_replace[0]) > 0){
							array_push($err_id, $id);							
						}else{
							$data = mysql_real_escape_string($editing);
						
							if($update){
								$update = $this->all_list->ex_editing_update($id,$data);	
								array_push($done_data,$id);
							}else{
								$json['update'] = 'error';
								break;
							}													
						}						
					}					
				}else{
					array_push($done_data,$id);
				}	

			} //foreach end.

			$result = $this->all_list->exportmembers($pj_id);								
			$json['result'] = $result;						

			$json['error'] = count($err_id);		
			$json['error_id'] = $err_id;					
			$json['done_id'] = $done_data;								
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));

	}

	function getarraybetweenmod($original_text, $needle1, $needle2) {
		$text_array = explode($needle1, $original_text);
		for($i=1; $i<sizeof($text_array); $i++) {
			$arr[] = '<mod>'.trim(substr($text_array[$i], 0, strpos($text_array[$i], $needle2))).'</mod>';
		}
		return $arr;
	}	

	public function memchkExport(){
		$essay_id = $this->input->post('essay_id');

		$get_essay = $this->all_list->memchkExportget_essay($essay_id);

		$err_html = array();
		$err_utag = array();
		$done_data = array();

		$update = true;
		foreach ($get_essay as $values) {

			$editing = $values->editing;
			$id = $values->essay_id;
			$ex_editing = $values->ex_editing;
			
			if($ex_editing == ''){ //DB에 ex_editing 이 없다면 confirm 한다!
				$editing = eregi_replace('</?(span)[^>]*>','',$editing); //span 테그 제거!
				$editing = eregi_replace('</?(font)[^>]*>','',$editing); //font 테그 제거!
				
				//$utag = preg_match_all('</?(u)[^>]*>', $editing, $utags); //  '//' count			
				$int = preg_match_all('/\/\//', $editing, $matches); //  '//' count			
				$content = $this->getTextBetweenTags('u', $editing); // <u>태그</u> 사이에 있는 값 가져오기!				

				if($int != count($content)){ // <u> 태그와 // 태그 카운터가 같아야 한다! 아니면 에러!				
					array_push($err_html, $id);

					foreach ($content as $value) { // Error sentence chk
						preg_match_all('/\/\//', $value, $matche_count);
						$front = substr($value, 0,2);
						$back = substr($value, -2);
						if(count($matche_count[0]) != 1 || $front == '//' || $back == '//'){
							array_push($err_utag, '<u>'.$value);
						}
					}
					continue;
				}

				$patterns = array("(<u>)","(</u>)","(<strike>)","(</strike>)","(<b>)","(</b>)");
				$replace = array("<mod>","</mod>","<del>","</del>","<ins>","</ins>");
				
				$data = preg_replace($patterns, $replace, $editing);				
				$mod_between_text = $this->getarraybetweenmod($data,'<mod>','</mod>');							

				foreach ($mod_between_text as $value) {										
					$explode = explode('//', $value);										
					$del_mod = eregi_replace('<mod>','',$explode[0]); // word
					$replace_tag = eregi_replace($value,'<mod target = "'.$del_mod.'">'.$explode[1],$value); //span 테그 제거!
					$data = eregi_replace($value,$replace_tag,$data);
				}
				$data = mysql_real_escape_string($data);
				// if($update){
				// 	$update = $this->all_list->ex_editing_update($id,$data);	
				// }else{
				// 	$json['update'] = 'error';
				// 	break;
				// }
			}			
		}

		$json['post_ids'] = $essay_id;
		$json['error'] = count($err_html);		
		$json['error_id'] = $err_html;		
		

		// $json['editing'] = $editing;		
		$json['tag_count'] = count($content);
		$json['result'] = $content;
		// $json['pattern'] = count($matches[0]);

		
		$json['err_utag'] = $err_utag;
		$json['done_data'] = $int;			
		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));

	}	

	public function all_export(){
		if($this->session->userdata('is_login')){			
			$done_id = $this->input->get_post('done');
			$pj_name = $this->input->get_post('pj_name');
			$this->load->dbutil();				

			$query = $this->db->query("SELECT prompt,ex_editing
					FROM tag_essay					
					WHERE essay_id in($done_id)
					AND tag_essay.pj_active = 0 
					AND tag_essay.active = 0");

			$delimiter = ":::";
			$newline = "\r\n";
			$result = $this->dbutil->csv_from_result($query, $delimiter, $newline);			

			if ( ! write_file('./'.$pj_name.'.csv', $result)){
			     $json['result'] = true;
			}
			else{
			    $data = file_get_contents("./".$pj_name.".csv"); // Read the file's contents
				$name = $pj_name.'.csv';
				if(strlen($data) > 0){
					$json['result'] = true;	
					//force_download($name,$data);

				}else{
					$json['result'] = false;	
				}				
			}			
			
		}else{
			redirect('/');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($json));		

	}

	public function download(){
		$pj_name = $this->input->get_post('pjname');

		$data = file_get_contents("./".$pj_name.".csv"); // Read the file's contents
		$name = $pj_name.'.csv';	
		force_download($name,$data);
	}

}
?>