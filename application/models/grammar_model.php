<?php
class Grammar_model extends CI_Model
{       
    public $id;
    public $sentence;
    public $json;
    //public $comment;
    //public $condition;
    //public $modified_date;
    //public $user_id;
    public $sentence_count;
    public $word_count;
    public $error_count;
    public $proc_time;
    public $created;

    public function getAll()
    {
        $query = $this->db->get('grammar_data');
        return $query->result();
    }

    public function getById($id) 
    {
        $query = $this->db->get_where('grammar_data', array('id' => $id));
        return $query->row();
    }

    public function getMaxId() 
    {
        $this->db->select_max('id', 'max_id');
        $query = $this->db->get('grammar_data');
        return $query->row();
    }

    public function getDataStat($gubun) 
    {
        switch ($gubun) {
            case 'daily':  $d_format = '%Y-%m-%d'; break;
            case 'monthly':  $d_format = '%Y-%m'; break;
            case 'yearly':  $d_format = '%Y'; break;
            default : $d_format = '%Y-%m';
        }
        
        $query = "SELECT date_format(created, '$d_format') AS regdate, 
            count(*) AS essay_count, sum(sentence_count) AS s_count 
            FROM `grammar_data` 
            GROUP BY regdate 
            ORDER BY regdate asc ";

        $source_data = $this->db->query($query);

        $stat_list = array();

        foreach ( $source_data->result() as $row) {
            $stat_info = array();
            $stat_info['date'] = $row->regdate;
            $stat_info['essay_count'] = $row->essay_count;
            $stat_info['sentence_count'] = $row->s_count;

            $stat_list[] = $stat_info;
        }

        return $stat_list;

    }

    public function insert() 
    {
         if (!isset($sentence_count)) {
            $sentences = explode(".", $this->sentence);
            $this->sentence_count = 0;
            foreach($sentences as $sentence) {
                 if (strlen(trim($sentence)) > 2) {
                    $this->sentence_count++;
                }
            }
        }
        return $this->db->insert('grammar_data', $this);
    }

    private function update() 
    {
        $this->db->set('title', $this->title);
        $this->db->set('content', $this->content);
        $this->db->where('id', $this->id);
        return $this->db->update('post');
    }

    public function delete() 
    {
        $this->db->where('id', $this->id);
        return $this->db->delete('post');
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