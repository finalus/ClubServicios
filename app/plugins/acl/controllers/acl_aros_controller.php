<?php

class AclArosController extends AclAppController {
    var $name = 'AclAros';
    var $uses = array('Acl.AclAro');

    function admin_index() {
        $this->set('title_for_layout', __('Aros', true));

        $this->AclAro->recursive = 0;
        $this->set('aros', $this->paginate());
    }

    function admin_add() {
        $this->set('title_for_layout', __('Add Aro', true));

        if (!empty($this->data)) {
            $this->AclAro->create();
            if ($this->AclAro->save($this->data)) {
                $this->Session->setFlash(__('The Aro has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Aro could not be saved. Please, try again.', true));
            }
        }
    }

    function admin_edit($id = null) {
        $this->set('title_for_layout', __('Edit Aro', true));

        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid Aro', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->AclAro->save($this->data)) {
                $this->Session->setFlash(__('The Aro has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The Aro could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->AclAro->read(null, $id);
        }
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Aro', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!isset($this->params['named']['token']) || ($this->params['named']['token'] != $this->params['_Token']['key'])) {
            $blackHoleCallback = $this->Security->blackHoleCallback;
            $this->$blackHoleCallback();
        }
        if ($this->AclAro->delete($id)) {
            $this->Session->setFlash(__('Aro deleted', true));
            $this->redirect(array('action' => 'index'));
        }
    }

}
?>