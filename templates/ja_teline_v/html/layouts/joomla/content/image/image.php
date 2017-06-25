<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$link = '';
if (isset($displayData['item'])) {
  $item = $displayData['item'];
  $itemparams = $item->params;
  if($itemparams->get('access-view')){
    $link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid));  
  }
  
}


$params  = new JRegistry($displayData);
$image = $params->get('image');
$alt = $params->get('alt');
$caption = $params->get('caption');
$captionText = $params->get('caption');
$size = $params->get('size');

require_once (JPATH_ROOT . '/plugins/system/jacontenttype/helpers/image.php');

$img = JAContentTypeImageHelper::getImage($image, $size);
if ($caption) $caption = 'class="caption"' . ' title="' . htmlspecialchars($caption) . '"';
?>

<?php if ($img) : ?>
  <?php if ($link) : ?>
    <a href="<?php echo $link; ?>" title="<?php echo htmlspecialchars($caption); ?>">
  <?php endif ?>
    <span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
	   <img <?php echo $caption ?>	src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($alt); ?>" itemprop="url"/>
     <meta itemprop="height" content="auto" />
     <meta itemprop="width" content="auto" />
    </span>
  <?php if ($link) : ?>
    </a>
  <?php endif ?>
  
  <?php if($captionText) : ?>
  <p class="img-caption"><?php echo $captionText ?></p>
  <?php endif ?>
<?php endif; ?>
