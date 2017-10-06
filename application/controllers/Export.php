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
        $this->excel->setActiveSheetIndex(0);
        // Gets all the data using MY_Model.php
        $data["users"] = $this->user_model->get_all();

        $this->load->view("excelTable",$data);
    }

    public function export_excel($id='')
     {
        $dulieu = $this->Bienban_model->get_dulieu_id($id);
        //var_dump($dulieu);
        $filename = stripUnicode($dulieu->ten_bienban);
        $dulieu = unserialize($dulieu->dulieu);
        //var_dump($filename); 

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
        $filename = stripUnicode($dulieu->ten_bienban);//đổi tên thành ko dấu
        $dulieu = unserialize($dulieu->dulieu);
        if ($dulieu['file'] == 'excel') {
            $object = new PHPExcel();
            $file = $this->Bienban_model->filename_excel_id($id);
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
        }else{
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $file = $this->Bienban_model->filename_excel_id($id);
            $document = $phpWord->loadTemplate('template/'.$file->file.'');
            unset($dulieu['file']);
            foreach ($dulieu as $dl) {
                if ($dl['loai'] == 'file') {
                    $image = "../../images/".$dl[0];
                    $document->replaceStrToImg($dl['search'],$image);
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
            $filename = stripUnicode($dulieu->ten_bienban);
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


    public function word_demo()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $document = $phpWord->loadTemplate('cv.docx');
        // var_dump($document);
        $variables = $document->getVariables();
        // print_r($variables);
        $mData =array();
        $var = array();
        for ($i=0; $i < count($variables); $i++) { 
            $var[$i] = preg_replace('/<[^>]+>/', '',$variables[$i]);
            $mData[$i] = explode(",",$var[$i] );
        }
        // print_r($var);
        $document->setValue($var[0],'Lê Thanh Hưng');
        $document->setValue($var[1],'Lập trình');
        $document->setValue($var[2],'23/04/1995');
        $document->setValue($var[3],'Nam');
        $document->setValue($var[4],'0903950907');
        $document->setValue($var[5],'heodat234@gmail.com');
        $document->setValue($var[6],'15 Nguyễn Giản Thanh, quận 10');

        // // Adding an empty Section to the document...
        // $section = $phpWord->addSection();

        // // Adding Text element to the Section having font styled by default...
        // $section->addText(
        //     'Lê Thanh Hưng'
        // );

        // // Adding Text element with font customized inline...
        // $section->addText(
        //     '"Great achievement is usually born of great sacrifice, '
        //         . 'and is never the result of selfishness." '
        //         . '(Napoleon Hill)',
        //     array('name' => 'Tahoma', 'size' => 10)
        // );

        // // Adding Text element with font customized using named font style...
        // $fontStyleName = 'oneUserDefinedStyle';
        // $phpWord->addFontStyle(
        //     $fontStyleName,
        //     array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
        // );
        // $section->addText(
        //     '"The greatest accomplishment is not in never falling, '
        //         . 'but in rising again after you fall." '
        //         . '(Vince Lombardi)',
        //     $fontStyleName
        // );

        // // Adding Text element with font customized using explicitly created font style object...
        // $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        // $fontStyle->setBold(true);
        // $fontStyle->setName('Tahoma');
        // $fontStyle->setSize(19);
        // $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
        // $myTextElement->setFontStyle($fontStyle);


        //save file
        $document->saveAs('temp.docx');
        header('Content-Type: application/document');
        header('Content-Disposition: attachment; filename="cvHung.docx"');
        readfile('temp.docx');
        unlink('temp.docx'); 

    }


    
}