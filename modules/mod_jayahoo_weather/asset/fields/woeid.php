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

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Platform.
 *
 * Provides a list of Where On the Earth Identifiers (WOEID)
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldWoeid extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Woeid';


	protected function getOptions()
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$modPath = JPATH_ROOT . '/modules/mod_jayahoo_weather/';
		$dataPath = $modPath . 'data/';

		require_once $modPath . 'yql.php';
		$yql = new jaYQLWeather();

		$woeids = array();
		//get list countries
		$continents = JFolder::files($dataPath.'countries/', '.', false, true);
		foreach($continents as $continent) {
			$data = json_decode(file_get_contents($continent));
			if(is_object($data) && isset($data->query->results->place)) {
				foreach ($data->query->results->place as $country) {
					$woeids[] = JHtml::_('select.option', '<OPTGROUP>', $country->name);
					$woeids[] = JHtml::_('select.option', $country->woeid, '['.$country->name.']');
					//get list states
					$file = $dataPath . 'states/'.$country->name.'.json';
					if(file_exists($file)) {
						$dataState = json_decode(file_get_contents($file));
					} else {
						$dataState = $yql->getGeoStates($country->name);
						if($dataState) {
							JFile::write($file, json_encode($dataState));
						}
					}
					if($dataState) {
						foreach($dataState as $state) {
							$woeids[] = JHtml::_('select.option', $state->woeid, $state->name);
						}
					}
					$woeids[] = JHtml::_('select.option', '</OPTGROUP>', $country->name);
				}
			}
		}
		return $woeids;
	}
}
