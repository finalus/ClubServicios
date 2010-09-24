<?php
class ImportUpload extends ImportAppModel {

	var $name = 'ImportUpload';

    var $actsAs = array(
        'Containable',
        'Import.ImportUpload' => array(
            'filename' => array(
                'required'  => true,
                'random_filename' => false,
            ) 
        )
    );

    var $belongsTo = array(
        'ImportDelimiter' => array(
            'className'     => 'Import.ImportDelimiter',
            'foreignKey'    => 'import_delimiter_id',
        ),
        'ImportSource'  => array(
            'className'     => 'Import.ImportSource',
            'foreignKey'    => 'source_id',
        ), 
        'ImportMapping' => array(
            'className'     => 'Import.ImportMapping',
            'foreignKey'    => 'mapping_id',
        ),
        'ImportStatus' => array(
            'className'     => 'Import.ImportStatus',
            'foreignKey'    => 'status_id',
        ), 
    ); 
}
?>
