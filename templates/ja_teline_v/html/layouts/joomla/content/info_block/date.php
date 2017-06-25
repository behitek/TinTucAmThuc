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
$params = $displayData['params'];
$date_field = $params->get ('show_date_field');
$date = $item->$date_field;
?>
			<dd class="published">
				<i class="icon-calendar"></i>
				<time datetime="<?php echo JHtml::_('date', $date, 'c'); ?>" itemprop="datePublished">
					<?php echo $item->displayDate ?>
				</time>
			</dd>