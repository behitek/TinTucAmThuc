<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->countModules('topbar-l') || $this->countModules('topbar-r')) : ?>
	<!-- TOPBAR -->
	<div class="t3-topbar">

		<div class="top-left">
			<nav class="t3-topnav">
				<jdoc:include type="modules" name="<?php $this->_p('topbar-l') ?>" style="raw"/>
			</nav>
		</div>

		<div class="top-right">
			<jdoc:include type="modules" name="<?php $this->_p('topbar-r') ?>" style="raw"/>
		</div>

	</div>

	<?php
	// Make topnav item active base on style
	if ($this->params->get('logolink') == 'page') :
		$logopageid = $this->params->get('logolink_id');
		?>
		<script>
			(function ($) {
				$('.t3-topnav li.item-<?php echo $logopageid ?>').addClass('active');
			})(jQuery);
		</script>
	<?php endif ?>
	<!-- //TOP BAR -->
<?php endif ?>
