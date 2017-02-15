<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$moduleinstance = Vtiger_Module::getInstance('Licensing');

        // Blocks Setup
        $block = Vtiger_Block::getInstance('GENERAL INFORMATION', $moduleinstance);

        // Field Setup

        $field4  = new Vtiger_Field();
        $field4->name = 'ldocument';
        $field4->table = 'vtiger_licensing';
        $field4->label= 'Document';
        $field4->uitype= 10;
        $field4->column = $field4->name;
        $field4->columntype = 'INT(19)';
        $field4->typeofdata = 'I~O';
        $block->addField($field4);
        $field4->setRelatedModules(Array('Documents'));

?>
