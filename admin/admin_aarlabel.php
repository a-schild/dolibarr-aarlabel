<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 	\file		admin/aarlabel.php
 * 	\ingroup	aarlabel
 * 	\brief		This file is an example module setup page
 * 				Put some comments here
 */
// Dolibarr environment
$res = @include "../../main.inc.php"; // From htdocs directory
if (! $res) {
	$res = @include "../../../main.inc.php"; // From "custom" directory
}

global $langs, $user;

// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once '../lib/aarlabel.lib.php';
//require_once "../class/myclass.class.php";
// Translations
$langs->load("aarlabel@aarlabel");

// Access control
if (! $user->admin) {
	accessforbidden();
}

// Parameters
$action = GETPOST('action', 'alpha');

/*
 * Actions
 */
$MAX_AARLABELS_FORMATS= 7;


/*
 * View
 */
$page_name = "Aarlabel Setup";
llxHeader('', $langs->trans($page_name));

// Subheader
$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">'
	. $langs->trans("BackToModuleList") . '</a>';
print_fiche_titre($langs->trans($page_name), $linkback);

// Configuration header
$head = aarlabelAdminPrepareHead();
dol_fiche_head(
	$head,
	'settings',
	$langs->trans("Module112000Name"),
	0,
	"aarlabel@aarlabel"
);

// Setup page goes here
echo $langs->trans("AarlabelSetupPage");
$requestedAction= $_POST["action"];

for ($i = 1; $i < $MAX_AARLABELS_FORMATS ; $i++)
{
    if ($requestedAction == ('pdf'.$i.'name') && isset($_POST["value"])) {
            dolibarr_set_const($db, "AARLABEL_PDF_".$i."_NAME", $_POST["value"],'chaine',0,'',$conf->entity);
    }
    else if ($requestedAction == ('pdf'.$i.'format') && isset($_POST["value"])) {
            dolibarr_set_const($db, "AARLABEL_PDF_".$i."_FORMAT", $_POST["value"],'chaine',0,'',$conf->entity);
    }
    else if ($requestedAction == ('pdf'.$i.'watermark') && isset($_POST["value"])) {
            dolibarr_set_const($db, "AARLABEL_PDF_".$i."_WATERMARK", $_POST["value"],'chaine',0,'',$conf->entity);
    }
    elseif ($requestedAction == ('pdf'.$i.'paddingleft') && isset($_POST["value"])) {
            dolibarr_set_const($db, "AARLABEL_PDF_".$i."_PADDING_LEFT", $_POST["value"],'chaine',0,'',$conf->entity);
    }
    elseif ($requestedAction == ('pdf'.$i.'paddingtop') && isset($_POST["value"])) {
            dolibarr_set_const($db, "AARLABEL_PDF_".$i."_PADDING_TOP", $_POST["value"],'chaine',0,'',$conf->entity);
    }
    elseif ($requestedAction == ('pdf'.$i.'layout') && isset($_POST["value"])) {
            dolibarr_set_const($db, "AARLABEL_PDF_".$i."_LAYOUT", $_POST["value"],'chaine',0,'',$conf->entity);
    }
}

/*
 * Affiche page
 */

$html = new Form($db);
$var = true;
/*
 * Formulaire parametres divers
 */


$var=!$var;

for ($i = 1; $i < $MAX_AARLABELS_FORMATS ; $i++)
{

    print '<table class="noborder" width="100%">';
    print '<tr class="liste_titre">';
    print "<td>".$langs->trans("AarLabelsPDFFormats")." $i</td>\n";
    print '<td>'.$langs->trans("Value").'</td>';
    print '<td >'.$langs->trans("Action").'</td>';
    print "</tr>\n";

    //PDF Name
    print "<form method=\"post\" action=\"admin_aarlabel.php\">";
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print "<input type=\"hidden\" name=\"action\" value=\"pdf".$i."name\">";
    print "<tr ".$bc[$var].">";
    print '<td>Caption button</td>';
    print "<td ><input size=\"30\" type=\"text\" class=\"flat\" name=\"value\" value=\"";
    print dolibarr_get_const($db, "AARLABEL_PDF_".$i."_NAME");
    print "\"></td>";
    print '<td ><input type="submit" class="button" value="'.$langs->trans("Modify").'"></td>';
    print '</tr>';
    print '</form>';
    
    //PDF format
    print "<form method=\"post\" action=\"admin_aarlabel.php\">";
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print "<input type=\"hidden\" name=\"action\" value=\"pdf".$i."format\">";
    print "<tr ".$bc[$var].">";
    print '<td>Label format</td>';
    print "<td ><input size=\"8\" type=\"text\" class=\"flat\" name=\"value\" value=\"";
    print dolibarr_get_const($db, "AARLABEL_PDF_".$i."_FORMAT");
    print "\"></td>";
    print '<td ><input type="submit" class="button" value="'.$langs->trans("Modify").'"></td>';
    print '</tr>';
    print '</form>';

    
    //PDF Watermark
    print "<form method=\"post\" action=\"admin_aarlabel.php\">";
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print "<input type=\"hidden\" name=\"action\" value=\"pdf".$i."watermark\">";
    print "<tr ".$bc[$var].">";
    print '<td>PDF watermark file (Must be a pdf document)</td>';
    print "<td ><input size=\"30\" type=\"text\" class=\"flat\" name=\"value\" value=\"";
    print dolibarr_get_const($db, "AARLABEL_PDF_".$i."_WATERMARK");
    print "\"></td>";
    print '<td ><input type="submit" class="button" value="'.$langs->trans("Modify").'"></td>';
    print '</tr>';
    print '</form>';


    //PDF padding left
    print "<form method=\"post\" action=\"admin_aarlabel.php\">";
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print "<input type=\"hidden\" name=\"action\" value=\"pdf".$i."paddingleft\">";
    print "<tr ".$bc[$var].">";
    print '<td>PDF padding left on label in mm</td>';
    print "<td ><input size=\"30\" type=\"text\" class=\"flat\" name=\"value\" value=\"";
    print dolibarr_get_const($db, "AARLABEL_PDF_".$i."_PADDING_LEFT");
    print "\"></td>";
    print '<td ><input type="submit" class="button" value="'.$langs->trans("Modify").'"></td>';
    print '</tr>';
    print '</form>';
    
    //PDF padding top
    print "<form method=\"post\" action=\"admin_aarlabel.php\">";
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print "<input type=\"hidden\" name=\"action\" value=\"pdf".$i."paddingtop\">";
    print "<tr ".$bc[$var].">";
    print '<td>PDF padding top on label in mm</td>';
    print "<td ><input size=\"30\" type=\"text\" class=\"flat\" name=\"value\" value=\"";
    print dolibarr_get_const($db, "AARLABEL_PDF_".$i."_PADDING_TOP");
    print "\"></td>";
    print '<td ><input type="submit" class="button" value="'.$langs->trans("Modify").'"></td>';
    print '</tr>';
    print '</form>';

    //PDF Layout
    print "<form method=\"post\" action=\"admin_aarlabel.php\">";
    print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
    print "<input type=\"hidden\" name=\"action\" value=\"pdf".$i."layout\">";
    print "<tr ".$bc[$var].">";
    print '<td>PDF layout<br><br>';
    print 'Enter each line definition in a new line<br>';
    print 'Before the : enter the field(s) concated with +<br>the value after the : is not used for PDF exports<br>';
    print '<br>Default value when empty:';
    print '<pre>name:Name<br>';
    print 'address<br>';
    print 'zip+town:PLZ Ort</pre><br>';
    print '</td>';
    print "<td ><textarea class=\"flat\" name=\"value\" cols=\"80\" rows=\"10\" wrap=\"hard\">";
    print dolibarr_get_const($db, "AARLABEL_PDF_".$i."_LAYOUT");
    print "</textarea></td>";
    print '<td ><input type="submit" class="button" value="'.$langs->trans("Modify").'"></td>';
    print '</tr>';
    print '</form>';
    
    print '</table>';
}


print '<br>';
print 'Supported formats are:';
print '<ul>';
print '<li>Avery 3422</li>';
print '<li>Avery 3424</li>';
print '<li>Avery 3651</li>';
print '<li>Avery 5160</li>';
print '<li>Avery 5161</li>';
print '<li>Avery 5162</li>';
print '<li>Avery 5163</li>';
print '<li>Avery 5164</li>';
print '<li>Avery L7163</li>';
print '<li>Avery 8600</li>';
print '</ul>';
print "Leave the format field empty if you don't wish to use that PDF format<br>";
// Page end
dol_fiche_end();
llxFooter();
