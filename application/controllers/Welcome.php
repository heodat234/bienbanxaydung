<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->_data['html_header'] = $this->load->view('home/header', NULL, TRUE);  
        $this->_data['html_menu'] = $this->load->view('home/menu', NULL, TRUE);
        // $this->_data['html_body'] = $this->load->view('home/index',null,true); 
    }
    public $_data = array();
	public function index()
	{		        
		return $this->load->view('home/master', $this->_data);
	}
	
}
