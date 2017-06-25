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

JLoader::register('JAContentTypeModelItem', JAPATH_CONTENT_TYPE . '/models/item.php');
JLoader::register('JAContentTypeModelItems', JAPATH_CONTENT_TYPE . '/models/items.php');
/**
 * Content Meta Base.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 * @since       1.6
 */
class JFormFieldCtmbase extends JFormField
{

	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Ctmbase';
	protected function getInput() {

	}

	public function renderField($options = array()) {
		if(!$this->check()) {
			return '';
		}

		return parent::renderField($options);
	}

	protected function check() {
		$includes = isset($this->element['include_types']) ? explode(',', $this->element['include_types']) : array();
		$excludes = isset($this->element['exclude_types']) ? explode(',', $this->element['exclude_types']) : array();

		if(count($includes) || count($excludes)) {

			$model = new JAContentTypeModelItem();
			$contenttype = $model->getPageContentType();
			if($contenttype && (count($excludes) && in_array($contenttype, $excludes))) {
				return false;
			}
			if(count($includes) && (!$contenttype || !in_array($contenttype, $includes))) {
				return false;
			}
		}
		return true;
	}
}