<?php drawFooterMenuLevel($menu->getMenuArray());?>
<?php function drawFooterMenuLevel($_menu, $_level = 0) { ?>
	<ul<?php if (!$_level): ?> id="top"<?php endif; ?>>
		<?php foreach ($_menu as $_item): ?>
			<li class=" <?php echo (!$_level && !empty($_item['active']))?' selected':'' ?> <?php echo !empty($_item['children'])?' parent':'' ?><?php echo (!empty($_level) && !empty($_item['last']))?' last':'' ?> level<?php echo $_level ?>"><?php if (($_level === 0 && !empty($_item['active'])) && (!empty($_item['children']))): ?><span class="selected-arrow">&nbsp;</span><?php endif; ?><a href="<?php echo $_item['url'] ?>" <?php if(!empty($_item['title'])): ?>title="<?php echo $_item['title'] ?>"<?php endif ?> <?php if(!empty($_item['click'])): ?>onclick="<?php echo $_item['click']; ?>"<?php endif ?> class="<?php echo ($_level===0 && !empty($_item['active']))?'active':'' ?>"><span><?php echo $_item['label'] ?><?php if(!$_level): ?><?php endif ?></span></a>
        	</li>
        <?php endforeach; ?>
	</ul>
<?php } ?>