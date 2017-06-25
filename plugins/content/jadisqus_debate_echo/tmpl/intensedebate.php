<?php
/**
 * ------------------------------------------------------------------------
 * JA Disqus Debate Echo plugin for J3x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//IntenseDebate config
$account        = $this->plgParams->get('provider-intensdebate-account');

$url = str_replace('&amp;', '&', $this->_url );
?>

<?php if ($this->commentContext == 'count'): ?>
	<span class="containerCountComment" onclick="window.location.href='<?php echo $this->_url;?>#idc-container'; return false;">
	<script id="idebate-counter" type="text/javascript">
	//<![CDATA[
		idcomments_acct = "<?php echo $account?>";
		idcomments_post_id = "<?php echo $this->_postid_debate?>";
		idcomments_post_url = encodeURIComponent("<?php echo $url;?>");
	//]]>
	</script>
	<input type="hidden" id="debate-counter"/>
	<script type="text/javascript" src="http://www.intensedebate.com/js/genericLinkWrapperV2.js"></script>
    </span>
<?php else: ?>
	<script id="idebate-comment" type="text/javascript">
	//<![CDATA[
		idcomments_acct = "<?php echo $account?>";
		idcomments_post_id = "<?php echo $this->_postid_debate?>";
		idcomments_post_url = "<?php echo $this->_url?>";
	//]]>
	</script>
	<span id="IDCommentsPostTitle" style="display:none"></span>
	<input type="hidden" id="debate-comment"/>
	<script type="text/javascript" src="http://www.intensedebate.com/js/genericCommentWrapperV2.js"></script>
<?php endif; ?>