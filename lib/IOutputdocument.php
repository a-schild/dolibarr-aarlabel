<?php

// Declare the interface 'iTemplate'
interface Outputdocument
{
    public function startDocument();
    public function endDocument();
    
    public function writeHeader($headerNames);
    
    public function writeData($dataValues);
    
    public function getContent();
    
}
?>