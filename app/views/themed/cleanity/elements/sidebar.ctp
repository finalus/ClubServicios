<?php
	drawSidebarLevel($sidebar->getMenuArray(), 0, $sidebar->getTitle());
?>
<?php function drawSidebarLevel($_menu, $_level = 0, $title=null) { ?>
	<?php if (!empty($_menu)): ?>
		<?php if (!$_level):?>
	<div class="box">
		<div style="-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px;" class="box-top yellow rounded_by_jQuery_corners">
			<h4><?php echo $title; ?></h4>
			<span class="clearFix">&nbsp;</span>
		</div>
    	<div style="-moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px;" class="box-container rounded_by_jQuery_corners">
		<?php endif; ?>
        	<ul <?php if ($_level>0): ?>style="visibility: hidden; display: none;"<?php endif; ?> role="<?php echo (!$_level)?'tablist':'tabpanel'?>" class="list-links ui-accordion ui-widget ui-helper-reset">
			<?php foreach ($_menu as $_item): ?>
				<li class="ui-accordion-li-fix">
					<a href="<?php echo $_item['url'] ?>" <?php if(!empty($_item['title'])): ?>title="<?php echo $_item['title'] ?>"<?php endif ?> <?php if(!empty($_item['click'])): ?>onclick="<?php echo $_item['click']; ?>"<?php endif ?> class="ui-accordion-header ui-helper-reset <?php echo ($_level===0 && !empty($_item['active']))?'active':'' ?> <?php echo !empty($_item['children'])?' top-level':'' ?>"><?php echo $_item['label'] ?><?php if(!empty($_item['children'])): ?><span>&nbsp;</span><?php endif ?></a>
					<?php if(!empty($_item['children'])): ?>
	                <?php drawSidebarLevel($_item['children'], $_level+1); ?>
	            	<?php endif; ?>	
	        	</li>
	        <?php endforeach; ?>
			</ul>
		<?php if (!$_level):?>
			</div>
		</div>
		<?php endif; ?>
	<?php endif; ?>
<?php } ?>
<!-- end of #menu -->