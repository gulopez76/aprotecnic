<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'Providers';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if ($moduleInstance || file_exists('modules/'.$MODULENAME)) {
        echo "Module already present - choose a different name.";
} else {
        $moduleInstance = new Vtiger_Module();
        $moduleInstance->name = $MODULENAME;
        $moduleInstance->save();

        // Schema Setup : This create three tables with modulename, modulenamecf and modulenamegroupdel
        $moduleInstance->initTables();

        $menuInstance = Vtiger_Menu::getInstance('Tools');
        $menuInstance->addModule($moduleInstance);

        // Blocks Setup
        $block = new Vtiger_Block();
        $block->label = 'GENERAL INFORMATION';
        $moduleInstance->addBlock($block);

        $blockcf = new Vtiger_Block();
        $blockcf->label = 'LBL_CUSTOM_INFORMATION';
        $moduleInstance->addBlock($blockcf);

        // Field Setup
        $field1  = new Vtiger_Field();
        $field1->name = 'lname';
        $field1->table = 'vtiger_providers';
        $field1->label= 'Name';
        $field1->uitype= 2;
        $field1->column = $field1->name;
        $field1->columntype = 'VARCHAR(50)';
        $field1->typeofdata = 'V~M';
        $block->addField($field1);

        $moduleInstance->setEntityIdentifier($field1);


        //Filter Setup
        //Filter Setup
        $filter1 = new Vtiger_Filter();
        $filter1->name = 'All';
        $filter1->isdefault = true;
        $moduleInstance->addFilter($filter1);
        $filter1->addField($field1);

        // Sharing Access Setup
        $moduleInstance->setDefaultSharing('Public_ReadWriteDelete');

        // Webservice Setup
        $moduleInstance->initWebservice();

        mkdir('modules/'.$MODULENAME);
        echo "OK\n";
}



