<?php


class CachedBehavior extends ModelBehavior {
	
	function setup(&$model, $config = array()) {
		if (is_string($config)) {
			$config = array($config);
		}
		$this->settings[$model->alias] = $config;
	}
	
	function afterSave(&$model, $created) {
		$this->_deleteCachedFiles($model);
	}
	
	function afterDelete(&$model) {
		$this->_deleteCachedFiles($model);
	}
	
	function _deleteCachedFiles(&$model) {
		foreach ($this->setting[$model->alias]['prefix'] as $prefix) {
			$files = glob(TMP.'cache/querires/cake_'.$prefix.'*');
			if (is_array($files) && count($files) > 0) {
				foreach ($files as $file) {
					unlink($file);
				}
			}
		}
	}
}