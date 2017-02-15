<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$moduleinstance = Vtiger_Module::getInstance('Buildprojects');

        // Blocks Setup
        $block = Vtiger_Block::getInstance('GENERAL INFORMATION', $moduleinstance);

        // Field Setup

        $field4  = new Vtiger_Field();
        $field4->name = 'bpbuilding';
        $field4->table = 'vtiger_buildprojects';
        $field4->label= 'Building';
        $field4->uitype= 10;
        $field4->column = $field4->name;
        $field4->columntype = 'VARCHAR(50)';
        $field4->typeofdata = 'V~O';
        $block->addField($field4);
        $field4->setRelatedModules(Array('Buildings'));

?>
