<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$item  			= $displayData['item'];
$context		= isset($displayData['context']) ? $displayData['context'] : '';
$params  		= $item->params;
$desc 			= '';

$gallery = $params->get('ctm_gallery');
if(is_array($gallery) && isset($gallery['src'])) {
	$thumbnail = $gallery['src'][0];
	$desc = $gallery['caption'][0];

	if(count($gallery['src']) > 1) {
		if(!defined('TELINE_GALLERY_LIST_PLAY')) {
			define('TELINE_GALLERY_LIST_PLAY', 1);
			$doc = JFactory::getDocument();
			JHtml::_('jquery.framework');
			$path = JUri::root(true).'/templates/ja_teline_v/js/gallery/';
			$doc->addStyleSheet($path.'blueimp-gallery.min.css');
			$doc->addStyleSheet($path.'bootstrap-image-gallery.css');
			$doc->addScript($path.'jquery.blueimp-gallery.min.js');
			$doc->addScript($path.'bootstrap-image-gallery.min.js');

			$galleryMarkup = JLayoutHelper::render('blueimp.gallery');
			$galleryMarkup = preg_replace('/[\r\n]+/', '', $galleryMarkup);
			$script = "
			(function ($) {
				$(document).ready(function(){
					$('body').append('".addslashes($galleryMarkup)."');
				});
			})(jQuery);
			";
			$doc->addScriptDeclaration($script);
		}
	}
}
if(!$thumbnail) {
	$images = json_decode($item->images);
	$thumbnail = @$images->image_intro;
}
if($context == 'icon') {
	$galleryId = 'ja-gallery-icon-'.$item->id;
} else {
	$galleryId = 'ja-gallery-list-'.$item->id;
}

// data to show image
$data = array();
if (is_array($displayData) && isset($displayData['img-size'])) $data['size'] = $displayData['img-size'];

if (isset($thumbnail) && !empty($thumbnail)) {
	$data['image'] = $thumbnail;
	$data['alt'] = $item->title;
	$data['caption'] = $desc;	
}
?>
<?php if($context == 'icon'): ?>
	<?php if (isset($thumbnail) && !empty($thumbnail)) : ?>
		<div id="<?php echo $galleryId; ?>" class="btn-fullscreen">
			<span class="fa fa-expand fa-2x" title="<?php echo htmlspecialchars(JText::_('TPL_VIEW_FULL_SCREEN')); ?>"></span>
		</div>
		<?php
			$images = array();
			foreach($gallery['src'] as $index => $src) {
				$img = new stdClass();
				$img->href = JUri::root(true).'/'.$src;
				$img->title = $gallery['caption'][$index];
				$images[] = $img;
			}
			?>
			<script type="text/javascript">
				(function ($) {
					$(document).ready(function(){
						$('#<?php echo $galleryId; ?>').on('click', function (event) {
							event.preventDefault();
							blueimp.Gallery(<?php echo json_encode($images); ?>, {
								transitionSpeed: 0,
								hidePageScrollbars:false
							});
						});
					});
				})(jQuery);
			</script>
	<?php endif; ?>
<?php else: ?>
	<?php if (isset($thumbnail) && !empty($thumbnail)) : ?>
		<div id="<?php echo $galleryId; ?>" class="item-image ja-gallery-list">
			<?php if(is_array($gallery) && count($gallery['src']) > 1): ?>
			<span class="btn-play">
				<i class="fa fa-play"></i>
				<span class="slideshow-text"><?php echo JText::_('TPL_VIEW_SLIDE_SHOW'); ?></span>
				<span class="num-photos"> <?php echo JText::sprintf('TPL_NUMBER_PHOTOS_OF_SLIDESHOW', count($gallery['src'])); ?></span>
			</span>
			<span class="gallery-mask"></span>
			<?php endif; ?>
			<?php echo JLayoutHelper::render('joomla.content.image.image', $data); ?>
		</div>
		<?php if(is_array($gallery) && count($gallery['src']) > 1):
			$images = array();
			foreach($gallery['src'] as $index => $src) {
				$img = new stdClass();
				$img->href = JUri::root(true).'/'.$src;
				$img->title = $gallery['caption'][$index];
				$images[] = $img;
			}
			?>
			<script type="text/javascript">
				(function ($) {
					$(document).ready(function(){
						$('#<?php echo $galleryId; ?> .btn-play').on('click', function (event) {
							event.preventDefault();
							blueimp.Gallery(<?php echo json_encode($images); ?>, {
								transitionSpeed: 0,
								hidePageScrollbars:false
							});
						});
					});
				})(jQuery);
			</script>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
