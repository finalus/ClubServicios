<?php
 
	echo $html->addCrumb(__('User', true));

	echo $sidebar->addTitle(sprintf(__('%s Menu', true), __('User', true)));
	echo $sidebar->addMenu('users', array('title' => sprintf(__('Manage %s',true), __('Users', true)), 'sort_order' => 10));
	echo $sidebar->addMenu('new_user', array('title' => sprintf(__('New %s',true), __('Users', true)), 'sort_order' => 10, 'url' => array('action' => 'add')), 'users');
	echo $sidebar->addMenu('groups', array('title' => sprintf(__('Manage %s', true), __('Groups', true)), 'sort_order' => 20));
echo $sidebar->addMenu('list groups', array('title' => sprintf(__('List %s', true), __('Groups', true)), 'sort_order' => 20, 'url' => array('controller' => 'groups', 'action' => 'index')), 'groups');
echo $sidebar->addMenu('new groups', array('title' => sprintf(__('New %s', true), __('Group', true)), 'sort_order' => 20, array('controller' => 'groups', 'action' => 'add')), 'groups');
?>




<div class="box">
<div class="box-top rounded_by_jQuery_corners" style="-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px;">
    <h4 class="white"><?php __('Users');?></h4>
	</div>
	<div style="-moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px;" class="box-container rounded_by_jQuery_corners">
		<table cellpadding="0" cellspacing="0" class="table-long">
			<thead>
				<tr>
					<th>&nbsp;</th>
            		<th><?php echo $this->Paginator->sort('username');?></th>
            		<th><?php echo $this->Paginator->sort('first_name');?></th>
            		<th><?php echo $this->Paginator->sort('last_name');?></th>
            		<th><?php echo $this->Paginator->sort('active');?></th>
            		<th><?php echo $this->Paginator->sort('group_id');?></th>
		            <th class="actions"><?php __('Actions', true);?></th>
	            </tr>
			</thead>
		    <tbody>
			<?php
			$i = 0;
			$count = 0;
			if (!empty($users)):
			foreach ($users as $user):
				$class = null;
				$count = count($user['User']);
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
			?>
	<tr<?php echo $class;?>>
		<td class="col-chk"><input type="checkbox" name="mass[]" value="<?php echo $user['User']['id']; ?>" /></td>
		<td><?php echo $user['User']['username']; ?>&nbsp;</td>
		<td><?php echo $user['User']['first_name']; ?>&nbsp;</td>
		<td><?php echo $user['User']['last_name']; ?>&nbsp;</td>
		<td><?php echo $user['User']['active']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $user['User']['id']), array('title' => __('Edit', true), 'class' => 'table-edit-link')); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $user['User']['id']), array('title' => __('Delete', true), 'class' => 'table-delete-link'), sprintf(__('Are you sure you want to delete # %s?', true), $user['User']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php else: ?>
	<tr>
		<td colspan=''><?php echo sprintf(__('No %s Found', true), __('User', true)); ?></td>
	</tr>
<?php endif; ?>
			</tbody>
			<tfoot>
				<td class="col-chk"><input type="checkbox" name="" /></td>
				<td colspan="14">
	
				</td>
				<tr>
					<td colspan="14">
                        <div class="pagination">
                            	<?php echo $this->Paginator->first('<'.__('First', true), array()); ?>
                            	<?php echo $this->Paginator->prev('<<'.__('Previous', true), array(), null, array('class' => 'disabled')); ?>
                            	<?php echo $this->Paginator->numbers(array('class' => 'number', 'separator' => '')); ?>
                            	<?php echo $this->Paginator->next(__('Next', true).'>>', array(), null, array('class' => 'disabled')); ?>
                            	<?php echo $this->Paginator->last(__('Last', true).'>', array()); ?>
                        </div>
                        <div class="clear"></div>
                    </td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

