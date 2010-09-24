<?php $sideBar = "";?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $html->charset(); ?>
		<title>
			<?php echo $title_for_layout; ?> ::
			<?php echo $appConfigurations['name']; ?>
		</title>
		<!-- <link rel="alternate" type="application/rss+xml" href="/auctions/index.rss" title="<?php __('Live Auctions');?>"> -->
		<?php
			if(!empty($meta_description)) :
				echo $html->meta('description', $meta_description);
			endif;
			if(!empty($meta_keywords)) :
				echo $html->meta('keywords', $meta_keywords);
			endif;
			echo $html->css('admin/style');

			echo $javascript->link('jquery/jquery');
			echo $javascript->link('jquery/ui');
			echo $javascript->link('default');

			echo $scripts_for_layout;
		?>
		<!--[if lt IE 7]>
			<?php echo $javascript->link('dropdown'); ?>
		<![endif]-->
	</head>
	<body>
		<div id="container">
    		<?php echo $this->element('header'); ?>
    		<div id="content">
				<div id="leftBox">
					<?php
						if($session->check('Message.flash')){
							echo $session->flash();
						}
					?>
					<?php echo $this->element('crumbs'); ?>
					<?php echo $content_for_layout; ?>
				<hr>
				</div><!-- end of #leftBox -->
		
				<?php echo $this->element('sidebar'); ?>
	
			</div><!-- end of #content -->
			<br class="clearFix">
		</div><!-- end of #container -->
		<hr>
		
		<?php echo $this->element('footer'); ?>
	</body>
</html>