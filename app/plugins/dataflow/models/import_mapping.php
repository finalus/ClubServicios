<?php

class ImportMapping extends ImportAppModel {

    var $name = "ImportMapping";

    var $hasMany = array(
        'ImportMappingField' => array(
            'className' => 'Import.ImportMappingField',
            'foreignKey' => 'mapping_id',
        )
    );


}
