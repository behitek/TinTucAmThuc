<?php
/**
 *------------------------------------------------------------------------------
 * @package Purity III Template - JoomlArt
 * @version 1.0 Feb 1, 2014
 * @author JoomlArt http://www.joomlart.com
 * @copyright Copyright (c) 2004 - 2014 JoomlArt.com
 * @license GNU General Public License version 2 or later;
 *------------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

JHtml::_('behavior.caption');
$items = $this->items;
?>

<div class="blog<?php echo $this->pageclass_sfx;?>" itemscope itemtype="http://schema.org/Blog">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>

	<?php if (!empty($items)) : ?>
		<div class="blog-items clearfix">
			<?php foreach ($items as &$item) : ?>
				<?php echo JLayoutHelper::render('joomla.content.item.event', array('item' => $item, 'params' => $this->params)); ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
			<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php  endif; ?>
</div>
