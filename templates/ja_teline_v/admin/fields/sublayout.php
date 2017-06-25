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

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

require_once 'jafield.php';

/**
 * Radio List Element
 *
 * @since      Class available since Release 1.2.0
 */
class JFormFieldSubLayout extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'sublayout';


	function getLabel() {
		return '';
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	function getInput()
	{
		if (!defined('SUBLAYOUT_THIS_URL')) {
			$path1 = str_replace('\\', '/', JPATH_ROOT);
			$path2 = str_replace('\\', '/', dirname(dirname(__FILE__)));
			define('SUBLAYOUT_THIS_URL', str_replace($path1, JUri::root(true), $path2));
			define('SUBLAYOUT_THIS_PATH', $path2);
		}

		$jdoc = JFactory::getDocument();
		if(version_compare(JVERSION, '3.0', 'lt')){
			$jdoc->addScript('http://code.jquery.com/jquery-latest.js');
			// add bootstrap for Joomla 2.5

			if (defined('T3_ADMIN_URL')) {
				$jdoc->addStyleSheet(T3_ADMIN_URL . '/admin/bootstrap/css/bootstrap.min.css');
				$jdoc->addScript(T3_ADMIN_URL . '/admin/bootstrap/js/bootstrap.min.js');
			}
		}
		// add font awesome 4
		if (defined('T3_ADMIN_URL')) {
				$jdoc->addStyleSheet(T3_ADMIN_URL . '/admin/fonts/fa4/css/font-awesome.min.css');
		}

		$jdoc->addStyleSheet(SUBLAYOUT_THIS_URL . '/assets/style.css');
		$jdoc->addStyleSheet('//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css');
		$jdoc->addScript('//code.jquery.com/ui/1.11.1/jquery-ui.js');
		$jdoc->addScript(SUBLAYOUT_THIS_URL . '/assets/script.js');


		// load all xml
		// detect current extension layout path
		$paths = array();
		$view = (string)$this->element['view'];
		$layout = (string)$this->element['layout'];

		$path = 'html/';
		if (preg_match('/^mod_/', $view)) {
			$paths['_'] = JPATH_ROOT . '/modules/' . $view . '/tmpl/' . $layout . '/';
			$path .= $view . '/' . $layout . '/';
		} else if (preg_match('/^com_/', $view)) {
			$tmp = explode('.', $view);
			$component = $tmp[0];
			$view = count($tmp) > 1 ? $tmp[1] : '';
			$paths['_'] = JPATH_ROOT . '/component/' . $component . '/view/' . $view . '/tmpl/' . $layout . '/';
			$path .= $component . '/' . $view . '/' . $layout . '/';
		} else {
			$_path = str_replace ('.', '/', $view);
			$paths['_'] = JPATH_ROOT . str_replace ('.', '/', $view) . '/' . $layout . '/';
			$path .= $_path . '/' . $layout . '/';
		}
		
		// template folders
		$tpls = JFolder::folders (JPATH_ROOT. '/templates/');
		foreach ($tpls as $tpl) {
			$paths[$tpl] = JPATH_ROOT . '/templates/' . $tpl . '/' . $path;
		}

		$sublayout_forms = array();
		$sublayouts = array();
		foreach ($paths as $template => $path) {
			if (!is_dir($path)) continue;
			$styles = JFolder::files($path, '.xml');
			if (!is_array($styles)) continue;

			$sublayouts[$template] = array();
			foreach ($styles as $style_xml) {

				$style = JFile::stripExt($style_xml);
				$form = new JForm($style);
				$xml = JFactory::getXML($path . $style_xml, true);
				$form->load ($xml, false);

				$fieldsets = $form->getFieldsets();

				$title = isset($xml->title) ? (string)$xml->title : $style;
				$description = isset($xml->description) ? $xml->description : '';

				$sublayouts[$template][$style] = $title;

				$sublayout_forms[$style] = $this->renderLayout ('sublayout-form',
																							array('form' => $form, 'fieldsets' => $fieldsets,
																										'style' => $style, 'title'=>$title,'description' => $description));

			}
		}

		$html = $this->renderLayout('sublayout', array('field' => $this, 'sublayouts' => $sublayouts, 'sublayout-forms' => $sublayout_forms));

		return $html;
	}

	function renderLayout ($file, $displayData) {
		$path = SUBLAYOUT_THIS_PATH . '/tmpl/field-' . $file . '.php';
		if (!is_file ($path)) return null;
		ob_start();
		include $path;
		$layoutOutput = ob_get_contents();
		ob_end_clean();

		return $layoutOutput;
	}
} 