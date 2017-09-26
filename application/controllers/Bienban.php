<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bienban extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation','session'));
		$this->load->helper('security');
		$this->load->model(array('Bienban_model'));
        $this->load->model(array('Bieumau_model'));

        $this->_data['html_header'] = $this->load->view('home/header', NULL, TRUE);  
        $this->_data['html_menu'] = $this->load->view('home/menu', NULL, TRUE);
    }
    public $_data = array();

    public function list_bienban()
    {
        $id = $this->session->userdata('user')['id'];
        $this->data['bienbans'] = $this->Bienban_model->select_bienban($id); 
        $this->_data['html_body'] = $this->load->view('page/list_bienban', $this->data, TRUE); 

        $this->load->view('home/master', $this->_data);
    }
    public function tao_bienban()
    {
        $id = $this->session->userdata('user')['id'];
        $this->data['bieumaus'] = $this->Bieumau_model->select_bieumau($id);
        $this->_data['html_body'] = $this->load->view('page/tao_bienban', $this->data, TRUE);  
        $this->load->view('home/master', $this->_data);
    }

    public function insert_bienban()
    {
        $a_data['ten_bienban'] = $this->input->post('ten');
        $a_data['id_user'] = $this->session->userdata('user')['id'];
        $id_bieumau = $this->input->post('id_bieumau');
        $a_data['id_bieumau'] = $id_bieumau;

        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './images/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['file_name'] = $_FILES['image']['name'];

            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('image')) {
                $uploadData = $this->upload->data();
                $data = $uploadData['file_name'];
            } else{
                $data = '';
            }
        }else{
            $data = '';
        }

        $dulieu = $this->Bieumau_model->select_dulieu_id($id_bieumau);
        $dulieu = unserialize($dulieu->dulieu);
        $count =count($dulieu);
        for ($i=1; $i <= $count; $i++) { 
            if($dulieu[$i]['loai']=='file'){
             array_push($dulieu[$i] , $data);
            }else{
                array_push($dulieu[$i] , $this->input->post($i));
            }
        }
        $a_data['dulieu'] = serialize($dulieu);
        
        $this->Bienban_model->insert_bienban($a_data);
        redirect(base_url('list_bienban'));
        
    }
}
