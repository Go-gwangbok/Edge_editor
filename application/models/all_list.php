<?php
class All_list extends CI_Model{
	function get_cate($service_id,$cate){
		return $this->db->query("SELECT * FROM data_kind WHERE service_id = '$service_id' and service_cate_name = '$cate'")->result();

	}

	function all_usr(){
   		return $this->db->query("SELECT * FROM usr WHERE classify = 1 and conf = 0 and active = 0")->result();   		
   	}   	

   	function getTask_kind_name($type,$kind_id){
   		return $this->db->query("SELECT task.name as task_name,data_kind.kind as kind_name 
   									FROM connect_templet 
   									LEFT JOIN data_kind ON data_kind.id = connect_templet.data_kind_id
   									LEFT JOIN task ON task.id = connect_templet.task_id   									
   									WHERE connect_templet.data_kind_id = '$kind_id'
   									AND connect_templet.task_id = '$type'
   									ORDER BY connect_templet.task_id")->row();
   	}

   	function get_kind_name($kind_id){
   		return $this->db->query("SELECT * FROM data_kind WHERE id = '$kind_id'")->row();
   	}

	function get_essay($data_id, $type = 1){
		if ($type == 1)
		{
			return $this->db->query("SELECT * FROM adjust_data WHERE id in($data_id)")->result();
		}
		else
		{
			return $this->db->query("SELECT * FROM service_data WHERE id in($data_id)")->result();
		}	
	}

	function getproject_name($pj_id){
		return $this->db->query("SELECT * FROM project where id = '$pj_id'")->row();
	}

	function get_project($usr_id){
		return $this->db->query("SELECT project.name, project.id AS pj_id, project.disc
									FROM adjust_data
									LEFT JOIN project ON adjust_data.pj_id = project.id
									LEFT JOIN usr ON adjust_data.usr_id = usr.id
									WHERE adjust_data.usr_id =  '$usr_id'
									AND adjust_data.active =0									
									and adjust_data.pj_active = 0
									GROUP BY project.id
									ORDER by project.add_date desc
									LIMIT 2")->result();
	}

	function get_one_essay($cate,$id){
		if($cate == 'service'){
			$from_table = 'service_data';
		}else{
			$from_table = 'adjust_data';
		}
		$query = "SELECT ".$from_table.".*,data_kind.kind as kind_name 
					FROM ".$from_table." 
					LEFT JOIN data_kind ON data_kind.id = ".$from_table.".kind
					WHERE ".$from_table.".id = '$id'";
		$result = $this->db->query($query);
		if($result->num_rows() > 0){
			return	$this->db->query($query)->row();
		}else{
			return false;
		}
	}		

	function pj_name($id){
   		return $this->db->query("SELECT project.*,data_kind.kind
   									FROM connect_usr_pj
   									LEFT JOIN project ON project.id = connect_usr_pj.pj_id
   									LEFT JOIN data_kind ON data_kind.id = connect_usr_pj.kind_id
   									WHERE project.id = '$id'
   									group by connect_usr_pj.pj_id")->row();
   	}

   	function pj_inmembers_list($pj_id){ // pj_id
   		return $this->db->query("SELECT usr.id AS usr_id, usr.name,project.name AS pj_name,connect_usr_pj.kind_id,connect_usr_pj.joindate as date, 
   									COUNT( adjust_data.essay_id ) AS count, 
   									COUNT( IF( adjust_data.submit =1, 1, NULL ) ) AS done_count, 
   									COUNT( IF( adjust_data.discuss =  'N', 1, NULL ) ) AS tbd, 
   									COUNT( IF( adjust_data.submit = 0 AND adjust_data.discuss =  'Y' AND adjust_data.error = 'N', 1, NULL ) ) AS share 
									FROM connect_usr_pj
									LEFT JOIN adjust_data ON adjust_data.pj_id = connect_usr_pj.pj_id AND connect_usr_pj.usr_id = adjust_data.usr_id
									LEFT JOIN project ON project.id = connect_usr_pj.pj_id
									LEFT JOIN usr ON usr.id = connect_usr_pj.usr_id
									WHERE connect_usr_pj.active =0
									AND connect_usr_pj.pj_id = '$pj_id'
									GROUP BY connect_usr_pj.usr_id
									ORDER BY connect_usr_pj.joindate DESC")->result();		
	}

	function service_inmembers_list(){ // pj_id
		return $this->db->query("SELECT usr.id AS usr_id, usr.name, usr.date,
									COUNT(IF(adjust_data.essay_id !=0, 1, NULL ) ) AS count, 
									count(if(adjust_data.submit = 1,1,null)) as done_count 
									FROM adjust_data
									LEFT JOIN usr ON usr.id = adjust_data.usr_id									
									WHERE adjust_data.type = 'writing'
									AND adjust_data.active =0									
									AND usr.classify =1
									AND usr.conf =0
									GROUP BY adjust_data.usr_id")->result();
	}	

	function get_enter_users($service_id,$year,$month){		
		return $this->db->query("SELECT usr.*,
									COUNT(essay_id) as done_count
									FROM service_data
									LEFT JOIN usr ON usr.id = service_data.usr_id									
									WHERE service_data.type = '$service_id'
									AND service_data.sub_date BETWEEN  '".$year."-".$month."-01 00:00:00' AND '".$year."-".$month."-31 23:59:59'
									ORDER BY service_data.usr_id")->result();
	}

	function get_service_datalist($service_id){
		return $this->db->query("SELECT service_data.*, usr.name
									FROM service_data
									LEFT JOIN usr ON usr.id = service_data.usr_id
									WHERE service_data.type = '$service_id'
									AND service_data.active = 0									
									ORDER BY sub_date DESC")->result();
	}

	function pj_add_users($pj_id,$users,$kind_id){
		$members = explode(',', $users); // ,가 없으면 false를 리턴한다!

		if(!$members){ // 유져가 1명 일때!
			$connect_usr_pj_confirm = $this->db->query("SELECT * FROM connect_usr_pj WHERE usr_id = '$users' and pj_id = '$pj_id' and active = 1");
			if($connect_usr_pj_confirm->num_rows() > 0){
				$connect_usr_pj_update = $this->db->query("UPDATE connect_usr_pj SET active = 0 WHERE usr_id = '$users' and pj_id = '$pj_id' and active = 1");
				if(!$connect_usr_pj_update){
					return false;
				}else{
					$confirm = $this->db->query("SELECT * FROM adjust_data WHERE usr_id = '$users' and pj_id = '$pj_id' and pj_active = 1 and active = 0");
					if($confirm->num_rows() > 0){  // 기존에 프로젝트에서 submit한 데이터가 있다면, 데이터를 같이 살려서 유져를 생성한다!
						$update = $this->db->query("UPDATE adjust_data SET pj_active = 0 WHERE usr_id = '$users' and pj_id = '$pj_id' and active = 0 and pj_active = 1");
						if(!$update){
							return false;
						}
					}
				}
			}else{ // 처음 생성되는 유져일 경우!
				$ins_user = $this->db->query("INSERT INTO connect_usr_pj(usr_id,pj_id,kind_id) VALUES('$users','$pj_id','$kind_id')");
				if(!$ins_user){
					return false;
				}
			}   							  				
		}else{
			foreach ($members as $mem) {
				$connect_usr_pj_confirm = $this->db->query("SELECT * FROM connect_usr_pj WHERE usr_id = '$mem' and pj_id = '$pj_id' and active = 1");
				if($connect_usr_pj_confirm->num_rows() > 0){
					$connect_usr_pj_update = $this->db->query("UPDATE connect_usr_pj SET active = 0 WHERE usr_id = '$mem' and pj_id = '$pj_id' and active = 1");
					if(!$connect_usr_pj_update){
						return false;
					}else{
						$confirm = $this->db->query("SELECT * FROM adjust_data WHERE usr_id = '$mem' and pj_id = '$pj_id' and pj_active = 1 and active = 0");
						if($confirm->num_rows() > 0){  // 기존에 프로젝트에서 submit한 데이터가 있다면, 데이터를 같이 살려서 유져를 생성한다!
							$update = $this->db->query("UPDATE adjust_data SET pj_active = 0 WHERE usr_id = '$mem' and pj_id = '$pj_id' and active = 0 and pj_active = 1");
							if(!$update){
								return false;
							}
						}
					}
				}else{ // 처음 생성되는 유져일 경우!
   					$ins_user = $this->db->query("INSERT INTO connect_usr_pj(usr_id,pj_id,kind_id) VALUES('$mem','$pj_id','$kind_id')");
   					if(!$ins_user){
   						return false;
   					}
   				}	   			
	   		}
		}
		return true; 
	}

	function del_user($pj_id,$usr_id){
		$get_user = $this->db->query("SELECT * FROM adjust_data WHERE pj_id = '$pj_id' and usr_id = '$usr_id' and active = 0");
		foreach ($get_user->result() as $rows) {
			$submit = $rows->submit;
			$id = $rows->id;  // adjust_data의 고유 아이디번호!

			if($submit != 1){ // submit을 하지 않는것은 모두 지운다!
				$data_del = $this->db->query("UPDATE adjust_data SET pj_active = 1, active = 1 WHERE id = '$id'");
				if(!$data_del){
					return false;
				}				
			}elseif($submit == 1){
				$pj_del = $this->db->query("UPDATE adjust_data SET pj_active = 1 WHERE id = '$id'");
				if(!$pj_del){					
					return false;					
				}
			}
		}

		$connect_update = $this->db->query("UPDATE connect_usr_pj SET active = 1 WHERE usr_id = '$usr_id' and pj_id = '$pj_id'");
		if(!$connect_update){
			return false;
		}else{
			return true;			
		}		
	}

	function get_user($usr_id){
		return $this->db->query("SELECT * FROM usr WHERE classify = 1 and conf = 0 and active = 0 and id = '$usr_id'")->row();
	}

	function usr_pj_name($usr_id,$pj_id){
		return $this->db->query("SELECT usr.name AS usr_name, project.name AS pj_name
									FROM adjust_data
									LEFT JOIN usr ON usr.id = adjust_data.usr_id
									LEFT JOIN project ON project.id = adjust_data.pj_id
									WHERE adjust_data.usr_id =  '$usr_id'
									AND project.id =  '$pj_id' LIMIT 1")->row();
	}	

	function admin_tbd_submit($usr_id,$data_id,$editing,$critique,$tagging,$scoring,$score2,$time){		
		return $this->db->query("UPDATE adjust_data SET editing = '$editing',critique = '$critique',tagging = '$tagging',scoring = '$scoring', score2 = '$score2', draft = 1,submit = 1,sub_date = now(), time = '$time', discuss = 'Y', usr_id = '$usr_id' WHERE id = '$data_id'");
	}	

	function admin_tbd_draft($data_id,$editing,$critique,$tagging,$scoring,$score2,$time){				
		return $this->db->query("UPDATE adjust_data SET editing = '$editing',critique = '$critique',tagging = '$tagging',draft = 1,scoring = '$scoring', score2 = '$score2', time = '$time' WHERE id = '$data_id'");				
	}

	function dos_avg(){
   		return $this->db->query("SELECT sum(word_count) as total_word_count,
								sum(replace_tag) as replace_count
								FROM adjust_data WHERE essay_id != 0 and replace_tag != 0 and submit = 1 and active = 0 and word_count > 100 and ex_editing != ''")->row();
   	}

	function pj_totalcount($pj_id){		
		return $this->db->query("SELECT COUNT( * ) AS count, 
									COUNT( IF( draft = 1 AND discuss =  'Y'AND submit = 0, 1, NULL ) ) AS draft, 
									COUNT( IF( submit = 1, 1, NULL ) ) AS submit, 
									COUNT( IF( discuss =  'Y', NULL , 1 ) ) AS discuss, 
									COUNT( IF( draft = 0 AND submit = 0, 1, NULL ) ) AS todo,
									sum(word_count) AS pj_total_word_count
									FROM adjust_data
									WHERE pj_id = '$pj_id'
									AND essay_id != 0									
									AND active = 0")->row();
	}

	function stats_data($pj_id){
		$query = "SELECT COUNT( IF( discuss =  'N' AND submit =0, 1, NULL ) ) AS tbd, usr.name, 
					SUM( IF( submit =1 and time > 18000 and ex_editing != '', TIME, 0 ) ) AS total_time, 
					SUM( IF( submit =1 and time > 18000 and ex_editing != '', word_count, 0 ) ) AS word_count, 
					COUNT( IF( discuss =  'Y' AND adjust_data.essay_id !=0, 1, NULL ) ) AS total, 
					COUNT( IF( submit = 1, 1, NULL ) ) AS submit,
					sum( IF( submit = 1, org_tag, 0 ) ) AS org_tag,
					sum( IF( submit = 1, replace_tag, 0 ) ) AS actual_tag,
					SUM( IF( submit = 1 and replace_tag != 0, word_count, 0 ) ) AS replace_word,
					SUM( IF( submit = 0 and replace_tag = 0, word_count, 0 ) ) AS todo_word,
					SUM( IF( submit = 1 and replace_tag = 0, word_count, 0 ) ) AS replace_error_word,
					SUM( IF( submit = 1 and replace_tag != 0, word_count, 0 ) ) AS avg_total_word,
					SUM(word_count) AS pj_each_word,
					error_count.count AS error_count
					FROM adjust_data
					LEFT JOIN usr ON usr.id = adjust_data.usr_id
					LEFT JOIN error_count ON error_count.usr_id = adjust_data.usr_id AND error_count.pj_id = adjust_data.pj_id
					WHERE adjust_data.pj_id =  '$pj_id'
					AND adjust_data.active =0
					and adjust_data.essay_id != 0
					GROUP BY adjust_data.usr_id";
		return $this->db->query($query)->result();
	}

	function get_pay_data(){
		$query = "SELECT usr.name, 
					SUM( IF( submit =1 and time > 18000 and ex_editing != '', TIME, 0 ) ) AS total_time, 
					SUM( IF( submit =1 and time > 18000 and ex_editing != '', word_count, 0 ) ) AS word_count, 
					COUNT( IF( discuss =  'Y' AND adjust_data.essay_id !=0, 1, NULL ) ) AS total, 
					COUNT( IF( submit = 1, 1, NULL ) ) AS submit,
					sum( IF( submit = 1, org_tag, 0 ) ) AS org_tag,
					sum( IF( submit = 1, replace_tag, 0 ) ) AS actual_tag,
					SUM( IF( submit = 1 and replace_tag != 0, word_count, 0 ) ) AS replace_word,
					SUM( IF( submit = 0 and replace_tag = 0, word_count, 0 ) ) AS todo_word,
					SUM( IF( submit = 1 and replace_tag = 0, word_count, 0 ) ) AS replace_error_word,
					SUM( IF( submit = 1 and replace_tag != 0, word_count, 0 ) ) AS avg_total_word
					FROM adjust_data
					LEFT JOIN usr ON usr.id = adjust_data.usr_id										
					where pj_id != 0					
					AND adjust_data.active =0
					and adjust_data.essay_id != 0					
					and adjust_data.usr_id != 1
					and type = 'musedata'
					GROUP BY adjust_data.usr_id";
		return $this->db->query($query)->result();
	}

	function error_count_up($usr_id,$pj_id){
		$chk = $this->db->query("SELECT * FROM error_count WHERE usr_id = '$usr_id' and pj_id = '$pj_id'");
		if($chk->num_rows() > 0){
			return $count_up = $this->db->query("UPDATE error_count set count = count+1 WHERE usr_id = '$usr_id' and pj_id = '$pj_id'");
		}else{
			return $this->db->query("INSERT INTO error_count(pj_id,usr_id,count) VALUES('$pj_id','$usr_id',1)");
		}
	}

	function editor_pjlist($usr_id){  
  		return $this->db->query("SELECT pj_id, project.name, project.disc, project.add_date, adjust_data.usr_id,data_kind.kind as kind_name,
									count(distinct adjust_data.essay_id) as total_count,
									count(if(adjust_data.submit = 1,1,null)) as completed,
									count(if(adjust_data.discuss = 'N',1,null)) as tbd
									FROM adjust_data
									LEFT JOIN project ON project.id = adjust_data.pj_id
									LEFT JOIN data_kind ON data_kind.id = adjust_data.kind
									WHERE usr_id =  '$usr_id'									
									AND adjust_data.pj_active = 0
									AND adjust_data.active = 0
									GROUP BY pj_id
									ORDER BY add_date DESC")->result();
   	}

   	function word_count_update(){
   		
   		$query = $this->db->query("SELECT * FROM adjust_data WHERE essay_id != 0 and word_count = 0 limit 2000");

   		foreach ($query->result() as $row)
		{
   			$id = $row->id;
   			$raw_txt = $row->raw_txt;
   			$count = str_word_count($raw_txt);   			
   			$this->db->query("UPDATE adjust_data SET word_count = '$count' WHERE id = '$id'");
   		}   		
   		return true;
   	}

   	function detecting_count(){
   		$query = $this->db->query("SELECT * FROM adjust_data WHERE essay_id != 0 and submit = 1 and active = 0 and ex_editing != ''");
   		//$query = $this->db->query("SELECT * FROM adjust_data WHERE essay_id = 10883");
   		foreach ($query->result() as $row)
		{
   			$id = $row->id;
   			$editing = $row->editing;
   			$ex_editing = $row->ex_editing;

   			preg_match_all("|<u>|", $editing, $u_matches);
   			preg_match_all("|<strike>|", $editing, $s_matches);
   			preg_match_all("|<b>|", $editing, $b_matches);

   			preg_match_all("|</mod>|", $ex_editing, $mod_matches);
   			preg_match_all("|</ins>|", $ex_editing, $ins_matches);
   			preg_match_all("|</del>|", $ex_editing, $del_matches);   			

   			$tag_count = count($u_matches[0])+count($s_matches[0])+count($b_matches[0]);
   			$det_count = count($mod_matches[0])+count($ins_matches[0])+count($del_matches[0]);
   			
   			$this->db->query("UPDATE adjust_data SET org_tag = '$tag_count', replace_tag = '$det_count' WHERE id = '$id'");
   			
   		}   		
   		return true;
   	}

   	function garbage_data_del($data_id,$garbage_data_del, $type = 1){
   		if ($type == 1)
   		{
   			$result = $this->db->query("UPDATE adjust_data SET editing = '$garbage_data_del' WHERE id = '$data_id'");
   		}
   		else
   		{
   			$result = $this->db->query("UPDATE service_data SET editing = '$garbage_data_del' WHERE id = '$data_id'");
   		}
   		
   		return $result;      
   	}   	


   	// Export Sql

   	function export_page_count($pj_id){
   		return $this->db->query("SELECT count(id) as count FROM adjust_data WHERE pj_id = '$pj_id' and essay_id != 0 and discuss = 'Y' and  active = 0 and submit = 1 and ex_editing != ''")->row();
   	}   	

   	function export_index($pj_id){
   		return $this->db->query("SELECT count(adjust_data.essay_id) as total_count,
									count(if(adjust_data.ex_editing != '',1,null)) as export_count,
									project.name
									FROM adjust_data 
									left join project ON project.id = adjust_data.pj_id
									WHERE pj_id = '$pj_id' 
									and essay_id != 0 
									and discuss = 'Y' 									
									and adjust_data.active = 0 
									and submit = 1")->row();
   	}   	

   	function export_list($pj_id,$limit,$list){
   		$query = "SELECT adjust_data.*,usr.name,data_kind.kind as kind_name
					FROM adjust_data
					left join usr on usr.id = adjust_data.usr_id
					LEFT JOIN data_kind ON data_kind.id = adjust_data.kind
					WHERE adjust_data.pj_id = '$pj_id'					
					AND essay_id != 0
					AND adjust_data.active = 0					
					AND submit = 1
					AND ex_editing != ''
					LIMIT $limit,$list";
		return $this->db->query($query)->result();
   	}


   	// Error Chk Sql
	function ex_editing_update_service($essay_id,$replace_data,$before_editing, $type = 1){
	   		preg_match_all("|<u>|", $before_editing, $u_matches);
			preg_match_all("|<strike>|", $before_editing, $s_matches);
			preg_match_all("|<b>|", $before_editing, $b_matches);

			preg_match_all("|</mod>|", $replace_data, $mod_matches);
			preg_match_all("|</ins>|", $replace_data, $ins_matches);
			preg_match_all("|</del>|", $replace_data, $del_matches);   			

			$org_tag = count($u_matches[0])+count($s_matches[0])+count($b_matches[0]);
			$replace_tag = count($mod_matches[0])+count($ins_matches[0])+count($del_matches[0]);


	   		if ($type == 1)
	   		{
	   			$result = $this->db->query("UPDATE adjust_data SET ex_editing = '$replace_data', org_tag = '$org_tag', replace_tag = '$replace_tag' WHERE id = '$essay_id'");
	   		}
	   		else
	   		{
	   			$result = $this->db->query("UPDATE service_data SET ex_editing = '$replace_data', org_tag = '$org_tag', replace_tag = '$replace_tag' WHERE id = '$essay_id'");
	   		}
	   		
	   		return $result;
	}

	function error_replace($data_id,$replace_data){
			preg_match_all("|</mod>|", $replace_data, $mod_matches);
			preg_match_all("|</ins>|", $replace_data, $ins_matches);
			preg_match_all("|</del>|", $replace_data, $del_matches);   			

			$replace_tag = count($mod_matches[0])+count($ins_matches[0])+count($del_matches[0]);

	   		$result = $this->db->query("UPDATE adjust_data SET ex_editing = '$replace_data', replace_tag = '$replace_tag' WHERE id = '$data_id'");
	   		return $result;      
	}







	// Service Sql
 	function get_serviceId_num($service_name){
 		return $this->db->query("SELECT * FROM task WHERE name = '$service_name'")->row();
 	}

	function get_service_list(){
		return $this->db->query("SELECT * FROM task WHERE type = 'service'")->result();
	}

	function service_all_year_data($service_id){ // 서비스가 시작한 모든 년도를 리턴한다!
		$query = "SELECT distinct DATE_FORMAT(sub_date, '%Y') as year,type
					FROM service_data
					WHERE sub_date
					BETWEEN '2013-01-01 00:00:00'
					AND now()										
					AND active = 0
					AND type = '$service_id'
					order by year desc";
		return $this->db->query($query)->result();
	}

	function service_month_data($yen,$type_id){
		$start = $yen."-01-01 00:00:00";
		$end = $yen."-12-31 23:59:59";	

		$query = "SELECT DISTINCT DATE_FORMAT( sub_date,  '%Y-%m' ) AS month , 
					COUNT( IF( sub_date BETWEEN  '".$yen."-01-01 00:00:00'AND  '".$yen."-01-31 23:59:59', 1, NULL ) ) AS 01month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-02-01 00:00:00'AND  '".$yen."-02-29 23:59:59', 1, NULL ) ) AS 02month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-03-01 00:00:00'AND  '".$yen."-03-31 23:59:59', 1, NULL ) ) AS 03month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-04-01 00:00:00'AND  '".$yen."-04-30 23:59:59', 1, NULL ) ) AS 04month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-05-01 00:00:00'AND  '".$yen."-05-31 23:59:59', 1, NULL ) ) AS 05month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-06-01 00:00:00'AND  '".$yen."-06-30 23:59:59', 1, NULL ) ) AS 06month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-07-01 00:00:00'AND  '".$yen."-07-31 23:59:59', 1, NULL ) ) AS 07month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-08-01 00:00:00'AND  '".$yen."-08-31 23:59:59', 1, NULL ) ) AS 08month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-09-01 00:00:00'AND  '".$yen."-09-30 23:59:59', 1, NULL ) ) AS 09month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-10-01 00:00:00'AND  '".$yen."-10-31 23:59:59', 1, NULL ) ) AS 10month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-11-01 00:00:00'AND  '".$yen."-11-30 23:59:59', 1, NULL ) ) AS 11month,
					COUNT( IF( sub_date BETWEEN  '".$yen."-12-01 00:00:00'AND  '".$yen."-12-31 23:59:59', 1, NULL ) ) AS 12month
					FROM service_data
					WHERE sub_date
					BETWEEN  '$start'
					AND  '$end'					
					AND active = 0
					AND type = '$type_id'
					group BY month DESC";
		return $this->db->query($query)->result();
	}

	function get_service_mem_completedCount($year,$month,$usr_id,$service_id){
		$query = "SELECT count(*) as count 
					FROM service_data
					WHERE sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 23:59:59'					
					AND usr_id = '$usr_id'
					and type = '$service_id'
					and active = 0
					AND submit = 1";
		return $this->db->query($query)->row();
	}

	function get_service_mem_completedData($usr_id,$year,$month,$service_id,$limit,$page_list){
		$query = "SELECT service_data.*,data_kind.kind
					FROM service_data
					LEFT JOIN data_kind ON data_kind.id = service_data.kind
					WHERE service_data.sub_date
					BETWEEN '".$year."-".$month."-01 00:00:00'
					AND '".$year."-".$month."-31 23:59:59'					
					AND usr_id = '$usr_id'
					and active = 0
					AND submit = 1
					and type = '$service_id'				
					ORDER BY sub_date DESC
					LIMIT $limit,$page_list";
		return $this->db->query($query)->result();
	}

	function admin_pjlist(){
		$query = "SELECT connect_usr_pj.pj_id, project.name, project.disc, project.add_date, data_kind.kind,data_kind.id as kind_id,
					COUNT(adjust_data.essay_id) AS total_count,
					COUNT( IF( adjust_data.submit =1, 1, NULL ) ) AS completed,
					COUNT( IF( adjust_data.submit = 0, 1, NULL ) ) AS todo
					FROM connect_usr_pj
					LEFT JOIN adjust_data ON adjust_data.pj_id = connect_usr_pj.pj_id
					LEFT JOIN usr ON usr.id = connect_usr_pj.usr_id
					LEFT JOIN project ON project.id = connect_usr_pj.pj_id
					LEFT JOIN data_kind ON data_kind.id = connect_usr_pj.kind_id
					WHERE connect_usr_pj.active = 0
					GROUP BY connect_usr_pj.pj_id
					ORDER BY project.add_date DESC";		
		return $this->db->query($query)->result();
	}

	function get_service_export_count($year,$month){
		$query = "SELECT count(*) as count 
					FROM adjust_data
					WHERE sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 23:59:59'
					AND essay_id !=0						
					and type != 'musedata'				
					AND submit = 1					
					and active = 0";
		return $this->db->query($query)->row();
	}

	function get_service_export_data($year,$month,$limit,$page_list){
		$query = "SELECT adjust_data.*,usr.name
					FROM adjust_data
					LEFT join usr ON usr.id = adjust_data.usr_id
					WHERE adjust_data.sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 23:59:59'
					AND essay_id !=0										
					and adjust_data.active = 0
					AND adjust_data.submit = 1
					and adjust_data.type != 'musedata'				
					ORDER BY sub_date DESC
					LIMIT $limit,$page_list";
		return $this->db->query($query)->result();
	}

	function service_export_total_count($month,$year){
		$query = "SELECT count(*) as count, count(if(ex_editing != '',1,null)) as export_count
					FROM adjust_data
					WHERE sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 23:59:59'
					AND essay_id !=0						
					and type != 'musedata'				
					AND submit = 1					
					and active = 0";
		return $this->db->query($query)->row();
	}

	function get_service_error_count($month,$year){
		$query = "SELECT count(*) as error_count
					FROM adjust_data
					WHERE sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 23:59:59'
					AND essay_id !=0						
					and type != 'musedata'				
					AND submit = 1
					and ex_editing = ''					
					and active = 0";
		return $this->db->query($query)->row();
	}

	function get_service_error_list($month,$year,$limit,$page_list){
		$query = "SELECT adjust_data.*,usr.name,usr.id as usr_id
					FROM adjust_data
					LEFT join usr ON usr.id = adjust_data.usr_id
					WHERE adjust_data.sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 23:59:59'
					AND essay_id !=0
					AND ex_editing = ''
					and adjust_data.type != 'musedata'
					AND adjust_data.submit = 1
					and adjust_data.active = 0										
					LIMIT $limit,$page_list";
		return $this->db->query($query)->result();
	}

	// Service Sql End


	

	// Setting Sql

	function get_task_list(){
		return $this->db->query("SELECT * FROM task")->result();
	}

	function active_task($usr_id){
		return $this->db->query("SELECT connect_usr_task.*,task.name
									FROM connect_usr_task
									LEFT JOIN task ON task.id = connect_usr_task.task_id
									WHERE connect_usr_task.usr_id = '$usr_id'
									AND connect_usr_task.active = 0")->result();	
	}

	function get_Editors(){
   		return $this->db->query("SELECT * FROM usr WHERE active = 0 and classify = 1 ORDER BY date desc")->result();
   	}	

   	function data_kind(){
   		return $this->db->query("SELECT * FROM data_kind")->result();
   	}

	function new_editor_accept($usr_id){
   		return $this->db->query("UPDATE usr SET conf = 0 WHERE id = '$usr_id'");   		
   	}

   	function new_editor_decline($id){
   		$result = $this->db->query("UPDATE usr SET conf = 0, active = 1 WHERE id = '$id'");
   		if($result){
   			return true;
   		}
   		else{
   			return false;
   		}
   	}

   	function accept_ok($usr_id,$musedata,$writing,$type,$pay,$start,$end){
   		$update = $this->db->query("UPDATE usr SET conf = 0, musedata = '$musedata', writing = '$writing', type = '$type', pay = '$pay', start = '$start', end = '$end', date = now() WHERE id = '$usr_id'");
   		if($update){
   			return $this->db->query("INSERT INTO adjust_data(usr_id,type) VALUES('$usr_id','join')");
   		}else{
   			return false;	
   		}
   		
   	}

   	function member_edit_save($usr_id,$task_ids,$type,$start,$end,$pay,$editor_desc,$email){
   		$tasks = explode(',',$task_ids);   		

   		$usr_task_confirm = $this->db->query("SELECT * FROM connect_usr_task WHERE usr_id = '$usr_id'");

   		if($usr_task_confirm->num_rows() > 0){   			
			$task_unactive = $this->db->query("UPDATE connect_usr_task SET active = 1 WHERE usr_id = '$usr_id'");   			
   			if($task_unactive){
   				if($tasks){ // Task를 여러개 선택했을때.	
   					foreach ($tasks as $value) {
					 	$task_confirm = $this->db->query("SELECT * FROM connect_usr_task WHERE usr_id = '$usr_id' and task_id = '$value'");

					 	if($task_confirm->num_rows() > 0){
					 		$task_active = $this->db->query("UPDATE connect_usr_task SET active = 0 WHERE usr_id = '$usr_id' and task_id = '$value'");	
					 		if(!$task_active){
					 			return false;
					 		}
					 	}else{
					 		$insert_task = $this->db->query("INSERT INTO connect_usr_task(usr_id,task_id,joindate) VALUES('$usr_id','$value',now())");
					 		if(!$insert_task){
				 				return false;
				 			}
					 	}   				 	
					}
   				}else{ // Task 를 한개만 선택했을때.
   					$task_confirm = $this->db->query("SELECT * FROM connect_usr_task WHERE usr_id = '$usr_id' and task_id = '$task_ids'");

				 	if($task_confirm->num_rows() > 0){
				 		$task_active = $this->db->query("UPDATE connect_usr_task SET active = 0 WHERE usr_id = '$usr_id' and task_id = '$task_ids'");	
				 		if(!$task_active){
					 			return false;
					 		}
				 	}else{
				 		$insert_task = $this->db->query("INSERT INTO connect_usr_task(usr_id,task_id,joindate) VALUES('$usr_id','$task_ids',now())");
				 		if(!$insert_task){
				 			return false;
				 		}
				 	}   				 	
   				}   				
   			}else{
   				return false;
   			} 			

   		}else{ // New editor 이거나 한번도 셋팅을 하지 않은 Editor.   		
   			
			foreach ($tasks as $value) {
				$insert_task = $this->db->query("INSERT INTO connect_usr_task(usr_id,task_id,joindate) VALUES('$usr_id','$value',now())");
		 		if(!$insert_task){
		 			return false;
		 		}		
			}
		}

   		if(in_array('2',$tasks,true)){ // Writing 서비스를 이용하는 경우!
   			return $this->db->query("UPDATE usr SET type = '$type', pay = '$pay', start = '$start', end = '$end', description = $editor_desc WHERE id = '$usr_id'");	   			
   		}else{ // Writing 서비스를 이용하지 않는경우!
   			return $this->db->query("UPDATE usr SET type = '$type', pay = '$pay', start = '$start', end = '$end' WHERE id = '$usr_id'");
   		}
   	}   	

   	function get_setup_tag($kind_id){
   		return $this->db->query("SELECT tags.tag,tags.id as tags_id,connect_tags.chk
   									FROM connect_tags
   									LEFT JOIN data_kind ON data_kind.id = connect_tags.data_kind_id
   									LEFT JOIN tags ON tags.id = connect_tags.tags_id
   									WHERE data_kind.id = '$kind_id'
   									and connect_tags.active = 0")->result();  		
   	}  

   	function get_setup_scores($kind_id){
   		return $this->db->query("SELECT score_type.name,score_type.id as score_id,connect_score.chk
   									FROM connect_score
   									LEFT JOIN data_kind ON data_kind.id = connect_score.data_kind_id
   									LEFT JOIN score_type ON score_type.id = connect_score.score_type_id
   									WHERE data_kind.id = '$kind_id'
   									and connect_score.active = 0")->result();   		
   	}
   	
   	function setting_add_sco($kind_id,$add_sco_id){
   		$scos = explode(',', $add_sco_id);
   		foreach ($scos as $sco_id) {
   			$tag_confirm = $this->db->query("SELECT * FROM connect_score WHERE score_type_id = '$sco_id' and data_kind_id = '$kind_id'");
   			if($tag_confirm->num_rows() > 0){
   				$update = $this->db->query("UPDATE connect_score SET chk = 'Y',active = 0,action_time = now() WHERE score_type_id = '$sco_id' and data_kind_id = '$kind_id'");
   				if(!$update){
	   				return false;
	   			}
   			}else{
	   			$ins = $this->db->query("INSERT INTO connect_score(score_type_id,data_kind_id,action_time) VALUES('$sco_id','$kind_id',now())");	
	   			if(!$ins){
	   				return false;
	   			}
   			}
   		}
   		return true;   		
   	}

   	function setting_sco_del($kind_id,$sco_id){
   		$sco_id = substr($sco_id, 1);
   		return $this->db->query("UPDATE connect_score SET active = 1,chk = 'N' WHERE score_type_id = '$sco_id' and data_kind_id = '$kind_id'");
   	}

   	function setting_add_tag($kind_id,$add_tags_id){
   		$tags = explode(',', $add_tags_id);
   		foreach ($tags as $tag_id) {
   			$tag_confirm = $this->db->query("SELECT * FROM connect_tags WHERE tags_id = '$tag_id' and data_kind_id = '$kind_id'");
   			if($tag_confirm->num_rows() > 0){
   				$update = $this->db->query("UPDATE connect_tags SET chk = 'Y',active = 0,action_time = now() WHERE tags_id = '$tag_id' and data_kind_id = '$kind_id'");
   				if(!$update){
	   				return false;
	   			}
   			}else{
   				$ins = $this->db->query("INSERT INTO connect_tags(tags_id,data_kind_id,action_time) VALUES('$tag_id','$kind_id',now())");	
	   			if(!$ins){
	   				return false;
	   			}
   			}   			
   		}
   		return true;   		
   	}

   	function setting_tag_del($tag_id,$kind_id){
   		$tag_id = substr($tag_id, 1);
   		return $this->db->query("UPDATE connect_tags SET active = 1,chk = 'N', action_time = now() WHERE tags_id = '$tag_id' and data_kind_id = '$kind_id'");
   	}   	

   	function get_tag($kind){
   		return $this->db->query("SELECT tags.tag,tags.id as tag_id,tags.appear
   									FROM connect_tags
   									LEFT JOIN data_kind ON data_kind.id = connect_tags.data_kind_id
   									LEFT JOIN tags ON tags.id = connect_tags.tags_id
   									WHERE data_kind.id = '$kind'
   									AND connect_tags.active = 0
   									AND connect_tags.chk = 'Y'")->result();
   	}   	

   	function get_scores_temp($kind){
   		return $this->db->query("SELECT score_type.name,score_type.id as score_id
   									FROM connect_score
   									LEFT JOIN data_kind ON data_kind.id = connect_score.data_kind_id
   									LEFT JOIN score_type ON score_type.id = connect_score.score_type_id
   									WHERE data_kind.id = '$kind'
   									AND connect_score.active = 0
   									AND connect_score.chk = 'Y'")->result();  		
   	}

   	function get_templet_ele($type,$kind){
   		return $this->db->query("SELECT templet_ele.element, templet_ele.view_ele, data_kind.id AS kind_id
									FROM connect_templet
									LEFT JOIN templet_ele ON templet_ele.id = connect_templet.templet_ele_id
									LEFT JOIN data_kind ON data_kind.id = connect_templet.data_kind_id
									LEFT JOIN task ON task.id = connect_templet.task_id
									WHERE data_kind.id = '$kind'
									AND task.id = '$type'
									AND connect_templet.active = 0")->result();
	}   	

   	function saveSetting($type_id,$kind_id,$tabs_val){   		
   		//Tab Save   		
   		if($tabs_val != ''){ 
   			$all_tab_updata = $this->db->query("UPDATE connect_templet SET active = '1' WHERE active = 0 and data_kind_id = '$kind_id' and task_id = '$type_id'");
   			if($all_tab_updata){
   				$tab_array = explode(',', $tabs_val); // original,detecting,score...
		   		foreach ($tab_array as $value) {
		   			$tab_id = substr($value,1);		
		   			// 체크되어진 값만 다시 Y로 변경한다!   			
		   			$this->db->query("UPDATE connect_templet SET active = 0 WHERE templet_ele_id = '$tab_id' and data_kind_id = '$kind_id' and task_id = '$type_id'");	
		   		}
   			}	
   		}else{
   			return false;
   		}   		
   		return true;
   	}

   	function rubricSaveSetting($kind_id,$tags_val,$scores_val){   		   		
   		// Tag Save   		
   		if($tags_val != ''){ 
   			$all_tag_updata = $this->db->query("UPDATE connect_tags SET chk = 'N' WHERE active = 0 and data_kind_id = '$kind_id'");
   			if($all_tag_updata){
   				$tag_array = explode(',', $tags_val); // tr,co,is,ex
		   		foreach ($tag_array as $value) {
		   			$tag_id = substr($value,1);		
		   			// 체크되어진 값만 다시 Y로 변경한다!   			
		   			$update = $this->db->query("UPDATE connect_tags SET chk = 'Y',action_time = now() WHERE active = 0 and tags_id = '$tag_id' and data_kind_id = '$kind_id'");	
		   		}
   			}	
   		}else{
   			return false;
   		}

   		// Score Save   		
   		if($scores_val != ''){ 
   			$all_score_updata = $this->db->query("UPDATE connect_score SET chk = 'N' WHERE active = 0 and data_kind_id = '$kind_id'");
   			if($all_tag_updata){
   				$score_array = explode(',', $scores_val); // tr,co,is,ex
		   		foreach ($score_array as $value) {
		   			$score_id = substr($value,1);		
		   			// 체크되어진 값만 다시 Y로 변경한다!   			
		   			$update = $this->db->query("UPDATE connect_score SET chk = 'Y',action_time = now() WHERE active = 0 and score_type_id = '$score_id' and data_kind_id = '$kind_id'");	
		   		}
   			}	
   		}else{
   			return false;
   		}
   		return true;
   	}

   	function add_tag_data($data_kind_id){
   		return $this->db->query("SELECT * FROM tags	where tags.id not in(select connect_tags.tags_id from connect_tags where connect_tags.data_kind_id = '$data_kind_id' and active = 0)")->result();
   	}

   	function add_score_data($data_kind_id){
   		return $this->db->query("SELECT * FROM score_type where score_type.id not in(select connect_score.score_type_id from connect_score where connect_score.data_kind_id = '$data_kind_id' and active = 0)")->result();
   	}

   	// function getService_type($type){
   	// 	return $this->db->query("SELECT * FROM task WHERE id = '$type'")->result();
   	// }

   	function getDataKind($task_id,$from_table){
   		// return $this->db->query("SELECT data_kind.* 
   		// 							FROM ".$from_table."
   		// 							LEFT JOIN data_kind ON data_kind.id = ".$from_table.".kind
   		// 							WHERE ".$from_table.".type = '$task_id'
   		// 							and ".$from_table.".active = 0
   		// 							GROUP BY data_kind.id")->result();

   		return $this->db->query("SELECT data_kind.* 
									FROM connect_templet
									LEFT JOIN data_kind ON data_kind.id = connect_templet.data_kind_id
									LEFT JOIN task ON task.id = connect_templet.id
									WHERE connect_templet.task_id = '$task_id'
									group by data_kind.id")->result();
    }

    function get_setup_tabs($type_id,$kind_id){
   		return $this->db->query("SELECT templet_ele.element,templet_ele.view_ele, templet_ele.id as element_id,connect_templet.active
   									FROM connect_templet
   									LEFT JOIN templet_ele ON templet_ele.id = connect_templet.templet_ele_id
   									LEFT JOIN data_kind ON data_kind.id = connect_templet.data_kind_id
   									LEFT JOIN task ON task.id = connect_templet.task_id
   									WHERE data_kind.id = '$kind_id'
   									AND task.id = '$type_id'")->result();
   	}

   	function cateType(){
   		return $this->db->query("SELECT * FROM task")->result();
   	}





   	//Setting End


   	// New project

   	function get_data_type(){
   		return $this->db->query("SELECT * FROM data_kind")->result();
   	}

   	function create_pj($name,$disc,$mem_list,$kind){
   		$query = $this->db->query("INSERT INTO project(name,disc,add_date) VALUES($name,$disc,now())");
   		$pj_id = $this->db->insert_id();   				   		
   		   		
   		if($query){   			
   			$match = preg_match('/,/', $mem_list); // ,으로 멤버가 한명인지 몇명인지 검사한다!
   			if($match == 1){ // 멤버가 1명 이상일때!
   				$members = explode(',', $mem_list);   				

		   		foreach ($members as $mem) {
		   			$ins = $this->db->query("INSERT INTO connect_usr_pj(usr_id,pj_id,kind_id,joindate) VALUES($mem,$pj_id,$kind,now())");
		   			if(!$ins){	   			
		   				return false;
		   			}
		   		}
   			}else{ // 멤버가 1명일때!  				   				   				   				
	   			$essay_data_ins = $this->db->query("INSERT INTO connect_usr_pj(usr_id,pj_id,kind_id,joindate) VALUES($mem_list,$pj_id,$kind,now())");
	   			if(!$essay_data_ins){
	   				return false;   						
	   			}   					   				
   			}	   		
   		}else{
   			return false;
   		}

   		$confirm_templet = $this->db->query("SELECT * FROM connect_templet WHERE data_kind_id = $kind and task_id = 1");
   		
   		if(!$confirm_templet->num_rows() > 0){
   			$get_templet_ele = $this->db->query("SELECT * FROM templet_ele")->result();
   			foreach ($get_templet_ele as $value) {
   				$element_id = $value->id;

   				$insert_kind = $this->db->query("INSERT INTO connect_templet(templet_ele_id,data_kind_id,task_id) VALUES($element_id,$kind,1)");
	   			if(!$insert_kind){
	   				return false;
	   			}
   			}   			
   		}
   		return true;   			
   	}  

   	function del_project($pj_id){
		$project_table = $this->db->query("UPDATE connect_usr_pj SET active = 1 WHERE pj_id = '$pj_id'");
		if($project_table){
			$confirm = $this->db->query("SELECT pj_id FROM import_data WHERE pj_id = '$pj_id'");
			if($confirm->num_rows() > 0){
				$essay_table = $this->db->query("UPDATE import_data SET chk = 'Y' WHERE pj_id = '$pj_id'");
				if($essay_table){
					$essay_data_table_del = $this->db->query("UPDATE adjust_data SET active = 1,pj_active = 1 WHERE pj_id = '$pj_id'");					
					if(!$essay_data_table_del){
						return false;
					}
				}else{
					return false;
				}				
			}else{
				$essay_data_table = $this->db->query("UPDATE adjust_data SET active = 1,pj_active = 1 WHERE pj_id = '$pj_id'");
				if(!$essay_data_table){					
					return false;						
				}
			}			
		}else{
			return false;
		}
		return true;
	}
	

	// New project end





   	// Import Sql

   	function find_kind($kind){
   		return $this->db->query("SELECT * FROM data_kind WHERE kind = '$kind'")->row();
   	}

   	function import_sentence($pj_id,$sentence,$structure,$kind_id,$scoring,$critique){
		$raw_sentence = strip_tags($sentence);
		return $this->db->query("INSERT INTO import_data(essay,structure,scoring,critique,date,kind,pj_id) VALUES('$raw_sentence','$structure','$scoring','$critique',now(),'$kind_id','$pj_id')");						
	}	

   	function new_essayList($pj_id){   		
		$query = "SELECT import_data.*, data_kind.kind as kind_name
					FROM import_data
					LEFT JOIN data_kind ON data_kind.id = import_data.kind
					where import_data.id != 0 
					and import_data.pj_id = '$pj_id' 
					and import_data.chk = 'N'";
		return $this->db->query($query)->result();	   		
	}

	function import_count($pj_id){
		$query = "SELECT count(id) as count FROM import_data WHERE id != 0 and pj_id = '$pj_id' and chk = 'N'";
		return $this->db->query($query)->row();
	}

	function modal_editors($id){   		
   		return $this->db->query("SELECT usr.id as usr_id,usr.name
   									FROM usr 
   									LEFT JOIN connect_usr_pj ON connect_usr_pj.usr_id = usr.id
   									WHERE connect_usr_pj.pj_id = '$id' and connect_usr_pj.active = 0 and usr.classify = '1' and usr.conf = 0 and usr.active = 0 GROUP by connect_usr_pj.usr_id")->result();
   	}

   	function all_tag(){
   		return $this->db->query("SELECT * FROM tags")->result();
   	}

   	function tag_replace($text){
   		$get_tag = $this->db->query("SELECT * FROM tags")->result();
   		$patterns_array = array();
   		$replace_array = array();

   		foreach ($get_tag as $value) {
   			$tag = $value->tag;

   			array_push($patterns_array, '(<'.strtoupper($tag).'>)');
   			array_push($patterns_array, '(</'.strtoupper($tag).'>)');

   			array_push($replace_array, "<span class='".strtolower($tag)."'>");
   			array_push($replace_array, "</span>");
   		}	

		$data = preg_replace($patterns_array, $replace_array, $text);
		$data = mysql_real_escape_string(trim($data));				
		return $data;		
   	}

   	function import_mem_sentence($mem_id,$sentence_num,$pj_id){

   		$sentences = explode(',', $sentence_num);  		
   		
   		foreach ($sentences as $senid) {
   			$select = $this->db->query("SELECT import_data.*,data_kind.kind as kind_name
   										FROM import_data 
   										LEFT JOIN data_kind ON data_kind.id = import_data.kind
   										WHERE import_data.id = '$senid'");

   			if($select->num_rows() > 0){
   				$row = $select->row();

   				$essay_id = $row->id;   				
   				$essay = trim($row->essay);
   				$structure = $row->structure;
   				$critique = $row->critique;
   				$scoring = $row->scoring;   				   				
   				$kind = $row->kind; // kind_id			
   				$kind_name = $row->kind_name;

   				if($kind_name == 'toefl'){
   					$sentence = explode('::', $essay);   				

	   				$prompt = mysql_real_escape_string($sentence[0]);
					$raw_txt = mysql_real_escape_string(strip_tags($structure));
					$critique = mysql_real_escape_string($critique);
					$editing = $raw_txt;
	   				$word_count = str_word_count($sentence[1]);   	

	   				// tag_replace
					$data = $this->tag_replace($structure);
					
   				}elseif($kind_name == 'diary'){
   					$sentence = explode('::', $essay);
   					$subject_conf = count($sentence);
   					if($subject_conf > 1){ // 카운터가 1이면 주제가 없는것!
   						$prompt = mysql_real_escape_string($sentence[0]);
   						$diary = strip_tags($sentence[1]);
	   					
	   					$raw_txt = mysql_real_escape_string($diary);	   									
						$critique = mysql_real_escape_string($critique);
		   				$word_count = str_word_count(strip_tags($essay));					
							
						// tag_replace
						$data = $this->tag_replace($structure); 
						
   					}else{
   						$diary = strip_tags($essay);  						   					
	   					
	   					$explode_editing = explode('&', $diary);	   					
	   					$prompt = mysql_real_escape_string($explode_editing[0]); // 주제가 없는 것은 주제 없이 디비에 넣는다! 번호만 집어 넣는다.
	   					$raw_txt = mysql_real_escape_string($explode_editing[1]);
	   					$editing = mysql_real_escape_string($explode_editing[1]);

						$critique = mysql_real_escape_string($critique);
		   				$word_count = str_word_count(strip_tags($essay));

		   				$explode_structure = explode('&', $structure);
		   				$structure = $explode_structure[1];

		   				// tag_replace
						$data = $this->tag_replace($structure);						
   					}  						
   				}else{
   					return false;
   				}   				

   				$ins = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,critique,scoring,tagging,draft,word_count) 
   											VALUES('$mem_id','$essay_id','$prompt','$raw_txt','$kind',1,now(),'$pj_id','$editing','$critique','$scoring','$data','1','$word_count')");

   				if($ins){
   					$sen_up = $this->db->query("UPDATE import_data SET chk = 'Y' WHERE id = '$essay_id'");
	   				if(!$sen_up){
	   					return false;   				
	   				}	   					
   				}else{
   					return false;
   				}
   			}else{
   				return false;
   			}   			
   		} // foreach end.
   		return true;
   	}  

   	function equal_distribute($total_essay_count,$pj_id){		
		$count = $this->db->query("SELECT count(usr_id) as count FROM connect_usr_pj WHERE pj_id = '$pj_id' and active = 0")->row();
		$usr_count = $count->count; //usr 전체 수를 구한다!		
		
		$division =  floor($total_essay_count/$usr_count); // user 각각이 가져야 할 essay수!		
		
		//$remainder = $total_essay_count - ($division*$usr_count); //user 각각 모두 똑같이 가지고남은 essay수! 		
		
		if($division > 0){
			
		 	$data = $this->db->query("SELECT usr_id FROM connect_usr_pj WHERE pj_id = '$pj_id' AND active = 0");		
			foreach ($data->result() as $value) {
				$usr_id = $value->usr_id;				
							
				$essays = $this->db->query("SELECT import_data.*,data_kind.kind as kind_name
												FROM import_data 
												LEFT JOIN data_kind ON data_kind.id = import_data.kind
												WHERE pj_id = '$pj_id' and chk = 'N' LIMIT $division");				
				
				foreach ($essays->result() as $row) {
					$essay_id = $row->id;
	   				$essay = trim($row->essay);
	   				$structure = $row->structure;
	   				$scoring = $row->scoring;   				
	   				$critique = $row->critique;	   					   				
	   				$kind = $row->kind;
	   				$kind_name = $row->kind_name;

					if($kind_name == 'toefl'){
	   					$sentence = explode('::', $essay);   				

		   				$prompt = mysql_real_escape_string($sentence[0]);
						$raw_txt = mysql_real_escape_string(strip_tags($structure));
						$critique = mysql_real_escape_string($critique);
						$editing = $raw_txt;
		   				$word_count = str_word_count($sentence[1]);   	

						// tag_replace
						$data = $this->tag_replace($structure);
	   				}elseif($kind_name == 'diary'){

	   					$sentence = explode('::', $essay);
   						$subject_conf = count($sentence);
   						
   						if($subject_conf > 1){ // 주제가 있는것!
   							$prompt = mysql_real_escape_string($sentence[0]);
   							$diary = strip_tags($sentence[1]);	   					
		   					
		   					$raw_txt = mysql_real_escape_string($diary);	   									
							$critique = mysql_real_escape_string($critique);
			   				$word_count = str_word_count(strip_tags($essay));

							// tag_replace
							$data = $this->tag_replace($structure);						
   						}else{
   							$diary = strip_tags($essay);
	   						$explode_editing = explode('&', $diary);	   					
		   					$prompt = mysql_real_escape_string($explode_editing[0]); // 주제가 없는 것은 주제 없이 디비에 넣는다! 번호만 집어 넣는다.
		   					$raw_txt = mysql_real_escape_string($explode_editing[1]);
		   					$editing = mysql_real_escape_string($explode_editing[1]);

							$critique = mysql_real_escape_string($critique);
			   				$word_count = str_word_count(strip_tags($essay));

			   				$explode_structure = explode('&', $structure);
			   				$structure = $explode_structure[1];

							// tag_replace
							$data = $this->tag_replace($structure);						
   						}
	   						
	   				}else{
	   					return false;
	   				}   				

	   				$ins = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,critique,scoring,tagging,draft,word_count) 
	   											VALUES('$usr_id','$essay_id','$prompt','$raw_txt','$kind',1,now(),'$pj_id','$editing','$critique','$scoring','$data','1','$word_count')");

	   				if($ins){
	   					$sen_up = $this->db->query("UPDATE import_data SET chk = 'Y' WHERE id = '$essay_id'");
		   				if(!$sen_up){
		   					return false;
		   				}	   					
	   				}else{
	   					return 1;
	   				}
	   				
				} // foreach end.
			}			

			// 나머지 essay를 가지고 다시 한번 랜덤으로 user을 뽑아서 essay를 출제 한다!
			$remainder_essay = $this->db->query("SELECT import_data.*,data_kind.kind as kind_name
													FROM import_data 
													LEFT JOIN data_kind ON data_kind.id = import_data.kind 
													WHERE pj_id = '$pj_id' and chk = 'N'");
		
			if($remainder_essay->num_rows() > 0){				
				foreach ($remainder_essay->result() as $row) {
					// User Random으로 뽑기!
					$usrs = $this->db->query("SELECT usr_id FROM connect_usr_pj WHERE pj_id = '$pj_id' AND active = 0 ORDER BY RAND() limit 1")->row();
					$re_usr_id = $usrs->usr_id;

					$essay_id = $row->id;   				
	   				$essay = trim($row->essay);
	   				$structure = $row->structure;
	   				$scoring = $row->scoring;   				
	   				$critique = $row->critique;	   					   				
	   				$kind = $row->kind;
	   				$kind_name = $row->kind_name; 				   	

					if($kind_name == 'toefl'){
	   					$sentence = explode('::', $essay);   				

		   				$prompt = mysql_real_escape_string($sentence[0]);
						$raw_txt = mysql_real_escape_string(strip_tags($structure));
						$critique = mysql_real_escape_string($critique);
						$editing = $raw_txt;
		   				$word_count = str_word_count($sentence[1]);   	

						// tag_replace
						$data = $this->tag_replace($structure);
	   				}elseif($kind_name == 'diary'){
	   					$sentence = explode('::', $essay);
   						$subject_conf = count($sentence);
   						
   						if($subject_conf > 1){ // 주제가 있는것!
   							$prompt = mysql_real_escape_string($sentence[0]);
   							$diary = strip_tags($sentence[1]);	   					
		   					
		   					$raw_txt = mysql_real_escape_string($diary);	   									
							$critique = mysql_real_escape_string($critique);
			   				$word_count = str_word_count(strip_tags($essay));

							// tag_replace
							$data = $this->tag_replace($structure);						
   						}else{
   							$diary = strip_tags($essay);
	   						$explode_editing = explode('&', $diary);	   					
		   					$prompt = mysql_real_escape_string($explode_editing[0]); // 주제가 없는 것은 주제 없이 디비에 넣는다! 번호만 집어 넣는다.
		   					$raw_txt = mysql_real_escape_string($explode_editing[1]);
		   					$editing = mysql_real_escape_string($explode_editing[1]);

							$critique = mysql_real_escape_string($critique);
			   				$word_count = str_word_count(strip_tags($essay));

			   				$explode_structure = explode('&', $structure);
			   				$structure = $explode_structure[1];

							// tag_replace
							$data = $this->tag_replace($structure);						
   						}	
	   				}else{
	   					return false;
	   				}   					

	   				$ins = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,critique,scoring,tagging,draft,word_count) 
	   											VALUES('$re_usr_id','$essay_id','$prompt','$raw_txt','$kind',1,now(),'$pj_id','$editing','$critique','$scoring','$data','1','$word_count')");

	   				if($ins){
	   					$sen_up = $this->db->query("UPDATE import_data SET chk = 'Y' WHERE id = '$essay_id'");
		   				if(!$sen_up){
		   					return false;
		   				}	   					
	   				}else{
	   					return 1;
	   				}

				} //foreach end.
			}
			return true;
		}else { //division == 0 일때!						
			
			// 새로운 essay수가 에디터 수보다 적을때!! 소수점으로 떨어질때!
			// 나머지 essay를 가지고 다시 한번 랜덤으로 user을 뽑아서 essay를 출제 한다!
			$remainder_essay = $this->db->query("SELECT import_data.*,data_kind.kind as kind_name
													FROM import_data 
													LEFT JOIN data_kind ON data_kind.id = import_data.kind 
													WHERE pj_id = '$pj_id' and chk = 'N'");

			if($remainder_essay->num_rows() > 0){
				
				//$this->db->query("UPDATE cou SET update_count = 0");
				foreach ($remainder_essay->result() as $row) {					
					$usrs = $this->db->query("SELECT usr_id	FROM adjust_data WHERE pj_id = '$pj_id' and pj_active = 0 and active = 0 and usr_id != 1 ORDER BY RAND() limit 1")->row();		
					$re_usr_id = $usrs->usr_id;
					$essay_id = $row->id;
	   				$essay = trim($row->essay);
	   				$structure = $row->structure;
	   				$scoring = $row->scoring;
	   				$critique = $row->critique;	   				
	   				$kind = $row->kind;
	   				$kind_name = $row->kind_name;

					if($kind_name == 'toefl'){
	   					$sentence = explode('::', $essay);   				

		   				$prompt = mysql_real_escape_string($sentence[0]);
						$raw_txt = mysql_real_escape_string(strip_tags($structure));
						$critique = mysql_real_escape_string($critique);
						$editing = $raw_txt;
		   				$word_count = str_word_count($sentence[1]);   	

						// tag_replace
						$data = $this->tag_replace($structure);
	   				}elseif($kind_name == 'diary'){
	   					$sentence = explode('::', $essay);
   						$subject_conf = count($sentence);
   						
   						if($subject_conf > 1){ // 주제가 있는것!
   							$prompt = mysql_real_escape_string($sentence[0]);
   							$diary = strip_tags($sentence[1]);
		   					
		   					$raw_txt = mysql_real_escape_string($diary);	   									
							$critique = mysql_real_escape_string($critique);
			   				$word_count = str_word_count(strip_tags($essay));

							// tag_replace
							$data = $this->tag_replace($structure);						
   						}else{
   							$diary = strip_tags($essay);
	   						$explode_editing = explode('&', $diary);	   					
		   					$prompt = mysql_real_escape_string($explode_editing[0]); // 주제가 없는 것은 주제 없이 디비에 넣는다! 번호만 집어 넣는다.
		   					$raw_txt = mysql_real_escape_string($explode_editing[1]);
		   					$editing = mysql_real_escape_string($explode_editing[1]);

							$critique = mysql_real_escape_string($critique);
			   				$word_count = str_word_count(strip_tags($essay));

			   				$explode_structure = explode('&', $structure);
			   				$structure = $explode_structure[1];

							// tag_replace
							$data = $this->tag_replace($structure);						
   						}
	   				}else{
	   					return false;
	   				}   				

	   				$ins = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,critique,scoring,tagging,draft,word_count) 
	   											VALUES('$re_usr_id','$essay_id','$prompt','$raw_txt','$kind',1,now(),'$pj_id','$editing','$critique','$scoring','$data','1','$word_count')");

	   				if($ins){
	   					$sen_up = $this->db->query("UPDATE import_data SET chk = 'Y' WHERE id = '$essay_id'");
		   				if(!$sen_up){
		   					return false;   				
		   				}	   					
	   				}else{
	   					return 1;
	   				}

				} // foreach end.
				return true;
			}else{
				return 6;
			}			
		} //else if end.				
	}

	// Import End

	// Admin members history

	function admin_pj_todo($pj_id,$usr_id,$page,$limit,$list){
		$query = "SELECT adjust_data.*,data_kind.kind as kind_name
					FROM adjust_data 
					LEFT JOIN data_kind ON data_kind.id = adjust_data.kind
					WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and pj_active = 0 and submit != 1 and discuss = 'Y' LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	function admin_pj_history($pj_id,$usr_id,$page,$limit,$list){ //0
		$query = "SELECT adjust_data.*,data_kind.kind as kind_name
					FROM adjust_data 
					LEFT JOIN data_kind ON data_kind.id = adjust_data.kind
					WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and pj_active = 0 and essay_id != 0 and discuss = 'Y' LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	function discuss_list($pj_id,$editor_id,$limit,$list){
   		$query = "SELECT adjust_data.*,data_kind.kind as kind_name
   					FROM adjust_data 
   					LEFT JOIN data_kind ON data_kind.id = adjust_data.kind
   					WHERE usr_id = '$editor_id' and pj_id = '$pj_id' and discuss = 'N' and pj_active = 0 and active = 0 LIMIT $limit,$list";
		return $this->db->query($query)->result();
   	}

   	function admin_pj_done($pj_id,$usr_id,$page,$limit,$list){ // 0
		$query = "SELECT adjust_data.*,data_kind.kind as kind_name
					FROM adjust_data 
					LEFT JOIN data_kind ON data_kind.id = adjust_data.kind
					WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and pj_active = 0 and draft = 1 and submit = 1 and discuss = 'Y' ORDER BY sub_date desc LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}



	// Admin members history end.

	public function getList($usr_id){
		$query = "SELECT * FROM import_data where id != 0";
		return $this->db->query($query)->result();
	}

	

	public function memList($usr_id){
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and active = 0 and essay_id != 0";
		return $this->db->query($query)->result();
	}

	public function pj_memList($pj_id,$usr_id){ //  X
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and essay_id != 0";
		return $this->db->query($query)->result();
	}	

	public function admin_pj_share($pj_id,$usr_id,$page,$limit,$list){ // 0 Admin share
		$query = "SELECT adjust_data.*, data_kind.kind as kind_name
					FROM adjust_data 
					LEFT JOIN data_kind ON data_kind.id = adjust_data.kind
					WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and pj_active = 0 and essay_id != 0 and submit != 1 and discuss = 'Y' LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}



	public function admin_history($usr_id,$page,$limit,$list){
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and active = 0 and pj_active = 0 and essay_id != 0 LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	public function admin_todo($usr_id,$page,$limit,$list){
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and active = 0 and pj_active = 0 and essay_id != 0 and submit != 1 LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	public function edi_todo($usr_id,$page,$limit,$list){
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and active = 0 and pj_active = 0 and essay_id != 0 and submit != 1 and discuss = 'Y' LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	public function admin_done($usr_id,$page,$limit,$list){
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and active = 0 and pj_active = 0 and essay_id != 0 and draft = 1 and submit = 1 LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}	

	public function page_essayList($usr_id,$last_num){  // X
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and active = 0 and submit = 0 and essay_id != 0 and type = 'musedata' and id >= '$last_num' limit 10";
		return $this->db->query($query)->result();
	}

	public function get_todolist($usr_id,$last_num){  // X
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and active = 0 and submit = 0 and type = 'musedata' and id > '$last_num' LIMIT 20";
		return $this->db->query($query)->result();
	}

	public function editor_pj_todolist($usr_id,$pj_id,$last_num){  // X
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and essay_id != 0 and id > '$last_num' limit 10";
		return $this->db->query($query)->result();
	}

	public function other_donelist($usr_id,$last_num){
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and active = 0 and submit = 1 and essay_id != 0 and id >= '$last_num' LIMIT 10";
		return $this->db->query($query)->result();
	}	

	public function pj_doneList($pj_id,$usr_id){
		$query = "SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and submit = 1 and type in('musedata','writing') ORDER BY sub_date desc";
		return $this->db->query($query)->result();
	}		

	// public function distribute($cou,$pj_id){		
	// 	$count = $this->db->query("SELECT count(id) as count FROM cou WHERE pj_id = '$pj_id' and active = 0")->row();
	// 	$usr_count = $count->count; //usr 전체 수를 구한다!		

	// 	$division =  floor($cou/$usr_count); // user 각각이 가져야 할 essay수!		

	// 	$remainder = $cou - ($division*$usr_count); //user 각각 모두 똑같이 가지고남은 essay수! 		

	// 	if($division > 0){
			
	// 	 	$data = $this->db->query("SELECT usr_id FROM cou WHERE pj_id = '$pj_id' and active = 0");		
	// 		foreach ($data->result() as $value) {
	// 			$usr_id = $value->usr_id;				
							
	// 			$essays = $this->db->query("SELECT * FROM import_data WHERE pj_id = '$pj_id' and chk = 'N' LIMIT $division");				
	// 			foreach ($essays->result() as $essay) {
	// 				$essay_id = $essay->id;
	// 				$title = mysql_real_escape_string($essay->prompt);
	// 				$raw_txt = mysql_real_escape_string($essay->essay);
	// 				$type = $essay->type;
	// 				$kind = $essay->kind;					
	// 				//echo $essay_id;
	// 				$insert = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id) VALUES('$usr_id','$essay_id','$title','$raw_txt','$kind','$type',now(),'$pj_id')");
	// 				if($insert){
	// 					$this->db->query("UPDATE import_data SET chk = 'Y' WHERE id = '$essay_id'");						
	// 				}else{
	// 					return '1';
	// 				}
	// 			}
	// 			$this->db->query("UPDATE cou SET update_count = '$division' WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0");
				
	// 		}			

	// 		// 나머지 essay를 가지고 다시 한번 랜덤으로 user을 뽑아서 essay를 출제 한다!
	// 		$remainder_essay = $this->db->query("SELECT * FROM import_data WHERE pj_id = '$pj_id' and chk = 'N'");
		
	// 		if($remainder_essay->num_rows() > 0){
				
	// 			foreach ($remainder_essay->result() as $value) {
	// 				$re_essay_id = $value->id;
	// 				$title = trim(mysql_real_escape_string($value->prompt));
	// 				$raw_txt = trim(mysql_real_escape_string($value->essay));
	// 				$type = $value->type;
	// 				$kind = $value->kind;

	// 				$usrs = $this->db->query("SELECT usr_id FROM cou WHERE pj_id = '$pj_id' and active = 0 ORDER BY RAND() limit 1")->row();		
	// 				$re_usr_id = $usrs->usr_id;

	// 				$remainder_insert = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id) VALUES('$re_usr_id','$re_essay_id','$title','$raw_txt','$kind','$type',now(),'$pj_id')");

	// 				if($remainder_insert){
	// 					$rand = $this->db->query("UPDATE import_data SET chk = 'Y' WHERE id = '$re_essay_id'");
	// 					if($rand){
	// 						$this->db->query("UPDATE cou SET update_count = update_count+1 WHERE usr_id = '$re_usr_id' and pj_id = '$pj_id' and active = 0");
							
	// 					}else{
	// 						return '2';
	// 					}
	// 				}else{
	// 					return '3';
	// 				}
	// 			}
	// 		}
	// 		return 'true';
	// 	}else { //division == 0 일때!						
			
	// 		// 새로운 essay수가 에디터 수보다 적을때!! 소수점으로 떨어질때!
	// 		// 나머지 essay를 가지고 다시 한번 랜덤으로 user을 뽑아서 essay를 출제 한다!
	// 		$remainder_essay = $this->db->query("SELECT * FROM import_data WHERE pj_id = '$pj_id' and chk = 'N'");

	// 		if($remainder_essay->num_rows() > 0){
				
	// 			//$this->db->query("UPDATE cou SET update_count = 0");
	// 			foreach ($remainder_essay->result() as $value) {
	// 				$re_essay_id = $value->id;
	// 				$title = trim(mysql_real_escape_string($value->prompt));
	// 				$raw_txt = trim(mysql_real_escape_string($value->essay));
	// 				$type = $value->type;
	// 				$kind = $value->kind;

	// 				$usrs = $this->db->query("SELECT usr_id FROM cou WHERE pj_id = '$pj_id' and active = 0 ORDER BY RAND() limit 1")->row();		
	// 				$re_usr_id = $usrs->usr_id;

	// 				//echo $re_usr_id;
	// 				$remainder_insert = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id) VALUES('$re_usr_id','$re_essay_id','$title','$raw_txt','$kind','$type',now(),'$pj_id')");

	// 				if($remainder_insert){
	// 					$rand = $this->db->query("UPDATE import_data SET chk = 'Y' WHERE id = '$re_essay_id'");
	// 					//$rand = true;
	// 					if($rand){				

	// 						$count_up = $this->db->query("UPDATE cou SET update_count = update_count+1 WHERE usr_id = '$re_usr_id' and pj_id = '$pj_id' and active = 0");
	// 						if(!$count_up){
	// 							return '7';
	// 						}
	// 					}else{
	// 						return '4';
	// 					}
	// 				}else{
	// 					return '5';
	// 				}
	// 			}
	// 			return 'true';
	// 		}else{
	// 			return '6';
	// 		}			
	// 	} //else if end.				
	// }

	public function status_list(){
		return $this->db->query("SELECT usr.id AS usr_id, usr.name, COUNT( IF( adjust_data.type = 'musedata', 1, NULL ) ) AS tagging, COUNT( IF( adjust_data.type =  'writing', 1, NULL ) ) AS writing
									FROM adjust_data
									LEFT JOIN usr ON usr.id = adjust_data.usr_id
									WHERE adjust_data.active = 0
									AND usr.classify = 1
									AND usr.conf = 0
									GROUP BY adjust_data.usr_id")->result();		
	}

	public function getEssay($essay_id,$type){
		return $this->db->query("SELECT adjust_data.*, error_essay.active AS chk
									FROM adjust_data 
									LEFT JOIN error_essay ON error_essay.essay_id = adjust_data.essay_id
									WHERE adjust_data.essay_id = '$essay_id' and type = '$type' and adjust_data.pj_active = 0 and adjust_data.active = 0")->row();	
	}	

	public function admin_done_list($usr_id,$essay_id,$type){		
		return $this->db->query("SELECT *
									FROM adjust_data 									
									WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type' and submit = 1")->row();	
	}

	public function draft($data_id,$editing,$critique,$tagging,$score1,$score2,$time){		
		$confirm = $this->db->query("SELECT * FROM adjust_data WHERE id = '$data_id'");
		if($confirm->num_rows() > 0){
			return $this->db->query("UPDATE adjust_data SET editing = '$editing',critique = '$critique',tagging = '$tagging',draft = 1,scoring = '$score1', score2 = '$score2', time = '$time' WHERE id = '$data_id'");			
		}else{
			return false;
		}		
	}	

	public function submit($data_id,$editing,$critique,$tagging,$score1,$score2,$time){
		$confirm = $this->db->query("SELECT * FROM adjust_data WHERE id = '$data_id'");
		
		if($confirm->num_rows() > 0){	
			$row = $confirm->row();					
			$pj_id = $row->pj_id;
			$raw_txt = $row->raw_txt;

			return $this->db->query("UPDATE adjust_data SET editing = '$editing',critique = '$critique',tagging = '$tagging', scoring = '$score1', score2 = '$score2', draft = 1,submit = 1,sub_date = now(), time = '$time', discuss = 'Y' WHERE id = '$data_id'");			
		}else{
			return false;					
		}			
	}

	public function editsubmit($data_id,$editing,$critique,$tagging,$score1,$score2){					
		$confirm = $this->db->query("SELECT * FROM adjust_data WHERE id = '$data_id'");
		
		if($confirm->num_rows() > 0){
			return $this->db->query("UPDATE adjust_data SET editing = '$editing',critique = '$critique',tagging = '$tagging',scoring = '$score1', score2 = '$score2', sub_date = now() WHERE id = '$data_id'");			
		}else{ //submit 된 데이터가 없을때!
			return false;
		}			
	}

	public function todoList($usr_id){
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,draft,submit,adjust_data.id
					FROM adjust_data 
					LEFT JOIN usr ON usr.id = adjust_data.usr_id
					WHERE adjust_data.usr_id = '$usr_id' and adjust_data.active = 0 and adjust_data.submit = 0 and adjust_data.draft = 0 and adjust_data.essay_id != 0 ORDER BY id asc";
		return $this->db->query($query)->result();
	}

	
	public function other_todoList($usr_id,$last_num) {
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,draft,submit,adjust_data.id
					FROM adjust_data 
					LEFT JOIN usr ON usr.id = adjust_data.usr_id
					WHERE adjust_data.usr_id = '$usr_id' and adjust_data.active = 0 and adjust_data.submit = 0 and adjust_data.draft = 0 and adjust_data.essay_id != 0 and adjust_data.id >= '$last_num' ORDER BY start_date DESC LIMIT 10";
		return $this->db->query($query)->result();
	}

	public function edi_other_todoList($usr_id,$pj_id,$last_num) {
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,draft,submit,adjust_data.id,sub_date
					FROM adjust_data 
					LEFT JOIN usr ON usr.id = adjust_data.usr_id
					WHERE adjust_data.usr_id = '$usr_id' and adjust_data.active = 0 and adjust_data.pj_id = '$pj_id' and adjust_data.essay_id != 0 and adjust_data.id >= '$last_num' LIMIT 10";
		return $this->db->query($query)->result();
	}

	public function pj_todoList($pj_id,$usr_id){
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name
					FROM adjust_data 
					LEFT JOIN usr ON usr.id = adjust_data.usr_id
					WHERE adjust_data.usr_id = '$usr_id' and adjust_data.pj_id = '$pj_id' and adjust_data.active = 0 and adjust_data.submit = 0 and adjust_data.draft = 0 and adjust_data.essay_id != 0";
		return $this->db->query($query)->result();
	}

	public function eid_pj_todoList($usr_id,$pj_id){
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,adjust_data.id,draft,submit
					FROM adjust_data 
					LEFT JOIN usr ON usr.id = adjust_data.usr_id
					WHERE adjust_data.usr_id = '$usr_id' and adjust_data.pj_id = '$pj_id' and adjust_data.active = 0 and adjust_data.submit = 0 and adjust_data.essay_id != 0";
		return $this->db->query($query)->result();
	}

	public function eid_pj_doneList($usr_id,$pj_id){
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,adjust_data.id,draft,submit,sub_date
					FROM adjust_data 
					LEFT JOIN usr ON usr.id = adjust_data.usr_id
					WHERE adjust_data.usr_id = '$usr_id' and adjust_data.pj_id = '$pj_id' and adjust_data.active = 0 and adjust_data.submit = 1 and adjust_data.essay_id != 0";
		return $this->db->query($query)->result();
	}	

	public function edi_other_doneList($usr_id,$pj_id,$last_num) {
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,draft,submit,adjust_data.id,sub_date
					FROM adjust_data 
					LEFT JOIN usr ON usr.id = adjust_data.usr_id
					WHERE adjust_data.usr_id = '$usr_id' and adjust_data.active = 0 and adjust_data.submit = 1 and adjust_data.pj_id = '$pj_id' and adjust_data.essay_id != 0 and adjust_data.id >= '$last_num' LIMIT 10";
		return $this->db->query($query)->result();
	}

	public function local_save($dic) {
		$usr_id = $dic['usr_id'];
		$w_id = $dic['w_id'];
		$title = $dic['title'];
		$raw_writing = $dic['raw_writing'];
		$editing = $dic['editing'];
		$tagging = $dic['tagging'];
		$critique = $dic['critique'];
		$score1 = $dic['score1'];
		$score2 = $dic['score2'];
		$word_count = $dic['word_count'];
		$time = $dic['time'];
		$kind = $dic['kind'];
		$type = $dic['type'];
		
		$sql = "INSERT INTO adjust_data(
				usr_id, essay_id, prompt, raw_txt, editing, tagging,
				critique, scoring, score2, word_count, draft, submit,
				time, kind, type, sub_date
				) VALUES (
				'$usr_id','$w_id',$title,$raw_writing,$editing,$tagging,
				$critique,$score1,$score2,'$word_count',1,1,
				$time,$kind,'$type',now())";

		log_message('error', '[DEBUG] local_save sql : ' . $sql);

		return $this->db->query($sql);
	}
	/***
	public function local_save($usr_id,$w_id,$raw_writing,$editing,$tagging,$critique,$title,$kind,$score1,$score2,$word_count,$time,$type){				    
					//local_save($usr_id,$w_id,$raw_writing,$editing,$tagging,$critique,$title,$kind,$score1,$score1,$time,$type)
		return $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,editing,tagging,critique,scoring,score2,word_count,draft,submit,time,kind,type,sub_date) 
									VALUES('$usr_id','$w_id',$title,$raw_writing,$editing,$tagging,$critique,$score1,$score2,'$word_count',1,1,$time,$kind,'$type',now())");
	}
	***/

	public function insert_service_data($dic) {
		$usr_id = $dic['usr_id'];
		$w_id = $dic['w_id'];
		$title = $dic['title'];
		$raw_writing = $dic['raw_writing'];
		$editing = $dic['editing'];
		$tagging = $dic['tagging'];
		$critique = $dic['critique'];
		$score1 = $dic['score1'];
		$score2 = $dic['score2'];
		$word_count = $dic['word_count'];
		$time = $dic['time'];
		$kind = $dic['kind'];
		$type = $dic['type'];
		
		$sql = "INSERT INTO service_data(
				usr_id, essay_id, prompt, raw_txt, editing, tagging,
				critique, scoring, score2, word_count, draft, submit,
				time, kind, type, sub_date
				) VALUES (
				'$usr_id','$w_id',$title,$raw_writing,$editing,$tagging,
				$critique,$score1,$score2,'$word_count',1,1,
				$time,$kind,'$type',now())";

		log_message('error', '[DEBUG] insert_service_data sql : ' . $sql);

		$ret = $this->db->query($sql);

		if ($ret)
		{
			return @mysql_insert_id();
		}
		else
		{
			return false;
		}
	}

	public function insert_file($filename, $title){
      	$data = array(
        	'filename'     => $filename,
         	'title'        => $title
      	);
      	$this->db->insert('files', $data);
      	return $this->db->insert_id();
   	}

   	public function insert_sentence($title,$sentence,$kind,$pj_id){  	

   		return $this->db->query("INSERT INTO import_data(prompt,essay,date,type,kind,pj_id) VALUES('$title','$sentence',now(),'musedata','$kind','$pj_id')");
   	}

   	public function ins_db_file($filename){   	
   	
   		$conform = $this->db->query("SELECT * FROM files WHERE filename = '$filename'");
	   	
	   	if($conform->num_rows() > 0){
	   		return false;
	   	}else{
			$file_ins = $this->db->query("INSERT INTO files(filename,date) VALUES('$filename',now())");   		
			if($file_ins){
				return true;
			}
	   	}
   	}    	

   	public function mem_sentence($mem_id,$sentence_num,$pj_id){

   		$sentences = explode(',', $sentence_num);
   		$this->db->query("UPDATE cou SET update_count = 0 WHERE usr_id = '$mem_id' and pj_id = '$pj_id' and active = 0");
   		
   		foreach ($sentences as $senid) {
   			$select = $this->db->query("SELECT * FROM import_data WHERE id = '$senid'");

   			if($select->num_rows() > 0){

   				$select = $select->row();
   				$essay_id = $select->id;
   				$prompt = trim(mysql_real_escape_string($select->prompt));
   				$sen = trim(mysql_real_escape_string($select->essay));
   				$type = $select->type; 
   				$kind = $select->kind; 				

   				$ins = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id) VALUES('$mem_id','$essay_id','$prompt','$sen','$kind','$type',now(),'$pj_id')");
   				$sen_up = $this->db->query("UPDATE import_data SET chk = 'Y' WHERE id = '$essay_id'");
   				if($sen_up){
   					$count_up = $this->db->query("UPDATE cou SET update_count = update_count+1 WHERE usr_id = '$mem_id' and pj_id = '$pj_id' and active = 0");							
   					if(!$count_up){
   						return 'false';				
   					}
   				}else{
   					return 'false';
   				}

   			}else{
   				return 'false';
   			}   			
   		}
   		return 'true';					
   		
   	}

   	public function notice_all(){
   		return $this->db->query("SELECT * FROM notice WHERE active = 0 ORDER BY date desc")->result();
   	}

   	public function notice(){
   		return $this->db->query("SELECT * FROM notice WHERE active = 0 ORDER BY date desc limit 5")->result();
   	}

   	public function recent_notice(){
   		return $this->db->query("SELECT * FROM notice WHERE active = 0 ORDER BY date desc limit 1")->row();
   	}

   	public function notice_save($title,$cont){
   		$query = $this->db->query("INSERT INTO notice(title,contents,date) VALUES('$title','$cont',now())");
   		if($query){
   			return true;
   		}else{
   			return false;
   		}

   	}

   	public function get_notice($id){
   		return $this->db->query("SELECT * FROM notice WHERE active = 0 and id = '$id'")->row();
   	}

   	function chart_num(){ // index chart
   		$data = array();
   		$todo = $this->db->query("SELECT count(essay_id) as todo FROM adjust_data WHERE essay_id != 0 and draft = 0 and submit = 0 and active = 0")->row();
   		$todo = $todo->todo;
   		array_push($data, $todo);

   		$draft = $this->db->query("SELECT count(draft) as draft FROM adjust_data WHERE essay_id != 0 and draft = 1 and submit = 0 and active = 0")->row();
   		$draft = $draft->draft;
   		array_push($data, $draft);

   		$done = $this->db->query("SELECT count(submit) as submit FROM adjust_data WHERE essay_id != 0 and draft = 1 and submit = 1 and active = 0")->row();
   		$done = $done->submit;
   		array_push($data, $done);

   		$total = $this->db->query("SELECT count(essay_id) as total FROM adjust_data WHERE essay_id != 0 and active = 0")->row();
   		$total = $total->total;
   		array_push($data, $total);

   		return $data;
   	}

   	function editor_chart_num($id){ // index chart
   		$data = array();
   		$todo = $this->db->query("SELECT count(essay_id) as todo FROM adjust_data WHERE essay_id != 0 and usr_id = '$id' and draft = 0 and submit = 0 and active = 0")->row();
   		$todo = $todo->todo;
   		array_push($data, $todo);

   		$draft = $this->db->query("SELECT count(draft) as draft FROM adjust_data WHERE essay_id != 0 and usr_id = '$id' and draft = 1 and submit = 0 and active = 0")->row();
   		$draft = $draft->draft;
   		array_push($data, $draft);

   		$done = $this->db->query("SELECT count(submit) as submit FROM adjust_data WHERE essay_id != 0 and usr_id = '$id' and draft = 1 and submit = 1 and active = 0")->row();
   		$done = $done->submit;
   		array_push($data, $done);

   		$total = $this->db->query("SELECT count(essay_id) as total FROM adjust_data WHERE essay_id != 0 and usr_id = '$id' and active = 0")->row();
   		$total = $total->total;
   		array_push($data, $total);

   		return $data;	
   	}

   	public function conf_newEditor(){
   		$result = $this->db->query("SELECT * FROM usr WHERE conf = 1 and active = 0");

   		if($result->num_rows() > 0){
   			return true;	
   		}else{
   			return false;	
   		}   		
   	}   	   	
   	
   	public function notice_del($id){
   		$result = $this->db->query("UPDATE notice SET active = 1 WHERE id = $id");
   		if($result){
   			return true;
   		}else{
   			return false;
   		}   		
   	}

   	public function pjlist(){
   		return $this->db->query("SELECT project. * , project.id AS pj_id, project.active AS tbd, COUNT(if(adjust_data.essay_id != 0,1,null)) AS total_count
									FROM project
									LEFT JOIN adjust_data ON adjust_data.pj_id = project.id
									WHERE adjust_data.active =0									
									GROUP BY adjust_data.pj_id
									ORDER BY add_date DESC")->result();
   	}

	public function add_userslist($id){		
		return $not_in_user = $this->db->query("SELECT id,name FROM usr WHERE id NOT IN (SELECT usr_id FROM adjust_data where pj_id = '$id' and pj_active = 0) and id != 0 and classify = 1 and conf = 0")->result();
	}

	public function share_userslist($id,$pj_id){		
		return $not_in_user = $this->db->query("SELECT id, name
												FROM usr WHERE id IN (SELECT DISTINCT (usr_id) FROM connect_usr_pj
												WHERE usr_id !=  '$id'
												AND usr_id !=0
												AND pj_id =  '$pj_id'
												AND active =0)
												AND id !=0
												AND classify =1
												AND conf =0")->result();		
	}	

	public function all_todo(){
		return $this->db->query("SELECT * FROM adjust_data WHERE active = 0 and essay_id != 0 and draft = 0 and submit = 0")->result();

	}

	public function all_done(){
		return $this->db->query("SELECT * FROM adjust_data WHERE active = 0 and essay_id != 0 and draft = 1 and submit = 1")->result();
	}

	public function alldone_essay($id){		
		return $this->db->query("SELECT essay_id as id,prompt,raw_txt,editing,tagging,critique,type,scoring
									FROM adjust_data 									
									WHERE id = '$id'")->row();	
	}

	public function all_history(){
		$query = "SELECT * FROM adjust_data WHERE active = 0 and essay_id != 0";
		return $this->db->query($query)->result();
	}

	public function pj_history_totalcount($pj_id,$usr_id){
		return $this->db->query("SELECT count(*) as count FROM adjust_data WHERE pj_id = '$pj_id' and usr_id = '$usr_id' and pj_active = 0 and active = 0 and discuss = 'Y'")->row();
	}

	// Editor In project list

	function pj_todo_totalcount($pj_id,$usr_id) { // 0
		return $this->db->query("SELECT count(*) as count FROM adjust_data WHERE pj_id = '$pj_id' and usr_id = '$usr_id' and submit != 1 and pj_active = 0 and active = 0 and discuss = 'Y'")->row();
	}




	// Editor In project list End.

	public function pj_share_totalcount($pj_id,$usr_id) {
		return $this->db->query("SELECT count(*) as count FROM adjust_data WHERE pj_id = '$pj_id' and usr_id = '$usr_id' and submit != 1 and pj_active = 0 and active = 0 and discuss = 'Y'")->row();
	}

	public function pj_comp_totalcount($pj_id,$usr_id) {
		return $this->db->query("SELECT count(*) as count FROM adjust_data WHERE pj_id = '$pj_id' and usr_id = '$usr_id' and draft = 1 and submit = 1 and pj_active = 0 and active = 0 and discuss = 'Y'")->row();
	}

	public function history_totalcount($usr_id){
		return $this->db->query("SELECT count(*) as count FROM adjust_data WHERE usr_id = '$usr_id' and pj_active = 0 and active = 0")->row();
	}

	public function todo_totalcount($usr_id) {
		return $this->db->query("SELECT count(*) as count FROM adjust_data WHERE usr_id = '$usr_id' and submit != 1 and pj_active = 0 and active = 0")->row();
	}

	public function comp_totalcount($usr_id) {
		return $this->db->query("SELECT count(*) as count FROM adjust_data WHERE usr_id = '$usr_id' and draft = 1 and submit = 1 and pj_active = 0 and active = 0")->row();
	}

	public function edi_todo_totalcount($usr_id) {
		return $this->db->query("SELECT count(*) as count FROM adjust_data WHERE usr_id = '$usr_id' and submit != 1 and pj_active = 0 and active = 0 and discuss = 'Y'")->row();
	}

   	function get_muse_detecting_count($mem_id,$sentence_num,$pj_id){
   		$sentences = explode(',', $sentence_num);  		
   		
   		foreach ($sentences as $senid) {
   			$select = $this->db->query("SELECT * FROM import_data WHERE id = '$senid'");

   			if($select->num_rows() > 0){

   				$select = $select->row();
   				$essay_id = $select->id;  				
   				$sentence = trim($select->essay);   				
   				$type = $select->type; 
   				
   				/*
				Some people believe that scientist should not take responsibility for their inventions when they have the potential to be dangerous to humans because this people think that inventions which are developed by scientists are so useful for living of human. However, I think that they should have responsibility for their inventions when they have dangerous factors. There are two reasons why I feel this way. One reason for my argument is that they have known negative effect of their inventions. And the other reason is that taking responsibility can give scientists opportunity to develop their studies.

				First of all, scientists know that they are creating something that has the potential to cause serious destruction. As a result, they should share the moral responsibility. For example, when the atom bomb was developed, Robert Opphenheimer knew that the atom bomb can cause to kill many people and to make cities devastated. But he made the atom bomb and it contributed to break out the second world war. Finally, creating the atom bomb exchanged the lives of people. This example shows that scientist should take the responsibility for their inventions.

				Second, if scientist has responsibility, they can have a chance to develop their research. Taking responsibility can make that scientists are concerned about public opinion through the internet or news. So they can earn feedback quickly on their creations and it can be foundation to advance the inventions of high quality. As a result, taking responsibility for scientist invention bring about improvement of society.

				In conclusion, scientists already know that how their creation dangerous and scientist also have an opportunity to develop their creations. So these are the reasons why I think that scientist should have responsibility for their creations when creations has dangerous factors.
   				*/  				

   				// $result_json['start'] = time();
		        $result_org = $this->curl->simple_post('http://ec2-54-202-97-249.us-west-2.compute.amazonaws.com:8080/muse3', array('text'=>$sentence));
		        //$result_org = $this->curl->simple_post('http://ec2-54-202-97-249.us-west-2.compute.amazonaws.com:8080/muse3', array('text'=>$writing));
				// $result_json['error_code'] = $this->curl->error_code;
				// $result_json['error_string'] = $this->curl->error_string;
				// $result_json['info'] = $this->curl->info;
				// $result_json['end'] = time();

		        // $result_json = json_decode($result_org, true);
		        // $miss_count = count($result_json['results']);

		        // $this->db->query("UPDATE essay SET gr_miss = '$miss_count' WHERE id = '$essay_id'");



   				//$sen_up = $this->db->query("UPDATE essay SET chk = 'Y' WHERE id = '$essay_id'");
   				
   			}else{
   				return 'false';
   			}   			
   		}
   		return $result_org;	
   	}

   	public function editor_pj_list_count($pj_id,$usr_id){ // X
   		return $this->db->query("SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = '0' and pj_active = '0' and essay_id != 0")->result();
   	}

   	public function list_count($usr_id){ //  X
   		return $this->db->query("SELECT * FROM adjust_data WHERE usr_id = '$usr_id' and active = '0' and pj_active = '0' and submit = 0 and essay_id != 0")->result();
   	}

   	public function share($editor_id,$pj_id,$select_mem,$share_data){
		$match = preg_match('/,/', $share_data); // ,으로 데이터가 몇개인지 검사한다!
   			
		if($match == 1){ // 데이터가 1개 이상일때!
			$datas = explode(',', $share_data);

   			foreach ($datas as $data) {
   				$query = $this->db->query("SELECT * FROM adjust_data WHERE usr_id = '$editor_id' and pj_id = '$pj_id' and id = '$data' and pj_active = 0 and active = 0");
   				if($query->num_rows() > 0){
   					$this->db->query("UPDATE adjust_data SET usr_id = '$select_mem' WHERE usr_id = '$editor_id' and pj_id = '$pj_id' and id = '$data' and pj_active = 0 and active = 0");   					
   				}else{
   					return false;
   				}
   			}
   			return true;
		}else{ // 데이터가 1개 일때!
			$query = $this->db->query("SELECT * FROM adjust_data WHERE usr_id = '$editor_id' and pj_id = '$pj_id' and id = '$share_data' and pj_active = 0 and active = 0");
			if($query->num_rows() > 0){
				$update = $this->db->query("UPDATE adjust_data SET usr_id = '$select_mem' WHERE usr_id = '$editor_id' and pj_id = '$pj_id' and id = '$share_data' and pj_active = 0 and active = 0");
				if($update){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
   	}

   	// Project import Error list
   	function error_proc($data_id){   		   		
		return $this->db->query("UPDATE adjust_data SET pj_active = 1, sub_date = now(), discuss = 'Y', error = 'Y' WHERE id = '$data_id'");
   	}

   	function error_count($id){
   		return $this->db->query("SELECT count(id) as count FROM adjust_data WHERE pj_id = '$id' and error = 'Y' and pj_active = 1 and active = 0")->row();
   	}

   	function errorlist($pj_id,$limit,$list){
		return $this->db->query("SELECT adjust_data.*,usr.name as usr_name, usr.id as editor_id,data_kind.kind as kind_name
									FROM adjust_data 									
									LEFT JOIN usr ON usr.id = adjust_data.usr_id
									LEFT JOIN data_kind ON data_kind.id = adjust_data.kind
									WHERE adjust_data.pj_id = '$pj_id' 
									AND adjust_data.error = 'Y' 
									AND adjust_data.active = 0
									LIMIT $limit,$list")->result();
   	}

   	function error_return($data_id){   		
   		return $this->db->query("UPDATE adjust_data SET error = 'RE', pj_active = 0 WHERE id = '$data_id'");
   	}

   	function error_yes($data_id){
   		return $this->db->query("UPDATE adjust_data SET active = 1 WHERE id = '$data_id'");
   	}
 

   	// Project import Error list end.

   	public function discuss_count($pj_id,$editor_id){
   		return $this->db->query("SELECT count(id) as count FROM adjust_data WHERE pj_id = '$pj_id' and usr_id = '$editor_id' and essay_id != 0 and discuss = 'N' and pj_active = 0 and active = 0")->row();

   	}   	  	

   	public function discuss_proc($id){   		
		return $this->db->query("UPDATE adjust_data SET discuss = 'N' WHERE id = '$id'");   			
   		
   	}   	

   	public function exportmembers($pj_id){
   		return $this->db->query("SELECT usr.id as usr_id,usr.name
   									FROM usr LEFT JOIN adjust_data ON adjust_data.usr_id = usr.id LEFT JOIN project ON project.id = adjust_data.pj_id
   									WHERE adjust_data.pj_id = '$pj_id' and pj_active = 0 and adjust_data.active = 0 and usr.active = 0 and usr.conf = 0 GROUP BY adjust_data.usr_id")->result();   	
   	}

   	

   	public function sorting_export_count($pj_id,$editor_id){
   		return $this->db->query("SELECT count(id) as count FROM adjust_data WHERE pj_id = '$pj_id' and usr_id in($editor_id) and essay_id != 0 and discuss = 'Y' and pj_active = 0 and active = 0 and submit = 1")->row();
   	}

   	public function sorting_export_allessay_id($pj_id,$editor_id){
   		return $this->db->query("SELECT essay_id FROM adjust_data WHERE pj_id = '$pj_id' and usr_id in($editor_id) and essay_id != 0 and discuss = 'Y' and pj_active = 0 and active = 0 and submit = 1")->result();
   	}

   	public function sorting_export_list($pj_id,$editor_id,$limit,$list){
   		$query = "SELECT adjust_data.*,usr.name
					FROM adjust_data
					left join usr on usr.id = adjust_data.usr_id
					WHERE pj_id = '$pj_id'
					AND usr_id in($editor_id)
					AND discuss = 'Y'
					AND essay_id != 0
					AND pj_active = 0
					AND adjust_data.active = 0
					AND submit = 1
					LIMIT $limit,$list";
		return $this->db->query($query)->result();
   	}

   	public function memchkExportget_essay($essay_id){
   		return $this->db->query("SELECT * FROM adjust_data WHERE essay_id in($essay_id) and type = 'musedata'")->result();
   	}

   	public function ex_editing_update($id,$data){
   		$result = $this->db->query("UPDATE adjust_data SET ex_editing = '$data' WHERE essay_id = '$id' and type = 'musedata'");
   		return $result;      
   	}

   	

   	public function all_essayid($pj_id) {
   		return $this->db->query("SELECT * FROM adjust_data WHERE pj_id = '$pj_id' and submit = 1 and pj_active = 0 and active = 0 and discuss = 'Y'")->result();
   	}   	

   	public function get_export_error_data($pj_id,$limit,$list){ // O
   		$query = "SELECT adjust_data.*,usr.name,data_kind.kind
					FROM adjust_data
					left join usr on usr.id = adjust_data.usr_id
					LEFT JOIN data_kind ON data_kind.id = adjust_data.kind
					WHERE adjust_data.pj_id = '$pj_id'										
					AND essay_id != 0					
					AND adjust_data.active = 0
					AND submit = 1 
					AND ex_editing = ''										
					ORDER BY sub_date asc
					LIMIT $limit,$list";
		return $this->db->query($query)->result();		
   	}   

   	public function export_error_count($pj_id){
   		return $this->db->query("SELECT count(id) as count FROM adjust_data WHERE pj_id = '$pj_id' and essay_id != 0 and active = 0 and submit = 1 and ex_editing = ''")->row();
   	}   	

   	function table_merge(){ // 삭제 필요 Service test table
   		$select = $this->db->query("SELECT * FROM test_service where pj_id = 0 limit 500");
   		foreach ($select->result() as $row) {
   			$usr_id = $row->usr_id;
   			$essay_id = $row->essay_id;
   			$pj_id = $row->pj_id;
   			$prompt = mysql_real_escape_string($row->prompt);
   			$raw_txt = mysql_real_escape_string($row->raw_txt);
   			$editing = mysql_real_escape_string($row->editing);
   			$ex_editing = mysql_real_escape_string($row->ex_editing);
   			$tagging = mysql_real_escape_string($row->tagging);
   			$critique = mysql_real_escape_string($row->critique);
   			$scoring = $row->scoring;
   			$word_count = $row->word_count;
   			$org_tag = $row->org_tag;
   			$replace_tag = $row->replace_tag;
   			$discuss = $row->discuss;
   			$draft = $row->draft;
   			$submit = $row->submit;
   			$time = $row->time;
   			$kind = $row->kind;
   			$type = $row->type;
   			$pj_active = $row->pj_active;
   			$active = $row->active;
   			$start_date = $row->start_date;
   			$sub_date = $row->sub_date;

   			$ins = $this->db->query("INSERT INTO adjust_data(usr_id,essay_id,pj_id,prompt,raw_txt,editing,ex_editing,tagging,critique,scoring,word_count,org_tag,replace_tag,discuss,draft,submit,time,kind,type,pj_active,active,start_date,sub_date) 
   									VALUES('$usr_id','$essay_id','$pj_id','$prompt','$raw_txt','$editing','$ex_editing','$tagging','$critique','$scoring','$word_count','$org_tag','$replace_tag','$discuss','$draft','$submit','$time','$kind','$type','$pj_active','$active','$start_date',now())");

   		}
   		return true;
   	}
}
?>