<?
class JoinDb extends CI_Model{
	
	function sign_in($email,$pass){
		$query = "SELECT * FROM usr WHERE email = '$email' and pass = '$pass'";				
		return $this->db->query($query)->row();	
	}

	function sign_up($name,$email,$pass){		
		$conf = $this->db->query("SELECT * FROM usr WHERE email = '$email'");
		if($conf->num_rows() > 0){
			return 'exist';
		}else{
			$query = $this->db->query("INSERT INTO usr(name,email,pass,classify,conf,date) VALUES('$name','$email','$pass','1','1',now())");
				
			if($query){
				return 'true';			
				// $usr_insert_id = $this->db->insert_id();	

				// $tag_essay_query = $this->db->query("INSERT INTO tag_essay(usr_id,essay_id,draft,submit,type,active,sub_date) VALUES('$usr_insert_id',0,0,0,'join',0,now())");
				
				// if($tag_essay_query){
				// 	//return 'true';
				// 	$ins_count = $this->db->query("INSERT INTO cou(usr_id,update_count) VALUES('$usr_insert_id',0)");			
					
				// 	if($ins_count){
				// 		return 'true';			
				// 	}else{
				// 		return 'false';	
				// 	}							
				// }else{
				// 	return 'false';
				// }			
			}else{
				return 'false';	
			}
		}
	}


	function sign_up_ins($name,$email,$pass,$ins,$code){
		// code 추가 컬럼을 만들고 inser해줘야 한다!
		$query = "INSERT INTO usr(name,email,pass,is_ins) VALUES('$name','$email','$pass','$ins')";
		$this->db->query($query);		
	}
				

	// function id_check($cls_name,$ins_id){
	// 	$query = "SELECT cls_id,cls.name FROM ins_doc 
	// 				JOIN cls ON ins_doc.cls_id = cls.id
	// 				WHERE ins_id = '$ins_id' AND cls.name = '$cls_name'";
	// 	return $this->db->query($query)->result();
	// }
}
?>