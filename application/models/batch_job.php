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
	
}
?>