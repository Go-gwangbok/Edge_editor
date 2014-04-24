<?
class All_list extends CI_Model{
	function get_essay($essay_id,$type){
		return $this->db->query("SELECT * FROM tag_essay WHERE essay_id in($essay_id) and type = '$type' and active = 0")->result();
	}

	function getproject_name($pj_id){
		return $this->db->query("SELECT * FROM project where id = '$pj_id'")->row();
	}

	function get_project($usr_id){
		return $this->db->query("SELECT project.name, project.id AS pj_id, project.disc
									FROM tag_essay
									LEFT JOIN project ON tag_essay.pj_id = project.id
									LEFT JOIN usr ON tag_essay.usr_id = usr.id
									WHERE tag_essay.usr_id =  '$usr_id'
									AND tag_essay.active =0
									AND tag_essay.essay_id !=0
									and tag_essay.pj_active = 0
									GROUP BY project.id
									ORDER by project.add_date desc
									LIMIT 2")->result();
	}

	function get_admin_comp($essay_id,$type){
		$query = "SELECT tag_essay.essay_id as id,tag_essay.* FROM tag_essay WHERE essay_id = '$essay_id' AND type = '$type' AND active = 0 ";
		$result = $this->db->query($query);
		if($result->num_rows() > 0){
			return	$this->db->query($query)->row();
		}else{
			return false;
		}
	}

	function pj_name($id){
   		return $this->db->query("SELECT name FROM project WHERE id = '$id'")->row();
   	}

   	function pj_inmembers_list($pj_id){ // pj_id
		return $this->db->query("SELECT usr.id AS usr_id, usr.name,project.name AS pj_name, 
									COUNT(IF(tag_essay.essay_id !=0, 1, NULL ) ) AS count, 
									count(if(tag_essay.submit = 1,1,null)) as done_count, 
									count(if(tag_essay.discuss =  'N',1,null)) AS tbd, usr.date, 
									count(IF(tag_essay.essay_id != 0 AND tag_essay.submit =0 AND tag_essay.discuss =  'Y', 1, null )) AS share 
									FROM tag_essay
									LEFT JOIN usr ON usr.id = tag_essay.usr_id
									LEFT JOIN project ON project.id = tag_essay.pj_id
									WHERE tag_essay.pj_id =  '$pj_id'
									AND tag_essay.active =0
									AND tag_essay.pj_active =0
									AND usr.classify =1
									AND usr.conf =0
									GROUP BY tag_essay.usr_id")->result();
	}

	function service_inmembers_list(){ // pj_id
		return $this->db->query("SELECT usr.id AS usr_id, usr.name, usr.date,
									COUNT(IF(tag_essay.essay_id !=0, 1, NULL ) ) AS count, 
									count(if(tag_essay.submit = 1,1,null)) as done_count 
									FROM tag_essay
									LEFT JOIN usr ON usr.id = tag_essay.usr_id									
									WHERE tag_essay.type = 'writing'
									AND tag_essay.active =0									
									AND usr.classify =1
									AND usr.conf =0
									GROUP BY tag_essay.usr_id")->result();
	}	

	function pj_add_users($pj_id,$users){
		$members = explode(',', $users);

		if(!$members){ // 유져가 1명 일때!
			$confirm = $this->db->query("SELECT * FROM tag_essay WHERE usr_id = '$users' and pj_id = '$pj_id' and pj_active = 1 and active = 0");
				if($confirm->num_rows() > 0){  // 기존에 프로젝트에서 submit한 데이터가 있다면, 데이터를 같이 살려서 유져를 생성한다!
					$update = $this->db->query("UPDATE tag_essay SET pj_active = 0 WHERE usr_id = '$users' and pj_id = '$pj_id' and active = 0 and pj_active = 1");
					if(!$update){
						return false;
					}
				}else{ // 처음 생성되는 유져일 경우!
					$ins_user = $this->db->query("INSERT INTO tag_essay(usr_id,pj_id,type) VALUES('$users','$pj_id','project')");
					if(!$ins_user){
						return false;
					}
				}   				
		}else{
			foreach ($members as $mem) {
   				$confirm = $this->db->query("SELECT * FROM tag_essay WHERE usr_id = '$mem' and pj_id = '$pj_id' and pj_active = 1 and active = 0");
   				if($confirm->num_rows() > 0){  // 기존에 프로젝트에서 submit한 데이터가 있다면, 데이터를 같이 살려서 유져를 생성한다!
   					$update = $this->db->query("UPDATE tag_essay SET pj_active = 0 WHERE usr_id = '$mem' and pj_id = '$pj_id' and active = 0 and pj_active = 1");
   					if(!$update){
   						return false;
   					}
   				}else{ // 처음 생성되는 유져일 경우!
   					$ins_user = $this->db->query("INSERT INTO tag_essay(usr_id,pj_id,type) VALUES('$mem','$pj_id','project')");
   					if(!$ins_user){
   						return false;
   					}
   				}	   			
	   		}	
   			
		}
		return true; 
	}

	function get_user($usr_id){
		return $this->db->query("SELECT * FROM usr WHERE classify = 1 and conf = 0 and active = 0 and id = '$usr_id'")->row();
	}

	function usr_pj_name($usr_id,$pj_id){
		return $this->db->query("SELECT usr.name AS usr_name, project.name AS pj_name
									FROM tag_essay
									LEFT JOIN usr ON usr.id = tag_essay.usr_id
									LEFT JOIN project ON project.id = tag_essay.pj_id
									WHERE tag_essay.usr_id =  '$usr_id'
									AND project.id =  '$pj_id' LIMIT 1")->row();
	}

	function get_error_Essay($essay_id,$type){
		return $this->db->query("SELECT tag_essay.*, error_essay.active AS chk
									FROM tag_essay 
									LEFT JOIN error_essay ON error_essay.essay_id = tag_essay.essay_id
									WHERE tag_essay.essay_id = '$essay_id' and type = '$type' and tag_essay.pj_active = 1 and tag_essay.active = 1")->row();	
	}

	function admin_tbd_submit($usr_id,$table_id,$editing,$critique,$tagging,$type,$scoring,$time){		
		$update = $this->db->query("UPDATE tag_essay SET editing = '$editing',critique = '$critique',tagging = '$tagging',scoring = '$scoring',draft = 1,submit = 1,sub_date = now(), time = '$time', discuss = 'Y', usr_id = '$usr_id' WHERE id = '$table_id'");
		if($update){
			return 'true';
		}
		
	}	

	function admin_tbd_draft($table_id,$editing,$critique,$tagging,$type,$scoring,$time){				
		return $this->db->query("UPDATE tag_essay SET editing = '$editing',critique = '$critique',tagging = '$tagging',draft = 1,scoring = '$scoring',time = '$time' WHERE id = '$table_id'");				
	}

	function dos_avg(){
   		return $this->db->query("SELECT sum(word_count) as total_word_count,
								sum(replace_tag) as replace_count
								FROM tag_essay WHERE essay_id != 0 and replace_tag != 0 and submit = 1 and active = 0 and word_count > 100 and ex_editing != ''")->row();
   	}

	function pj_totalcount($pj_id){		
		return $this->db->query("SELECT COUNT( * ) AS count, 
									COUNT( IF( draft = 1 AND discuss =  'Y'AND submit = 0, 1, NULL ) ) AS draft, 
									COUNT( IF( submit = 1, 1, NULL ) ) AS submit, 
									COUNT( IF( discuss =  'Y', NULL , 1 ) ) AS discuss, 
									COUNT( IF( draft = 0 AND submit = 0, 1, NULL ) ) AS todo,
									sum(word_count) AS pj_total_word_count
									FROM tag_essay
									WHERE pj_id = '$pj_id'
									AND essay_id != 0									
									AND active = 0")->row();
	}

	function stats_data($pj_id){
		$query = "SELECT COUNT( IF( discuss =  'N' AND submit =0, 1, NULL ) ) AS tbd, usr.name, 
					SUM( IF( submit =1 and time > 18000 and ex_editing != '', TIME, 0 ) ) AS total_time, 
					SUM( IF( submit =1 and time > 18000 and ex_editing != '', word_count, 0 ) ) AS word_count, 
					COUNT( IF( discuss =  'Y' AND tag_essay.essay_id !=0, 1, NULL ) ) AS total, 
					COUNT( IF( submit = 1, 1, NULL ) ) AS submit,
					sum( IF( submit = 1, org_tag, 0 ) ) AS org_tag,
					sum( IF( submit = 1, replace_tag, 0 ) ) AS actual_tag,
					SUM( IF( submit = 1 and replace_tag != 0, word_count, 0 ) ) AS replace_word,
					SUM( IF( submit = 0 and replace_tag = 0, word_count, 0 ) ) AS todo_word,
					SUM( IF( submit = 1 and replace_tag = 0, word_count, 0 ) ) AS replace_error_word,
					SUM( IF( submit = 1 and replace_tag != 0, word_count, 0 ) ) AS avg_total_word,
					SUM(word_count) AS pj_each_word,
					error_count.count AS error_count
					FROM tag_essay
					LEFT JOIN usr ON usr.id = tag_essay.usr_id
					LEFT JOIN error_count ON error_count.usr_id = tag_essay.usr_id AND error_count.pj_id = tag_essay.pj_id
					WHERE tag_essay.pj_id =  '$pj_id'
					AND tag_essay.active =0
					and tag_essay.essay_id != 0
					GROUP BY tag_essay.usr_id";
		return $this->db->query($query)->result();
	}

	function get_pay_data(){
		$query = "SELECT usr.name, 
					SUM( IF( submit =1 and time > 18000 and ex_editing != '', TIME, 0 ) ) AS total_time, 
					SUM( IF( submit =1 and time > 18000 and ex_editing != '', word_count, 0 ) ) AS word_count, 
					COUNT( IF( discuss =  'Y' AND tag_essay.essay_id !=0, 1, NULL ) ) AS total, 
					COUNT( IF( submit = 1, 1, NULL ) ) AS submit,
					sum( IF( submit = 1, org_tag, 0 ) ) AS org_tag,
					sum( IF( submit = 1, replace_tag, 0 ) ) AS actual_tag,
					SUM( IF( submit = 1 and replace_tag != 0, word_count, 0 ) ) AS replace_word,
					SUM( IF( submit = 0 and replace_tag = 0, word_count, 0 ) ) AS todo_word,
					SUM( IF( submit = 1 and replace_tag = 0, word_count, 0 ) ) AS replace_error_word,
					SUM( IF( submit = 1 and replace_tag != 0, word_count, 0 ) ) AS avg_total_word
					FROM tag_essay
					LEFT JOIN usr ON usr.id = tag_essay.usr_id										
					where pj_id != 0					
					AND tag_essay.active =0
					and tag_essay.essay_id != 0					
					and tag_essay.usr_id != 1
					and type = 'musedata'
					GROUP BY tag_essay.usr_id";
		return $this->db->query($query)->result();
	}

// 	SUM( IF( submit = 1 and replace_tag != 0, word_count, 0 ) ) AS replace_word,
// SUM( IF( submit = 0 and replace_tag = 0, word_count, 0 ) ) AS todo_word,
// SUM( IF( submit = 1 and replace_tag = 0, word_count, 0 ) ) AS replace_error_word,
// SUM(word_count) AS each_pj_word,
// error_count.count AS error_count

	function error_count_up($usr_id,$pj_id){
		$chk = $this->db->query("SELECT * FROM error_count WHERE usr_id = '$usr_id' and pj_id = '$pj_id'");
		if($chk->num_rows() > 0){
			return $count_up = $this->db->query("UPDATE error_count set count = count+1 WHERE usr_id = '$usr_id' and pj_id = '$pj_id'");
		}else{
			return $this->db->query("INSERT INTO error_count(pj_id,usr_id,count) VALUES('$pj_id','$usr_id',1)");
		}
	}

	function editor_pjlist($usr_id){  		
  		return $this->db->query("SELECT pj_id, project.name, project.disc, project.add_date, tag_essay.usr_id,
									count(distinct tag_essay.essay_id) as total_count,
									count(if(tag_essay.submit = 1,1,null)) as completed,
									count(if(tag_essay.discuss = 'N',1,null)) as tbd
									FROM tag_essay
									LEFT JOIN project ON project.id = tag_essay.pj_id
									WHERE usr_id =  '$usr_id'
									and pj_id != 0
									AND tag_essay.essay_id != 0
									AND tag_essay.pj_active = 0
									AND tag_essay.active = 0
									GROUP BY pj_id
									ORDER BY add_date DESC ")->result();
   	}

   	function word_count_update(){
   		
   		$query = $this->db->query("SELECT * FROM tag_essay WHERE essay_id != 0 and word_count = 0 limit 2000");

   		foreach ($query->result() as $row)
		{
   			$id = $row->id;
   			$raw_txt = $row->raw_txt;
   			$count = str_word_count($raw_txt);   			
   			$this->db->query("UPDATE tag_essay SET word_count = '$count' WHERE id = '$id'");
   		}   		
   		return true;
   	}

   	function detecting_count(){
   		$query = $this->db->query("SELECT * FROM tag_essay WHERE essay_id != 0 and submit = 1 and active = 0 and ex_editing != ''");
   		//$query = $this->db->query("SELECT * FROM tag_essay WHERE essay_id = 10883");
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
   			
   			$this->db->query("UPDATE tag_essay SET org_tag = '$tag_count', replace_tag = '$det_count' WHERE id = '$id'");
   			
   		}   		
   		return true;
   	}

   	function garbage_data_del($essay_id,$type,$garbage_data_del){
   		$result = $this->db->query("UPDATE tag_essay SET editing = '$garbage_data_del' WHERE essay_id = '$essay_id' and type = '$type'");
   		return $result;      
   	}   	


   	// Export Sql

   	function export_page_count($pj_id){
   		return $this->db->query("SELECT count(id) as count FROM tag_essay WHERE pj_id = '$pj_id' and essay_id != 0 and discuss = 'Y' and  active = 0 and submit = 1 and ex_editing != ''")->row();
   	}   	

   	function export_index($pj_id){
   		return $this->db->query("SELECT count(tag_essay.essay_id) as total_count,
									count(if(tag_essay.ex_editing != '',1,null)) as export_count,
									project.name
									FROM tag_essay 
									left join project ON project.id = tag_essay.pj_id
									WHERE pj_id = '$pj_id' 
									and essay_id != 0 
									and discuss = 'Y' 									
									and tag_essay.active = 0 
									and submit = 1")->row();
   	}   	

   	function export_list($pj_id,$limit,$list){
   		$query = "SELECT tag_essay.*,usr.name
					FROM tag_essay
					left join usr on usr.id = tag_essay.usr_id
					WHERE tag_essay.pj_id = '$pj_id'					
					AND essay_id != 0
					AND tag_essay.active = 0					
					AND submit = 1
					AND ex_editing != ''
					LIMIT $limit,$list";
		return $this->db->query($query)->result();
   	}


   	// Error Chk Sql
	function ex_editing_update_service($essay_id,$replace_data,$before_editing,$type){
	   		preg_match_all("|<u>|", $before_editing, $u_matches);
			preg_match_all("|<strike>|", $before_editing, $s_matches);
			preg_match_all("|<b>|", $before_editing, $b_matches);

			preg_match_all("|</mod>|", $replace_data, $mod_matches);
			preg_match_all("|</ins>|", $replace_data, $ins_matches);
			preg_match_all("|</del>|", $replace_data, $del_matches);   			

			$org_tag = count($u_matches[0])+count($s_matches[0])+count($b_matches[0]);
			$replace_tag = count($mod_matches[0])+count($ins_matches[0])+count($del_matches[0]);

	   		$result = $this->db->query("UPDATE tag_essay SET ex_editing = '$replace_data', org_tag = '$org_tag', replace_tag = '$replace_tag' WHERE essay_id = '$essay_id' and type = '$type'");
	   		return $result;      
	}

	function error_replace($essay_id,$replace_data,$type){
			preg_match_all("|</mod>|", $replace_data, $mod_matches);
			preg_match_all("|</ins>|", $replace_data, $ins_matches);
			preg_match_all("|</del>|", $replace_data, $del_matches);   			

			$replace_tag = count($mod_matches[0])+count($ins_matches[0])+count($del_matches[0]);

	   		$result = $this->db->query("UPDATE tag_essay SET ex_editing = '$replace_data', replace_tag = '$replace_tag' WHERE essay_id = '$essay_id' and type = '$type'");
	   		return $result;      
	}







	// Service Sql
	function service_all_year_data(){ // 서비스가 시작한 모든 년도를 리턴한다!
		$query = "SELECT distinct DATE_FORMAT(sub_date, '%Y') as year
					FROM tag_essay
					WHERE sub_date
					BETWEEN '2013-01-01 00:00:00'
					AND now()
					AND essay_id != 0
					and type != 'musedata'
					and submit = 1
					and active = 0
					order by year desc";
		return $this->db->query($query)->result();
	}

	function service_month_data($yen){
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
					FROM tag_essay
					WHERE sub_date
					BETWEEN  '$start'
					AND  '$end'
					AND essay_id !=0
					AND submit =1	
					and type != 'musedata'				
					and active = 0
					group BY month DESC";
		return $this->db->query($query)->result();
	}

	function get_service_month_count($year,$month,$usr_id){
		$query = "SELECT count(*) as count 
					FROM tag_essay
					WHERE sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 23:59:59'
					AND essay_id !=0	
					AND usr_id = '$usr_id'
					and type != 'musedata'				
					and active = 0
					AND submit = 1 ";
		return $this->db->query($query)->row();
	}

	function get_service_month_data($usr_id,$year,$month,$limit,$page_list){
		$query = "SELECT tag_essay.*,usr.name
					FROM tag_essay
					LEFT join usr ON usr.id = tag_essay.usr_id
					WHERE tag_essay.sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 23:59:59'
					AND essay_id !=0
					AND usr_id = '$usr_id'
					and tag_essay.active = 0
					AND tag_essay.submit = 1
					and tag_essay.type != 'musedata'				
					ORDER BY sub_date DESC
					LIMIT $limit,$page_list";
		return $this->db->query($query)->result();
	}

	function admin_pjlist(){
		$query = "SELECT pj_id, project.name, project.disc, project.add_date, tag_essay.usr_id, 
					COUNT( IF( tag_essay.essay_id !=0, 1, NULL ) ) AS total_count, 
					COUNT( IF( tag_essay.submit =1, 1, NULL ) ) AS completed, 
					COUNT( IF( tag_essay.discuss =  'N', 1, NULL ) ) AS tbd, 
					COUNT( IF( tag_essay.submit =0
					AND tag_essay.essay_id !=0, 1, NULL ) ) AS todo
					FROM tag_essay
					LEFT JOIN project ON project.id = tag_essay.pj_id
					WHERE pj_id !=0
					AND tag_essay.pj_active =0
					AND tag_essay.active =0
					GROUP BY pj_id
					ORDER BY add_date DESC";
		return $this->db->query($query)->result();
	}

	function get_service_export_count($year,$month){
		$query = "SELECT count(*) as count 
					FROM tag_essay
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
		$query = "SELECT tag_essay.*,usr.name
					FROM tag_essay
					LEFT join usr ON usr.id = tag_essay.usr_id
					WHERE tag_essay.sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 23:59:59'
					AND essay_id !=0										
					and tag_essay.active = 0
					AND tag_essay.submit = 1
					and tag_essay.type != 'musedata'				
					ORDER BY sub_date DESC
					LIMIT $limit,$page_list";
		return $this->db->query($query)->result();
	}

	function service_export_total_count($month,$year){
		$query = "SELECT count(*) as count, count(if(ex_editing != '',1,null)) as export_count
					FROM tag_essay
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
					FROM tag_essay
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
		$query = "SELECT tag_essay.*,usr.name,usr.id as usr_id
					FROM tag_essay
					LEFT join usr ON usr.id = tag_essay.usr_id
					WHERE tag_essay.sub_date
					BETWEEN  '".$year."-".$month."-01 00:00:00'
					AND  '".$year."-".$month."-31 23:59:59'
					AND essay_id !=0
					AND ex_editing = ''
					and tag_essay.type != 'musedata'
					AND tag_essay.submit = 1
					and tag_essay.active = 0										
					LIMIT $limit,$page_list";
		return $this->db->query($query)->result();
	}

	// Service Sql End


	

	// Setting Sql

	function get_Editors(){
   		return $this->db->query("SELECT * FROM usr WHERE active = 0 and classify = 1 ORDER BY date desc")->result();
   	}	

   	function data_kind(){
   		return $this->db->query("SELECT * FROM data_kind")->result();
   	}

	function new_editor_accept($id){
   		$result = $this->db->query("UPDATE usr SET conf = 0 WHERE id = '$id'");
   		if($result){
			$tag_essay_query = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,draft,submit,type,active,start_date) VALUES('$id',0,0,0,'join',0,now())");				
			if($tag_essay_query){			
				return true;			
				
			}else{
				return false;
			}			
   		}else{
   			return false;
   		}
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
   			return $this->db->query("INSERT INTO tag_essay(usr_id,type) VALUES('$usr_id','join')");
   		}else{
   			return false;	
   		}
   		
   	}

   	function member_edit_save($usr_id,$musedata,$writing,$type,$start,$end,$pay){
   		return $this->db->query("UPDATE usr SET musedata = '$musedata', writing = '$writing', type = '$type', pay = '$pay', start = '$start', end = '$end' WHERE id = '$usr_id'");   		
   	}

   	function get_tags($data_kind_id){
   		return $this->db->query("SELECT tags.* 
   									FROM connect_tag
   									LEFT JOIN data_kind ON data_kind.id = connect_tag.data_kind_id
   									LEFT JOIN tags ON tags.id = connect_tag.tags_id
   									WHERE connect_tag.data_kind_id = '$data_kind_id'")->result();

   	}
   	//Setting End




   	// Import Sql

   	function import_sentence($pj_id,$sentence,$structure,$kind,$scoring,$critique){
		$raw_sentence = strip_tags($sentence);
		return $this->db->query("INSERT INTO essay(essay,structure,scoring,critique,date,type,kind,pj_id) VALUES('$raw_sentence','$structure','$scoring','$critique',now(),'musedata','$kind','$pj_id')");						
	}	

   	function new_essayList($pj_id){   		
		$query = "SELECT * FROM essay where id != 0 and pj_id = '$pj_id' and chk = 'N'";
		return $this->db->query($query)->result();	   		
	}

	function import_count($pj_id){
		$query = "SELECT count(id) as count FROM essay WHERE id != 0 and pj_id = '$pj_id' and chk = 'N'";
		return $this->db->query($query)->row();
	}

	function modal_editors($id){   		
   		return $this->db->query("SELECT usr.id as usr_id,usr.name
   									FROM usr 
   									LEFT JOIN cou ON cou.usr_id = usr.id
   									WHERE cou.pj_id = '$id' and cou.active = 0 and usr.classify = '1' and usr.conf = 0 and usr.active = 0")->result();
   	}

   	function import_mem_sentence($mem_id,$sentence_num,$pj_id){

   		$sentences = explode(',', $sentence_num);  		
   		
   		foreach ($sentences as $senid) {
   			$select = $this->db->query("SELECT * FROM essay WHERE id = '$senid'");

   			if($select->num_rows() > 0){

   				$row = $select->row();

   				$essay_id = $row->id;   				
   				$essay = trim($row->essay);
   				$structure = $row->structure;
   				$critique = $row->critique;
   				$scoring = $row->scoring;   				
   				$type = $row->type; 
   				$kind = $row->kind; 			

   				if($kind == 'essay'){
   					$sentence = explode('::', $essay);   				

	   				$prompt = mysql_real_escape_string($sentence[0]);
					$raw_txt = mysql_real_escape_string(strip_tags($structure));
					$critique = mysql_real_escape_string($critique);

	   				$word_count = str_word_count($sentence[1]);   	

					$patterns = array("(<IN>)","(<TR>)","(<TS>)","(<BO1>)","(<BO2>)","(<BO3>)","(<BO4>)","(<SI1>)","(<SI2>)","(<SI3>)","(<SI4>)","(<EX>)","(<CO>)","(<MI1>)","(<MI2>)","(<MI3>)","(<MI4>)",
									"(</IN>)","(</TR>)","(</TS>)","(</BO1>)","(</BO2>)","(</BO3>)","(</BO4>)","(</SI1>)","(</SI2>)","(</SI3>)","(</SI4>)","(</EX>)","(</CO>)","(</MI1>)","(</MI2>)","(</MI3>)","(</MI4>)");
						
					$replace = array("<span class='in' tag='IN'>&lt;IN&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='ts' tag='TS'>&lt;TS&gt;","<span class='bo' tag='BO1'>&lt;BO1&gt;","<span class='bo' tag='BO2'>&lt;BO2&gt;","<span class='bo' tag='BO3'>&lt;BO3&gt;","<span class='bo' tag='BO4'>&lt;BO4&gt;","<span class='si' tag='SI1'>&lt;SI1&gt;","<span class='si' tag='SI2'>&lt;SI2&gt;","<span class='si' tag='SI3'>&lt;SI3&gt;","<span class='si' tag='SI4'>&lt;SI4&gt;","<span class='ex' tag='EX'>&lt;EX&gt;","<span class='co' tag='CO'>&lt;CO&gt;","<span class='mi' tag='MI1'>&lt;MI1&gt;","<span class='mi' tag='MI2'>&lt;MI2&gt;","<span class='mi' tag='MI3'>&lt;MI3&gt;","<span class='mi' tag='MI4'>&lt;MI4&gt;",
										"&lt;/IN&gt;</span>","&lt;/TR&gt;</span>","&lt;/TS&gt;</span>","&lt;/BO1&gt;</span>","&lt;/BO2&gt;</span>","&lt;/BO3&gt;</span>","&lt;/BO4&gt;</span>","&lt;/SI1&gt;</span>","&lt;/SI2&gt;</span>","&lt;/SI3&gt;</span>","&lt;/SI4&gt;</span>","&lt;/EX&gt;</span>","&lt;/CO&gt;</span>","&lt;/MI1&gt;</span>","&lt;/MI2&gt;</span>","&lt;/MI3&gt;</span>","&lt;/MI4&gt;</span>");

					$data = preg_replace($patterns, $replace, $structure);
					$data = mysql_real_escape_string(trim($data));		
   				}elseif($kind == 'diary'){
   					$diary = strip_tags($essay);

   					if(strlen($diary) > 120){
   						$prompt = mysql_real_escape_string(substr($diary, 0,140).'...');	
   					}else{
   						$prompt = mysql_real_escape_string($diary);
   					}
   					
   					$raw_txt = mysql_real_escape_string($diary);	   									
					$critique = mysql_real_escape_string($critique);
	   				$word_count = str_word_count(strip_tags($essay));

					$patterns = array("(<EV>)","(<TR>)","(<SR>)","(<CO>)",
									"(</EV>)","(</TR>)","(</SR>)","(</CO>)");
						
					$replace = array("<span class='ev' tag='EV'>&lt;EV&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='sr' tag='SR'>&lt;SR&gt;","<span class='co' tag='CO'>&lt;CO&gt;",
										"&lt;/EV&gt;</span>","&lt;/TR&gt;</span>","&lt;/SR&gt;</span>","&lt;/CO&gt;</span>");

					$data = preg_replace($patterns, $replace, $structure);
					$data = mysql_real_escape_string(trim($data));	
   				}   				

   				$ins = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,critique,scoring,tagging,draft,word_count) 
   											VALUES('$mem_id','$essay_id','$prompt','$raw_txt','$kind','$type',now(),'$pj_id','$raw_txt','$critique','$scoring','$data','1','$word_count')");

   				if($ins){
   					$sen_up = $this->db->query("UPDATE essay SET chk = 'Y' WHERE id = '$essay_id'");
	   				if(!$sen_up){
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

   	function equal_distribute($total_essay_count,$pj_id){		
		$count = $this->db->query("SELECT count(distinct usr_id) as count 
									FROM tag_essay WHERE pj_id = '$pj_id' and pj_active = 0 and active = 0 and usr_id != 1")->row();
		$usr_count = $count->count; //usr 전체 수를 구한다!		

		$division =  floor($total_essay_count/$usr_count); // user 각각이 가져야 할 essay수!		

		$remainder = $total_essay_count - ($division*$usr_count); //user 각각 모두 똑같이 가지고남은 essay수! 		

		if($division > 0){
			
		 	$data = $this->db->query("SELECT usr_id	FROM tag_essay WHERE pj_id = '$pj_id' and pj_active = 0 and active = 0 and usr_id != 1 group by usr_id");		
			foreach ($data->result() as $value) {
				$usr_id = $value->usr_id;				
							
				$essays = $this->db->query("SELECT * FROM essay WHERE pj_id = '$pj_id' and chk = 'N' LIMIT $division");				
				
				foreach ($essays->result() as $row) {
					$essay_id = $row->id;
	   				$essay = trim($row->essay);
	   				$structure = $row->structure;
	   				$scoring = $row->scoring;   				
	   				$critique = $row->critique;	   				
	   				$type = $row->type; 
	   				$kind = $row->kind; 			

	   	// 			$sentence = explode('::', $essay);
	   	// 			// $structure_tag = explode('::', $structure);

	   	// 			$prompt = mysql_real_escape_string($sentence[0]);
					// $raw_txt = mysql_real_escape_string(strip_tags($structure));
					// $word_count = str_word_count($sentence[1]);   	

					// $critique = mysql_real_escape_string($critique);	   				

					// $patterns = array("(<IN>)","(<TR>)","(<TS>)","(<BO1>)","(<BO2>)","(<BO3>)","(<BO4>)","(<SI1>)","(<SI2>)","(<SI3>)","(<SI4>)","(<EX>)","(<CO>)","(<MI1>)","(<MI2>)","(<MI3>)","(<MI4>)",
					// 				"(</IN>)","(</TR>)","(</TS>)","(</BO1>)","(</BO2>)","(</BO3>)","(</BO4>)","(</SI1>)","(</SI2>)","(</SI3>)","(</SI4>)","(</EX>)","(</CO>)","(</MI1>)","(</MI2>)","(</MI3>)","(</MI4>)");
						
					// $replace = array("<span class='in' tag='IN'>&lt;IN&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='ts' tag='TS'>&lt;TS&gt;","<span class='bo' tag='BO1'>&lt;BO1&gt;","<span class='bo' tag='BO2'>&lt;BO2&gt;","<span class='bo' tag='BO3'>&lt;BO3&gt;","<span class='bo' tag='BO4'>&lt;BO4&gt;","<span class='si' tag='SI1'>&lt;SI1&gt;","<span class='si' tag='SI2'>&lt;SI2&gt;","<span class='si' tag='SI3'>&lt;SI3&gt;","<span class='si' tag='SI4'>&lt;SI4&gt;","<span class='ex' tag='EX'>&lt;EX&gt;","<span class='co' tag='CO'>&lt;CO&gt;","<span class='mi' tag='MI1'>&lt;MI1&gt;","<span class='mi' tag='MI2'>&lt;MI2&gt;","<span class='mi' tag='MI3'>&lt;MI3&gt;","<span class='mi' tag='MI4'>&lt;MI4&gt;",
					// 					"&lt;/IN&gt;</span>","&lt;/TR&gt;</span>","&lt;/TS&gt;</span>","&lt;/BO1&gt;</span>","&lt;/BO2&gt;</span>","&lt;/BO3&gt;</span>","&lt;/BO4&gt;</span>","&lt;/SI1&gt;</span>","&lt;/SI2&gt;</span>","&lt;/SI3&gt;</span>","&lt;/SI4&gt;</span>","&lt;/EX&gt;</span>","&lt;/CO&gt;</span>","&lt;/MI1&gt;</span>","&lt;/MI2&gt;</span>","&lt;/MI3&gt;</span>","&lt;/MI4&gt;</span>");

					// $data = preg_replace($patterns, $replace, $structure);
					// $data = mysql_real_escape_string(trim($data));	

					if($kind == 'essay'){
	   					$sentence = explode('::', $essay);   				

		   				$prompt = mysql_real_escape_string($sentence[0]);
						$raw_txt = mysql_real_escape_string(strip_tags($structure));
						$critique = mysql_real_escape_string($critique);

		   				$word_count = str_word_count($sentence[1]);   	

						$patterns = array("(<IN>)","(<TR>)","(<TS>)","(<BO1>)","(<BO2>)","(<BO3>)","(<BO4>)","(<SI1>)","(<SI2>)","(<SI3>)","(<SI4>)","(<EX>)","(<CO>)","(<MI1>)","(<MI2>)","(<MI3>)","(<MI4>)",
										"(</IN>)","(</TR>)","(</TS>)","(</BO1>)","(</BO2>)","(</BO3>)","(</BO4>)","(</SI1>)","(</SI2>)","(</SI3>)","(</SI4>)","(</EX>)","(</CO>)","(</MI1>)","(</MI2>)","(</MI3>)","(</MI4>)");
							
						$replace = array("<span class='in' tag='IN'>&lt;IN&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='ts' tag='TS'>&lt;TS&gt;","<span class='bo' tag='BO1'>&lt;BO1&gt;","<span class='bo' tag='BO2'>&lt;BO2&gt;","<span class='bo' tag='BO3'>&lt;BO3&gt;","<span class='bo' tag='BO4'>&lt;BO4&gt;","<span class='si' tag='SI1'>&lt;SI1&gt;","<span class='si' tag='SI2'>&lt;SI2&gt;","<span class='si' tag='SI3'>&lt;SI3&gt;","<span class='si' tag='SI4'>&lt;SI4&gt;","<span class='ex' tag='EX'>&lt;EX&gt;","<span class='co' tag='CO'>&lt;CO&gt;","<span class='mi' tag='MI1'>&lt;MI1&gt;","<span class='mi' tag='MI2'>&lt;MI2&gt;","<span class='mi' tag='MI3'>&lt;MI3&gt;","<span class='mi' tag='MI4'>&lt;MI4&gt;",
											"&lt;/IN&gt;</span>","&lt;/TR&gt;</span>","&lt;/TS&gt;</span>","&lt;/BO1&gt;</span>","&lt;/BO2&gt;</span>","&lt;/BO3&gt;</span>","&lt;/BO4&gt;</span>","&lt;/SI1&gt;</span>","&lt;/SI2&gt;</span>","&lt;/SI3&gt;</span>","&lt;/SI4&gt;</span>","&lt;/EX&gt;</span>","&lt;/CO&gt;</span>","&lt;/MI1&gt;</span>","&lt;/MI2&gt;</span>","&lt;/MI3&gt;</span>","&lt;/MI4&gt;</span>");

						$data = preg_replace($patterns, $replace, $structure);
						$data = mysql_real_escape_string(trim($data));		
	   				}elseif($kind == 'diary'){
	   					$diary = strip_tags($essay);

	   					if(strlen($diary) > 120){
	   						$prompt = mysql_real_escape_string(substr($diary, 0,140).'...');	
	   					}else{
	   						$prompt = mysql_real_escape_string($diary);
	   					}
	   					
	   					$raw_txt = mysql_real_escape_string($diary);	   									
						$critique = mysql_real_escape_string($critique);
		   				$word_count = str_word_count(strip_tags($essay));

						$patterns = array("(<EV>)","(<TR>)","(<SR>)","(<CO>)",
										"(</EV>)","(</TR>)","(</SR>)","(</CO>)");
							
						$replace = array("<span class='ev' tag='EV'>&lt;EV&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='sr' tag='SR'>&lt;SR&gt;","<span class='co' tag='CO'>&lt;CO&gt;",
											"&lt;/EV&gt;</span>","&lt;/TR&gt;</span>","&lt;/SR&gt;</span>","&lt;/CO&gt;</span>");

						$data = preg_replace($patterns, $replace, $structure);
						$data = mysql_real_escape_string(trim($data));	
	   				}   				

	   				$ins = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,critique,scoring,tagging,draft,word_count) 
	   											VALUES('$usr_id','$essay_id','$prompt','$raw_txt','$kind','$type',now(),'$pj_id','$raw_txt','$critique','$scoring','$data','1','$word_count')");

	   				if($ins){
	   					$sen_up = $this->db->query("UPDATE essay SET chk = 'Y' WHERE id = '$essay_id'");
		   				if(!$sen_up){
		   					return 'false';   				
		   				}	   					
	   				}else{
	   					return 1;
	   				}
	   				
				} // foreach end.
			}			

			// 나머지 essay를 가지고 다시 한번 랜덤으로 user을 뽑아서 essay를 출제 한다!
			$remainder_essay = $this->db->query("SELECT * FROM essay WHERE pj_id = '$pj_id' and chk = 'N'");
		
			if($remainder_essay->num_rows() > 0){				
				foreach ($remainder_essay->result() as $row) {
					$usrs = $this->db->query("SELECT usr_id	FROM tag_essay WHERE pj_id = '$pj_id' and pj_active = 0 and active = 0 and usr_id != 1 ORDER BY RAND() limit 1")->row();		
					$re_usr_id = $usrs->usr_id;

					$essay_id = $row->id;   				
	   				$essay = trim($row->essay);
	   				$structure = $row->structure;
	   				$scoring = $row->scoring;   				
	   				$critique = $row->critique;	   				
	   				$type = $row->type; 
	   				$kind = $row->kind; 			

	   	// 			$sentence = explode('::', $essay);
	   	// 			// $structure_tag = explode('::', $structure);

	   	// 			$prompt = mysql_real_escape_string($sentence[0]);
					// $raw_txt = mysql_real_escape_string(strip_tags($structure));
					// $word_count = str_word_count($sentence[1]);   	

					// $critique = mysql_real_escape_string($critique);	   				

					// $patterns = array("(<IN>)","(<TR>)","(<TS>)","(<BO1>)","(<BO2>)","(<BO3>)","(<BO4>)","(<SI1>)","(<SI2>)","(<SI3>)","(<SI4>)","(<EX>)","(<CO>)","(<MI1>)","(<MI2>)","(<MI3>)","(<MI4>)",
					// 				"(</IN>)","(</TR>)","(</TS>)","(</BO1>)","(</BO2>)","(</BO3>)","(</BO4>)","(</SI1>)","(</SI2>)","(</SI3>)","(</SI4>)","(</EX>)","(</CO>)","(</MI1>)","(</MI2>)","(</MI3>)","(</MI4>)");
						
					// $replace = array("<span class='in' tag='IN'>&lt;IN&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='ts' tag='TS'>&lt;TS&gt;","<span class='bo' tag='BO1'>&lt;BO1&gt;","<span class='bo' tag='BO2'>&lt;BO2&gt;","<span class='bo' tag='BO3'>&lt;BO3&gt;","<span class='bo' tag='BO4'>&lt;BO4&gt;","<span class='si' tag='SI1'>&lt;SI1&gt;","<span class='si' tag='SI2'>&lt;SI2&gt;","<span class='si' tag='SI3'>&lt;SI3&gt;","<span class='si' tag='SI4'>&lt;SI4&gt;","<span class='ex' tag='EX'>&lt;EX&gt;","<span class='co' tag='CO'>&lt;CO&gt;","<span class='mi' tag='MI1'>&lt;MI1&gt;","<span class='mi' tag='MI2'>&lt;MI2&gt;","<span class='mi' tag='MI3'>&lt;MI3&gt;","<span class='mi' tag='MI4'>&lt;MI4&gt;",
					// 					"&lt;/IN&gt;</span>","&lt;/TR&gt;</span>","&lt;/TS&gt;</span>","&lt;/BO1&gt;</span>","&lt;/BO2&gt;</span>","&lt;/BO3&gt;</span>","&lt;/BO4&gt;</span>","&lt;/SI1&gt;</span>","&lt;/SI2&gt;</span>","&lt;/SI3&gt;</span>","&lt;/SI4&gt;</span>","&lt;/EX&gt;</span>","&lt;/CO&gt;</span>","&lt;/MI1&gt;</span>","&lt;/MI2&gt;</span>","&lt;/MI3&gt;</span>","&lt;/MI4&gt;</span>");

					// $data = preg_replace($patterns, $replace, $structure);
					// $data = mysql_real_escape_string(trim($data));

					if($kind == 'essay'){
	   					$sentence = explode('::', $essay);   				

		   				$prompt = mysql_real_escape_string($sentence[0]);
						$raw_txt = mysql_real_escape_string(strip_tags($structure));
						$critique = mysql_real_escape_string($critique);

		   				$word_count = str_word_count($sentence[1]);   	

						$patterns = array("(<IN>)","(<TR>)","(<TS>)","(<BO1>)","(<BO2>)","(<BO3>)","(<BO4>)","(<SI1>)","(<SI2>)","(<SI3>)","(<SI4>)","(<EX>)","(<CO>)","(<MI1>)","(<MI2>)","(<MI3>)","(<MI4>)",
										"(</IN>)","(</TR>)","(</TS>)","(</BO1>)","(</BO2>)","(</BO3>)","(</BO4>)","(</SI1>)","(</SI2>)","(</SI3>)","(</SI4>)","(</EX>)","(</CO>)","(</MI1>)","(</MI2>)","(</MI3>)","(</MI4>)");
							
						$replace = array("<span class='in' tag='IN'>&lt;IN&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='ts' tag='TS'>&lt;TS&gt;","<span class='bo' tag='BO1'>&lt;BO1&gt;","<span class='bo' tag='BO2'>&lt;BO2&gt;","<span class='bo' tag='BO3'>&lt;BO3&gt;","<span class='bo' tag='BO4'>&lt;BO4&gt;","<span class='si' tag='SI1'>&lt;SI1&gt;","<span class='si' tag='SI2'>&lt;SI2&gt;","<span class='si' tag='SI3'>&lt;SI3&gt;","<span class='si' tag='SI4'>&lt;SI4&gt;","<span class='ex' tag='EX'>&lt;EX&gt;","<span class='co' tag='CO'>&lt;CO&gt;","<span class='mi' tag='MI1'>&lt;MI1&gt;","<span class='mi' tag='MI2'>&lt;MI2&gt;","<span class='mi' tag='MI3'>&lt;MI3&gt;","<span class='mi' tag='MI4'>&lt;MI4&gt;",
											"&lt;/IN&gt;</span>","&lt;/TR&gt;</span>","&lt;/TS&gt;</span>","&lt;/BO1&gt;</span>","&lt;/BO2&gt;</span>","&lt;/BO3&gt;</span>","&lt;/BO4&gt;</span>","&lt;/SI1&gt;</span>","&lt;/SI2&gt;</span>","&lt;/SI3&gt;</span>","&lt;/SI4&gt;</span>","&lt;/EX&gt;</span>","&lt;/CO&gt;</span>","&lt;/MI1&gt;</span>","&lt;/MI2&gt;</span>","&lt;/MI3&gt;</span>","&lt;/MI4&gt;</span>");

						$data = preg_replace($patterns, $replace, $structure);
						$data = mysql_real_escape_string(trim($data));		
	   				}elseif($kind == 'diary'){
	   					$diary = strip_tags($essay);

	   					if(strlen($diary) > 120){
	   						$prompt = mysql_real_escape_string(substr($diary, 0,140).'...');	
	   					}else{
	   						$prompt = mysql_real_escape_string($diary);
	   					}
	   					
	   					$raw_txt = mysql_real_escape_string($diary);	   									
						$critique = mysql_real_escape_string($critique);
		   				$word_count = str_word_count(strip_tags($essay));

						$patterns = array("(<EV>)","(<TR>)","(<SR>)","(<CO>)",
										"(</EV>)","(</TR>)","(</SR>)","(</CO>)");
							
						$replace = array("<span class='ev' tag='EV'>&lt;EV&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='sr' tag='SR'>&lt;SR&gt;","<span class='co' tag='CO'>&lt;CO&gt;",
											"&lt;/EV&gt;</span>","&lt;/TR&gt;</span>","&lt;/SR&gt;</span>","&lt;/CO&gt;</span>");

						$data = preg_replace($patterns, $replace, $structure);
						$data = mysql_real_escape_string(trim($data));	
	   				}   					

	   				$ins = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,critique,scoring,tagging,draft,word_count) 
	   											VALUES('$re_usr_id','$essay_id','$prompt','$raw_txt','$kind','$type',now(),'$pj_id','$raw_txt','$critique','$scoring','$data','1','$word_count')");

	   				if($ins){
	   					$sen_up = $this->db->query("UPDATE essay SET chk = 'Y' WHERE id = '$essay_id'");
		   				if(!$sen_up){
		   					return 'false';   				
		   				}	   					
	   				}else{
	   					return 1;
	   				}

				} //foreach end.
			}
			return 'true';
		}else { //division == 0 일때!						
			
			// 새로운 essay수가 에디터 수보다 적을때!! 소수점으로 떨어질때!
			// 나머지 essay를 가지고 다시 한번 랜덤으로 user을 뽑아서 essay를 출제 한다!
			$remainder_essay = $this->db->query("SELECT * FROM essay WHERE pj_id = '$pj_id' and chk = 'N'");

			if($remainder_essay->num_rows() > 0){
				
				//$this->db->query("UPDATE cou SET update_count = 0");
				foreach ($remainder_essay->result() as $row) {					
					$usrs = $this->db->query("SELECT usr_id	FROM tag_essay WHERE pj_id = '$pj_id' and pj_active = 0 and active = 0 and usr_id != 1 ORDER BY RAND() limit 1")->row();		
					$re_usr_id = $usrs->usr_id;

					$essay_id = $row->id;   				
	   				$essay = trim($row->essay);
	   				$structure = $row->structure;
	   				$scoring = $row->scoring;   				
	   				$critique = $row->critique;	   				
	   				$type = $row->type; 
	   				$kind = $row->kind; 			

	   	// 			$sentence = explode('::', $essay);
	   	// 			// $structure_tag = explode('::', $structure);

	   	// 			$prompt = mysql_real_escape_string($sentence[0]);
					// $raw_txt = mysql_real_escape_string(strip_tags($structure));
					// $word_count = str_word_count($sentence[1]);   	

					// $critique = mysql_real_escape_string($critique);	   				

					// $patterns = array("(<IN>)","(<TR>)","(<TS>)","(<BO1>)","(<BO2>)","(<BO3>)","(<BO4>)","(<SI1>)","(<SI2>)","(<SI3>)","(<SI4>)","(<EX>)","(<CO>)","(<MI1>)","(<MI2>)","(<MI3>)","(<MI4>)",
					// 				"(</IN>)","(</TR>)","(</TS>)","(</BO1>)","(</BO2>)","(</BO3>)","(</BO4>)","(</SI1>)","(</SI2>)","(</SI3>)","(</SI4>)","(</EX>)","(</CO>)","(</MI1>)","(</MI2>)","(</MI3>)","(</MI4>)");
						
					// $replace = array("<span class='in' tag='IN'>&lt;IN&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='ts' tag='TS'>&lt;TS&gt;","<span class='bo' tag='BO1'>&lt;BO1&gt;","<span class='bo' tag='BO2'>&lt;BO2&gt;","<span class='bo' tag='BO3'>&lt;BO3&gt;","<span class='bo' tag='BO4'>&lt;BO4&gt;","<span class='si' tag='SI1'>&lt;SI1&gt;","<span class='si' tag='SI2'>&lt;SI2&gt;","<span class='si' tag='SI3'>&lt;SI3&gt;","<span class='si' tag='SI4'>&lt;SI4&gt;","<span class='ex' tag='EX'>&lt;EX&gt;","<span class='co' tag='CO'>&lt;CO&gt;","<span class='mi' tag='MI1'>&lt;MI1&gt;","<span class='mi' tag='MI2'>&lt;MI2&gt;","<span class='mi' tag='MI3'>&lt;MI3&gt;","<span class='mi' tag='MI4'>&lt;MI4&gt;",
					// 					"&lt;/IN&gt;</span>","&lt;/TR&gt;</span>","&lt;/TS&gt;</span>","&lt;/BO1&gt;</span>","&lt;/BO2&gt;</span>","&lt;/BO3&gt;</span>","&lt;/BO4&gt;</span>","&lt;/SI1&gt;</span>","&lt;/SI2&gt;</span>","&lt;/SI3&gt;</span>","&lt;/SI4&gt;</span>","&lt;/EX&gt;</span>","&lt;/CO&gt;</span>","&lt;/MI1&gt;</span>","&lt;/MI2&gt;</span>","&lt;/MI3&gt;</span>","&lt;/MI4&gt;</span>");

					// $data = preg_replace($patterns, $replace, $structure);
					// $data = mysql_real_escape_string(trim($data));

					if($kind == 'essay'){
	   					$sentence = explode('::', $essay);   				

		   				$prompt = mysql_real_escape_string($sentence[0]);
						$raw_txt = mysql_real_escape_string(strip_tags($structure));
						$critique = mysql_real_escape_string($critique);

		   				$word_count = str_word_count($sentence[1]);   	

						$patterns = array("(<IN>)","(<TR>)","(<TS>)","(<BO1>)","(<BO2>)","(<BO3>)","(<BO4>)","(<SI1>)","(<SI2>)","(<SI3>)","(<SI4>)","(<EX>)","(<CO>)","(<MI1>)","(<MI2>)","(<MI3>)","(<MI4>)",
										"(</IN>)","(</TR>)","(</TS>)","(</BO1>)","(</BO2>)","(</BO3>)","(</BO4>)","(</SI1>)","(</SI2>)","(</SI3>)","(</SI4>)","(</EX>)","(</CO>)","(</MI1>)","(</MI2>)","(</MI3>)","(</MI4>)");
							
						$replace = array("<span class='in' tag='IN'>&lt;IN&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='ts' tag='TS'>&lt;TS&gt;","<span class='bo' tag='BO1'>&lt;BO1&gt;","<span class='bo' tag='BO2'>&lt;BO2&gt;","<span class='bo' tag='BO3'>&lt;BO3&gt;","<span class='bo' tag='BO4'>&lt;BO4&gt;","<span class='si' tag='SI1'>&lt;SI1&gt;","<span class='si' tag='SI2'>&lt;SI2&gt;","<span class='si' tag='SI3'>&lt;SI3&gt;","<span class='si' tag='SI4'>&lt;SI4&gt;","<span class='ex' tag='EX'>&lt;EX&gt;","<span class='co' tag='CO'>&lt;CO&gt;","<span class='mi' tag='MI1'>&lt;MI1&gt;","<span class='mi' tag='MI2'>&lt;MI2&gt;","<span class='mi' tag='MI3'>&lt;MI3&gt;","<span class='mi' tag='MI4'>&lt;MI4&gt;",
											"&lt;/IN&gt;</span>","&lt;/TR&gt;</span>","&lt;/TS&gt;</span>","&lt;/BO1&gt;</span>","&lt;/BO2&gt;</span>","&lt;/BO3&gt;</span>","&lt;/BO4&gt;</span>","&lt;/SI1&gt;</span>","&lt;/SI2&gt;</span>","&lt;/SI3&gt;</span>","&lt;/SI4&gt;</span>","&lt;/EX&gt;</span>","&lt;/CO&gt;</span>","&lt;/MI1&gt;</span>","&lt;/MI2&gt;</span>","&lt;/MI3&gt;</span>","&lt;/MI4&gt;</span>");

						$data = preg_replace($patterns, $replace, $structure);
						$data = mysql_real_escape_string(trim($data));		
	   				}elseif($kind == 'diary'){
	   					$diary = strip_tags($essay);

	   					if(strlen($diary) > 120){
	   						$prompt = substr($diary, 0,140).'...';	
	   					}else{
	   						$prompt = $diary;
	   					}
	   					
	   					$raw_txt = mysql_real_escape_string($diary);	   									
						$critique = mysql_real_escape_string($critique);
		   				$word_count = str_word_count(strip_tags($essay));

						$patterns = array("(<EV>)","(<TR>)","(<SR>)","(<CO>)",
										"(</EV>)","(</TR>)","(</SR>)","(</CO>)");
							
						$replace = array("<span class='ev' tag='EV'>&lt;EV&gt;","<span class='tr' tag='TR'>&lt;TR&gt;","<span class='sr' tag='SR'>&lt;SR&gt;","<span class='co' tag='CO'>&lt;CO&gt;",
											"&lt;/EV&gt;</span>","&lt;/TR&gt;</span>","&lt;/SR&gt;</span>","&lt;/CO&gt;</span>");

						$data = preg_replace($patterns, $replace, $structure);
						$data = mysql_real_escape_string(trim($data));	
	   				}   				

	   				$ins = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id,editing,critique,scoring,tagging,draft,word_count) 
	   											VALUES('$re_usr_id','$essay_id','$prompt','$raw_txt','$kind','$type',now(),'$pj_id','$raw_txt','$critique','$scoring','$data','1','$word_count')");

	   				if($ins){
	   					$sen_up = $this->db->query("UPDATE essay SET chk = 'Y' WHERE id = '$essay_id'");
		   				if(!$sen_up){
		   					return 'false';   				
		   				}	   					
	   				}else{
	   					return 1;
	   				}

				} // foreach end.
				return 'true';
			}else{
				return '6';
			}			
		} //else if end.				
	}

	// Import End


	public function getList($usr_id){
		$query = "SELECT * FROM essay where id != 0";
		return $this->db->query($query)->result();
	}

	

	public function memList($usr_id){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = 0 and essay_id != 0";
		return $this->db->query($query)->result();
	}

	public function pj_memList($pj_id,$usr_id){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and essay_id != 0";
		return $this->db->query($query)->result();
	}

	public function admin_pj_history($pj_id,$usr_id,$page,$limit,$list){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and pj_active = 0 and essay_id != 0 and discuss = 'Y' LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	public function admin_pj_todo($pj_id,$usr_id,$page,$limit,$list){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and pj_active = 0 and essay_id != 0 and submit != 1 and discuss = 'Y' LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	public function admin_pj_share($pj_id,$usr_id,$page,$limit,$list){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and pj_active = 0 and essay_id != 0 and submit != 1 and discuss = 'Y' LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	public function admin_pj_done($pj_id,$usr_id,$page,$limit,$list){ // 0
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and pj_active = 0 and essay_id != 0 and draft = 1 and submit = 1 and discuss = 'Y' ORDER BY sub_date desc LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	public function admin_history($usr_id,$page,$limit,$list){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = 0 and pj_active = 0 and essay_id != 0 LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	public function admin_todo($usr_id,$page,$limit,$list){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = 0 and pj_active = 0 and essay_id != 0 and submit != 1 LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	public function edi_todo($usr_id,$page,$limit,$list){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = 0 and pj_active = 0 and essay_id != 0 and submit != 1 and discuss = 'Y' LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	public function admin_done($usr_id,$page,$limit,$list){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = 0 and pj_active = 0 and essay_id != 0 and draft = 1 and submit = 1 LIMIT $limit,$list";
		return $this->db->query($query)->result();
	}

	// public function essayList($usr_id){
	// 	$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = 0 and submit = 0 and type = 'musedata' limit 10";
	// 	return $this->db->query($query)->result();
	// }

	public function page_essayList($usr_id,$last_num){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = 0 and submit = 0 and essay_id != 0 and type = 'musedata' and id >= '$last_num' limit 10";
		return $this->db->query($query)->result();
	}

	public function get_todolist($usr_id,$last_num){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = 0 and submit = 0 and type = 'musedata' and id > '$last_num' LIMIT 20";
		return $this->db->query($query)->result();
	}

	public function editor_pj_todolist($usr_id,$pj_id,$last_num){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and essay_id != 0 and id > '$last_num' limit 10";
		return $this->db->query($query)->result();
	}

	// public function doneList($usr_id){
	// 	$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = 0 and submit = 1 and essay_id != 0";
	// 	return $this->db->query($query)->result();
	// }	

	public function other_donelist($usr_id,$last_num){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = 0 and submit = 1 and essay_id != 0 and id >= '$last_num' LIMIT 10";
		return $this->db->query($query)->result();
	}	

	public function pj_doneList($pj_id,$usr_id){
		$query = "SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0 and submit = 1 and type in('musedata','writing') ORDER BY sub_date desc";
		return $this->db->query($query)->result();
	}	

	// public function memName($usr_id){
	// 	return $this->db->query("SELECT * FROM usr WHERE id = '$usr_id'")->row();
	// }

	

	public function distribute($cou,$pj_id){		
		$count = $this->db->query("SELECT count(id) as count FROM cou WHERE pj_id = '$pj_id' and active = 0")->row();
		$usr_count = $count->count; //usr 전체 수를 구한다!		

		$division =  floor($cou/$usr_count); // user 각각이 가져야 할 essay수!		

		$remainder = $cou - ($division*$usr_count); //user 각각 모두 똑같이 가지고남은 essay수! 		

		if($division > 0){
			
		 	$data = $this->db->query("SELECT usr_id FROM cou WHERE pj_id = '$pj_id' and active = 0");		
			foreach ($data->result() as $value) {
				$usr_id = $value->usr_id;				
							
				$essays = $this->db->query("SELECT * FROM essay WHERE pj_id = '$pj_id' and chk = 'N' LIMIT $division");				
				foreach ($essays->result() as $essay) {
					$essay_id = $essay->id;
					$title = mysql_real_escape_string($essay->prompt);
					$raw_txt = mysql_real_escape_string($essay->essay);
					$type = $essay->type;
					$kind = $essay->kind;					
					//echo $essay_id;
					$insert = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id) VALUES('$usr_id','$essay_id','$title','$raw_txt','$kind','$type',now(),'$pj_id')");
					if($insert){
						$this->db->query("UPDATE essay SET chk = 'Y' WHERE id = '$essay_id'");						
					}else{
						return '1';
					}
				}
				$this->db->query("UPDATE cou SET update_count = '$division' WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0");
				
			}			

			// 나머지 essay를 가지고 다시 한번 랜덤으로 user을 뽑아서 essay를 출제 한다!
			$remainder_essay = $this->db->query("SELECT * FROM essay WHERE pj_id = '$pj_id' and chk = 'N'");
		
			if($remainder_essay->num_rows() > 0){
				
				foreach ($remainder_essay->result() as $value) {
					$re_essay_id = $value->id;
					$title = trim(mysql_real_escape_string($value->prompt));
					$raw_txt = trim(mysql_real_escape_string($value->essay));
					$type = $value->type;
					$kind = $value->kind;

					$usrs = $this->db->query("SELECT usr_id FROM cou WHERE pj_id = '$pj_id' and active = 0 ORDER BY RAND() limit 1")->row();		
					$re_usr_id = $usrs->usr_id;

					$remainder_insert = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id) VALUES('$re_usr_id','$re_essay_id','$title','$raw_txt','$kind','$type',now(),'$pj_id')");

					if($remainder_insert){
						$rand = $this->db->query("UPDATE essay SET chk = 'Y' WHERE id = '$re_essay_id'");
						if($rand){
							$this->db->query("UPDATE cou SET update_count = update_count+1 WHERE usr_id = '$re_usr_id' and pj_id = '$pj_id' and active = 0");
							
						}else{
							return '2';
						}
					}else{
						return '3';
					}
				}
			}
			return 'true';
		}else { //division == 0 일때!						
			
			// 새로운 essay수가 에디터 수보다 적을때!! 소수점으로 떨어질때!
			// 나머지 essay를 가지고 다시 한번 랜덤으로 user을 뽑아서 essay를 출제 한다!
			$remainder_essay = $this->db->query("SELECT * FROM essay WHERE pj_id = '$pj_id' and chk = 'N'");

			if($remainder_essay->num_rows() > 0){
				
				//$this->db->query("UPDATE cou SET update_count = 0");
				foreach ($remainder_essay->result() as $value) {
					$re_essay_id = $value->id;
					$title = trim(mysql_real_escape_string($value->prompt));
					$raw_txt = trim(mysql_real_escape_string($value->essay));
					$type = $value->type;
					$kind = $value->kind;

					$usrs = $this->db->query("SELECT usr_id FROM cou WHERE pj_id = '$pj_id' and active = 0 ORDER BY RAND() limit 1")->row();		
					$re_usr_id = $usrs->usr_id;

					//echo $re_usr_id;
					$remainder_insert = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id) VALUES('$re_usr_id','$re_essay_id','$title','$raw_txt','$kind','$type',now(),'$pj_id')");

					if($remainder_insert){
						$rand = $this->db->query("UPDATE essay SET chk = 'Y' WHERE id = '$re_essay_id'");
						//$rand = true;
						if($rand){				

							$count_up = $this->db->query("UPDATE cou SET update_count = update_count+1 WHERE usr_id = '$re_usr_id' and pj_id = '$pj_id' and active = 0");
							if(!$count_up){
								return '7';
							}
						}else{
							return '4';
						}
					}else{
						return '5';
					}
				}
				return 'true';
			}else{
				return '6';
			}			
		} //else if end.				
	}

	public function status_list(){
		return $this->db->query("SELECT usr.id AS usr_id, usr.name, COUNT( IF( tag_essay.type = 'musedata', 1, NULL ) ) AS tagging, COUNT( IF( tag_essay.type =  'writing', 1, NULL ) ) AS writing
									FROM tag_essay
									LEFT JOIN usr ON usr.id = tag_essay.usr_id
									WHERE tag_essay.active = 0
									AND usr.classify = 1
									AND usr.conf = 0
									GROUP BY tag_essay.usr_id")->result();		
	}

	public function getEssay($essay_id,$type){
		return $this->db->query("SELECT tag_essay.*, error_essay.active AS chk
									FROM tag_essay 
									LEFT JOIN error_essay ON error_essay.essay_id = tag_essay.essay_id
									WHERE tag_essay.essay_id = '$essay_id' and type = '$type' and tag_essay.pj_active = 0 and tag_essay.active = 0")->row();	
	}

	public function draftEssay($usr_id,$essay_id,$type){		
		return $this->db->query("SELECT tag_essay.essay_id AS id, prompt, raw_txt, editing, tagging, critique, type , scoring, time, discuss,kind, error_essay.active AS chk,tag_essay.submit,tag_essay.draft
									FROM tag_essay
									LEFT JOIN error_essay ON error_essay.essay_id = tag_essay.essay_id
									WHERE tag_essay.essay_id =  '$essay_id'
									AND tag_essay.usr_id =  '$usr_id'
									AND tag_essay.TYPE =  '$type'
									AND tag_essay.draft = 1
									AND tag_essay.submit = 0
									AND tag_essay.pj_active = 0
									ANd tag_essay.active = 0")->row();	

		// return $this->db->query("SELECT essay_id as id,prompt,raw_txt,editing,tagging,critique,type,scoring,time,discuss
		// 							FROM tag_essay 									
		// 							WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type' and draft = 1 and submit = 0")->row();	
	}

	public function get_completed($essay_id,$type){		
		return $this->db->query("SELECT tag_essay.essay_id as id,prompt,raw_txt,editing,tagging,critique,type,scoring,time,discuss,kind,error_essay.active as chk,tag_essay.submit,tag_essay.draft
									FROM tag_essay 
									LEFT JOIN error_essay ON error_essay.essay_id = tag_essay.essay_id									
									WHERE tag_essay.essay_id = '$essay_id' and type = '$type'")->row();	
	}

	public function admin_done_list($usr_id,$essay_id,$type){
		
		return $this->db->query("SELECT *
									FROM tag_essay 									
									WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type' and submit = 1")->row();	
	}

	public function draft($usr_id,$essay_id,$editing,$critique,$tagging,$type,$scoring,$time){		
		$confirm = $this->db->query("SELECT * FROM tag_essay WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type'");
		if($confirm->num_rows() > 0){
			$query = $this->db->query("UPDATE tag_essay SET editing = '$editing',critique = '$critique',tagging = '$tagging',draft = 1,scoring = '$scoring',time = '$time' WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type'");
			if($query){				
				return true;					
			}else{
				return false;
			}			
		}else{
			return false;
		}		
	}

	// public function submit($usr_id,$essay_id,$editing,$critique,$tagging,$type,$scoring,$time){
	// 	$confirm = $this->db->query("SELECT * FROM tag_essay WHERE essay_id = '$essay_id' and type = 'musedata'");
		
	// 	if($confirm->num_rows() > 0){	
	// 		$row = $confirm->row();					
	// 		$pj_id = $row->pj_id;
	// 		$query = $this->db->query("UPDATE tag_essay SET editing = '$editing',critique = '$critique',tagging = '$tagging',scoring = '$scoring',draft = 1,submit = 1,sub_date = now(), time = '$time', discuss = 'Y', usr_id = '$usr_id' WHERE id = '$table_id'");
	// 		if($query){				
	// 			$count_query = $this->db->query("SELECT count(id) as count FROM tag_essay WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and submit = 1")->row();
	// 			$count = $count_query->count;
	// 			$count_update = $this->db->query("UPDATE cou SET done_count = '$count' WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = 0");
	// 			if($count_update){
	// 				return 'true';					
	// 			}else{
	// 				return 'false';
	// 			}
	// 		}else{
	// 			return 'false';
	// 		}				
	// 	}else{
	// 		return 'false';					
	// 	}			
	// }

	public function submit($usr_id,$essay_id,$editing,$critique,$tagging,$type,$scoring,$time){
		$confirm = $this->db->query("SELECT * FROM tag_essay WHERE essay_id = '$essay_id' and type = '$type' and active = 0");
		
		if($confirm->num_rows() > 0){	
			$row = $confirm->row();					
			$pj_id = $row->pj_id;
			$raw_txt = $row->raw_txt;
			$word_count = str_word_count($raw_txt);
			$query = $this->db->query("UPDATE tag_essay SET editing = '$editing',critique = '$critique',tagging = '$tagging',scoring = '$scoring',draft = 1,submit = 1,sub_date = now(), time = '$time', discuss = 'Y', usr_id = '$usr_id', word_count = '$word_count' WHERE essay_id = '$essay_id' and type = '$type'");
			if($query){				
				return true;
			}else{
				return false;
			}				
		}else{
			return false;					
		}			
	}

	public function editsubmit($usr_id,$essay_id,$editing,$critique,$tagging,$type,$scoring){					
		$confirm = $this->db->query("SELECT * FROM tag_essay WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type'");
		
		if($confirm->num_rows() > 0){
			$query = $this->db->query("UPDATE tag_essay SET editing = '$editing',critique = '$critique',tagging = '$tagging',scoring = '$scoring',sub_date = now() WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type'");
			if($query){				
				return 'true';					
			}else{
				return 'false';
			}			
		}else{ //submit 된 데이터가 없을때!
			return 'false';
		}			
	}

	public function todoList($usr_id){
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,draft,submit,tag_essay.id
					FROM tag_essay 
					LEFT JOIN usr ON usr.id = tag_essay.usr_id
					WHERE tag_essay.usr_id = '$usr_id' and tag_essay.active = 0 and tag_essay.submit = 0 and tag_essay.draft = 0 and tag_essay.essay_id != 0 ORDER BY id asc";
		return $this->db->query($query)->result();
	}

	
	public function other_todoList($usr_id,$last_num) {
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,draft,submit,tag_essay.id
					FROM tag_essay 
					LEFT JOIN usr ON usr.id = tag_essay.usr_id
					WHERE tag_essay.usr_id = '$usr_id' and tag_essay.active = 0 and tag_essay.submit = 0 and tag_essay.draft = 0 and tag_essay.essay_id != 0 and tag_essay.id >= '$last_num' ORDER BY start_date DESC LIMIT 10";
		return $this->db->query($query)->result();
	}

	public function edi_other_todoList($usr_id,$pj_id,$last_num) {
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,draft,submit,tag_essay.id,sub_date
					FROM tag_essay 
					LEFT JOIN usr ON usr.id = tag_essay.usr_id
					WHERE tag_essay.usr_id = '$usr_id' and tag_essay.active = 0 and tag_essay.pj_id = '$pj_id' and tag_essay.essay_id != 0 and tag_essay.id >= '$last_num' LIMIT 10";
		return $this->db->query($query)->result();
	}

	public function pj_todoList($pj_id,$usr_id){
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name
					FROM tag_essay 
					LEFT JOIN usr ON usr.id = tag_essay.usr_id
					WHERE tag_essay.usr_id = '$usr_id' and tag_essay.pj_id = '$pj_id' and tag_essay.active = 0 and tag_essay.submit = 0 and tag_essay.draft = 0 and tag_essay.essay_id != 0";
		return $this->db->query($query)->result();
	}

	public function eid_pj_todoList($usr_id,$pj_id){
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,tag_essay.id,draft,submit
					FROM tag_essay 
					LEFT JOIN usr ON usr.id = tag_essay.usr_id
					WHERE tag_essay.usr_id = '$usr_id' and tag_essay.pj_id = '$pj_id' and tag_essay.active = 0 and tag_essay.submit = 0 and tag_essay.essay_id != 0";
		return $this->db->query($query)->result();
	}

	public function eid_pj_doneList($usr_id,$pj_id){
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,tag_essay.id,draft,submit,sub_date
					FROM tag_essay 
					LEFT JOIN usr ON usr.id = tag_essay.usr_id
					WHERE tag_essay.usr_id = '$usr_id' and tag_essay.pj_id = '$pj_id' and tag_essay.active = 0 and tag_essay.submit = 1 and tag_essay.essay_id != 0";
		return $this->db->query($query)->result();
	}	

	public function edi_other_doneList($usr_id,$pj_id,$last_num) {
		$query = "SELECT essay_id,prompt,raw_txt,editing,tagging,critique,type,kind,usr.id as usr_id,usr.name,start_date,draft,submit,tag_essay.id,sub_date
					FROM tag_essay 
					LEFT JOIN usr ON usr.id = tag_essay.usr_id
					WHERE tag_essay.usr_id = '$usr_id' and tag_essay.active = 0 and tag_essay.submit = 1 and tag_essay.pj_id = '$pj_id' and tag_essay.essay_id != 0 and tag_essay.id >= '$last_num' LIMIT 10";
		return $this->db->query($query)->result();
	}

	public function local_save($usr_id,$w_id,$raw_writing,$editing,$tagging,$critique,$title,$kind,$scoring,$time,$type){				    
		return $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,prompt,raw_txt,editing,tagging,critique,draft,submit,kind,type,sub_date,time,scoring) VALUES('$usr_id','$w_id',$title,$raw_writing,$editing,$tagging,$critique,1,1,$kind,'$type',now(),'$time','$scoring')");
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

   		return $this->db->query("INSERT INTO essay(prompt,essay,date,type,kind,pj_id) VALUES('$title','$sentence',now(),'musedata','$kind','$pj_id')");
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
   			$select = $this->db->query("SELECT * FROM essay WHERE id = '$senid'");

   			if($select->num_rows() > 0){

   				$select = $select->row();
   				$essay_id = $select->id;
   				$prompt = trim(mysql_real_escape_string($select->prompt));
   				$sen = trim(mysql_real_escape_string($select->essay));
   				$type = $select->type; 
   				$kind = $select->kind; 				

   				$ins = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,prompt,raw_txt,kind,type,start_date,pj_id) VALUES('$mem_id','$essay_id','$prompt','$sen','$kind','$type',now(),'$pj_id')");
   				$sen_up = $this->db->query("UPDATE essay SET chk = 'Y' WHERE id = '$essay_id'");
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

   	public function chart_num(){
   		$data = array();
   		$todo = $this->db->query("SELECT count(essay_id) as todo FROM tag_essay WHERE essay_id != 0 and draft = 0 and submit = 0 and active = 0")->row();
   		$todo = $todo->todo;
   		array_push($data, $todo);

   		$draft = $this->db->query("SELECT count(draft) as draft FROM tag_essay WHERE essay_id != 0 and draft = 1 and submit = 0 and active = 0")->row();
   		$draft = $draft->draft;
   		array_push($data, $draft);

   		$done = $this->db->query("SELECT count(submit) as submit FROM tag_essay WHERE essay_id != 0 and draft = 1 and submit = 1 and active = 0")->row();
   		$done = $done->submit;
   		array_push($data, $done);

   		$total = $this->db->query("SELECT count(essay_id) as total FROM tag_essay WHERE essay_id != 0 and active = 0")->row();
   		$total = $total->total;
   		array_push($data, $total);

   		return $data;
   	}

   	public function editor_chart_num($id){
   		$data = array();
   		$todo = $this->db->query("SELECT count(essay_id) as todo FROM tag_essay WHERE essay_id != 0 and usr_id = '$id' and draft = 0 and submit = 0 and active = 0")->row();
   		$todo = $todo->todo;
   		array_push($data, $todo);

   		$draft = $this->db->query("SELECT count(draft) as draft FROM tag_essay WHERE essay_id != 0 and usr_id = '$id' and draft = 1 and submit = 0 and active = 0")->row();
   		$draft = $draft->draft;
   		array_push($data, $draft);

   		$done = $this->db->query("SELECT count(submit) as submit FROM tag_essay WHERE essay_id != 0 and usr_id = '$id' and draft = 1 and submit = 1 and active = 0")->row();
   		$done = $done->submit;
   		array_push($data, $done);

   		$total = $this->db->query("SELECT count(essay_id) as total FROM tag_essay WHERE essay_id != 0 and usr_id = '$id' and active = 0")->row();
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

   	public function all_usr(){
   		return $this->db->query("SELECT * FROM usr WHERE classify = 1 and conf = 0 and active = 0")->result();
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
   		return $this->db->query("SELECT project. * , project.id AS pj_id, project.active AS tbd, COUNT(if(tag_essay.essay_id != 0,1,null)) AS total_count
									FROM project
									LEFT JOIN tag_essay ON tag_essay.pj_id = project.id
									WHERE tag_essay.active =0									
									GROUP BY tag_essay.pj_id
									ORDER BY add_date DESC")->result();
   	}

   	public function create_pj($name,$disc,$mem_list){
   		$query = $this->db->query("INSERT INTO project(name,disc,add_date) VALUES($name,$disc,now())");
   		$pj_id = $this->db->insert_id();   				 

   		if($query){   			
   			
   			$match = preg_match('/,/', $mem_list); // ,으로 멤버가 한명인지 몇명인지 검사한다!
   			
   			if($match == 1){ // 멤버가 1명 이상일때!
   				$members = explode(',', $mem_list);
	   		
		   		foreach ($members as $mem) {
		   			$cou_ins = $this->db->query("INSERT INTO cou(usr_id,pj_id) VALUES('$mem','$pj_id')");
		   			if($cou_ins){
		   				$this->db->query("INSERT INTO tag_essay(usr_id,pj_id,type) VALUES('$mem','$pj_id','project')");
		   			}else{
		   				return false;   						
		   			}
		   		}	
		   		return true;   			

   			}else{ // 멤버가 1명일때!

   				$cou_ins = $this->db->query("INSERT INTO cou(usr_id,pj_id) VALUES('$mem_list','$pj_id')");
   				if($cou_ins){

		   			$tag_essay_ins = $this->db->query("INSERT INTO tag_essay(usr_id,pj_id,type) VALUES('$mem_list','$pj_id','project')");
		   			
		   			if($tag_essay_ins){
		   				return true;   						
		   			}else{
		   				return false;   						
		   			}   					
   				}
   			}	   		
   		}else{
   			return false;
   		}
   	}   	

 //   	public function pj_status_list($id){ // pj_id
	// 	return $this->db->query("SELECT usr.id as usr_id, usr.name,count(DISTINCT tag_essay.id) as count, cou.update_count, cou.done_count, project.name as pj_name, SUM( tag_essay.discuss =  'N' ) AS tbd
	// 								FROM tag_essay
	// 								LEFT JOIN usr ON usr.id = tag_essay.usr_id
	// 								LEFT JOIN cou ON cou.usr_id = tag_essay.usr_id
	// 								LEFT JOIN project ON project.id = tag_essay.pj_id
	// 								WHERE cou.pj_id = '$id' and tag_essay.pj_id = '$id' and tag_essay.active = 0 and tag_essay.pj_active = 0 and usr.classify = 1 and usr.conf = 0 GROUP BY tag_essay.usr_id")->result();
	// }

	public function del_project($pj_id){
		$project_table = $this->db->query("UPDATE project SET active = 1 WHERE id = '$pj_id'");
		if($project_table){
			$confirm = $this->db->query("SELECT pj_id FROM essay WHERE pj_id = '$pj_id'");
			if($confirm->num_rows() > 0){
				$essay_table = $this->db->query("UPDATE essay SET chk = 'Y' WHERE pj_id = '$pj_id'");
				if($essay_table){
					$tag_essay_table_del = $this->db->query("UPDATE tag_essay SET active = 1,pj_active = 1 WHERE pj_id = '$pj_id'");					
					if($tag_essay_table_del){
						$cou_del = $this->db->query("UPDATE cou SET active = 1 WHERE pj_id = '$pj_id'");					
						if($cou_del){
							return true;	
						}else{
							return 4;	
						}						
					}else{
						return 5;
					}

				}else{
					return 2;
				}				
			}else{
				$tag_essay_table = $this->db->query("UPDATE tag_essay SET active = 1,pj_active = 1 WHERE pj_id = '$pj_id'");
				if($tag_essay_table){
					$cou_del = $this->db->query("UPDATE cou SET active = 1 WHERE pj_id = '$pj_id'");					
					if($cou_del){
						return true;	
					}else{
						return 6;	
					}						
				}else{
					return 3;
				}
			}			
		}else{
			return 1;
		}
	}

	public function add_userslist($id){		
		return $not_in_user = $this->db->query("SELECT id,name FROM usr WHERE id NOT IN (SELECT usr_id FROM tag_essay where pj_id = '$id' and pj_active = 0) and id != 0 and classify = 1 and conf = 0")->result();
	}

	public function share_userslist($id,$pj_id){		
		return $not_in_user = $this->db->query("SELECT id,name FROM usr WHERE id IN (SELECT distinct(usr_id) FROM tag_essay where usr_id != '$id' and usr_id !=0 and pj_id = '$pj_id' and pj_active = 0) and id != 0 and classify = 1 and conf = 0")->result();
		//SELECT id, name FROM usr WHERE id IN (SELECT DISTINCT (usr_id) FROM tag_essay WHERE usr_id !=  '5' AND usr_id !=0 AND pj_id =  '4' AND pj_active = 0) AND id !=0 AND classify =1 AND conf =0
	}

	

	public function del_user($pj_id,$usr_id){
		$get_user = $this->db->query("SELECT * FROM tag_essay WHERE pj_id = '$pj_id' and usr_id = '$usr_id' and active = 0");
		foreach ($get_user->result() as $rows) {
			$submit = $rows->submit;
			$id = $rows->id;  // tag_essay 이의 고유 아이디번호!

			if($submit != 1){ // submit을 하지 않는것은 모두 지운다!
				$data_del = $this->db->query("UPDATE tag_essay SET pj_active = 1, active = 1 WHERE id = '$id'");
				if(!$data_del){
					return false;
				}
			}elseif($submit == 1){
				$pj_del = $this->db->query("UPDATE tag_essay SET pj_active = 1 WHERE id = '$id'");
				if(!$pj_del){
					return false;
				}
			}
		}
		return true;		
	}

	public function all_todo(){
		return $this->db->query("SELECT * FROM tag_essay WHERE active = 0 and essay_id != 0 and draft = 0 and submit = 0")->result();

	}

	public function all_done(){
		return $this->db->query("SELECT * FROM tag_essay WHERE active = 0 and essay_id != 0 and draft = 1 and submit = 1")->result();
	}

	public function alldone_essay($id){		
		return $this->db->query("SELECT essay_id as id,prompt,raw_txt,editing,tagging,critique,type,scoring
									FROM tag_essay 									
									WHERE id = '$id'")->row();	
	}

	public function all_history(){
		$query = "SELECT * FROM tag_essay WHERE active = 0 and essay_id != 0";
		return $this->db->query($query)->result();
	}

	

	

	public function pj_history_totalcount($pj_id,$usr_id){
		return $this->db->query("SELECT count(*) as count FROM tag_essay WHERE pj_id = '$pj_id' and usr_id = '$usr_id' and essay_id != 0 and pj_active = 0 and active = 0 and discuss = 'Y'")->row();
	}

	public function pj_todo_totalcount($pj_id,$usr_id) {
		return $this->db->query("SELECT count(*) as count FROM tag_essay WHERE pj_id = '$pj_id' and usr_id = '$usr_id' and essay_id != 0 and submit != 1 and pj_active = 0 and active = 0 and discuss = 'Y'")->row();
	}

	public function pj_share_totalcount($pj_id,$usr_id) {
		return $this->db->query("SELECT count(*) as count FROM tag_essay WHERE pj_id = '$pj_id' and usr_id = '$usr_id' and essay_id != 0 and submit != 1 and pj_active = 0 and active = 0 and discuss = 'Y'")->row();
	}

	public function pj_comp_totalcount($pj_id,$usr_id) {
		return $this->db->query("SELECT count(*) as count FROM tag_essay WHERE pj_id = '$pj_id' and usr_id = '$usr_id' and essay_id != 0 and draft = 1 and submit = 1 and pj_active = 0 and active = 0 and discuss = 'Y'")->row();
	}

	public function history_totalcount($usr_id){
		return $this->db->query("SELECT count(*) as count FROM tag_essay WHERE usr_id = '$usr_id' and essay_id != 0 and pj_active = 0 and active = 0")->row();
	}

	public function todo_totalcount($usr_id) {
		return $this->db->query("SELECT count(*) as count FROM tag_essay WHERE usr_id = '$usr_id' and essay_id != 0 and submit != 1 and pj_active = 0 and active = 0")->row();
	}

	public function comp_totalcount($usr_id) {
		return $this->db->query("SELECT count(*) as count FROM tag_essay WHERE usr_id = '$usr_id' and essay_id != 0 and draft = 1 and submit = 1 and pj_active = 0 and active = 0")->row();
	}

	public function edi_todo_totalcount($usr_id) {
		return $this->db->query("SELECT count(*) as count FROM tag_essay WHERE usr_id = '$usr_id' and essay_id != 0 and submit != 1 and pj_active = 0 and active = 0 and discuss = 'Y'")->row();
	}

   	function get_muse_detecting_count($mem_id,$sentence_num,$pj_id){
   		$sentences = explode(',', $sentence_num);  		
   		
   		foreach ($sentences as $senid) {
   			$select = $this->db->query("SELECT * FROM essay WHERE id = '$senid'");

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

   	public function editor_pj_list_count($pj_id,$usr_id){
   		return $this->db->query("SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and pj_id = '$pj_id' and active = '0' and pj_active = '0' and essay_id != 0")->result();
   	}

   	public function list_count($usr_id){
   		return $this->db->query("SELECT * FROM tag_essay WHERE usr_id = '$usr_id' and active = '0' and pj_active = '0' and submit = 0 and essay_id != 0")->result();
   	}

   	public function share($editor_id,$pj_id,$select_mem,$share_data){
		$match = preg_match('/,/', $share_data); // ,으로 데이터가 몇개인지 검사한다!
   			
		if($match == 1){ // 데이터가 1개 이상일때!
			$datas = explode(',', $share_data);

   			foreach ($datas as $data) {
   				$query = $this->db->query("SELECT * FROM tag_essay WHERE usr_id = '$editor_id' and pj_id = '$pj_id' and essay_id = '$data' and pj_active = 0 and active = 0");
   				if($query->num_rows() > 0){
   					$this->db->query("UPDATE tag_essay SET usr_id = '$select_mem' WHERE usr_id = '$editor_id' and pj_id = '$pj_id' and essay_id = '$data' and pj_active = 0 and active = 0");   					
   				}else{
   					return false;
   				}
   			}
   			return true;
		}else{ // 데이터가 1개 일때!
			$query = $this->db->query("SELECT * FROM tag_essay WHERE usr_id = '$editor_id' and pj_id = '$pj_id' and essay_id = '$share_data' and pj_active = 0 and active = 0");
			if($query->num_rows() > 0){
				$update = $this->db->query("UPDATE tag_essay SET usr_id = '$select_mem' WHERE usr_id = '$editor_id' and pj_id = '$pj_id' and essay_id = '$share_data' and pj_active = 0 and active = 0");
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

   	public function error_proc($essay_id,$usr_id,$type){   		
   		$query = $this->db->query("SELECT * FROM tag_essay WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type' and pj_active = 0 and active = 0");
   		
   		if($query->num_rows() > 0){
   			$rows = $query->row(); 
   			$pj_id = $rows->pj_id;
   			//return $pj_id;
   			$result = $this->db->query("INSERT INTO error_essay(usr_id,essay_id,pj_id,date) VALUES('$usr_id','$essay_id','$pj_id',now())");
   			if($result){
   				$this->db->query("UPDATE tag_essay SET pj_active = 1, active = 1, sub_date = now(), discuss = 'Y' WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type'");
   				return $result;
   			}else{
   				return $result;
   			}
   		}else{
   			return false;   			
   		}
   	}

   	public function error_count($id){
   		return $this->db->query("SELECT count(id) as count FROM error_essay WHERE pj_id = '$id' and active = 0")->row();   		
   	}

   	public function errorlist($pj_id,$limit,$list){
		return $this->db->query("SELECT tag_essay.*,usr.name as usr_name, usr.id as editor_id,project.name 
									FROM error_essay 
									LEFT JOIN tag_essay ON tag_essay.essay_id = error_essay.essay_id
									LEFT JOIN usr ON usr.id = error_essay.usr_id
									LEFT JOIN project ON project.id = error_essay.pj_id
									WHERE error_essay.pj_id = '$pj_id' and error_essay.active = 0 LIMIT $limit,$list")->result();
   	}

   	public function discuss_count($pj_id,$editor_id){
   		return $this->db->query("SELECT count(id) as count FROM tag_essay WHERE pj_id = '$pj_id' and usr_id = '$editor_id' and essay_id != 0 and discuss = 'N' and pj_active = 0 and active = 0")->row();

   	}

   	public function discuss_list($pj_id,$editor_id,$limit,$list){
   		$query = "SELECT * FROM tag_essay WHERE usr_id = '$editor_id' and pj_id = '$pj_id' and discuss = 'N' and essay_id != 0 and pj_active = 0 and active = 0 LIMIT $limit,$list";
		return $this->db->query($query)->result();
   	}

   	public function error_yes($essay_id){
   		return $this->db->query("UPDATE error_essay SET active = 1 WHERE essay_id = '$essay_id'");
   	}

   	public function error_return($essay_id){
   		$update = $this->db->query("UPDATE error_essay SET active = 1 WHERE essay_id = '$essay_id'");
   		if($update){
   			$result = $this->db->query("UPDATE tag_essay SET active = 0, pj_active = 0 WHERE essay_id = '$essay_id' and type = 'musedata'");
   			return $result;
   		}
   	}  	

   	public function discuss_proc($essay_id,$usr_id,$type){
   		$query = $this->db->query("SELECT * FROM tag_essay WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type' and pj_active = 0 and active = 0 and discuss = 'Y'");
   		
   		if($query->num_rows() > 0){   			
			$update = $this->db->query("UPDATE tag_essay SET discuss = 'N' WHERE essay_id = '$essay_id' and usr_id = '$usr_id' and type = '$type'");
			return $update;				
   			
   		}else{
   			return false;   			
   		}
   	}   	

   	public function exportmembers($pj_id){
   		return $this->db->query("SELECT usr.id as usr_id,usr.name
   									FROM usr LEFT JOIN tag_essay ON tag_essay.usr_id = usr.id LEFT JOIN project ON project.id = tag_essay.pj_id
   									WHERE tag_essay.pj_id = '$pj_id' and pj_active = 0 and tag_essay.active = 0 and usr.active = 0 and usr.conf = 0 GROUP BY tag_essay.usr_id")->result();   	
   	}

   	

   	public function sorting_export_count($pj_id,$editor_id){
   		return $this->db->query("SELECT count(id) as count FROM tag_essay WHERE pj_id = '$pj_id' and usr_id in($editor_id) and essay_id != 0 and discuss = 'Y' and pj_active = 0 and active = 0 and submit = 1")->row();
   	}

   	public function sorting_export_allessay_id($pj_id,$editor_id){
   		return $this->db->query("SELECT essay_id FROM tag_essay WHERE pj_id = '$pj_id' and usr_id in($editor_id) and essay_id != 0 and discuss = 'Y' and pj_active = 0 and active = 0 and submit = 1")->result();
   	}

   	public function sorting_export_list($pj_id,$editor_id,$limit,$list){
   		$query = "SELECT tag_essay.*,usr.name
					FROM tag_essay
					left join usr on usr.id = tag_essay.usr_id
					WHERE pj_id = '$pj_id'
					AND usr_id in($editor_id)
					AND discuss = 'Y'
					AND essay_id != 0
					AND pj_active = 0
					AND tag_essay.active = 0
					AND submit = 1
					LIMIT $limit,$list";
		return $this->db->query($query)->result();
   	}

   	public function memchkExportget_essay($essay_id){
   		return $this->db->query("SELECT * FROM tag_essay WHERE essay_id in($essay_id) and type = 'musedata'")->result();
   	}

   	public function ex_editing_update($id,$data){
   		$result = $this->db->query("UPDATE tag_essay SET ex_editing = '$data' WHERE essay_id = '$id' and type = 'musedata'");
   		return $result;      
   	}

   	

   	public function all_essayid($pj_id) {
   		return $this->db->query("SELECT * FROM tag_essay WHERE pj_id = '$pj_id' and submit = 1 and pj_active = 0 and active = 0 and discuss = 'Y'")->result();
   	}

   	// public function export_error_count($editor_id){				
   	// 	return $this->db->query("SELECT tag_essay.*,usr.name
				// 	FROM tag_essay
				// 	left join usr on usr.id = tag_essay.usr_id
				// 	WHERE essay_id in($editor_id)
				// 	AND tag_essay.pj_active = 0 
				// 	AND tag_essay.active = 0")->result();
   	// }

   	

   	public function get_export_error_data($pj_id,$limit,$list){
   		$query = "SELECT tag_essay.*,usr.name
					FROM tag_essay
					left join usr on usr.id = tag_essay.usr_id
					WHERE tag_essay.pj_id = '$pj_id'										
					AND essay_id != 0					
					AND tag_essay.active = 0
					AND submit = 1 
					AND ex_editing = ''										
					ORDER BY sub_date asc
					LIMIT $limit,$list";
		return $this->db->query($query)->result();		
   	}   

   	public function export_error_count($pj_id){
   		return $this->db->query("SELECT count(id) as count FROM tag_essay WHERE pj_id = '$pj_id' and essay_id != 0 and active = 0 and submit = 1 and ex_editing = ''")->row();
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

   			$ins = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,pj_id,prompt,raw_txt,editing,ex_editing,tagging,critique,scoring,word_count,org_tag,replace_tag,discuss,draft,submit,time,kind,type,pj_active,active,start_date,sub_date) 
   									VALUES('$usr_id','$essay_id','$pj_id','$prompt','$raw_txt','$editing','$ex_editing','$tagging','$critique','$scoring','$word_count','$org_tag','$replace_tag','$discuss','$draft','$submit','$time','$kind','$type','$pj_active','$active','$start_date',now())");


   		}
   		return true;
   	}
}
?>