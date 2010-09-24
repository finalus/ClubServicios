<?php


class Setting extends CoreAppModel {
	
	var $name = "Setting";
	
	var $actsAs = array(
		'Cached' => array(
			'prefix' => array(
				'setting_'
			)
		),
		'Expandable' => array(
			'with' => 'SettingData',
			'key' => 'key',
			'value' => 'value',
		),
	);
	
	var $hasMany = array(
		'SettingData' => array(
			'className' => 'SettingData',
			'foreignKey' => 'setting_id',
			'dependent' => true
		)
	);
	
	
	function applyAllUpdates() {
		$plugins = explode(',', Configure::read('Hook.bootstraps'));
		if (is_array($plugins)) {
			foreach ($plugins as $plugin) {

			}
		}
	}

}