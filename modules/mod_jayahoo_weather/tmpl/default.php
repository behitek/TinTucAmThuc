<?php
/**
 * ------------------------------------------------------------------------
 * JA Yahoo Weather
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

?>
<div id="ja-weather-<?php echo $module->id; ?>" class="ja-weather">
	<table class="table table-striped">
		<tbody>
		<?php foreach($list as $index => $item): ?>
			<?php list($woeid, $info) = $item;
			if(!isset($info->item->condition)) continue;
			?>
			<tr>
				<td>
					<strong><?php echo $info->location->city; ?></strong><br/>
					<small><?php echo $info->location->country; ?></small>
				</td>
				<td>
					<?php echo ModJaYahooWeatherHelper::getIcon($info->item->condition->code, $info->item->condition->text); ?>
				</td>
				<td>
					<?php echo ModJaYahooWeatherHelper::formatTemperature($info->item->condition->temp, $info->units->temperature, $temperature_unit); ?>
				</td>
				<td>
					<?php echo ModJaYahooWeatherHelper::getText('MOD_JAYAHOO_WEATHER_CONDITION_CODE_'.$info->item->condition->code, $info->item->condition->text); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>

		<?php if($display_logo): ?>
			<tfoot>
				<tr>
					<td colspan="4">
						<a href="<?php echo $info->image->link; ?>" title="<?php echo $info->image->title; ?>" target="_blank">
							<img src="<?php echo $info->image->url; ?>" alt="<?php echo $info->image->title; ?>" width="<?php echo $info->image->width; ?>" height="<?php echo $info->image->height; ?>" />
						</a>
					</td>
				</tr>
			</tfoot>
		<?php endif; ?>
	</table>
</div>