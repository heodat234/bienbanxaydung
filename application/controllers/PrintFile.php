<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH.'/libraries/PhpWord/IOFactory.php';
class PrintFile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->helper('My_helper');
        $this->load->helper(array('dompdf_helper', 'file'));
        // Load the Library
        $this->load->library("excel");
        $this->load->library('word');
        $this->load->library('session');
        // Load the Model
        $this->load->model("Bienban_model");

    }
     
    public function index() {
        
    }
    public function view_file($id='')
     {
        $id_user = $this->session->userdata('user')['id'];
        $dulieu = $this->Bienban_model->get_dulieu_id($id);
        //var_dump($dulieu);
        $type = $dulieu->type_bienban;
        $filename = stripUnicode($dulieu->ten_bienban);
        $dulieu = unserialize($dulieu->dulieu);
        //var_dump($filename);
        $file = $this->Bienban_model->filename_id($id);
        $data = array();
        if ($type == 'excel') {
            $object = new PHPExcel();
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load('template/'.$id_user.'/'.$file->file.'');
            $objPHPExcel->setActiveSheetIndex(0);
            unset($dulieu['file']);
            // var_dump($dulieu);
            foreach ($dulieu as $dl) {
                if ($dl['loai']=='file') {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dl['cot'],$dl['hang'],'');
                    $colString = PHPExcel_Cell::stringFromColumnIndex($dl['cot']+1);
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setName($dl[0]);
                    $objDrawing->setDescription($dl[0]);
                    $objDrawing->setPath('./images/'.$dl[0].'');
                    $objDrawing->setCoordinates(''.$colString.''.$dl['hang'].'');
                    $objDrawing->setHeight(200);
                    $objDrawing->setWidth(400);
                    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                 } 
                 else{
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dl['cot'],$dl['hang'],$dl[0]);
                }
            }
            
            $objPHPExcel->getActiveSheet()->setShowGridlines(false);
            
            $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
            ob_end_clean();
           
            $object_writer->save($filename.'.xlsx');

            $data['filename'] = $filename.'.xlsx';

        }else{
           
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $document = $phpWord->loadTemplate('template/'.$id_user.'/'.$file->file.'');
            unset($dulieu['file']);
            foreach ($dulieu as $dl) {
                if ($dl['loai'] == "file") {
                    $document->setImg($dl['search'], [
                      "src"=>'images/'.$dl[0],
                      "swh"=>"200"
                    ]);
                }
                $document->setValue($dl['search'],$dl[0]);
            }
            //save file
             $document->saveAs($filename.'.docx');
            
             $data['filename'] = $filename.'.docx';
            
        }


       echo json_encode($data['filename']);

        
    }
}