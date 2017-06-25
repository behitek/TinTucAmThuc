<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_category
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$doc->addStyleSheet (T3_TEMPLATE_URL . '/css/mod_articles_category.css');

if(isset($item_heading) || $item_heading=='') $item_heading = 4;
?>
<div class="section-inner <?php echo $params->get('moduleclass_sfx'); ?>">

    <div class="category-module<?php echo $moduleclass_sfx; ?> magazine-links">
        <?php foreach ($list as $item) : ?>
            <?php echo JLayoutHelper::render('joomla.content.link.default', array('item' => $item, 'params' => $params)); ?>
        <?php endforeach; ?>
    </div>
</div>