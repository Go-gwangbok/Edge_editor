<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('all_list');                             
    }

    public function index(){
        
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'pay';
            $this->load->view('head',$data);            
            $dos_avg = $this->all_list->dos_avg(); 
            $data['total_word_count'] = $dos_avg->total_word_count;
            $data['replace_count'] = $dos_avg->replace_count;

            $this->load->view('/pay/pay_view',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }   
    }

    public function get_pay_data(){
        if($this->session->userdata('is_login')){                              
            $json['data'] = $this->all_list->get_pay_data();                        

        }else{
            redirect('/');
        }   
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}

