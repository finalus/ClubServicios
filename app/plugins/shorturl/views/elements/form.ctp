<?php
echo $form->create('Shorturl', array(
  'url' => array(
    'plugin' => 'shorturl',
    'controller' => 'shorturl',
    'action' => 'index'
  )
));
echo $form->input('url', array('type' => 'text', 'label' => 'Shorten URL', 'id' => 'SearchSearch'));
echo $form->end(__('Shorten URL', true)); 
?>