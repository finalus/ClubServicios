<?php


class ImportMappingField extends ImportAppModel {

    var $name = "ImportMappingField";

    var $belongsTo = array(
        'ImportMapping' => array(
            'className'     => 'Import.ImportMapping',
            'foreignKey'    => 'mapping_id',
        )
    ); 

}
