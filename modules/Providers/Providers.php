<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

include_once 'modules/Vtiger/CRMEntity.php';

class Providers extends Vtiger_CRMEntity {
	var $table_name = 'vtiger_providers';
	var $table_index= 'providersid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_providerscf', 'providersid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_providers', 'vtiger_providerscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_providers' => 'providersid',
		'vtiger_providerscf'=>'providersid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Name' => Array('providers', 'pname'),
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Name' => 'pname',
	);

	// Make the field link to detail view
	var $list_link_field = 'pname';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Name' => Array('providers', 'pname'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Name' => 'pname',
	);

	// For Popup window record selection
	var $popup_fields = Array ('pname');

	// For Alphabetical search
	var $def_basicsearch_col = 'pname';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'pname';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('pname');

	var $default_order_by = 'pname';
	var $default_sort_order='ASC';

        function Providers() {
                $this->log = LoggerManager::getLogger('providers');
                $this->log->debug("Entering Providers() method ...");
                $this->db = PearDatabase::getInstance();
                $this->column_fields = getColumnFields('Providers');
                $this->log->debug("Exiting Providers method ...");
        }

	/* Generic function to get attachments in the related list of a given module */

	function get_attachments($id, $cur_tab_id, $rel_tab_id, $actions = false) {

		global $currentModule, $app_strings, $singlepane_view;
		$this_module = $currentModule;
		$parenttab = getParentTab();

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		$other = CRMEntity::getInstance($related_module);

		// Some standard module class doesn't have required variables
		// that are used in the query, they are defined in this generic API
		vtlib_setup_modulevars($related_module, $other);

		$singular_modname = vtlib_toSingular($related_module);
		$button = '';
		if ($actions) {
			if (is_string($actions))
				$actions = explode(',', strtoupper($actions));
			if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
				$button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "'>&nbsp;";
			}
			if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes') {
				$button .= "<input type='hidden' name='createmode' id='createmode' value='link' />" .
						"<input title='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
						" value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "'>&nbsp;";
			}
		}

		// To make the edit or del link actions to return back to same view.
		if ($singlepane_view == 'true')
			$returnset = "&return_module=$this_module&return_action=DetailView&return_id=$id";
		else
			$returnset = "&return_module=$this_module&return_action=CallRelatedList&return_id=$id";

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'vtiger_users.first_name',
														'last_name' => 'vtiger_users.last_name'), 'Users');
		$query = "select case when (vtiger_users.user_name not like '') then $userNameSql else vtiger_groups.groupname end as user_name," .
				"'Documents' ActivityType,vtiger_attachments.type  FileType,crm2.modifiedtime lastmodified,vtiger_crmentity.modifiedtime,
				vtiger_seattachmentsrel.attachmentsid attachmentsid, vtiger_crmentity.smownerid smownerid, vtiger_notes.notesid crmid,
				vtiger_notes.notecontent description,vtiger_notes.*
				from vtiger_notes
				inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid
				left join vtiger_notescf ON vtiger_notescf.notesid= vtiger_notes.notesid
				inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_notes.notesid and vtiger_crmentity.deleted=0
				inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_senotesrel.crmid
				LEFT JOIN vtiger_groups
				ON vtiger_groups.groupid = vtiger_crmentity.smownerid
				left join vtiger_seattachmentsrel  on vtiger_seattachmentsrel.crmid =vtiger_notes.notesid
				left join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
				left join vtiger_users on vtiger_crmentity.smownerid= vtiger_users.id
				where crm2.crmid=" . $id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if ($return_value == null)
			$return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		return $return_value;
	}


        function get_contracts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
                global $log, $singlepane_view,$currentModule,$current_user;
                $log->debug("Entering get_contracts(".$id.") method ...");
                $this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
                require_once("modules/$related_module/$related_module.php");
                $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
                $singular_modname = vtlib_toSingular($related_module);

                $parenttab = getParentTab();

                if($singlepane_view == 'true')
                        $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
                else
                        $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

                $button = '';

                if($actions) {
                        if(is_string($actions)) $actions = explode(',', strtoupper($actions));
                        if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
                                $button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
                        }
                        if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                                        " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                                        " value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
                        }
                }

                $query = "SELECT vtiger_crmentity.*,
                                 vtiger_contracts.*
                          FROM   vtiger_contracts 
                          INNER JOIN vtiger_crmentity
                            ON   vtiger_crmentity.crmid = vtiger_contracts.contractsid
                          LEFT  JOIN vtiger_contractscf
                            ON   vtiger_contractscf.contractsid = vtiger_contracts.contractsid
                          WHERE  vtiger_crmentity.deleted = 0
                            AND  vtiger_contracts.conprovider = ".$id;

                $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

                if($return_value == null) $return_value = Array();
                $return_value['CUSTOM_BUTTON'] = $button;

                $log->debug("Exiting get_buildprojects method ...");
                return $return_value;
        }

        function get_providerreports($id, $cur_tab_id, $rel_tab_id, $actions=false) {
                global $log, $singlepane_view,$currentModule,$current_user;
                $log->debug("Entering get_providerreports(".$id.") method ...");
                $this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
                require_once("modules/$related_module/$related_module.php");
                $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
                $singular_modname = vtlib_toSingular($related_module);

                $parenttab = getParentTab();

                if($singlepane_view == 'true')
                        $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
                else
                        $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

                $button = '';

                if($actions) {
                        if(is_string($actions)) $actions = explode(',', strtoupper($actions));
                        if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
                                $button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
                        }
                        if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                                        " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                                        " value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
                        }
                }

                $query = "SELECT vtiger_crmentity.*,
                                 vtiger_providerreports.*
                          FROM   vtiger_providerreports 
                          INNER JOIN vtiger_crmentity
                            ON   vtiger_crmentity.crmid = vtiger_providerreports.providerreportsid
                          LEFT  JOIN vtiger_providerreportscf
                            ON   vtiger_providerreportscf.providerreportsid = vtiger_providerreports.providerreportsid
                          WHERE  vtiger_crmentity.deleted = 0
                            AND  vtiger_providerreports.prptprovider = ".$id;

                $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

                if($return_value == null) $return_value = Array();
                $return_value['CUSTOM_BUTTON'] = $button;

                $log->debug("Exiting get_budgets method ...");
                return $return_value;
        }

        function get_budgets($id, $cur_tab_id, $rel_tab_id, $actions=false) {
                global $log, $singlepane_view,$currentModule,$current_user;
                $log->debug("Entering get_budgets(".$id.") method ...");
                $this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
                require_once("modules/$related_module/$related_module.php");
                $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
                $singular_modname = vtlib_toSingular($related_module);

                $parenttab = getParentTab();

                if($singlepane_view == 'true')
                        $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
                else
                        $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

                $button = '';

                if($actions) {
                        if(is_string($actions)) $actions = explode(',', strtoupper($actions));
                        if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
                                $button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
                        }
                        if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                                        " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                                        " value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
                        }
                }

                $query = "SELECT vtiger_crmentity.*,
                                 vtiger_budgets.*
                          FROM   vtiger_budgets 
                          INNER JOIN vtiger_crmentity
                            ON   vtiger_crmentity.crmid = vtiger_budgets.budgetsid
                          LEFT  JOIN vtiger_budgetscf
                            ON   vtiger_budgetscf.budgetsid = vtiger_budgets.budgetsid
                          WHERE  vtiger_crmentity.deleted = 0
                            AND  vtiger_budgets.budprovider = ".$id;

                $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

                if($return_value == null) $return_value = Array();
                $return_value['CUSTOM_BUTTON'] = $button;

                $log->debug("Exiting get_budgets method ...");
                return $return_value;
        }

        function get_providerutils($id, $cur_tab_id, $rel_tab_id, $actions=false) {
                global $log, $singlepane_view,$currentModule,$current_user;
                $log->debug("Entering get_providerutils(".$id.") method ...");
                $this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
                require_once("modules/$related_module/$related_module.php");
                $other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
                $singular_modname = vtlib_toSingular($related_module);

                $parenttab = getParentTab();

                if($singlepane_view == 'true')
                        $returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
                else
                        $returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

                $button = '';

                if($actions) {
                        if(is_string($actions)) $actions = explode(',', strtoupper($actions));
                        if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
                                $button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
                        }
                        if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                                        " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                                        " value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
                        }
                }

                $query = "SELECT vtiger_crmentity.*,
                                 vtiger_providerutils.*
                          FROM   vtiger_providerutils 
                          INNER JOIN vtiger_crmentity
                            ON   vtiger_crmentity.crmid = vtiger_providerutils.providerutilsid
                          LEFT  JOIN vtiger_providerutilscf
                            ON   vtiger_providerutilscf.providerutilsid = vtiger_providerutils.providerutilsid
                          WHERE  vtiger_crmentity.deleted = 0
                            AND  vtiger_providerutils.putilsprovider = ".$id;

                $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

                if($return_value == null) $return_value = Array();
                $return_value['CUSTOM_BUTTON'] = $button;

                $log->debug("Exiting get_providerutils method ...");
                return $return_value;
        }


	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb;
 		if($eventType == 'module.postinstall') {
			// TODO Handle actions after this module is installed.
		} else if($eventType == 'module.disabled') {
			// TODO Handle actions before this module is being uninstalled.
		} else if($eventType == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
 	}
}
