<div class="importMasterDelimiters view">
<h2><?php  __('ImportMasterDelimiter');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $importMasterDelimiter['ImportMasterDelimiter']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $importMasterDelimiter['ImportMasterDelimiter']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $importMasterDelimiter['ImportMasterDelimiter']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Delimiter'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $importMasterDelimiter['ImportMasterDelimiter']['delimiter']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Use Excel Reader'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $importMasterDelimiter['ImportMasterDelimiter']['use_excel_reader']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $importMasterDelimiter['ImportMasterDelimiter']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $importMasterDelimiter['ImportMasterDelimiter']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit ImportMasterDelimiter', true), array('action'=>'edit', $importMasterDelimiter['ImportMasterDelimiter']['id'])); ?> </li>
		<li><?php echo $html->link(__('Delete ImportMasterDelimiter', true), array('action'=>'delete', $importMasterDelimiter['ImportMasterDelimiter']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $importMasterDelimiter['ImportMasterDelimiter']['id'])); ?> </li>
		<li><?php echo $html->link(__('List ImportMasterDelimiters', true), array('action'=>'index')); ?> </li>
		<li><?php echo $html->link(__('New ImportMasterDelimiter', true), array('action'=>'add')); ?> </li>
	</ul>
</div>
