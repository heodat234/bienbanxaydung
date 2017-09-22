<?php
class Login extends CI_Controller{
	
	private $b_Check = false;
	
	public function __construct(){
		parent::__construct();
		#Tải thư viện  và helper của Form trên CodeIgniter
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation','session'));
		$this->load->helper('security');
		
		#Tải model
		$this->load->model(array('Login_model'));
	}
	
	public function index(){
		$this->load->view('home/login');
	}
	
	public function login(){
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		#Kiểm tra điều kiện validate
		if($this->form_validation->run() == TRUE){
			$a_UserInfo['username'] = $this->input->post('username');
			$a_UserInfo['password'] = md5($this->input->post('password'));
			$a_UserChecking = $this->Login_model->a_fCheckUser( $a_UserInfo );
			if($a_UserChecking){
				$this->session->set_userdata('user', $a_UserChecking);
				redirect(base_url('welcome'));
			}else{
				$this->b_Check = false;
			}
		}
		$a_Data['b_Check']= $this->b_Check;
		$this->load->view('home/login', $a_Data);
		
	}

	public function logout(){
		$this->session->unset_userdata('user');	// Unset session of user
		redirect(base_url('login'));
	}
	
}