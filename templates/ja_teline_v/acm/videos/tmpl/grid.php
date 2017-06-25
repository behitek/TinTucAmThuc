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
$count_leading = $aparams->get('featured_leading', 5);
//$count_intro = $aparams->get('featured_intro', 3);
$count_intro = 0;
//$count_links = $aparams->get('featured_links', 5);
$count_links = 0;
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

<div class="row videos-grid">

	<div class="col videos-grid-items">
		<?php if ($show_leading_title) : ?>
			<div class="magazine-section-heading videos-section-heading col-md-6">
				<h4><?php echo $leading_title; ?></h4>
			</div>
		<?php endif; ?>

		<div class="player-wrap col-md-6" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
			<div id="ja-main-player" itemprop="video">
				<?php if (count($leading)): ?>
					<?php echo JLayoutHelper::render('joomla.content.video_play', array('item' => $leading[0], 'context' => 'featured')); ?>
				<?php endif ?>
			</div>

			<script type="text/javascript">

				(function($){
					$(document).ready(function(){
						$('#ja-main-player').find('iframe.ja-video, video, .jp-video, .jp-jplayer').each(function(){
							var container = $('#ja-main-player');
							var width = container.outerWidth(true);
							var height = container.outerHeight(true);

							$(this).removeAttr('width').removeAttr('height');
							$(this).css({width: width, height: height});
						});
					});
				})(jQuery);
			</script>
		</div>

		<?php if (count($leading)): ?>
			<!-- Leading -->
			<?php
			$aparams->set('show_category', $aparams->get('show_leading_category', $show_category));
			$aparams->set('show_readmore', $aparams->get('show_leading_readmore', $show_readmore));
			$aparams->set('block_position', $aparams->get('leading_block_position', $block_position));
			?>
			<div class="videos-featured-grid <?php if($count_leading > 5) echo 'has-scroll'?>">
				<ul>
					<?php
					$i = 0;
					foreach ($leading as $item) : ?>
					<li class="video-item col-md-6 <?php if ($i++ == 0) echo 'active'; ?>" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
						<?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams, 'img-size' => 'medium')); ?>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- //Leading -->
		<?php endif ?>

	</div>
	<!-- //Left Column -->

	</div>