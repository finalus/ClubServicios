<?php
echo $form->create('Shorturl', array('id' => 'shortUrl',
  'url' => array(
    'plugin' => 'shorturl',
    'controller' => 'shorturl',
    'action' => 'index'
  )
));
echo $form->input('short', array('type' => 'text', 'label' => 'Shorten URL', 'id' => 'SearchSearch'));
echo $form->end(__('Shorten URL', true));  
?>     
<?php echo $html->scriptStart(); ?>   
	$("#shortUrl").submit(function() { 
		$.get({
			url: '<?php echo $html->url(array('plugin' => 'shorturl', 'controller' => 'shorturl', 'action' =>'short', 'ext' => 'json' )) ?>',  
			dataType: 'json',
			success: function (json) {
				alert(json);
			}
		}) 
		return false; 
	});
<?php echo $html->scriptEnd();?>