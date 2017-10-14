<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Congtrinh extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation','session'));
		$this->load->helper('security');
		$this->load->model(array('Congtrinh_model'));
        $this->_data['html_header'] = $this->load->view('home/header', NULL, TRUE);  
        $this->_data['html_menu'] = $this->load->view('home/menu', NULL, TRUE);
    }
    private $b_Check = false;
    public $_data = array();


    public function list_congtrinh()
	{
		$id = $this->session->userdata('user')['id'];
		$this->data['congtrinh'] = $this->Congtrinh_model->select_congtrinh($id); 
		$this->_data['html_body'] = $this->load->view('page/list_congtrinh', $this->data, TRUE); 

		$this->load->view('home/master', $this->_data);
	}

	
	public function them_congtrinh()
	{
		$this->_data['html_body'] = $this->load->view('page/them_congtrinh', NULL, TRUE);  
		$this->load->view('home/master', $this->_data);
	}
	public function insert()
	{
		$this->form_validation->set_rules('ten', 'Tên công trình', 'trim|required|xss_clean');
		// $this->form_validation->set_rules('file', 'File mẫu ', 'required');

		if($this->form_validation->run() == TRUE){
			$a_data['ten'] = $this->input->post('ten');
			$a_data['id_user'] = $this->session->userdata('user')['id'];
			$a_data['mota'] = $this->input->post('mota');

			// if (!empty($_FILES['file']['name'])) {
			// $config['upload_path'] = './template/';
			// $config['allowed_types'] = 'xlsx|docx';
			// $config['file_name'] = $_FILES['file']['name'];
			// $this->load->library('upload', $config);
			// $this->upload->initialize($config);
			// 	if ($this->upload->do_upload('file')) {
			// 		$uploadData = $this->upload->data();
			// 		$a_data["file"] = $uploadData['file_name'];
			// 		$data["type"] = $uploadData['file_ext'];
			// 	} else{
			// 		$error = $this->upload->display_errors();
   //          		echo $error;
			// 		$a_data["file"] = '';
			// 	}
			// }else{
			// 	$a_data["file"] = '';
			// }
			// if ($data["type"] == '.xlsx') {
			// 	$data = $this->readExcel($a_data["file"]);
			// 	$a_data['type_bieumau'] = "excel";
			// }else{
			// 	$data = $this->readWord($a_data["file"]);
			// 	$a_data['type_bieumau'] = "word";
			// }
			// $a_data['dulieu'] = serialize($data);
			$this->Congtrinh_model->insert_congtrinh($a_data);
			redirect(base_url('list_congtrinh'));
		}
		$this->_data['html_body'] = $this->load->view('page/them_congtrinh', NULL, TRUE);  
		$_data['b_Check']= $this->b_Check;
		$this->load->view('home/master', $this->_data);
	}


	
    function edit_congtrinh(){
        // var_dump($this->input->post());
        // var_dump($_FILES);
        $id = $this->input->post('id');
        $a_data['ten'] = $this->input->post('name');
		$a_data['mota'] = $this->input->post('desc');

		// if (!empty($_FILES['file_input']['name'])) {
		// $config['upload_path'] = './template/';
		// $config['allowed_types'] = 'xlsx';
		// $config['file_name'] = $_FILES['file_input']['name'];
		// $this->load->library('upload', $config);
		// $this->upload->initialize($config);
		// 	if ($this->upload->do_upload('file_input')) {
		// 		$uploadData = $this->upload->data();
		// 		$a_data["file"] = $uploadData['file_name'];
		// 	} else{
		// 		$error = $this->upload->display_errors();
  //       echo $error;
		// 		$a_data["file"] = '';
		// 	}
		// }else{
		// 	$a_data["file"] = '';
		// }

		// if($a_data["file"]=='') array_pop($a_data);
		// else{
		// 	$data = $this->readExcel($a_data["file"]);
		// 	$a_data['dulieu'] = serialize($data);
		// }
		$this->Congtrinh_model->update_congtrinh($id,$a_data);

		echo json_encode($this->Congtrinh_model->select_dulieu_id($id));
    }

    
    public function select_dulieu()
    {
    	$id    = $this->input->post('id');
    	$dulieu = $this->Congtrinh_model->select_dulieu_id($id);
    	// $dulieu = unserialize($dulieu->dulieu);
    	$dulieu = json_encode($dulieu);
    	print_r( $dulieu);
    }
}	