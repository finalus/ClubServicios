<div id="menu">
	<?php drawMenuLevel($menu->getMenuArray());?>
	<?php function drawMenuLevel($_menu, $_level = 0) { ?>
		<ul<?php if (!$_level): ?> id="top"<?php endif; ?>>
			<?php foreach ($_menu as $_item): ?>
				<li class=" <?php echo (!empty($_item['active']))?' selected':'' ?> <?php echo !empty($_item['children'])?' parent':'' ?><?php echo (!empty($_level) && !empty($_item['last']))?' last':'' ?> level<?php echo $_level ?>"><?php if (($_level === 0 && !empty($_item['active'])) && (!empty($_item['children']))): ?><span class="selected-arrow">&nbsp;</span><?php endif; ?><a href="<?php echo $_item['url'] ?>" <?php if(!empty($_item['title'])): ?>title="<?php echo $_item['title'] ?>"<?php endif ?> <?php if(!empty($_item['click'])): ?>onclick="<?php echo $_item['click']; ?>"<?php endif ?> class="<?php echo ($_level===0 && !empty($_item['active']))?'active':'' ?>"><span><?php echo $_item['label'] ?><?php if(!$_level): ?><?php endif ?></span></a>
				<?php if(!empty($_item['children'])): ?>
	                <?php drawMenuLevel($_item['children'], $_level+1); ?>
	            <?php endif; ?>
	        	</li>
	        <?php endforeach; ?>
		</ul>
	<?php } ?>
	<p id="rightLink"><?php echo $html->link(__('View Site', true), 'http://www.google.com')?></p>
</div><!-- end of #menu -->