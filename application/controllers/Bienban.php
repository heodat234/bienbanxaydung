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
        $this->load->helper('My_helper');
		$this->load->model(array('Bienban_model'));
        $this->load->model(array('Bieumau_model'));
        $this->load->model(array('Congtrinh_model'));
        $this->_data['html_header'] = $this->load->view('home/header', NULL, TRUE);  
        $this->_data['html_menu'] = $this->load->view('home/menu', NULL, TRUE);
    }
    public $_data = array();

    public function list_bienban()
    {
        $id = $this->session->userdata('user')['id'];
        $bienbans = $this->Bienban_model->select_bienban($id);
        for ($i=0; $i < count($bienbans); $i++) { 
            $kodau = stripUnicode($bienbans[$i]['ten_bienban']);
            array_push($bienbans[$i], $kodau);
         } 
         $this->data['bienbans'] = $bienbans;
        $this->_data['html_body'] = $this->load->view('page/list_bienban', $this->data, TRUE); 

        $this->load->view('home/master', $this->_data);
    }
    public function tao_bienban()
    {
        $id = $this->session->userdata('user')['id'];
        $this->data['bieumaus'] = $this->Bieumau_model->select_bieumau($id);
        $this->data['congtrinh'] = $this->Congtrinh_model->select_congtrinh($id);
        $this->_data['html_body'] = $this->load->view('page/tao_bienban', $this->data, TRUE);  
        $this->load->view('home/master', $this->_data);
    }

    public function insert_bienban()
    {
        $a_data['ten_bienban'] = $this->input->post('ten');
        $a_data['id_user'] = $this->session->userdata('user')['id'];
        $id_bieumau = $this->input->post('id_bieumau');
        $a_data['id_congtrinh'] = $this->input->post('id_congtrinh');
        $a_data['id_bieumau'] = $id_bieumau;
        $a_data['id_congtrinh'] = $this->input->post('id_congtrinh');
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
        $type = $dulieu->type_bieumau;
        $dulieu = unserialize($dulieu->dulieu);
        $count =count($dulieu);
        
        for ($i=1; $i <= $count; $i++) { 
            if($dulieu[$i]['loai']=='file'){
                if (!empty($_FILES[$dulieu[$i]['id']]['name'])) {
                    $config['upload_path'] = './images/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['file_name'] = $_FILES[$dulieu[$i]['id']]['name'];

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload($dulieu[$i]['id'])) {
                        $uploadData = $this->upload->data();
                        $data = $uploadData['file_name'];
                    } else{
                        $data = '';
                    }
                }else{
                    $data = '';
                }
                array_push($dulieu[$i] , $data);
            }else{
                array_push($dulieu[$i] , $this->input->post($i));
            }
        }
        if($type == 'excel'){
            $a_data['type_bienban'] = "excel";
        }else{
            $a_data['type_bienban'] = "word";
        }
        
        $a_data['dulieu'] = serialize($dulieu);
        $this->Bienban_model->insert_bienban($a_data);
        redirect(base_url('list_bienban'));
        
    }

    public function edit_bien_ban(){
        $data['form-data'] ='';
        $id = $this->input->post('id');
        $dulieu = $this->Bienban_model->get_bienban($id);
        $bienban = unserialize($dulieu->dulieu);
        foreach ($bienban as $bb){
            if(is_array($bb) && array_key_exists('0',$bb)){
                if($bb['loai']=='file'){
                $data['form-data'].='
                    <div class="form-group">
                     <div><b>'.$bb['ten'].'</b></div>
                     <div class="input-group">
                        <div class="input-group-addon iga2">
                           <span class="glyphicon glyphicon-edit"></span>
                        </div>
                        <input type="text" disabled=""  class="form-control" id="f_in" value="'.$bb['0'].'">
                        <div class="input-group-addon iga2">
                           <label class="fa"><b>...</b><input onchange="showFile(this);" type="file" id="file_input" name="'.$bb['id'].'" style="display: none;"></label>
                        </div>
                     </div>
                  </div>
                ';}else if($bb['loai']=='textarea'){
                $data['form-data'].='
                    <div class="form-group">
                     <div><b>'.$bb['ten'].'</b></div>
                     <div class="input-group">
                        <div class="input-group-addon iga2">
                           <span class="glyphicon glyphicon-edit"></span>
                        </div>
                        <textarea class="form-control" name="'.$bb['id'].'">'.$bb['0'].'</textarea>
                     </div>
                  </div>
                ';}else{
                    $data['form-data'].='
                    <div class="form-group">
                     <div><b>'.$bb['ten'].'</b></div>
                     <div class="input-group">
                        <div class="input-group-addon iga2">
                           <span class="glyphicon glyphicon-edit"></span>
                        </div>
                        <input type="'.$bb['loai'].'" class="form-control" name="'.$bb['id'].'" value="'.$bb['0'].'">
                     </div>
                  </div>
                ';
                } 
            }else{
                if($bb['loai']=='file'){
                $data['form-data'].='
                    <div class="form-group">
                     <div><b>'.$bb['ten'].'</b></div>
                     <div class="input-group">
                        <div class="input-group-addon iga2">
                           <span class="glyphicon glyphicon-edit"></span>
                        </div>
                        <input type="text" disabled=""  class="form-control" id="f_in" value="">
                        <div class="input-group-addon iga2">
                           <label class="fa"><b>...</b><input onchange="showFile(this);" type="file" id="file_input" name="'.$bb['id'].'" style="display: none;"></label>
                        </div>
                     </div>
                  </div>
                ';}else if($bb['loai']=='textarea'){
                $data['form-data'].='
                    <div class="form-group">
                     <div><b>'.$bb['ten'].'</b></div>
                     <div class="input-group">
                        <div class="input-group-addon iga2">
                           <span class="glyphicon glyphicon-edit"></span>
                        </div>
                        <textarea class="form-control" name="'.$bb['id'].'">'.$bb['0'].'</textarea>
                     </div>
                  </div>
                ';}else{
                $data['form-data'].='
                    <div class="form-group">
                     <div><b>'.$bb['ten'].'</b></div>
                     <div class="input-group">
                        <div class="input-group-addon iga2">
                           <span class="glyphicon glyphicon-edit"></span>
                        </div>
                        <input type="'.$bb['loai'].'" class="form-control" name="'.$bb['id'].'" value="">
                     </div>
                  </div>
                ';} 
            }
           
        
        }
        echo json_encode($data['form-data']);

    }

    public function update_bien_ban(){
        $frm = $this->input->post();
        $dulieu = $this->Bienban_model->get_bienban($frm['id']);
        $bienban = unserialize($dulieu->dulieu);
        $da = array();
        
        
        for ($i=1; $i<=count($bienban);$i++) {
            if($bienban[$i]['loai']=='file'){
                    // $da['image'] = $bienban[$i]['0'];
                    if (!empty($_FILES[$bienban[$i]['id']]['name'])) {
                        $config['upload_path'] = './images/';
                        $config['allowed_types'] = 'jpg|jpeg|png|gif';
                        $config['file_name'] = $_FILES[$bienban[$i]['id']]['name'];

                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload($bienban[$i]['id'])) {
                            $uploadData = $this->upload->data();
                            $da['image'] = $uploadData['file_name'];
                            $bienban[$i]['0'] = $da['image'];
                        } } 
            }else{
                $bienban[$i]['0'] = $frm[$i];
            }
            
        }
        $udata['dulieu'] = serialize($bienban);
        $udata['ten_bienban'] = $frm['name'];
        $this->Bienban_model->update_data_bien_ban($frm['id'],$udata);
        $data['date'] = $this->Bienban_model->get_time_update($frm['id']);

        echo json_encode($data['date']);
    }
}
