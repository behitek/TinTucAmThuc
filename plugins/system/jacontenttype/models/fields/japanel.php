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

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.formfield');

class JFormFieldJapanel extends JFormField {
    protected $type = 'Japanel';
    
    protected function getInput() {
		JFormFieldJapanel::init();

		$group_name = 'jform';
		preg_match_all('/jform\\[([^\]]*)\\]/', $this->name, $matches);

		if(!isset($matches[1]) || empty($matches[1])){
			preg_match_all('/jaform\\[([^\]]*)\\]/', $this->name, $matches);
			$group_name = 'jaform';
		}


		$script = '';
		if(isset($matches[1]) && !empty($matches[1])) {
			foreach ($this->element->children() as $option){
				$elms = preg_replace('/\s+/', '', (string)$option[0]);
				$script .= "
					JADepend.inst.add('".$option['for']."', {
						val: '".$option['value']."',
						elms: '".$elms."',
						group: '".$group_name . '[' . @$matches[1][0] . ']'."'
					});";
			}
		}
		if(!empty($script)) {
			$doc = JFactory::getDocument();
			$doc->addScriptDeclaration("
			$(window).addEvent('load', function(){
				".$script."
			});");
		}
		return null;
    }
    
    public static function init() {
		static $init = null;
		if(!$init) {
			$init = 1;
			$doc = JFactory::getDocument();
			$path = JURI::root(true).'/plugins/system/jacontenttype/models/fields/asset/';

			$doc->addScript($path.'depend.js');
			$doc->addScript($path.'btngroup.js');
		}
        return null;
    }
}