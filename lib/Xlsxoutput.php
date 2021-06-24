<?php
require_once "../lib/IOutputdocument.php";
require_once("xlsxwriter.class.php");

class Xlsxoutput implements Outputdocument
{
    private $writer;
    private $fileName= "output.xlsx";

    public function getContent()
    {
        return $this->fileName;
    }
    
    public function getMimeType()
    {
        return "text/html";
    }
    
    public function startDocument()
    {
        $this->writer= new XLSXWriter();
        $this->writer->setAuthor('Dolibarr by Aarboard');
    }
    public function endDocument()
    {
        $this->writer->writeToFile($this->fileName);
    }
    
    public function writeHeader($headerNames)
    {
        $headers = array();
    
        foreach ($headerNames as $key)
        {
            array_push($headers, $key);
        }
        $this->writer->writeSheetHeader("Export", $headers);
    }
    
    public function writeData($dataValues)
    {
        $dValues = array();
        foreach ($dataValues as $f)
        {
            if (is_null($f))
            {
                array_push($dValues, '');
            }
            else
            {
                array_push($dValues, $f);
            }
        }
        $this->writer->writeSheetRow('Export', $dValues );
    }
        
    
}