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

<div class="magazine-item-media videos-item-media" itemprop="video">
	<?php echo JLayoutHelper::render('joomla.content.image.video', $displayData); ?>
	<?php $title = $item->category_title; ?>
</div>

<div class="magazine-item-main videos-item-main">
	<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $item); ?>

	<?php if (!$aparams->get('show_intro', 1)) : ?>
		<?php echo $item->event->afterDisplayTitle; ?>
	<?php endif; ?>
	<?php echo $item->event->beforeDisplayContent; ?>

	<?php if ($aparams->get('show_intro', 1)) : ?>
		<div class="videos-item-ct" itemprop="description">
			<?php echo $item->introtext; ?>
		</div>
	<?php endif; ?>

	<?php echo $item->event->afterDisplayContent; ?>
</div>
