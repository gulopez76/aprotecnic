<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$moduleinstance = Vtiger_Module::getInstance('Bulletins');

        // Blocks Setup
        $block = Vtiger_Block::getInstance('GENERAL INFORMATION', $moduleinstance);

        $f1 = Vtiger_Field::getInstance('lbuildproject', $moduleinstance);
        $f1->delete();

?>

