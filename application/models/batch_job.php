<?php
class Batch_Job extends CI_Model{

	function copy_project_data($source_pj_id, $dest_pj_id) {
		$query = "SELECT * FROM adjust_data WHERE pj_id = $source_pj_id AND submit = 1 AND error = 'N' and ex_editing != ''";

		$source_data = $this->db->query($query);

		$user_list = array(5, 14, 15, 16);

		foreach ( $source_data->result() as $row) {
			$usr_id = $row->usr_id;
			do {
				$idx = rand(0,3);
				$new_usr_id = $user_list[$idx];
			} while ($usr_id == $new_usr_id);
			echo "$usr_id => $new_usr_id\n";
			 
   			$essay_id = $row->essay_id;
   			
   			$prompt = mysql_real_escape_string($row->prompt);
   			$raw_txt = mysql_real_escape_string($row->raw_txt);
   			$editing = mysql_real_escape_string($row->editing);
   			$ex_editing = mysql_real_escape_string($row->ex_editing);
   			$tagging = mysql_real_escape_string($row->tagging);
   			$critique = mysql_real_escape_string($row->critique);
   			$scoring = $row->scoring;
   			$score2 = $row->score2;
   			$word_count = $row->word_count;
   			$org_tag = $row->org_tag;
   			$replace_tag = $row->replace_tag;
   			//$discuss = $row->discuss;
   			//$draft = $row->draft;
   			//$submit = $row->submit;
   			//$time = $row->time;
   			$kind = $row->kind;
   			$type = $row->type;
   			//$pj_active = $row->pj_active;
   			$active = $row->active;
   			//$start_date = $row->start_date;
   			//$sub_date = $row->sub_date;

   			$pj_id = $dest_pj_id;

   			$ins = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,ex_editing, critique,scoring,score2,tagging,draft,word_count) 
   											VALUES('$new_usr_id','$essay_id','$prompt','$raw_txt','$kind',1,now(),'$pj_id','$editing','$ex_editing','$critique','$scoring','$score2','$tagging','1','$word_count')");


   			if(!$ins){
   				return false;
   			}
   		} // foreach end.

   		return true;
	}

   function import_museprep_data($source_pj_id, $id, $dest1_pj_id, $dest2_pj_id, $cnt) {
      $query = "SELECT * FROM adjust_data WHERE id = $id AND pj_id = $source_pj_id";

      $source_data = $this->db->query($query);

      $user_list = array(5, 14, 16);

      echo "import_museprep_data :: $source_pj_id || $id\n";

      foreach ( $source_data->result() as $row) {
         $idx = $cnt % 3;
         $new_usr_id_1 = $user_list[$idx];
         $idx = $idx+1;
         if ($idx == 3) $idx = 0;
         $new_usr_id_2 = $user_list[$idx];

         echo "usr_id : $new_usr_id_1, $new_usr_id_2\n";
          
            $essay_id = $row->essay_id;
            
            $prompt = mysql_real_escape_string($row->prompt);

            //echo "prompt : $prompt\n";

            $start_doubble_quotationConfirm = substr($prompt, 0, 2);
            $end_doubble_quotationConfirm = substr($prompt, -2);

            if ($start_doubble_quotationConfirm == '\"') {
               $prompt = substr($prompt, 2);
            }

            if ($start_doubble_quotationConfirm == '\r') {
               $prompt = substr($prompt, 2);
            }

            if ($end_doubble_quotationConfirm == '\"') {
               $prompt = substr($prompt, 0, -2);
            }

            $prompt = preg_replace("#^(\d+)&#", "", $prompt);

            //echo "prompt : $prompt\n";

            $raw_txt = mysql_real_escape_string($row->raw_txt);
            $editing = mysql_real_escape_string($row->editing);
            $ex_editing = mysql_real_escape_string($row->ex_editing);
            $tagging = mysql_real_escape_string($row->tagging);
            $critique = mysql_real_escape_string($row->critique);
            $word_count = $row->word_count;
            //$org_tag = $row->org_tag;
            //$replace_tag = $row->replace_tag;
            //$discuss = $row->discuss;
            //$draft = $row->draft;
            //$submit = $row->submit;
            //$time = $row->time;
            $kind = $row->kind;
            $type = $row->type;
            //$pj_active = $row->pj_active;
            $active = $row->active;
            //$start_date = $row->start_date;
            //$sub_date = $row->sub_date;

            $ins = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,ex_editing, critique,tagging,draft,word_count) 
                                    VALUES('$new_usr_id_1','$essay_id','$prompt','$raw_txt','$kind',1,now(),'$dest1_pj_id','$editing','$ex_editing','$critique','$tagging','1','$word_count')");

            if(!$ins){
               return false;
            }

            $ins = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,ex_editing, critique,tagging,draft,word_count) 
                                    VALUES('$new_usr_id_2','$essay_id','$prompt','$raw_txt','$kind',1,now(),'$dest2_pj_id','$editing','$ex_editing','$critique','$tagging','1','$word_count')");

            if(!$ins){
               return false;
            }
         } // foreach end.

         return true;
   }

      function make_sentence_count_musedata() {
         $query = "SELECT * FROM adjust_data WHERE sentence_count = 0 limit 1000";

         $source_data = $this->db->query($query);

         $total_count = 0;
         foreach ( $source_data->result() as $row) {
            $id = mysql_real_escape_string($row->id);
            $raw_txt = mysql_real_escape_string($row->raw_txt);
            $sentences = explode(".", $raw_txt);

            $sentence_count = 0;

            foreach($sentences as $sentence) {
                  if (strlen(trim($sentence)) > 3) {
                     $sentence_count++;
                  }
            }
            if ($sentence_count == 0) {
               $sentence_count = 1;
            }

            $result = $this->db->query("UPDATE adjust_data SET sentence_count = $sentence_count WHERE id = $id");
            if (!$result) {
               return false;
            }
            $total_count++;
         }

         return $total_count;
      }
	
      function make_sentence_count_writing() {
         $query = "SELECT * FROM service_data WHERE sentence_count = 0 limit 1000";

         $source_data = $this->db->query($query);

         $total_count = 0;
         foreach ( $source_data->result() as $row) {
            $id = mysql_real_escape_string($row->id);
            $raw_txt = mysql_real_escape_string($row->raw_txt);
            $sentences = explode(".", $raw_txt);

            $sentence_count = 0;

            foreach($sentences as $sentence) {
                  if (strlen(trim($sentence)) > 3) {
                     $sentence_count++;
                  }
            }
            if ($sentence_count == 0) {
               $sentence_count = 1;
            }

            $result = $this->db->query("UPDATE service_data SET sentence_count = $sentence_count WHERE id = $id");
            if (!$result) {
               return false;
            }
            $total_count++;
         }

         return $total_count;
      }

      function make_sentence_count_grammar() {
         $query = "SELECT * FROM grammar_muse WHERE sentence_count = 0 limit 1000";

         $source_data = $this->db->query($query);

         $total_count = 0;
         foreach ( $source_data->result() as $row) {
            $id = mysql_real_escape_string($row->id);
            $raw_txt = mysql_real_escape_string($row->sentence);
            $sentences = explode(".", $raw_txt);

            $sentence_count = 0;

            foreach($sentences as $sentence) {
                  if (strlen(trim($sentence)) > 3) {
                     $sentence_count++;
                  }
            }
            if ($sentence_count == 0) {
               $sentence_count = 1;
            }

            $result = $this->db->query("UPDATE grammar_muse SET sentence_count = $sentence_count WHERE id = $id");
            if (!$result) {
               return false;
            }
            $total_count++;
         }

         return $total_count;
      }

      function make_sentence_count_bbs() {
         $query = "SELECT id, answer FROM bbs_refine_data WHERE answer_md5 != '' and sentence_count = 0 limit 1000";

         $source_data = $this->db->query($query);

         $total_count = 0;
         foreach ( $source_data->result() as $row) {
            $id = mysql_real_escape_string($row->id);
            $raw_txt = mysql_real_escape_string($row->answer);
            $sentences = explode(".", $raw_txt);

            $sentence_count = 0;

            foreach($sentences as $sentence) {
                  if (strlen(trim($sentence)) > 3) {
                     $sentence_count++;
                  }
            }
            if ($sentence_count == 0) {
               $sentence_count = 1;
            }

            $idx =  rand(1,23);

            if ($idx < 10) {
               $date = "2014-07-0" . $idx;
            }
            else {
               $date = "2014-07-" . $idx;
            }

            $result = $this->db->query("UPDATE bbs_refine_data SET created = '$date', sentence_count = $sentence_count WHERE id = $id");
            if (!$result) {
               return false;
            }
            $total_count++;
         }

         return $total_count;
      }

      function dump_import_data() {
         $query = "SELECT id, essay FROM import_data WHERE pj_id in (14,16,20,22,23,24,25,26)";

         $source_data = $this->db->query($query);

         $total_count = 0;
         $result = array();
         foreach ( $source_data->result() as $row) {
            
            $id = $row->id;
            $essay = trim($row->essay);
            $sentence = explode('::', $essay); 
            //echo "sentence = $essay\n";

            if (count($sentence) > 1) {
               $total_count++;
               $prompt = $sentence[0];
               $temp = explode('&', $prompt);
               if (count($temp) == 2) {
                  $prompt = $temp[1];
               }

               $prompt = preg_replace("#(\\\r\\\n|\\\r|\\\n)#"," ",$prompt);
               $prompt = preg_replace("#(\\\\\r|\\\\)#","",$prompt);

            } else {
               continue;
            }

            $data = array();
            $data['id'] = $id;
            $data['prompt'] = $prompt;
            //echo "id = $id\n";
            //echo "prompt = $prompt\n";

            $result[] = $data;
         }

         echo "total_count = $total_count";
         return $result;
      }

      function make_training_data($pj_id, $usr_id, $start_id, $end_id, $limit, $start_tr_id, $tr_count)
      {
         $query = "SELECT prompt, raw_txt, scoring FROM adjust_data WHERE usr_id = $usr_id and pj_id = $pj_id and submit = 1 and id >= $start_id and id <= $end_id limit $limit";

         $source_data = $this->db->query($query);

         $total_count = 0;
         $result = array();

         $tr_id = $start_tr_id;
         $end_tr_id = $start_tr_id + $tr_count - 1;
         foreach ( $source_data->result() as $row) {
            
            $prompt = mysql_real_escape_string($row->prompt);
            $raw_txt = mysql_real_escape_string($row->raw_txt);
            $scoring = $row->scoring;

            $decoded_score = json_decode($scoring);
            //var_dump($decoded_score);
            $total_score = $decoded_score->total_score;

            $total_count++;
            echo "$tr_id $total_score $scoring <br>";

            $ins = $this->db->query("INSERT INTO training_data(tr_id, prompt,row_txt,score, muse_score) 
                                    VALUES($tr_id, '$prompt','$raw_txt',$total_score,'$scoring')");

            if(!$ins){
               return false;
            }

            $tr_id++;
            if ($tr_id > $end_tr_id) {
               $tr_id = $start_tr_id;
            }


         }

         echo "total_count = $total_count";
         return $result;


      }

       function make_training_data2($id, $tr_id)
      {
         $query = "SELECT prompt, raw_txt, editing, tagging, scoring FROM adjust_data WHERE id = $id";

         $source_data = $this->db->query($query);

         $total_count = 0;
         $result = array();

         foreach ( $source_data->result() as $row) {
            
            $prompt = mysql_real_escape_string($row->prompt);
            $raw_txt = mysql_real_escape_string($row->raw_txt);
            $editing = mysql_real_escape_string($row->editing);
            $tagging = mysql_real_escape_string($row->tagging);
            $scoring = $row->scoring;

            $decoded_score = json_decode($scoring);
            //var_dump($decoded_score);
            $total_score = $decoded_score->total_score;

            $total_count++;
            echo "$tr_id $total_score $scoring <br>";

            $ins = $this->db->query("INSERT INTO training_data(tr_id, prompt,row_txt,score, muse_score,editing,tagging) 
                                    VALUES($tr_id, '$prompt','$raw_txt',$total_score,'$scoring','$editing','$tagging')");

            if(!$ins){
               return false;
            }
         }

         return $result;


      }



}
?>