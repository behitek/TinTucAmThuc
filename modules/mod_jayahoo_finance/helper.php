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


abstract class ModJaYahooFinanceHelper
{
	public static function getList(&$params)
	{
		$yql = new jaYQLFinance();
		$items = $yql->getQuotes($params->get('symbols'));

		return $items;
	}

	public static function formartNumber($number) {
		if($number > 1000000000) {
			$number = number_format((float) $number / 1000000000, 2).'B';
		} elseif($number > 1000000) {
			$number = number_format((float) $number / 1000000, 2).'M';
		}
		return $number;
	}
}
