<?php
class Training_result_detail_model extends CI_Model
{       
    public $id;
    public $usr_id;
    public $tr_id;
    public $seq;
    public $trdata_id;
    public $muse_score;
    public $score;
    public $created;

    public function getAll()
    {
        $query = $this->db->get('training_result_detail');
        return $query->result();
    }

    public function getById($id) 
    {
        $query = $this->db->get_where('training_result_detail', array('id' => $id));
        return $query->row();
    }

    public function start_training($usr_id, $tr_id) 
    {
        $this->db->select('id');
        $this->db->from('training_result_detail');
        $this->db->where('usr_id', $usr_id);
        $this->db->where('tr_id', $tr_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            //echo "already exists";
            return true;
        }

        $training_data = $this->db->order_by('id', 'asc')->get_where('training_data', array('tr_id' => $tr_id));
        if($training_data->num_rows() == 0) {
            return false;
        }

        $this->usr_id = $usr_id;
        $this->tr_id = $tr_id;
        $this->seq = 1;
        foreach ( $training_data->result() as $row) {
            $this->trdata_id = $row->id;
            $this->created = date("Y-m-d H:i:s");

            if ( !$this->insert() ) {
                return false;
            }
            $this->seq++;
        }

        return true;
    }

    public function get_training_data($usr_id, $tr_id, $seq = 0) 
    {
        if ($seq == 0) {
             $sql = "SELECT a.*, b.prompt, b.row_txt, b.score as org_score
                FROM training_result_detail a 
                LEFT JOIN training_data b ON b.id = a.trdata_id
                WHERE a.usr_id = $usr_id AND a.tr_id = $tr_id
                ORDER BY a.seq ASC";     
        } else {
             $sql = "SELECT a.*, b.prompt, b.row_txt, b.score as org_score, b.muse_score as org_muse_score,
                b.editing, b.tagging
                FROM training_result_detail a 
                LEFT JOIN training_data b ON b.id = a.trdata_id
                WHERE a.usr_id = $usr_id AND a.tr_id = $tr_id AND a.seq = $seq";           
        }
        //echo $sql;

        return $this->db->query($sql)->result();
    }

    public function insert() 
    {
        return $this->db->insert('training_result_detail', $this);
    }

    public function update() 
    {
        $this->db->set('muse_score', $this->muse_score);
        $this->db->set('score', $this->score);
        $this->db->set('created', date("Y-m-d H:i:s") );
        $this->db->where('id', $this->id);
        return $this->db->update('training_result_detail');
    }

    public function delete() 
    {
        $this->db->where('id', $this->id);
        return $this->db->delete('training_result_detail');
    }

    public function save() 
    {
        if (isset($this->id)) {  
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    private function get_first_trdata_id($usr_id, $tr_id) {
        $this->db->select_min('trdata_id');
        $this->db->from('training_result_detail');
        $this->db->where('usr_id', $usr_id);
        $this->db->where('tr_id', $tr_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->trdata_id;
        } else {
            return 0;
        }
    }
}
?>