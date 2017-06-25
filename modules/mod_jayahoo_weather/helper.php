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


abstract class ModJaYahooWeatherHelper
{
	public static function getList(&$params)
	{
		$yql = new jaYQLWeather();
		$places = $params->get('woeids');

		$places = preg_split("/[\r\n]+/", $places);
		foreach($places as $index => $place) {
			$places[$index] = trim($place);
		}

		$places = JArrayHelper::arrayUnique($places);
		$weathers = array();
		foreach($places as $place) {
			$woeid = 0;
			if(preg_match("/^[0-9]+$/", $place)) {
				//place is woeid
				$woeid = (int) $place;
			} else {

				$place = $yql->getGeoPlace($place);
				if($place && isset($place->woeid)) {
					$woeid = $place->woeid;
				}
			}

			if($woeid) {
				$info = $yql->getWeatherForecast($woeid);
				if($info) {
					$weathers[] = array($woeid, $info);
				}
			}
		}
        //var_dump($weathers[0][1]);
		return $weathers;
	}

	/**
	 * @param $code - Yahoo Weather Condition Code
	 */
	public static function getIcon($code) {
		//https://developer.yahoo.com/weather/documentation.html#codes
		$conditions = array(
			0 => 'wi-tornado',
			1 => 'wi-storm-showers',
			2 => 'wi-tornado',
			3 => 'wi-thunderstorm',
			4 => 'wi-thunderstorm',
			5 => 'wi-snow',
			6 => 'wi-rain-mix',
			7 => 'wi-rain-mix',
			8 => 'wi-sprinkle',
			9 => 'wi-sprinkle',
			10 => 'wi-hail',
			11 => 'wi-showers',
			12 => 'wi-showers',
			13 => 'wi-snow',
			14 => 'wi-storm-showers',
			15 => 'wi-snow',
			16 => 'wi-snow',
			17 => 'wi-hail',
			18 => 'wi-hail',
			19 => 'wi-cloudy-gusts',
			20 => 'wi-fog',
			21 => 'wi-fog',
			22 => 'wi-fog',
			23 => 'wi-cloudy-gusts',
			24 => 'wi-cloudy-windy',
			25 => 'wi-thermometer',
			26 => 'wi-cloudy',
			27 => 'wi-night-cloudy',
			28 => 'wi-day-cloudy',
			29 => 'wi-night-cloudy',
			30 => 'wi-day-cloudy',
			31 => 'wi-night-clear',
			32 => 'wi-day-sunny',
			33 => 'wi-night-clear',
			34 => 'wi-day-sunny-overcast',
			35 => 'wi-hail',
			36 => 'wi-day-sunny',
			37 => 'wi-thunderstorm',
			38 => 'wi-thunderstorm',
			39 => 'wi-thunderstorm',
			40 => 'wi-storm-showers',
			41 => 'wi-snow',
			42 => 'wi-snow',
			43 => 'wi-snow',
			44 => 'wi-cloudy',
			45 => 'wi-lightning',
			46 => 'wi-snow',
			47 => 'wi-thunderstorm',
			3200 => 'wi-cloud',
		);

		$code = (int) $code;

		$icon = isset($conditions[$code]) ? $conditions[$code] : 'wi-cloud';
		$icon = '<i class="wi '.$icon.'" title="'.JText::_("MOD_JAYAHOO_WEATHER_CONDITION_CODE_".$code).'"></i>';
		return $icon;
	}

	public static function formatTemperature($temperature, $inputUnit, $displayingUnit) {
		$inputUnit = strtoupper($inputUnit);
		$displayingUnit = strtoupper($displayingUnit);

		$format = '%d <sup class="deg">&deg;%s</sup>';
		if($inputUnit != $displayingUnit) {
			$converted = false;
			if($inputUnit == 'F') {
				switch($displayingUnit) {
					case 'C':
						$temperature = ($temperature  -  32)  *  5/9;
						$converted = true;
						break;
				}
			} elseif($inputUnit == 'C') {
				switch($displayingUnit) {
					case 'F':
						$temperature = $temperature  *  9/5 + 32;
						$converted = true;
						break;
				}
			}
			if(!$converted) {
				//can not convert unit
				$displayingUnit = $inputUnit;
			}
		}

		return sprintf($format, $temperature, $displayingUnit);
	}

    public static function getText($string, $default = ''){
		$lang = JFactory::getLanguage();
		if($lang->hasKey($string)) {
			return JText::_($string);
		} else {
			return $default;
		}
    }

	public static function formatSpeed($wind, $inputUnit, $displayingUnit){
		$format = '%.1f %s';
		if($inputUnit != $displayingUnit){
			$converted = false;
			if($inputUnit == 'km/h'){
				switch ($displayingUnit) {
					case 'mph':
						$wind = $wind/1.609344;
						$converted = true;
						break;
                    case 'm/s':
                        $wind = $wind/3.6;
                        $converted = true;
                        break;
				}
			}elseif($inputUnit == 'mph'){
				switch ($displayingUnit) {
					case 'km/h':
						$wind = $wind*1.609344;
						$converted = true;
						break;
                    case 'm/s':
                        $wind = $wind*1.609344/3.6;
                        $converted = true;
                        break;
				}
			}elseif($inputUnit == 'm/s'){
                switch ($displayingUnit){
                    case 'mph':
                        $wind = $wind*3.6/1.609344;
                        $converted = true;
                        break;
                    case 'km/h':
                        $wind = $wind*3.6;
                        $converted = true;
                        break;
                }
            }

			if(!$converted){
				$displayingUnit = $inputUnit;
			}
		}

		return sprintf($format, $wind, $displayingUnit);
	}

	public static function formatPressure($pressure, $inputUnit, $displayingUnit){
		$format = '%.3f %s';
		if($inputUnit != $displayingUnit){
			$converted = false;
			if($inputUnit == 'in'){
				switch ($displayingUnit) {
					case 'bar':
						$pressure = $pressure/29.53;
						$converted = true;
						break;
                    case 'atm':
                        $pressure = $pressure/29.92;
                        $converted = true;
                        break;
				}
			}elseif($inputUnit == 'bar'){
				switch ($displayingUnit) {
					case 'in':
						$pressure = $pressure*29.53;
						$converted =true;
						break;
                    case 'atm':
                        $pressure = $pressure/1.01325;
                        $converted = true;
                        break;
				}
			}elseif($inputUnit == 'atm'){
                switch ($displayingUnit){
                    case 'in':
                        $pressure = $pressure*29.92;
                        $converted = true;
                        break;
                    case 'bar':
                        $pressure = $pressure*1.01325;
                        $converted = true;
                        break;
                }
            }
			if(!$converted){
				$displayingUnit = $inputUnit;
			}
		}

		return sprintf($format, $pressure, $displayingUnit);
	}

	public static function getWindDirectionIcon($direction) {
		//http://www.windfinder.com/wind/windspeed.htm
		//Abbreviation 	wind direction 		Degrees
		//N 			North 				0°
		//NNE 			NorthNorthEast 		22.5°
		//NE 			NorthEast 			45°
		//ENE 			EastNorthEast 		67.5°
		//E 			East 				90°
		//ESE 			EastSouthEast 		112.5°
		//SE 			SouthEast 			135°
		//SSE 			SouthSouthEast 		157.5°
		//S 			South 				180°
		//SSW 			SouthSouthwest 		202.5°
		//SW 			Southwest 			225°
		//WSW 			WestSouthwest 		247.5°
		//W 			West 				270°
		//WNW 			WestNorthwest 		292.5°
		//NW 			Northwest 			315°
		//NNW 			NorthNorthwest 		337.5°

		$pieceSize = 22.5; // ~ 1/16 of 360 degrees
		$piecesPerPart = 2;

		$directions = array(
			0 => 'North',
			1 => 'North-North-East',
			2 => 'North-East',
			3 => 'East-North-East',
			4 => 'East',
			5 => 'East-South-East',
			6 => 'South-East',
			7 => 'South-South-East',
			8 => 'South',
			9 => 'South-South-West',
			10 => 'South-West',
			11 => 'West-South-West',
			12 => 'West',
			13 => 'West-North-West',
			14 => 'North-West',
			15 => 'North-North-West'
		);

		$windDir = '';
		$text = JText::sprintf('MOD_JAYAHOO_WEATHER_VAR_DEGREES', $direction);
		foreach($directions as $index => $icon) {
			$haftPart = $pieceSize / 2;
			if($index == 0) {
				if(($direction > 360 - $haftPart) || ($direction <= $haftPart)) {
					$windDir = $icon;
					break;
				}
			} else {
				$start = $pieceSize * $index - $haftPart;
				if(($direction > $start) && ($direction <= $start + $pieceSize)) {
					$windDir = $icon;
					break;
				}

			}
		}
		$iconDir = sprintf('wi-wind-default _%d-deg', floor($direction / 15) * 15);

		if($windDir) {
			$lang = JFactory::getLanguage();
			$langKey = 'MOD_JAYAHOO_WEATHER_WIND_'.strtoupper(str_replace('-', '_', $windDir));
			if($lang->hasKey($langKey)) {
				$text = JText::_($langKey) . ' ('.$text.')';
			} else {
				$text = str_replace('-', ' ', $windDir) . ' ('.$text.')';
			}
		}
		$icon = '<i class="wi '.$iconDir.'" title="'.htmlspecialchars($text).'"></i>';
		return $icon;
	}
}
