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

class statusfilterType{
	function __construct(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', '0', acymailing_translation('ALL_STATUS') );
		$this->values[] = JHTML::_('select.option',  '<OPTGROUP>', acymailing_translation( 'ACCEPT_REFUSE' ) );
		$this->values[] = JHTML::_('select.option', '1', acymailing_translation('ACCEPT_EMAIL') );
		$this->values[] = JHTML::_('select.option', '-1', acymailing_translation('REFUSE_EMAIL') );
		$this->values[] = JHTML::_('select.option',  '</OPTGROUP>');
		$config = acymailing_config();
		if($config->get('require_confirmation',0)){
			$this->values[] = JHTML::_('select.option',  '<OPTGROUP>', acymailing_translation( 'SUBSCRIPTION' ) );
			$this->values[] = JHTML::_('select.option', '2', acymailing_translation('PENDING_SUBSCRIPTION') );
			$this->values[] = JHTML::_('select.option',  '</OPTGROUP>');
		}
		$this->values[] = JHTML::_('select.option',  '<OPTGROUP>', acymailing_translation( 'ENABLED_DISABLED' ) );
		$this->values[] = JHTML::_('select.option', '3', acymailing_translation('ENABLED') );
		$this->values[] = JHTML::_('select.option', '-3', acymailing_translation('DISABLED') );
		$this->values[] = JHTML::_('select.option',  '</OPTGROUP>');
	}

	function display($map,$value){
		return JHTML::_('select.genericlist',   $this->values, $map, 'size="1" onchange="document.adminForm.limitstart.value=0;document.adminForm.submit( );"', 'value', 'text', (int) $value );
	}
}
