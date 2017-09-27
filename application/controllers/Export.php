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
        $filename = $this->stripUnicode($dulieu->ten_bienban);
        $dulieu = unserialize($dulieu->dulieu);
        // var_dump($filename); 

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
        header('Content-Disposition: attachment; filename='.$filename.'.xlsx');
        header('Cache-Control: max-age=0');
        $object_writer->save('php://output');
        
    }

    public function all_excel()
    {
        $fileExcel = array();
        $id = $this->session->userdata('user')['id'];
        $data = $this->Bienban_model->select_bienban($id);
        foreach ($data as $da) {
            $object = new PHPExcel();

            $file = $this->Bienban_model->filename_excel_id($da->id);
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load('template/'.$file->file.'');

            $objPHPExcel->setActiveSheetIndex(0);

            $dulieu = $this->Bienban_model->get_dulieu_id($da->id);
            $filename = $this->stripUnicode($dulieu->ten_bienban);
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
            $object_writer->save($filename.'.xlsx');
        
            array_push($fileExcel, $filename.'.xlsx');
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



    public function stripUnicode($str){
      if(!$str) return false;
       $unicode = array(
          'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
          'd'=>'đ',
          'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
          'i'=>'í|ì|ỉ|ĩ|ị',
          'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
          'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
          'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
       );
    foreach($unicode as $nonUnicode=>$uni) $str = preg_replace("/($uni)/i",$nonUnicode,$str);
    return $str;
    }
}