<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$sidebar = 'sidebar';
$hasSidebar = $this->countModules ($sidebar);
$mainwidth = $hasSidebar ? ' col-md-9' : '';
?>

<div class="main">

	<?php if ($this->countModules('home-1')) : ?>
		<div class="wrap <?php $this->_c('home-1') ?>">
			<div class="container">
				<jdoc:include type="modules" name="<?php $this->_p('home-1') ?>" style="raw" />
			</div>
		</div>
	<?php endif ?>

  <div id="t3-mainbody" class="container t3-mainbody mainbody-magazine">
  
  	<div class="row equal-height">

			<!-- MAIN CONTENT -->
			<div id="t3-content" class="col t3-content<?php echo $mainwidth ?>">
				<?php if($this->hasMessage()) : ?>
				<jdoc:include type="message" />
				<?php endif ?>
				<jdoc:include type="component" />
			</div>
			<!-- //MAIN CONTENT -->

			<?php if ($hasSidebar) : ?>
			<!-- SIDEBAR RIGHT -->
			<div class="col t3-sidebar t3-sidebar-right col-md-3 <?php $this->_c($sidebar) ?>">
				<jdoc:include type="modules" name="<?php $this->_p($sidebar) ?>" style="T3Xhtml" />
			</div>
			<!-- //SIDEBAR RIGHT -->
			<?php endif ?>

			</div>

  </div> 

	<?php if ($this->countModules('home-2')) : ?>
	<div class="wrap <?php $this->_c('home-2') ?>">
		<div class="container">
			<jdoc:include type="modules" name="<?php $this->_p('home-2') ?>" style="raw" />
		</div>
	</div>
	<?php endif ?>


</div>
