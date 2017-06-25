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
$aparams->loadArray ($helper->toArray(true));
// get featured items
$catid = $aparams->get ('catid', 1);
$count_leading = $aparams->get ('featured_leading', 1);
$count_intro   = $aparams->get ('featured_intro', 3);
$count_links   = $aparams->get ('featured_links', 5);
$intro_columns = $aparams->get ('featured_intro_columns', 3);
$featured_count = $count_leading + $count_intro + $count_links;
$leading       = $intro = $links = array();
$leading_title = $aparams->get('leading_title');
$show_leading_title   = $aparams->get('show_leading_title');
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
?>

<div class="row style-3 magazine-featured">
    <?php if (count ($leading)): ?>
		<div class="col col-md-6 magazine-featured-items">

      <?php if($show_leading_title): ?>
			<div class="magazine-section-heading">
				<h4><?php echo $leading_title; ?></h4>
			</div>
      <?php endif; ?>
			
			<!-- Leading -->
			<?php
			$aparams->set('show_intro', 0);
			?>
			
			<?php foreach ($leading as $item) :?>  
      <div class="magazine-leading magazine-featured-leading">
				<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams, 'img-size' => 'big')); ?>
      </div>
			<?php endforeach; ?>
			
			<!-- //Leading -->
			

		</div> <!-- //Left Column -->
    <?php endif ?>

		<?php if ($intro_count = count ($intro)): ?>
			<!-- Intro -->
			<?php
			$aparams->set('show_intro', 0);
			$aparams->set('show_readmore', 0);
			$aparams->set('show_category', $aparams->get('show_intro_category', $show_category));
			$aparams->set('block_position', $aparams->get('intro_block_position', $block_position));
			?>
			<div class="<?php if (count ($leading)): ?> col-md-6 <?php else: ?> col-md-12 <?php endif; ?> magazine-intro magazine-featured-intro">
				<?php $intro_index = 0; ?>
				<?php foreach ($intro as $item) : ?>
					<?php if($intro_index % $intro_columns == 0) : ?>
						<div class="row-articles">
					<?php endif ?>
					<div class="magazine-item col-sm-<?php echo round((12 / $intro_columns)) ?>">
						<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams, 'img-size' => 'medium')); ?>
					</div>
					<?php $intro_index++; ?>
					<?php if(($intro_index % $intro_columns == 0) || $intro_index == $intro_count) : ?>
						</div>
					<?php endif ?>
				<?php endforeach; ?>
			</div>
			<!-- // Intro -->
		<?php endif ?>



</div>