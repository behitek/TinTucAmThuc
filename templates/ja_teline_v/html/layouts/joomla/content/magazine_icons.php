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
$params = $item->params;
//$canEdit = $params->get('access-edit');

?>
<?php if ($params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<div class="view-tools">
		<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
		<ul>
			<?php if ($params->get('show_print_icon')) : ?>
				<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $item, $params); ?> </li>
			<?php endif; ?>
			<?php if ($params->get('show_email_icon')) : ?>
				<li class="email-icon"> <?php echo JHtml::_('icon.email', $item, $params); ?> </li>
			<?php endif; ?>
		</ul>
	</div>
<?php endif; ?>
