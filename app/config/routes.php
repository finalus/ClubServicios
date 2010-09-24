<?php

class SuperRouter extends Router {
	
	function connect($route , $default = array(), $params = array()) {
		parent::connect($route, $default, $params);
		if ($route == '/') {
			$route = '';
		}
		parent::connect('/:locale', $default, array_merge(array('locale' => '[a-z]{3}'), $params));
	}
	
	function plugins() {
		$pluginRoutes = Configure::read("Hook.routes");
		if (!$pluginRoutes) {
			return;
		}
		
		$plugins = explode(',', $pluginRoutes);
		foreach ($plugins as $plugin) {
			if (file_exists(APP.'plugins'.DS.$plugin.DS.'config'.DS.'routes.php')) {
				require_once(APP.'plugins'.DS.$plugin.DS.'config'.DS.'routes.php');
			}
		}
	}
}

	SuperRouter::plugins();

	if (!isInstalled()) {
		SuperRouter::connect('/', array('plugin' => 'install', 'controller' => 'wizard'));
	}
	
	// Basic
	SuperRouter::connect('/admin', array('admin' => true, 'controller' => 'dashboard'));

	// Pages
	SuperRouter::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	SuperRouter::connect('/pages/:slug', array('controller' => 'pages', 'action' => 'display'));
	
	// Users
	SuperRouter::connect('/register', array('controller' => 'users', 'action' => 'register'));
	SuperRouter::connect('/login', array('controller' => 'users', 'action' => 'login'));
	SuperRouter::connect('/recover', array('controller' => 'users', 'action' => 'recover'));




