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

// get news
$catids = $aparams->get('list_categories');
if (!count($catids)) return ;
$categories = JATemplateHelper::loadCategories($catids);

// get list articles for each sub cat
$cat_items = array();
foreach ($categories as $cat) {
	$cat_items[$cat->id] = JATemplateHelper::getArticles($aparams, $cat->id, $aparams->get('highlight_count', 4));
}

$cols = $aparams->get('columns', 2);
$leading_count = $aparams->get('leading_count');
$col_width = round(12 / $cols);

$sidebar_pos = $aparams->get('sidebar-pos');
$sidebar = ($sidebar_pos && $helper->countModules($sidebar_pos));
$mainwidth = $sidebar ? 8 : 12;
?>

<div class="row style-2 magazine-list equal-height">
	<!-- MAGAZINE LISTING -->
	<div class="col col-md-<?php echo $mainwidth ?> magazine-categories">

		<div class="magazine-section-heading">
		<?php if($show_section_title) : ?>
			<h4><?php echo $section_title; ?></h4>
		<?php endif; ?>

			<div class="magazine-section-tools">
				<label>View as:</label>
				<a href="#" class="btn" title="Grid View" data-action="switchClass" data-target=".magazine-list"
					 data-value="grid-view" data-key="acm<?php echo $module->id ?>" data-default="1"><i class="fa fa-th-large"></i> Grid</a>
				<a href="#" class="btn" title="List View" data-action="switchClass" data-target=".magazine-list"
					 data-value="list-view" data-key="acm<?php echo $module->id ?>"><i class="fa fa-list"></i> List</a>
			</div>
		</div>

		<?php
		$i = 0;
		$count = count($categories);
		?>

		<?php foreach ($categories as $cat) : ?>

			<?php
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

			<?php if ($i % $cols == 0): /* start new row */ ?>
				<div class="row-articles equal-height">
			<?php endif ?>
			<div class="col col-xs-12 col-sm-<?php echo $col_width ?> magazine-category">

				<div class="magazine-category-title <?php echo JATemplateHelper::getCategoryClass($cat->id) ?>">
					<h2><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id)); ?>"
								 title="<?php echo $cat->title; ?>">
							<?php echo $cat->title; ?>
						</a></h2>
				</div>

				<?php
				$j = 0;
				foreach ($items as $item) : ?>
					<?php if ($j++ < $leading_count): ?>
						<div class="magazine-item magazine-leading">
							<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams)); ?>
						</div>
					<?php else: ?>
						<?php echo JLayoutHelper::render('joomla.content.link.default', array('item' => $item, 'params' => $aparams)); ?>
					<?php endif ?>
				<?php endforeach; ?>

			</div>
			<?php if (++$i % $cols == 0 || $i == $count): /* close row */ ?>
				</div>
			<?php endif ?>
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