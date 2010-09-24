<div id="pageIntro">
	<h2><?php echo $title_for_layout; ?></h2>
	<p><?php echo $html->link(__('Dashboard', true), '/admin'); ?>
		<?php if ($html->getCrumbs()): ?>
			&raquo; 
		<?php endif; ?>
	
	<?php echo $html->getCrumbs(' &raquo '); ?></p>
	<?php if (!empty($description_for_page)): ?>
		<p><?php echo $description_for_page; ?></p>
	<?php endif; ?>
</div>