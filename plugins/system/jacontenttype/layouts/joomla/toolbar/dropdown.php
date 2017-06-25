<?php
/**
 * ------------------------------------------------------------------------
 * Plugin JA Content Type
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

$doTask   = $displayData['doTask'];
$class    = $displayData['class'];
$text     = $displayData['text'];
$btnClass = $displayData['btnClass'];
$items    = $displayData['items'];

?>
<div class="btn-group">

	<button class="<?php echo $btnClass; ?> dropdown-toggle" data-toggle="dropdown">
		<span class="<?php echo trim($class); ?>"></span>
		<?php echo $text; ?>
		<span class="caret"></span>
	</button>
	<ul class="dropdown-menu">
		<?php foreach($items as $item): ?>
			<?php if(isset($item['link']) && !empty($item['link'])): ?>
				<li>
					<a href="<?php echo $item['link']; ?>">
						<?php if(isset($item['icon'])): ?>
							<span class="icon icon-<?php echo $item['icon']; ?>"></span>
						<?php endif; ?>
						<?php echo $item['title']; ?>
					</a>
				</li>
			<?php else: ?>
				<li>
					<a href="#" onclick="<?php echo $doTask; ?>">
						<?php if(isset($item['icon'])): ?>
							<span class="icon icon-<?php echo $item['icon']; ?>"></span>
						<?php endif; ?>
						<?php echo $item['title']; ?>
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>