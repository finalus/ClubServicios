<?php
class DashboardController extends AppController {

	var $name = 'Dashboard';
	
	var $uses = array();
	
	function admin_index() {
		$this->set('title_for_layout', __('Dashboard', true));
		
	}

}
?>