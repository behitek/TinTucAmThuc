<?php
/**
 * ------------------------------------------------------------------------
 * Plugin JA Content Type
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');

class JAContentTypeImageHelper
{
	public static function getImage ($image, $size = 'medium') {
		$gparams = JComponentHelper::getParams('com_content');		
		$img_dir = $gparams->get ('jact_img_dir', 'media/jact');
		$maxwidth = $gparams->get ('jact_img_' . $size . '_width');
		$maxheight = $gparams->get ('jact_img_' . $size . '_height');
		$relpath = JPath::clean(JFolder::makeSafe ($img_dir . '/' . $size), '/');
		// remove up folder
		$relpath = preg_replace ('#\.\./?#', '', $relpath);
		$imgpath = JPATH_ROOT . '/' . $image;
		$imgpath2 = JPATH_ROOT . '/' . $relpath . '/' . $image;

		// if not enable JA ContentType Images, just return original image
		if (!$gparams->get('jact_img_enabled')) return $image;
		
		// Ignore if not config width/height
		if (!$maxwidth && !$maxheight) return $image;
		// Ignore if file not found
		if (!is_file ($imgpath)) return $image;

		// check if image generated
		if (!is_file ($imgpath2) || (filemtime($imgpath) > filemtime($imgpath2))) {
			// check folder exits
			$d = dirname($imgpath2);
			if (!is_dir ($d)) {
				mkdir ($d, 0755, true);
			}
			list($width, $height) = getimagesize($imgpath);
			if (!function_exists('smart_resize_image')) {
				require_once __DIR__ . '/libs/smart_resize_image.function.php';
			}
			smart_resize_image ($imgpath, null, $maxwidth, $maxheight, true, $imgpath2, false);
		}

		return $relpath . '/' . $image;
	}

	public static function scanImages() {
		//TODO: not implement yet
	}
}