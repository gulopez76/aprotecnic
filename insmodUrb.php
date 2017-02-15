<?php
include_once('vtlib/Vtiger/Module.php');
$moduleInstance = Vtiger_Module::getInstance('Buildings');
$accountsModule = Vtiger_Module::getInstance('Urbanisme');
$relationLabel  = 'Urbanisme';
$moduleInstance->setRelatedList(
$accountsModule, $relationLabel, Array('ADD','SELECT')
);

