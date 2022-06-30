<?php
require_once "../lib/IOutputdocument.php";
//require_once DOL_DOCUMENT_ROOT . "/includes/phpoffice/phpexcel/Classes/PHPExcel.php";
//require_once DOL_DOCUMENT_ROOT . "/includes/phpoffice/phpspreadsheet/src/autoloader.php";
require_once DOL_DOCUMENT_ROOT . "/core/modules/export/export_excel2007.modules.php";

class PHPExceloutput implements Outputdocument
{
    private $spreadsheet;
    private $currRow= 1;
    private $fileName= "output.xlsx";

    public function getContent()
    {
        //$objWriter = new ExportExcel2007($this->spreadsheet);
        
        // We'll be outputting an excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // It will be called file.xls
        header('Content-Disposition: attachment; filename="'.$this->fileName.'"');
        header('Cache-Control: max-age=0');
        
        // Write file to the browser
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spreadsheet);
        //$writer->save('hello world.xlsx');        
        $writer->save('php://output');
        // $this->SaveViaTempFile($objWriter);
    }
    
    public function startDocument()
    {
		require_once DOL_DOCUMENT_ROOT.'/includes/phpoffice/phpspreadsheet/src/autoloader.php';
		require_once DOL_DOCUMENT_ROOT.'/includes/Psr/autoloader.php';
		require_once PHPEXCELNEW_PATH.'Spreadsheet.php';
		require_once PHPEXCELNEW_PATH.'Writer/Xlsx.php';
        
        // PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        $this->spreadsheet= new PhpOffice\PhpSpreadsheet\Spreadsheet(); // new PHPExcel();
        $this->spreadsheet->setActiveSheetIndex(0);
        $this->spreadsheet->getProperties()->setCreator("Aarboard");
        $this->spreadsheet->getProperties()->setLastModifiedBy("Aarboard Dolibarr");
        $this->spreadsheet->getProperties()->setTitle("Export labels");
        //$this->spreadsheet->getProperties()->setSubject("Office 2007 XLSX Test Document");
        //$this->spreadsheet->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
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
            $this->spreadsheet->getActiveSheet()->SetCellValue($col++.$this->currRow, $key);
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
                $this->spreadsheet->getActiveSheet()->SetCellValue($col++.$this->currRow, $f);
            }
        }
        $this->currRow++;
    }
        
    
}