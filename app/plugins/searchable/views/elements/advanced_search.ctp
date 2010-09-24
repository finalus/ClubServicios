<?php  echo $html->css('/searchable/css/advanced_search'); ?>   
<div id="searchable_advanced_search" style="display: none;">
	This is the Advanced Search
</div>
<?php echo $html->link(__('Advanced Search', true), array('plugin' => 'searchable', 'controller' => 'search', 'action' => 'index'), array('id' => "advanced_search")); ?>  
<?php echo $html->scriptStart(); ?> 
	$('#advanced_search').click(function() {
	  $("#searchable_advanced_search").slideToggle('slow');     
		return false;
	})
<?php echo $html->scriptEnd(); ?>
