<div id="content-top">
	<h2><?php echo $title_for_layout; ?></h2>	
	<a href="#" id="topLink">Change Order</a>
	<span class="clearFix">&nbsp;</span>
	<?php if ($html->getCrumbs()): ?>
	<p><?php echo $html->link(__('Dashboard', true), '/admin'); ?>
			&raquo; 
	<?php echo $html->getCrumbs(' &raquo '); ?></p>
	<?php endif; ?>
</div>
