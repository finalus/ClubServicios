<div class="groups index content-box">
    <div class="content-box-header">
    	<h3><?php __('Groups');?></h3>
        <ul class="content-box-buttons">
            <li><?php echo $this->Html->link(sprintf(__('Add New %s', true), __('Group', true)), array('action' => 'add'), array('class' => 'button')); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Users', true)), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('User', true)), array('controller' => 'users', 'action' => 'add')); ?> </li>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">
    	<table cellpadding="0" cellspacing="0">
            <thead>
    	        <tr>
                    <th><input type="checkbox" class="check-all" /></th>
	            		<th><?php echo $this->Paginator->sort('id');?></th>
	            		<th><?php echo $this->Paginator->sort('name');?></th>
	            		<th><?php echo $this->Paginator->sort('created');?></th>
	            		<th><?php echo $this->Paginator->sort('modified');?></th>
			            <th class="actions"><?php __('Actions');?></th>
	            </tr>
            </thead> 

            <tbody>
	<?php
	$i = 0;
	foreach ($groups as $group):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><input type="checkbox" name="mass" value="<?php echo $group['Group']['id']; ?>" /></td>
		<td><?php echo $group['Group']['id']; ?>&nbsp;</td>
		<td><?php echo $group['Group']['name']; ?>&nbsp;</td>
		<td><?php echo $group['Group']['created']; ?>&nbsp;</td>
		<td><?php echo $group['Group']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link($this->Html->image('admin/icons/hammer_screwdriver.png', array('alt' => __('View', true))) , array('action' => 'view', $group['Group']['id']), array('escape' => false, 'title' => __('View', true))); ?>
			<?php echo $this->Html->link($this->Html->image('admin/icons/pencil.png', array('alt' => __('Edit', true))), array('action' => 'edit', $group['Group']['id']), array('escape' => false, 'title' => __('Edit', true))); ?>
			<?php echo $this->Html->link($this->Html->image('admin/icons/cross.png', array('alt' => __('Delete', true))), array('action' => 'delete', $group['Group']['id']), array('escape' => false, 'title' => __('Delete', true)), sprintf(__('Are you sure you want to delete # %s?', true), $group['Group']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="pagination">
                            	<?php echo $this->Paginator->first('<<'.__('First', true), array()); ?>
                            	<?php echo $this->Paginator->prev('<<'.__('Previous', true), array(), null, array('class' => 'disabled')); ?>
                            	<?php echo $this->Paginator->numbers(array('class' => 'number', 'separator' => '')); ?>
                            	<?php echo $this->Paginator->next(__('Next', true).'>>', array(), null, array('class' => 'disabled')); ?>
                            	<?php echo $this->Paginator->last(__('Last', true).'>>', array()); ?>
                        </div>
                        <div class="clear"></div>
                    </td>
                </tr>
            </tfoot>
	</table>
</div>
