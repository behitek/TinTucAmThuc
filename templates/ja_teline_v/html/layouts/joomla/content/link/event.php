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

<div class="item-event">
  <div class="col-sm-3 event-time">
    <span class="date"><?php echo JHtml::_('date', $item->params->get('ctm_start',''), 'DATE_FORMAT_TELINE_LC1'); ?></span>
    <span class="month-year"><?php echo JHtml::_('date', $item->params->get('ctm_start',''), 'DATE_FORMAT_TELINE_LC2'); ?></span>
  </div>

  <div class="col-sm-9 col-content">
    <?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $item); ?>
  </div>
</div>