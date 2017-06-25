<?php
/**
 *------------------------------------------------------------------------------
 * @package Teline V Template - JoomlArt
 * @version 1.0 Feb 1, 2014
 * @author JoomlArt http://www.joomlart.com
 * @copyright Copyright (c) 2004 - 2014 JoomlArt.com
 * @license GNU General Public License version 2 or later;
 *------------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

$aparams = JATemplateHelper::getParams();

$aparams->loadArray($helper->toArray(true));

// Get basic setting
$section_title = $aparams->get('section_title');
$show_section_title = $aparams->get('show_section_title', 0);

// get news
$catids = $aparams->get('list_categories');
$categories = count($catids) ? JATemplateHelper::loadCategories($catids) : JATemplateHelper::getCategories();

// get list articles for each sub cat
JText::script('TPL_LOAD_MODULE_AJAX_DONE');
$display_items = (int) $aparams->get('highlight_count', 4);
$limit = (int) $aparams->get('highlight_limit', 0);
$maxPage = 10;//only get 10 pages by default
if($limit) {
	if($display_items) {
		$maxPage = ceil($display_items / $limit);
	}
	$display_items = $limit;
}
$items = JATemplateHelper::getArticles($aparams, $catids, $display_items);

$cols = $aparams->get('highlight_columns', 2);
$direction = $aparams->get('direction', 'hoz');
$col_width = round(12 / $cols);

$sidebar_pos = $aparams->get('sidebar-pos');
$sidebar = ($sidebar_pos && $helper->countModules($sidebar_pos));
$mainwidth = $sidebar ? 8 : 12;


$isAjax = ($app->input->get('t3action') == 'module');
$listId = 'magazine-category-module-'.$module->id;
?>

<?php if(!$isAjax): ?>
<div class="row magazine-list videos-list equal-height">
	<!-- MAGAZINE LISTING -->
	<div class="col col-md-<?php echo $mainwidth ?> magazine-categories">
		<?php if($show_section_title) : ?>
			<div class="magazine-section-heading videos-section-heading">
				<h4><?php echo $section_title; ?></h4>
			</div>
		<?php endif; ?>

		<div class="magazine-category" id="<?php echo $listId; ?>">
<?php endif; ?>
			<?php
			$i = 0;
			foreach ($items as $item) {
				$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;

				$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;

				// No link for ROOT category
				if ($item->parent_alias == 'root') {
					$item->parent_slug = null;
				}

				$item->catslug = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
			}
			?>

				<?php if ($direction == 'hoz'): ?>

					<?php
					$i = 0;
					$t = count($items);
					foreach ($items as $item) : ?>
						<?php if ($i % $cols == 0): /* start new row */ ?>
							<div class="row-articles equal-height">
						<?php endif ?>
						<div class="col col-xs-12 col-sm-<?php echo $col_width ?> magazine-item videos-item" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
							<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams, 'img-size' => 'medium')); ?>
						</div>
						<?php if (++$i % $cols == 0 || $i == $t): /* close row */ ?>
							</div>
						<?php endif ?>
					<?php endforeach; ?>

				<?php else: ?>

					<div class="row-articles equal-height">
						<?php
						$t = count($items);
						$c = $cols;
						$n = ceil($t / $c);
						$i = 0;
						?>
						<?php foreach ($items as $item) : ?>
							<?php if ($i == 0): /* start new col */ ?>
								<div class="col col-xs-12 col-sm-<?php echo $col_width ?> magazine-item">
							<?php endif ?>
							<div class="magazine-item-inner">
								<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams, 'img-size' => 'medium')); ?>
							</div>
							<?php  if (++$i == $n):
								$i = 0;
								$t -= $n;
								$c--;
								$n = $c ? ceil($t / $c) : 0;
								/* close col */
								?>
								</div>
							<?php endif ?>
						<?php endforeach; ?>
					</div>

				<?php endif ?>

<?php if(!$isAjax): ?>
			</div>
			<?php if($limit && count($items) == $limit): ?>
				<div class="load-more">
					<button class="btn btn-default btn-info" data-link="<?php echo JUri::getInstance()->toString(); ?>" data-maxpage="<?php echo $maxPage; ?>" onclick="jActions.loadModuleNextPage(this, <?php echo $module->id; ?>, '<?php echo $listId; ?>', function(){JAVideoPlayer.playlist();}); return false;" title="<?php echo JText::_('Load More'); ?>">
						<?php echo JText::_('TPL_LOAD_MORE'); ?>
						<span class="fa fa-spin fa-circle-o-notch" style="display: none"></span>
					</button>
				</div>
			<?php endif; ?>
	</div>
	<!-- //MAGAZINE LISTING -->

	<?php if ($sidebar) : ?>
		<!-- SIDEBAR -->
		<div class="col col-md-4 t3-sidebar">
			<?php echo $helper->renderModules($sidebar_pos, array('style' => 'T3xhtml')) ?>
		</div>
		<!-- //SIDEBAR -->
	<?php endif ?>

</div>
<?php endif; ?>