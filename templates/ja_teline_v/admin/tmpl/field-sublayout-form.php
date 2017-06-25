<?php
$form = $displayData['form'];
$fieldsets = $displayData['fieldsets'];
$style = $displayData['style'];
$title = $displayData['title'];
$description = $displayData['description'];
?>
<div class="sublayout-form sublayout-form-<?php echo $style ?> hide">
	<h4 class="sublayout-style-title"><?php echo $title ?></h4>
	<?php if ($description): ?>
	<p class="sublayout-style-desc"><?php echo $description ?></p>
	<?php endif ?>

	<?php
	if (!is_array($fieldsets)) return;
	foreach ($fieldsets as $name => $fieldset) : ?>

	<div class="sublayout-fieldset clearfix">

		<div class="sublayout-fieldset-header clearfix">
			<h3 class="fieldset-title"><?php echo JText::_($fieldset->label) ?></h3>
			<p class="fieldset-desc"><?php echo JText::_($fieldset->description) ?></p>
		</div>

		<?php
		$fields = $form->getFieldset($name);
		?>

		<div class="sublayout-fieldset-body clearfix">
			<?php foreach ($fields as $field) : ?>
				<?php
				$label = $field->getLabel();
				$input = $field->getInput();
				?>
				<div class="control-group">
					<?php if ($label) : ?>
						<div class="control-label"><?php echo $label ?></div>
						<div class="controls"><?php echo $input ?></div>
					<?php else : ?>
						<?php echo $input ?>
					<?php endif ?>
				</div>
			<?php endforeach ?>
		</div>
	</div>
	<?php endforeach ?>

</div>