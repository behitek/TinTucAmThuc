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

include(ACYMAILING_BACK.'controllers'.DS.'file.php');

class FrontfileController extends FileController
{
	function __construct($config = array()){
		parent::__construct($config);

		$task = JRequest::getString('task');
		if($task != 'select') die('Access not allowed');
	}

	function select(){
		JRequest::setVar('layout', 'select');
		return parent::display();
	}
}
