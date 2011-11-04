<?php $this->beginContent('//layouts/main'); ?>
	<div id="main">
		<div class="block">
			<div class="content">
                            <div class="inner">
				<?php echo $content; ?>
                            </div>
			</div>
		</div>
		<?php echo $this->renderPartial('//layouts/_footer'); ?>
	</div>
	<div id="sidebar">
		<div class="block">
			<h3>Operations</h3>
			<?php $this->widget('zii.widgets.CMenu', array(
				'items' => $this->menu,
				'htmlOptions' => array(
					'class' => 'navigation',
				),
			)); ?>
		</div>
	</div>
<?php $this->endContent(); ?>