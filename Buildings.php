<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'Buildings';

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
        $field1->name = 'bdname';
        $field1->table = 'vtiger_buildings';
        $field1->label= 'Name';
        $field1->uitype= 2;
        $field1->column = $field1->name;
        $field1->columntype = 'VARCHAR(50)';
        $field1->typeofdata = 'V~M';
        $block->addField($field1);

        $moduleInstance->setEntityIdentifier($field1);

        $field2  = new Vtiger_Field();
        $field2->name = 'bdstreetaddress';
        $field2->table = 'vtiger_buildings';
        $field2->label= 'Street Address';
        $field2->uitype= 21;
        $field2->column = $field2->name;
        $field2->columntype = 'text';
        $field2->typeofdata = 'V~O';
        $block->addField($field2);

        $field3  = new Vtiger_Field();
        $field3->name = 'bdcontact';
        $field3->table = 'vtiger_buildings';
        $field3->label= 'Contact';
        $field3->uitype= 10;
        $field3->column = $field3->name;
        $field3->columntype = 'VARCHAR(50)';
        $field3->typeofdata = 'V~O';
        $block->addField($field3);
        $field3->setRelatedModules(Array('Contacts'));

        $field4  = new Vtiger_Field();
        $field4->name = 'bdcertenergetic';
        $field4->table = 'vtiger_buildings';
        $field4->label= 'Energy certificate';
        $field4->uitype= 10;
        $field4->column = $field4->name;
        $field4->columntype = 'VARCHAR(50)';
        $field4->typeofdata = 'V~O';
        $block->addField($field4);
        $field4->setRelatedModules(Array('Documents'));

        $field5  = new Vtiger_Field();
        $field5->name = 'bdddtsale';
        $field5->table = 'vtiger_buildings';
        $field5->label= 'DDT-Sale';
        $field5->uitype= 10;
        $field5->column = $field5->name;
        $field5->columntype = 'VARCHAR(50)';
        $field5->typeofdata = 'V~O';
        $block->addField($field5);
        $field5->setRelatedModules(Array('Documents'));

        $field6  = new Vtiger_Field();
        $field6->name = 'bdelectricpanel';
        $field6->table = 'vtiger_buildings';
        $field6->label= 'Electric Panel';
        $field6->uitype= 10;
        $field6->column = $field6->name;
        $field6->columntype = 'VARCHAR(50)';
        $field6->typeofdata = 'V~O';
        $block->addField($field6);
        $field6->setRelatedModules(Array('Documents'));

        $field7  = new Vtiger_Field();
        $field7->name = 'bdphotos';
        $field7->table = 'vtiger_buildings';
        $field7->label= 'Photos';
        $field7->uitype= 69;
        $field7->column = $field7->name;
        $field7->columntype = 'text';
        $field7->typeofdata = 'V~O';
        $block->addField($field7);

        $field8  = new Vtiger_Field();
        $field8->name = 'bdconductregulations';
        $field8->table = 'vtiger_buildings';
        $field8->label= 'Conduct Regulations';
        $field8->uitype= 10;
        $field8->column = $field8->name;
        $field8->columntype = 'VARCHAR(50)';
        $field8->typeofdata = 'V~O';
        $block->addField($field8);
        $field8->setRelatedModules(Array('Documents'));

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



