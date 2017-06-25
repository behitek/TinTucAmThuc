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

$item 		= $displayData['item'];

$content_type = $item->params->get('ctm_content_type', 'article');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
$path = JAPATH_CONTENT_TYPE . '/models/';
JForm::addFieldPath($path.'fields');

if(!empty($content_type) && file_exists($path.'types/'.$content_type.'.xml')):

	$form = new JForm('extrafields');
	$result = $form->loadFile($path.'types/'.$content_type.'.xml');

	if($result):
	?>
	<table class="table additional-info">
		<thead>
			<tr>
				<th colspan="2"><?php echo JText::_('Additional Info'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($form->getGroup('attribs') as $field):
				$type = strtolower($field->type);
				if(in_array($type, array('hidden', 'jaitems'))) continue;

				$value = $item->params->get($field->fieldname);
				if(empty($value)) continue;
				?>
				<tr>
					<td><?php echo $field->title; ?></td>
					<td>
						<?php
						switch($type) {
							case 'text':
							case 'textarea':
							case 'editor':
								echo $value;
								break;
							case 'calendar':
								echo JHtml::_('date', $value, JText::_('DATE_FORMAT_LC3'));
								break;
							case 'media':
								echo '<img alt="'.htmlspecialchars($field->title).'" src="'.JUri::root(true).'/'.$value.'"  />';
								break;
							case 'jaitems':
								/**
								 * @todo display list items
								 */
								break;
						}
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php
	endif;
endif;
?>