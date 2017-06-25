<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$item = $displayData['item'];
$aparams = $displayData['params'];
$positions = $aparams->get('block_position', 0);

$useDefList =
	($aparams->get('show_modify_date') ||
		$aparams->get('show_publish_date') ||
		$aparams->get('show_create_date') ||
		$aparams->get('show_hits') ||
		$aparams->get('show_category') ||
		$aparams->get('show_parent_category') ||
		$aparams->get('show_author'));
$aparams->set('show_category',1);
?>
<div class="photo-item-ct">
	<div class="magazine-item-media">
	<?php echo JLayoutHelper::render('joomla.content.image.intro', $displayData); ?>
	<?php $title = $item->category_title; ?>

	<?php if ($aparams->get('show_category')) : ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_category', array('item' => $item, 'params' => $aparams)); ?>
	<?php endif; ?>
</div>

<div class="magazine-item-main" itemscope itemtype="http://schema.org/ImageObject">

	<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $item); ?>

	<?php if ($useDefList && in_array($positions, array(0, 2))) : ?>
		<aside class="article-aside clearfix">
			<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_block', array('item' => $item, 'params' => $aparams, 'position' => 'above')); ?>
		</aside>
	<?php endif; ?>

	<?php if (!$aparams->get('show_intro', 1)) : ?>
		<?php echo $item->event->afterDisplayTitle; ?>
	<?php endif; ?>
	<?php echo $item->event->beforeDisplayContent; ?>

	<?php echo JLayoutHelper::render('joomla.content.gallery_image_detail', array('item' => $item)); ?>

	<?php if ($useDefList && in_array($positions, array(1, 2))) : ?>
		<aside class="article-aside clearfix">
			<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $item, 'params' => $aparams, 'position' => 'below')); ?>
		</aside>
	<?php endif; ?>

	<div class="magazine-item-ct" itemprop="description">
		<?php if ($aparams->get('show_intro', 0)) : ?>
			<blockquote class="article-intro">
				<?php echo $item->introtext; ?>
			</blockquote>
		<?php endif; ?>

		<section class="article-content">
			<?php echo JLayoutHelper::render('joomla.content.info_block.topic', array('item' => $item)); ?>
			<?php echo $item->fulltext; ?>
		</section>
	</div>

	<?php if ($aparams->get('show_readmore') && $item->readmore) :
		if ($item->params->get('access-view')) :
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
		else :
			$menu = JFactory::getApplication()->getMenu();
			$active = $menu->getActive();
			$itemId = $active->id;
			$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
			$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
			$link = new JUri($link1);
			$link->setVar('return', base64_encode($returnURL));
		endif; ?>

		<section class="readmore">
			<a class="btn btn-default" href="<?php echo $link; ?>" itemprop="url"><span>
				<?php if (!$item->params->get('access-view')) :
					echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
				elseif ($readmore = $item->alternative_readmore) :
					echo $readmore;
					if ($aparams->get('show_readmore_title', 0) != 0) :
						echo JHtml::_('string.truncate', ($item->title), $aparams->get('readmore_limit'));
					endif;
				elseif ($aparams->get('show_readmore_title', 0) == 0) :
					echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
				else :
					echo JText::_('COM_CONTENT_READ_MORE');
					echo JHtml::_('string.truncate', ($item->title), $aparams->get('readmore_limit'));
				endif; ?>

				</span></a>
		</section>

	<?php endif; ?>

	<?php echo $item->event->afterDisplayContent; ?>
</div>
</div>
