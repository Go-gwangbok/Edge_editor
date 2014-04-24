<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('all_list');                             
        $this->load->helper('url'); 
    }

    public function index(){
        
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'setting';
            $this->load->view('head',$data);

            $this->load->view('/setting_view/info_view',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }   
    }

    function setting_data(){
        if($this->session->userdata('is_login')){
            $json['get_editors'] = $this->all_list->get_Editors();
            $json['data_kind'] = $this->all_list->data_kind();
        }else{
            
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function accept($id){
        if($this->session->userdata('is_login')){
            $json['result'] = $this->all_list->new_editor_accept($id);        
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

            $json['result'] = $this->all_list->get_user($usr_id);            
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));           
    }

    function member_edit_save(){
        if($this->session->userdata('is_login')){                              
            $usr_id = $this->input->post('usr_id');
            $musedata = $this->input->post('musedata');
            $writing = $this->input->post('writing');
            $type = $this->input->post('type');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $pay = $this->input->post('pay');

            if($musedata == 'musedata'){
                $musedata = 1;
            }
            if ($writing == 'writing') {
                $writing = 1;
            }            

            $json['result'] = $this->all_list->member_edit_save($usr_id,$musedata,$writing,$type,$start,$end,$pay);
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));           
    }

    function templet($data_kind_id,$kind){
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'setting';
            $this->load->view('head',$data);           

            $data['kind'] = $kind;
            $data['kind_id'] = $data_kind_id;
            $this->load->view('/setting_view/templet',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }  
    }

    function get_typevals(){
        if($this->session->userdata('is_login')){  
            $kind_id = $this->input->post('kind_id');

            $json['tabs'] = $this->all_list->get_setup_tabs($kind_id);
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

            $result = $this->all_list->setting_tag_del($tag_id);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function sco_del(){  
        if($this->session->userdata('is_login')){        
            $sco_id = $this->input->post('scoid');

            $result = $this->all_list->setting_sco_del($sco_id);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function create_tag(){  
        if($this->session->userdata('is_login')){        
            $kind_id = $this->input->post('kind_id');
            $tag_name = $this->input->post('tag_name');

            $result = $this->all_list->setting_crete_tag($kind_id,$tag_name);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function create_sco(){  
        if($this->session->userdata('is_login')){        
            $kind_id = $this->input->post('kind_id');
            $sco_name = $this->input->post('sco_name');

            $result = $this->all_list->setting_crete_sco($kind_id,$sco_name);
            $json['result'] = $result;
        }else{
            redirect('/');
        }  
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


}

