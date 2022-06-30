<?php
$res = @include "../../main.inc.php"; // From htdocs directory
if (! $res) {
	$res = @include "../../../main.inc.php"; // From "custom" directory
}

global $langs, $user, $db;

// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once DOL_DOCUMENT_ROOT . "/includes/phpoffice/phpspreadsheet/src/autoloader.php";
require_once  '../lib/aarlabel.lib.php';
require_once  '../lib/xlsxwriter.class.php';
require_once  "../lib/Htmloutput.php";
require_once  "../lib/PHPExceloutput.php";
require_once  "../lib/Labelsoutput.php";
require_once  "../class/exporthelper.class.php";
// Translations
$langs->load("aarlabel@aarlabel");

// Access control
if (! ($user->rights->aarlabel)) {
	accessforbidden();
}

$printFields;

$export_xlsx= isset($_REQUEST["type"]) && $_REQUEST["type"] == 'xlsx';
$export_labels= isset($_REQUEST["type"]) && $_REQUEST["type"] == 'labels';
$export_labels_format= $_REQUEST["format"];
$export_list= isset($_REQUEST["type"]) && $_REQUEST["type"] == 'list';
$exportKey= $_REQUEST["exportKey"];
$export_stmt= base64_decode($_SESSION[$exportKey]);
if ($export_xlsx)
{
    $outDoc= new PHPExceloutput();
}
else if ($export_labels)
{
    $printFieldsLayout= dolibarr_get_const($db, "AARLABEL_PDF_".$export_labels_format."_LAYOUT");
    if (!isset($printFieldsLayout) || strlen($printFieldsLayout) == 0)
    {
        $printFields= array("name:Name", "address", "zip+town:PLZ Ort");
    }
    else
    {
        $printFields= explode("\n", $printFieldsLayout);
    }
    
    $outDoc= new Labelsoutput($export_labels_format);
}
else if ($export_list)
{
    // $printFields= array("name:Name", "zip+town:PLZ Ort");
    // $printFields= array("name", "zip", "town");
    $outDoc= new Htmloutput();
}
else
{
    $outDoc= new Htmloutput();
    dol_syslog("Aarlabel export request, unknown format", LOG_WARNING);
}

$exportHelper= new ExporthelperClass($printFields);

if (strrpos($export_stmt, "LIMIT") !== false)
{
    $export_stmt= substr($export_stmt, 0, strrpos($export_stmt, "LIMIT"));
}

dol_syslog("SQL statement for label building: ".$export_stmt, LOG_DEBUG);

$notDone= 1;
$resql=$db->query($export_stmt);
if ($resql)
{
	$num = $db->num_rows($resql);
	$i = 0;
        $header_written= false;
	if ($num)
	{
            //echo "Anzahl Datensaetze: " . $num;
            $outDoc->startDocument();
            while ($i < $num)
            {
                    $data = $db->fetch_object($resql);
                    if ($data)
                    {
                        if (!$header_written)
                        {
                            $outDoc->writeHeader($exportHelper->processHeader($data));
                            $header_written= true;
                        }
                        $outDoc->writeData($exportHelper->processDataRow($data));
                        $i++;
                    }
            }
            $outDoc->endDocument();
            $notDone= 0;
	}
}
if (!$notDone)
{
    echo $outDoc->getContent();
}
else
{
?>
<!--
<h1>Exported</h1>
<ul>
<?php if ($export_xlsx) { ?>
    <li>export_xlsx</li>
<?php } ?>
<?php if ($export_labels) { ?>
    <li>export_labels</li>
<?php } ?>
    <li>context<br />
<?php 
    echo $_REQUEST["context"];
?>    
    </li>
    <li>export_src<br />
<?php 
    echo $export_stmt;
?>    
    </li>
</ul>

-->
<h1>Keine Daten zum exportieren gefunden</h1>
<?php 
    }
?>