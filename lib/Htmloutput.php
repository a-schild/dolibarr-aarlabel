<?php
require_once "../lib/IOutputdocument.php";

class Htmloutput implements Outputdocument
{
    private $htmlContent= "";

    public function getContent()
    {
        
        header('Content-Type: text/html; charset=utf-8');
        // It will be called file.xls
        // header('Content-Disposition: attachment; filename="'.$this->fileName.'"');
        header('Cache-Control: max-age=0');
        echo $this->htmlContent;
    }
    
    public function getMimeType()
    {
        return "text/html";
    }
    
    public function startDocument()
    {
        $this->htmlContent= $this->htmlContent . "<html>\n <body>\n  <table>\n";
    }
    public function endDocument()
    {
        $this->htmlContent= $this->htmlContent . "  </table>\n </body>\n</html>\n";
    }
    
    public function writeHeader($headerNames)
    {
        $this->htmlContent= $this->htmlContent . "   <tr>\n";
        foreach ($headerNames as $key )
        {
            $this->htmlContent=  $this->htmlContent . "    <th>" . $key . "</th>\n";
        }
        $this->htmlContent= $this->htmlContent . "   </tr>\n";
    }
    
    public function writeData($dataValues)
    {
        $this->htmlContent= $this->htmlContent . "   <tr>";
        foreach ($dataValues as $f)
        {
            if (is_null($f))
            {
                $this->htmlContent= $this->htmlContent . "<td></td>";
            }
            else
            {
                $this->htmlContent= $this->htmlContent . "<td>" . $f . "</td>";
            }
        }
        $this->htmlContent= $this->htmlContent . "</tr>\n";
    }
        
    
}