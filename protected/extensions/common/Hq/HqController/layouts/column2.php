<?php $this->beginContent('hqLayouts.main'); ?>
<div class="row-fluid">
	<div class="span9">
		<?php echo $content; ?>
	</div>
	<div class="span3">
		<?php $items=$this->menu; array_unshift($items, array('label'=>'COMMANDS')); ?>
		<?php $this->widget('bootstrap.widgets.TbMenu', array(
			'type'=>'list', // '', 'tabs', 'pills' (or 'list')
//			'stacked'=>true, // whether this is a stacked menu
			'items'=>$items,
			'htmlOptions'=>array(
            	'class'=>'well',
			),
		)); ?>
	</div>
</div>
<?php $this->endContent(); ?>