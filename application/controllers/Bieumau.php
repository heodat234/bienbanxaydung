<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bieumau extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
		$this->load->library(array('form_validation','session'));
		$this->load->helper('security');
		$this->load->library("excel");
		$this->load->library('word');
		$this->load->model(array('Bieumau_model'));
        $this->_data['html_header'] = $this->load->view('home/header', NULL, TRUE);  
        $this->_data['html_menu'] = $this->load->view('home/menu', NULL, TRUE);
    }
    private $b_Check = false;
    public $_data = array();


    public function list_bieumau()
	{
		$id = $this->session->userdata('user')['id'];
		$this->data['bieumaus'] = $this->Bieumau_model->select_bieumau($id); 
		$this->_data['html_body'] = $this->load->view('page/list_bieumau', $this->data, TRUE); 

		$this->load->view('home/master', $this->_data);
	}

	public function chitiet_bieumau($id_bieumau)
	{
		$dulieu = $this->Bieumau_model->select_dulieu_id($id_bieumau);
		$data['dulieu'] = unserialize($dulieu->dulieu);
		$data['ten_bieumau'] = $dulieu->ten;
		
		$this->_data['html_body'] = $this->load->view('page/chitiet_bieumau',$data , TRUE); 
		$this->load->view('home/master', $this->_data);
	}
	public function them_bieumau()
	{
		$this->_data['html_body'] = $this->load->view('page/them_bieumau', NULL, TRUE);  
		$this->load->view('home/master', $this->_data);
	}
	public function them()
	{
		$this->form_validation->set_rules('ten', 'Tên biểu mẫu', 'trim|required|xss_clean');
		// $this->form_validation->set_rules('file', 'File mẫu ', 'required');

		if($this->form_validation->run() == TRUE){
			$a_data['ten'] = $this->input->post('ten');
			$a_data['id_user'] = $this->session->userdata('user')['id'];
			$a_data['mota'] = $this->input->post('mota');

			if (!empty($_FILES['file']['name'])) {
			$config['upload_path'] = './template/';
			$config['allowed_types'] = 'xlsx|docx';
			$config['file_name'] = $_FILES['file']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
				if ($this->upload->do_upload('file')) {
					$uploadData = $this->upload->data();
					$a_data["file"] = $uploadData['file_name'];
					$data["type"] = $uploadData['file_type'];
				} else{
					$error = $this->upload->display_errors();
            echo $error;
					$a_data["file"] = '';
				}
			}else{
				$a_data["file"] = '';
			}
			if ($data["type"] == 'xlsx') {
				$data = $this->readExcel($a_data["file"]);
			}else{
				$data = $this->readWord($a_data["file"]);
			}
			$a_data['dulieu'] = serialize($data);
			$this->Bieumau_model->insert_bieumau($a_data);
			redirect(base_url('list_bieumau'));
		}
		$this->_data['html_body'] = $this->load->view('page/them_bieumau', NULL, TRUE);  
		$_data['b_Check']= $this->b_Check;
		$this->load->view('home/master', $this->_data);
	}


	function readExcel($filename)
    {
        $object = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('template/'.$filename.'');

        $objWorksheet  = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $array = array('file' =>'excel' );
        $data = array();
        for ($row = 2; $row <= $highestRow;++$row)
        {
            for ($col = 0; $col <$highestColumnIndex-1;++$col)
            {
                $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                $value = trim($value,'( )');
                if (substr($value, 0,4) == "vung") {    //tim nhung chuoi co chu vung
                	if ($col==0) {
                		$ten=$objWorksheet->getCellByColumnAndRow($col, $row-1)->getValue();
                	}else{
                		$ten=$objWorksheet->getCellByColumnAndRow($col-1, $row)->getValue();
                	}
                    if ($ten=='') {
                        $ten=$objWorksheet->getCellByColumnAndRow($col, $row-1)->getValue();
                    }
                    $k= substr($value, 4,1);//lấy so vung
                    $type = substr($value, 6); //lay loai du lieu can nhap
                    $array[$k] =array('id'=>$k, 'ten'=>$ten, 'loai'=>$type, 'cot'=>$col, 'hang'=>$row);
                }
            }
        }
        //$data['title'] = $array;
        // echo $data['content'] = $arraydata;
        return $array;
    }


    function readWord($filename)
    {
    	$phpWord = new \PhpOffice\PhpWord\PhpWord();

        $document = $phpWord->loadTemplate('template/'.$filename.'');
        $variables = $document->getVariables();
        $mData =array();
        $var = array();
        $array = array('file' =>'word' );
        $id=1;
        for ($i=0; $i < count($variables); $i++) { 
            $var[$i] = preg_replace('/<[^>]+>/', '',$variables[$i]);
            $mData[$i] = explode(",",$var[$i] );
            $array[$i] =array('id'=>$id, 'ten'=>$mData[$i][0], 'loai'=>$mData[$i][1],'search'=>$var[$i]);
            $id++;
        }

        // var_dump($array);
        return $array;
    }
    function edit_bieu_mau(){
        // var_dump($this->input->post());
        // var_dump($_FILES);
        $id = $this->input->post('id');
        $a_data['ten'] = $this->input->post('name');
		$a_data['mota'] = $this->input->post('desc');

		if (!empty($_FILES['file_input']['name'])) {
		$config['upload_path'] = './template/';
		$config['allowed_types'] = 'xlsx';
		$config['file_name'] = $_FILES['file_input']['name'];
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
			if ($this->upload->do_upload('file_input')) {
				$uploadData = $this->upload->data();
				$a_data["file"] = $uploadData['file_name'];
			} else{
				$error = $this->upload->display_errors();
        echo $error;
				$a_data["file"] = '';
			}
		}else{
			$a_data["file"] = '';
		}

		if($a_data["file"]=='') array_pop($a_data);
		else{
			$data = $this->readExcel($a_data["file"]);
			$a_data['dulieu'] = serialize($data);
		}
		$this->Bieumau_model->update_bieumau($id,$a_data);

		echo json_encode($this->Bieumau_model->get_bieumau($id));
    }

    
    public function select_dulieu()
    {
    	$id    = $this->input->post('id');
    	$dulieu = $this->Bieumau_model->select_dulieu_id($id);
    	$dulieu = unserialize($dulieu->dulieu);
    	unset($dulieu['file']);
    	$dulieu = json_encode($dulieu);
    	print_r( $dulieu);
    }
}	