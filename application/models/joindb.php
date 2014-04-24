<?
class JoinDb extends CI_Model{
	
	function sign_in($email,$pass){
		$query = "SELECT * FROM usr WHERE email = '$email' and pass = '$pass'";				
		return $this->db->query($query)->row();	
	}

	function get_cate($usr_id){
   		return $this->db->query("SELECT task.* 
   									FROM connect_usr_task
   									LEFT JOIN task ON task.id = connect_usr_task.task_id
   									LEFT JOIN usr ON usr.id = connect_usr_task.usr_id
   									WHERE connect_usr_task.usr_id = '$usr_id'
   									AND connect_usr_task.active = 0
   									GROUP BY task.type")->result();

   	}

	function sign_up($name,$email,$pass){		
		$conf = $this->db->query("SELECT * FROM usr WHERE email = '$email'");
		if($conf->num_rows() > 0){
			return 'exist';
		}else{
			$query = $this->db->query("INSERT INTO usr(name,email,pass,classify,conf,date) VALUES('$name','$email','$pass','1','1',now())");
				
			if($query){
				return 'true';										
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
}
?>