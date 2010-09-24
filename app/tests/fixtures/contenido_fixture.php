<?php
/* Contenido Fixture generated on: 2010-06-30 00:06:49 : 1277873149 */
class ContenidoFixture extends CakeTestFixture {
	var $name = 'Contenido';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'publicacion_id' => array('type' => 'integer', 'null' => false),
		'formato_id' => array('type' => 'integer', 'null' => false),
		'codigo' => array('type' => 'string', 'null' => false),
		'contenido' => array('type' => 'string', 'null' => false),
		'descripcion' => array('type' => 'text', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

	var $records = array(
		array(
			'id' => 1,
			'publicacion_id' => 1,
			'formato_id' => 1,
			'codigo' => 'Lorem ipsum dolor sit amet',
			'contenido' => 'Lorem ipsum dolor sit amet',
			'descripcion' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2010-06-30 00:45:49',
			'modified' => '2010-06-30 00:45:49'
		),
	);
}
?>