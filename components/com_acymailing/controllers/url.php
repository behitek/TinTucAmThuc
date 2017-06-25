<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.7.0
 * @author	acyba.com
 * @copyright	(C) 2009-2017 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class UrlController extends acymailingController{

	function __construct($config = array())
	{
		parent::__construct($config);

		JRequest::setVar('tmpl','component');
		$this->registerDefaultTask('click');

	}


	function sef(){
		$urls = JRequest::getVar('urls', array(), '', 'array');
		$result = array();

		$otherarguments = '';

		$liveParsed = parse_url(ACYMAILING_LIVE);
		if(isset($liveParsed['path']) AND strlen($liveParsed['path']) > 0){
			$mainurl = substr(ACYMAILING_LIVE, 0, strrpos(ACYMAILING_LIVE, $liveParsed['path'])).'/';
			$otherarguments = trim(str_replace($mainurl, '', ACYMAILING_LIVE), '/');
			if(strlen($otherarguments) > 0) $otherarguments .= '/';
		}else{
			$mainurl = ACYMAILING_LIVE;
		}

		$uri = JUri::root(true);
		foreach($urls as $url){
			$url = base64_decode($url);
			$link = JRoute::_($url, false);
			if(!empty($uri) && strpos($link, $uri) === 0) $link = substr($link, strlen($uri));

			$link = ltrim($link, '/');
			if(!empty($otherarguments) && strpos($link, $otherarguments) === false) $link = $otherarguments.$link;

			$result[$url] = $mainurl.$link;
		}
		echo json_encode($result);
		exit;
	}
}
