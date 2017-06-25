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
$author = ($item->created_by_alias ? $item->created_by_alias : $item->author);
$author = '<span itemprop="name">' . $author . '</span>';
$authorobj = JUser::getInstance($item->created_by);
$ahtorparams = new JRegistry;
$ahtorparams->loadString ($authorobj->params);
$avatar = $ahtorparams->get ('avatar');

$title = $this->escape($item->category_title);
if (!isset($item->catslug)) {
	$item->catslug = $item->category_alias ? ($item->catid.':'.$item->category_alias) : $item->catid;
}

$blockPosition = $displayData['params']->get('info_block_position', 0);

?>
	<?php if ($avatar) : ?>
		<span class="author-img"><img src="<?php echo $avatar ?>" alt="<?php echo $authorobj->name ?>" /></span>
	<?php endif ?>

	<dl class="article-info muted">

		<?php if ($displayData['position'] == 'above' && ($blockPosition == 0 || $blockPosition == 2)
				|| $displayData['position'] == 'below' && ($blockPosition == 1)
				) : ?>

			<dt class="article-info-term">
				<?php // TODO: implement info_block_show_title param to hide article info title ?>
				<?php if ($displayData['params']->get('info_block_show_title', 1)) : ?>
					<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
				<?php endif; ?>
			</dt>
      <dd class="hidden"></dd>
			<?php if ($displayData['params']->get('show_author') && !empty($displayData['item']->author )) : ?>
				<dd class="createdby hasTooltip" itemprop="author">
					<strong><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', ''); ?></strong>
					<?php if (!empty($displayData['item']->contact_link ) && $displayData['params']->get('link_author') == true) : ?>
						<?php echo JHtml::_('link', $displayData['item']->contact_link, $author, array('itemprop' => 'url')); ?>
					<?php else :?>
						<?php echo $author; ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_category')) : ?>
				<dd class="category-name hasTooltip" title="<?php echo JText::sprintf('COM_CONTENT_CATEGORY', ''); ?>">
					<strong><?php echo JText::sprintf('COM_CONTENT_CATEGORY', ''); ?></strong>
					<?php if ($displayData['params']->get('link_category') && $item->catslug) : ?>
						<?php echo JHtml::_('link', JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug)), '<span itemprop="genre">'.$title.'</span>'); ?>
					<?php else : ?>
						<?php echo $title ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_publish_date')) : ?>
				<dd class="published hasTooltip" title="<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', ''); ?>">
					<strong><?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', ''); ?></strong>
					<time datetime="<?php echo JHtml::_('date', $displayData['item']->publish_up, 'c'); ?>" itemprop="datePublished">
						<?php echo JHtml::_('date', $displayData['item']->publish_up, JText::_('DATE_FORMAT_LC3')); ?>
					</time>
				</dd>
			<?php endif; ?>

		<?php endif; ?>

		<?php if ($displayData['position'] == 'above' && ($blockPosition == 0)
				|| $displayData['position'] == 'below' && ($blockPosition == 1 || $blockPosition == 2)
				) : ?>
			<?php if ($displayData['params']->get('show_create_date')) : ?>
        <dd class="create">
          <strong><?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', ''); ?></strong>
  				<time datetime="<?php echo JHtml::_('date', $displayData['item']->created, 'c'); ?>" itemprop="dateCreated">
  					<?php echo JHtml::_('date', $displayData['item']->created, JText::_('DATE_FORMAT_LC3')); ?>
  				</time>
        </dd>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_modify_date')) : ?>
				<dd class="modified">
          <strong><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', ''); ?></strong>
  				<time datetime="<?php echo JHtml::_('date', $displayData['item']->modified, 'c'); ?>" itemprop="dateModified">
  					<?php echo JHtml::_('date', $displayData['item']->modified, JText::_('DATE_FORMAT_LC3')); ?>
  				</time>
  			</dd>
			<?php endif; ?>

			<?php if ($displayData['params']->get('show_hits')) : ?>
				<strong><?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', ''); ?></strong>
				<?php echo JLayoutHelper::render('joomla.content.info_block.hits', $displayData); ?>
			<?php endif; ?>
		<?php endif; ?>
	</dl>
