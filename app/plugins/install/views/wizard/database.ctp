<div class="install form">
	<h2><?php echo $title_for_layout; ?></h2>
	<?php
		echo $form->create('Install', array('url' => array('plugin' => 'install', 'controller' => 'wizard', 'action' => 'database')));
		echo $form->input('Install.driver', array('label' => __('Database Driver', true), 'type' => 'select', 'options' => array('mysql' => __('MySQL', true), 'sqlite' => __('SQLite', true))));
		echo $form->input('Install.host', array('label' => __('Host', true), 'value' => 'localhost'));
		echo $form->input('Install.login', array('label' => __('Username', true), 'value' => 'root'));
		echo $form->input('Install.password', array('label' => __('Password', true)));
		echo $form->input('Install.database', array('label' => __('Database', true)));
		echo $form->input('Install.prefix', array('label' => __('Table Prefix', true)));	
		echo $form->end('Submit');
	
	?>
</div>