<?php
include_once('vtlib/Vtiger/Module.php');
$moduleInstance = Vtiger_Module::getInstance('Providers');
$accountsModule = Vtiger_Module::getInstance('Budgets');
$relationLabel  = 'Budgets';
$moduleInstance->setRelatedList(
$accountsModule, $relationLabel, Array('ADD','SELECT')
);

