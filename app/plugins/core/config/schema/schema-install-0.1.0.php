<?php 
/* SVN FILE: $Id$ */
/* Core schema generated on: 2010-07-22 16:07:41 : 1279830641*/
class CoreSchema extends CakeSchema {
	var $name = 'Core';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}
	
	var $core_setting_datas = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'setting_id' => array('type' => 'text', 'null' => true, 'default' => NULL, 'length' => 11),
		'key' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'value' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(),
		'tableParameters' => array()
	);
	var $core_settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(),
		'tableParameters' => array()
	);

	var $core_resources = array(
		'code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'primary', 'length' => 50),
		'version' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'indexes' => array('PRIMARY' => array('column' => 'code', 'unique' => 1)),
		'tableParameters' => array()
	);
}