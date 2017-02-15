<?php
include_once('vtlib/Vtiger/Module.php');
$moduleInstance = Vtiger_Module::getInstance('Buildings');
$accountsModule = Vtiger_Module::getInstance('Ocas');
$relationLabel  = 'OCAs';
$moduleInstance->setRelatedList(
$accountsModule, $relationLabel, Array('ADD','SELECT')
);

