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
		$data['type'] = $dulieu->type_bieumau;
		// var_dump($data['dulieu']);
		$this->_data['html_body'] = $this->load->view('page/chitiet_bieumau',$data , TRUE); 
		$this->load->view('home/master', $this->_data);
	}
	public function them_bieumau()
	{
		$this->_data['html_body'] = $this->load->view('page/them_bieumau', NULL, TRUE);  
		$this->load->view('home/master', $this->_data);
	}
	public function insert()
	{
		$this->form_validation->set_rules('ten', 'Tên biểu mẫu', 'trim|required|xss_clean');
		// $this->form_validation->set_rules('file', 'File mẫu ', 'required');

		if($this->form_validation->run() == TRUE){
			$a_data['ten'] = $this->input->post('ten');
			$a_data['id_user'] = $this->session->userdata('user')['id'];
			$a_data['mota'] = $this->input->post('mota');

			if (!empty($_FILES['file']['name'])) {
			//Kiểm tra tồn tại thư mục
			if (!is_dir('template/'.$a_data['id_user']))
			{
			   //Tạo thư mục
				mkdir('template/'.$a_data['id_user']);
			}
			$config['upload_path'] = './template/'.$a_data['id_user'];
			$config['allowed_types'] = 'xlsx|docx';
			$config['file_name'] = $_FILES['file']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
				if ($this->upload->do_upload('file')) {
					$uploadData = $this->upload->data();
					$a_data["file"] = $uploadData['file_name'];
					$data["type"] = $uploadData['file_ext'];
				} else{
					$error = $this->upload->display_errors();
            		echo $error;
					$a_data["file"] = '';
				}
			}else{
				$a_data["file"] = '';
			}

			if ($data["type"] == '.xlsx') {
				$data = $this->readExcel($a_data["file"]);
				$a_data['type_bieumau'] = "excel";
			}else{
				$data = $this->readWord($a_data["file"]);
				$a_data['type_bieumau'] = "word";
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
        $id = $this->session->userdata('user')['id'];
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('template/'.$id.'/'.$filename.'');

        $objWorksheet  = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $array = array();
        $data = array();
        $k=1;
        for ($row = 2; $row <= $highestRow;++$row)
        {
            for ($col = 0; $col <$highestColumnIndex-1;++$col)
            {
                $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                
                if (substr($value, 0,2) == '${' && substr($value,-1) == '}') {    //tim nhung chuoi co chu vung
                	$value = trim($value,'${ }');
                	$mData = explode(";", $value);
                	if ($mData[1] == "text") {
                		$mData[1] = "textarea";
                	}
                    $array[$k] =array('id'=>$k, 'ten'=>$mData[0], 'loai'=>$mData[1], 'cot'=>$col, 'hang'=>$row);
                    $k++;
                }
            }
        }
        // echo("<br>");
        // print_r($array);
        return $array;
    }


    function readWord($filename)
    {
    	$phpWord = new \PhpOffice\PhpWord\PhpWord();
    	$id = $this->session->userdata('user')['id'];
        $document = $phpWord->loadTemplate('template/'.$id.'/'.$filename.'');
        $variables = $document->getVariables();
        $mData =array();
        $var = array();
        $array = array();
        $id=1;
        for ($i=0; $i < count($variables); $i++) { 
            $var[$i] = preg_replace('/<[^>]+>/', '',$variables[$i]);
            $mData[$i] = explode(";",$var[$i] );
            if ($mData[$i][1] == "text") {
                $mData[$i][1] = "textarea";
            }
            $array[$id] =array('id'=>$id, 'ten'=>$mData[$i][0], 'loai'=>$mData[$i][1],'search'=>$var[$i]);
            $id++;
        }

        // var_dump($array);
        return $array;
    }
    function edit_bieu_mau(){
       
        $data = array();
        $typeFile = "";
        $idUser = $this->session->userdata('user')['id'];
        $id = $this->input->post('id');
        $a_data['ten'] = $this->input->post('name');
		$a_data['mota'] = $this->input->post('desc');


		if (!empty($_FILES['file_input']['name'])) {
			$config['upload_path'] = './template/'.$idUser;
			$config['allowed_types'] = 'xlsx|docx';
			$config['file_name'] = $_FILES['file_input']['name'];
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			if ($this->upload->do_upload('file_input')) {
				
				$uploadData = $this->upload->data();
				$a_data["file"] = $uploadData['file_name'];
				$typeFile = $uploadData['file_ext'];
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
			if ($typeFile == '.xlsx') {
				$data = $this->readExcel($a_data["file"]);
				$a_data['type_bieumau'] = "excel";
			}else{
				$data = $this->readWord($a_data["file"]);
				$a_data['type_bieumau'] = "word";
			}
		}
		
		
		$a_data['dulieu'] = serialize($data);
		$this->Bieumau_model->update_bieumau($id,$a_data);

		$Bieumau = $this->Bieumau_model->select_dulieu_id($id);
		 echo(json_encode($Bieumau->file));
    }

    
    public function select_dulieu()
    {
    	$id    = $this->input->post('id');
    	$dulieu = $this->Bieumau_model->select_dulieu_id($id);
    	$dulieu = unserialize($dulieu->dulieu);
    	$dulieu = json_encode($dulieu);
    	print_r( $dulieu);
    }
}	