<?php

App::import('Vendor', 'Mailer', array('file' => 'phpmailer'.DS.'phpmailer.php'));

class MailerComponent extends Object {
    
    var $mail;


    function startup() {
        $this->mail = new PHPMailer();
    }

    function __set($name, $value) {
        $this->mail->{$name} = $value;
    }

    function __get($name) {
        if (isset($this->mail->{$name})) {
            return $this->mail->{$name};
        }
    }

    function __call($method, $args) {
        if (method_exists($this->mail, $method)) {
            return call_user_func_array(array($this->mail, $method), $args);
        }
    }
}
