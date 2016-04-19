<?php
namespace backend\controllers;

class ExcelController
{
    private $config = [
        'fileName' => 'excel',
        'sheetName' => 'sheet1',
        'columnHeight' => '30',
        'contentHeight' => '30',
        'fontSize' => '12',
    ];

    public function __construct($config=array()){
        $this->config = array_merge($this->config, $config);
    }

    public function Import()
    {

    }

    /**
     * 导出
     * @param $column
     * @param $data
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
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
        //设置默认字体和大小
        $objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', '宋体'));
        $objPHPExcel->getDefaultStyle()->getFont()->setSize($this->config['fontSize']);
        //设置栏目行高
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight($this->config['columnHeight']);
// Add some data
        foreach($column as $k => $v){
            //水平居中
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //设置列宽
            $objPHPExcel->getActiveSheet()->getColumnDimension($v['column'])->setWidth($v['width']);
            //单元格
            $objPHPExcel->getActiveSheet()->setCellValue($v['column'].'1', $v['name']);
        }
        foreach($data as $key => $val){
            //设置内容行高
            $objPHPExcel->getActiveSheet()->getRowDimension($key+2)->setRowHeight($this->config['contentHeight']);
            foreach($val as $k => $v){
                $objPHPExcel->getActiveSheet()->setCellValue($column[$k]['column'].($key+2), $v);
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
