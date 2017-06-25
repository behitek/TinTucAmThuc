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

JLoader::register('JAContentTypeModelItems', JPATH_ROOT . '/plugins/system/jacontenttype/models/items.php');
JLoader::register('JAContentTypeModelItem', JPATH_ROOT . '/plugins/system/jacontenttype/models/item.php');

//number of older articles to display
$numBeforeItems = 2;
//number of newer articles to display
$numAfterItems = 2;
$topic_id = (int) $item->params->get('ctm_topic_id', 0);
if($topic_id):
	$db 		= JFactory::getDbo();
	$modelItem 	= new JAContentTypeModelItem(array());
	$model 		= new JAContentTypeModelItems();

	$topic = $modelItem->getItem($topic_id);

	$ordering = $model->getState('list.ordering');//trick to run populateState function before re-set some state
	$model->setState('list.ordering', 'a.publish_up');
	$model->setState('list.direction', 'DESC');
	//$where = array('a.id <> '.$item->id);
	$where = array();
	$items = $model->getMetaItems('', $where, array('topic_id' => '='.$topic_id));

	if(count($items)):
		$currIndex = 0;
		foreach($items as $index => $article) {
			if($article->id == $item->id) {
				$currIndex = $index;
				break;
			}
		}
		//$begin 	= ($currIndex > $numBeforeItems) ? $currIndex - $numBeforeItems : 0;
		//$end	= ($currIndex + $numAfterItems < count($items)) ? $currIndex + $numAfterItems : count($items);
		?>
	<aside class="topic-links">
		<?php if($topic): ?>
			<div class="topic-header">
				<h5><?php echo JText::_('Topic'); ?></h5>
				<h6>
					<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($topic->id, $topic->catid)); ?>" title="<?php echo htmlspecialchars(JText::sprintf('ARTICLES_ABOUT_THE_TOPIC', $topic->title)); ?>">
						<?php echo $topic->title; ?>&nbsp;&nbsp;<i class="fa fa-long-arrow-right"></i>
					</a>
				</h6>
			</div>
		<?php endif; ?>
		<div class="topic-links-ct">
			<ul class="nav">
                <?php $counter = 0; ?>
				<?php foreach($items as $index => $article):
					//if($index > $end) break;
					if($article->id != $item->id):
					?>
					<li>
						<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid)); ?>" title="<?php echo htmlspecialchars($article->title); ?>">
							<?php echo $article->title; ?>
						</a>
					</li>
                        <?php $counter++; ?>
					<?php endif; ?>
                    <?php if ($counter > 4 || $counter > count($items)) break; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</aside>
	<?php endif; ?>
<?php endif; ?>