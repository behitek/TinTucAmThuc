<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- META FOR IOS & HANDHELD -->
<?php if ($this->getParam('responsive', 1)): ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<style type="text/stylesheet">
		@-webkit-viewport   { width: device-width; }
		@-moz-viewport      { width: device-width; }
		@-ms-viewport       { width: device-width; }
		@-o-viewport        { width: device-width; }
		@viewport           { width: device-width; }
	</style>
	<script type="text/javascript">
		//<![CDATA[
		if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
			var msViewportStyle = document.createElement("style");
			msViewportStyle.appendChild(
				document.createTextNode("@-ms-viewport{width:auto!important}")
			);
			document.getElementsByTagName("head")[0].appendChild(msViewportStyle);
		}
		//]]>
	</script>
<?php endif ?>
<meta name="HandheldFriendly" content="true"/>
<meta name="apple-mobile-web-app-capable" content="YES"/>
<!-- //META FOR IOS & HANDHELD -->

<?php
// SYSTEM CSS
$this->addStyleSheet(JURI::base(true) . '/templates/system/css/system.css');
?>

<?php
// T3 BASE HEAD
$this->addHead();
$this->addScriptDeclaration('
	var ja_base_uri = "'.JURI::base(true).'";
');
?>

<!-- GOOGLE FONTS -->
<link href='http://fonts.googleapis.com/css?family=Roboto:400,400italic,300,300italic,700,700italic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,300,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:700,400' rel='stylesheet' type='text/css'>
<!--//GOOGLE FONTS -->

<?php
// CUSTOM CSS
if (is_file(T3_TEMPLATE_PATH . '/css/custom.css')) {
	$this->addStyleSheet(T3_TEMPLATE_URL . '/css/custom.css');
}
?>

<!-- Le HTML5 shim and media query for IE8 support -->
<!--[if lt IE 9]>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script type="text/javascript" src="<?php echo T3_URL ?>/js/respond.min.js"></script>
<![endif]-->

<!-- You can add Google Analytics here or use T3 Injection feature -->
<?php
// Add category class to body
$active = JFactory::getApplication()->getMenu()->getActive();
if ($active && isset($active->query)) {
	$qparams = new JRegistry ($active->query);
	if ($qparams->get('option') == 'com_content' && $qparams->get('view') == 'category') {
		$this->addPageClass(JATemplateHelper::getCategoryClass($qparams->get('id', 1)));
	}
}
?>
