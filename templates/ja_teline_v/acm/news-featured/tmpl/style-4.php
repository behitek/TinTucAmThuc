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
$intro_columns = $aparams->get ('featured_intro_columns', 3);
$animation_type = $aparams->get('animation_type');
$leading       = $intro = $links = array();
$leading_auto_play = $aparams->get('leading_auto_play');
$section_heading = $aparams->get('section_heading');
$show_section_heading = $aparams->get('show_section_heading');

$items = JATemplateHelper::getArticles($aparams, $catid, $count_leading);


// get global values
$show_intro = $aparams->get('show_intro');
$show_category = $aparams->get('show_category');
$show_readmore = $aparams->get('show_readmore');
$show_hits = $aparams->get('show_hits');
$show_author = $aparams->get('show_author');
$show_publish_date = $aparams->get('show_publish_date');
$block_position = $aparams->get('leading_block_position');
?>

<div class="row style-4 magazine-featured">

		<div class="col magazine-featured-items">
			<?php if (count ($items)): ?>
			<!-- Leading -->
			<?php
			$aparams->set('show_intro', 0);
			$aparams->set('show_readmore', 0);
			$aparams->set('show_category', $aparams->get('show_leading_category', $show_category));
			$aparams->set('block_position', $aparams->get('leading_block_position', $block_position));
			?>

			<div class="magazine-leading magazine-featured-leading">
        <?php if($show_section_heading) : ?>
        <div class="magazine-section-heading">
          <h4><?php echo $section_heading; ?></h4>
        </div>
        <?php endif; ?>

        <div id="carousel-example-generic" class="carousel slide magazine-carousel" data-ride="carousel" <?php if(!$leading_auto_play) : ?>data-interval="false"<?php endif; ?>>
        
          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <?php
						$i = 0;
						$cols = $intro_columns;
						$count = count ($items);
						foreach ($items as $item) :?>
							<?php if($i % $cols == 0) : ?>
							<div class="item<?php if ($i==0): ?> active<?php endif ?>"><!--Item -->
							<?php endif ?>
              <div class="news-item col-sm-6 col-md-<?php echo (12 / $cols) ?>">
								<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams, 'img-size' => 'medium')); ?>
              </div>
							<?php if((++$i % $cols == 0) || $i == $count) : ?>
								</div><!--//Item -->
							<?php endif ?>
            <?php endforeach; ?>
          </div>
          <!-- Controls -->
					<div class="carousel-control-btns">
            <span class="carousel-number">
            	<strong class="carousel-number-index">1</strong>&nbsp; of &nbsp;<strong><?php echo $count/$cols ; ?></strong>
            </span>
						<a class="btn left" href="#carousel-example-generic" role="button" data-slide="prev">
							<i class="fa fa-chevron-left"></i>
							<span class="sr-only">Previous</span>
						</a>
						<a class="btn right" href="#carousel-example-generic" role="button" data-slide="next">
							<i class="fa fa-chevron-right"></i>
							<span class="sr-only">Next</span>
						</a>
					</div>     
        </div>
        <script>
					(function ($) {
						$('#carousel-example-generic').on('slid.bs.carousel', function () {
							var $carousel = $(this),
								currentIndex = $carousel.find('.item.active').index() + 1;
							$carousel.find('.carousel-number-index').html(currentIndex);
						});
					})(jQuery);
				</script>
			</div>
			<!-- //Leading -->
			<?php endif ?>

		</div> <!-- //Left Column -->

</div>