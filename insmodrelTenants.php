<?php
include_once('vtlib/Vtiger/Module.php');
$moduleInstance = Vtiger_Module::getInstance('Buildprojects');
$accountsModule = Vtiger_Module::getInstance('Tenants');
$relationLabel  = 'Tenants';
$moduleInstance->setRelatedList(
$accountsModule, $relationLabel, Array('ADD','SELECT')
);

