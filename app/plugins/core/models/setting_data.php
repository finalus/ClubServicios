<?php


class SettingData extends CoreModel {
	var $name = 'SettingData';
	var $displayField = 'key';
	
	var $belongsTo = array(
		'Setting' => array(
			'className' => 'Setting',
			'foreignKey' => 'setting_id',
		)
	);
}