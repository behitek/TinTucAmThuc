<?php
/**
 * ------------------------------------------------------------------------
 * JA Yahoo Finance
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

?>
<div id="ja-finance-<?php echo $module->id; ?>" class="ja-finance">
<table class="table">
	<thead>
	<tr>
		<th><?php echo JText::_('MOD_JAYAHOO_FINANCE_SYMBOL'); ?></th>
		<th><?php echo JText::_('MOD_JAYAHOO_FINANCE_COMPANY_NAME'); ?></th>
		<th><?php echo JText::_('MOD_JAYAHOO_FINANCE_PRICE'); ?></th>
		<th><?php echo JText::_('MOD_JAYAHOO_FINANCE_CHANGE'); ?></th>
		<th><?php echo JText::_('MOD_JAYAHOO_FINANCE_CHANGE'); ?></th>
		<th><?php echo JText::_('MOD_JAYAHOO_FINANCE_VOLUME'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($list as $item): ?>
		<?php
		$class = '';
		$change = (float) $item->Change;
		if($change > 0) {
			$class = 'change-up';
		} elseif ($change < 0) {
			$class = 'change-down';
		}
		?>
	<tr>
		<td><?php echo $item->symbol; ?></td>
		<td><?php echo $item->Name; ?></td>
		<td><?php echo $item->LastTradePriceOnly; ?></td>
		<td><span class="<?php echo $class; ?>"><?php echo $item->Change; ?></span></td>
		<td><span class="<?php echo $class; ?>"><?php echo $item->PercentChange; ?></span></td>
		<td><?php echo $item->Volume; ?></td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>
</div>