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
 * \file    class/actions_aarlabel.class.php
 * \ingroup aarlabel
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

/**
 * Class ActionsAarlabel
 */
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
class ActionsAarlabel
{
        private $MAX_AARLABELS_FORMATS= 7;
        
	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	public function printFieldListFooter($parameters, &$object, &$action, $hookmanager)
	{
            global $db;
            // Access control
            //if ($user->rights->aarlabel) 
            {
		$myvalue = 'test'; // A result value
                $exportKey= uniqid("aarlabel_");
                $_SESSION[$exportKey]= base64_encode($parameters["sql"]);
                $urlPart1= DOL_URL_ROOT."/custom/aarlabel/lib/export.php?exportKey=".$exportKey;
                dol_syslog("Hook in doActions called with " . var_export($object, true) . " and ". var_export($action, true) . " and ". var_export($parameters, true), LOG_WARNING);
		echo "Export als <a href='$urlPart1&type=xlsx&context=$action' target='_blank' class='butAction'>Excel</a>";

                for ($i = 1; $i < $this->MAX_AARLABELS_FORMATS ; $i++)
                {
                    $pdfFormat= dolibarr_get_const($db, "AARLABEL_PDF_".$i."_FORMAT");
                    if (isset($pdfFormat) && strlen($pdfFormat) > 0)
                    {
                        $buttonCaption= dolibarr_get_const($db, "AARLABEL_PDF_".$i."_NAME");
                        if (!isset($buttonCaption) || strlen($buttonCaption) ==0)
                        {
                            $buttonCaption= $pdfFormat;
                        }
                        echo "<a href='$urlPart1&type=labels&context=$action&format=$i' target='_blank' class='butAction'>$buttonCaption</a>";
                    }
                }
		echo "<a href='$urlPart1&type=list&context=$action' target='_blank' class='butAction'>Liste</a>";
            }
            return 0; // Return OK
	}
        
	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	function printFieldListSelect($parameters, &$object, &$action, $hookmanager)
	{
		$error = 0; // Error counter
		$myvalue = 'OK'; // A result value
 
//		print_r($parameters);
//		echo "action: " . $action;
//		print_r($object);
 
		if (in_array('somecontext', explode(':', $parameters['context'])))
		{
		  // do something only for the context 'somecontext'
		}
 
		if (! $error)
		{
			$this->results = array('myreturn' => $myvalue);
                        if ($this->endswith($parameters['context'], "memberlist:main"))
                        {
                            $this->resprints = ', civility, (select label from llx_c_civility where code=civility) as civility_label';
                            $this->resprints .= ", (select concat(left(code,1),'-') from llx_c_country where rowid=country and country != 6) as country_code";
                        }
			return 0; // or return 1 to replace standard code
		}
		else
		{
			$this->errors[] = 'Error message';
			return -1;
		}
	}        
        
    function endswith($string, $test) {
        $strlen = strlen($string);
        $testlen = strlen($test);
        if ($testlen > $strlen) return false;
        return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
    }

}
