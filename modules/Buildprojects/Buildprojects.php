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

class Buildprojects extends Vtiger_CRMEntity {
	var $table_name = 'vtiger_buildprojects';
	var $table_index= 'buildprojectsid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_buildprojectscf', 'buildprojectsid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_buildprojects', 'vtiger_buildprojectscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_buildprojects' => 'buildprojectsid',
		'vtiger_buildprojectscf'=>'buildprojectsid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Name' => Array('buildprojects', 'bpname'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Name' => 'bpname',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'bpname';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Name' => Array('buildprojects', 'bpname'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Name' => 'bpname',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('bpname');

	// For Alphabetical search
	var $def_basicsearch_col = 'bpname';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'bpname';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('bpname','assigned_user_id');

	var $default_order_by = 'bpname';
	var $default_sort_order='ASC';

        function Buildprojects() {
                $this->log = LoggerManager::getLogger('buildprojects');
                $this->log->debug("Entering Buildprojects() method ...");
                $this->db = PearDatabase::getInstance();
                $this->column_fields = getColumnFields('Buildprojects');
                $this->log->debug("Exiting Buildprojects method ...");
        }

        function get_bulletins($id, $cur_tab_id, $rel_tab_id, $actions=false) {
                global $log, $singlepane_view,$currentModule,$current_user;
                $log->debug("Entering get_bulletins(".$id.") method ...");
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
                                 vtiger_bulletins.*,
                                 vtiger_bulletinscf.*
                          FROM   vtiger_bulletins 
                          INNER JOIN vtiger_crmentity
                            ON   vtiger_crmentity.crmid = vtiger_bulletins.bulletinsid
                          LEFT  JOIN vtiger_bulletinscf
                            ON   vtiger_bulletinscf.bulletinsid = vtiger_bulletins.bulletinsid
                          WHERE  vtiger_crmentity.deleted = 0
                            AND  vtiger_bulletinscf.bbuildproject = ".$id;

                $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

                if($return_value == null) $return_value = Array();
                $return_value['CUSTOM_BUTTON'] = $button;

                $log->debug("Exiting get_bulletins method ...");
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
