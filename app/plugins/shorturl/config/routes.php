<?php

Router::connect('/r/*', array('plugin' => 'shorturl', 'controller' => 'shorturl', 'action' => 'redirection'));

