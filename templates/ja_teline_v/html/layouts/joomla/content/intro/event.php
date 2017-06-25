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

?>

<div class="magazine-item-media">
	<span class="media-mask"></span>
	<?php echo JLayoutHelper::render('joomla.content.image.intro', $displayData); ?>
	<?php $title = $item->category_title; ?>
</div>

<div class="magazine-item-main">
	<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $item); ?>

	<?php if (!$aparams->get('show_intro', 1)) : ?>
		<?php echo $item->event->afterDisplayTitle; ?>
	<?php endif; ?>
	<?php echo $item->event->beforeDisplayContent; ?>

	<?php if ($aparams->get('show_intro', 1)) : ?>
		<div class="magazine-item-ct">
			<?php echo $item->introtext; ?>
		</div>
	<?php endif; ?>

	<div class="event-info">
  	<ul>
  		<li>
			<i class="fa fa-calendar-o"></i>
			<span itemprop="startDate"><?php echo JHtml::_('date', $item->params->get('ctm_start',''), 'DATE_FORMAT_LC3'); ?></span>
			<?php if($item->params->get('ctm_end') != ''): ?>
            -
			<span itemprop="endDate"><?php echo JHtml::_('date', $item->params->get('ctm_end',''), 'DATE_FORMAT_LC3'); ?></span>
			<?php endif; ?>
            </li>
  		
  		<li itemprop="location">
  			<i class="fa fa-building"></i><?php echo $item->params->get('ctm_venue',''); ?>
  		</li>
  		
  		<li itemprop="address">
  			<i class="fa fa-location-arrow"></i><?php echo $item->params->get('ctm_addr1',''); ?>
  		</li>
  	</ul>
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
			<a itemprop="url" class="btn btn-default" href="<?php echo $link; ?>"><span>

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
