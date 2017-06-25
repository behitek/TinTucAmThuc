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
class jaYQLFinance
{
	protected $endpoint = 'https://query.yahooapis.com/v1/public/yql';

	public function __construct() {

	}

	public function getQuotes($symbols) {
		if(!is_array($symbols)) {
			$symbols = explode(',', $symbols);
		}
		foreach($symbols as $index => $symbol) {
			$symbols[$index] = trim($symbol);
		}

		$symbols = JArrayHelper::arrayUnique($symbols);
		$query = "select * from yahoo.finance.quotes where symbol in ('".implode("','", $symbols)."')";

		$result = $this->getResult($query);

		$items = array();
		if(is_object($result) && isset($result->query->results->quote)) {
			if(is_array($result->query->results->quote)){
				$items = $result->query->results->quote;
			} else {
				$items[] = $result->query->results->quote;
			}
		}
		return $items;
	}

	protected function getResult($query) {
		$url = new JUri($this->endpoint);
		$url->setVar('q', rawurlencode($query));
		$url->setVar('format', rawurlencode('json'));
		$url->setVar('diagnostics', rawurlencode('true'));
		$url->setVar('env', rawurlencode('store://datatables.org/alltableswithkeys'));
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