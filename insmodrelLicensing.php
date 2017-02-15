<?php
include_once('vtlib/Vtiger/Module.php');
$moduleInstance = Vtiger_Module::getInstance('Buildprojects');
$accountsModule = Vtiger_Module::getInstance('Bulletins');
$relationLabel  = 'Bulletins';
$moduleInstance->setRelatedList(
$accountsModule, $relationLabel, Array('ADD','SELECT')
);

