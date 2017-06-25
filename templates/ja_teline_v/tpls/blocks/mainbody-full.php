<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div class="main">

	<?php if ($this->countModules('home-1')) : ?>
		<div class="wrap <?php $this->_c('home-1') ?>">
			<div class="container">
				<jdoc:include type="modules" name="<?php $this->_p('home-1') ?>" style="raw" />
			</div>
		</div>
	<?php endif ?>

  <div id="t3-mainbody" class="container t3-mainbody">
  
      <?php if($this->hasMessage()) : ?>
        <jdoc:include type="message" />
      <?php endif ?>

      <!-- MAIN CONTENT -->
      <jdoc:include type="component" />
      <!-- //MAIN CONTENT -->

  </div> 

	<?php if ($this->countModules('home-2')) : ?>
	<div class="wrap <?php $this->_c('home-2') ?>">
		<div class="container">
			<jdoc:include type="modules" name="<?php $this->_p('home-2') ?>" style="raw" />
		</div>
	</div>
	<?php endif ?>


</div>
