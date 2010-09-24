<?php 
/* SVN FILE: $Id$ */
/*  schema generated on: 2010-07-08 09:07:34 : 1278595054*/
class Schema extends CakeSchema {
	var $name = '';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}
	
	var $shorturls = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'url' => array('type' => 'text', 'null' => false),
		'short' => array('type' => 'text', 'null' => false),
		'key' => array('type' => 'string', 'null' => false),
		'model' => array('type' => 'string', 'null' => false, 'length' => 100),
		'created' => array('type' => 'datetime', 'null' => false),
		'modified' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);
}
?>