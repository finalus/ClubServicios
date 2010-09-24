<div class="install">
	<h2><?php echo $title_for_layout; ?></h2>
	
	<?php
		echo $html->link(__('Click here to create your database.', true), array(
			'plugin' => 'install',
			'controller' => 'wizard',
			'action' => 'data',
			'run' => 1,
		));
	?>
</div>