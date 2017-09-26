<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class export extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('date');
        // Load the Library
        $this->load->library("excel");
        $this->load->library('session');
        // Load the Model
        $this->load->model("Bienban_model");
    }

    public function index() {
        $this->excel->setActiveSheetIndex(0);
        // Gets all the data using MY_Model.php
        $data["users"] = $this->user_model->get_all();

        $this->load->view("excelTable",$data);
    }

    public function export_excel($id='')
     {
        $object = new PHPExcel();

        $file = $this->Bienban_model->filename_excel_id($id);
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load('template/'.$file->file.'');

        $objPHPExcel->setActiveSheetIndex(0);

        $dulieu = $this->Bienban_model->get_dulieu_id($id);
        $dulieu = unserialize($dulieu->dulieu);
        // var_dump($dulieu[8]);        
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
                $objDrawing->setWidth(380);
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
             } 
             else{
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dl['cot'],$dl['hang'],$dl[0]);
            }
        }
        $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.$file->file.'');
        header('Cache-Control: max-age=0');
        $object_writer->save('php://output');
        
    }

    public function all_excel()
    {
        $fileExcel = array();
        $dem = 1;
        $id = $this->session->userdata('user')['id'];
        $data = $this->Bienban_model->select_bienban($id);
        foreach ($data as $da) {
            $object = new PHPExcel();

            $file = $this->Bienban_model->filename_excel_id($da->id);
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load('template/'.$file->file.'');

            $objPHPExcel->setActiveSheetIndex(0);

            $dulieu = $this->Bienban_model->get_dulieu_id($da->id);
            $dulieu = unserialize($dulieu->dulieu);
            // var_dump($dulieu[8]);        
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
                    $objDrawing->setWidth(380);
                    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                 } 
                 else{
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($dl['cot'],$dl['hang'],$dl[0]);
                }
            }
            $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
            $object_writer->save($dem.$file->file);
        
            array_push($fileExcel, $dem.$file->file);
            $dem++;
        }
        // var_dump($fileExcel);
        $zipname = 'file.zip';
        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ($fileExcel as $file) {
          $zip->addFile($file);
        }
        $zip->close();
        
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
        // unset($fileExcel);
    }
}