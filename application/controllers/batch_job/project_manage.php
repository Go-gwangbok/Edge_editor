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

}
?>