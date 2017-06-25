<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$lang = JFactory::getLanguage();
$menutype = $this->getParam('mm_type', 'mainmenu');

$menu = JFactory::getApplication()->getMenu();

if ($lang->getDefault() != $lang->getTag()) {
	$menutype_gost = $menutype.'-'.strtolower($lang->getTag()); 
	$mnitems = $menu->getItems (array('menutype'), array($menutype_gost));

	if (count($mnitems)) {
		$menutype = $menutype_gost;
	} 
}

$mnitems = $menu->getItems (array('menutype'), array($menutype));
?>

<!-- MAIN NAVIGATION -->
<nav id="t3-mainnav" class="wrap navbar navbar-default t3-mainnav">
	<div class="container">

		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<?php if ($this->getParam('navigation_collapse_enable', 1) && $this->getParam('responsive', 1)) : ?>
				<?php $this->addScript(T3_URL.'/js/nav-collapse.js'); ?>
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".t3-navbar-collapse">
					<i class="fa fa-bars"></i>
				</button>
			<?php endif ?>
		</div>

		<?php if ($this->getParam('navigation_collapse_enable')) : ?>
			<div class="t3-navbar-collapse navbar-collapse collapse"></div>
		<?php endif ?>

		<div class="t3-navbar navbar-collapse collapse">
			<jdoc:include type="<?php echo $this->getParam('navigation_type', 'megamenu') ?>" name="<?php echo $menutype ?>" />
		</div>

	</div>
</nav>

<?php
/* Teak mainnav to add category class to mainmenu item */
	$mnclasses = array();
	foreach ($mnitems as $mnitem) {
		$query = new JRegistry ($mnitem->query);
		if ($query->get ('option') == 'com_content' && $query->get ('view') == 'category') {
			$catid = $query->get ('id');
			$class = JATemplateHelper::getCategoryClass($catid, false);
			if ($class) {
				$mnclass = new stdClass();
				$mnclass->id = $mnitem->id;
				$mnclass->class = $class;
				$mnclasses[] = $mnclass;
			}
		}
	}
?>
<script>
	(function ($){
		var maps = <?php echo json_encode($mnclasses) ?>;
		$(maps).each (function (){
			$('li[data-id="' + this['id'] + '"]').addClass (this['class']);
		});
	})(jQuery);
</script>
<!-- //MAIN NAVIGATION -->
