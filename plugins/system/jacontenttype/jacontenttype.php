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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

define('JAPATH_CONTENT_TYPE', dirname(__FILE__));
/**
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.Jacontenttype
 * @since       1.5
 */
class PlgSystemJacontenttype extends JPlugin
{
	protected $keyPrefix = 'ctm_';//Content Meta
	protected $pathField = '';
	protected $pathForm = '';
	protected $pathType = '';

	protected $data = array();
	protected $pageTitle = '';

	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		$this->pathField 	= JAPATH_CONTENT_TYPE . '/models/fields';
		$this->pathForm 	= JAPATH_CONTENT_TYPE . '/models/forms';
		$this->pathType 	= JAPATH_CONTENT_TYPE . '/models/types';

		$this->_check();
	}

	/**
	 * Check add-on extensions if they are installed and enabled to ensure that all features works properly
	 */
	protected function _check() {
		//enable ajax plugin
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__extensions')
			->set('enabled=1')
			->where(array(
				$db->quoteName('type').'='.$db->quote('plugin'),
				$db->quoteName('element').'='.$db->quote('jacontenttype'),
				$db->quoteName('folder').'='.$db->quote('ajax')
			));
		$db->setQuery($query);
		$db->execute();
	}

	/*public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		if(in_array($context, array(
			'com_content.featured',
			'com_content.article',
			'com_content.archive',
			'com_content.category',
		))) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')->from('#__content_meta')->where($db->quoteName('content_id').'='.$db->quote($row->id));
			$db->setQuery($query);
			$items = $db->loadObjectList();
			if(count($items)) {
				foreach($items as $item) {
					$key = $this->keyPrefix.$item->meta_key;
					$value = ($item->encoded) ? json_decode($item->meta_value) : $item->meta_value;

					$params->set($key, $value);
					$row->params->set($key, $value);
				}
			}
		}
	}*/

	public function onContentPrepareData($context, $data)
	{
		if($context == 'com_content.form' || $context == 'com_content.article') {
			/*$id = 0;

			if(is_object($data) && isset($data->id)) {
				$id = $data->id;
			} elseif (is_array($data) && isset($datap['id'])) {
				$id = $data['id'];
			}
			if($id) {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('*')->from('#__content_meta')->where($db->quoteName('content_id').'='.$db->quote($id));
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				if(count($rows)) {
					foreach($rows as $row) {
						$key = $this->keyPrefix.$row->meta_key;
						$value = ($row->encoded) ? json_decode($row->meta_value) : $row->meta_value;

						if(is_object($data)) {
							$data->attribs[$key] = $value;
						} elseif(is_array($data)) {
							$data['attribs'][$key] = $value;
						}
					}
				}
			}*/
		}
	}

	/**
	 * Adding extra fields into Content Component's forms
	 * @param $form
	 * @param $data
	 * @return bool
	 */
	public function onContentPrepareForm($form, $data)
	{
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
/*
		if(!in_array($form->getName(), array(
			'com_categories.categories.content.filter',
			'com_content.featured.filter',
			'com_categories.categorycom_content',
			'com_content.articles.filter',
			'com_content.article'
		))) {
			return true;
		}
*/

		switch($form->getName()) {
			case 'com_categories.categories.content.filter':
				//list categories
				break;
			case 'com_categories.categorycom_content':
				//edit category
				break;
			case 'com_content.articles.filter':
			case 'com_content.featured.filter':
				//list articles
				$this->_onContentPrepareFormArticles($form, $data);
				break;
			case 'com_menus.item':
				//edit menu item
				$this->_onContentPrepareFormMenuEdit($form, $data);
				break;
			case 'com_content.article':
				//edit articles
				$this->_onContentPrepareFormArticleEdit($form, $data);
				break;
			case 'com_config.component':
				if (JFactory::getApplication()->input->get('component') == 'com_content') {
					$extended = __DIR__ . '/form/config.xml';
					if (is_file($extended)) {
						$form->loadFile($extended, false);
					}
				}
				break;
		}

		return true;
	}

	/*public function onContentBeforeSave($context, $article, $isNew)
	{
		// Check we are handling the frontend edit form.
		if ($context == 'com_content.form' || $context == 'com_content.article') {
			$attribs = new JRegistry($article->attribs);
			$data = $attribs->toArray();
			$this->data = array();

			$check = 0;
			//remove meta data from article attributes
			//and store them into different table
			foreach($data as $key => $value) {
				if(strpos($key, $this->keyPrefix) === 0) {
					$this->data[$key] = $value;
					if($key !== $this->keyPrefix.'content_type') {
						$check = 1;
						$attribs->set($key, null);
					}
				}
			}
			if($check) {
				$article->attribs = $attribs->toString();
			}
		}
		return true;
	}*/

	public function onContentAfterSave($context, $article, $isNew)
	{
		// Check we are handling the frontend edit form.
		if ($context == 'com_content.form' || $context == 'com_content.article') {
			$attribs = new JRegistry($article->attribs);
			$data = $attribs->toArray();
			if(count($data)) {
				$content_id = $article->id;
				$db = JFactory::getDbo();
				/**
				 * @todo need sql INSERT IGNORE statement (Joomla does not support now)
				 */
				$queryInsert 	= $db->getQuery(true);
				$queryUpdate 	= $db->getQuery(true);
				$queryCheck 	= $db->getQuery(true);

				$queryInsert->insert('#__content_meta')->columns(array($db->quoteName('content_id'), $db->quoteName('meta_key'), $db->quoteName('meta_value'), $db->quoteName('encoded') ));
				$queryUpdate->update('#__content_meta');
				$queryCheck->select($db->quoteName('id'))->from('#__content_meta');

				foreach($data as $key => $value) {
					if(strpos($key, $this->keyPrefix) === 0) {
						//remove prefix to get short key
						$key = substr($key, strlen($this->keyPrefix));
						if(is_array($value) || is_object($value)) {
							$encoded = 1;
							$value = json_encode($value);
						} else {
							$encoded = 0;
						}

						$queryCheck->clear('where');
						$queryCheck->where(array($db->quoteName('content_id').'='.$db->quote($content_id), $db->quoteName('meta_key').'='.$db->quote($key)));

						$db->setQuery($queryCheck);
						$id = $db->loadResult();
						if($id) {
							$queryUpdate->clear('where');
							$queryUpdate->clear('values');
							$queryUpdate->set(array($db->quoteName('meta_value').'='.$db->quote($value), $db->quoteName('encoded').'='.$db->quote($encoded)));
							$queryUpdate->where($db->quoteName('id').'='.$db->quote($id));
							$db->setQuery($queryUpdate);
							$db->execute();
						} else {
							if($value == '') {
								continue;
							}
							$queryInsert->clear('values');
							$queryInsert->values($db->quote($content_id).','.$db->quote($key).','.$db->quote($value).','.$db->quote($encoded));
							$db->setQuery($queryInsert);
							$db->execute();
						}
					}
				}
			}
		}
		return true;
	}

	/*public function onContentBeforeDelete($context, $article)
	{

	}*/
	/**
	 * Remove meta data after deleting article
	 * @param $context
	 * @param $article
	 */
	public function onContentAfterDelete($context, $article)
	{
		if($context == 'com_content.article') {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->delete('#__content_meta')->where($db->quoteName('content_id').'='.$db->quote($article->id));
			$db->setQuery($query);
			$db->execute();
		}
	}

	public function onBeforeRender() {
		$app = JFactory::getApplication();
		$input = $app->input;

		if($app->isAdmin()) {
			if(!empty($this->pageTitle)) {
				list($icon, $title) = explode(':', $this->pageTitle, 2);
				JToolbarHelper::title($title, $icon);
			}
		}
	}

	public function onAfterRoute() {
		$app = JFactory::getApplication();
		$input = $app->input;
		//front-end edit
		if($app->isSite() && $input->get('option') == 'com_content' && $input->get('view') == 'form' && $input->get('layout') == 'edit') {
			JLoader::register('JViewLegacy', JAPATH_CONTENT_TYPE . '/libraries/legacy/view/legacy.php');
		}
	}

	public function onAfterInitialise()
	{
		//only override Joomla core for some cases to ensure that other extensions still work properly with Joomla Content component
		$app = JFactory::getApplication();
		$input = $app->input;

		//list articles
		if($app->isAdmin() && $input->get('option') == 'com_content' && $input->get('view') == 'articles') {
			JLoader::register('ContentModelArticles', JAPATH_CONTENT_TYPE . '/models/com_content.admin.articles.php');
		}

		// check to automatically generate images for contents
		// If the option Auto-generated Images enabled, JACT scan content table to
		// auto generate 3 sizes BIG, MEDIUM, SMALL for images of last 200 contents
		// TODO: not implement yet
		if (false && $app->isAdmin()) {
			$jact_img_enabled = JComponentHelper::getParams('com_content')->get('jact_img_enabled');
			$scanned = 'scanned';
			if ($jact_img_enabled && $this->params->get('status') != $scanned) {
				// do scan and generate images here
				require_once JAPATH_CONTENT_TYPE . '/helpers/image.php';
				JAContentTypeImageHelper::scanImages();

				// update scanned status
				$this->params->set('status', $scanned);
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->update('#__extensions')
					->set('params=' . $db->quote($this->params->toString()))
					->where(array(
						$db->quoteName('type') . '=' . $db->quote('plugin'),
						$db->quoteName('element') . '=' . $db->quote($this->_name),
						$db->quoteName('folder') . '=' . $db->quote('system')
					));
				$db->setQuery($query);
				$db->execute();
			}

			if (!$jact_img_enabled && $this->params->get('status') == $scanned) {
				// reset scanned status
				$this->params->set('status', '');
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->update('#__extensions')
					->set('params=' . $db->quote($this->params->toString()))
					->where(array(
						$db->quoteName('type') . '=' . $db->quote('plugin'),
						$db->quoteName('element') . '=' . $db->quote($this->_name),
						$db->quoteName('folder') . '=' . $db->quote('system')
					));
				$db->setQuery($query);
				$db->execute();
			}
		}
	}

	protected function _onContentPrepareFormMenuEdit($form, $data) {
		$pattern = '/^index\.php\?option=com_content\&view=form\&layout=edit(&contenttype=.*)?/i';
		if(
		(is_array($data) && preg_match($pattern, $data['link']))
		|| (is_object($data) && preg_match($pattern, $data->link))
		) {
			$this->addFormPath();
			$form->loadFile('com_menus.item.com_article.form.edit', false);
		}
	}

	protected function _onContentPrepareFormArticleEdit($form, $data) {
		$this->addFormPath();

		$app = JFactory::getApplication();
		
		$lang = JFactory::getLanguage();
		$extension = 'plg_system_jacontenttype_ex';
		$base_dir = JPATH_ADMINISTRATOR;
		$language_tag = 'en-GB';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);

		$isNew = true;
		if(is_object($data) && $data->id) {
			$isNew = false;
			if(isset($data->attribs) && is_string($data->attribs)) {
				$data->attribs = json_decode($data->attribs, true);
			}
			$contenttype = isset($data->attribs['ctm_content_type']) ? $data->attribs['ctm_content_type'] : 'article';
			$isNew = false;
		} elseif (is_array($data) && $data['id']) {
			$contenttype = isset($data['attribs']['ctm_content_type']) ? $data['attribs']['ctm_content_type'] : 'article';
		} else {
			//is new
			$post  = $app->input->post->get('jform', array(), 'array');
			if(isset($post['attribs']['ctm_content_type'])) {
				$contenttype = $post['attribs']['ctm_content_type'];
			} else {
				$contenttype = $app->input->get('contenttype', 'article');
			}
		}
		if($contenttype) {
			$form->loadFile($contenttype, false);
		}
		$form->loadFile('article_edit', false);
		//update page title
		$this->pageTitle = 'pencil-2 article-add:'.JText::sprintf('PLG_JACONTENT_TYPE_PAGE_' . (($isNew ? 'ADD_ITEM' : 'EDIT_ITEM')), ucfirst(str_replace('_', ' ', $contenttype)));

		//move tab content meta config to the first
		$doc = JFactory::getDocument();
		$script = '
		(function($){
			$(window).load(function(){
				var tabs = $("#myTabTabs");
				if(tabs) {
					var tab = tabs.find(\'li > a[href="#attrib-content_meta"]\');
					if(tab) {
						tab.parent().prependTo(tabs);
						tab.trigger("click");
					}
				}
			});
		})(jQuery);
		';
		$doc->addScriptDeclaration($script);
	}

	protected function _onContentPrepareFormArticles($form, $data) {
		$this->pageTitle = 'stack article:'.JText::_('PLG_JACONTENT_TYPE_PAGE_ITEMS_TITLE');

		//Adding new filter option
		$this->addFormPath();
		$form->loadFile('filter_articles_xtd', false);

		//Adding new toolbar buttons
		$user  = JFactory::getUser();
		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		$canDo = JHelperContent::getActions('com_content', 'category', 0);

		require_once JAPATH_CONTENT_TYPE . '/helpers/toolbar.dropdown.php';

		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_content', 'core.create'))) > 0 )
		{

			$files = $this->getContentTypes();
			if(count($files)) {
				$buttons = array();
				foreach($files as $file) {
					$xml = simplexml_load_file($file);
					//add buttons to create different content types
					//JToolbarHelper::addNew('article.add', JText::sprintf('PLG_JACONTENT_TYPE_NEW_VAR', (string) $xml->title));
					$buttons[] = array(
						'icon' => isset($xml->icon) ? (string) $xml->icon : 'pencil',
						'type' => (string) $xml->type,
						'title' => (string) $xml->title,
						'link' => JRoute::_('index.php?option=com_content&view=article&layout=edit&contenttype='.((string)$xml->type))
					);
				}
				if(count($buttons)) {
					array_unshift($buttons, array(
						'icon' => 'pencil',
						'type' => 'article',
						'title' => JText::_('PLG_JACONTENT_TYPE_ARTICLE')
					));
					$bar->appendButton('Dropdown', 'new', JText::_('PLG_JACONTENT_TYPE_ADD_NEW'), 'article.add', $buttons, false);
					$bar->appendButton('Separator');
				}
			}

		}
	}

	public function getContentTypes() {
		$files = JFolder::files($this->pathType, '\.xml$', false, true);

		$templates = JFolder::folders(JPATH_ROOT.'/templates/', '.', false, true);
		foreach($templates as $template) {
			if(JFolder::exists($template.'/contenttype/types/')) {
				$files = array_merge($files, JFolder::files($template.'/contenttype/types/', '\.xml$', false, true));
			}
		}

		return $files;
	}

	public function addFormPath() {
		JFormHelper::addFieldPath($this->pathField);
		JFormHelper::addFormPath($this->pathForm);
		JFormHelper::addFormPath($this->pathType);

		$templates = JFolder::folders(JPATH_ROOT.'/templates/', '.', false, true);
		foreach($templates as $template) {
			$path = $template.'/contenttype/';
			if(JFolder::exists($path)) {
				JFormHelper::addFormPath($path.'forms');
				JFormHelper::addFormPath($path.'types');
				JFormHelper::addFieldPath($path.'fields');
			}
		}
	}
}