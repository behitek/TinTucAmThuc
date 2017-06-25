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
$show_section_title = $aparams->get('show_section_title');
$show_layout_tool = $aparams->get('show_layout_tool');
$default_layout = $aparams->get('default_layout');

// get news
$catids = $aparams->get('list_categories');
if (!count($catids)) return ;
$categories = JATemplateHelper::loadCategories($catids);

// get list articles for each sub cat
$cat_items = array();
foreach ($categories as $cat) {
	$cat_items[$cat->id] = JATemplateHelper::getArticles($aparams, $cat->id, $aparams->get('highlight_count', 4));
}

$cols = $aparams->get('highlight_columns', 2);
$direction = $aparams->get('direction', 'hoz');
$col_width = round(12 / $cols);

$sidebar_pos = $aparams->get('sidebar-pos');
$sidebar = ($sidebar_pos && $helper->countModules($sidebar_pos));
$mainwidth = $sidebar ? 8 : 12;
?>

<div class="row magazine-list <?php echo $default_layout; ?> equal-height">
	<!-- MAGAZINE LISTING -->
	<div class="col col-md-<?php echo $mainwidth ?> magazine-categories">

		<?php if( $show_section_title || $show_layout_tool) : ?>
		<div class="magazine-section-heading">
		<?php if($show_section_title) : ?>
			<h4><?php echo $section_title; ?></h4>
		<?php endif; ?>

		<?php if($show_layout_tool) : ?>
		<div class="magazine-section-tools">
			<label>View as:</label>
			<a href="#" class="btn" title="Grid View" data-action="switchClass" data-target=".magazine-list"
				 data-value="grid-view" data-key="acm<?php echo $module->id ?>" data-default="1"><i class="fa fa-th-large"></i> Grid</a>
			<a href="#" class="btn" title="List View" data-action="switchClass" data-target=".magazine-list"
				 data-value="list-view" data-key="acm<?php echo $module->id ?>"><i class="fa fa-list"></i> List</a>
		</div>
		<?php endif ?>

	</div>
	<?php endif ?>

		<?php foreach ($categories as $cat) : ?>

			<?php
			$i = 0;
			$items = $cat_items[$cat->id];
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

			<div class="magazine-category">

				<div class="magazine-category-title <?php echo JATemplateHelper::getCategoryClass($cat->id) ?>">
					<h2><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id)); ?>"
								 title="<?php echo $cat->title; ?>">
							<?php echo $cat->title; ?>
						</a></h2>
				</div> 

				<?php if ($direction == 'hoz'): ?>

					<?php
					$i = 0;
					$t = count($items);
					foreach ($items as $item) : ?>
						<?php if ($i % $cols == 0): /* start new row */ ?>
							<div class="row-articles equal-height">
						<?php endif ?>
						<div class="col col-xs-12 col-sm-<?php echo $col_width ?> magazine-item">
							<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams)); ?>
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
							<div class="magazine-item-inner" itemscope>
								<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams)); ?>
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

			</div>

		<?php endforeach ?>
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