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
        
        $dulieu = $this->Bieumau_model->select_dulieu_id($id_bieumau);
        $dulieu = unserialize($dulieu->dulieu);
        // var_dump($dulieu);
        $count =count($dulieu);
        // // var_dump(unserialize($dulieu->dulieu));
        // $data = array();
        for ($i=1; $i < $count; $i++) { 
             array_push($dulieu[$i] , $this->input->post($i));
        }
        // var_dump($dulieu[1][0]);
        $a_data['dulieu'] = serialize($dulieu);
        
        $this->Bienban_model->insert_bienban($a_data);
        redirect(base_url('list_bienban'));
        
    }
}
