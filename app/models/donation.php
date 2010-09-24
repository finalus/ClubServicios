<?php
class Donation extends AppModel {
	var $name = 'Donation';

    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

        $this->validate = array(
            'first_name' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Please enter a first name', true),
                ),
            ),
            'last_name' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Please enter a last name', true),
                ),
            ),
            'email' => array(
                'email' => array(
                    'rule' => array('email'),
                    'message' => __('Please enter a valid email address', true),
                ),
            ),
        ); 
    } 

    function donate($data) {
        if (!empty($data)) {
            return $data;
        }
    }
    

}
?>
