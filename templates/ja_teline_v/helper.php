<?php
/**
 * ------------------------------------------------------------------------
 * JA Teline V Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;


class JATemplateHelper
{
	public static function getArticles($params, $catid, $count, $front = NULL)
	{
		require_once dirname(__FILE__) . '/helper.content.php';
		$aparams = clone $params;
		$aparams->set('count', $count);
		if ($front != null) $aparams->set('show_front', $front);
		$aparams->set('catid', (array)$catid);
		$aparams->set('show_child_category_articles', 1);
		$aparams->set('levels', 2);
		$aparams->set('created_by_alias', -1);

		$alist = JATemplateHelperContent::getList($aparams);
		self::prepareItems($alist, $params);

		return $alist;
	}

	public static function getCategories($parent = 'root', $count = 0)
	{
		require_once dirname(__FILE__) . '/helper.content.php';
		$params = new JRegistry();
		$params->set('parent', $parent);
		$params->set('count', $count);
		return JATemplateHelperContent::getList($params);
	}

	public static function loadCategories($catids)
	{
		$categories = array();
		foreach ($catids as $catid) {
			$cat = JTable::getInstance('category');
			$cat->load ($catid);
			if ($cat->published == 1) $categories[] = $cat;
		}
		return $categories;
	}

	public static function loadModule($name, $style = 'raw')
	{
		jimport('joomla.application.module.helper');
		$module = JModuleHelper::getModule($name);
		$params = array('style' => $style);
		echo JModuleHelper::renderModule($module, $params);
	}

	public static function loadModules($position, $style = 'raw')
	{
		jimport('joomla.application.module.helper');
		$modules = JModuleHelper::getModules($position);
		$params = array('style' => $style);
		foreach ($modules as $module) {
			echo JModuleHelper::renderModule($module, $params);
		}
	}

	public static function getParams () {
		static $menuParams = null;
		if (!$menuParams) {
			$app = JFactory::getApplication();
			// Load the parameters. Merge Global and Menu Item params into new object
			$params = JComponentHelper::getParams('com_content', true);
			$menuParams = new JRegistry;

			if ($menu = $app->getMenu()->getActive())
			{
				$menuParams->loadString($menu->params);
			}

			$menuParams->merge($params);
		}
		$params2 = clone $menuParams;
		return $params2;
	}

	public static function getCategoryClass($catid, $recursive = true) {
		$cats = JCategories::getInstance('content');
		$cat = $cats->get($catid);
		$params = new JRegistry;
		while ($cat) {
			$params->loadString($cat->params);
			if ($params->get ('classes')) return $params->get ('classes');
			$cat = $recursive ? $cat->getParent() : null;
		}
		return '';
	}

	/* render content item base on content type */
	public static function render ($item, $path, $displayData) {
		$attribs = new JRegistry ($item->attribs);
		$content_type = $attribs->get('ctm_content_type', 'article');
		// try to render the content with content type layout
		$html = JLayoutHelper::render($path . '.' . $content_type, $displayData);
		if (!$html) {
			// render with default layout
			$html = JLayoutHelper::render($path . '.default', $displayData);
		}
		return $html;
	}

	/* get Related items base on topic, tags, category */
	public static function getRelatedItems ($item, $params, $type = '') {
		JModelLegacy::addIncludePath(JPATH_SITE. '/plugins/system/jacontenttype/models', 'JAContentTypeModel');
		$model = JModelLegacy::getInstance('Items', 'JAContentTypeModel', array('ignore_request' => true));
		$model->setState('params', $params);
		if ($type == 'category' || $params->get ('same_cat') == 1) {
			$model->setState('filter.category_id', (array)$item->catid);
		}
		if ($type == 'topic' || $params->get ('same_topic') == 1) {
			$model->metaFilter ('topic_id', $item->params->get ('ctm_topic_id'));
		}
		if ($type == 'tags' || $params->get ('same_tags') == 1) {
			$model->setState('filter.tags', $item->id);
		}
		$model->setState('list.limit', $params->get ('count', 4));
		$model->setState('list.start', 0);
		$contenttype = $params->get ('same_contenttype') ? $item->params->get ('ctm_contenttype') : null;
		$items = $model->getMetaItems($contenttype, array('a.`id` != '.$item->id, 'a.`state`=1'));
		self::prepareItems($items, $params);

		return $items;
	}

	public static function countModules ($condition) {
		if (!$condition) return 0;
		// not render in component tmpl
		if (JFactory::getApplication()->input->get ('tmpl' == 'component')) return 0;
		return JFactory::getDocument()->countModules ($condition);
	}

	public static function renderModules ($position, $attribs = array()) {
		if (!$position) return null;
		// not render in component tmpl
		if (JFactory::getApplication()->input->get ('tmpl' == 'component')) return null;

		static $buffers = array();
		if (isset($buffers[$position])) return $buffers[$position];
		// init cache to prevent nested parse
		$buffers[$position] = '';
		// prevent cache
		$attribs['params'] = '{"cache":0}';
		$buffers[$position] = JFactory::getDocument()->getBuffer('modules', $position, $attribs);
		return $buffers[$position];
	}

	public static function prepareItems (&$items, $params) {
		// Get an instance of the generic articles model
		$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$app       = JFactory::getApplication();

		// Access filter
		$access     = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));

		$articles->setState('filter.access', $access);
		// Display options
		$show_date        = $params->get('show_date', 0);
		$show_date_field  = $params->get('show_date_field', 'created');
		$show_date_format = $params->get('show_date_format', 'Y-m-d H:i:s');
		$show_category    = $params->get('show_category', 0);
		$show_hits        = $params->get('show_hits', 0);
		$show_author      = $params->get('show_author', 0);
		$show_introtext   = $params->get('show_introtext', 0);
		$introtext_limit  = $params->get('introtext_limit', 100);

		// Find current Article ID if on an article page
		$option = $app->input->get('option');
		$view   = $app->input->get('view');

		if ($option === 'com_content' && $view === 'article')
		{
			$active_article_id = $app->input->getInt('id');
		}
		else
		{
			$active_article_id = 0;
		}

		// Prepare data for display using display options
		foreach ($items as &$item)
		{
			if($item->params instanceof JRegistry) {
				$iparams = new JRegistry($item->attribs);
				$item->params->merge($iparams);

			} else {
				$item->params = new JRegistry($item->attribs);
			}
			$item->slug    = $item->id . ':' . $item->alias;
			$item->catslug = $item->catid ? $item->catid . ':' . $item->category_alias : $item->catid;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			}
			else
			{
				$app       = JFactory::getApplication();
				$menu      = $app->getMenu();
				$menuitems = $menu->getItems('link', 'index.php?option=com_users&view=login');

				if (isset($menuitems[0]))
				{
					$Itemid = $menuitems[0]->id;
				}
				elseif ($app->input->getInt('Itemid') > 0)
				{
					// Use Itemid from requesting page only if there is no existing menu
					$Itemid = $app->input->getInt('Itemid');
				}

				$item->link = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $Itemid);
			}

			// Used for styling the active article
			$item->active      = $item->id == $active_article_id ? 'active' : '';
			$item->displayDate = '';

			if ($show_date)
			{
				$item->displayDate = JHTML::_('date', $item->$show_date_field, $show_date_format);
			}

			if ($item->catid)
			{
				$item->displayCategoryLink  = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid));
				$item->displayCategoryTitle = $show_category ? '<a href="' . $item->displayCategoryLink . '">' . $item->category_title . '</a>' : '';
			}
			else
			{
				$item->displayCategoryTitle = $show_category ? $item->category_title : '';
			}

			$item->displayHits       = $show_hits ? $item->hits : '';
			$item->displayAuthorName = $show_author ? $item->author : '';

			if ($show_introtext)
			{
				$item->introtext = JHtml::_('content.prepare', $item->introtext, '', 'mod_articles_category.content');
				$item->introtext = self::_cleanIntrotext($item->introtext);
			}

			$item->displayIntrotext = $show_introtext ? self::truncate($item->introtext, $introtext_limit) : '';
			$item->displayReadmore  = $item->alternative_readmore;
		}

		$dispatcher    = JEventDispatcher::getInstance();

		foreach ($items as &$item) {
			$item->event = new stdClass;

			// Old plugins: Ensure that text property is available
			if (!isset($item->text)) {
				$item->text = $item->introtext;
			}
			JPluginHelper::importPlugin('content');
			$dispatcher->trigger('onContentPrepare', array('com_content.featured', &$item, &$params, 0));

			// Old plugins: Use processed text as introtext
			$item->introtext = $item->text;

			$results = $dispatcher->trigger('onContentAfterTitle', array('com_content.featured', &$item, &$item->params, 0));
			$item->event->afterDisplayTitle = trim(implode("\n", $results));

			$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_content.featured', &$item, &$item->params, 0));
			$item->event->beforeDisplayContent = trim(implode("\n", $results));

			$results = $dispatcher->trigger('onContentAfterDisplay', array('com_content.featured', &$item, &$item->params, 0));
			$item->event->afterDisplayContent = trim(implode("\n", $results));
		}
	}

	public static function countItemsByDate ($catids, $duration) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(*)');
		$query->from('#__content');
		// get list of catids
		if ($catids)
		{
			$catids = (array)$catids;
			// Get an instance of the generic categories model
			JLoader::register('ContentModelCategories', JPATH_SITE . '/components/com_content/models/categories.php');
			$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
			$categories->setState('params', JFactory::getApplication()->getParams());
			$categories->setState('filter.get_children', 999);
			$categories->setState('filter.published', 1);
			// $categories->setState('filter.access', $access);
			$additional_catids = array();

			foreach ($catids as $catid)
			{
				$categories->setState('filter.parentId', $catid);
				$recursive = true;
				$items     = $categories->getItems($recursive);

				if ($items)
				{
					foreach ($items as $category)
					{
						$additional_catids[] = $category->id;
					}
				}
			}

			$catids = array_unique(array_merge($catids, $additional_catids));
		}

		// cat group
		if (count($catids)) {
			$query->where('`catid` in (' . implode(',', $catids) . ')');
		}

		// limit by time
		$nullDate	= $db->quote($db->getNullDate());
		$nowDate	= $db->quote(JFactory::getDate()->toSql());
		$lastDate	= $db->quote(JFactory::getDate(strtotime ('-' . $duration . ' 0:00'))->toSql());
		$query->where('state = 1');
		$query	->where('(publish_up >= '.$lastDate.')')
			->where('(publish_down = '.$nullDate.' OR publish_down >= '.$nowDate.')');
		//filter by language
		$query->where('language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

		$db->setQuery($query);
		$count = $db->loadResult();
		return $count;
	}
	

}

?>