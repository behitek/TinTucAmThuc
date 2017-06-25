<?php
/**
 *------------------------------------------------------------------------------
 * @package Teline V Template - JoomlArt
 * @version 1.0 Feb 1, 2014
 * @author JoomlArt http://www.joomlart.com
 * @copyright Copyright (c) 2004 - 2014 JoomlArt.com
 * @license GNU General Public License version 2 or later;
 *------------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

$aparams = JATemplateHelper::getParams();
$aparams->loadArray($helper->toArray(true));

$input = JFactory::getApplication()->input;
if ($input->get ('option') != 'com_content' || $input->get ('view') != 'article') {
	return ;
}
$item_id = $input->get ('id');

$model = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
$model->setState('params', $aparams);
$item = $model->getItem ($item_id);
$items = JATemplateHelper::getRelatedItems($item, $aparams);
?>

<?php foreach ($items as $item) : ?>
<?php echo JLayoutHelper::render('joomla.content.item_link', array('item' => $item, 'params' => $aparams)); ?>
<?php endforeach ?>