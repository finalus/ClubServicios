<?php


App::import('Vendor', 'recaptcha'.DS.'recaptchalib');

class RecaptchaHelper extends Helper {
    var $public_key;
    var $private_key;
    var $secure_server;

    function __construct() {
        parent::__construct();

        $config = Configure::read('Recaptcha');
        if (!empty($config['enabled'])) {
            if (!empty($config['public_key'])) {
                $this->public_key = $config['public_key'];
            } else {
                die('Recaptcha needs the public key to work.  Please check your configuration');
            }
            if (!empty($config['private_key'])) {
                $this->private_key = $config['private_key'];
            } else {
                die('Recaptcha needs the private key to work.  Please check your configuration');
            }

            if (!empty($config['secure_server'])) {
                $this->secure_server = true;
            } else {
                $this->secure_server = false;
            }
        }
    }

    function getHtml($error = null) {
        return $this->output(recaptcha_get_html($this->public_key, $error, $this->secure_server));
    }
}


