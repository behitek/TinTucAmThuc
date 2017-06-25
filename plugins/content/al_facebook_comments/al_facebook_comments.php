<?php
/**
 * @package AL_Facebook_Comments
 * @copyright (C)2013 AlexLopezIT.com
 * @license GNU/GPL
 * http://www.AlexLopezIT.com
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport( 'joomla.plugin.plugin' );

// load class ContentHelperRoute if K2
if(!class_exists('ContentHelperRoute')) require_once (JPATH_SITE . '/components/com_content/helpers/route.php');

class plgContental_facebook_comments extends JPlugin {
	
	//private $app;
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->app = JFactory::getApplication();
	}
	
	public function onContentBeforeDisplay($context, &$article, &$params, $page=0){
		if($this->app->isAdmin())
		{
			return '';
		}		
		$ignore_pagination = $this->params->get( 'ignore_pagination');
		$view = JRequest::getCmd('view');
		if (($ignore_pagination==0)) {
			$this->InjectCode($article, $view);
		}
	}

	public function onContentAfterDisplay($context, &$article, &$params, $page=0)
	{
		if($this->app->isAdmin())
		{
			return '';
		}
		$ignore_pagination = $this->params->get( 'ignore_pagination');
		$view = JRequest::getCmd('view');
		if (($ignore_pagination==1)) {
			$this->InjectCode($article, $view);
		}
	}
	
	public function onBeforeCompileHead(){
		$this->InjectHeadCode();
	}

	private function InjectHeadCode(){
		$document                 = JFactory::getDocument();
		$enable_like              = $this->params->get( 'enable_like');
		$enable_pinterest         = $this->params->get( 'enable_pinterest');
		$enable_linkedin          = $this->params->get( 'enable_linkedin');
		$enable_gplus             = $this->params->get( 'enable_gplus');
		$enable_twitter           = $this->params->get( 'enable_twitter');
		$enable_comments          = $this->params->get( 'enable_comments');
		$view                     = JRequest::getCmd('view');
		if (($enable_like==1)||($enable_comments==1)||($enable_linkedin==1)||($enable_gplus==1)||($enable_twitter==1)) {
			$config                   = JFactory::getConfig();
			$site_name                = $config->get('config.sitename');
			$description              = $this->params->get('description');
			$enable_admin             = $this->params->get('enable_admin');
			$admin_id                 = $this->params->get('admin_id');
			$app_id                   = $this->params->get('app_id');
			$idioma                   = $this->params->get('idioma');
			$meta                     = "";

			$head_data = array();
			foreach( $document->getHeadData() as $tmpkey=>$tmpval ){
				if(!is_array($tmpval)){
					$head_data[] = $tmpval;
				} else {
					foreach( $tmpval as $tmpval2 ){
						if(!is_array($tmpval2)){
							$head_data[] = $tmpval2;
						}
					}
				}
			}
			$head = implode(',',$head_data);
			// $document->addCustomTag('<link rel="stylesheet" type="text/css" href="/plugins/content/al_facebook_comments/assets/css/style.css" media="screen" />');
			$document->addStylesheet(JURI::base(true) . '/plugins/content/al_facebook_comments/assets/css/style.css');

			if (($description==0)&&(preg_match('/<meta property="og:description"/i',$head)==0)){
				$description = $document->getMetaData("description");
				$meta .= "<meta property=\"og:description\" content=\"".$description."\" />".PHP_EOL;
			}
			if ($enable_admin==0) {
				$admin_id="";
			}

			if (preg_match('/<meta property="og:locale"/i',$head)==0){
				$meta .= "<meta property=\"og:locale\" content=\"".$this->getIdioma($idioma, 'like')."\"/>".PHP_EOL;
			}
			if (preg_match('/<meta property="og:site_name"/i',$head)==0){
				$meta .= "<meta property=\"og:site_name\" content=\"$site_name\"/>".PHP_EOL;
			}
			if (preg_match('/<meta property="fb:admins"/i',$head)==0){
				$meta .= "<meta property=\"fb:admins\" content=\"$admin_id\"/>".PHP_EOL;
			}
			if (preg_match('/<meta property="fb:app_id"/i',$head)==0){
				$meta .= "<meta property=\"fb:app_id\" content=\"$app_id\"/>".PHP_EOL;
			}
			$document->addCustomTag( $meta );

			if($enable_pinterest==1){
				$document->addCustomTag('<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>');
			}
			if($enable_linkedin==1){
				$document->addCustomTag('<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>');
			}
			if($enable_gplus==1){
				$g_lang = $this->getIdioma($idioma, "gplus");
				$document->addCustomTag('<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: "'.$g_lang.'"}</script>');
			}
		}
	}

	private function InjectCode(&$article, $view){
		$document                 = JFactory::getDocument();
		$config 			  	  = JFactory::getConfig();
		$position                 = $this->params->get( 'position',  '' );
		$enable_like              = $this->params->get( 'enable_like');
		$enable_pinterest         = $this->params->get( 'enable_pinterest');
		$enable_linkedin          = $this->params->get( 'enable_linkedin');
		$enable_gplus             = $this->params->get( 'enable_gplus');
		$enable_twitter           = $this->params->get( 'enable_twitter');
		$enable_comments          = $this->params->get( 'enable_comments');
		$idioma		              = $this->params->get( 'idioma');
		$view_article_buttons     = $this->params->get( 'view_article_buttons');
		$view_frontpage_buttons   = $this->params->get( 'view_frontpage_buttons');
		$view_category_buttons    = $this->params->get( 'view_category_buttons');
		$app_id                   = $this->params->get('app_id');
		$admin_id                 = $this->params->get('admin_id');
		$enable_view_buttons      = 0;
		$description  			  = $this->params->get('description');
		$metadescription		  = $article->metadesc;
		$enable_email			  = $this->params->get( 'enable_email', 0 );
		$mail_to				  = $this->params->get('mail_to','');
		$mail_subject			  = $this->params->get('mail_subject','New post');
		$sendmailphp			  = "/plugins/content/al_facebook_comments/al_facebook_comments_sendmail.php";
		$meta 					  = "";
		
		$title    = htmlentities( $article->title, ENT_QUOTES, "UTF-8");
		$url      = $this->getPageUrl($article);
			
		/*echo "<pre>";
		print_r($view);
		echo "</pre>";*/
		
		$sendmailphp .= "?to=".urlencode($mail_to)."&subject=".urlencode($mail_subject)."&url=".urlencode($url);

		$basetitle= $document->getTitle();
		if ($view=='category'){
			$baseurl  = $this->getCatUrl($article);
		} else {
			$baseurl  = $document->getBase();
		}

		if (($enable_like==1)||($enable_comments==1)) {
			$type = $this->params->get('type');
			$defaultimage = $this->params->get('defaultimage');

			$head_data = array();
			foreach( $document->getHeadData() as $tmpkey=>$tmpval ){
		  if(!is_array($tmpval)){
		  	$head_data[] = $tmpval;
		  } else {
		  	foreach( $tmpval as $tmpval2 ){
		  		if(!is_array($tmpval2)){
		  			$head_data[] = $tmpval2;
		  		}
		  	}
		  }
			}
			$head = implode(',',$head_data);
			if (($description==1)&&(preg_match('/<meta property="og:description"/i',$head)==0)){
				if ($view == 'article') {
					$content = htmlentities(strip_tags($article->text));
					$pos = strpos($content, '.');
					if($pos === false) {
						$description = $content;
					} else {
						$description = substr($content, 0, $pos+1);
					}
					$description = $description;
				}else{
					$description = $document->getMetaData("description");
				}
		  $meta .= "<meta property=\"og:description\" content=\"".$description."/>".PHP_EOL;
			}
			if ((preg_match('/<meta property="og:type"/i',$head)==0)&&($app_id!=""))  {
				if ($view == 'article') {
					$meta .= "<meta property=\"og:type\" content=\"$type\"/>".PHP_EOL;
				} else {
					$meta .= "<meta property=\"og:type\" content=\"website\"/>".PHP_EOL;
				}
			}
			if (preg_match('/<meta property="og:image"/i',$head)==0){
				$image = $this->getImage($article);
				if ($image != "") {
					$meta .= "<meta property=\"og:image\" content=\"$image\"/>".PHP_EOL;
				}
			}
			if (preg_match('/<meta property="og:url"/i',$head)==0) {
				if ($view == 'article') {
					$meta .= "<meta property=\"og:url\" content=\"$url\"/>".PHP_EOL;
				} else {
					$meta .= "<meta property=\"og:url\" content=\"$baseurl\"/>".PHP_EOL;
				}
			}
			if (preg_match('/<meta property="og:url"/i',$head)==0) {
				if ($view == 'article') {
			  $meta .= "<meta property=\"og:title\" content=\"$title\"/>".PHP_EOL;
				} else {
			  $meta .= "<meta property=\"og:title\" content=\"$basetitle\"/>".PHP_EOL;
				}
			}
			$document->addCustomTag( $meta );
		}

		if (($view == 'article')&&($view_article_buttons==1)||
				($view == 'featured')&&($view_frontpage_buttons==1)||
				($view == 'category')&&($view_category_buttons==1)) {
			$enable_view_buttons = 1;
		} else {
			return FALSE;
		}
		
		if ($view!='article'){
			$tmp = $article->introtext;
		} else {
			$tmp = $article->text;
		}

		if ((($enable_like==1)||($enable_share==1)||($enable_linkedin==1)||($enable_pinterest==1)||($enable_gplus==1)||($enable_twitter==1))&&($enable_view_buttons==1)) {
			$htmlcode=$this->getPlugInButtonsHTML($article, $url, $title, $metadescription);
			if ($position == '1'){
				$tmp = $htmlcode . $tmp;
			}
			if ($position == '2'){
				$tmp = $tmp . $htmlcode;
			}
			if ($position == '3'){
				$tmp = $htmlcode . $tmp . $htmlcode;
			}
		}

		if (($enable_like==1)||($enable_comments==1 && $view == 'article')) {
			$f_lang = $this->getIdioma($idioma, "like");
			$tmp.= "<div id=\"fb-root\"></div>";
			$tmp.="<script>(function(d, s, id) {\n";
			$tmp.="var js, fjs = d.getElementsByTagName(s)[0];\n";
			$tmp.="if (d.getElementById(id)) return;\n";
			$tmp.="js = d.createElement(s); js.id = id;\n";
			$tmp.="js.src = \"//connect.facebook.net/".$f_lang."/all.js#xfbml=1&appId=".$app_id."\";\n";
			$tmp.="fjs.parentNode.insertBefore(js, fjs);\n";
			$tmp.="}(document, 'script', 'facebook-jssdk'));</script>";

			// Send email, validate for article view only
			if ($mail_to != "" && $enable_email==1 && $view == 'article')
			{
				$mail_body = "A new comment has been posted in:<br /><br />";
				$mail_body .= "<strong>".$title."</strong>";
					
				$sendmailphp .= "&body=".urlencode($mail_body);
					
				$scr="function sendmail(cid) {\n";
				$scr.="var xmlhttp;\n";
				$scr.="var ps = '".$sendmailphp."&cid='+cid;\n";
				$scr.="if (window.XMLHttpRequest) {xmlhttp=new XMLHttpRequest();} else {xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');}\n";
				$scr.="xmlhttp.open('GET',ps,true);\n";
				$scr.="xmlhttp.send();\n";
				$scr.="};\n";
				$scr.="window.fbAsyncInit = function() {\n";
				$scr.="FB.init({appId: '".$app_id."', status: true, cookie: true, xfbml: true});\n";
				$scr.="FB.Event.subscribe('comment.create', function (response) {sendmail(response.commentID);});\n";
				$scr.="};\n";
				$document->addScriptDeclaration($scr);

			}else{
				$document->addScript("http://connect.facebook.net/$f_lang/all.js#xfbml=1");
			};
		}
		if ($enable_comments==1 && $view == 'article') {
			$tmp = $tmp . $this->getPlugInCommentsHTML($article, $url, $title);
		}

		if ($view!='article'){
			$article->introtext=$tmp;
		} else {
			$article->text=$tmp;
		}

	}

	private function  getPlugInCommentsHTML($article, $url, $title) {
		$document                 = JFactory::getDocument();
		$config 			  	  = JFactory::getConfig();

		// $category_tobe_excluded      = $this->params->get('category_tobe_excluded_comments', '' );
		// $content_tobe_excluded       = $this->params->get('content_tobe_excluded_comments', '' );
		// $excludedContentList         = @explode ( ",", $content_tobe_excluded );
		// if ($article->id!=null) {
		// 	if ( in_array ( $article->id, $excludedContentList )) {
		// 		return;
		// 	}
		// 	if (is_array($category_tobe_excluded ) && in_array ( $article->catid, $category_tobe_excluded )) {
		// 		return;
		// 	}
		// } else {
		// 	if (is_array($category_tobe_excluded ) && in_array ( JRequest::getCmd('id'), $category_tobe_excluded )) return;
		// }

		// Prevent display plugin if this article is excluded
		if($this->exclude($article, "comments") == true) return;

		$htmlCode                    = "";
		$app_id                   	 = $this->params->get('app_id');
		$comments_width             = $this->params->get('comments_width');
		$number_comments             = $this->params->get('number_comments');
		$color_scheme                = $this->params->get('color_scheme');
		$enable_comments_count       = $this->params->get('enable_comments_count');
		$support					 = $this->params->get('support','1');
		$idioma		              	 = $this->params->get( 'idioma');

		$htmlCode .="<div class=\"al_comments_container\">";

		if ($enable_comments_count==1){
			$htmlCode .= "<div class=\"al_comments_count\">";
			$htmlCode .= "<fb:comments-count href=\"$url\"></fb:comments-count> comments";

			$htmlCode .="</div>";

		}
		$tmp = "<fb:comments href=\"$url\" num_posts=\"$number_comments\" width=\"$comments_width\" colorscheme=\"$color_scheme\"></fb:comments>";

		$htmlCode .= "<div class=\"al_comments_box\">$tmp</div>";

		$al = 'Facebook Social Comments';
		$link = 'http://www.alexlopezit.com/joomla/al-facebook-comments-box-for-joomla';
		if($idioma == 'es'){
			$al = 'Plugin Commentarios de Facebook';
			$link = 'http://www.alexlopezit.com/es/plugin-comentarios-de-facebook-para-joomla';
		}else{
			$al = 'Facebook Social Comments';
			$link = 'http://www.alexlopezit.com/facebook-comments-plugin-for-joomla';
		}
		if($support == '1'){
			$htmlCode .= '<div id="al_link20" style="font-size:9px;"><a href="'.$link.'" title="'.$al.'"><strong>'.$al.'</strong></a></div>';
		}
		if($support == '0'){
			$htmlCode .= '<div  id="al_link20" style="display:none;"><a href="'.$link.'" title="'.$al.'"><strong>'.$al.'</strong></a></div>';
		}

		$htmlCode .="</div>";

		return $htmlCode;
	}
	
	private function  getPlugInButtonsHTML($article, $url, $title, $metadescription) {
		$document                 	= JFactory::getDocument();
		$imagen 					= $this->getImage($article);

		// $category_tobe_excluded     = $this->params->get('category_tobe_excluded_buttons' );
		// $content_tobe_excluded      = $this->params->get('content_tobe_excluded_buttons','');
		// $excludedContentList        = @explode ( ",", $content_tobe_excluded );
		// if ($article->id!=null) {
		// 	if ( in_array ( $article->id, $excludedContentList )) {
		// 		return;
		// 	}
		// 	if (is_array($category_tobe_excluded ) && in_array ( $article->catid, $category_tobe_excluded )) {
		// 		return;
		// 	}
		// } else {
		// 	if (is_array($category_tobe_excluded ) && in_array ( JRequest::getCmd('id'), $category_tobe_excluded )) return;
		// }

		// Prevent display plugin if this article is excluded
		if($this->exclude($article, "buttons") == true) return;

		$width_like                 = $this->params->get('width_like');
		$enable_like                = $this->params->get('enable_like','1');
		$enable_fbshare               = $this->params->get('enable_fbshare','1');
		$share_fbtext                 = $this->params->get('share_fbtext','Share');
		$enable_pinterest           = $this->params->get( 'enable_pinterest');
		$enable_linkedin            = $this->params->get( 'enable_linkedin');
		$enable_gplus             	= $this->params->get( 'enable_gplus');
		$enable_twitter             = $this->params->get( 'enable_twitter');
		$tweetby              		= $this->params->get( 'tweetby');
		$send                       = $this->params->get('send','1');
		$idioma		              	= $this->params->get( 'idioma');
		$encoded_description		= urlencode($metadescription);

		$htmlCode                   = "";
		if ($send == 2) {
			$standalone=1;
		} else {
			$standalone=0;
			if ($send == 1) {
				$send  = "true";
			} else {
				$send = "false";
			}
		}

		$share_button_style = $this->params->get('share_button_style','button_count');
		$color_scheme = $this->params->get( 'color_scheme','light');
		$htmlCode ="<div class=\"al_buttons_container\">";

		if($enable_pinterest==1){

			$pinterest = '<a data-pin-config="beside" target="_blank" href="http://pinterest.com/pin/create/button/?url='.$url.'&media='.$imagen.'&description='.$encoded_description.'" data-pin-do="buttonPin"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>';
			$htmlCode .="<div class=\"al_pinterest al_btn\">$pinterest</div>";
		}
		if($enable_linkedin==1){
			$linkedin = '<script type="IN/Share" data-url="'.$url.'" data-counter="right"></script>';
			$htmlCode .="<div class=\"al_linkedin al_btn\">$linkedin</div>";
		}
		if($enable_gplus==1){
			$gplus = '<g:plusone href="'.$url.'" size="medium"></g:plusone>';
			$htmlCode .="<div class=\"al_gplus al_btn\">$gplus</div>";
		}
		if($enable_twitter==1){
			if($tweetby != ''){
				$twitterby = ' data-via="'.$tweetby.'" ';
				$twitterrelated = ' data-related="'.$tweetby.'" ';
			}else{
				$twitterby = '';
				$twitterrelated = '';
			}
			$t_lang = $this->getIdioma($idioma, "twitter");
			$twitter = '<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal"'.$twitterby.''.$twitterrelated.'data-lang="'.$t_lang.'">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
			$htmlCode .="<div class=\"al_twitter al_btn\">$twitter</div>";
		}
		if($enable_fbshare==1){
			$fbshare = $this->fb_share($title, $url, $metadescription, $imagen, $share_fbtext);
			$fbsharecount = $this->fb_count($url);
			$htmlCode .="<div class=\"al_fbshare al_btn al_fbshare_$color_scheme\">$fbshare <div class=\"al_fbshare_count\">$fbsharecount</div></div>";
		}
		if ($standalone==1){
			$tmp = "<fb:send href=\"$url\" colorscheme=\"$color_scheme\"></fb:send>";
			$htmlCode .="<div class=\"al_like al_btn\">$tmp</div>";
		}
		$tmp = "<fb:like href=\"$url\" layout=\"button_count\" show_faces=\"false\" send=\"$send\" width=\"$width_like\" colorscheme=\"$color_scheme\"></fb:like> \n";
		if ($enable_like==1){
			$htmlCode .="<div class=\"al_like al_btn\">$tmp</div>";
		}
		$htmlCode .="</div>";

		return $htmlCode;
	}

	private function getCatUrl($obj){
		if (!is_null($obj)&&(!empty($obj->catid))) {
			$url = JRoute::_(ContentHelperRoute::getCategoryRoute($obj->catid));
			$uri = JURI::getInstance();
			$base  = $uri->toString( array('scheme', 'host', 'port'));
			$url = $base . $url;
			$url = JRoute::_($url, true, 0);
			return $url;
		}
	}

	private function getPageUrl($obj){
		if (!is_null($obj)&&(!empty($obj->catid))) {
			if (empty($obj->catslug)){
				$url = JRoute::_(ContentHelperRoute::getArticleRoute($obj->slug, $obj->catid));
			} else {
				$url = JRoute::_(ContentHelperRoute::getArticleRoute($obj->slug, $obj->catslug));
			}
			$uri = JURI::getInstance();
			$base  = $uri->toString( array('scheme', 'host', 'port'));
			$url = $base . $url;
			$url = JRoute::_($url, true, 0);
			return $url;
		}
	}

	// Exclude content and categories from ID
	function  exclude($article, $type) {
		$document 	= JFactory::getDocument();
		$config 	= JFactory::getConfig();

		if($type == "comments"){
			$category_tobe_excluded = $this->params->get('category_tobe_excluded_comments', '' );
			$content_tobe_excluded = $this->params->get('content_tobe_excluded_comments', '' );
		}
		if($type == "buttons"){
			$category_tobe_excluded = $this->params->get('category_tobe_excluded_buttons','');
			$content_tobe_excluded = $this->params->get('content_tobe_excluded_buttons','');
		}

		$excludedContentList = @explode ( ",", $content_tobe_excluded );
		if ($article->id!=null) {
			if ( in_array ( $article->id, $excludedContentList )) {
				return true;
			}
			if (is_array($category_tobe_excluded ) && in_array ( $article->catid, $category_tobe_excluded )) {
				return true;
			}
		} else {
			if (is_array($category_tobe_excluded ) && in_array ( JRequest::getCmd('id'), $category_tobe_excluded )) return true;
		}
	}

	private function fb_count($url) {
		$facebook = file_get_contents('https://api.facebook.com/restserver.php?method=links.getStats&urls='.$url);
		$fbbegin = '<share_count>';
		$fbend = '</share_count>';
		$fbpage = $facebook;
		$fbparts = explode($fbbegin,$fbpage);
		$fbpage = $fbparts[1];
		$fbparts = explode($fbend,$fbpage);
		$fbcount = $fbparts[0];
		if($fbcount == '') {
			$fbcount = '0';
		}
		return $fbcount;
	}

	private function fb_share($title, $url, $summary, $image, $text){
		$title=urlencode($title);
		$url=urlencode($url);
		$summary=urlencode($summary);
		$image=urlencode($image);

		$fbshare = "<a onClick=\"window.open('https://www.facebook.com/sharer.php?s=100&amp;p[title]=$title&amp;p[summary]=$summary&amp;p[url]=$url&amp;&p[images][0]=$image', 'sharer', 'toolbar=0,status=0,width=548,height=325');\" href=\"javascript: void(0)\"><i class=\"al_fbshare_icon\"></i>$text</a>";
		return $fbshare;
	}
	
	private function getImage($article){
		$document = JFactory::getDocument();
		$defaultimage = $this->params->get('defaultimage');
		if ((preg_match('%<img.*?src=(?:"(.*?)"|\'(.*?)\').*?>%i', $article->text, $regs)))
		{
			if (preg_match('/^http/i',$regs[1]))
			{
				$image = $regs[1];
			} else {
				$image ='http://'.$_SERVER['SERVER_NAME'].'/'.$regs[1];
			}
		} else
		{
			if ($defaultimage=="")
			{
				$image = 'http://'.$_SERVER['SERVER_NAME'].'/plugins/content/plg_al_facebook_comments/assets/images/enlace.png';
			} else {
				$image = $defaultimage;
			}
		}
		if ($image != "")
		{
			return $image;
		}
	}
	
	private function getIdioma($idioma, $type){
		$lenguaje = "";
		$g_lang = 'en';
		$t_lang = 'en';
		$f_lang = 'en_US';

		if($idioma == 'en'){
			$g_lang = 'en';
			$t_lang = 'en';
			$f_lang = 'en_US';
		}
		if($idioma == 'nl'){
			$g_lang = 'nl';
			$t_lang = 'nl';
			$f_lang = 'nl_NL';
		}
		if($idioma == 'es'){
			$g_lang = 'es';
			$t_lang = 'es';
			$f_lang = 'es_ES';
		}
		if($idioma == 'it'){
			$g_lang = 'it';
			$t_lang = 'it';
			$f_lang = 'it_IT';
		}
		if($idioma == 'fr'){
			$g_lang = 'fr';
			$t_lang = 'fr';
			$f_lang = 'fr_FR';
		}
		if($idioma == 'de'){
			$g_lang = 'de';
			$t_lang = 'de';
			$f_lang = 'de_DE';
		}
		if($idioma == 'pt'){
			$g_lang = 'pt-BR';
			$t_lang = 'pt';
			$f_lang = 'pt_PT';
		}
		if($idioma == 'ja'){
			$g_lang = 'ja';
			$t_lang = 'ja';
			$f_lang = 'ja_JP';
		}
		if($idioma == 'ko'){
			$g_lang = 'ko';
			$t_lang = 'ko';
			$f_lang = 'ko_KR';
		}
		if($idioma == 'ru'){
			$g_lang = 'ru';
			$t_lang = 'ru';
			$f_lang = 'ru_RU';
		}
		if($idioma == 'tr'){
			$g_lang = 'tr';
			$t_lang = 'tr';
			$f_lang = 'tr_TR';
		}

		if($type == "gplus"){
			$lenguaje = $g_lang;
		}elseif($type == "twitter"){
			$lenguaje = $t_lang;
		}elseif($type == "like"){
			$lenguaje = $f_lang;
		}
		return $lenguaje;
	}
}
?>

