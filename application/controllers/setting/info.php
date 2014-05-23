<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('all_list');                             
        $this->load->helper('url'); 
    }

    function index(){        
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'setting';
            $this->load->view('head',$data);

            $data['cateType'] = $this->all_list->cateType();

            $this->load->view('/setting_view/info_view',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }   
    }

    function getType_data_kind(){
        if($this->session->userdata('is_login')){            
            $task = $this->input->post('task');
            $from_table = $this->input->post('from_table');
            $json['data_kind'] = $this->all_list->getDataKind($task,$from_table);
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function getDataKind(){
        if($this->session->userdata('is_login')){            
            $cate_id = $this->input->post('cate_id');
            
            $json['result'] = $this->all_list->getDataKind($cate_id);    
            
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function setting_data(){
        if($this->session->userdata('is_login')){
            $json['get_editors'] = $this->all_list->get_Editors();
            $json['data_kind'] = $this->all_list->data_kind();
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function templet($type_id,$data_kind_id){
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'setting';
            $this->load->view('head',$data);           

            $data['kind_id'] = $data_kind_id;
            $data['type_id'] = $type_id;
            $this->load->view('/setting_view/templet',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }  
    }

    function tagScoreSet($kind_id){
        if($this->session->userdata('is_login')){
            $data['cate'] = 'setting';
            $this->load->view('head',$data);           

            $data['add_tag_data'] = $this->all_list->add_tag_data($kind_id);
            $data['add_score_data'] = $this->all_list->add_score_data($kind_id);
            
            $data['kind_id'] = $kind_id;           

            $this->load->view('/setting_view/rubric_view',$data);     
            $this->load->view('footer');
        }else{
            redirect('/');
        }
        

    }

    function accept(){
        if($this->session->userdata('is_login')){
            $usr_id = $this->input->post('usr_id');
            $json['result'] = $this->all_list->new_editor_accept($usr_id);        
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));   
    }

    function decline(){
        if($this->session->userdata('is_login')){
            $id = $this->input->post('usr_id');

            $json['result'] = $this->all_list->new_editor_decline($id);      
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));           
    }

    function accept_data(){
        $usr_id = $this->input->post('usr_id');
        $musedata = $this->input->post('musedata');
        $writing = $this->input->post('writing');
        $part_time = $this->input->post('part_time');
        $case = $this->input->post('case');
        $start = $this->input->post('start');
        $end = $this->input->post('end');
        $pay = $this->input->post('pay');        

        if($musedata == 'musedata'){
            $musedata = 1;
        }
        if ($writing == 'writing') {
            $writing = 1;
        }

        if($part_time == 'part_time') {
            $type = 'partTime';
        }

        if($case == 'case'){
            $type = 'case';
        }

        $result = $this->all_list->accept_ok($usr_id,$musedata,$writing,$type,$pay,$start,$end);

        if($result){
            redirect('/setting_view/info');
        }else{
            echo '<script> alert("DB false");</script>';
        }     
    }

    function member_edit($usr_id){
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'setting';
            $this->load->view('head',$data);

            $data['task_list'] = $this->all_list->get_task_list();
            $data['usr_id'] = $usr_id;
            $this->load->view('/setting_view/member_edit',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }  
    }

    function get_member_edit_data(){
        if($this->session->userdata('is_login')){                              
            $usr_id = $this->input->post('usr_id');
            $json['task'] = $this->all_list->active_task($usr_id);
            $json['result'] = $this->all_list->get_user($usr_id);            
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));           
    }

    function member_edit_save(){
        if($this->session->userdata('is_login')){                              
            $usr_id = $this->input->post('usr_id');
            $task_ids = $this->input->post('task_ids');            
            $type = $this->input->post('type');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $pay = $this->input->post('pay');            
            $editor_desc = $this->db->escape($this->input->post('editor_desc'));
            $email = $this->db->escape($this->input->post('email'));

            $result = $this->all_list->member_edit_save($usr_id,$task_ids,$type,$start,$end,$pay,$editor_desc,$email);

            if($result){
                $this->load->library('curl');        
                $curl_result = $this->curl->simple_post('http://54.248.103.31/editor/editordesc', array('email'=>$email,'desc'=>$editor_desc));
                
                $access_status = json_decode($curl_result, true);       
                $json['result'] = $access_status['status'];                
            }else{
                $json['result'] = false;
            }
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));           
    }

    function get_rubric_val(){
        if($this->session->userdata('is_login')){  
            $kind_id = $this->input->post('kind_id');           

            $get_kind_name = $this->all_list->get_kind_name($kind_id);
            $json['kind_name'] = $get_kind_name->kind;                        
            $json['get_setup_tag'] = $this->all_list->get_setup_tag($kind_id);            
            $json['scores'] = $this->all_list->get_setup_scores($kind_id);
            
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }    

    function get_typevals(){
        if($this->session->userdata('is_login')){  
            $kind_id = $this->input->post('kind_id');
            $type_id = $this->input->post('type_id');

            $get_kind_name = $this->all_list->getTask_kind_name($type_id,$kind_id);
            $json['kind_name'] = $get_kind_name->kind_name;
            $json['task_name'] = $get_kind_name->task_name;

            $json['tabs'] = $this->all_list->get_setup_tabs($type_id,$kind_id);
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function saveSetting(){
        if($this->session->userdata('is_login')){    
            $type_id = $this->input->post('type_id');      
            $kind_id = $this->input->post('kind_id');
            $tabs_val = $this->input->post('tabs_val');                        

            $result = $this->all_list->saveSetting($type_id,$kind_id,$tabs_val);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function rubricSaveSetting(){
        if($this->session->userdata('is_login')){                      
            $tags_val = $this->input->post('tags_val');
            $scores_val = $this->input->post('scores_val');
            $kind_id = $this->input->post('kind_id');

            $result = $this->all_list->rubricSaveSetting($kind_id,$tags_val,$scores_val);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function tag_del(){  
        if($this->session->userdata('is_login')){        
            $tag_id = $this->input->post('tag_id');
            $kind_id = $this->input->post('kind_id');            

            $result = $this->all_list->setting_tag_del($tag_id,$kind_id);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function sco_del(){  
        if($this->session->userdata('is_login')){        
            $sco_id = $this->input->post('scoid');
            $kind_id = $this->input->post('kind_id');

            $result = $this->all_list->setting_sco_del($kind_id,$sco_id);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function add_tag(){  
        if($this->session->userdata('is_login')){        
            $kind_id = $this->input->post('kind_id');
            $add_tags_id = $this->input->post('add_tags_id');            

            $result = $this->all_list->setting_add_tag($kind_id,$add_tags_id);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function add_sco(){  
        if($this->session->userdata('is_login')){        
            $kind_id = $this->input->post('kind_id');
            $add_sco_id = $this->input->post('add_sco_id');            

            $result = $this->all_list->setting_add_sco($kind_id,$add_sco_id);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


}

