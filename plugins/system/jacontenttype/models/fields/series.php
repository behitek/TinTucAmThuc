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

JFormHelper::loadFieldClass('ctmbase');
/**
 * Series of article in topic.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_contact
 * @since       1.6
 */
class JFormFieldSeries extends JFormFieldCtmbase
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'Series';
	protected function getInput() {
		$app = JFactory::getApplication();
		if($app->isSite()) {
			$id = $app->input->getInt('a_id', 0);
		} else {
			$id = $app->input->getInt('id', 0);
		}
		if($id) {

			$model = new JAContentTypeModelItems();

			$items = $model->getMetaItems('', array(), array('topic_id'=>'='.$id));

			$html = array();
			if(count($items)) {
				$html[] = '<ol class="nav nav-list">';
				$html[] = '<li class="nav-header">'.JText::_('PLG_JACONTENT_TYPE_SERIES_OF_ARTICLES_IN_THIS_TOPIC').'</li>';
				foreach($items as $item) {
					$link = $app->isSite() ? JRoute::_('index.php?option=com_content&task=article.edit&a_id='.$item->id) : JRoute::_('index.php?option=com_content&task=article.edit&id='.$item->id);
					$html[] = '<li><a target="_blank" href="'.$link.'" title="'.htmlspecialchars($item->title).'">'.$item->title.' <span class="icon-out-2 small"></span></a></li>';
				}
				$html[] = '</ol>';
			}
			return implode("\r\n", $html);
		}
	}
}