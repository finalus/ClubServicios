<?php


class AppError extends ErrorHandler {
    function __construct($method, $messages) {
        $this->controller = new AppController();
        $params = Router::getParams();
		$viewPath = $this->controller->viewPath;
		
		if (Configure::read('App.theme')) {
        	$viewPath = 'errors';
	        if ($this->controller->view == 'Theme') {
	            $viewPath = 'themed'.DS.Configure::read('App.theme').DS.'errors';
	        }
		}

        if (Configure::read('debug') == 0) {
            $method = 'error404';
        }

        $checkView = VIEWS.$viewPath.DS.Inflector::underscore($method).'.ctp';
        if (file_exists($checkView)) {
            $this->controller->_set(Router::getPaths());
            $this->controller->viewPath     = $viewPath;
            $this->controller->theme        = $appConfigurations['theme'];
            $this->controller->pageTitle    = __('Error', true);

            $this->controller->set('message', $messages[0]['url']);
            $this->controller->set('appConfigurations', $appConfigurations);

            $this->controller->render($method);
            e($this->controller->output);
        } else {
            parent::__construct($method, $messages);
        }
    }

}
