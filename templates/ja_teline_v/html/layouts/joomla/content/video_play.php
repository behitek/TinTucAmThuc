<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$item 		= $displayData['item'];
$context 	= $displayData['context'];
$params  	= $item->params;

$ctm_source 	= $params->get('ctm_source', 'youtube');
$width 			= (int) $params->get('ctm_width', 640);
$height		 	= (int) $params->get('ctm_height', 360);

if($context == 'iframe') {
	//update to get full with, height

	JHtml::_('jquery.framework');
	$doc = JFactory::getDocument();

	$css = '
	body { overflow: hidden !important; }
	.window-mainbody, .row, .col, .article-main {
		margin:0 !important;
		padding:0 !important;
	}
	';
	$doc->addStyleDeclaration($css);
}


if($ctm_source == 'youtube') {
	$url = $params->get('ctm_embed_url', '');
	if ($url) {
		if(preg_match('#.*?/watch\?v\=([^&]+).*#i', $url)){
			$vid = preg_replace('#.*?/watch\?v\=([^&]+).*#i', '$1', $url);
		} elseif (preg_match('#^([a-z]+)?\://(www\.)?(youtu\.be|youtube\.com)/([^&]+)#i', $url)) {
			$vid = preg_replace('#^([a-z]+)?\://(www\.)?(youtu\.be|youtube\.com)/([^&]+)#i', '$4', $url);
		} else {
			$vid = $url;
		}
		$url = '//www.youtube.com/embed/'.$vid;
		if($context == 'list') {
			$url .= (strpos($url, '?') === false ? '?' : '&amp;').'autoplay=1';
		}
		echo '<iframe class="ja-video" width="'.$width.'" height="'.$height.'" src="'.$url.'" frameborder="0" allowfullscreen></iframe>';
	}
} elseif($ctm_source == 'vimeo') {
	$url = $params->get('ctm_embed_url', '');
	if ($url) {
		if(preg_match('#^([a-z]+)?\://.*?/([0-9]+)$#i', $url)) {
			$vid = preg_replace('#^([a-z]+)?\://.*?/([0-9]+)$#i', '$2', $url);
		} else {
			$vid = $url;
		}
		$url = '//player.vimeo.com/video/'.$vid;
		if($context == 'list') {
			$url .= (strpos($url, '?') === false ? '?' : '&amp;').'autoplay=true';
		}
		echo '<iframe class="ja-video" width="'.$width.'" height="'.$height.'" src="'.$url.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
	}
} elseif($ctm_source == 'local') {
	$src = $params->get('ctm_local_src');


	if($src) {
		if($context == 'list' || $context == 'featured'):
			$url = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid));
			$url .= (strpos($url, '?') === false ? '?' : '&amp;').'layout=videoplayer&amp;tmpl=component';
			if($context == 'list') {
				$url .= '&amp;autoplay=1';
			}
			echo '<iframe class="ja-video" width="'.$width.'" height="'.$height.'" src="'.$url.'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		else:
			JHtml::_('jquery.framework');

			$doc = JFactory::getDocument();
			$path = JUri::root(true).'/plugins/system/jacontenttype/asset/jplayer/';
			$doc->addScript($path.'jquery.jplayer.min.js');
			$doc->addStyleSheet($path.'skin/ja.skin/jplayer.ja.skin.css');

			jimport('joomla.filesystem.file');
			$ext = JFile::getExt($src);
			$src = JUri::root().$src;

			$thumbnail = $params->get('ctm_thumbnail');

			$poster = '';
			if($thumbnail) {
				$thumbnail = JUri::root().$thumbnail;
				$poster = 'poster: "'.$thumbnail.'"';
			}
			$autoplay = '';
			if(isset($item->autoplay) && $item->autoplay) {
				$autoplay = '.jPlayer("play")';
			}
		?>

		<script type="text/javascript">
			//<![CDATA[
			(function($){
				$(document).ready(function(){
					$("#jquery_jplayer_1").jPlayer({
						ready: function () {
							$(this).jPlayer("setMedia", {
								title: "<?php echo htmlspecialchars($item->title); ?>",
								"<?php echo $ext; ?>": "<?php echo $src; ?>",
								<?php echo $poster; ?>
							})<?php echo $autoplay; ?>;
						},
						play: function() {
							$(window).resize();
							$(".jp-jplayer").not(this).jPlayer("stop");
						},

						swfPath: "<?php echo $path.'jquery.jplayer.swf'; ?>",
						supplied: "<?php echo $ext; ?>",
						size: {
							width: "<?php echo $width.'px'; ?>",
							height: "<?php echo $height.'px'; ?>",
							cssClass: ""
						},
						useStateClassSkin: true,
						autoBlur: false,
						smoothPlayBar: true,
						keyEnabled: true,
						remainingDuration: true,
						toggleDuration: true
					});
				});
			})(jQuery);
			//]]>
		</script>
		<div id="jp_container_1" class="jp-video clearfix" role="application" aria-label="media player" style="width: <?php echo $width+2; ?>px;">
			<div class="jp-type-single">
				<div id="jquery_jplayer_1" class="jp-jplayer"></div>

				<div class="jp-gui jp-interface">
            <ul class="jp-controls">
                <li><a href="javascript:;" class="jp-play" tabindex="0">play</a></li>
                <li><a href="javascript:;" class="jp-pause" tabindex="0">pause</a></li>
                <li><a href="javascript:;" class="jp-stop" tabindex="0">stop</a></li>
                <li><a href="javascript:;" class="jp-mute" tabindex="0" title="mute">mute</a></li>
                <li><a href="javascript:;" class="jp-unmute" tabindex="0" title="unmute">unmute</a></li>
                <li><a href="javascript:;" class="jp-volume-max" tabindex="0" title="max volume">max volume</a></li>
            </ul>
            <div class="jp-progress">
                <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                </div>
            </div>
            <div class="jp-volume-bar">
                <div class="jp-volume-bar-value"></div>
            </div>
            <div class="jp-time-holder">
                <div class="jp-current-time"></div>
                <div class="jp-duration"></div>

                <ul class="jp-toggles">
                    <li><a href="javascript:;" class="jp-repeat" tabindex="0" title="repeat">repeat</a></li>
                    <li><a href="javascript:;" class="jp-repeat-off" tabindex="0" title="repeat off">repeat off</a></li>
                </ul>
            </div>
        </div>
        <?php if($context !== 'iframe'): ?>
        <div class="jp-details">
					<div class="jp-title" aria-label="title">&nbsp;</div>
				</div>
        <?php endif; ?>
        <div class="jp-no-solution">
            <span>Update Required</span>
            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
        </div>
			</div>
		</div>
		<?php endif; ?>
		<?php
	}
}

?>

<?php if($context == 'iframe'): ?>
	<script type="text/javascript">

		(function($){
			$(document).ready(function(){
				$(window).resize();
			});
			$(window).resize(function() {
				var container = $(window);
				var width = container.width();
				var height = container.height();

				$('iframe.ja-video, video').removeAttr('width').removeAttr('height');
				$('iframe.ja-video, video, .jp-video').css({width: width, height: height});
				$('.jp-jplayer, #jp_poster_0').css({width: width, height: height - $('.jp-interface').outerHeight(true)});
			});
		})(jQuery);
	</script>
<?php endif; ?>
