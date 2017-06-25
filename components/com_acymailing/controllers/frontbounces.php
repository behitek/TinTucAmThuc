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
$my = JFactory::getUser();
if(empty($my->id)){
	$usercomp = !ACYMAILING_J16 ? 'com_user' : 'com_users';
	$uri = JFactory::getURI();
	$url = 'index.php?option='.$usercomp.'&view=login&return='.base64_encode($uri->toString());
	$app = JFactory::getApplication();
	$app->redirect($url, acymailing_translation('ACY_NOTALLOWED'), 'error');
	return false;
}

$config = acymailing_config();
if(!acymailing_isAllowed($config->get('acl_statistics_manage', 'all'))) die(acymailing_translation('ACY_NOTALLOWED'));

$frontHelper = acymailing_get('helper.acyfront');
include(ACYMAILING_BACK.'controllers'.DS.'bounces.php');


class FrontbouncesController extends BouncesController{

	function __construct($config = array()){
		parent::__construct($config);
		$task = JRequest::getCmd('task');
		if($task != 'chart') die(acymailing_translation('ACY_NOTALLOWED'));
	}

	function chart(){
		JRequest::setVar('layout', 'chart');
		return parent::display();
	}
}
