<div class="install index">
	<h2><?php echo $title_for_layout; ?></h2>
	<?php
		$check = true;
		
		if (is_writable(TMP)) {
			echo '<p class="error">' . __('Your tmp directory is writable.', true) . '</p>';
		} else {
			$check = false;
			echo '<p class="error">' . __('Your tmp directory is not writable.', true) . '</p>';
		}
		
		if (is_writable(APP.'config')) {
			echo '<p class="success">' . __('Your config directory is writable.', true) . '</p>';
		} else {
			$check = false;
			echo '<p class="error">' . __('Your config directory is not writable.', true) . '</p>';
		}
		
		if (phpversion() > 5) {
			echo '<p class="success">' . sprintf(__('PHP Version %s < 5', true), phpversion()) . '</p>';
		} else {
			$check = false;
			echo '<p class="error">' . sprintf(__('PHP Version %s < 5', true), phpversion()) . '</p>';
		}
		
		if ($check) {
			echo '<p>' . $html->link(__('Click here to begin installation', true), array('action' => 'database')) . '</p>';
		} else {
			echo '<p>' . __('Installation cannot continue as minimum requirements are not met', true) . '</p>';
		}
	
	?>
</div>