<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$moduleinstance = Vtiger_Module::getInstance('Buildings');

        // Blocks Setup
        $block = Vtiger_Block::getInstance('GENERAL INFORMATION', $moduleinstance);

        // Field Setup

        $field4  = new Vtiger_Field();
        $field4->name = 'bdhelaty';
        $field4->table = 'vtiger_buildings';
        $field4->label= 'Document';
        $field4->uitype= 10;
        $field4->column = $field4->name;
        $field4->columntype = 'VARCHAR(25)';
        $field4->typeofdata = 'V~O';
        $block->addField($field4);
        $field4->setRelatedModules(Array('Documents'));

?>
