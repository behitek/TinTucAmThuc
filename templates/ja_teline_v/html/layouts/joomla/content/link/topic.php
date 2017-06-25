<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$item = $displayData['item'];
$aparams = $displayData['params'];
$positions = $aparams->get('block_position', 0);

$useDefList =
		$aparams->get('show_publish_date') ||
		$aparams->get('show_hits') ||
		$aparams->get('show_category') ||
		$aparams->get('show_author');

?>
<div class="magazine-item link-item topic-item">

	<div class="col col-media">
		<?php echo JLayoutHelper::render('joomla.content.image.intro', array('item'=>$item, 'img-size'=>'small')); ?>
	</div>

	<div class="col col-content">

		<?php if ($useDefList && in_array($positions, array(0, 2))) : ?>
			<aside class="article-aside">
				<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_category', array('item' => $item, 'params' => $aparams, 'position'=>'above')); ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_block', array('item' => $item, 'params' => $aparams, 'position' => 'above')); ?>
			</aside>
		<?php endif; ?>

		<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $item); ?>

		<?php
		if((int) $aparams->get('show_latest_article')) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select($db->quoteName('cm.content_id'))
				->from('#__content_meta cm')
				->innerJoin('#__content c ON c.id = cm.content_id')
				->where(array($db->quoteName('cm.meta_key').'='.$db->quote('topic_id'), $db->quoteName('cm.meta_value').'='.$db->quote($item->id)))
				->where('c.state = 1')
				->order($db->quoteName('cm.content_id').' DESC');
			$db->setQuery($query);
			$article = (int) $db->loadResult();
			if($article) {

				JLoader::register('ContentModelArticle', JPATH_ROOT . '/components/com_content/models/article.php');
				$model = new ContentModelArticle(array());
				$article = $model->getItem($article);
				$article->slug    = $article->id . ':' . $article->alias;
				$article->catslug = $article->catid ? $article->catid . ':' . $article->category_alias : $article->catid;
				?>
				<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>">
					<?php echo $this->escape($article->title); ?></a>
				<?php
			}
		}
		?>

		<?php if ($useDefList && in_array($positions, array(1, 2))) : ?>
			<aside class="article-aside">
				<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_category', array('item' => $item, 'params' => $aparams, 'position'=>'above')); ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $item, 'params' => $aparams, 'position' => 'below')); ?>
			</aside>
		<?php endif; ?>
	</div>
</div>