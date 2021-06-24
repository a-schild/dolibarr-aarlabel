<?php
require_once "../lib/IOutputdocument.php";
require_once("PDF_Label.php");
require_once DOL_DOCUMENT_ROOT . "/includes/tecnickcom/tcpdf/tcpdf.php";

class Labelsoutput implements Outputdocument
{
    private $pdf;
    private $labelFormat= '3651';
    private $waterMark= null;
    private $paddingX;
    private $paddingY;
    private $labelLayout;
    
    private $fileName= "Labels.pdf";
    
    private $exportFormat;
    public function __construct($exportFormat)
    {
        global $db;
        $this->exportFormat= $exportFormat;
        $this->labelFormat= dolibarr_get_const($db, "AARLABEL_PDF_".$exportFormat."_FORMAT");
        $this->waterMark= dolibarr_get_const($db, "AARLABEL_PDF_".$exportFormat."_WATERMARK");
        $this->paddingX= dolibarr_get_const($db, "AARLABEL_PDF_".$exportFormat."_PADDING_LEFT");
        $this->paddingY= dolibarr_get_const($db, "AARLABEL_PDF_".$exportFormat."_PADDING_TOP");
        $this->labelLayout = dolibarr_get_const($db, "AARLABEL_PDF_".$exportFormat."_LAYOUT");
        if ($this->labelLayout == null)
        {
            $this->labelLayout= "name:Name\naddress\nzip+town:PLZ Ort";
        }
        dol_syslog("PDF Label generation format <".$this->labelFormat."> watermark <".$this->waterMark.">");
    }

    public function getContent()
    {
        return $this->pdf->Output($this->fileName, 'I');
    }
    
    public function startDocument()
    {
        // Example of custom format
        // $pdf = new PDF_Label(array('paper-size'=>'A4', 'metric'=>'mm', 'marginLeft'=>1, 'marginTop'=>1, 'NX'=>2, 'NY'=>7, 'SpaceX'=>0, 'SpaceY'=>0, 'width'=>99, 'height'=>38, 'font-size'=>14));

        $pdfTemplate= NULL;
        if (strlen($this->waterMark) > 0)
        {
            $pdfTemplate= DOL_DATA_ROOT . '/aarlabel/' .$this->waterMark;
            if (!is_readable($pdfTemplate))
            {
                dol_syslog("Missing aarlabel template file <".$pdfTemplate.">", LOG_ERR);
                $pdfTemplate= null;
            }
            else
            {
                dol_syslog("Found aarlabel template file <".$pdfTemplate.">", LOG_DEBUG);
            }
        }
        else
        {
            dol_syslog("No aarlabel template defined", LOG_DEBUG );
        }
        // Standard format
        $this->pdf = new PDF_Label($this->labelFormat, 'mm', 1, 1, $pdfTemplate);
        
        $this->fileName= "Labels-".$this->labelFormat.".pdf";
        // set document information
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor('Dolibarr Aarboard');
        $this->pdf->SetTitle('AarLabel');
        $this->pdf->setPaddings($this->paddingX, $this->paddingY);
        $this->pdf->AddPage();        
    }
    public function endDocument()
    {
        $this->pdf->Close();
    }
    
    public function writeHeader($headerNames)
    {
    }
    
    public function writeData($dataValues)
    {
        $text= "";
        foreach ($dataValues as $f)
        {
            if (is_null($f))
            {
                // $this->htmlContent= $this->htmlContent . "<td></td>";
            }
            else
            {
                if (strlen($text) > 0)
                {
                    $text.= "\n";
                    // $text.= '<br>';
                }
                $text.= $f;
            }
        }
        $this->pdf->Add_Label($text);
    }
        
    
}