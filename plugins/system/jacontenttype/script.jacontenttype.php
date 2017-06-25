<?php
/**
 * ------------------------------------------------------------------------
 * Plugin JA Content Type
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
/**
 * Installation script
 * @link https://docs.joomla.org/J3.x:Developing_a_MVC_Component/Adding_an_install-uninstall-update_script_file
 * @package     Joomla.Plugin
 * @subpackage  Content.Jacontenttype
 * @since       1.5
 */
class PlgSystemJacontenttypeInstallerScript
{
	/**
	 * Method to install the extension
	 * $parent is the class calling this method
	 *
	 * @return void
	 */
	/*function install($parent)
	{
		echo '<p>The module has been installed</p>';
	}*/

	/**
	 * Method to uninstall the extension
	 * $parent is the class calling this method
	 *
	 * @return void
	 */
	/*function uninstall($parent)
	{
		echo '<p>The module has been uninstalled</p>';
	}*/

	/**
	 * Method to update the extension
	 * $parent is the class calling this method
	 *
	 * @return void
	 */
	/*function update($parent)
	{
		echo '<p>The module has been updated to version' . $parent->get('manifest')->version . '</p>';
	}*/

	/**
	 * Method to run before an install/update/uninstall method
	 * $parent is the class calling this method
	 * $type is the type of change (install, update or discover_install)
	 *
	 * @return void
	 */
	/*function preflight($type, $parent)
	{
		echo '<p>Anything here happens before the installation/update/uninstallation of the module</p>';
	}*/

	/**
	 * Method to run after an install/update/uninstall method
	 * $parent is the class calling this method
	 * $type is the type of change (install, update or discover_install)
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		//enable plugin
		if($type == 'install' || $type == 'update') {
			$db    = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->update('#__extensions')
				->set('enabled=1')
				->where(array(
					$db->quoteName('type').'='.$db->quote('plugin'),
					$db->quoteName('element').'='.$db->quote('jacontenttype'),
					$db->quoteName('folder').'='.$db->quote('system')
				));
			$db->setQuery($query);
			$db->execute();
		}

		return true;
	}
}