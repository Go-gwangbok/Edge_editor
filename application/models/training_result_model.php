<?php
class Training_result_model extends CI_Model
{       
    public $id;
    public $usr_id;
    public $tr_id;
    public $score;
    public $certificated;
    public $created;

    public function getAll()
    {
        $query = $this->db->get('training_result');
        return $query->result();
    }

    public function getById($id) 
    {
        $query = $this->db->get_where('training_result', array('id' => $id));
        return $query->row();
    }

    public function getTrainingById($id) 
    {
        $query = $this->db->get_where('training', array('id' => $id));
        return $query->row();
    }


    public function get_training_result_id($usr_id, $tr_id)
    {
        $this->db->select('id');
        $this->db->from('training_result');
        $this->db->where('usr_id', $usr_id);
        $this->db->where('tr_id', $tr_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->id;
        } else {
            return 0;
        }
    }

    public function getTrainingUser() 
    {
        $sql = "SELECT distinct training_result.usr_id, usr.name
            FROM `training_result` 
            LEFT JOIN usr ON usr.id = training_result.usr_id
            ";

        return $this->db->query($sql)->result();
    }

    public function getTraingResultData($usr_id) 
    {
        $sql = "SELECT training.id, name, usr_id, score, certificated, created
            FROM `training` 
            LEFT JOIN training_result ON training.id = training_result.tr_id
            WHERE usr_id = $usr_id
            ORDER BY training.id asc ";

        return $this->db->query($sql)->result();
    }

    public function start_training($usr_id, $tr_id) 
    {
        $this->db->select('id');
        $this->db->from('training_result');
        $this->db->where('usr_id', $usr_id);
        $this->db->where('tr_id', $tr_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            //echo "already exists";
            return true;
        }
        $this->usr_id = $usr_id;
        $this->tr_id = $tr_id;
        $this->created = date("Y-m-d H:i:s");
        return $this->db->insert('training_result', $this);
    }

    public function insert() 
    {
        return $this->db->insert('training_result', $this);
    }

    public function update() 
    {
        $this->db->set('score', $this->score);
        $this->db->set('certificated', $this->certificated);
        $this->db->set('created', date("Y-m-d H:i:s") );
        $this->db->where('id', $this->id);
        return $this->db->update('training_result');
    }

    public function delete() 
    {
        $this->db->where('id', $this->id);
        return $this->db->delete('training_result');
    }

    public function save() 
    {
        if (isset($this->id)) {  
            return $this->update();
        } else {
            return $this->insert();
        }
    }
}
?>