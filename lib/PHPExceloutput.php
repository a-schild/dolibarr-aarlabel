<?php
require_once "../lib/IOutputdocument.php";
		require_once DOL_DOCUMENT_ROOT . '/includes/phpoffice/autoloader.php';
		require_once DOL_DOCUMENT_ROOT . '/includes/Psr/autoloader.php';
		require_once PHPEXCELNEW_PATH . 'Spreadsheet.php'; 

class PHPExceloutput implements Outputdocument
{
    private $objPHPExcel;
    private $currRow= 1;
    private $fileName= "output.xlsx";

    public function getContent()
    {
		 set_time_limit ( 120 );
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->objPHPExcel, "Xlsx");
        
        // We'll be outputting an excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // It will be called file.xls
        header('Content-Disposition: attachment; filename="'.$this->fileName.'"');
        header('Cache-Control: max-age=0');
        
        // Write file to the browser
        $objWriter->save('php://output');
        // $this->SaveViaTempFile($objWriter);
    }
    
    public function startDocument()
    {
        // PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        $this->objPHPExcel= new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->objPHPExcel->getProperties()->setCreator("Aarboard");
        $this->objPHPExcel->getProperties()->setLastModifiedBy("Aarboard Dolibarr");
        $this->objPHPExcel->getProperties()->setTitle("Export labels");
        //$this->objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
        //$this->objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
    }
    
    static function SaveViaTempFile($objWriter)
    {
        $filePath = sys_get_temp_dir() . "/" . rand(0, getrandmax()) . rand(0, getrandmax()) . ".tmp";
        dol_syslog("Temp path for excel file: " . $filePath, LOG_WARNING);
        $objWriter->save($filePath);
        readfile($filePath);
        unlink($filePath);
    }
    public function endDocument()
    {

    }
    
    public function writeHeader($headerNames)
    {
        $col= 'A';
        foreach ($headerNames as $key)
        {
            $this->objPHPExcel->getActiveSheet()->SetCellValue($col++.$this->currRow, $key);
        }
        $this->currRow++;
    }
    
    public function writeData($dataValues)
    {
        $col= 'A';
        foreach ($dataValues as $f)
        {
            if (is_null($f))
            {
                $col++;
            }
            else
            {
                $this->objPHPExcel->getActiveSheet()->SetCellValue($col++.$this->currRow, $f);
            }
        }
        $this->currRow++;
    }
        
    
}