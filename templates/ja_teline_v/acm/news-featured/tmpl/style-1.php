<?php
/**
 *------------------------------------------------------------------------------
 * @package Purity III Template - JoomlArt
 * @version 1.0 Feb 1, 2014
 * @author JoomlArt http://www.joomlart.com
 * @copyright Copyright (c) 2004 - 2014 JoomlArt.com
 * @license GNU General Public License version 2 or later;
 *------------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

$aparams = JATemplateHelper::getParams();
$aparams->loadArray($helper->toArray(true));
// get featured items
$catid = $aparams->get('catid', 1);
$count_leading = $aparams->get('featured_leading', 1);
$count_intro = $aparams->get('featured_intro', 3);
$count_links = $aparams->get('featured_links', 5);
$intro_columns = $aparams->get('featured_intro_columns', 3);
$featured_count = $count_leading + $count_intro + $count_links;
$leading = $intro = $links = array();
$leading_title = $aparams->get('leading_title');
$leading_auto_play = $aparams->get('leading_auto_play');
$show_leading_title = $aparams->get('show_leading_title');
$block_links_title = $aparams->get('block_links_title');
$show_block_links_title = $aparams->get('show_block_links_title');

$items = JATemplateHelper::getArticles($aparams, $catid, $featured_count);

$i = 0;
foreach ($items as &$item) {

	if ($i < $count_leading) {
		$leading[] = $item;
	} elseif ($i < $count_leading + $count_intro) {
		$intro[] = $item;
	} else {
		$links[] = $item;
	}

	$i++;
}

// get global values
$show_intro = $aparams->get('show_intro');
$show_category = $aparams->get('show_category');
$show_readmore = $aparams->get('show_readmore');
$show_hits = $aparams->get('show_hits');
$show_author = $aparams->get('show_author');
$show_publish_date = $aparams->get('show_publish_date');
$block_position = $aparams->get('block_position');
$animation_type = $aparams->get('animation_type', 'slide');
?>

<div class="row style-1 magazine-featured">

	<div class="col magazine-featured-items <?php echo $count_links?' col-md-8': ' col-md-12'; ?>">
		<?php if ($show_leading_title) : ?>
			<div class="magazine-section-heading">
				<h4><?php echo $leading_title; ?></h4>
			</div>
		<?php endif; ?>

		<?php if (count($leading)): ?>
			<!-- Leading -->
			<?php
			$aparams->set('show_intro', $aparams->get('show_leading_intro', $show_intro));
			$aparams->set('show_category', $aparams->get('show_leading_category', $show_category));
			$aparams->set('show_readmore', $aparams->get('show_leading_readmore', $show_readmore));
			$aparams->set('block_position', $aparams->get('leading_block_position', $block_position));
			?>
			<div class="magazine-item magazine-leading magazine-featured-leading">
				<?php if (count($leading) > 1) :
					/* Present leading items in a carousel */
					?>
					<div id="magazine-carousel-<?php echo $module->id ?>" class="carousel magazine-carousel <?php echo $animation_type ?>"
							 data-ride="carousel" <?php if (!$leading_auto_play) : ?>data-interval="false"<?php endif; ?>>
						<div class="carousel-inner" role="listbox">

							<?php
							$i = 0;
							foreach ($leading as $item) : ?>
								<div class="item <?php if ($i++ == 0) echo 'active'; ?>">
									<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams, 'img-size' => 'big')); ?>
								</div>
							<?php endforeach; ?>

						</div>

						<!-- Controls -->
						<div class="carousel-control-btns">
              <span class="carousel-number">
              	<strong class="carousel-number-index">1</strong>&nbsp; of &nbsp;<strong><?php echo count($leading) ?></strong>
              </span>
							<a class="btn left" href="#magazine-carousel-<?php echo $module->id ?>" role="button" data-slide="prev">
								<i class="fa fa-chevron-left"></i>
								<span class="sr-only">Previous</span>
							</a>
							<a class="btn right" href="#magazine-carousel-<?php echo $module->id ?>" role="button" data-slide="next">
								<i class="fa fa-chevron-right"></i>
								<span class="sr-only">Next</span>
							</a>
						</div>
					</div>

					<script>
						(function ($) {
							$('#magazine-carousel-<?php echo $module->id ?>').on('slid.bs.carousel', function () {
								var $carousel = $(this),
									currentIndex = $carousel.find('.item.active').index() + 1;
								$carousel.find('.carousel-number-index').html(currentIndex);
							});
						})(jQuery);
					</script>
				<?php elseif (count($leading)): /* Single Leading item */ ?>
					<?php echo JATemplateHelper::render($leading[0], 'joomla.content.intro', array('item' => $leading[0], 'params' => $aparams, 'img-size' => 'big')); ?>
				<?php endif; ?>
			</div>

			<!-- //Leading -->
		<?php endif ?>

		<?php if ($intro_count = count($intro)): ?>
			<?php
			$aparams->set('show_intro', $aparams->get('show_intro_text', $show_intro));
			$aparams->set('show_category', $aparams->get('show_intro_category', $show_category));
			$aparams->set('show_readmore', $aparams->get('show_intro_readmore', $show_readmore));
			$aparams->set('block_position', $aparams->get('intro_block_position', $block_position));
			?>
			<!-- Intro -->
			<div class="magazine-intro magazine-featured-intro">
				<?php $intro_index = 0; ?>
				<?php foreach ($intro as $item) : ?>
					<?php if ($intro_index % $intro_columns == 0) : ?>
						<div class="row-articles equal-height">
					<?php endif ?>
					<div class="col col-sm-<?php echo round((12 / $intro_columns)) ?> magazine-item ">
						<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams, 'img-size' => 'medium')); ?>
					</div>
					<?php $intro_index++; ?>
					<?php if (($intro_index % $intro_columns == 0) || $intro_index == $intro_count) : ?>
						</div>
					<?php endif ?>
				<?php endforeach; ?>
			</div>
			<!-- // Intro -->
		<?php endif ?>

	</div>
	<!-- //Left Column -->

	<?php if (count($links)): ?>
	<?php
	$aparams->set('show_intro', 0);
	$aparams->set('show_readmore', 0);
	$aparams->set('show_category', $aparams->get('show_link_category', $show_category));
	$aparams->set('show_hits', $aparams->get('show_link_hits', $show_hits));
	$aparams->set('show_author', $aparams->get('show_link_author', $show_author));
	$aparams->set('show_publish_date', $aparams->get('show_link_publish_date', $show_publish_date));
	$aparams->set('block_position', $aparams->get('link_block_position', $block_position));
	?>

	<div class="col col-md-4 magazine-featured-links">
		<?php if ($show_block_links_title) : ?>
			<div class="magazine-section-heading">
				<h4 class="line-head"><?php echo $block_links_title; ?></h4>
			</div>
		<?php endif; ?>
		<!-- Links -->
		<div class="magazine-links">
			<?php foreach ($links as $item) : ?>
				<?php echo JLayoutHelper::render('joomla.content.link.default', array('item' => $item, 'params' => $aparams)); ?>
			<?php endforeach; ?>
		</div>
		<!-- // Links -->

		<!-- Banner -->
		<div class="banner">
			<?php
			$document = JFactory::getDocument();
			$renderer = $document->loadRenderer('modules');
			$position = "banner-1";
			$options = array('style' => 'T3xhtml');
			echo $renderer->render($position, $options, null);
			?>
		</div>
		<!-- // Banner -->
	</div>
    <?php endif ?>
	<!-- //Right Column -->

</div>