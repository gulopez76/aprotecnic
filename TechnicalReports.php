<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'Technicalreports';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if ($moduleInstance || file_exists('modules/'.$MODULENAME)) {
        echo "Module already present - choose a different name.";
} else {
        $moduleInstance = new Vtiger_Module();
        $moduleInstance->name = $MODULENAME;
        $moduleInstance->save();

        // Schema Setup : iThis create three tables with modulename, modulenamecf and modulenamegroupdel
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
        $field1->name = 'tiname';
        $field1->table = 'vtiger_technicalreports';
        $field1->label= 'Name';
        $field1->uitype= 2;
        $field1->column = $field1->name;
        $field1->columntype = 'VARCHAR(50)';
        $field1->typeofdata = 'V~M';
        $block->addField($field1);

        $moduleInstance->setEntityIdentifier($field1);

        $field3  = new Vtiger_Field();
        $field3->name = 'tidocument';
        $field3->table = 'vtiger_technicalreports';
        $field3->label= 'Document';
        $field3->uitype= 10;
        $field3->column = $field3->name;
        $field3->columntype = 'VARCHAR(50)';
        $field3->typeofdata = 'V~O';
        $block->addField($field3);
        $field3->setRelatedModules(Array('Documents'));
 
        $field4  = new Vtiger_Field();
        $field4->name = 'tibuilding';
        $field4->table = 'vtiger_technicalreports';
        $field4->label= 'Building Associated';
        $field4->uitype= 10;
        $field4->column = $field4->name;
        $field4->columntype = 'VARCHAR(50)';
        $field4->typeofdata = 'V~O';
        $block->addField($field4);
  
        $field5  = new Vtiger_Field();
        $field5->name = 'titype';
        $field5->table = 'vtiger_technicalreports';
        $field5->label= 'Type';
        $field5->uitype= 33;
        $field5->column = $field5->name;
        $field5->columntype = 'VARCHAR(50)';
        $field5->typeofdata = 'V~O';
        $block->addField($field5);
        $field5->setPicklistValues(Array('Facilities', 'Administration', 'others'));

      $field4->setRelatedModules(Array('Buildings'));

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



