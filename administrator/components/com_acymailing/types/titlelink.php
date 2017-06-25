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

class titlelinkType{
	var $onclick="updateTag();";

	function __construct(){
		$this->values = array();
		$this->values[] = JHTML::_('select.option', "|link",acymailing_translation('JOOMEXT_YES'));
		$this->values[] = JHTML::_('select.option', "",acymailing_translation('JOOMEXT_NO'));

	}

	function display($map,$value){
		if(empty($value)) $value = '';
		return JHTML::_('acyselect.radiolist', $this->values, $map , 'size="1" onclick="'.$this->onclick.'"', 'value', 'text', $value);
	}

}
