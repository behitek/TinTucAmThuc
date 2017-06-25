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

class deliverstatusType{

	function __construct(){

		$this->values = array();
		$this->values[] = JHTML::_('select.option', '0', acymailing_translation('ALL_STATUS') );
		$this->values[] = JHTML::_('select.option', 'open', acymailing_translation('OPEN') );
		$this->values[] = JHTML::_('select.option', 'notopen', acymailing_translation('NOT_OPEN') );
		$this->values[] = JHTML::_('select.option', 'failed', acymailing_translation('FAILED') );
		if(acymailing_level(3)) $this->values[] = JHTML::_('select.option', 'bounce', acymailing_translation('BOUNCES') );

	}

	function display($map,$value){
		return JHTML::_('select.genericlist',   $this->values, $map, 'class="inputbox" size="1" style="width:150px;" onchange="document.adminForm.submit( );"', 'value', 'text', $value );
	}
}
