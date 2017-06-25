<?php
/**
 * ------------------------------------------------------------------------
 * Plugin Ajax JA Content Type
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
/**
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.Jacontenttype
 * @since       1.5
 */
class PlgAjaxJacontenttype extends JPlugin
{
	protected $layoutBasePath;
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->layoutBasePath = JPATH_ROOT . '/plugins/ajax/jacontenttype/layouts';
	}

	public function onAjaxJacontenttype() {
		$app = JFactory::getApplication();
		$view = $app->input->getCmd('view', '');
		switch($view) {
			case 'contacts':
				$this->getContacts();
				break;
			case 'items':
				$this->getItems();
				break;
			case 'media':
				$this->getMedia();
				break;
			case 'mediaList':
				$this->getMediaList();
				break;
		}
	}

	protected function  getItems() {
		JLoader::register('JAContentTypeModelItems', JPATH_ROOT . '/plugins/system/jacontenttype/models/items.php');
		$model = new JAContentTypeModelItems();

		$app = JFactory::getApplication();
		$lang	= JFactory::getLanguage();
		$lang->load('com_content', JPATH_ADMINISTRATOR);
		$content_type = $app->input->get('content_type', '');

		$items = $model->getMetaItems($content_type);
		$state = $model->getState();

		$limit = (int) $model->getState('list.limit') - (int) $model->getState('list.links');
		$pagination = new JPagination($model->getTotal(), $model->getStart(), $limit);

		$options = array('items' => $items, 'state' => $state, 'pagination' => $pagination);
		echo JLayoutHelper::render('contenttype.items', $options, $this->layoutBasePath);
	}

	protected function  getMedia() {
		JLoader::register('MediaModelManager', JPATH_ADMINISTRATOR. '/components/com_media/models/manager.php');
		$config = JComponentHelper::getParams('com_media');
		$lang	= JFactory::getLanguage();
		$model = new MediaModelManager();

		$lang->load('com_media', JPATH_ADMINISTRATOR);

		$path = 'file_path';
		define('COM_MEDIA_BASE',    JPATH_ROOT . '/' . $config->get($path, 'images'));
		define('COM_MEDIA_BASEURL', JUri::root() . $config->get($path, 'images'));
		// Include jQuery
		JHtml::_('jquery.framework');
		JHtml::_('script', 'media/popup-imagemanager.js', true, true);
		JHtml::_('stylesheet', 'media/popup-imagemanager.css', array(), true);

		if ($lang->isRTL())
		{
			JHtml::_('stylesheet', 'media/popup-imagemanager_rtl.css', array(), true);
		}

		/*
		 * Display form for FTP credentials?
		 * Don't set them here, as there are other functions called before this one if there is any file write operation
		 */
		$ftp = !JClientHelper::hasCredentials('ftp');


		$options = array(
			'session' => JFactory::getSession(),
			'config' => $config,
			'state' => $model->getState(),
			'folderList' => $model->getFolderList(),
			'require_ftp' => $ftp
		);
		echo JLayoutHelper::render('contenttype.media', $options, $this->layoutBasePath);
	}

	protected function  getMediaList() {
		JLoader::register('MediaHelper', JPATH_ADMINISTRATOR. '/components/com_media/helpers/media.php');
		JLoader::register('MediaModelList', JPATH_ADMINISTRATOR. '/components/com_media/models/list.php');
		$config = JComponentHelper::getParams('com_media');

		// Do not allow cache
		JFactory::getApplication()->allowCache(false);

		$lang	= JFactory::getLanguage();
		$lang->load('com_media', JPATH_ADMINISTRATOR);
		$path = 'file_path';
		define('COM_MEDIA_BASE',    JPATH_ROOT . '/' . $config->get($path, 'images'));
		define('COM_MEDIA_BASEURL', JUri::root() . $config->get($path, 'images'));

		JHtml::_('stylesheet', 'media/popup-imagelist.css', array(), true);
		if ($lang->isRTL()) {
			JHtml::_('stylesheet', 'media/popup-imagelist_rtl.css', array(), true);
		}

		$document = JFactory::getDocument();
		$document->addScriptDeclaration("var ImageManager = window.parent.ImageManager;");

		$model = new MediaModelList();

		$options = array(
			'baseURL' => COM_MEDIA_BASEURL,
			'docs' => $model->getDocuments(),
			'images' => $model->getImages(),
			'folders' => $model->getFolders(),
			'state' => $model->getState()
		);
		echo JLayoutHelper::render('contenttype.media_list', $options, $this->layoutBasePath);
	}

	protected function  getContacts() {
		JLoader::register('ContactModelContacts', JPATH_ADMINISTRATOR . '/components/com_contact/models/contacts.php');
		$model = new ContactModelContacts(array());

		$app = JFactory::getApplication();
		$lang	= JFactory::getLanguage();
		$lang->load('com_contact', JPATH_ADMINISTRATOR);

		$items = $model->getItems();
		$state = $model->getState();

		$limit = (int) $model->getState('list.limit') - (int) $model->getState('list.links');
		$pagination = new JPagination($model->getTotal(), $model->getStart(), $limit);

		$options = array('items' => $items, 'state' => $state, 'pagination' => $pagination);
		echo JLayoutHelper::render('contact.items', $options, $this->layoutBasePath);
	}
}