<!--<?php if (!empty($items)): ?>
	<ul>
		<?php foreach($items as $item): ?>
			<li id="<?php echo $item['id'] ?>" url="<?php echo $item['url']; ?>">
				<div style="float:right; color: red;font-weight:bold;">[<?php echo $item['type'];?>]</div>
				<strong>Some</strong>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?> -->


<?php if (!empty($results)): ?>
  <ul>
    <?php foreach ($results as $result) : ?>
    <li url="<?php echo $html->url(json_decode($result['SearchIndex']['url'], true)); ?>">
		<div style="float:right; color: red; font-weight:bold;">[<?php echo $result['SearchIndex']['model']; ?>]</div>
		<strong><?php echo $text->truncate($result['SearchIndex']['name'], 50); ?></strong>
      <?php if (!empty($result['SearchIndex']['summary'])): ?>
		<p><?php echo $text->truncate($result['SearchIndex']['summary'], 20); ?></p>
      <?php else : ?>
        <?php echo $searchable->snippets($result['SearchIndex']['data']); ?>
      <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p><?php echo __('Nothing Found', true); ?></p>
<?php endif; ?>

