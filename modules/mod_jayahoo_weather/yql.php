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
class jaYQLWeather
{
	protected $endpoint = 'https://query.yahooapis.com/v1/public/yql';

	public function __construct() {

	}

	public function getWeatherForecast($woeid) {
		$query = "select * from weather.forecast where woeid=".intval($woeid);
		$result = $this->getResult($query, false);

		$info = null;
		if(is_object($result) && isset($result->query->results->channel)) {
			$info = $result->query->results->channel;
		}
		return $info;
	}

	public function getGeoPlace($placename) {
		//(1) to return only first result
		$query = "select * from geo.places(1) where text = '".addslashes($placename)."'";
		$result = $this->getResult($query, false);

		$place = null;
		if(is_object($result) && isset($result->query->results->place)) {
			$place = $result->query->results->place;
			if(is_array($place)) {
				$place = $place[0];
			}
		}
		return $place;
	}

	public function getGeoStates($country) {
		$query = "select * from geo.states where place='".addslashes($country)."'";
		$result = $this->getResult($query, false);

		$items = array();
		if(is_object($result) && isset($result->query->results->place)) {
			$items = $result->query->results->place;
		}
		return $items;
	}

	protected function getResult($query, $alltables = true) {
		$url = new JUri($this->endpoint);
		$url->setVar('q', rawurlencode($query));
		$url->setVar('format', rawurlencode('json'));
		$url->setVar('diagnostics', rawurlencode('true'));
		if($alltables) {
			$url->setVar('env', rawurlencode('store://datatables.org/alltableswithkeys'));
		}
		$url->setVar('callback', '');
		//&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=

		$params = new JRegistry();
		/*if(JHttpTransportCurl::isSupported()) {
			$transport = new JHttpTransportCurl($params);
		} else{
			$transport = new JHttpTransportSocket($params);
		}*/
		//use socket to fix issue: can not verify CA cert file

		try
		{
			$transport = new JHttpTransportSocket($params);
			$result = $transport->request('GET', $url);
			$body = json_decode($result->body);
			return $body;
		} catch (Exception $e) {
			$app = JFactory::getApplication();
			$app->enqueueMessage($e->getMessage());
			return false;
		}
	}
}