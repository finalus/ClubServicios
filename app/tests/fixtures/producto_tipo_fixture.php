<?php
/* ProductoTipo Fixture generated on: 2010-06-30 00:06:31 : 1277873191 */
class ProductoTipoFixture extends CakeTestFixture {
	var $name = 'ProductoTipo';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'tipo' => array('type' => 'string', 'null' => false, 'length' => 25),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'tipo' => 'Lorem ipsum dolor sit a',
			'created' => '2010-06-30 00:46:31',
			'modified' => '2010-06-30 00:46:31'
		),
	);
}
?>