<?php
	$menu->addMenuArray(Configure::read('Menu'));
	drawMenuLevel($menu->getMenuArray());
?>
<?php function drawMenuLevel($_menu, $_level = 0) { ?>
	<ul<?php if (!$_level): ?> id="menu"<?php else: ?> style="visibility: hidden; display: none;"<?php endif; ?>>
		<?php foreach ($_menu as $_item): ?>
			<li class=" <?php echo (!empty($_item['active']))?' selected':'' ?> <?php echo !empty($_item['children'])?' parent':'' ?><?php echo (!empty($_level) && !empty($_item['last']))?' last':'' ?> level<?php echo $_level ?>"><a style="background-position: 0pt 0pt;" href="<?php echo $_item['url'] ?>" <?php if(!empty($_item['title'])): ?>title="<?php echo $_item['title'] ?>"<?php endif ?> <?php if(!empty($_item['click'])): ?>onclick="<?php echo $_item['click']; ?>"<?php endif ?> class="<?php echo ($_level===0 && !empty($_item['active']))?'active':'' ?> <?php echo !empty($_item['children'])?' top-level':'' ?>"><?php echo $_item['label'] ?><?php if(!empty($_item['children'])): ?><span>&nbsp;</span><?php endif ?></a>
			<?php if(!empty($_item['children'])): ?>
                <?php drawMenuLevel($_item['children'], $_level+1); ?>
            <?php endif; ?>
        	</li>
        <?php endforeach; ?>
	</ul>
<?php } ?>
<!-- end of #menu -->