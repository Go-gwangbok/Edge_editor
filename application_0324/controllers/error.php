<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'errorchk.php';
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
			$cate['cate'] = 'musedata';
			$this->load->view('head',$cate);				
			
			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;						

			$name = $this->all_list->pj_name($pjid);				
			$data['pjName'] = $name->name;			
			$data['pj_id'] = $pjid;			
			$data['cate'] = 'export_error';

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

	function get_pj_error_data(){ 
		$pj_id = $this->input->post('pj_id');

		if($this->session->userdata('is_login')){			
			$pj_id = $this->input->post('pj_id');				
			$cate = $this->input->post('cate');
			$page = $this->input->post('page');
			
			$page_list = 20;			
			$limit = ($page - 1) * $page_list;					

			$totalcount = $this->all_list->error_count($pj_id);										
			$json['data_count'] = $totalcount->count;
			$json['data_list'] = $this->all_list->errorlist($pj_id,$limit,$page_list);

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
			
			$data['cate'] = $type;
			$this->load->view('head',$data);			

			// $obj_error_chk = new Errorchk;
			// $result = $obj_error_chk->error_chk_post('once',$essay_id,$type);

			$rows = $this->all_list->get_essay($essay_id,$type);			

			$editing = $rows[0]->editing;

			$string = preg_replace("/<u style[^>]*>/i", '<u>', $editing);
			//<span id="3b3477c9-a1b1-451a-9d6f-630deead0a37" ginger_software_uiphraseguid="1513b3f1-481b-402d-aac3-f65b3f641b2e" class="GINGER_SOFTWARE_mark">
   			$string = preg_replace("/<span style[^>]*>/i", '', $string); //span 테그 제거!
   			$string = preg_replace("/<span id=[^>]*>/i", '', $string); //span 테그 제거!
   			$string = str_replace('</span>', '',$string); //span 테그 제거!

   			$string = preg_replace("/<b style[^>]*>/i", '<b>', $string);
   			$string = preg_replace("/<stringike style[^>]*>/i", '<strike>', $string);
   			$string = preg_replace("/<s style[^>]*>/i", '<s>', $string);
   			$string = preg_replace("/<br style[^>]*>/i", '<br>', $string);				

   			$string = preg_replace('/<span[^>]+\>/i','',$string); 
			$string = preg_replace('/<font[^>]+\>/i','',$string); //font 테그 제거!							
			$string = str_replace('</font>', '',$string);
			$string = str_replace('</span>', '',$string);	
			
			$string = str_replace('&nbsp;', ' ',$string);
			$string = str_replace('“', '"',$string);
			$string = str_replace('”', '"',$string); // “ ” Del				
			$string = str_replace("’", "'", $string); // ’ Del				
			$string = str_replace("`", "'", $string); // ` Del

			$patterns = array('(<s>)','(</s>)'); // <s> 태그는 <strike> 태그가 오류난 것이다! 이것을 <strike>로 돌려줘야 한다!
			$replace = array("<strike>","</strike>");
			$editing = preg_replace($patterns, $replace, $string);			

			$data['editing'] = $editing;

			$data['cate'] = 'error_edit';
			$data['pj_id'] = $pj_id;
			$data['essay_id'] = $essay_id;
			$data['type'] = $type;

			$this->load->view('/error/error_edit_view',$data);		
			$this->load->view('footer');					
			
			// $rows = $this->all_list->get_completed($editor_id,$essay_id,$type);
			// $editing  = trim($rows->editing);						

			// // Array
			// $error_list = array();
			
			// // “			
			
			// $int = preg_match_all('/\/\//', $editing, $matches); //  '//' count			
			
			// $obj_project = new Errorchk;
			// $u_count = $obj_project->getTextBetweenTags('cou','u', $editing); // <u>태그</u> 사이에 있는 값 가져오기! u 태그가 비어 있는것도 카운터 해야 한다!										

			// array_push($error_list, '// count : '.$int);
			// array_push($error_list, '&lt;u&gt; tag count : '.count($u_count));

			// if($int != count($u_count)){
			// 	array_push($error_list, 'not match count');
			// }

			// foreach ($u_count as $value) {	// u태그 사이값을 검사!							
			// 	$match = preg_match('/\/\/\/\//', $value); // <u>문자////문자</u> -- Error
			// 	$match3 = preg_match('/\/\/\//', $value); // <u>문자///문자</u> -- Error				
				
			// 	$front = substr(trim($value), 0,2); // <u>//lkdjglskgj</u>  <--- 이런값이 있는지 확인
			// 	$back = substr(trim($value), -2);	// <u>lkdjglskgj//</u>  <--- 이런값이 있는지 확인

			// 	preg_match_all('/\/\//', $value, $matche_count);

			// 	if($front == '//' || $back == '//' || $match == 1 || $match3 == 1){
			// 		array_push($error_list, $value);

			// 	}elseif (count($matche_count[0]) != 1) { // 태그 사이 값이 하나도 없거나 하나 이상이면.
			// 		// if(strlen(trim($value)) == 0){ // 태그 사이 값이 하나도 없다면.
						
			// 		// 	array_push($error_list, '&ltu&gt&lt/u&gt');

			// 		// }elseif(count($matche_count[0]) == 1){

			// 		// 	array_push($error_list, '&ltu&gt &lt/u&gt');

			// 		// }else{

			// 		// 	array_push($error_list, '&ltu&gt'.$value.'&lt/u&gt');

			// 		// }					

			// 		array_push($error_list, '&ltu&gt'.$value.'&lt/u&gt');
			// 	}				
			// }						

			// // B Tag 
			// $b_tag = $obj_project->getTextBetweenTags('cou','b', $editing); // <b>태그</b> 사이에 있는 값 가져오기! 사이에 없는 값도 가져온다!

			// foreach ($b_tag as $value) {								
			// 	preg_match_all('/\/\//', $value, $matche_count);				
				
			// 	if(count($matche_count[0]) > 0 ){
			// 		array_push($error_list, '&ltb&gt'.$value.'&lt/b&gt');
			// 	}				
			// }	
			// // B Tag End


			// // U Overlartag 검사
			// $u_tagconfirm = $obj_project->getarraybetween($editing,'<u>','</u>');

			// foreach ($u_tagconfirm as $value) {
			// 	preg_match_all('/<strike>/', $value, $strike_count);
			// 	preg_match_all('/<b>/', $value, $b_count);

			// 	if(count($strike_count[0]) > 0 ){						
			// 		$strikein_data = $obj_project->getTextBetweenTags('cou','strike',$value);
			// 		array_push($error_list, '&ltu&gt&ltstrike&gt'.$strikein_data[0].'&lt/strike&gt&lt/u&gt');
			// 	}else if(count($b_count[0]) > 0){
			// 		$bin_data = $obj_project->getTextBetweenTags('cou','b',$value);
			// 		array_push($error_list, '&ltu&gt&ltb&gt'.$bin_data[0].'&lt/b&gt&lt/u&gt');
			// 	}
			// }				

			// // B_tag Overlartag 검사
			// $overlaptag = $obj_project->getTextBetweenTags('cou','b', $editing);

			// foreach ($overlaptag as $value) {
			// 	//array_push($error_list, $value);											
			// 	preg_match_all('/<strike>/', $value, $strike_count);
			// 	preg_match_all('/<u>/', $value, $u_count);

			// 	if(count($strike_count[0]) > 0 || count($u_count[0]) > 0){
			// 		array_push($error_list, $value);											
			// 	}										
			// }

			
			
			
		}else{
			redirect('/');
		}		
	}

	public function error_chk(){
		if($this->session->userdata('is_login')){

			$editing = $this->input->post('data');
			$essay_id = $this->input->post('essay_id');
			$type = $this->input->post('type');
			$obj_error_chk = new Errorchk;
			$result = $obj_error_chk->error_chk('once',$essay_id,$type);

			// $editing = preg_replace('/<span[^>]+\>/i','',$editing); //span 테그 제거!
			// $editing = preg_replace('/<font[^>]+\>/i','',$editing); //font 테그 제거!							
			// $editing = str_replace('</font>', '',$editing);
			// $editing = str_replace('</span>', '',$editing);
			
			// $int = preg_match_all('/\/\//', $editing, $matches); //  '//' count			

			
			// $u_count = $obj_project->getTextBetweenTags('cou','u', $editing); // <u>태그</u> 사이에 있는 값 가져오기!				
			// $u_tag = $obj_project->getTextBetweenTags('conf','u', $editing); // <u>태그</u> 사이에 있는 값 가져오기!			
			// $b_tag = $obj_project->getTextBetweenTags('conf','b', $editing); // <u>태그</u> 사이에 있는 값 가져오기!				

			// $error_list = array();

			// if($int != count($u_count)){
			// 	array_push($error_list, 'not match count');
			// }
			
			// foreach ($u_tag as $value) {				
			// 	$match = preg_match('/\/\/\/\//', $value); // <u>문자////문자</u> -- Error
				
			// 	preg_match_all('/\/\//', $value, $matche_count);
			// 	$front = substr($value, 0,2);
			// 	$back = substr($value, -2);
				
			// 	if($front == '//' || $back == '//' || $match == 1){
			// 		array_push($error_list, $value);
			// 	}elseif (count($matche_count[0]) != 1) {
			// 		array_push($error_list, '&ltu&gt &lt/u&gt');
			// 	}				
			// }						

			// foreach ($b_tag as $value) {								
			// 	preg_match_all('/\/\//', $value, $matche_count);				
				
			// 	if(count($matche_count[0]) > 0 ){
			// 		array_push($error_list, $value);
			// 	}				
			// }

			// if(count($error_list) > 0){ // call back				
			// 	$json['essay_id'] = $essay_id;		
			// }else{ // DB update
			// 	$editing = mysql_real_escape_string($editing);
			// 	$update = $this->all_list->editing_update($essay_id,$editing); // DB에 Editing만 UPDATE한다.
			// 	if($update){
			// 		$obj_project_reupdate = $obj_project->essay_chk('once',$essay_id);					
			// 		$json['update'] = $obj_project_reupdate;					
			// 	}else{
			// 		$json['update'] = $update;	
			// 	}
				
			// }		

			$json['result'] = $result;		
		}else{
			redirect('/');
		}
		
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