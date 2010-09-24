<?php

class AclBehavior extends ModelBehavior {

/**
 * Maps ACL type options to ACL models
 *
 * @var array
 * @access protected
 */                     
	var $__typeMaps = array('requester' => 'Aro', 'controlled' => 'Aco');  

/**
 * Sets up the configuation for the model, and loads ACL models if they haven't been already
 *
 * @param mixed $config
 */
	function setup(&$model, $config = array()) {     
        if (is_string($config)) {
			$config = array('type' => $config);
		}
		$this->settings[$model->name] = array_merge(array('type' => 'requester'), (array)$config);

        $type = $this->__typeMaps[$this->settings[$model->name]['type']];   
		if (!class_exists('AclNode')) {
			require LIBS . 'model' . DS . 'db_acl.php';
		}
		if (PHP5) {
			$model->{$type} = ClassRegistry::init($type);
		} else {
			$model->{$type} =& ClassRegistry::init($type);
		}
		if (!method_exists($model, 'parentNode')) {
			trigger_error("Callback parentNode() not defined in {$model->name}", E_USER_WARNING);
		}
	}
/**
 * Retrieves the Aro/Aco node for this model
 *
 * @param mixed $ref
 * @return array
 */
	function node(&$model, $ref = null) {        
		$type = $this->__typeMaps[strtolower($this->settings[$model->name]['type'])]; 
		if (empty($ref)) {
			$ref = array('model' => $model->name, 'foreign_key' => $model->id);
		}
		return $model->{$type}->node($ref);
	}
/**
 * Creates a new ARO/ACO node bound to this record
 *
 * @param boolean $created True if this is a new record
 */
	function afterSave(&$model, $created) {  
		$type = $this->__typeMaps[strtolower($this->settings[$model->alias]['type'])];
		$parent = $model->parentNode();
		if (!empty($parent)) {
			$parent = $this->node($model, $parent);
		}
		$data = array(
			'parent_id' => isset($parent[0][$type]['id']) ? $parent[0][$type]['id'] : null,
			'model' => $model->alias,
			'foreign_key' => $model->id,
			'alias'	=> $model->name . "." . $model->id 
		);
		if (!$created) {
			$node = $this->node($model);
			$data['id'] = isset($node[0][$type]['id']) ? $node[0][$type]['id'] : null;
		}
		$model->{$type}->create();
		$model->{$type}->save($data);      
	}
/**
 * Destroys the ARO/ACO node bound to the deleted record
 *
 */
	function afterDelete(&$model) { 
		$type = $this->__typeMaps[strtolower($this->settings[$model->name]['type'])];
		$node = Set::extract($this->node($model), "0.{$type}.id");
		if (!empty($node)) {
			$model->{$type}->delete($node);
		}
	}
}

?>
