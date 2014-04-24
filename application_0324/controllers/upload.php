<?php
class Upload extends CI_Controller {	
	function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('all_list');		
		$this->load->helper('file');
		$this->load->helper(array('form', 'url'));
		
	}

	public function index()
	{		
		$files = $this->input->post('userfile');
		$pj_id = $this->input->post('pj_id');
		
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv';
		$config['max_size']	= '0';
		$config['max_width']  = '0';
		$config['max_height']  = '0';
		$config['overwrite'] = true;
		$this->load->library('upload', $config);
		
		if (!$this->upload->do_upload())
		{			
			$data['error'] = 'Please select a file';			
		}	
		else
		{
			$data = $this->upload->data();			
			$filename = $data['file_name'];
			$conf = $this->all_list->ins_db_file($filename);

			if($conf){
				$file = read_file('./uploads/'.$filename);
				$file = substr($file, 0,-2); // 마지막 문장의 끝에 //를 없앤다! 
				$data = explode('//', $file);
				$data_cou = count($data);
				$result = true;
				
				for($i = 1; $i < $data_cou; $i++){									
					$data_replace = $data[$i];

					$data_replace = str_replace('“', '"',$data_replace);
					$data_replace = str_replace('”', '"',$data_replace); // “ ” Del				
					$data_replace = str_replace("’", "'", $data_replace); // ’ Del				
					$data_replace = str_replace("`", "'", $data_replace); // ` Del

					

					$text = explode('::', $data_replace);
					$title = trim(mysql_real_escape_string(substr($text[0],0,-1)));										
					$sen = substr($text[1],1);
					$sentence = trim(mysql_real_escape_string(substr($sen,0,-1)));					
					$kind = 'essay';
					// scoring
					// $ibc = trim($text[2]);
					// $thesis = trim($text[3]);
					// $topic = trim($text[4]);
					// $coherence = trim($text[5]);
					// $transition = trim($text[6]);
					// $mi = trim($text[7]);
					// $si = trim($text[8]);
					// $style = trim($text[9]);
					// $usage = trim($text[10]);

					if(empty($text[11])){
						$critique = '';
					}else{
						$raw_criti = substr($text[11],1);
						$raw_criti = str_replace('"', '', $raw_criti);
						$critique = mysql_real_escape_string(trim(substr($raw_criti, 0,-1)));						
					}				

					$json['ibc'] = trim(substr($text[2],1,1));
					$json['thesis'] = trim(substr($text[3],1,1));
					$json['topic'] = trim(substr($text[4],1,1));
					$json['coherence'] = trim(substr($text[5],1,1));
					$json['transition'] = trim(substr($text[6],1,1));
					$json['mi'] = trim(substr($text[7],1,1));
					$json['si'] = trim(substr($text[8],1,1));
					$json['style'] = trim(substr($text[9],1,1));
					$json['usage'] = trim(substr($text[10],1,1));
					$scoring = json_encode($json);	
					//essay 테이블에 문장 입력!
					if($result){
						$result = $this->all_list->import_sentence($pj_id,$title,$sentence,$kind,$scoring,$critique);							
					}else{
						$data['error'] = $result;		
					}			
				}					
				$data['error'] = '';
			}else{				
				$data['error'] = 'Such file exists!';				
			}			
		}	
		$cate['cate'] = 'disc';
		$this->load->view('head',$cate);
		
		$data['cate'] = 'import';
		$data['list'] = $this->all_list->new_essayList($pj_id);
		$data['count'] = $this->all_list->count($pj_id);
		$data['pj_name'] = $this->all_list->pj_name($pj_id);
		$data['pj_id'] = $pj_id;
		$data['edi'] = $this->all_list->modal_editors($pj_id);

		$this->load->view('import',$data);
		$this->load->view('footer');
	}	

	function get_mistakes(){		

		$title = 'Do you agree or disagree with the following statement? A person';
		$essay = 'It is better for children to spend their time in a big city than in the countryside. Im convinced that the advantages there will help the young enjoy better environments in a variety of phase. 
					First, they are likey to catch more opportunities to appreciate cultural convenience. ';

	 	$result_org = $this->curl->simple_post('http://ec2-54-249-202-206.ap-northeast-1.compute.amazonaws.com:9000/assessment2', array('prompt' => $title,'essay' => $essay));


	    $result_json = json_decode($result_org, true);

	    $feedbacks = $result_json['feedbacks'];

	    $mistakes = 0;
	    foreach ($feedbacks as $value) 
	    {
	        $mistakes = $mistakes + count($value['checks']);
	    }
	   	$json['mistakes'] = $mistakes;
	    
	    $this->output->set_content_type('application/json')->set_output(json_encode($json));	        
	}

	// public function do_upload()
	// {
		
	// 	$files = $this->input->post('userfile');
	// 	$pj_id = $this->input->post('pj_id');
	// 	//print_r($pj_id);
	// 	//echo var_dump($_FILES);
	// 	$config['upload_path'] = './uploads/';
	// 	$config['allowed_types'] = 'csv';
	// 	$config['max_size']	= '0';
	// 	$config['max_width']  = '0';
	// 	$config['max_height']  = '0';
	// 	$config['overwrite'] = true;
	// 	$this->load->library('upload', $config);
		
	// 	if (!$this->upload->do_upload())
	// 	{			
	// 		$data['error'] = 'Please select a file';			
	// 	}	
	// 	else
	// 	{
	// 		$data = $this->upload->data();			
	// 		$filename = $data['file_name'];
	// 		$conf = $this->all_list->ins_db_file($filename);

	// 		if($conf){
	// 			$file = read_file('./uploads/'.$filename);
	// 			$file = substr($file, 0,-2); // 마지막 문장의 끝에 ::를 없앤다! 
	// 			$data = explode('::', $file);
	// 			$data_cou = count($data);
				
	// 			for($i = 1; $i < $data_cou; $i++){									
	// 				$text = explode('//', $data[$i]);					
	// 				$title = trim(mysql_real_escape_string(substr($text[0],0,-1)));					
	// 				$sentence = substr($text[1],1);
	// 				$sentence = trim(mysql_real_escape_string(substr($sentence, 0,-1)));					
	// 				$kind = substr($text[2],1);
	// 				$kind = substr($kind, 0,-1);
	// 				//essay 테이블에 문장 입력!
	// 				$this->all_list->insert_sentence($title,$sentence,$kind,$pj_id);
					
	// 			}					
	// 			$data['error'] = '';
	// 		}else{				
	// 			$data['error'] = 'Such file exists!';				
	// 		}			
	// 	}	
	// 	$data['cate'] = 'disc';
	// 	$this->load->view('head',$data);
		
	// 	$data['cate'] = 'status';
	// 	$data['list'] = $this->all_list->new_essayList($pj_id);
	// 	$data['count'] = $this->all_list->count($pj_id);
	// 	$data['pj_name'] = $this->all_list->pj_name($pj_id);
	// 	$data['pj_id'] = $pj_id;
	// 	$data['edi'] = $this->all_list->modal_editors($pj_id);

	// 	$this->load->view('new_list',$data);
	// 	$this->load->view('footer');

	// }		
}
?>