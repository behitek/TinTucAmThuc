<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Provides an Invisible field that you can see and update by form in normal way
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 */
class JFormFieldInvisible extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Invisible';

	protected function getInput() {
		return '';
	}


	public function renderField($options = array())
	{
		return '';
	}
}
