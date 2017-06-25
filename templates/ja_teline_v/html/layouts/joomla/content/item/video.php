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
$aparams->set('show_category', 1);

$hasSidebar = JATemplateHelper::countModules('video-sidebar');
$colwidth = $hasSidebar ? 'col-sm-8' : 'col-sm-12';
$layout = JFactory::getApplication()->input->get('layout');
if ($layout == 'videoplayer'):
	$item->autoplay = JFactory::getApplication()->input->getInt('autoplay', 0);
	echo JLayoutHelper::render('joomla.content.video_play', array('item' => $item, 'context' => 'iframe'));
else:
	?>

	<div class="video-main" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
		<?php if (!$title = $item->category_title) : ?>
			<h1 itemprop="title"><?php $title = $item->category_title; ?></h1>
		<?php endif; ?>

		<div class="magazine-item-main videos-item-main">
			<section class="video-wrap">
				<div id="ja-main-player" class="main-player" itemprop="video">
					<?php echo JLayoutHelper::render('joomla.content.video_play', array('item' => $item, 'context' => 'featured')); ?>
				</div>
			</section>

			<div class="row">
				<div class="col video-info <?php echo $colwidth ?>">
					<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $item); ?>

					<?php if ($useDefList && in_array($positions, array(0, 2))) : ?>
						<aside class="article-aside article-aside-full">
							<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_block', array('item' => $item, 'params' => $aparams, 'position' => 'above')); ?>
						</aside>
					<?php endif; ?>

					<?php if (!$aparams->get('show_intro', 1)) : ?>
						<?php echo $item->event->afterDisplayTitle; ?>
					<?php endif; ?>

					<?php echo $item->event->beforeDisplayContent; ?>

					<?php if ($aparams->get('show_intro', 1)) : ?>
						<div class="magazine-item-ct" itemprop="description">
							<?php echo JLayoutHelper::render('joomla.content.info_block.topic', array('item' => $item)); ?>
							<?php echo $item->introtext; ?>
						</div>
					<?php endif; ?>

					<?php echo $item->event->afterDisplayContent; ?>
				</div>

				<?php if ($hasSidebar): ?>
					<div class="col col-sm-4 video-sidebar">
						<?php echo JATemplateHelper::renderModules('video-sidebar',array('style'=>'T3XHTML')) ?>
					</div>
				<?php endif ?>

			</div>
		</div>
	</div>
<?php endif; ?>