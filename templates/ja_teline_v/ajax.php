<?php
/**
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org
 *------------------------------------------------------------------------------
 */


// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

$app 	= JFactory::getApplication();
$user 	= JFactory::getUser();

switch($app->input->get('task'))
{
	case 'loadmodule':
		$moduleid = $app->input->getInt('modid');
		if($moduleid) {


			$db = JFactory::getDbo();

			$query = $db->getQuery(true);
			$query->select('*')->from('#__modules')->where('id='.$moduleid);
			$db->setQuery($query);

			$module = $db->loadObject();
			if($module) {
				echo JModuleHelper::renderModule($module);
			}
		}
		break;
	case 'update_event':
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$id = $app->input->getInt('id');
		if(!$id) {
			jexit(JText::_('Invalid Event'));
		}
		$table = JTable::getInstance('Content', 'JTable');
		// Check for a valid user and that they are the owner.
		$table->load($id);
		if ($table->id && !$user->get('guest'))
		{
			$userId = $user->get('id');
			$asset = 'com_content.article.' . $id;

			$access_edit = false;
			// Check general edit permission first.
			if ($user->authorise('core.edit', $asset) && 0)
			{
				$access_edit = true;
			}

			// Now check if edit.own is available.
			elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
			{
				if ($userId == $table->created_by)
				{
					$access_edit = true;
				}
			}

			if($access_edit) {
				$attribs    = new JRegistry($table->attribs);
				$livedata   = $attribs->get('event_livedata');
				$livedata  .= '<hr title="'.htmlspecialchars($app->input->get('title', '', 'raw')).'" class="system-pagebreak" />';
				$livedata  .= '<p>'.$app->input->get('content', '', 'raw').'</p>';
				$attribs->set('event_livedata', $livedata);
				$table->attribs = $attribs->toString();
				$result = $table->store();
				jexit('OK');
			}
		} else {
			jexit(JText::_('You do not have a permission to update this event'));
		}
		break;
}