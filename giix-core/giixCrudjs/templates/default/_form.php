<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div class="wide form">

<?php $ajax = ($this->enable_ajax_validation) ? 'true' : 'false'; ?>

<?php echo '<?php '; ?>
$form = $this->beginWidget('GxActiveForm', array(
	'id' => '<?php echo $this->class2id($this->modelClass); ?>-form',
	'enableAjaxValidation' => <?php echo $ajax; ?>,
));
<?php echo '?>'; ?>


	<p class="note">
		<?php echo "<?php echo Yii::t('app', 'Fields with'); ?> <span class=\"required\">*</span> <?php echo Yii::t('app', 'are required'); ?>"; ?>.
	</p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php foreach ($this->tableSchema->columns as $column): ?>
<?php if (!$column->isPrimaryKey): ?>
		<div class="span-8 last">
		<?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column) . "; ?>\n"; ?>
		<?php echo "<?php " . $this->generateActiveField($this->modelClass, $column) . "; ?>\n"; ?>
		<?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
		</div><!-- row -->
<?php endif; ?>
<?php endforeach; ?>
<!-- june -->
<div class="row"></div>
<!-- june -->
<?php //foreach ($this->getRelations($this->modelClass) as $relation): ?>
<?php //if ($relation[1] == GxActiveRecord::HAS_MANY || $relation[1] == GxActiveRecord::MANY_MANY): ?>
		<!--label--><?php //echo $this->pluralize($this->class2name($relation[3])); ?><!--/label-->
		<?php /*echo '<?php ' . $this->generateActiveRelationField($this->modelClass, $relation) . "; ?>//\n"; */?>
<?php //endif; ?>
<?php //endforeach; ?>
                
<?php echo "<?php
echo GxHtml::Button(Yii::t('app', 'Cancel'), array(
			'submit' => array('". strtolower($this->modelClass) ."/admin')
		));
echo GxHtml::submitButton(Yii::t('app', 'Save'));
\$this->endWidget();
?>\n"; ?>
</div><!-- form -->