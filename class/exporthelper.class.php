<?php

class ExporthelperClass
{
    // Whitelist of fields to export/print
    private $printFields;
    
    public function __construct($printFields) 
    {
        $this->printFields= $printFields;
    }
    
    public function processHeader($headerFields)
    {
        if (isset($this->printFields))
        {
            $retVal= array();
                
            foreach ($this->printFields as $pField)
            {
                if (strpos($pField, ":"))
                {
                    $labelName= substr($pField, strpos($pField, ":")+1);
                    array_push($retVal, $labelName);
                }
                else
                {
                    array_push($retVal, $pField);
                }
            }
        }
        else
        {
            $retVal= array();
                
            foreach ($headerFields as $key => $value)
            {
                array_push($retVal, $key);
            }
        }

        return $retVal;
    }
    
    public function processDataRow($dataRow)
    {
        if (isset($this->printFields))
        {
            $retVal= array();
                
            foreach ($this->printFields as $pField)
            {
                if (strpos($pField, ":"))
                {
                    $fieldList= substr($pField, 0, strpos($pField, ":"));
                    if (strpos($fieldList, "+"))
                    {
                        $parts= explode("+", $fieldList);
                        $myVal= "";
                        foreach( $parts as $p)
                        {
                            $newVal= $dataRow->$p;
                            if (isset($newVal))
                            {
                                if (strlen($myVal) > 0)
                                {
                                    $myVal.= " ". $newVal;
                                }
                                else
                                {
                                    $myVal= $newVal;
                                }
                            }
                        }
                        $myVal= trim($myVal);
                        if (strlen($myVal) > 0)
                        {
                            array_push($retVal, $myVal);
                        }
                    }
                    else
                    {
                        $myVal= trim($dataRow->$fieldList);
                        if (strlen($myVal) > 0)
                        {
                            array_push($retVal, $myVal);
                        }
                    }
                }
                else
                {
                    $myVal= trim($dataRow->$pField);
                    if (strlen($myVal) > 0)
                    {
                        array_push($retVal, $myVal);
                    }
                }
            }
        }
        else
        {
            $retVal= array();
                
            foreach ($dataRow as $key => $value)
            {
                array_push($retVal, $value);
            }
        }
        return $retVal;
    }
}

?>