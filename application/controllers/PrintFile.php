<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
            $data = pdf_create($objPHPExcel, 'hung');
            // $rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
            // $rendererLibrary = 'tcpdf';
            // $rendererLibraryPath = '' . $rendererLibrary;
            // if (!PHPExcel_Settings::setPdfRenderer(
            //   $rendererName,
            //   $rendererLibraryPath
            //  )) {
            //  die(
            //   'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
            //   '<br />' .
            //   'at the top of this script as appropriate for your directory structure'
            //  );
            // }
            // $objPHPExcel->getActiveSheet()->setShowGridlines(false);
            // // $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
            // $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_TRANSVERSE_PAPER );
            // // $objPHPExcel->getActiveSheet()->getPageSetup()->setScale(50);
            // // $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
            // $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel,'PDF');
            // // ob_end_clean();
            // // $object_writer->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT);

            // // $object_writer->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4_TRANSVERSE_PAPER );
            // $object_writer->save($filename.'.pdf');
        }else{
            $rendererName = \PhpOffice\PhpWord\Settings::PDF_RENDERER_TCPDF;
            $rendererLibrary = 'tcpdf';
            $rendererLibraryPath = '' . $rendererLibrary;
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
            
            if (!\PhpOffice\PhpWord\Settings::setPdfRenderer(
              $rendererName,
              $rendererLibraryPath
             )) {
             die(
              'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
              '<br />' .
              'at the top of this script as appropriate for your directory structure'
             );
            }
             $object_writer = \PhpOffice\PhpWord\IOFactory::createWriter($document , 'PDF');
             $object_writer->saveAs($filename.'.pdf');
        }

       $this->load->view('page/viewFile'); 
        
    }
}