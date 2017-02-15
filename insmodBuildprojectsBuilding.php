<?php
include_once('vtlib/Vtiger/Module.php');
$moduleInstance = Vtiger_Module::getInstance('Buildings');
$accountsModule = Vtiger_Module::getInstance('Buildprojects');
$relationLabel  = 'Building Projects';
$moduleInstance->setRelatedList(
$accountsModule, $relationLabel, Array('ADD','SELECT')
);

