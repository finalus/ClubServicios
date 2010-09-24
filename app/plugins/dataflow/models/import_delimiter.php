<?php
class ImportDelimiter extends ImportAppModel {

	var $name = 'ImportDelimiter';
	var $validate = array(
		'description' => array('notempty'),
		'delimiter' => array('notempty'),
		'use_excel_reader' => array('numeric')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'ImportUpload' => array(
			'className' => 'ImportUpload',
			'foreignKey' => 'import_delimiter_id',
		)
	);

}
?>
