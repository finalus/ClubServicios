<?php


class ShorturlController extends ShorturlAppController {
	
	var $name = "Shorturls";   
    
	var $helpers = array('Html');
	
	public function index() {       
		$shortUrl = '';
		if (!empty($this->data)) {  
			if ($shortUrl = $this->Shorturl->shortUrl($this->data)) {
				App::import('Helper', 'Html');
				$html = new HtmlHelper();
				$this->Session->setFlash(sprintf(__('Your new Url is %s', true), $html->link($shortUrl['Shorturl']['short'], $shortUrl['Shorturl']['short'])), 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be shortened. Please, try again.', true), __('Url', true)), 'error');
			}
		} 
	} 
	
	public function short() {   
		$shortUrl = array();
		if ($this->RequestHandler->isAjax()) {
			$this->redirect(array('plugin'=>false, 'controller' => 'dashboards', 'action' => 'index'));
		} else {
			$this->disableCache(); 
			if (!empty($this->params['url']['long'])) {    
				$data['Shorturl']['url'] = $this->params['url']['long'];
			  	$shortUrl = $this->Shorturl->shortUrl($data);
			}
		}    
		$this->set(compact('shortUrl'));
	}
	
	public function redirection($url = "") {
		if (!empty($url)) {   
			$shorturl = $this->Shorturl->findByKey(base64_decode($url));  
			$this->redirect($shorturl['Shorturl']['url']);
			exit;
		}
	}
}