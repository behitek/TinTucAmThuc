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

$items = JATemplateHelper::getArticles($aparams, $catids, $aparams->get('count', 4));
?>

<div class="magazine-links">

	<div class="carousel slide carousel-fade" data-ride="carousel" data-interval="5000">

		<!-- Wrapper for slides -->
		<div class="carousel-inner" role="listbox">
			<?php foreach ($items as $index => $item): ?>
			<div class="item <?php if($index == 0) echo 'active'; ?>">
				<?php echo JATemplateHelper::render($item, 'joomla.content.link', array('item' => $item, 'params' => $aparams)); ?>
			</div>
			<?php endforeach ?>
		</div>
	</div>
</div>
