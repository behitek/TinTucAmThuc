<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$view = JFactory::getApplication()->input->get('view');
$item  = is_array($displayData) ? $displayData['item'] : $displayData;
$params  = $item->params;
$images = json_decode($item->images);
$imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->fload_fulltext;

$data = array();
$data['item'] = $item;
if($view === 'article'){
    if(isset($images->image_fulltext) && !empty($images->image_fulltext)){
        $data['image'] = $images->image_fulltext;
        $data['alt'] = $images->image_fulltext_alt;
	   $data['caption'] = $images->image_fulltext_caption;    
    }
}else if (isset($images->image_intro) && !empty($images->image_intro)) {
	$data['image'] = $images->image_intro;
	$data['alt'] = $images->image_intro_alt;
	$data['caption'] = $images->image_intro_caption;	
}
if (is_array($displayData) && isset($displayData['img-size'])) $data['size'] = $displayData['img-size'];

?>

<?php if ((isset($images->image_intro) && !empty($images->image_intro)) || (isset($images->image_fulltext) && !empty($images->image_fulltext))) : ?>
<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image">
<?php echo JLayoutHelper::render('joomla.content.image.image', $data); ?>
</div>
<?php endif ?>