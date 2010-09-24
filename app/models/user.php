<?php


class User extends AppModel {

    var $name = 'User';

	var $order = 'name ASC';

    var $actsAs = array('Containable', 'Acl.Acl' => 'requester');

    var $belongsTo = array('Group');

    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

        $this->validate = array(
            'username' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
					'message' => __('Username must not be empty.', true),
				),
                'isUnique' => array(
                    'rule' => array('isUnique' , 'username'),
                    'message' => __('Username is already taken. Please choose a different username', true)
                ), 
                'alphaNumeric' => array(
                    'rule' => 'alphaNumeric',
                    'message' => __('The username can contain letters and numbers only.', true)
                ),
                'between' => array(
                    'rule' => array('between', 3, 16),
                    'message' => __('Username must be between 3 and 16 characters long.', true),
                ),
                'minLength' => array(
                    'rule' => array('minLength', 1),
                    'message' => __('Username is a required field', true)
                )
            ),
            'password_before' => array(
                'between' => array(
                    'rule' => array('between', 6, 20),
                    'message' => __('Password must be between 6 and 20 characters long.', true)
                ),
                'minLength' => array(
                    'rule' => array('minLength', '1'),
                    'message' => __('A valid password is required.', true)
                ),
            ),
            'password_confirmation' => array(
                'matchFields' => array(
                    'rule' => array('matchFields', 'password_before'),
                    'message' => __('Password and Retype Password do not match.', true)
                ),
                'minLength' => array(
                    'rule' => array('minLength', 1),
                    'message' => __('A valid retype password is required.', true)
                ),
            ),    
            'email' => array(
                'isUnique' => array(
                    'rule' => array('isUnique', 'email'),
                    'message' => __('The email was already used by another user.', true)
                ),
                'email' => array(
                    'rule' => 'email',
                    'message' => __('The email you provided does not appear to be valid.', true)
                ),
                'minlength' => array(
                    'rule' => array('minLength', 1),
                    'message' => __('A valid email is required.', true)
                ), 
            ),
        );
    }

	function parentNode($type='Aro') {  
		if ($type == 'Aro') {
			if (!$this->id && empty($this->data)) {
				return null;
			}
		
			$data = $this->data;
			if (empty($this->data)) {
				$data = $this->read();
			}           

			if (empty($data['User']['group_id'])) {
				return null;              
			} else {
				return array('model' => 'Group', 'foreign_key' => $data['User']['group_id']);
			}
  		}
		return false;
	}

	function afterSave($created) {      
		if (!$created) {
			$parent = $this->parentNode();   
			$parent = $this->node($parent);
			$node = $this->node();   
			$aro = $node[0];
			$aro['Aro']['parent_id'] = $parent[0]['Aro']['id'];
			$this->Aro->save($aro);
		}
	}

    function register($data, $id = null) {
        if (is_array($data)) {
            if (!empty($data['User'])) {
				if (empty($id) || !empty($data['User']['reset_key'])) {
					$data['User']['key']  = Security::hash(uniqid(rand(), true).Configure::read('Security.salt'));
				}
                
                if (!empty($data['User']['password_before'])) {
                    $data['User']['password'] = Security::hash(Configure::read('Security.salt').$data['User']['password_before']);
                }

                if (!empty($id)) {
                    $data['User']['id'] = $id;
                } else {
					$data['User']['ip'] = $_SERVER['REMOTE_ADDR'];
                    $this->create();
                }
                if ($this->save($data)) {
                    $user = $this->read(null, $this->getLastInsertId());

                	

                    #$user['User']['username']       = $data['User']['username'];
                    #$user['User']['password']       = $data['User']['before_password'];
                    #$user['User']['activate_link']  = $this->appConfigurations['url'].'/users/activate/'. $data['User']['key'];
                    #$user['to']                     = $data['User']['email'];
                   # if ($admin == true) {
                   # } else {
                   #     $user['subject']            = sprintf(__('Registration Verification - %s', true), $this->appConfigurations['name']);
                   # }
                   # $user['template']               = 'users/activate';

                    return $user;
                } else {
                    return false;
                }

            } else {
                return false;
            }
        }
    }

    function recover($data, $newPasswordLength = 8) {
        $conditions = array();

        if (is_array($data)) {
            if (!empty($data['User'])) {
                foreach ($data['User'] as $key => $datum) {
                    if ($this->hasField($key)) {
                        $conditions[$key] = $datum;
                    }
                }

                $user = $this->find('first', array('conditions' => $conditions));

                if (!empty($user)) {

                    $user['User']['key']             = Security::hash(uniqid(rand(), true));

                    $user['User']['before_password'] = substr(sha1(uniqid(rand(), true)), 0, $newPasswordLength);
                    $user['to']                      = $user['User']['email'];
                    $user['subject']                 = sprintf(__('Account Reset - %s', true), $this->appConfigurations['name']);
                    $user['template']                = 'users/reset';

                    $user['User']['password']        = Security::hash(Configure::read('Security.salt').$user['User']['before_password']);
                    $user['User']['reset_link']      = $this->appConfigurations['url'].'/users/reset/'. $user['User']['key'];

                    $this->save($user, false);
                    return $user;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }  

	function reset($data, $id = null) {
		if (is_array($data)) {
			if (!empty($data['User'])) {  
				if (!empty($data['User']['before_password'])) {
                	$data['User']['password'] = Security::hash(Configure::read('Security.salt').$data['User']['before_password']);
          		}  
				
				if (!empty($id)) {
	          		$data['User']['id'] = $id;   
					if ($this->save($data)) {
						return true;
					}
				}
			}
		}
		return false;
	}

    function resetKey($key) {
        if (isset($key)) {
            $user = $this->find('first', array('conditions' => array('User.key' => $key)));   
            if (!empty($user)) {   
			   	$user['User']['key'] = '';
				$this->save($user);
			   	return $user;
            } else {
                return false;
            }
		} 
		return false;
    }      

    function resend($email) {
        if (!is_null($email)) {
            $user = $this->find('first', array('contitions' => array('User.email' => $email, 'User.active' => 0)));

            if (!empty($user)) {

                $data['User']['key']    = Security::hash(uniqid(rand(), true));
                $this->read(null, $user['User']['id']);
                $this->save($data);
                
                $user['User']['activate_link']  = $this->appConfigurations['url'].'/users/activate/'. $data['User']['key'];
                $user['to']                     = $user['User']['email'];
                $user['subject']            = sprintf(__('Resent Verification - %s', true), $this->appConfigurations['name']);
                $user['template']               = 'users/activate';

                return $user;
            } 
        }
        return false;
    }


    function activate($key) {
        $user = $this->find('first', array('conditions' => array('User.key' => $key, 'User.active' => 0)));


        if (!empty($user)) {
            $user['User']['active'] = 1;
            $user['User']['key'] = '';
            $this->save($user);
            
            $user['to']                     = $user['User']['email'];
            $user['subject']                = sprintf(__('Welcome to %s, %s', true), $this->appConfigurations['name'], $user['User']['username']);
            $user['template']               = 'users/welcome';

            return $user;
        } else {
            return false;
        }
    }
}
