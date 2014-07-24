<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('all_list');
        $this->load->model('stat');                             
        $this->load->model('picto_model');
        $this->load->model('speaking_model');
        $this->load->helper('url'); 
    }

    function index($service_name = 'musedata'){        
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'stat';
            $this->load->view('head',$data);

            $data['service_name'] = $service_name;
            $data['title'] = 'Muse Data';

            //$data['cateType'] = $this->all_list->cateType();

            $this->load->view('/stat_view/info_view',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }   
    }

    function picto(){        
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'stat';
            $this->load->view('head',$data);

            $data['service_name'] = 'picto';
            $data['title'] = 'Picto';

            $this->load->view('/stat_view/info_view',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }   
    }

    function spekaing(){        
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'stat';
            $this->load->view('head',$data);

            $data['service_name'] = 'speaking';
            $data['title'] = 'EDGE Speaking';

            $this->load->view('/stat_view/info_view',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }   
    }

    function writing(){        
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'stat';
            $this->load->view('head',$data);

            $data['service_name'] = 'writing';
            $data['title'] = 'EDGE Writings';

            $this->load->view('/stat_view/info_view',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }   
    }

    function editor(){        
        if($this->session->userdata('is_login')){                              
            $data['cate'] = 'stat';
            $this->load->view('head',$data);

            $data['service_name'] = 'editor';
            $data['title'] = 'EDGE Editor';

            $data['summary_stat'] = $this->stat->get_summary_stat('grammar');
            //var_dump($data);


            $this->load->view('/stat_view/info_view',$data);     
            $this->load->view('footer');                    
        }else{
            redirect('/');
        }   
    }




    function get_musedata(){
        if($this->session->userdata('is_login')){            
            $gubun = $this->input->post('gubun');
            $json['stat_list'] = $this->stat->get_musedata_stat($gubun);
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function get_pictodata(){
        if($this->session->userdata('is_login')){            
            $gubun = $this->input->post('gubun');
            //$json['stat_list'] = $this->picto_model->getDataStat($gubun);
            $json['stat_list'] = $this->stat->get_pictodata_stat($gubun);
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function get_speakingdata(){
        if($this->session->userdata('is_login')){            
            $gubun = $this->input->post('gubun');
            //$json['stat_list'] = $this->speaking_model->getDataStat($gubun);
            $json['stat_list'] = $this->stat->get_speakingdata_stat($gubun);
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function get_writingdata(){
        if($this->session->userdata('is_login')){            
            $gubun = $this->input->post('gubun');
            $json['stat_list'] = $this->stat->get_writing_stat($gubun);
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    function get_editordata(){
        if($this->session->userdata('is_login')){            
            $gubun = $this->input->post('gubun');
            $json['stat_list'] = $this->stat->get_editor_stat($gubun);
        }else{
            redirect('/');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
}

