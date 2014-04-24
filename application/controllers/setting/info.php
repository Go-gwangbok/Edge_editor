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
            $type = $this->input->post('type');
            $json['data_kind'] = $this->all_list->getDataKind($type);                
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
            //$json['data_kind'] = $this->all_list->data_kind();
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
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

            $json['result'] = $this->all_list->member_edit_save($usr_id,$task_ids,$type,$start,$end,$pay);
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));           
    }

    function templet($type_id,$data_kind_id,$kind){
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'setting';
            $this->load->view('head',$data);           

            $data['add_tag_data'] = $this->all_list->add_tag_data($data_kind_id);
            $data['add_score_data'] = $this->all_list->add_score_data($data_kind_id);

            $data['kind'] = $kind;
            $data['kind_id'] = $data_kind_id;
            $data['type_id'] = $type_id;
            $this->load->view('/setting_view/templet',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }  
    }

    function get_typevals(){
        if($this->session->userdata('is_login')){  
            $kind_id = $this->input->post('kind_id');
            $type_id = $this->input->post('type_id');

            $json['tabs'] = $this->all_list->get_setup_tabs($type_id,$kind_id);
            $json['get_setup_tag'] = $this->all_list->get_setup_tag($kind_id);            
            $json['scores'] = $this->all_list->get_setup_scores($kind_id);
            
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function saveSetting(){
        if($this->session->userdata('is_login')){          
            $tabs_val = $this->input->post('tabs_val');
            $tags_val = $this->input->post('tags_val');
            $scores_val = $this->input->post('scores_val');
            $kind_id = $this->input->post('kind_id');

            $result = $this->all_list->saveSetting($kind_id,$tabs_val,$tags_val,$scores_val);
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
            $type_id = $this->input->post('type_id');

            $result = $this->all_list->setting_tag_del($type_id,$tag_id,$kind_id);
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
            $type_id = $this->input->post('type_id');

            $result = $this->all_list->setting_add_tag($type_id,$kind_id,$add_tags_id);
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
            $type_id = $this->input->post('type_id');

            $result = $this->all_list->setting_add_sco($type_id,$kind_id,$add_sco_id);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


}

