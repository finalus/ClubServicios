<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php echo $html->charset(); ?>
		<title>
			<?php echo $title_for_layout; ?> ::
			<?php echo $appConfigurations['name']; ?>
		</title>
		<?php
			if(!empty($meta_description)) :
				echo $html->meta('description', $meta_description);
			endif;
			if(!empty($meta_keywords)) :
				echo $html->meta('keywords', $meta_keywords);
			endif;
			echo $html->css('admin/reset');
			echo $html->css('admin/style');
			echo $html->css('admin/message');
			
			echo $html->css('admin/colorbox');
			echo $html->css('admin/colorbox-custom');
			echo $html->css('admin/jquery.wysiwyg');
			
			echo $javascript->link('jquery/jquery-1.3.2.min');
			echo $javascript->link('jquery/jquery.colorbox-min');
			echo $javascript->link('jquery/jquery.ui');
			echo $javascript->link('jquery/jquery.corners.min');
			
			echo $javascript->link('bg.pos');
			echo $javascript->link('jquery/jquery.wysiwyg');
			echo $javascript->link('tabs.pack');

			echo $javascript->link('default');
			
		?>

		<style type="text/css">
        div.wysiwyg ul.panel li {padding:0px !important;} /**textarea visual editor padding override**/
        </style>
	    <!--[if IE 6]> -->
		<?php
			echo $html->css('admin/ie');
       	?>
       	<!-- <![endif]-->
       	<!--[if IE]> -->
		<?php
			echo $html->css('admin/colorbox-custom-ie');
		?>
       	<!--<![endif]-->

		<?php
			echo $scripts_for_layout;	
		?>
	</head>
	<body>
		<div id="container">
			<?php echo $this->element('modal'); ?>
			<?php echo $this->element('header'); ?>
			<div id="content">
				<?php echo $this->element('crumbs'); ?>
				<?php
					if($session->check('Message.flash')){
						echo $session->flash();
					}
				?>
				<div id="mid-col" class="full-col">
					<?php echo $content_for_layout; ?>
				</div><!-- end of #min-col -->
				<span class="clearFix">&nbsp;</span>     

			</div><!-- end of #content -->
			<div class="push"></div>
		</div><!-- end of #container -->
		<?php echo $this->element('footer'); ?>
	</body>
</html>