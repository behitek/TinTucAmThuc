<?php
// add css
$field = $displayData['field'];
$forms = $displayData['sublayout-forms'];
$sublayouts = $displayData['sublayouts'];
$label = (string)$field->element['label'];
$description = (string)$field->element['description'];
$layout = (string)$field->element['layout'];
?>

<div id="sublayout-<?php echo $field->id ?>" class="sublayout-group joomla<?php echo substr(JVERSION, 0, 1) ?>">

	<input type="hidden" name="<?php echo $field->name ?>" id="<?php echo $field->id ?>" class="sublayout-config" data-ignoresave='1' value="<?php echo htmlspecialchars($field->value, ENT_COMPAT, 'UTF-8') ?>" />
	<input type="hidden" name="sublayout" value="<?php echo $layout ?>" />

	<div class="control-group sublayout-header ">
		<div class="control-label">
			<label id="sublayout-stype-lbl" for="sublayout-stype" class="hasTooltip" title="<?php echo JText::_($description) ?>"><?php echo JText::_($label) ?></label>
		</div>

		<div class="controls">
			<select class="sublayout-styles required" name="sublayout-stype">
				<option value="" selected="selected"><?php echo JText::_('JGLOBAL_SELECT_AN_OPTION') ?></option>
				<?php foreach ($sublayouts as $tpl => $styles) : ?>
					<optgroup label="<?php if ($tpl=='_'): ?>---Core---<?php else: ?>---From <?php echo $tpl ?> Template---<?php endif ?>">
						<?php foreach ($styles as $style => $title): ?>
							<option value="<?php echo $style ?>"><?php echo $title ?></option>
						<?php endforeach ?>
					</optgroup>
				<?php endforeach ?>
			</select>
		</div>

	</div>

	<div class="sublayout-body">
		<?php foreach ($forms as $style => $form) : ?>
		<?php echo $form ?>
		<?php endforeach ?>
	</div>

</div>

<script>
	jQuery ('#sublayout-<?php echo $field->id ?>').sublayoutInit();
</script>
