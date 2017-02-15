<?php
include_once('vtlib/Vtiger/Module.php');
$moduleInstance = Vtiger_Module::getInstance('Buildprojects');
$accountsModule = Vtiger_Module::getInstance('Documents');
$relationLabel  = 'Technical Reports';
$moduleInstance->setRelatedList(
$accountsModule, $relationLabel, Array('ADD','SELECT'), 'get_attachments'
);

