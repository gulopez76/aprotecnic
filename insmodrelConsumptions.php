<?php
include_once('vtlib/Vtiger/Module.php');
$moduleInstance = Vtiger_Module::getInstance('Providerutils');
$accountsModule = Vtiger_Module::getInstance('Documents');
$relationLabel  = 'Consumptions';
$moduleInstance->setRelatedList(
$accountsModule, $relationLabel, Array('ADD','SELECT','get_attachments')
);

