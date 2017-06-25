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
$params  		= $item->params;
$desc 			= '';

$gallery = $params->get('ctm_gallery');

if(is_array($gallery) && isset($gallery['src'])) {
	$thumbnail = $gallery['src'][0];
	$desc = $gallery['caption'][0];
}

if(!$thumbnail) {
	$images = json_decode($item->images);
	$thumbnail = @$images->image_intro;
}

$galleryId = 'ja-gallery-detail-'.$item->id;
?>

<?php if(is_array($gallery) && count($gallery['src']) > 1):
	?>

	<div id="<?php echo $galleryId; ?>" class="carousel carousel-thumbnail carousel-fade slide" data-ride="carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<?php $cnt = -1; ?>
			<?php foreach($gallery['src'] as $index => $src): $cnt++; ?>
				<li data-target="#<?php echo $galleryId; ?>" data-slide-to="<?php echo $cnt; ?>" class="<?php if($cnt == 0) echo 'active'; ?>" itemprop="thumbnail">
					<img style="max-width: 50px; max-height: 50px;" src="<?php echo htmlspecialchars(JUri::root(true).'/'.$src); ?>" alt="<?php echo htmlspecialchars($gallery['caption'][$index]); ?>" itemprop="thumbnailUrl"/>
				</li>
			<?php endforeach; ?>
		</ol>

		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox">
			<?php $cnt = -1; ?>
			<?php foreach($gallery['src'] as $index => $src): $cnt++; ?>
			<div class="item <?php if($index == 0) echo 'active'; ?>">
				<div class="item-image" itemprop="image">
					<img src="<?php echo htmlspecialchars(JUri::root(true).'/'.$src); ?>" alt="<?php echo htmlspecialchars($gallery['caption'][$index]); ?>" itemprop="thumbnailUrl"/>
				</div>
				<div class="carousel-caption" itemprop="caption">
					<?php echo $gallery['caption'][$index]; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>

		<!-- Controls -->
		<a class="left carousel-control" href="#<?php echo $galleryId; ?>" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#<?php echo $galleryId; ?>" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
		<?php echo JLayoutHelper::render('joomla.content.image.gallery', array('item' => $item, 'context' => 'icon')); ?>
	</div>

<?php elseif (isset($thumbnail) && !empty($thumbnail)) : ?>
	<div class="item-image">
		<img
			<?php if ($desc):
				echo 'class="caption"' . ' title="' . htmlspecialchars($desc) . '"';
			endif; ?>
			src="<?php echo htmlspecialchars($thumbnail); ?>" alt="<?php echo htmlspecialchars($item->title); ?>" itemprop="thumbnailUrl"/>
	</div>
<?php endif; ?>
