<?php
namespace backend\controllers;

class ExcelController
{
    private $config = [
        'fileName' => 'excel',
        'sheetName' => 'sheet1',
        'height' => '30',
        'titleSize' => '20',
        'contentSize' => '20',
    ];

    public function __construct($config=array()){
        $this->config = array_merge($this->config, $config);
    }

    public function Import()
    {

    }
    public function Export($column, $data)
    {
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

// Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();

// Set document properties
        $objPHPExcel->getProperties()->setCreator("helper")
            ->setLastModifiedBy("helper")
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Document")
            ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php");

// Add some data
        foreach($column as $k => $v){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($v['column'].'1', $v['name']);
        }
        foreach($data as $key => $val){
            foreach($val as $k => $v){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column[$k]['column'].($key+2), $v);
            }
        }

// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($this->config['sheetName']);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
//        $objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$this->config['fileName'].'.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }









}
