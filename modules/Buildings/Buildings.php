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

class Buildings extends Vtiger_CRMEntity {
	var $table_name = 'vtiger_buildings';
	var $table_index= 'buildingsid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_buildingscf', 'buildingsid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_buildings', 'vtiger_buildingscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_buildings' => 'buildingsid',
		'vtiger_buildingscf'=>'buildingsid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Name' => Array('buildings', 'bdname'),
	);
	var $list_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Name' => 'bdname',
	);

	// Make the field link to detail view
	var $list_link_field = 'bdname';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
		// tablename should not have prefix 'vtiger_'
		'Name' => Array('buildings', 'bdname'),
	);
	var $search_fields_name = Array (
		/* Format: Field Label => fieldname */
		'Name' => 'bdname',
	);

	// For Popup window record selection
	var $popup_fields = Array ('bdname');

	// For Alphabetical search
	var $def_basicsearch_col = 'bdname';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'bdname';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('bdname');

	var $default_order_by = 'bdname';
	var $default_sort_order='ASC';

        function Buildings() {
                $this->log = LoggerManager::getLogger('buildings');
                $this->log->debug("Entering Buildings() method ...");
                $this->db = PearDatabase::getInstance();
                $this->column_fields = getColumnFields('Buildings');
                $this->log->debug("Exiting Buildings method ...");
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

        function insertIntoAttachment($id,$module)
        {
                global  $log,$adb;
                $log->debug("Entering into insertIntoAttachment($id,$module) method.");

                $file_saved = false;
                foreach($_FILES as $fileindex => $files)
                {
                        if($files['name'] != '' && $files['size'] > 0)
                        {
                              if($_REQUEST[$fileindex.'_hidden'] != '')
                                      $files['original_name'] = vtlib_purify($_REQUEST[$fileindex.'_hidden']);
                              else
                                      $files['original_name'] = stripslashes($files['name']);
                              $files['original_name'] = str_replace('"','',$files['original_name']);
                                $file_saved = $this->uploadAndSaveFile($id,$module,$files);
                        }
                }

                //Updating image information in main table of products
                $existingImageSql = 'SELECT name FROM vtiger_seattachmentsrel INNER JOIN vtiger_attachments ON
                                                                vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid LEFT JOIN vtiger_products ON
                                                                vtiger_products.productid = vtiger_seattachmentsrel.crmid WHERE vtiger_seattachmentsrel.crmid = ?';
                $existingImages = $adb->pquery($existingImageSql,array($id));
                $numOfRows = $adb->num_rows($existingImages);
                $productImageMap = array();

                for ($i = 0; $i < $numOfRows; $i++) {
                        $imageName = $adb->query_result($existingImages, $i, "name");
                        array_push($productImageMap, decode_html($imageName));
                }
                $commaSeperatedFileNames = implode(",", $productImageMap);

                $adb->pquery('UPDATE vtiger_products SET imagename = ? WHERE productid = ?',array($commaSeperatedFileNames,$id));

                //Remove the deleted vtiger_attachments from db - Products
                if($module == 'Products' && $_REQUEST['del_file_list'] != '')
                {
                        $del_file_list = explode("###",trim($_REQUEST['del_file_list'],"###"));
                        foreach($del_file_list as $del_file_name)
                        {
                                $attach_res = $adb->pquery("select vtiger_attachments.attachmentsid from vtiger_attachments inner join vtiger_seattachmentsrel on vtiger_attachments.attachmentsid=vtiger_seattachmentsrel.attachmentsid where crmid=? and name=?", array($id,$del_file_name));
                                $attachments_id = $adb->query_result($attach_res,0,'attachmentsid');

                                $del_res1 = $adb->pquery("delete from vtiger_attachments where attachmentsid=?", array($attachments_id));
                                $del_res2 = $adb->pquery("delete from vtiger_seattachmentsrel where attachmentsid=?", array($attachments_id));
                        }
                }

                $log->debug("Exiting from insertIntoAttachment($id,$module) method.");
        }


        function get_ocas($id, $cur_tab_id, $rel_tab_id, $actions=false) {
                global $log, $singlepane_view,$currentModule,$current_user;
                $log->debug("Entering get_ocas(".$id.") method ...");
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
                                 vtiger_ocas.*
                          FROM   vtiger_ocas 
                          INNER JOIN vtiger_crmentity
                            ON   vtiger_crmentity.crmid = vtiger_ocas.ocasid
                          LEFT  JOIN vtiger_ocascf
                            ON   vtiger_ocascf.ocasid = vtiger_ocas.ocasid
                          WHERE  vtiger_crmentity.deleted = 0
                            AND  vtiger_ocas.ocasbuilding = ".$id;

                $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

                if($return_value == null) $return_value = Array();
                $return_value['CUSTOM_BUTTON'] = $button;

                $log->debug("Exiting get_ocas method ...");
                return $return_value;
        }

        function get_urbanisme($id, $cur_tab_id, $rel_tab_id, $actions=false) {
                global $log, $singlepane_view,$currentModule,$current_user;
                $log->debug("Entering get_urbanisme(".$id.") method ...");
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
                                 vtiger_urbanisme.*
                          FROM   vtiger_urbanisme 
                          INNER JOIN vtiger_crmentity
                            ON   vtiger_crmentity.crmid = vtiger_urbanisme.urbanismeid
                          LEFT  JOIN vtiger_urbanismecf
                            ON   vtiger_urbanismecf.urbanismeid = vtiger_urbanisme.urbanismeid
                          WHERE  vtiger_crmentity.deleted = 0
                            AND  vtiger_urbanisme.urbbuilding = ".$id;

                $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

                if($return_value == null) $return_value = Array();
                $return_value['CUSTOM_BUTTON'] = $button;

                $log->debug("Exiting get_urbanisme method ...");
                return $return_value;
        }

        function get_technicalreports($id, $cur_tab_id, $rel_tab_id, $actions=false) {
                global $log, $singlepane_view,$currentModule,$current_user;
                $log->debug("Entering get_technicalreports(".$id.") method ...");
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
                                 vtiger_technicalreports.*
                          FROM   vtiger_technicalreports 
                          INNER JOIN vtiger_crmentity
                            ON   vtiger_crmentity.crmid = vtiger_technicalreports.technicalreportsid
                          LEFT  JOIN vtiger_technicalreportscf
                            ON   vtiger_technicalreportscf.technicalreportsid = vtiger_technicalreports.technicalreportsid
                          WHERE  vtiger_crmentity.deleted = 0
                            AND  vtiger_technicalreports.tibuilding = ".$id;

                $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

                if($return_value == null) $return_value = Array();
                $return_value['CUSTOM_BUTTON'] = $button;

                $log->debug("Exiting get_technicalreports method ...");
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
