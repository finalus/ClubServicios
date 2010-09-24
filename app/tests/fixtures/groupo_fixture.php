<?php
/* Groupo Fixture generated on: 2010-06-30 22:06:33 : 1277951973 */
class GroupoFixture extends CakeTestFixture {
	var $name = 'Groupo';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'nombre' => array('type' => 'string', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'nombre' => 'Lorem ipsum dolor sit amet',
			'created' => '2010-06-30 22:39:33',
			'modified' => '2010-06-30 22:39:33'
		),
	);
}
?>