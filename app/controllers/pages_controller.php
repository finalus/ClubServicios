<?php

class PagesController extends AppController {
	
	var $name = 'Pages';
	
	var $uses = array();
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function display() {
	}
	
	
	function admin_index() {
		$this->set('title_for_layout', __('Pages', true));
	}
}

