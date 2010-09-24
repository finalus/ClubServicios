<?php
class Group extends AppModel {
	var $name = 'Group';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'group_id',
		)
	);

    var $actsAs = array('Acl' => array('type' => 'requester'));

    function parentNode() {
        return null;
    }

}
?>
