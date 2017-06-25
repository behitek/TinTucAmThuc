<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.pagenavigation
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$table = JTable::getInstance ('content');
$prev_title = $next_title = '';
if ($row->prev) {
	$prev = $rows[$location - 1];
	$table->load ($prev->id);
	$prev_title = $table->title;
}
if ($row->next) {
	$next = $rows[$location + 1];
	$table->load ($next->id);
	$next_title = $table->title;
}
?>
<ul class="pager pagenav">

  <?php if ($row->prev) : ?>
	<li class="previous">
  	<a href="<?php echo $row->prev; ?>" rel="prev">
      <i class="fa fa-caret-left"></i>
      <span><?php echo JText::_('TPL_PREV_ARTICLE'); ?></span>
      <strong><?php echo $prev_title; ?></strong>
    </a>
	</li>
  <?php endif; ?>

  <?php if ($row->next) : ?>
	<li class="next">
  	<a href="<?php echo $row->next; ?>" rel="next">
      <i class="fa fa-caret-right"></i>
      <span><?php echo JText::_('TPL_NEXT_ARTICLE'); ?></span>
      <strong><?php echo $next_title; ?></strong>
    </a>
	</li>
  <?php endif; ?>
  
</ul>
