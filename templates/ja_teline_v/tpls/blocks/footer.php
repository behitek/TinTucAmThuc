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
$logotype  = $this->params->get('logofootertype', 'text');
$logoimage = $logotype == 'image' ? $this->params->get('logofooterimage', T3Path::getUrl('images/logo.png', '', true)) : '';
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

<!-- FOOTER -->
<footer id="t3-footer" class="wrap t3-footer">
  <div class="container">

  <section class="t3-footer-links">
    <div class="row">

      <div class="col-md-4">
        <!-- LOGO -->
        <div class="logo">
          <div class="logo-<?php echo $logotype, ('' ? ' logo-control' : '') ?>">
            <a href="<?php echo $logourl ?>" title="<?php echo strip_tags($sitename) ?>">
              <?php if($logotype == 'image'): ?>
                <img class="logo-img" src="<?php echo JURI::base(true) . '/' . $logoimage ?>" alt="<?php echo strip_tags($sitename) ?>" />
              <?php endif ?>
              <span><?php echo $sitename ?></span>
            </a>
            <small class="site-slogan"><?php echo $slogan ?></small>
          </div>
        </div>
        <!-- //LOGO -->

        <!-- NEWSLETTER -->
        <div class="acy-email-footer">
            <jdoc:include type="modules" name="acy-email-footer" />
        </div>
        <!-- //NEWSLETTER -->
      </div>

      <div class="col-md-8">
      	<?php if ($this->checkSpotlight('footnav', 'footer-1, footer-2, footer-3, footer-4')) : ?>
      	<!-- FOOT NAVIGATION -->
      		<?php $this->spotlight('footnav', 'footer-1, footer-2, footer-3, footer-4') ?>
      	<!-- //FOOT NAVIGATION -->
      	<?php endif ?>
          <div class="footer-banner">
              <jdoc:include type="modules" name="<?php $this->_p('footer-banner') ?>" />
          </div>
      </div>

    </div>
  </section>

  <section class="t3-copyright">
  	<div class="row">
  		<div class="<?php echo $this->getParam('t3-rmvlogo', 1) ? 'col-md-8' : 'col-md-12' ?> copyright <?php $this->_c('footer') ?>">
  			<jdoc:include type="modules" name="<?php $this->_p('footer') ?>" />
        <small>Copyright &copy; 2015 Joomla!. All Rights Reserved. Powered by <a href="<?php echo JURI::base(true) ?>" title="<?php echo strip_tags($sitename) ?>" rel="nofollow"><?php echo strip_tags($sitename) ?></a> - Designed by JoomlArt.com.</small>
        <small>
          <a href="http://twitter.github.io/bootstrap/" target="_blank">Bootstrap</a> is a front-end framework of Twitter, Inc. Code licensed under <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License v2.0</a>.
        </small>
        <small>
          <a href="http://fortawesome.github.io/Font-Awesome/" target="_blank">Font Awesome</a> font licensed under <a href="http://scripts.sil.org/OFL">SIL OFL 1.1</a>.
        </small>
  		</div>
  		<?php if ($this->getParam('t3-rmvlogo', 1)): ?>
  			<div class="col-md-4 poweredby text-hide">
  				<a class="t3-logo t3-logo-light" href="http://t3-framework.org" title="<?php echo JText::_('T3_POWER_BY_TEXT') ?>"
  				   target="_blank" <?php echo method_exists('T3', 'isHome') && T3::isHome() ? '' : 'rel="nofollow"' ?>><?php echo JText::_('T3_POWER_BY_HTML') ?></a>
  			</div>
  		<?php endif; ?>
  	</div>
  </section>

  </div>
</footer>
<!-- //FOOTER -->