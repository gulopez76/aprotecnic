<?php
include_once('vtlib/Vtiger/Module.php');
$moduleInstance = Vtiger_Module::getInstance('Buildings');
$accountsModule = Vtiger_Module::getInstance('Technicalreports');
$relationLabel  = 'Technical Reports';
$moduleInstance->setRelatedList(
$accountsModule, $relationLabel, Array('ADD','SELECT')
);

