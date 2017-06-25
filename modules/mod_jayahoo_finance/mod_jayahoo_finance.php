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

// Include the helper functions only once
require_once dirname(__FILE__) . '/yql.php';
require_once dirname(__FILE__) . '/helper.php';

$input = JFactory::getApplication()->input;

$idbase = $params->get('symbols');

$cacheid = md5(serialize(array ($idbase, $module->module)));

$cacheparams               = new stdClass;
$cacheparams->cachemode    = 'id';
$cacheparams->class        = 'ModJaYahooFinanceHelper';
$cacheparams->method       = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams   = $cacheid;

$list = JModuleHelper::moduleCache($module, $params, $cacheparams);

if (!empty($list))
{
	$doc = JFactory::getDocument();
	$doc->addStyleSheet(JUri::base(true).'/modules/'.$module->module.'/asset/style.css');
	require JModuleHelper::getLayoutPath($module->module, $params->get('layout', 'default'));
}
