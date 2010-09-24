<?php echo $html->script('jquery/jquery.autocomplete', array('inline' => true))?>
<div id="searchform">
	<?php echo $html->tag('span', $html->image('loader.gif', array('alt' => __('Loading', true)."...")), array('id' => 'search-indicator', 'class' => 'autocomplete-indicator', 'style' => 'display: none'));?>
	<?php echo $form->input('q', array('type' => 'text', 'div' => false, 'label' => false, 'id' => 'query', 'class' => 'search_box', 'value' => __('Search', true)))?>
	<?php echo $form->submit('search', array('type' => 'button', 'div' => false, 'label' => false, 'value' => __('Search', true), 'class' => 'search_btn'))?>
	<?php echo $html->scriptStart(); ?>
		var options, a;
		options = {
			serviceUrl: '<?php echo $html->url(array('plugin' => 'searchable', 'controller' => 'search_indexes', 'action' => 'search', 'admin' => false))?>',
			minChars: 2,
			indicator: 'search-indicator',
			selectElement:getElementId,
		};
		$("#query").autocomplete(options);
		function getElementId(li) {
			location.href = $(li).attr('url');
		}
	<?php echo $html->scriptEnd();?>
</div>