<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JLoader::register('ContactModelContact', JPATH_ROOT.'/components/com_contact/models/contact.php');
JLoader::register('ContactHelperRoute', JPATH_ROOT.'/components/com_contact/helpers/route.php');

$modelContact = JModelLegacy::getInstance('Contact', 'ContactModel');

$contact_id = $displayData['contact_id'];
$contact = null;
if($contact_id) {
	try {
		$contact = $modelContact->getItem($contact_id);
	} catch (Exception $e) {

	}
}
?>
<?php if($contact): ?>
<a href="<?php echo ContactHelperRoute::getContactRoute($contact->id.'-'.$contact->alias, $contact->catid); ?>" title="<?php echo $contact->name; ?>" target="_blank">
	<img alt="<?php echo $contact->name; ?>" style="max-height: 100px; max-width: 100px; display: block; float: left; margin: 3px;" src="<?php echo JUri::root(true).'/'.$contact->image; ?>" data-holder-rendered="true" />
</a>
<?php endif; ?>