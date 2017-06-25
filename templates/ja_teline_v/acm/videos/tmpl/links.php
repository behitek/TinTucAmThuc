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

// get news
$catids = $aparams->get('list_categories');
$aparams->set('show_intro',0);

$items = JATemplateHelper::getArticles($aparams, $catids, $aparams->get('count', 4));
?>

<div class="magazine-links video-links" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
	<?php foreach ($items as $item): ?>
    <div class="video-item">
      <?php echo JATemplateHelper::render($item, 'joomla.content.intro', array('item' => $item, 'params' => $aparams, 'img-size' => 'small')); ?>
    </div>
	<?php endforeach ?>
</div>