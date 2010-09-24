<?php if ($sidebar->getSidebarArray()): ?>
<div id="sidebar" class="rightBoxes">
	<div class="rightBoxesTop"><h3><?php echo $sidebar->getTitle(); ?></h3></div>
	<div class="rightContent">
		<?php echo $this->element('sidebar/links'); ?>
	</div>
</div><!-- end of #sidebar -->
<?php endif; ?>