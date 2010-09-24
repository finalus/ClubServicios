<?php
/**
 * AclFilter Component
 *
 * PHP version 5
 *
 * @category Component
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class AclFilterComponent extends Object {

/**
 * @param object $controller controller
 * @param array  $settings   settings
 */
    function initialize(&$controller, $settings = array()) {
        $this->controller =& $controller;
    }

/**
 * acl and auth
 *
 * @return void
 */
    function auth() {
        //Configure AuthComponent
        $this->controller->Auth->authorize = 'actions';
        $this->controller->Auth->loginAction = array('plugin' => 0, 'controller' => 'users', 'action' => 'login');
        $this->controller->Auth->logoutRedirect = array('plugin' => 0, 'controller' => 'users', 'action' => 'login');
        $this->controller->Auth->loginRedirect = array('plugin' => 0, 'controller' => 'users', 'action' => 'index');
		$this->controller->Auth->loginError = __('Login failed. Invalid username or password.', true);
		$this->controller->Auth->flashElement = 'error';
		$this->controller->Auth->authError = __('You are not authorized to access that location.', true);
        $this->controller->Auth->userScope = array('User.active' => 1);
        $this->controller->Auth->actionPath = 'controllers/';

        if ($this->controller->Auth->user() &&
            $this->controller->Auth->user('group_id') == 1) {
            // Role: Admin
            $this->controller->Auth->allowedActions = array('*');
        } else {
            if ($this->controller->Auth->user()) {
                $groupId = $this->controller->Auth->user('group_id');
            } else {
                $groupId = 3; // Role: Public
            }

            $thisControllerNode = $this->controller->Acl->Aco->node($this->controller->Auth->actionPath.$this->controller->name);

            if ($thisControllerNode) {
                $thisControllerNode = $thisControllerNode['0'];
                $thisControllerActions = $this->controller->Acl->Aco->find('list', array(
                    'conditions' => array(
                        'Aco.parent_id' => $thisControllerNode['Aco']['id'],
                    ),
                    'fields' => array(
                        'Aco.id',
                        'Aco.alias',
                    ),
                    'recursive' => '-1',
                ));
                $thisControllerActionsIds = array_keys($thisControllerActions);
                $allowedActions = $this->controller->Acl->Aco->Permission->find('list', array(
                    'conditions' => array(
                        'Permission.aro_id' => $groupId,
                        'Permission.aco_id' => $thisControllerActionsIds,
                        'Permission._create' => 1,
                        'Permission._read' => 1,
                        'Permission._update' => 1,
                        'Permission._delete' => 1,
                    ),
                    'fields' => array(
                        'id',
                        'aco_id',
                    ),
                    'recursive' => '-1',
                ));
                $allowedActionsIds = array_values($allowedActions);
            }

            $allow = array();
            if (isset($allowedActionsIds) &&
                is_array($allowedActionsIds) &&
                count($allowedActionsIds) > 0) {
                foreach ($allowedActionsIds AS $i => $aId) {
                    $allow[] = $thisControllerActions[$aId];
                }
            }
            $this->controller->Auth->allowedActions = $allow;
        }
    }

}
?>