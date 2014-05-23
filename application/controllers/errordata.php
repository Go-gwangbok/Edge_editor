<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errordata extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');
		$this->load->helper('url');	
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
		//$this->load->library('eng_month');
	}

	function eng_month($int_month){
		switch ($int_month) {
			case '01' : $str_month = 'January'; break;
            case '02' : $str_month = 'February'; break;
            case '03' : $str_month = 'March'; break;
            case '04' : $str_month = 'April'; break;
            case '05' : $str_month = 'May'; break;
            case '06' : $str_month = 'June'; break;
            case '07' : $str_month = 'July'; break;
            case '08' : $str_month = 'August'; break;
            case '09' : $str_month = 'September'; break;
            case '10' : $str_month = 'October'; break;
            case '11' : $str_month = 'November'; break;
            case '12' : $str_month = 'December'; break;
		}
		return $str_month;
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

	public function get_pj_error_data(){ 
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

	// public function error_chk(){
	// 	if($this->session->userdata('is_login')){
	// 		$editing = $this->input->post('data');
	// 		$essay_id = $this->input->post('essay_id');
	// 		$type = $this->input->post('type');
	// 		$obj_error_chk = new Errorchk;
	// 		$result = $obj_error_chk->error_chk('once',$essay_id,$type);
	// 		$json['result'] = $result;		
	// 	}else{
	// 		redirect('/');
	// 	}
		
	// 	$this->output->set_content_type('application/json')->set_output(json_encode($json));	
	// }

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

	function error_edit($essay_id,$type){
		if($this->session->userdata('is_login')){			
			$data['cate'] = 'musedata';
			$this->load->view('head',$data);					

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
			$data['raw'] = $rows[0]->raw_txt;

			$data['cate'] = 'error_edit';
			$data['pj_id'] = $rows[0]->pj_id;
			$data['essay_id'] = $essay_id;
			$data['type'] = $type;

			$this->load->view('/error/error_edit_view',$data);		
			$this->load->view('footer');								
		}else{
			redirect('/');
		}		
	}	

	function service_export_error($month,$year){
		if($this->session->userdata('is_login')){			
			$cate['cate'] = 'service';
			$this->load->view('head',$cate);				
			
			$page = 1;
			$data['page'] = $page;
			$list = 20; // 한페이지에 보요질 갯수.			
			$data['list'] = $list;						

			$data['str_month'] = $str_month = $this->eng_month($month);
			$data['month'] = $month;
			$data['year'] = $year;

			$this->load->view('/error/service_export_errorlist',$data);		
			$this->load->view('footer');
		}else{
			redirect('/');
		}	
	}

	function service_error_edit($essay_id,$type,$month,$year){
		if($this->session->userdata('is_login')){
			
			$data['cate'] = 'service';
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

			$data['cate'] = 'service_error_edit';			
			$data['month'] = $month;
			$data['year'] = $year;
			$data['essay_id'] = $essay_id;
			$data['type'] = $type;

			$this->load->view('/service_view/service_error_edit_view',$data);		
			$this->load->view('footer');								
		}else{
			redirect('/');
		}		
	}	

	// function service_export_errorlist(){
	// 	if($this->session->userdata('is_login')){						
	// 		$page = $this->input->post('page');
	// 		$year = $this->input->post('year');			
	// 		$month = $this->input->post('month');		
			
	// 		$page_list = 20;			
	// 		$limit = ($page - 1) * $page_list;	

	// 		$error_count = $this->all_list->service_export_total_count($month,$year);

			
	// 		$json['year'] = $year;
	// 		$json['page'] = $page;
	// 		$json['page_list'] = $page_list;			
	// 	}else{
	// 		redirect('/');
	// 	}

	// 	$this->output->set_content_type('application/json')->set_output(json_encode($json));

	// }

}	