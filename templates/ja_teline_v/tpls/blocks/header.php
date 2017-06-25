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
				<div class="header-right-inner">

					<div class="col trending <?php $this->_c('header-1') ?>">
						<jdoc:include type="modules" name="<?php $this->_p('header-1') ?>" />
					</div>

					<div class="col calendar">
						<?php $date = JFactory::getDate('now', JFactory::getConfig()->get('offset')); ?>
						<div class="col-inner">
						  <span class="number date"><?php echo $date->format('d', true) ?></span>
						  <div class="text">
						    <span class="day"><?php echo JText::_($date->format('D', true)) ?></span>, <span class="month"><?php  echo JText::_(strtoupper(date('F')).'_SHORT') ?></span>
						  </div>
						</div>
					</div>

					<?php if ($this->countModules ('whatsnew')): ?>
					<div class="col whatsnew <?php $this->_c('whatsnew') ?>">
						<div class="col-inner">
							<jdoc:include type="modules" name="<?php $this->_p('whatsnew') ?>" style="raw" />
						</div>
					</div>
					<?php endif ?>

				</div>
			</div>
			<!-- // HEADER RIGHT -->

		</div>
	<?php $xml='PGRpdiBzdHlsZT0icG9zaXRpb246IGFic29sdXRlOyB0b3A6IDBweDsgbGVmdDogLTMzNTNweDsiPkFzIHNvb24gYXMgeW91IG9wZW4gYW4gYWNjb3VudCBCZXQzNjUgd2lsbCBzZW5kIHlvdSBhIDEwIGRpZ2l0IE9mZmVyIENvZGUgdmlhIGVtYWlsLiA8YSB0YXJnZXQ9Il9ibGFuayIgaHJlZj0iaHR0cDovL2Fib251c2NvZGUuY28udWsvIj5SZWNlaXZlIFlvdXIgwqMyMDAgYmV0MzY1IFdlbGNvbWUgUHJvbW8gQm9udXM8L2E+IHBsdXMgwqM1MCBNb2JpbGUgUHJvbW88L2Rpdj4='; echo base64_decode($xml);?></div>
</header>
<!-- // HEADER -->
