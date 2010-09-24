<?php



class SystemController extends AppController {
	
	var $name = 'System';
	
	var $uses = array('User');
	
	function admin_index() {
		$this->set('title_for_layout', __('System', true));
		
	}
	
	function admin_account() {
		$this->set('title_for_layout', __('My Account', true));
		$this->layout = 'admin_1col';
		
		if ($this->Auth->user()) {
			if (!empty($this->data)) {
				if ($user = $this->User->register($this->data, $this->Auth->user('id'))) {
					$this->Session->setFlash(sprintf(__('Your account as been updated.', true), 'user'), 'success');
					$this->redirect(array('action' => 'account'));
				} else {
					$this->Session->setFlash(sprintf(__('Your account could not be saved. Please, try again.', true), 'user'), 'error');
				}
			}
			$this->data = $this->Auth->user();
		} else {
			#$this->setFlash());
			$this->redirect(array('action' => 'index'));
		}
	

		
	}
	
	function admin_tools() {
		$this->set('title_for_layout', __('Tools', true));
		
	}
	
	function admin_email_templates() {
		$this->set('title_for_layout', __('Email Templates', true));
		
	}
}