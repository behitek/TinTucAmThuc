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
defined('_JEXEC') or die('Restricted access');

/**
 * T3 Blank Helper class
 *
 * @package		T3 Blank
 */

JLoader::register('JATemplateHelper', T3_TEMPLATE_PATH . '/helper.php');


jimport('joomla.event.event');

class JA_Teline_VHook extends JEvent
{
	
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);		
	}
	
	public function onT3Init() // no params
	{
		
	}

	public function onT3TplInit($t3app)
	{
		
	}

	public function onT3LoadLayout(&$path, $layout)
	{
		//T3::getApp()->addBodyClass('loadlayout');
	}

	public function onT3Spotlight(&$info, $name, $position)
	{
		
	}
	
	public function onT3Megamenu(&$menutype, &$config, &$levels)
	{
		
	}

	public function onT3BodyClass(&$class)
	{
		//$class[] = 'onbodyclass';
	}

	public function onT3BeforeCompileHead() // no params
	{
		
	}
	
	public function onT3BeforeRender() // no params
	{
		$app = JFactory::getApplication();
		if (!$app->isAdmin()) {
			// allow load module/modules in component using jdoc:include
			$doc = JFactory::getDocument();
			$main_content = $doc->getBuffer('component');
			if ($main_content) {
				// parse jdoc
				if (preg_match_all('#<jdoc:include\ type="([^"]+)"(.*)\/>#iU', $main_content, $matches))
				{
					$replace = array();
					$with = array();
			
					// Step through the jdocs in reverse order.
					for ($i = 0; $i < count($matches[0]); $i++)
					{
					$type = $matches[1][$i];
					$attribs = empty($matches[2][$i]) ? array() : JUtility::parseAttributes($matches[2][$i]);
					$name = isset($attribs['name']) ? $attribs['name'] : null;
							$replace[] = $matches[0][$i];
							$with[] = $doc->getBuffer($type, $name, $attribs);
					}
			
					$main_content = str_replace($replace, $with, $main_content);
			
					// update buffer
					$doc->setBuffer($main_content, 'component');
				}
			}	
		}
	}
	
	public function onT3AfterRender() // no params
	{
		
	}
}

?>