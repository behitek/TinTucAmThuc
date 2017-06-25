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
	<div role="tabpanel">

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<?php foreach($list as $index => $item): ?>
				<li role="presentation" title="<?php echo addslashes($item->Name); ?>" class="<?php if($index == 0) echo 'active'; ?>">
					<a href="#<?php echo 'tab-'.$module->id.'-'.$item->symbol; ?>" aria-controls="<?php echo 'tab-'.$module->id.'-'.$item->symbol; ?>" role="tab" data-toggle="tab">
						<?php echo $item->symbol; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<?php foreach($list as $index => $item): ?>
				<?php
				$class = '';
				$change = (float) $item->Change;
				if($change > 0) {
					$class = 'change-up';
				} elseif ($change < 0) {
					$class = 'change-down';
				}
				list($month, $day, $year) = explode('/', $item->LastTradeDate);
				$tradingTime = strtotime($year.'-'.$month.'-'.$day);
				?>
			<div role="tabpanel" class="tab-pane <?php if($index == 0) echo 'active'; ?>" id="<?php echo 'tab-'.$module->id.'-'.$item->symbol; ?>">

				<header class="finance-header">
					<h4><?php echo $item->Name; ?></h4>

					<span class="text finance-symbol"><?php echo $item->StockExchange; ?> : </span>
					<span class="text finance-symbol"><?php echo $item->symbol; ?></span> - 
					<small class="text finance-symbol"><?php echo date('d M', $tradingTime), ', ' , $item->LastTradeTime; ?></small>

					<div class="finance-snap-data">
						<strong class="text"><?php echo $item->LastTradePriceOnly; ?></strong>
						<div>
							<span class="<?php echo $class; ?>"><?php echo $item->Change; ?></span>
							<span class="<?php echo $class; ?>">(<?php echo $item->PercentChange; ?>)</span>
							<small class="warning">
								<?php echo JText::_('MOD_JAYAHOO_FINANCE_AFTER_HOURS'); ?>
								<span class="<?php echo $class; ?>"><?php echo $item->ChangeRealtime; ?></span>
								<span class="<?php echo $class; ?>"><?php echo preg_replace('/.*?([+\-0-9\.]+%)$/', '$1', $item->ChangePercentRealtime); ?></span>
							</small>
						</div>
					</div>
				</header>

				<table class="table">
					<tbody>
						<tr>
							<td width="15%" class="head"><?php echo JText::_('MOD_JAYAHOO_FINANCE_OPEN'); ?></td>
							<td width="25%"><?php echo $item->Open; ?></td>
							<td width="30%" class="head"><?php echo JText::_('MOD_JAYAHOO_FINANCE_MKTCAP'); ?></td>
							<td width="30%"><?php echo $item->MarketCapitalization; ?></td>
						</tr>
						<tr>
							<td class="head"><?php echo JText::_('MOD_JAYAHOO_FINANCE_HIGH'); ?></td>
							<td><?php echo $item->DaysHigh; ?></td>
							<td class="head"><?php echo JText::_('MOD_JAYAHOO_FINANCE_52WK_HIGHT'); ?></td>
							<td><?php echo $item->YearHigh; ?></td>
						</tr>
						<tr>
							<td class="head"><?php echo JText::_('MOD_JAYAHOO_FINANCE_LOW'); ?></td>
							<td><?php echo $item->DaysLow; ?></td>
							<td class="head"><?php echo JText::_('MOD_JAYAHOO_FINANCE_52WK_LOW'); ?></td>
							<td><?php echo $item->YearLow; ?></td>
						</tr>
						<tr>
							<td class="head"><?php echo JText::_('MOD_JAYAHOO_FINANCE_VOL'); ?></td>
							<td><?php echo ModJaYahooFinanceHelper::formartNumber($item->Volume); ?></td>
							<td class="head"><?php echo JText::_('MOD_JAYAHOO_FINANCE_AVG_VOL'); ?></td>
							<td><?php echo ModJaYahooFinanceHelper::formartNumber($item->AverageDailyVolume); ?></td>
						</tr>
						<tr>
							<td class="head"><?php echo JText::_('MOD_JAYAHOO_FINANCE_EPS'); ?></td>
							<td><?php echo $item->EPSEstimateCurrentYear; ?></td>
							<td class="head"><?php echo JText::_('MOD_JAYAHOO_FINANCE_PE'); ?></td>
							<td><?php echo $item->PERatio; ?></td>
						</tr>
						<tr>
							<td colspan="4" class="currency">
								<small><?php echo JText::_('MOD_JAYAHOO_FINANCE_CURRENCY'); ?> <strong><?php echo $item->Currency; ?></strong></small>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php endforeach; ?>
		</div>

	</div>
</div>