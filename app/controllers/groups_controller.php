<?php
class GroupsController extends AppController {

	var $name = 'Groups';

    var $helpers = array('Acl.Jstree');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }

	function admin_index() {
		$this->set('title_for_layout', __('Groups', true));
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'group'), 'attention');
			$this->redirect(array('action' => 'index'));
		}
		$this->set('group', $this->Group->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Group->create();
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'group'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'group'), 'error');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'group'), 'attention');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'group'), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'group'), 'error');
			}
		}
		if (empty($this->data)) {
            $users = $this->paginate('User', array('User.group_id' => $id));
            $this->set('users', $users);
			$this->data = $this->Group->read(null, $id);
		}
        $this->set('selected', array('node_10', 'node_20'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'group'), 'attention');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Group->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Group'), 'success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Group'), 'error');
		$this->redirect(array('action' => 'index'));
	}

}
?>
