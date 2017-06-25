<?php
/**
 * ------------------------------------------------------------------------
 * Plugin Ajax JA Content Type
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

$input = JFactory::getApplication()->input;

$baseURL   		= $displayData['baseURL'];
$docs   		= $displayData['docs'];
$images   		= $displayData['images'];
$folders   		= $displayData['folders'];
$state   		= $displayData['state'];

$currFolder = $input->get('folder');
$upFolder = false;
if($currFolder) {
	$paths = explode('/', $currFolder);
	$upFolder = count($paths) > 1 ? implode('/', array_shift($paths)) : '';
}
$filterExtensions = explode(',', $input->get('filter_exts', '', 'raw'));
?>
	<ul class="manager thumbnails">
		<?php if($upFolder !== false): ?>
			<li class="imgOutline thumbnail height-80 width-80 center">
				<a href="index.php?option=com_ajax&amp;plugin=jacontenttype&amp;view=mediaList&amp;tmpl=component&amp;format=html&amp;folder=<?php echo $upFolder; ?>&amp;filter_exts=<?php echo $input->get('filter_exts', '', 'raw');?>&amp;asset=<?php echo $input->getCmd('asset');?>&amp;author=<?php echo $input->getCmd('author');?>" target="imageframe" title="<?php echo JText::_('Up', true); ?>">
					<div class="height-50">
						<i class="icon-folder-2"></i>
					</div>
					<div class="small">
						..
					</div>
				</a>
			</li>
		<?php endif; ?>
		<?php for ($i = 0, $n = count($folders); $i < $n; $i++) :
			$item = $folders[$i];
		?>

			<li class="imgOutline thumbnail height-80 width-80 center">
				<a href="index.php?option=com_ajax&amp;plugin=jacontenttype&amp;view=mediaList&amp;tmpl=component&amp;format=html&amp;folder=<?php echo $item->path_relative; ?>&amp;filter_exts=<?php echo $input->get('filter_exts', '', 'raw');?>&amp;asset=<?php echo $input->getCmd('asset');?>&amp;author=<?php echo $input->getCmd('author');?>" target="imageframe">
					<div class="height-50">
						<i class="icon-folder-2"></i>
					</div>
					<div class="small">
						<?php echo JHtml::_('string.truncate', $item->name, 10, false); ?>
					</div>
				</a>
			</li>
		<?php endfor; ?>

		<?php for ($i = 0, $n = count($docs); $i < $n; $i++) :
			$item = $docs[$i];
			if(count($filterExtensions) && !in_array(strtolower(JFile::getExt($item->name)), $filterExtensions)) continue;
		?>

			<li class="imgOutline thumbnail height-80 width-80 center">
				<a class="img-preview" href="javascript:ImageManager.populateFields('<?php echo $item->path_relative; ?>')" title="<?php echo $item->name; ?>" >
					<div class="height-50">
						<?php  echo JHtml::_('image', $item->icon_16, $item->title, null, true, true) ? JHtml::_('image', $item->icon_16, $item->title, array('width' => 16, 'height' => 16), true) : JHtml::_('image', 'media/con_info.png', $item->title, array('width' => 16, 'height' => 16), true);?>
					</div>
					<div class="small">
						<?php echo JHtml::_('string.truncate', $item->name, 10, false); ?>
					</div>
				</a>
			</li>
		<?php endfor; ?>

		<?php for ($i = 0, $n = count($images); $i < $n; $i++) :
			$item = $images[$i];
			if(count($filterExtensions) && !in_array(strtolower(JFile::getExt($item->name)), $filterExtensions)) continue;
		?>

			<li class="imgOutline thumbnail height-80 width-80 center">
				<a class="img-preview" href="javascript:ImageManager.populateFields('<?php echo $item->path_relative; ?>')" title="<?php echo $item->name; ?>" >
					<div class="height-50">
						<?php echo JHtml::_('image', $baseURL . '/' . $item->path_relative, JText::sprintf('COM_MEDIA_IMAGE_TITLE', $item->title, JHtml::_('number.bytes', $item->size)), array('width' => $item->width_60, 'height' => $item->height_60)); ?>
					</div>
					<div class="small">
						<?php echo JHtml::_('string.truncate', $item->name, 10, false); ?>
					</div>
				</a>
			</li>
		<?php endfor; ?>
	</ul>
