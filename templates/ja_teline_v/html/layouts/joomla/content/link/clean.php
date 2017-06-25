<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

$item = $displayData['item'];
$aparams = $displayData['params'];
$aparams->set('show_publish_date', 1);
$aparams->set('show_category', 1);
?>
<div class="link-item">

	<aside class="article-aside">
		<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_category', array('item' => $item, 'params' => $aparams, 'position'=>'above')); ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.magazine_block', array('item' => $item, 'params' => $aparams, 'position' => 'above')); ?>
	</aside>

	<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $item); ?>

</div>