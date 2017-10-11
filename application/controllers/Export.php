<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class export extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->helper('My_helper');
        // Load the Library
        $this->load->library("excel");
        $this->load->library('word');
        $this->load->library('session');
        // Load the Model
        $this->load->model("Bienban_model");
    }

    public function index() {
        
    }
    public function export_file($id='')
     {
        $dulieu = $this->Bienban_model->get_dulieu_id($id);
        //var_dump($dulieu);
        $type = $dulieu->type_bienban;
        $filename = stripUnicode($dulieu->ten_bienban);
        $dulieu = unserialize($dulieu->dulieu);
        //var_dump($filename);
        $file = $this->Bienban_model->filename_id($id);
       
        if ($type == 'excel') {
            $object = new PHPExcel();
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load('template/'.$file->file.'');
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
                    $objDrawing->setHeight(150);
                    $objDrawing->setWidth(200);
                    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                 } 
                 else{
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dl['cot'],$dl['hang'],$dl[0]);
                }
            }
            $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
            ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename='.$filename.'.xlsx');
            header('Cache-Control: max-age=0');
            $object_writer->save('php://output');
        }else{
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $document = $phpWord->loadTemplate('template/'.$file->file.'');
            unset($dulieu['file']);
            foreach ($dulieu as $dl) {
                
                $document->setValue($dl['search'],$dl[0]);
            }
            //save file
            $document->saveAs('temp.docx');
            header('Content-Type: application/document');
            header('Content-Disposition: attachment; filename='.$filename.'.docx');
            readfile('temp.docx');
            unlink('temp.docx'); 
        }

        
        
    }

    public function all_file()
    {
        $fileOffice = array();
        $id = $this->session->userdata('user')['id'];
        $data = $this->Bienban_model->select_bienban($id);
        foreach ($data as $da) {
            $dulieu = $this->Bienban_model->get_dulieu_id($da->id);
            $filename = stripUnicode($dulieu->ten_bienban);
            $dulieu = unserialize($dulieu->dulieu);
            $file = $this->Bienban_model->filename_id($da->id);
            if ($da->type_bienban == "excel") {
               $object = new PHPExcel();
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                $objPHPExcel = $objReader->load('template/'.$file->file.'');
                $objPHPExcel->setActiveSheetIndex(0);
                unset($dulieu['file']);
                foreach ($dulieu as $dl) {
                    if ($dl['loai']=='file') {
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dl['cot'],$dl['hang'],'');
                        $colString = PHPExcel_Cell::stringFromColumnIndex($dl['cot']+1);
                        $objDrawing = new PHPExcel_Worksheet_Drawing();
                        $objDrawing->setName($dl[0]);
                        $objDrawing->setDescription($dl[0]);
                        $objDrawing->setPath('./images/'.$dl[0].'');
                        $objDrawing->setCoordinates(''.$colString.''.$dl['hang'].'');
                        $objDrawing->setHeight(150);
                        $objDrawing->setWidth(280);
                        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                     } 
                     else{
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dl['cot'],$dl['hang'],$dl[0]);
                    }
                }
                $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
                $object_writer->save($filename.'.xlsx');
                array_push($fileOffice, $filename.'.xlsx');
            }else{
                $phpWord = new \PhpOffice\PhpWord\PhpWord();
                $document = $phpWord->loadTemplate('template/'.$file->file.'');
                unset($dulieu['file']);
                foreach ($dulieu as $dl) {
                    
                    $document->setValue($dl['search'],$dl[0]);
                }
                //save file
                $document->saveAs($filename.'.docx');
                array_push($fileOffice, $filename.'.docx');
            }
            
        }
        // var_dump($fileExcel);
        $zipname = 'file.zip';
        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ($fileOffice as $file) {
          $zip->addFile($file);
        }
        $zip->close();
        
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
        unlink($zipname);
        foreach ($fileOffice as $file) {
          unlink($file);
        }
    }


    
}