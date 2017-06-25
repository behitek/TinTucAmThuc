<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$print = $displayData['print'];
$item = $displayData['item'];
$params = $item->params;
$positions = $params->get('block_position', 0);

$useDefList =
	($params->get('show_modify_date') ||
		$params->get('show_publish_date') ||
		$params->get('show_create_date') ||
		$params->get('show_hits') ||
		$params->get('show_category') ||
		$params->get('show_parent_category') ||
		$params->get('show_author'));
$icons = $params->get('show_print_icon') || $params->get('show_email_icon');

$tplparams = JFactory::getApplication()->getTemplate(true)->params;
$typo_tools = $params->get('show_typo_tools', '') == '' ? $tplparams->get('show_typo_tools', 1) : $params->get('show_typo_tools');
$sharing_tools = $params->get('show_sharing_tools', '') == '' ? $tplparams->get('show_sharing_tools', 1) : $params->get('show_sharing_tools');
$tools = $icons || $typo_tools || $sharing_tools;

// split introtext & text
if ($params->get('show_intro', 0)) {
	$pos = strpos($item->text, $item->introtext);
	if ($pos !== false) {
		$item->text = substr_replace ($item->text, '', $pos, strlen($item->introtext));
	}
}
?>
<article class="article" itemscope itemtype="http://schema.org/Article">
	<meta itemscope itemprop="mainEntityOfPage"  itemType="https://schema.org/WebPage" itemid="https://google.com/article"/>
	<meta itemprop="inLanguage" content="<?php echo ($item->language === '*') ? JFactory::getConfig()->get('language') : $item->language; ?>" />
	<meta itemprop="url" content="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)) ?>" />
	<?php if ($params->get('show_title')) : ?>
		<?php echo JLayoutHelper::render('joomla.content.item_title', array('item' => $item, 'params' => $params, 'title-tag'=>'h1')); ?>
	<?php endif; ?>
	<?php if ($print || ($useDefList && in_array($positions, array(0, 2)))) : ?>
		<aside class="article-aside article-aside-full">
			<?php if ($useDefList && in_array($positions, array(0, 2))) : ?>
			<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_block_item', array('item' => $item, 'params' => $params, 'position' => 'above')); ?>
			<?php endif ?>

			<?php if ($print): ?>
				<div id="pop-print" class="hidden-print">
					<?php echo JHtml::_('icon.print_screen', $item, $params); ?>
				</div>
			<?php endif ?>
		</aside>
	<?php endif; ?>

	<section class="article-intro-media">
		<?php echo JLayoutHelper::render('joomla.content.image.intro', $displayData); ?>
		<?php $title = $item->category_title; ?>

		<?php if ($params->get('show_category')) : ?>
			<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_category', array('item' => $item, 'params' => $params)); ?>
		<?php endif; ?>
	</section>

	<section class="row article-navigation top">
		<?php if (isset ($item->pagination)) echo $item->pagination ?>
	</section>

	<section class="article-full<?php if($tools) :?> has-article-tools<?php endif ?>">

		<?php if ($tools): ?>
    <div class="article-tools">

			<?php if ($icons): ?>
    	<div class="default-tools">
    		<h6><?php echo JText::_('TPL_DEFAULT_TOOL_TITLE') ?></h6>
        <?php echo JLayoutHelper::render('joomla.content.magazine_icons', array('item' => $item, 'params' => $params)); ?>
      </div>
			<?php endif; ?>

			<?php if ($typo_tools): ?>
			<?php echo JLayoutHelper::render('joomla.content.typo_tools', array()); ?>
			<?php endif ?>

			<?php if ($sharing_tools): ?>
			<?php echo JLayoutHelper::render('joomla.content.sharing_tools', array()); ?>
			<?php endif ?>
    </div>
		<?php endif ?>

		<div class="article-content-main">

		<?php if (!$params->get('show_intro', 1)) : ?>
			<?php echo $item->event->afterDisplayTitle; ?>
		<?php endif; ?>
		<?php echo $item->event->beforeDisplayContent; ?>

		<?php if ($params->get('show_intro', 0)) : ?>
			<blockquote class="article-intro" itemprop="description">
				<?php echo $item->introtext; ?>
			</blockquote>
		<?php endif; ?>

			<section class="article-content" itemprop="articleBody">
				<?php echo JLayoutHelper::render('joomla.content.info_block.topic', array('item' => $item)); ?>
				<?php echo $item->text; ?>
				
				<?php if ($params->get('show_tags', 1) && !empty($item->tags)) : ?>
					<?php echo JLayoutHelper::render('joomla.content.tags', $item->tags->itemTags); ?>
				<?php endif; ?>
			</section>

		<?php if ($useDefList && in_array($positions, array(1, 2))) : ?>
			<footer class="article-footer">
				<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $item, 'params' => $params, 'position' => 'below')); ?>
			</footer>
		<?php endif; ?>
		

		<?php if ($params->get('show_readmore') && $item->readmore) :
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

			<div class="readmore">
				<a class="btn btn-default" href="<?php echo $link; ?>"><span>
					<?php if (!$item->params->get('access-view')) :
						echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
					elseif ($readmore = $item->alternative_readmore) :
						echo $readmore;
						if ($params->get('show_readmore_title', 0) != 0) :
							echo JHtml::_('string.truncate', ($item->title), $params->get('readmore_limit'));
						endif;
					elseif ($params->get('show_readmore_title', 0) == 0) :
						echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
					else :
						echo JText::_('COM_CONTENT_READ_MORE');
						echo JHtml::_('string.truncate', ($item->title), $params->get('readmore_limit'));
					endif; ?>
				</span></a>
			</div>

		<?php endif; ?>

		<?php echo $item->event->afterDisplayContent; ?>

		</div>
	</section>

	<section class="row article-navigation bottom">
		<?php if (isset ($item->pagination)) echo $item->pagination ?>
	</section>

</article>
