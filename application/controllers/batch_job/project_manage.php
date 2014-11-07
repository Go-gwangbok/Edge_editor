<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_Manage extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('batch_job');
	}

	public function copy_project_data($source_pj_id, $dest_pj_id){
		
		$result = $this->batch_job->copy_project_data($source_pj_id, $dest_pj_id);

		$json['status'] = $result;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function import_museprep_data(){

		$filename = "./uploads/essay-sampling1000.txt";

		$fp = @fopen($filename, "r");
		if ($fp) {
			$cnt = 0;
			while (($buffer = fgets($fp, 100)) !== false) {
				$buffer = str_replace("\r", "", $buffer);
				$buffer = str_replace("\n", "", $buffer);
				echo $buffer;
				$token = explode("/", $buffer);
				if (count($token) != 3) continue;
				$pj_id = $token[0];
				$id = $token[1];
				$score = $token[2];

				echo "$cnt || $pj_id  || $id || $score\n";

				$result = $this->batch_job->import_museprep_data($pj_id, $id, 29, 30, $cnt);

				if ($result == false) {
					echo "dbinsert fail!!!";
					break;
				}

				$cnt++;
				//if ($cnt > 10) break;
			}
			fclose($fp);
		}

		/***
		$result = $this->batch_job->copy_project_data($source_pj_id, $dest_pj_id);

		$json['status'] = $result;
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
		***/
	}

	public function dump_import_data(){
		$result = $this->batch_job->dump_import_data();

		$json['data'] = $result;
		$this->output->set_header('Content-disposition: attachment; filename=import_data.json');
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function make_sentence_count($service_name){
		if ($service_name == 'musedata') {
			$result = $this->batch_job->make_sentence_count_musedata();
		} else if ($service_name == 'writing') {
			$result = $this->batch_job->make_sentence_count_writing();
		} else if ($service_name == 'grammar') {
			$result = $this->batch_job->make_sentence_count_grammar();
		} else if ($service_name == 'bbs') {
			$result = $this->batch_job->make_sentence_count_bbs();
		} else {
			$json['status'] = fail;
			$json['message'] = "invalid service name"; 
			$this->output->set_content_type('application/json')->set_output(json_encode($json));
			return;
		}

		if (!$result) {
			$json['status'] = $result;	
		} else {
			$json['status'] = true;
			$json['update_count'] = $result; 
		}
		
		$this->output->set_content_type('application/json')->set_output(json_encode($json));
	}

	public function get_picto_data(){

		$url = "http://akaonedge.com/muse_base/datas/picto/";

		$this->load->model('picto_model');

		$picto_model = new Picto_model();
		$row = $picto_model->getMaxId();
		$max_id = $row->max_id + 1;

		//echo "max_id : " . $max_id;

		$num = "200";
		
		$url .= $max_id . "/" . $num . "/";

		echo date("Y-m-d H:i:s") . "\n";
		echo $url . "\n";

		$result = $this->curl->simple_get($url);
		
		$picto_list = json_decode($result, true);

		//var_dump($picto_list);
		
		$count = 0;

		foreach($picto_list as $picto) {
			$picto_model = new Picto_model();

			$picto_model->id 		= $picto['id'];
			$picto_model->org_id 	= $picto['org_id'];
			$picto_model->org_txt 	= $picto['org_txt'];
			$picto_model->mod_txt 	= $picto['mod_txt'];
			$picto_model->created 	= $picto['date'];

			if (!$picto_model->insert()) {
				echo "fail";
				break;
			}
			$count++;
		}

		echo "success count : $count\n";
	}

	public function get_speaking_data(){

		$url = "http://akaonedge.com/muse_base/datas/edge_speaking/";

		$this->load->model('speaking_model');

		$speaking_model = new Speaking_model();
		$row = $speaking_model->getMaxId();
		$max_id = $row->max_id + 1;

		//echo "max_id : " . $max_id . "\n";

		$num = "200";
		
		$url .= $max_id . "/" . $num . "/";

		echo date("Y-m-d H:i:s") . "\n";
		echo $url . "\n";

		$result = $this->curl->simple_get($url);
		
		$speaking_list = json_decode($result, true);

		//var_dump($speaking_list);

		$count = 0;
		foreach($speaking_list as $speaking) {
			$speaking_model = new Speaking_model();

			$speaking_model->id 		= $speaking['id'];
			$speaking_model->org_id 	= $speaking['org_id'];
			$speaking_model->org_txt 	= $speaking['org_txt'];
			$speaking_model->mod_txt 	= $speaking['mod_txt'];
			$speaking_model->audio 	= $speaking['audio'];
			$speaking_model->audio_duration 	= $speaking['audio_duration'];
			$speaking_model->created 	= $speaking['date'];

			if (!$speaking_model->insert()) {
				echo "fail";
				break;
			}
			$count++;
		}

		echo "success count : $count\n";
	}

	public function get_grammar_data(){

		$before_day = date("Ymd", strtotime("-1 day"));
		echo "before_day : $before_day\n";

		//$url = "http://ec2-54-244-190-156.us-west-2.compute.amazonaws.com:8080/logs/muse.20140717.log";
		$url = "http://ec2-54-244-190-156.us-west-2.compute.amazonaws.com:8080/logs/muse.$before_day.log";

		$this->load->model('grammar_model');

		echo date("Ymd") . "\n";
		echo $url . "\n";

		$result = $this->curl->simple_get($url);
		
		$grammar_list = explode("\n", $result);

		$count = 0;
		foreach($grammar_list as $json) {
			//echo "$count : $grammar\n";

			$grammar = json_decode($json, true);

			if ( !isset($grammar['sentences'][0]['sentence']) ) {
				break;
			}

			$created = str_replace (" UTC", "", $grammar['utctime']);


			//echo $grammar['utctime'] . "\n";
			//echo "[$created]\n\n";
			//echo $grammar['sentences'][0]['sentence'] . "\n";
			//echo count($grammar['sentences'][0]['words']) . "\n";
			//echo $grammar['error_counts'] . "\n";
			//echo $grammar['proctime'] . "\n";
			//echo $grammar['sentences'][0]['sentence'] . "\n";

			$grammar_model = new Grammar_model();

			$grammar_list = json_decode($result, true);

			$grammar_model->sentence 	= $grammar['sentences'][0]['sentence'];
			$grammar_model->json 		= $json;
			$grammar_model->word_count 	= count($grammar['sentences'][0]['words']);
			$grammar_model->error_count 	= $grammar['error_counts'];
			$grammar_model->proc_time 	= $grammar['proctime'];
			if  (strpos ($grammar['input_category'], '2014') === 0 ) {
				$grammar_model->category 	= "-";
			} else {
				$grammar_model->category 	= $grammar['input_category'];
			}
			$grammar_model->created 	= str_replace (" UTC", "", $grammar['utctime']);

			if (!$grammar_model->insert()) {
				echo "fail";
				break;
			}
			$count++;
		}

		echo "success count : $count\n";
	}


	public function make_daily_stat($service){
		$this->load->model('stat');

		$result = $this->stat->make_daily_stat($service);

		if ($result) {
			echo "success count : $result\n";
		}
	}

	public function make_project_summary() {
		$this->load->model('all_list');

		$pj_list = $this->all_list->admin_pjlist();

		foreach($pj_list as $pj) {
			echo "project_name : " . $pj->name . "<br>";
			echo "total_count : " . $pj->total_count . "<br>";
			echo "completed : " . $pj->completed . "<br>";
			echo "todo : " . $pj->todo . "<br><br>";

			$result = $this->all_list->make_project_summary($pj->pj_id, $pj->total_count, $pj->completed, $pj->todo);
			if (!$result) {
				echo "make_project_summary fail";
				return;
			}
		}

		echo "make_project_summary success1!!";
	}

	public function make_training_data(){
		$start_id = 24531;
		$end_id = 24867;
		$limit = 50;
		$pj_id = 30;
		$usr_id = 14;
		$start_tr_id = 1;
		$tr_count = 10;
		//$result = $this->batch_job->make_training_data($pj_id, $usr_id, $start_id, $end_id, $limit, $start_tr_id, $tr_count);

		if ($result) {
			echo "success count : $result\n";
		}
	}

	public function make_training_data2() {
		$id_list = array(24009,24015,24021,24033,24039,24513,24525,24921,24045,24087,24141,24177,24195,24495,24903,24909,25005,24201,24219,24255,24267,24273,24279,24315,24321,24363,24417,24459,24465,24963);

		$tr_id = 1;
		$count = 0;
		foreach ( $id_list as $id) {
			echo "$id $tr_id <br>";

			$result = $this->batch_job->make_training_data2($id, $tr_id);
			$tr_id++;
			if ($tr_id > 6) $tr_id = 1;
			$count++;
		}
		echo "total : $count";
	}
}
?>