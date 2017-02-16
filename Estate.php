<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'Estate';

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
        $field1->name = 'estatename';
        $field1->table = 'vtiger_estate';
        $field1->label= 'Name';
        $field1->uitype= 2;
        $field1->column = $field1->name;
        $field1->columntype = 'VARCHAR(50)';
        $field1->typeofdata = 'V~M';
        $block->addField($field1);

        $moduleInstance->setEntityIdentifier($field1);

        $field2  = new Vtiger_Field();
        $field2->name = 'estatecomunity';
        $field2->table = 'vtiger_estate';
        $field2->label= 'Community';
        $field2->uitype= 56;
        $field2->column = $field2->name;
        $field2->columntype = 'VARCHAR(3)';
        $field2->typeofdata = 'C~O';
        $block->addField($field2);

        $field3  = new Vtiger_Field();
        $field3->name = 'estatedivision';
        $field3->table = 'vtiger_estate';
        $field3->label= 'Horizontal division';
        $field3->uitype= 56;
        $field3->column = $field3->name;
        $field3->columntype = 'VARCHAR(3)';
        $field3->typeofdata = 'C~O';
        $block->addField($field3);

        $field4  = new Vtiger_Field();
        $field4->name = 'estatelopd';
        $field4->table = 'vtiger_estate';
        $field4->label= 'LOPD';
        $field4->uitype= 56;
        $field4->column = $field4->name;
        $field4->columntype = 'VARCHAR(3)';
        $field4->typeofdata = 'C~O';
        $block->addField($field4);

        $field5  = new Vtiger_Field();
        $field5->name = 'estatepropertyregistration';
        $field5->table = 'vtiger_estate';
        $field5->label= 'Property Registration';
        $field5->uitype= 56;
        $field5->column = $field5->name;
        $field5->columntype = 'VARCHAR(3)';
        $field5->typeofdata = 'C~O';
        $block->addField($field5);

        $field6  = new Vtiger_Field();
        $field6->name = 'estatecorporate';
        $field6->table = 'vtiger_estate';
        $field6->label= 'Corporate';
        $field6->uitype= 2;
        $field6->column = $field6->name;
        $field6->columntype = 'VARCHAR(50)';
        $field6->typeofdata = 'V~O';
        $block->addField($field6);

        $field7  = new Vtiger_Field();
        $field7->name = 'estatesurface';
        $field7->table = 'vtiger_estate';
        $field7->label= 'Surface';
        $field7->uitype= 7;
        $field7->column = $field7->name;
        $field7->columntype = 'decimal(11,3)';
        $field7->typeofdata = 'NN~O~7,3';
        $block->addField($field7);

        $field8  = new Vtiger_Field();
        $field8->name = 'estatelocation';
        $field8->table = 'vtiger_estate';
        $field8->label= 'Locationo';
        $field8->uitype= 2;
        $field8->column = $field8->name;
        $field8->columntype = 'VARCHAR(50)';
        $field8->typeofdata = 'V~O';
        $block->addField($field8);

        $field9  = new Vtiger_Field();
        $field9->name = 'estatecadastre';
        $field9->table = 'vtiger_estate';
        $field9->label= 'Cadastre';
        $field9->uitype= 2;
        $field9->column = $field9->name;
        $field9->columntype = 'VARCHAR(50)';
        $field9->typeofdata = 'V~O';
        $block->addField($field9);

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



