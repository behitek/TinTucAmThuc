<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$viewobj = $displayData['viewobj'];
$item = $displayData['item'];
$aparams = $displayData['params'];
$params = $item->params;
$positions = $aparams->get('block_position', 0);

$useDefList =
	($params->get('show_modify_date') ||
		$params->get('show_publish_date') ||
		$params->get('show_create_date') ||
		$params->get('show_hits') ||
		$params->get('show_category') ||
		$params->get('show_parent_category') ||
		$params->get('show_author'));
$icons = $params->get('access-edit') || $params->get('show_print_icon') || $params->get('show_email_icon');

?>
<article class="article" itemscope itemtype="http://schema.org/Article">
	<meta itemprop="inLanguage" content="<?php echo ($item->language === '*') ? JFactory::getConfig()->get('language') : $item->language; ?>" />
	<meta itemprop="url" content="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)) ?>" />

	<?php echo JLayoutHelper::render('joomla.content.item_title', array('item' => $item, 'params' => $aparams, 'title-tag'=>'h1')); ?>

	<?php if ($useDefList && in_array($positions, array(0, 2))) : ?>
		<aside class="article-aside article-aside-full">
			<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_block', array('item' => $item, 'params' => $aparams, 'position' => 'above')); ?>
		</aside>
	<?php endif; ?>

	<section class="article-intro-media">
		<?php echo JLayoutHelper::render('joomla.content.image.intro', $displayData); ?>
		<?php $title = $item->category_title; ?>
	</section>

	<section class="article-full">

		<div class="article-content-main">

			<?php if (!$aparams->get('show_intro', 1)) : ?>
				<?php echo $item->event->afterDisplayTitle; ?>
			<?php endif; ?>
			<?php echo $item->event->beforeDisplayContent; ?>

			<?php if ($aparams->get('show_intro', 0)) : ?>
				<blockquote class="article-intro">
					<?php echo $item->introtext; ?>
				</blockquote>
			<?php endif; ?>

			<section class="article-content">
				<?php echo $item->fulltext; ?>
			</section>

			<section class="topic-articles">
				<?php
				JLoader::register('JAContentTypeModelItems', JPATH_ROOT . '/plugins/system/jacontenttype/models/items.php');
				$topic_id = (int) $item->params->get('ctm_topic_id', 0);
				$model = new JAContentTypeModelItems();

				$items = $model->getMetaItems('', array(), array('topic_id' => '='.$item->id));

				if(count($items)):
					$iparams = array('show_publish_date' => 1, 'show_hits' => 1, 'show_category' => 1, 'show_author' => 1, 'block_position' => 0);
					$iparams = new JRegistry($iparams);
					?>
					<h2>
						<small><?php echo JText::_('ARTICLES_ABOUT_THE_TOPIC'); ?></small>
						<?php echo $item->title; ?>
					</h2>
					<div class="magazine-links">
                     <?php
                        //Ordering latests topic by published date
                        usort($items, function($a,$b){
                                        return ($b->displayDate > $a->displayDate);
                                    }
                              );
                    ?>
					<?php foreach($items as $article):

					$article->slug = $article->alias ? ($article->id . ':' . $article->alias) : $article->id;

					$article->parent_slug = ($article->parent_alias) ? ($article->parent_id . ':' . $article->parent_alias) : $article->parent_id;

					// No link for ROOT category
					if ($article->parent_alias == 'root')
					{
						$article->parent_slug = null;
					}

					$article->catslug = $article->category_alias ? ($article->catid.':'.$article->category_alias) : $article->catid;

					$article->event   = new stdClass;

					$dispatcher = JEventDispatcher::getInstance();

					// Old plugins: Ensure that text property is available
					if (!isset($article->text))
					{
						$article->text = $article->introtext;
					}

					JPluginHelper::importPlugin('content');
					$dispatcher->trigger('onContentPrepare', array ('com_content.category', &$article, &$article->params, 0));

					// Old plugins: Use processed text as introtext
					$article->introtext = $article->text;

					$results = $dispatcher->trigger('onContentAfterTitle', array('com_content.category', &$article, &$article->params, 0));
					$article->event->afterDisplayTitle = trim(implode("\n", $results));

					$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_content.category', &$article, &$article->params, 0));
					$article->event->beforeDisplayContent = trim(implode("\n", $results));

					$results = $dispatcher->trigger('onContentAfterDisplay', array('com_content.category', &$article, &$article->params, 0));
					$article->event->afterDisplayContent = trim(implode("\n", $results));

					?>
					<?php echo JLayoutHelper::render('joomla.content.link.default', array('item' => $article, 'params'=> $iparams)); ?>
					<?php //echo JATemplateHelper::render($article, 'joomla.content.intro', array('item' => $article, 'params'=> $iparams)); ?>
				<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</section>

			<?php if ($useDefList && in_array($positions, array(1, 2))) : ?>
				<footer class="article-footer">
					<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $item, 'params' => $aparams, 'position' => 'below')); ?>
				</footer>
			<?php endif; ?>

			<?php if ($aparams->get('show_readmore') && $item->readmore) :
				if ($item->params->get('access-view')) :
					$link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
				else :
					$menu = JFactory::getApplication()->getMenu();
					$active = $menu->getActive();
					$itemId = $active->id;
					$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
					$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));
					$link = new JUri($link1);
					$link->setVar('return', base64_encode($returnURL));
				endif; ?>

				<div class="readmore">
					<a class="btn btn-default" href="<?php echo $link; ?>"><span>
					<?php if (!$item->params->get('access-view')) :
						echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
					elseif ($readmore = $item->alternative_readmore) :
						echo $readmore;
						if ($aparams->get('show_readmore_title', 0) != 0) :
							echo JHtml::_('string.truncate', ($item->title), $aparams->get('readmore_limit'));
						endif;
					elseif ($aparams->get('show_readmore_title', 0) == 0) :
						echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
					else :
						echo JText::_('COM_CONTENT_READ_MORE');
						echo JHtml::_('string.truncate', ($item->title), $aparams->get('readmore_limit'));
					endif; ?>
				</span></a>
				</div>

			<?php endif; ?>

			<?php echo $item->event->afterDisplayContent; ?>

		</div>
	</section>

</article>
