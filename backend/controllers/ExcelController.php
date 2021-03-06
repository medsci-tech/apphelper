<?php
namespace backend\controllers;

class ExcelController
{

    /**
     * 读取Excel数据
     * author zhaiyu
     * @param $fileName
     * @param $column
     * @return array
     * @throws \PHPExcel_Reader_Exception
     */
    public function Import($fileName, $column)
    {
        error_reporting(E_ALL); //开启错误
        set_time_limit(0); //脚本不超时
        $suffix = mb_substr($fileName, (mb_strripos($fileName, '.') + 1));
        if('xls' == $suffix){
            $inputFileType = 'Excel5';    //这个是读 xls的
        }elseif ('xlsx' == $suffix){
            $inputFileType = 'Excel2007';    //这个是读 xls的
        }else{
            return ['code'=>601,'msg' => '文件格式不正确'];
        }
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($fileName);

        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();//取得总行数
        $highestColumnIndex = count($column);//总列数

        $listTitle = [];$listData = [];
        for ($col = 0;$col < $highestColumnIndex;$col++)
        {
            $listTitle[] =$objWorksheet->getCellByColumnAndRow($col, 1)->getValue();//标题
        }
        for ($row = 2;$row <= $highestRow;$row++)
        {
            $data = [];$emptyCount = 0;
            foreach ($column as $key => $val){
                //内容
                $data[$key]=$objWorksheet->getCellByColumnAndRow(array_search($val, $listTitle), $row)->getValue();
                if(empty(trim($data[$key]))){
                    $emptyCount++;
                }
            }
            if($emptyCount == 0){
                $listData[] = $data;
            }else if($emptyCount > 0 && $emptyCount < $highestColumnIndex){
                return ['code'=>601,'msg' => '第' . $row . '行数据有误，请更正后重新导入'];
            }
        }
        if($listData){
            return ['code'=>200,'msg'=>'success', 'data' => $listData];
        }else{
            return ['code'=>602,'msg'=>'data empty'];
        }
    }

    /**
     * 导出Excel数据
     * @param $column
     * @param $data
     * @param $config
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function Export($config, $column, $data)
    {
        $defaultConfig = [
            'fileName' => 'excel',
            'sheetName' => 'sheet1',
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
            'create' => 'helper',
        ];
        $config = array_merge($defaultConfig, $config);

        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

// Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();

// Set document properties
        $objPHPExcel->getProperties()->setCreator($config['create'])
            ->setLastModifiedBy($config['create'])
            ->setTitle("Office 2007 XLSX Document")
            ->setSubject("Office 2007 XLSX Document")
            ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php");
        //设置默认字体和大小
        $objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', '宋体'));
        $objPHPExcel->getDefaultStyle()->getFont()->setSize($config['fontSize']);
        //设置栏目行高
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight($config['columnHeight']);
// Add some data
        foreach($column as $k => $v){
            //水平居中
            $objPHPExcel->getActiveSheet()->getStyle($v['column'].'1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //设置列宽
            $objPHPExcel->getActiveSheet()->getColumnDimension($v['column'])->setWidth($v['width']);
            //单元格
            $objPHPExcel->getActiveSheet()->setCellValue($v['column'].'1', $v['name']);
        }
        foreach($data as $key => $val){
            //设置内容行高
            $objPHPExcel->getActiveSheet()->getRowDimension($key+2)->setRowHeight($config['contentHeight']);
            foreach($val as $k => $v){
                $objPHPExcel->getActiveSheet()->setCellValue($column[$k]['column'].($key+2), $v);
            }
        }

// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($config['sheetName']);
        
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$config['fileName'].'.xls"');
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
