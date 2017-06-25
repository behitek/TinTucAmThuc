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
		$aparams->get('show_publish_date') ||
		$aparams->get('show_hits') ||
		$aparams->get('show_category') ||
		$aparams->get('show_author');

?>

<div class="magazine-item link-item">
	<div class="col col-media" itemscope itemtype="http://schema.org/Article">
		<?php echo JLayoutHelper::render('joomla.content.image.intro', array('item' => $item, 'img-size' => 'big')); ?>
	</div>

	<div class="col col-content">

		<?php if ($useDefList && in_array($positions, array(0, 2))) : ?>
			<aside class="article-aside">
				<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_category', array('item' => $item, 'params' => $aparams, 'position'=>'above')); ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_block', array('item' => $item, 'params' => $aparams, 'position' => 'above')); ?>
			</aside>
		<?php endif; ?>

		<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $item); ?>

		<?php if ($useDefList && in_array($positions, array(1, 2))) : ?>
			<aside class="article-aside">
				<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_category', array('item' => $item, 'params' => $aparams, 'position'=>'above')); ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $item, 'params' => $aparams, 'position' => 'below')); ?>
			</aside>
		<?php endif; ?>
	</div>
</div>