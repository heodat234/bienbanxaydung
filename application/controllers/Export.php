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
        $id_user = $this->session->userdata('user')['id'];
        $dulieu = $this->Bienban_model->get_bienban($id);
        $type = $dulieu->type_bienban;
        $filename = stripUnicode($dulieu->ten_bienban);
        $dulieu = unserialize($dulieu->dulieu);
        $file = $this->Bienban_model->filename_id($id);
       
        if ($type == 'excel') {
            $object = new PHPExcel();
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load('template/'.$id_user.'/'.$file->file.'');
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
                    $objDrawing->setHeight(200);
                    $objDrawing->setWidth(400);
                    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                 } 
                 else{
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dl['cot'],$dl['hang'],$dl[0]);
                }
            }
            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);                          
            $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
            ob_end_clean();
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename='.$filename.'.xlsx');
            header('Cache-Control: max-age=0');
            $object_writer->save('php://output');
            exit;
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
                $objPHPExcel = $objReader->load('template/'.$id.'/'.$file->file.'');
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
                        $objDrawing->setHeight(200);
                        $objDrawing->setWidth(400);
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
                $document = $phpWord->loadTemplate('template/'.$id.'/'.$file->file.'');
                unset($dulieu['file']);
                foreach ($dulieu as $dl) {
                    
                    $document->setValue($dl['search'],$dl[0]);
                }
                //save file
                $document->saveAs($filename.'.docx');
                array_push($fileOffice, $filename.'.docx');
            }
            
        }
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