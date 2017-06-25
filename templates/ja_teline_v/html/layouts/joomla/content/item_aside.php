<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$params = $displayData['params'];
$item = $displayData['item'];
$blockPosition = $params->get('info_block_position', 2);
?>

<aside class="article-aside clearfix">
	<dl class="article-info pull-left">
		<dt class="article-info-term">
			<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
		</dt>
    <dd class="hidden"></dd>
		<?php if ($params->get('show_parent_category') && !empty($item->parent_slug)) : ?>
			<?php echo JLayoutHelper::render('joomla.content.info_block.parent_category', $displayData); ?>
		<?php endif; ?>

		<?php if ($params->get('show_category')) : ?>
			<?php echo JLayoutHelper::render('joomla.content.info_block.category', $displayData); ?>
		<?php endif; ?>

		<?php if ($params->get('show_author') && !empty($item->author)) : ?>
			<?php echo JLayoutHelper::render('joomla.content.info_block.author', $displayData); ?>
		<?php endif; ?>

		<?php if ($params->get('show_publish_date')) : ?>
			<?php echo JLayoutHelper::render('joomla.content.info_block.publish_date', $displayData); ?>
		<?php endif; ?>

		<?php if ($blockPosition == 0) : ?>
			<?php if ($params->get('show_modify_date')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.modify_date', $displayData); ?>
			<?php endif; ?>
			<?php if ($params->get('show_create_date')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.create_date', $displayData); ?>
			<?php endif; ?>

			<?php if ($params->get('show_hits')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.hits', $displayData); ?>
			<?php endif; ?>
		<?php endif; ?>
	</dl>

	<?php echo JLayoutHelper::render('joomla.content.icons', $displayData); ?>

</aside>