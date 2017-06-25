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

if(!class_exists('ContentModelArticles')) {
	$app = JFactory::getApplication();
	if($app->isAdmin()) {
		JLoader::register('ContentModelArticles', JPATH_ADMINISTRATOR . '/components/com_content/models/articles.php');
	} else {
		JLoader::register('ContentModelArticles', JPATH_ROOT . '/components/com_content/models/articles.php');
	}
}

class JAContentTypeModelItems extends ContentModelArticles
{
	protected $_where = array();
	protected $_whereMeta = array();
	protected $_orderBy = '';
	protected $_orderDir = '';
	protected $_orderMode = 'string';

	public function clearConditions() {
		$this->_orderBy = '';
		$this->_orderDir = '';
		$this->_orderMode = 'string';
		$this->_where = array();
		$this->_whereMeta = array();
	}

	/**
	 * get items join with content meta table
	 *
	 * @param string $content_type
	 * @param array $where
	 * @param array $whereMeta
	 * @param string $orderBy - extra field name
	 * @param string $orderDir - order direction
	 * @param string $orderMode - specify data type of extra field (string or number) to get list in correct order
	 * @return mixed
	 *
	 * Sample Use:
	 *
	 *
	$model = new JAContentTypeModelItems();

	$items = $model->getMetaItems('event', array(), array('speakers'=>'=3'));
	$items = $model->getMetaItems('event', array(), array('addr2'=>"LIKE '%HaNoi%'", 'latitude' => "='21.047128'"), 'latitude', 'DESC', 'number');
	 */
	public function getMetaItems($content_type = '', $where = array(), $whereMeta = array(), $orderBy = '', $orderDir = 'ASC', $orderMode = 'string') {
		$db = JFactory::getDbo();
		$this->_orderBy 	= $orderBy;
		$this->_orderDir 	= $orderDir;
		$this->_orderMode 	= $orderMode;
		$this->_where 		= $where;

		// merge whereMeta
		foreach ($whereMeta as $attrib => $condition) {
			$this->_whereMeta[$attrib] = $condition;
		}

		if($content_type != '' && $content_type != 'article') {
			$this->_whereMeta['content_type'] = '='.$db->quote($content_type);
		}

		return parent::getItems();
	}

	public function setMetaOrder($orderBy = '', $orderDir = 'ASC', $orderMode = 'string') {
		$this->_orderBy 	= $orderBy;
		$this->_orderDir 	= $orderDir;
		$this->_orderMode 	= $orderMode;
	}

	protected function getListQuery() {
		$db = JFactory::getDbo();
		$query = parent::getListQuery();

		if(count($this->_where)) {
			$query->where($this->_where);
		}

		$tblOrder = '';
		if(count($this->_whereMeta)) {
			$tbl = 0;
			foreach($this->_whereMeta as $meta_key => $condition) {
				$tblname = 'ctm'.(++$tbl);

				if(!empty($this->_orderBy) && $this->_orderBy == $meta_key) {
					$tblOrder = $tblname;
				}
				if($meta_key == 'content_type' && preg_match('/\barticle\b/i', $condition)) {
					$query->leftJoin('#__content_meta AS '.$tblname.' ON ('.$tblname.'.content_id = a.id AND '.$tblname.'.meta_key = '.$db->quote($meta_key).')');
					$query->where($tblname.'.'.$db->quoteName('meta_value').' IS NULL');
				} else {
					$query->innerJoin('#__content_meta AS '.$tblname.' ON ('.$tblname.'.content_id = a.id AND '.$tblname.'.meta_key = '.$db->quote($meta_key).')');
					if(is_array($condition)) {
						if(count($condition)) {
							$where = array();
							foreach($condition as $cond) {
								$where[] = $tblname.'.'.$db->quoteName('meta_value').' '.$cond;
							}
							$query->where('('.implode(' OR ', $where).')');
						}
					} else {
						$query->where($tblname.'.'.$db->quoteName('meta_value').' '.$condition);
					}
				}
			}
		}
		
		// Filter by tags
		$Itemid = $this->getState('filter.tags');

		if (is_numeric($Itemid))
		{
			$query->where($db->quoteName('tagmap.tag_id') . ' IN (SELECT GROUP_CONCAT(`tag_id` SEPARATOR ",") FROM `#__contentitem_tag_map` WHERE `content_item_id` = '.$Itemid.')')
				->join(
					'LEFT', $db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_content.article')
				);
		}

		if($tblOrder) {
			$orders = $query->order;

			//move this order by to first
			$query->clear('order');
			if($this->_orderMode == 'number') {
				$query->order($tblOrder.'.'.$db->quoteName('meta_value').' + 0 '.$this->_orderDir);
			} else {
				$query->order($tblOrder.'.'.$db->quoteName('meta_value').' '.$this->_orderDir);
			}
			if($orders instanceof JDatabaseQueryElement) {
				$query->order($orders->getElements());
			}
		}
		return $query;
	}

	public function metaFilters ($attrib, $conditions = array()) {
		$this->_whereMeta[$attrib] = $conditions;
	}
	/* meta filter: filter base on meta data */
	public function metaFilter ($attrib, $value, $op = '=') {
		$this->_whereMeta[$attrib] = $op . ' ' . JFactory::getDbo()->quote($value);
	}

	/* meta filter: search using like */
	public function metaFilterLike ($attrib, $value) {
		$this->_whereMeta[$attrib] = 'LIKE ' . JFactory::getDbo()->quote('%' . $value . '%');
	}

	/* meta filter: filter in an array of values */
	public function metaFilterIn ($attrib, $values) {
		$db = JFactory::getDbo();
		$value = '';
		foreach ($values as $val) $value = !$value ? $db->quote($val) : ', ' . $db->quote($val);
		$this->_whereMeta[$attrib] = 'IN (' . $value . ')';
	}

	public function metaFilterNotIn ($attrib, $values) {
		$db = JFactory::getDbo();
		$value = '';
		foreach ($values as $val) $value = !$value ? $db->quote($val) : ', ' . $db->quote($val);
		$this->_whereMeta[$attrib] = 'NOT IN (' . $value . ')';
	}
}