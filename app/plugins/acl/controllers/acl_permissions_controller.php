<?php

class AclPermissionsController extends AclAppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
    var $name = 'AclPermissions';
/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
    var $uses = array(
        'Acl.AclAco',
        'Acl.AclAro',
        'Acl.AclArosAco',
        'Group',
    );

    function beforeFilter() {
        parent::beforeFilter();
        if (!empty($this->Auth)) {
            $this->Auth->allow('admin_aco');
        }
    } 

    function admin_index() {      
        $this->set('title_for_layout', __('Permissions', true));
        $acoConditions = array(
            'parent_id !=' => null,
            'model' => null,
            'foreign_key' => null,
            'alias !=' => null,
        );
        $acos  = $this->Acl->Aco->generatetreelist($acoConditions, '{n}.Aco.id', '{n}.Aco.alias');
        $groups = $this->Group->find('list');

        $this->set(compact('acos', 'groups'));
		if (!empty($groups)) {
        	$groupsAros = $this->AclAro->find('all', array(
	            'conditions' => array(
	                'AclAro.model' => 'Group',
	                'AclAro.foreign_key' => array_keys($groups),
	            ),
	        ));
	        $groupsAros = Set::combine($groupsAros, '{n}.AclAro.foreign_key', '{n}.AclAro.id');
		}

        $permissions = array(); // acoId => roleId => bool
        foreach ($acos AS $acoId => $acoAlias) {
            if (substr_count($acoAlias, '_') != 0) {
                $permission = array();
                foreach ($groups AS $groupId => $groupTitle) {
                    $hasAny = array(
                        'aco_id'  => $acoId,
                        'aro_id'  => $groupsAros[$groupId],
                        '_create' => 1,
                        '_read'   => 1,
                        '_update' => 1,
                        '_delete' => 1,
                    );
                    if ($this->AclArosAco->hasAny($hasAny)) {
                        $permission[$groupId] = 1;
                    } else {
                        $permission[$groupId] = 0;
                    }
                    $permissions[$acoId] = $permission;
                }
            }
        }
        $this->set(compact('groupsAros', 'permissions'));
    }

    function admin_toggle($action, $acoId, $aroId) {
        if (!$this->RequestHandler->isAjax()) {
            $this->redirect(array('action' => 'index'));
        }

        // see if acoId and aroId combination exists
        $conditions = array(
            'AclArosAco.aco_id' => $acoId,
            'AclArosAco.aro_id' => $aroId,
        );
        $acoPath = $this->Acl->Aco->getPath($acoId);
        $aroPath = $this->Acl->Aro->getPath($aroId);
        $path = '';
        if (!empty($acoPath)) {
            foreach ($acoPath as $value) {
                $path[] = $value['Aco']['alias']; 
            }
            $acoPath = implode('/', $path);
        }
        $path = '';
        if (!empty($aroPath)) {
            foreach ($aroPath as $value) {
                $path[] = $value['Aro']['alias'];
            }
            $aroPath = implode('/', $path);
        }
        switch($action) {
            case "grant":
                if ($this->Acl->allow($aroPath, $acoPath)) {
                    $success = 1;
                    $permitted = 1;
                }
            break;
            case "deny":
                if ($this->Acl->deny($aroPath, $acoPath)) {
                    $success = 1;
                    $permitted = 0;
                }
            break;
        }


/*

        if ($this->AclArosAco->hasAny($conditions)) {
            $data = $this->AclArosAco->find('first', array('conditions' => $conditions));
            if ($data['AclArosAco']['_create'] == 1 &&
                $data['AclArosAco']['_read'] == 1 &&
                $data['AclArosAco']['_update'] == 1 &&
                $data['AclArosAco']['_delete'] == 1) {
                // from 1 to -1
                $data['AclArosAco']['_create'] = -1;
                $data['AclArosAco']['_read'] = -1;
                $data['AclArosAco']['_update'] = -1;
                $data['AclArosAco']['_delete'] = -1;
                $permitted = 0;
            } else {
                // from -1 to 1
                $data['AclArosAco']['_create'] = 1;
                $data['AclArosAco']['_read'] = 1;
                $data['AclArosAco']['_update'] = 1;
                $data['AclArosAco']['_delete'] = 1;
                $permitted = 1;
            }
        } else {
            // create - CRUD with 1
            $data['AclArosAco']['aco_id'] = $acoId;
            $data['AclArosAco']['aro_id'] = $aroId;
            $data['AclArosAco']['_create'] = 1;
            $data['AclArosAco']['_read'] = 1;
            $data['AclArosAco']['_update'] = 1;
            $data['AclArosAco']['_delete'] = 1;
            $permitted = 1;
        }

        // save
        $success = 0;
        if ($this->AclArosAco->save($data)) {
            $success = 1;
        }a

        */
        

        $this->set(compact('acoId', 'aroId', 'data', 'success', 'permitted'));
    }


    function admin_aco() {

        $conditions = array(
            'parent_id !=' => null,
            'model' => null,
            'foreign_key' => null,
            'alias !=' => null,
        );  

        $acos =$this->AclAco->generateJson($this->Acl->Aco->generatetreelist($conditions, '{n}.Aco.id','{n}.Aco.alias'));

        if ($this->RequestHandler->isAjax()) {
            $this->disableCache();
            if ($this->RequestHandler->accepts('json')) {
                $this->set('acos', $acos);
            }   
        }   
    
        if ($this->RequestHandler->accepts('json')) {
            $this->set('acos', $acos);
        }   


    }
    
}
?>
