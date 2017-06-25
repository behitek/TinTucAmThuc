<?php
/**
 * ------------------------------------------------------------------------
 * Plugin JA Content Type
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('JPATH_BASE') or die;

$field 		= $displayData['field'];
$attributes = $displayData['attributes'];
$items 		= $displayData['items'];
//$value 		= htmlspecialchars($field->value, ENT_COMPAT, 'UTF-8');
$value 		= $field->value;
$id 		= $field->id;
$name 		= $field->name;
$hideLabel 	= (bool) $attributes['hiddenLabel'];
$label 		= JText::_((string) $attributes['label']);
$desc 		= JText::_((string) $attributes['description']);

$width 		= 90/count ($items);

$field_items = array();
if(is_array($value) && count($value)) {
	foreach($value as $f_name => $f_items) {
		if(is_array($f_items) && (count($f_items) > count($field_items))) {
			$field_items = $f_items;
		}
	}
}
if(!count($field_items)) {
	$field_items = array(0 => null);
}
?>
<div class="jaacm-list <?php echo $id ?>" data-index="<?php echo count($field_items); ?>">
	<?php if ($hideLabel): ?>
		<h4><?php echo $label ?></h4>
		<p><?php echo $desc ?></p>
	<?php endif ?>
	<table class="jalist" width="100%">
		<thead>
		<tr>
			<?php foreach ($items as $item) : ?>
				<th>
					<?php echo $item->label ?>
				</th>
			<?php endforeach ?>
			<th>&nbsp;</th>
		</tr>
		</thead>

		<tbody>

		<?php
		$cnt = 0;
		$arkmediaids = array();
		?>
		<?php foreach($field_items as $index => $v): ?>
			<tr class="<?php if(!$cnt) echo 'first'; ?>">
				<?php
				foreach ($items as $_item) :
					$item = clone $_item;
					//$item->id .= '_'.$cnt;
					$item->value = isset($value[$item->fieldname][$index]) ? $value[$item->fieldname][$index] : '';
					if($item->type == 'Calendar') {
						$item->class = ($field->class) ? $field->class . ' type-calendar' : 'type-calendar';
					}
					$input = $item->input;
					if($item->type == 'Calendar') {
						if($cnt == 0) {
							$input = str_replace(array($item->name), array($item->name.'['.$cnt.']'), $input);
						} else {
							$input = str_replace(array($item->name, $item->id), array($item->name.'['.$cnt.']', $item->id.'_'.$cnt), $input);
							JHtml::_('calendar', $item->value, $item->name.'['.$cnt.']', $item->id.'_'.$cnt);
						}
					} else {
						$input = str_replace(array($item->name, $item->id), array($item->name.'['.$cnt.']', $item->id.'_'.$cnt), $input);
						if($item->type == 'Media') {
							//mark as media field, since some property will be override by ArkMedia later
							$input = str_replace("rel=\"{handler: 'iframe'", "data-type=\"media\" rel=\"{handler: 'iframe'", $input);

							$arkmediaids[]	= '#'.$item->id.'_'.$cnt;
						}
					}
					?>
					<td>
						<?php echo $input; ?>
					</td>
				<?php endforeach ?>
				<td>
					<span class="btn action btn-clone" data-action="clone_row" title="Clone Row"><i class="icon-plus fa fa-plus"></i></span>
					<span class="btn action btn-delete" data-action="delete_row" title="Delete Row"><i class="icon-minus fa fa-minus"></i></span>
				</td>
			</tr>
			<?php $cnt++; ?>
		<?php endforeach; ?>

		</tbody>

	</table>
</div>
<script type="text/javascript">
	jQuery('.<?php echo $id ?>').jalist();
</script>
<?php
// Required in Some Instances
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
$loader	= JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_arkmedia' . DIRECTORY_SEPARATOR . 'initiate.php';
if(count($arkmediaids) && JFile::exists( $loader )):
	// Initiate Ark
	defined('_ARKMEDIA_EXEC') or define('_ARKMEDIA_EXEC', true);
	require_once $loader;

	$stack = 'images';
	$quick = 1;
	$name = 'mediafield';
	$callback	= 'arkmedia' . 'callback' . $name;
	$url = array(
		'option' 		=> 'com_arkmedia',
		'stack' 		=> $stack,
		'editor' 		=> $name,
		'editorname' 	=> '%s',
		'edit' 			=> '%s',
		'editquick' 	=> $quick,
		'language' 		=> JFactory::getLanguage()->getDefault(),
		'callback' 		=> $callback,
		'tmpl' 			=> 'component'
	);

	// Build Window Options
	$parameters = array(
		'location' 		=> 'no',
		'menubar' 		=> 'no',
		'toolbar' 		=> 'no',
		'dependent' 	=> 'yes',
		'minimizable' 	=> 'no',
		'modal' 		=> 'yes',
		'alwaysRaised' 	=> 'yes',
		'resizable' 	=> 'yes',
		'scrollbars' 	=> 'yes'
	);

	// Collapse to URL Query String (ensure sprintf's aren't encoded)
	$url = JRoute::_( 'index.php?' . JURI::buildQuery( $url ), false );

	// Collapse Window String
	$parameters = str_replace( '"', '', JArrayHelper::toString( $parameters, '=', ',' ) );
?>
	<script type="text/javascript">
		var Joomla = (Joomla || {});
		jQuery( document ).ready( function( $ )
		{
			Joomla.uris = {
				base : '<?php echo JURI::base(true); ?>/'
			};
			jQuery.fn.<?php echo $name; ?>(
			{
				css			: {
					root			: 'body',
					ids				: <?php echo json_encode( $arkmediaids ); ?>,
					popup			: 'arkmedia',
					modal			: { value : '<?php echo Ark\Helper::css( 'joomla.modal' ); ?>', selector : '.<?php echo Ark\Helper::css( 'joomla.modal' ); ?>' }
				},
				html 		: {
					url				: '<?php echo $url; ?>',
					callback		: '<?php echo $callback; ?>',
					parameters		: '<?php echo $parameters; ?>'
				}
			});
		});
	</script>
<?php endif; ?>