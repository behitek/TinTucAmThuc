<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$item  		= $displayData['item'];
$params  		= $item->params;
$thumbnail 		= $params->get('ctm_thumbnail');
$desc 			= $params->get('ctm_description');

$ctm_source 	= $params->get('ctm_source', 'youtube');

if(!$thumbnail) {
	$images = json_decode($item->images);
	$thumbnail = @$images->image_intro;
}

$data = array();
if (is_array($displayData) && isset($displayData['img-size'])) $data['size'] = $displayData['img-size'];

if(!defined('TELINE_VIDEO_LIST_PLAY')) {
	define('TELINE_VIDEO_LIST_PLAY', 1);

	JHtml::_('jquery.framework');
	$doc = JFactory::getDocument();
	$script = "
	var JAVideoPlayer = {};
	(function($){
		$(document).ready(function(){
			JAVideoPlayer.playlist = function() {
				$('.ja-video-list').each(function(){
					var container = $(this);

					var btnPlay = container;
					if(container.find('.btn-play').length) {
						btnPlay = container.find('.btn-play');
					}
					btnPlay.click(function(){
						var width = container.outerWidth(true);
						var height = container.outerHeight(true);

						if(container.data('video')) {
							var mainPlayer = $('#ja-main-player');
							if(!mainPlayer.length) {
								video = container.find('.video-wrapper');
								clearContent = true;
							} else {
								video = mainPlayer;
								var width = video.width();
								var height = video.height();
								var clearContent = false;

								if(container.data('url') && typeof(window.history.pushState) == 'function') {
									window.history.pushState('string', container.data('title'), container.data('url'));
								}
							}

							if(video.length) {
								$('.ja-video-list').removeClass('video-playing');
								container.addClass('video-playing');
								video.html(container.data('video'));
								video.find('iframe.ja-video, video').removeAttr('width').removeAttr('height');
								video.find('iframe.ja-video, video, .jp-video, .jp-jplayer').css({width: width, height: height});
								video.show();
								if(clearContent) {
									container.data('video', '');
								}
								if(mainPlayer.length) {
									setTimeout(function(){
										$('html, body').animate({
											scrollTop: mainPlayer.offset().top
										}, 200);
									}, 500);
								}
							}
						}
					});
				});
			}

			JAVideoPlayer.playlist();
		});
	})(jQuery);
	";
	$doc->addScriptDeclaration($script);
}

if (isset($thumbnail) && !empty($thumbnail)) {
	$data['image'] = $thumbnail;
	$data['alt'] = $item->title;
	$data['caption'] = $desc;	
}
?>
<?php if (isset($thumbnail) && !empty($thumbnail)) : ?>
	<div class="item-image ja-video-list"
		 data-url="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid)); ?>"
		 data-title="<?php echo htmlspecialchars($item->title); ?>"
		 data-video="<?php echo htmlspecialchars(JLayoutHelper::render('joomla.content.video_play', array('item' => $item, 'context' => 'list'))); ?>">
		<span class="btn-play">
			<i class="fa fa-play-circle-o"></i>
		</span>
		<span class="video-mask"></span>
		<?php echo JLayoutHelper::render('joomla.content.image.image', $data); ?>
		<div class="video-wrapper">
		</div>
	</div>
<?php endif; ?>
