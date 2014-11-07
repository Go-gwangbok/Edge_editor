<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Training extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('training_result_model');
		$this->load->model('training_result_detail_model');
		$this->load->model('all_list');
		$this->load->helper('url');	
		//$this->load->helper('file');
		//$this->load->helper(array('form', 'url'));
		//$this->load->dbutil();

		if(!$this->session->userdata('is_login')){
			redirect('/');
		}
	}

	function index()
	{			
		$classify = $this->session->userdata('classify');
		$data['cate'] = 'training';
		$this->load->view('head',$data);							
	
		if ($classify == 0) { // admnin
			$training_users = $this->training_result_model->getTrainingUser();
			//var_dump($training_users );
			$data['usr_id'] = 0;
			$data['training_users'] = $training_users;
			$this->load->view('/training/admin_index',$data);
		} else {
			$usr_id = $this->session->userdata('id');
			//echo $usr_id;

			$training_result = $this->training_result_model->getTraingResultData($usr_id);

			$pass_num = 0;
			foreach($training_result as $training) {
				if ($training->certificated == 1) {
					$pass_num++;
				}
			} 
			if ($pass_num >= 2) {
				$data['qualified'] = true;
			} else {
				$data['qualified'] = false;
			}

			$data['training_result'] = $training_result;
			$this->load->view('/training/index',$data);
		}
		
		
		//var_dump($data);						
		//$data['cate'] = 'editor';
				
		$this->load->view('footer');					
	}

	function admin_list($usr_id)
	{			
		$classify = $this->session->userdata('classify');
		if ($classify != 0) { // not admnin
			redirect('/');
		}

		$data['cate'] = 'training';
		$this->load->view('head',$data);							
		
		$training_users = $this->training_result_model->getTrainingUser();
		
		$data['usr_id'] = $usr_id;
		$data['training_users'] = $training_users;
		
		$training_result = $this->training_result_model->getTraingResultData($usr_id);

		$pass_num = 0;
		foreach($training_result as $training) {
			if ($training->certificated == 1) {
				$pass_num++;
			}
		} 
		if ($pass_num >= 2) {
			$data['qualified'] = true;
		} else {
			$data['qualified'] = false;
		}

		$data['training_result'] = $training_result;

		$this->load->view('/training/admin_index',$data);
				
		$this->load->view('footer');					
	}

	function test($tr_id, $seq = 1)
	{
		if($this->session->userdata('is_login')){
			$classify = $this->session->userdata('classify');
			$data['cate'] = 'training';
			$this->load->view('head',$data);							
		
			$usr_id = $this->session->userdata('id');

			$result = $this->training_result_model->start_training($usr_id, $tr_id);
			if (!$result) {
				echo "DB error!!!";
				return false;
			}
			$result = $this->training_result_detail_model->start_training($usr_id, $tr_id);
			if (!$result) {
				echo "DB error!!!";
				return false;
			}

			$training_data = $this->training_result_detail_model->get_training_data($usr_id, $tr_id, $seq);

			$training_data[0]->row_txt = nl2br($training_data[0]->row_txt);

			$training_data[0]->muse_score = $this->score_pattern_replace($training_data[0]->muse_score);

			$question_count = 5;

			//var_dump($training_data);

			$data['tr_id']= $tr_id;
			$data['seq']= $seq;
			$data['question_count'] = $question_count;
			$data['training_data']= $training_data[0];
			$data['score_templet'] = $this->all_list->get_scores_temp(2);	
			//var_dump($data);						
			//$data['cate'] = 'editor';
			$this->load->view('/training/test',$data);		
			$this->load->view('footer');					
		}else{
			redirect('/');
		}		
	}

	public function save_training_result_detail(){
		if($this->session->userdata('is_login')){
			$usr_id = $this->session->userdata('id');

			$id = $this->input->POST('id');
			$tr_id = $this->input->POST('tr_id');
			$seq = $this->input->POST('seq');
			$muse_score = $this->input->POST('muse_score');
			$score = $this->input->POST('score');

			$tr_obj = $this->training_result_detail_model->getById($id);

			if ($tr_obj == null) {
				$json['status'] = false;
				$json['message'] = 'Invalid ID';
				$this->output->set_content_type('application/json')->set_output(json_encode($json));
			}

			if ($tr_obj->tr_id != $tr_id || $tr_obj->seq != $seq || $tr_obj->usr_id != $usr_id) {
				$json['status'] = false;
				$json['message'] = 'Invalid Parameter';
				$this->output->set_content_type('application/json')->set_output(json_encode($json));
			}

			$tr_model = new Training_result_detail_model();
			$tr_model->id = $id;
			$tr_model->muse_score = $muse_score;
			$tr_model->score = $score;

			$result = $tr_model->update();

			$json['status'] = $result;
			if (!$result) {
				$json['message'] = 'DB Error!!!';
			}		
		}else{
			redirect('/');
		}		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));	
	}

	function finish($tr_id)
	{
		if($this->session->userdata('is_login')){
			$classify = $this->session->userdata('classify');
			$data['cate'] = 'training';
			$this->load->view('head',$data);									
			$usr_id = $this->session->userdata('id');

			$training_datas = $this->training_result_detail_model->get_training_data($usr_id, $tr_id);

			$total_diff = 0;
			foreach ($training_datas as $training_data) {
				$org_score = $training_data->org_score;
				$editor_score = $training_data->score;
				$diff = abs($org_score - $editor_score);
				$total_diff += $diff;

				//echo "org : $org_score, editor : $editor_score, diff : $diff<br>\n";
			}
			//echo "total_diff : $total_diff<br>\n";

			$final_score = 100 - ($total_diff * 4);
			if ($final_score > 90) {
				$certificated = 1;
			} else {
				$certificated = 0;
			}

			$training_result_id = $this->training_result_model->get_training_result_id($usr_id, $tr_id);

			$training_result_model = new Training_result_model();

			$training_result_model->id = $training_result_id;
			$training_result_model->usr_id = $usr_id;
			$training_result_model->tr_id = $tr_id;
			$training_result_model->score = $final_score;
			$training_result_model->certificated = $certificated;

			$training_result_model->update();

			//var_dump($training_result_model);

			if ($tr_id < 10) {
				$result = $this->training_result_model->start_training($usr_id, $tr_id + 1);
			}

			/***

			$data['tr_id']= $tr_id;
			$data['seq']= $seq;
			$data['question_count'] = $question_count;
			$data['training_data']= $training_data[0];
			$data['score_templet'] = $this->all_list->get_scores_temp(2);	
			//var_dump($data);						
			//$data['cate'] = 'editor';
			$this->load->view('/training/test',$data);		
			$this->load->view('footer');
			***/
			redirect('/training/result/' . $tr_id . '/');
		}else{
			redirect('/');
		}		
	}


	function result($training_result_id)
	{			
		$classify = $this->session->userdata('classify');
		$data['cate'] = 'training';
		$this->load->view('head',$data);
	
		$usr_id = $this->session->userdata('id');

		$training_result_id = $this->training_result_model->get_training_result_id($usr_id, $training_result_id);

		$training_result = $this->training_result_model->getById($training_result_id);

		$training = $this->training_result_model->getTrainingById($training_result->tr_id);

		$training_result->name = $training->name;

		$training_result_detail = $this->training_result_detail_model->get_training_data($training_result->usr_id, $training_result->tr_id);

		$data['classify'] = $classify;
		$data['training_result'] = $training_result;
		$data['training_result_detail'] = $training_result_detail;
		//var_dump($data);	

		//$data['cate'] = 'editor';
		$this->load->view('/training/result',$data);		
		$this->load->view('footer');							
	}

	function admin_result($usr_id, $training_result_id)
	{			
		$classify = $this->session->userdata('classify');
		if ($classify != 0) { // not admnin
			redirect('/');
		}

		$data['cate'] = 'training';
		$this->load->view('head',$data);

		$training_result_id = $this->training_result_model->get_training_result_id($usr_id, $training_result_id);

		$training_result = $this->training_result_model->getById($training_result_id);

		$training = $this->training_result_model->getTrainingById($training_result->tr_id);

		$training_result->name = $training->name;

		$training_result_detail = $this->training_result_detail_model->get_training_data($training_result->usr_id, $training_result->tr_id);


		$data['classify'] = $classify;
		$data['training_result'] = $training_result;
		$data['training_result_detail'] = $training_result_detail;
		//var_dump($data);	

		//$data['cate'] = 'editor';
		$this->load->view('/training/result',$data);		
		$this->load->view('footer');						
	}


	function view_criterion_result($tr_id, $seq = 1, $usr_id = 0)
	{
		$classify = $this->session->userdata('classify');
		if ($classify != 0) {
			$usr_id = $this->session->userdata('id');
		}

		$training_result = $this->training_result_model->getById($tr_id);
		if ($training_result->score == null) {
			echo "score is null";
			return;
		}

		$training_data = $this->training_result_detail_model->get_training_data($usr_id, $tr_id, $seq);
		if ($training_data == null) {
			echo "training_data is null";
			return;
		}

		echo file_get_contents('./criterion/' . $training_data[0]->trdata_id  . '.html');
	}

	function view_score_result($tr_id, $seq = 1, $usr_id = 0)
	{
		$classify = $this->session->userdata('classify');
		$data['cate'] = 'training';
		$this->load->view('head',$data);							
	
		if ($classify != 0) {
			$usr_id = $this->session->userdata('id');
		}

		$training_result = $this->training_result_model->getById($tr_id);
		if ($training_result->score == null) {
			echo "score is null";
			return;
		}

		$training_data = $this->training_result_detail_model->get_training_data($usr_id, $tr_id, $seq);
		if ($training_data == null) {
			echo "training_data is null";
			return;
		}
		$training_data[0]->muse_score = $this->score_pattern_replace($training_data[0]->muse_score);
		$training_data[0]->org_muse_score = $this->score_pattern_replace($training_data[0]->org_muse_score);

		$question_count = 5;
		//var_dump($training_data);

		$data['tr_id']= $tr_id;
		$data['seq']= $seq;
		$data['training_data']= $training_data[0];
		$data['tag_templet'] = $this->all_list->get_tag(2);
		$data['score_templet'] = $this->all_list->get_scores_temp(2);

		$data['question_count'] = $question_count;	
		//var_dump($data);						
		//$data['cate'] = 'editor';
		$this->load->view('/training/view_score_result',$data);		
		$this->load->view('footer');					
	}

	function score_pattern_replace($data_obj){
		$pattern = array('({)','(})','(")');
		$replace = array('','','');
		return preg_replace($pattern, $replace, $data_obj);			
	}
}
?>