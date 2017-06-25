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

JFormHelper::loadFieldClass('list');
/**
 * List of Content Type
 *
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 * @since       1.6
 */
class JFormFieldJactt extends JFormFieldList
{

	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Jactt';
	protected function getOptions() {
		$path = JAPATH_CONTENT_TYPE . '/models/types';

		$files = JFolder::files($path, '\.xml$', false, true);
		$templates = JFolder::folders(JPATH_ROOT.'/templates/', '.', false, true);
		foreach($templates as $template) {
			if(JFolder::exists($template.'/contenttype/types/')) {
				$files = array_merge($files, JFolder::files($template.'/contenttype/types/', '\.xml$', false, true));
			}
		}
		$options = array();
		if(count($files)) {
			foreach($files as $file) {
				$xml = simplexml_load_file($file);
				if($xml) {
					$options[] = JHtml::_('select.option', (string) $xml->type, (string) $xml->title);
				}
			}
		}

		return array_merge(parent::getOptions(), $options);
	}
}