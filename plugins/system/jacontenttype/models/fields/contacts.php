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

defined('JPATH_BASE') or die;

/**
 * Supports a modal contact picker.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 * @since       1.6
 */
class JFormFieldContacts extends JFormField
{
	protected static $initialised = false;
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Contacts';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
		$allowEdit		= ((string) $this->element['edit'] == 'true') ? true : false;
		$allowClear		= ((string) $this->element['clear'] != 'false') ? true : false;

		// Load language
		JFactory::getLanguage()->load('com_contact', JPATH_ADMINISTRATOR);

		// Load the javascript
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal', 'a.modal');
		JHtml::_('bootstrap.tooltip');


		if (!self::$initialised)
		{
			// Build the script.
			$script = array();

			// Select button script
			$script[] = '	var ja_select_content = 0;';
			$script[] = '	function jaSelectContact(id, name, object) {';
			$script[] = '		document.id(ja_select_content + "_id").value = id;';
			$script[] = '		document.id(ja_select_content + "_name").value = name;';

			if ($allowEdit)
			{
				$script[] = '		jQuery("#"+ja_select_content + "_edit").removeClass("hidden");';
			}

			if ($allowClear)
			{
				$script[] = '		jQuery("#"+ja_select_content + "_clear").removeClass("hidden");';
			}

			$script[] = '		SqueezeBox.close();';
			$script[] = '	}';

			// Clear button script
			static $scriptClear;

			if ($allowClear && !$scriptClear)
			{
				$scriptClear = true;

				$script[] = '	function jClearContact(id) {';
				$script[] = '		document.getElementById(id + "_id").value = "";';
				$script[] = '		document.getElementById(id + "_name").value = "'.htmlspecialchars(JText::_('PLG_JACONTENT_TYPE_SELECT_A_CONTACT', true), ENT_COMPAT, 'UTF-8').'";';
				$script[] = '		jQuery("#"+id + "_clear").addClass("hidden");';
				$script[] = '		if (document.getElementById(id + "_edit")) {';
				$script[] = '			jQuery("#"+id + "_edit").addClass("hidden");';
				$script[] = '		}';
				$script[] = '		return false;';
				$script[] = '	}';
			}

			// Add the script to the document head.
			JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

			self::$initialised = true;
		}

		// Setup variables for display.
		$html	= array();

		//$link	= 'index.php?option=com_contact&amp;view=contacts&amp;layout=modal&amp;tmpl=component&amp;function=jaSelectContact';
		$link	= 'index.php?option=com_ajax&amp;plugin=jacontenttype&amp;view=contacts&amp;tmpl=component&amp;format=html&amp;function=jaSelectContact';

		//specify category by note
		$note = isset($this->element['note']) ? $this->element['note'] : '';
		if($note) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('id'))
				->from($db->quoteName('#__categories'))
				->where($db->quoteName('extension') .' = ' . $db->quote('com_contact'))
				->where($db->quoteName('note') .' = ' . $db->quote($note));
			$db->setQuery($query);
			$catid = $db->loadResult();
			if($catid) {
				$link .= '&amp;filter_category_id='.$catid;
			}
		}

		if (isset($this->element['language']))
		{
			$link .= '&amp;forcedLanguage=' . $this->element['language'];
		}

		// Get the title of the linked chart
		if ((int) $this->value > 0)
		{
			$ids = explode(',', $this->value);
			JArrayHelper::toInteger($ids);
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('name'))
				->from($db->quoteName('#__contact_details'))
				->where('id = ' . (int) $this->value);
			$db->setQuery($query);

			try
			{
				$title = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				JError::raiseWarning(500, $e->getMessage());
			}
		}

		if (empty($title))
		{
			$title = JText::_('PLG_JACONTENT_TYPE_SELECT_A_CONTACT');
		}

		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The active contact id field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		// The current contact display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';
		$html[] = '<a data-id="' . $this->id . '" onclick="ja_select_content=jQuery(this).data(\'id\');" class="modal btn hasTooltip" title="' . JHtml::tooltipText('COM_CONTACT_CHANGE_CONTACT') . '"  href="' . $link . '&amp;' . JSession::getFormToken() . '=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> ' . JText::_('JSELECT') . '</a>';

		// Edit article button
		if ($allowEdit)
		{
			$html[] = '<a class="btn hasTooltip' . ($value ? '' : ' hidden') . '" href="index.php?option=com_contact&layout=modal&tmpl=component&task=contact.edit&id=' . $value . '" target="_blank" title="' . JHtml::tooltipText('COM_CONTACT_EDIT_CONTACT') . '" ><span class="icon-edit"></span> ' . JText::_('JACTION_EDIT') . '</a>';
		}

		// Clear contact button
		if ($allowClear)
		{
			$html[] = '<button id="' . $this->id . '_clear" data-id="' . $this->id . '" class="btn' . ($value ? '' : ' hidden') . '" onclick="return jClearContact(jQuery(this).data(\'id\'))"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</button>';
		}

		$html[] = '</span>';

		// class='required' for client side validation
		$class = '';

		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		return implode("\n", $html);
	}
}
