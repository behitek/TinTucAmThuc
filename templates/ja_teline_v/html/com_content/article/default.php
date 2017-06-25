<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$params   = $this->item->params;
$urls     = json_decode($this->item->urls);

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::addIncludePath(T3_PATH . '/html/com_content');
JHtml::addIncludePath(dirname(dirname(__FILE__)));
?>

<?php if (JFactory::getApplication()->input->get ('tmpl') == 'component'): ?>

	<?php echo JATemplateHelper::render ($this->item, 'joomla.content.item', array('print' => $this->print, 'item' => $this->item, 'params' => $this->params)) ?>

<?php else: ?>

	<?php if (JATemplateHelper::countModules ('article-top')): ?>
		<div class="item-row row-top">
			<?php echo JATemplateHelper::renderModules('article-top') ?>
		</div>
	<?php endif ?>

	<div class="item-row row-main">
		<div class="article-main">
			<?php echo JATemplateHelper::render ($this->item, 'joomla.content.item', array('print' => $this->print, 'item' => $this->item, 'params' => $this->params)) ?>

			<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position))) || (empty($urls->urls_position) && (!$params->get('urls_position')))): ?>
				<?php echo $this->loadTemplate('links'); ?>
			<?php endif; ?>
		</div>
	</div>

	<?php if (JATemplateHelper::countModules ('article-bottom')): ?>
		<div class="item-row row-bottom">
			<?php echo JATemplateHelper::renderModules('article-bottom') ?>
		</div>
	<?php endif ?>

<?php endif ?>