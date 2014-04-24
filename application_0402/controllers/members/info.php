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
            $data['cate'] = 'members';
            $this->load->view('head',$data);

            $this->load->view('/members/info_view',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }   
    }

    function get_Editors(){
        if($this->session->userdata('is_login')){
            $json['get_editors'] = $this->all_list->get_Editors();                
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
            redirect('/members/info');
        }else{
            echo '<script> alert("DB false");</script>';
        }

        
        // echo 'musedata : '.$musedata.'<br>
        //     writing : '.$writing.'<br>
        //     part_time : '.$part_time.'<br>
        //     case : '.$case.'<br>
        //     type : '.$type.'<br>
        //     start : '.$start.'<br>
        //     end : '.$end.'<br>
        //     pay : '.$pay.'<br>
        //     usr_id : '.$usr_id.'';
    }

    function member_edit($usr_id){
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'members';
            $this->load->view('head',$data);

            $data['usr_id'] = $usr_id;
            $this->load->view('/members/member_edit',$data);     
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
}

