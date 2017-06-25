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

if(!class_exists('ContentModelArticle')) {
	$app = JFactory::getApplication();
	if($app->isAdmin()) {
		JLoader::register('ContentModelArticle', JPATH_ADMINISTRATOR . '/components/com_content/models/article.php');
	} else {
		JLoader::register('ContentModelArticle', JPATH_ROOT . '/components/com_content/models/article.php');
	}
}

class JAContentTypeModelItem extends ContentModelArticle
{

	/**
	 * return content type of given content id
	 * @param $id - content id
	 */
	public function getContentType($id) {
		$item = $this->getItem($id);
		$contenttype = '';
		if($item) {
			if($item->attribs instanceof JRegistry) {
				$contenttype = $item->attribs->get('ctm_content_type', '');
			} elseif (is_array($item->attribs)) {
				$contenttype = isset($item->attribs['ctm_content_type']) ? $item->attribs['ctm_content_type'] : '';
			}
		}
		return $contenttype;
	}

	/**
	 * @return content type of page
	 */
	public function getPageContentType() {
		$app = JFactory::getApplication();
		$name = $app->isSite() ? 'a_id' : 'id';
		$id = $app->input->getInt($name);
		$contenttype = '';
		if($id) {
			$contenttype = $this->getContentType($id);
		} else {
			$app = JFactory::getApplication();
			$post  = $app->input->post->get('jform', array(), 'array');
			if(isset($post['attribs']['ctm_content_type'])) {
				$contenttype = $post['attribs']['ctm_content_type'];
			} else {
				$contenttype = $app->input->get('contenttype', 'article');
			}
		}
		return $contenttype;
	}
}