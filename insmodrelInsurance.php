<?php
include_once('vtlib/Vtiger/Module.php');
$moduleInstance = Vtiger_Module::getInstance('Estate');
$accountsModule = Vtiger_Module::getInstance('Documents');
$relationLabel  = 'Insurance';
$moduleInstance->setRelatedList(
$accountsModule, $relationLabel, Array('ADD','SELECT')
);

