<?php
/**
 * ------------------------------------------------------------------------
 * JA Sugite Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('stylesheet', 'mod_languages/template.css', array(), true);

$app	   = JFactory::getApplication();
$tplparams = $app->getTemplate(true)->params;
$menu 	   = $app->getMenu();
$active    = $menu->getActive();

if(!$active){
	$active = $menu->getDefault();
}

if($tplparams->get('tpl_userpage_mid') == $active->id){

	// Load associations
	$assoc = isset($app->item_associations) ? $app->item_associations : 0;
	if ($assoc)
	{
		// load component associations
		$option = $app->input->get('option');
		$eName = JString::ucfirst(JString::str_ireplace('com_', '', $option));
		$cName = JString::ucfirst($eName.'HelperAssociation');
		JLoader::register($cName, JPath::clean(JPATH_COMPONENT_SITE . '/helpers/association.php'));

		if (class_exists($cName) && is_callable(array($cName, 'getAssociations')))
		{
			$cassociations = call_user_func(array($cName, 'getAssociations'));
		}

		if(!empty($cassociations)){
			$associations = MenusHelper::getAssociations($active->id);

			foreach ($list as $language) {
				if(!$language->active && $cassociations[$language->lang_code] && $associations[$language->lang_code]){

					$language->link = JRoute::_(preg_replace('@Itemid=(\d+)@', 'Itemid=' . $associations[$language->lang_code], $cassociations[$language->lang_code].'&lang='.$language->sef));
				}
			}
		}
	}
}
?>

<div class="dropdown mod-languages">
<?php if ($headerText) : ?>
	<div class="pretext"><p><?php echo $headerText; ?></p></div>
<?php endif; ?>

<?php if ($params->get('dropdown', 1)) : ?>
	<form name="lang" method="post" action="<?php echo htmlspecialchars(JURI::current()); ?>">
	<select class="inputbox" onchange="document.location.replace(this.value);" >
	<?php foreach($list as $language):?>
		<option dir=<?php echo JLanguage::getInstance($language->lang_code)->isRTL() ? '"rtl"' : '"ltr"'?> value="<?php echo $language->link;?>" <?php echo $language->active ? 'selected="selected"' : ''?>>
		<?php echo $language->title_native;?></option>
	<?php endforeach; ?>
	</select>
	</form>
<?php else : ?>
	<a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo JUri::base(true) ?>">
		<?php if($params->get('image', 1)): ?>
			<?php 
				foreach($list as $language) {
					if($language->active){

						echo JHtml::_('image', 'mod_languages/'.$language->image.'.gif', $language->title_native, array('title'=>$language->title_native), true);
						
						break;
					}
				}
			?>
		<?php else : ?>
			<i class="fa fa-flag"></i>
		<?php endif ?>
		<i class="fa fa-caret-down"></i>
	</a>
	<ul class="<?php echo $params->get('inline', 1) ? 'lang-inline' : 'lang-block';?> dropdown-menu" role="menu" aria-labelledby="dLabel">
	<?php foreach($list as $language):?>
		<?php if ($params->get('show_active', 0) || !$language->active):?>
			<li class="<?php echo $language->active ? 'lang-active' : '';?>">
			<a href="<?php echo $language->link;?>">
			<?php if ($params->get('image', 1)):?>
				<?php echo JHtml::_('image', 'mod_languages/'.$language->image.'.gif', $language->title_native, array('title'=>$language->title_native), true);?>
				<span><?php echo $language->title_native; ?></span>
			<?php else : ?>
				<?php echo $params->get('full_name', 1) ? $language->title_native : strtoupper($language->sef);?>
			<?php endif; ?>
			</a>
			</li>
		<?php endif;?>
	<?php endforeach;?>
	</ul>
<?php endif; ?>

<?php if ($footerText) : ?>
	<div class="posttext"><p><?php echo $footerText; ?></p></div>
<?php endif; ?>
</div>

