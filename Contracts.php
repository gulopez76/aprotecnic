<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'Contracts';

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
        $field1->name = 'conname';
        $field1->table = 'vtiger_contracts';
        $field1->label= 'Name';
        $field1->uitype= 2;
        $field1->column = $field1->name;
        $field1->columntype = 'VARCHAR(50)';
        $field1->typeofdata = 'V~M';
        $block->addField($field1);

        $moduleInstance->setEntityIdentifier($field1);

        $field3  = new Vtiger_Field();
        $field3->name = 'condocument';
        $field3->table = 'vtiger_contracts';
        $field3->label= 'Document';
        $field3->uitype= 10;
        $field3->column = $field3->name;
        $field3->columntype = 'VARCHAR(50)';
        $field3->typeofdata = 'V~O';
        $block->addField($field3);
        $field3->setRelatedModules(Array('Documents'));
 
        $field4  = new Vtiger_Field();
        $field4->name = 'conprovider';
        $field4->table = 'vtiger_contracts';
        $field4->label= 'Provider Associated';
        $field4->uitype= 10;
        $field4->column = $field4->name;
        $field4->columntype = 'VARCHAR(50)';
        $field4->typeofdata = 'V~O';
        $block->addField($field4);
        $field4->setRelatedModules(Array('Providers'));

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



