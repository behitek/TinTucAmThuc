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
$db = JFactory::getDBO();
$db->setQuery('SELECT count(*) FROM `#__yanc_subscribers`');
$resultUsers = $db->loadResult();

$db = JFactory::getDBO();
$db->setQuery('SELECT count(*) FROM `#__yanc_letters`');
$resultLists = $db->loadResult();

echo acymailing_translation_sprintf('USERS_IN_COMP', $resultUsers, 'Yanc');

if(!empty($resultLists)){
	echo '<div class="acyblockoptions"><span class="acyblocktitle">'.acymailing_translation_sprintf('LISTS_IN_COMP', $resultLists, 'Yanc').'</span>';
	echo acymailing_translation_sprintf('IMPORT_X_LISTS', $resultLists).'<br />';
	echo acymailing_translation_sprintf('IMPORT_LIST_TOO', 'Yanc').JHTML::_('acyselect.booleanlist', "yanc_lists");
	echo '</div>';
}
