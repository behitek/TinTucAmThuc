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
    <!-- Tab panes -->
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      <?php foreach($list as $index => $item): ?>
        <?php list($woeid, $info) = $item;
                if(!isset($info->item->condition)) continue;
              ?>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne<?php echo '-'.$module->id.'-'.$woeid; ?>">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo 'tab-'.$module->id.'-'.$woeid; ?>" aria-expanded="<?php if($index == 0) echo 'true'; else echo 'false'; ?>" aria-controls="<?php echo 'tab-'.$module->id.'-'.$woeid; ?>">
                  <?php echo $info->location->city; ?>
                </a>
                <span>
                  <?php echo ModJaYahooWeatherHelper::getIcon($info->item->condition->code,$info->item->condition->text); ?>
                  <?php echo ModJaYahooWeatherHelper::formatTemperature($info->item->condition->temp, $info->units->temperature, $temperature_unit); ?>
                </span>
              </h4>
            </div>
            <div id="<?php echo 'tab-'.$module->id.'-'.$woeid; ?>" class="panel-collapse collapse <?php if($index == 0) echo 'in'; ?>" role="tabpanel" aria-labelledby="headingOne<?php echo '-'.$module->id.'-'.$woeid; ?>" aria-expanded="<?php if($index == 0) echo 'true'; else echo 'false'; ?>">
              <div class="panel-body">

                <header class="weather-header">
                 <span class="icon icon-large condition">
                    <?php echo ModJaYahooWeatherHelper::getIcon($info->item->condition->code, $info->item->condition->text); ?>
                  </span>
                  <div>
                    <span class="text city">
                      <?php echo $info->location->city; ?>
                      <small><?php echo $info->location->country; ?></small>
                    </span>
                    <span class="text weather-info">
                      <?php echo ModJaYahooWeatherHelper::getText('MOD_JAYAHOO_WEATHER_CONDITION_CODE_'.$info->item->condition->code, $info->item->condition->text); ?>,
                      <?php echo ModJaYahooWeatherHelper::formatTemperature($info->item->condition->temp, $info->units->temperature, $temperature_unit); ?>
                    </span>
                  </div>
                </header>

                <section class="weather-detail">
                  <table class="table">
                    <thead>
                    <tr>
                      <th colspan="3"><?php echo JText::_('MOD_JAYAHOO_WEATHER_CURRENT_CONDITIONS'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td colspan="3">
                        <?php
                        $info->astronomy->sunrise = preg_replace('/^([0-9\:]+).*/', '$1', $info->astronomy->sunrise). ' '.JText::_('MOD_JAYAHOO_WEATHER_TIME_AM');
                        $info->astronomy->sunset = preg_replace('/^([0-9\:]+).*/', '$1', $info->astronomy->sunset). ' '.JText::_('MOD_JAYAHOO_WEATHER_TIME_PM');
                        ?>
                        <?php echo JText::_('MOD_JAYAHOO_WEATHER_SUNRISE'); ?> <?php echo $info->astronomy->sunrise; ?>
                        &nbsp; | &nbsp;
                        <?php echo JText::_('MOD_JAYAHOO_WEATHER_SUNSET'); ?> <?php echo $info->astronomy->sunset; ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <span title="<?php echo JText::_('MOD_JAYAHOO_WEATHER_HUMIDITY'); ?>"><i class="wi wi-thermometer"></i> <?php echo $info->atmosphere->humidity . '%'; ?></span>
                        &nbsp;&nbsp;&nbsp;
                        <span title="<?php echo JText::_('MOD_JAYAHOO_WEATHER_WIND'); ?>">
                          <?php echo ModJaYahooWeatherHelper::getWindDirectionIcon($info->wind->direction); ?>
                          <?php echo ModJaYahooWeatherHelper::formatSpeed($info->wind->speed, $info->units->speed, $wind_unit); ?>
                        </span>
                        &nbsp;&nbsp;&nbsp;
                        <span title="<?php echo JText::_('MOD_JAYAHOO_WEATHER_PRESSURE'); ?>"><i class="wi wi-sprinkles"></i> <?php echo ModJaYahooWeatherHelper::formatPressure($info->atmosphere->pressure,$info->units->pressure, $pressure_unit); ?></span>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </section>

                <section class="weather-forecast">
                  <table class="table">
                    <thead>
                    <tr>
                      <th colspan="4"><?php echo JText::_('MOD_JAYAHOO_WEATHER_FORECAST'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($info->item->forecast as $forecast):
                        $date = new JDate($forecast->date);
                        $forecast->day = $date->format('D');
                        $forecast->date = $date->format(JText::_('DATE_FORMAT_LC'));
                    ?>
                    <tr>
                      <td><span class="text date" title="<?php echo $forecast->date; ?>"><?php echo JText::_(strtoupper($forecast->day)); ?></span></td>
                      <td><?php echo ModJaYahooWeatherHelper::getIcon($forecast->code,$info->item->condition->text); ?></td>
                      <td><span class="text temp-low"><?php echo JText::sprintf('MOD_JAYAHOO_WEATHER_LOW_VAR', ModJaYahooWeatherHelper::formatTemperature($forecast->low, $info->units->temperature, $temperature_unit)); ?></span></td>
                      <td><span class="text temp-high"><?php echo JText::sprintf('MOD_JAYAHOO_WEATHER_HIGH_VAR', ModJaYahooWeatherHelper::formatTemperature($forecast->high, $info->units->temperature, $temperature_unit)); ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>
                </section>

                <?php //echo @$info->item->description; ?>
                <?php if($display_logo): ?>
                  <div class="source-logo">
                    <a href="<?php echo $info->image->link; ?>" title="<?php echo $info->image->title; ?>" target="_blank">
                    <img src="<?php echo $info->image->url; ?>" alt="<?php echo $info->image->title; ?>" width="<?php echo $info->image->width; ?>" height="<?php echo $info->image->height; ?>" />
                    </a>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        
      <?php endforeach; ?>
    </div>

</div>