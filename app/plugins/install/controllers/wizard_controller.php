<?php


class WizardController extends InstallAppController {
	
	var $name = 'Wizard';
	
	var $components = null;
	
	var $uses = null;
	
	
	function beforeFilter() {
		parent::beforeFilter();
		App::import('Component', 'Session');
		$this->Session = new SessionComponent;
		$this->layout = 'install';
	}
	
	function _check() {
		if (isInstalled()) {
			$this->Session->setFlash(__('Already installed', true));
			$this->redirect('/');
		}
	}
	
	function index() {
		$this->_check();
		$this->set('title_for_layout', __('Installation: Welcome', true));
	}
	
	function database() {
		$this->_check();
		$this->set('title_for_layout', __('Step 1: Database', true));
		
		if (empty($this->data)) {
			return;
		}
		
		switch($this->data['Install']['driver']) {

			case 'mysql':
				if (!@mysql_connect($this->data['Install']['host'], $this->data['Install']['login'], $this->data['Install']['password'])) {
					$this->Session->setFlash(__('Could not connect to database', true));
					return;
				}
				if (!@mysql_select_db($this->data['Install']['database'])) {
					$this->Session->setFlash(__('Could not select database', true));
					return;
				}
				
			break;
			
			case 'sqlite':
			default:
				$this->data['Install']['database'] = CONFIGS . 'schema' . DS .  $this->data['Install']['database'].'.'.$this->data['Install']['driver'];
			break;
		}
		
		copy(CONFIGS . 'database.php.install', CONFIGS . 'database.php');
		App::import('Core', 'Files');
		$file = new File(CONFIGS.'database.php', true);
		$content = $file->read();
		
		$content = r('{default_driver}', $this->data['Install']['driver'], $content);
		$content = r('{default_host}', $this->data['Install']['host'], $content);
		$content = r('{default_login}', $this->data['Install']['login'], $content);
		$content = r('{default_password}', $this->data['Install']['password'], $content);
		$content = r('{default_database}', $this->data['Install']['database'], $content);
		$content = r('{default_prefix}', $this->data['Install']['prefix'], $content);
		
		if ($file->write($content)) {
			return $this->redirect(array('action' => 'data'));
		} else {
			$this->Session->setFlash(__('Could not write database.php file.', true));
		}
	}
	
	function data() {
		$this->_check();
		$this->set('title_for_layout', __('Step 2: Run SQL', true));
		if (isset($this->params['named']['run'])) {
			App::import('Core', 'File');
			App::import('Model', 'ConnectionManager');
			App::import('Model', 'CakeSchema', false);
			$db =& ConnectionManager::getDataSource('default');

			$name = $path = $file = $plugin = null;
			if (!$db->isConnected()) {
				$this->Session->setFlash(__('Could not connect to database.', true));
			} else {
				
				$this->Schema =& new CakeSchema(compact('name', 'path', 'file', 'db', 'plugin'));

				list($Schema, $table) = $this->_loadSchema();
				if (is_object($Schema)) {
					$this->__create($Schema, $table);
				}
				
				#$this->__executeSQLScript($db, CONFIGS.'schema'.DS.'install_data.sql');
				#$this->__executeSQLScript($db, CONFIGS.'schema'.DS.'data.sql');
				
				$this->redirect(array('action' => 'finish'));
			}
		}
	}
	
	
	function finish() {
		$this->_check();
		$this->set('title_for_layout', __('Installation completed sucessfully', true));
		if (isset($this->params['named']['delete'])) {
			App::import('Core', 'Folder');
			$this->folder = new Folder;
		}
		
		copy(CONFIGS.'settings.yml.install', CONFIGS.'settings.yml');
		$file =& new File(CONFIGS.'settings.yml');
		$settings = Spyc::YAMLLoad($file->read());
		$settings['Install']['date'] = date(DATE_RFC822);		
		$settings = Spyc::YAMLDump($settings, 4, 60);
		$file->write($settings);


		$File =& new File(CONFIGS . 'core.php');
		if (!class_exists('Security')) {
			uses('security.php');
		}
		$salt = Security::generateAuthKey();
		$seed = mt_rand() . mt_rand();
		$contents = $File->read();
		$contents = preg_replace('/(?<=Configure::write\(\'Security.salt\', \')([^\' ]+)(?=\'\))/', $salt, $contents);
		$contents = preg_replace('/(?<=Configure::write\(\'Security.cipherSeed\', \')([^\' ]+)(?=\'\))/', $seed, $contents);
		if (!$File->write($contents)) {
			return false;
		}
		$this->redirect('/');
		
		
	}
	
	
	function _loadSchema() {
		$name = $plugin = null;
		$options = array('name' => $name, 'plugin' => $plugin);
		$Schema =& $this->Schema->load($options);

		if (!$Schema) {
			#$this->err(sprintf(__('%s could not be loaded', true), $this->Schema->path . DS . $this->Schema->file));
			return;
		}
		$table = null;
		if (isset($this->args[1])) {
			$table = $this->args[1];
		}
		return array(&$Schema, $table);
	}
	
	
	function __create(&$Schema, $table = null) {
		$db =& ConnectionManager::getDataSource($this->Schema->connection);

		$drop = $create = array();

		if (!$table) {
			foreach ($Schema->tables as $table => $fields) {
				$drop[$table] = $db->dropSchema($Schema, $table);
				$create[$table] = $db->createSchema($Schema, $table);
			}
		} elseif (isset($Schema->tables[$table])) {
			$drop[$table] = $db->dropSchema($Schema, $table);
			$create[$table] = $db->createSchema($Schema, $table);
		}
		if (empty($drop) || empty($create)) {
			return;
		}

		$this->__run($drop, 'drop', $Schema);
		$this->__run($create, 'create', $Schema);
		return;
	}
	
	
	function __run($contents, $event, &$Schema) {
		if (empty($contents)) {
			return;
		}
		Configure::write('debug', 2);
		$db =& ConnectionManager::getDataSource($this->Schema->connection);

		foreach ($contents as $table => $sql) {
			if (empty($sql)) {
				return;
			} else {
				if (!$Schema->before(array($event => $table))) {
					return false;
				}
				$error = null;
				if (!@$db->execute($sql)) {
					$error = $table . ': '  . $db->lastError();
				}

				$Schema->after(array($event => $table, 'errors' => $error));
			}
		}
	}
	
	function __executeSQLScript($connection, $fileName) {
		$statements = file_get_contents($fileName);
		$statements = explode(';', $statements);
		
		foreach ($statements as $statement) {
			if (trim($statement) != '') {
				if (!@$db->execute($statement)) {
					$error = __('Error', true).":" . $db->lastError();
				}
			}
		}
	}

}