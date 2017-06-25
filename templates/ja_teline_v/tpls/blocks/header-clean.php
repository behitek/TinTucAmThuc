<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// get params
$sitename  = $this->params->get('sitename');
$slogan    = $this->params->get('slogan', '');
$logotype  = $this->params->get('logotype', 'text');
$logoimage = $logotype == 'image' ? $this->params->get('logoimage', T3Path::getUrl('images/logo.png', '', true)) : '';
$logoimgsm = ($logotype == 'image' && $this->params->get('enable_logoimage_sm', 0)) ? $this->params->get('logoimage_sm', T3Path::getUrl('images/logo-sm.png', '', true)) : false;
$logolink  = $this->params->get('logolink');

if (!$sitename) {
	$sitename = JFactory::getConfig()->get('sitename');
}

// get logo url
$logourl = JURI::base(true);
if ($logolink == 'page') {
	$logopageid = $this->params->get('logolink_id');
	$_item = JFactory::getApplication()->getMenu()->getItem ($logopageid);
	if ($_item) {
		$logourl = JRoute::_('index.php?Itemid=' . $logopageid);
	}
}

?>

<!-- HEADER -->
<header id="t3-header" class="t3-header">
	<div class="container">

		<div class="row">

			<div class="col-md-5 header-left">

				<!-- OFF CANVAS TOGGLE -->
				<?php $this->loadBlock ('off-canvas') ?>
				<!-- // OFF CANVAS TOGGLE -->

				<!-- LOGO -->
				<div class="logo">
					<div class="logo-<?php echo $logotype, ($logoimgsm ? ' logo-control' : '') ?>">
						<a href="<?php echo $logourl ?>" title="<?php echo strip_tags($sitename) ?>">
							<?php if($logotype == 'image'): ?>
								<img class="logo-img" src="<?php echo JURI::base(true) . '/' . $logoimage ?>" alt="<?php echo strip_tags($sitename) ?>" />
							<?php endif ?>
							<?php if($logoimgsm) : ?>
								<img class="logo-img-sm" src="<?php echo JURI::base(true) . '/' . $logoimgsm ?>" alt="<?php echo strip_tags($sitename) ?>" />
							<?php endif ?>
							<span><?php echo $sitename ?></span>
						</a>
						<small class="site-slogan"><?php echo $slogan ?></small>
					</div>
				</div>
				<!-- //LOGO -->

			</div>

			<!-- HEADER RIGHT -->
			<div class="col-md-7 header-right">
				<div class="header-right-inner header-banner">
				<jdoc:include type="modules" name="<?php $this->_p('header-banner') ?>" />
			</div>
			<!-- //HEADER RIGHT -->

		</div>

	</div>
</header>
<!-- //HEADER -->
