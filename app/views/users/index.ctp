<div class="box">
	<div style="-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 5px;" class="box-top white rounded_by_jQuery_corners">
		<h4><?php __('Users');?></h4>
	</div>
	<div style="-moz-border-radius-bottomleft: 5px; -moz-border-radius-bottomright: 5px;" class="box-container rounded_by_jQuery_corners">
		<table cellpadding="0" cellspacing="0" class="table-long">
			<thead>
				<tr>
					<th>&nbsp;</th>
					            		<th><?php echo $this->Paginator->sort('id');?></th>
					            		<th><?php echo $this->Paginator->sort('group_id');?></th>
					            		<th><?php echo $this->Paginator->sort('username');?></th>
					            		<th><?php echo $this->Paginator->sort('first_name');?></th>
					            		<th><?php echo $this->Paginator->sort('last_name');?></th>
					            		<th><?php echo $this->Paginator->sort('password');?></th>
					            		<th><?php echo $this->Paginator->sort('email');?></th>
					            		<th><?php echo $this->Paginator->sort('active');?></th>
					            		<th><?php echo $this->Paginator->sort('key');?></th>
					            		<th><?php echo $this->Paginator->sort('ip');?></th>
					            		<th><?php echo $this->Paginator->sort('created');?></th>
					            		<th><?php echo $this->Paginator->sort('modified');?></th>
							            <th class="actions"><?php __('Actions');?></th>
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
		<td><?php echo $user['User']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($user['Group']['name'], array('controller' => 'groups', 'action' => 'view', $user['Group']['id'])); ?>
		</td>
		<td><?php echo $user['User']['username']; ?>&nbsp;</td>
		<td><?php echo $user['User']['first_name']; ?>&nbsp;</td>
		<td><?php echo $user['User']['last_name']; ?>&nbsp;</td>
		<td><?php echo $user['User']['password']; ?>&nbsp;</td>
		<td><?php echo $user['User']['email']; ?>&nbsp;</td>
		<td><?php echo $user['User']['active']; ?>&nbsp;</td>
		<td><?php echo $user['User']['key']; ?>&nbsp;</td>
		<td><?php echo $user['User']['ip']; ?>&nbsp;</td>
		<td><?php echo $user['User']['created']; ?>&nbsp;</td>
		<td><?php echo $user['User']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id']), array('title' => __('Edit', true), 'class' => 'table-edit-link')); ?>
			<?php echo $this->Html->tag('span', '|', array('class' => 'hidden')); ?>
			<?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $user['User']['id']), array('title' => __('Delete', true), 'class' => 'table-delete-link')); ?>
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
				<td colspan="13">
	
				</td>
				<tr>
					<td colspan="14">
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
</div>

