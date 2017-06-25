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

jimport('joomla.plugin.plugin');
/**
 *
 * JA DISQUS DEBATE ECHO CLASS
 * @author JoomlArt
 *
 */
class plgContentJADisqus_debate_echo extends JPlugin
{
    var $_plgCode = '#{jacomment\s*}#i';
    var $_plgCodeDisable = '#{jacomment(\s*)disable}#i';

    var $_url = "";
    var $_path = "";
    var $_postid = "";
	var $_postid_debate ="";
    var $source = "both";
    var $provider = "intensedebate";


    /**
     *
     * Construct method
     * @param object $subject
     * @param object $config
     */
    function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
		JHTML::_('behavior.framework', true);
        $this->plugin = JPluginHelper::getPlugin('content', 'jadisqus_debate_echo');
        $this->plgParams = new JRegistry();
        $this->plgParams->loadString($this->plugin->params);
        $this->source = $this->plgParams->get('source', 'both');
        $provider = $this->plgParams->get('provider', 'intensedebate');
        $this->postion_display_listing = $this->plgParams->get('postion_display_listing','onContentBeforeDisplay');
    	$this->postion_display_details = $this->plgParams->get('postion_display_details','onContentBeforeDisplay');
    	$this->position 	= $this->plgParams->get('position','onContentAfterDisplay');
        switch ($provider) {
            case 'disqus':
                $provider = 'disqus';
                break;
            case 'jskit':
                $provider = 'jskit';
                break;
            case 'intensdebate':
                $provider = 'intensedebate';
                break;
            default:
                $provider = 'intensedebate';
                break;
        }
        $this->provider = $provider;
		if ($this->provider == 'disqus') {
            $countscript = $this->loadLayout($this->plugin, 'disqus_count');
            //$body = str_replace('</body>', $countscript . "\n</body>", $body);
			$doc = JFactory::getDocument();
			$doc->addScriptDeclaration($countscript);
        }
		
        $this->stylesheet($this->plugin);
    }
	
    /**
     *
     * Process content before display
     * @param string $context key type component
     * @param object $article
     * @param object $params
     * @param int $limitstart
     * @return unknown
     */
    function onContentBeforeDisplay($context, &$article, &$params, $limitstart = 0)
    { 	
    	if(($this->isDetailPage() && $this->postion_display_details == 'onContentBeforeDisplay') || (!$this->isDetailPage()  && $this->postion_display_listing == 'onContentBeforeDisplay') ){
    		$return = $this->displayComment('count', $context, $article, $params, $limitstart);      	
        	return $return;
    	}
		return;
    }
	/**
     *
     * Process content after display
     * @param string $context key type component
     * @param object $article
     * @param object $params
     * @param int $limitstart
     * @return unknown
     */
	function onContentAfterDisplay($context, &$article, &$params, $limitstart = 0){
		$return = '';
		if(($this->isDetailPage() && $this->postion_display_details == 'onContentAfterDisplay') || (!$this->isDetailPage()  && $this->postion_display_listing == 'onContentAfterDisplay') ){
    		$return .= $this->displayComment('count', $context, $article, $params, $limitstart);      	
        	
    	}
    	if ($this->isDetailPage() && $this->position == 'onContentAfterDisplay') {
            //it is not called from detail view
            $return .= $this->displayComment('comment', $context, $article, $params, $limitstart);
        }  
		return $return;
	}
	/**
     *
     * Process content after display
     * @param string $context key type component
     * @param object $article
     * @param object $params
     * @param int $limitstart
     * @return unknown
     */
	function onContentAfterTitle($context, &$article, &$params, $limitstart = 0){
		if(($this->isDetailPage() && $this->postion_display_details == 'onContentAfterTitle') || (!$this->isDetailPage()  && $this->postion_display_listing == 'onContentAfterTitle')){
    		$return = $this->displayComment('count', $context, $article, $params, $limitstart);      	
        	return $return;
    	}     
		return;
	}
    /**
     *
     * Process content
     * @param string $context key type component
     * @param object $article
     * @param object $params
     * @param int $limitstart
     * @return unknown
     */
    function onContentPrepare($context, &$article, &$params, $limitstart = 0)
    {
    	$isK2 = (strpos($context, 'com_k2') !== false);
    	
    	if($this->postion_display_details == 'BeforeContent' && $this->isDetailPage()){
    		$return = $this->displayComment('count', $context, $article, $params, $limitstart);
    		if($isK2) {
    			if($params->get('itemIntroText', 1) && isset($article->introtext)) {
    				$article->introtext = $return.$article->introtext;
    				if(isset($article->text) && !empty($article->text)) {
    					$article->text = $return.$article->text;
    				}
    			} elseif($params->get('itemFullText', 1) && isset($article->fulltext)) {
    				$article->fulltext = $return.$article->fulltext;
    				if(isset($article->text) && !empty($article->text)) {
    					$article->text = $return.$article->text;
    				}
    			} elseif (isset($article->text)) {
    				$article->text = $return.$article->text;
    			}
    		} else {
    			$article->text = $return.$article->text;
    		}
    	}
    	if($this->postion_display_details == 'AfterContent' && $this->isDetailPage()){
    		$return = $this->displayComment('count', $context, $article, $params, $limitstart);
    		if($isK2) {
    			if($params->get('itemFullText', 1) && isset($article->fulltext)) {
    				$article->fulltext = $article->fulltext.$return;
    				if(isset($article->text) && !empty($article->text)) {
    					$article->text = $article->text.$return;
    				}
    			} elseif($params->get('itemIntroText', 1) && isset($article->introtext)) {
    				$article->introtext = $article->introtext.$return;
    				if(isset($article->text) && !empty($article->text)) {
    					$article->text = $article->text.$return;
    				}
    			} elseif(isset($article->text)) {
    				$article->text = $article->text.$return;
    			}
    		} else {
    			$article->text = $article->text.$return;
    		}
    	}
    	if ($this->isDetailPage() && $this->position == 'onAfterDisplay') {

    		//it is not called from detail view
    		$return = $this->displayComment('comment', $context, $article, $params, $limitstart);
    		
    		if($isK2) {
    			if($params->get('itemFullText', 1) && isset($article->fulltext)) {
    				$article->fulltext = $article->fulltext.$return;
    				if(isset($article->text) && !empty($article->text)) {
    					$article->text = $article->text.$return;
    				}
    			} elseif($params->get('itemIntroText', 1) && isset($article->introtext)) {
    				$article->introtext = $article->introtext.$return;
    				if(isset($article->text) && !empty($article->text)) {
    					$article->text = $article->text.$return;
    				}
    			} else {
    				$article->text = $article->text.$return;
    			}
    		} else {
    			$article->text = $article->text.$return;
    		}

    	}
    }
 

    /**
     *
     * Parse content with disqus
     * @param string $context
     * @param object $article
     * @param object $params
     * @param int $limitstart
     * @return unknown
     */
    function displayComment($commentContext, $context, &$article, &$params, $limitstart)
    {
    	if($commentContext == 'count' && $context != 'com_content.article' && $context != 'com_content.featured' && $context !='com_content.category' && $context != 'com_k2.itemlist' && $context != 'com_k2.item') {
    		return;
    	}
    	if($commentContext == 'comment' && $context != 'com_content.article' && $context != 'com_k2.item') {
    		return;
    	}
        $plugin = $this->plugin;
        $plgParams = $this->plgParams;
        
        if (($this->source=="com_k2")&& (!$this->checkComponent('com_k2')))
		{
			return;
		}
		
		
		$app = JFactory::getApplication();

        //$sectionid      = $article->sectionid;
        if (!isset($article->catid)) {
            $article->catid = 0;
        }

        /*if (!isset($article->text) && empty($article->text)) {
            $article->text = '';
        }*/

        /*if (!isset($article->text)) {
            $article->introtext = isset($article->fulltext) ? $article->fulltext : (isset($article->introtext) ? $article->introtext : "");
        }*/
        $catid = $article->catid;
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $itemid = JRequest::getInt('Itemid');
        $id = JRequest::getInt('id');
        //
        if (!isset($article->id) || !$article->id) {
            return '';
        }
        if ($this->source != 'both' && $this->source != $option) {
            return;
        }
        
        if ($this->isDetailPage()) {
            //it is not called from detail view
            if($id != $article->id) {
            	return;
            }
            
	        $display_on_details = (int) $plgParams->get('display_on_details', 1);
	        if (($commentContext == 'count') && !$display_on_details) {
	            return;
	        }
        } else {
        	
	        $display_on_home = (int) $plgParams->get('display_on_home', 1);
	        if (($commentContext == 'count') && ($view == 'featured' && $display_on_home != 1)) {
	            return;
	        }
	        
	        $display_on_list = (int) $plgParams->get('display_on_list', 1);
	        if (
	        ($commentContext == 'count') &&
	        ((($option == 'com_k2' && $view != 'item') || ($option == 'com_content' && $view != 'article' && $view != 'featured')) && $display_on_list != 1)) {
	            return;
	        }
        }
        
        //check if article is disabled manually
        if(isset($article->comment_disabled)) {
        	return;
        }
        if (isset($article->fulltext) && preg_match($this->_plgCodeDisable, $article->fulltext)) {
        	$article->fulltext = $this->removeCode($article->fulltext);
        	$article->comment_disabled = 1;
        } 
        if (isset($article->introtext) && preg_match($this->_plgCodeDisable, $article->introtext)) {
        	$article->introtext = $this->removeCode($article->introtext);
        	$article->comment_disabled = 1;
        } 
        if (isset($article->text) && preg_match($this->_plgCodeDisable, $article->text)) {
        	$article->text = $this->removeCode($article->text);
        	$article->comment_disabled = 1;
        }
        if(isset($article->comment_disabled)) {
        	return;
        }
        //GET PLUGIN CONFIG
        $mode = $plgParams->get('mode', 'automatic');

        if ($this->isContentItem($article)) {
            $catids = $plgParams->get('mode-automatic-catsid', '');
        } else {
            $catids = $plgParams->get('mode-automatic-k2catsid', '');
        }
        $tagmode = $plgParams->get('tagmode', 0);
        //
        $user = JFactory::getUser();
        $document = JFactory::getDocument();
        $this->document = & $document;

        //CHECK PAGE WAS SELECTED TO DISPLAY COMMENT FORM
        switch ($mode) {
            case 'disable':
                if(isset($article->text)) $article->text = $this->removeCode($article->text);
                if(isset($article->introtext)) $article->introtext = $this->removeCode($article->introtext);
                if(isset($article->fulltext)) $article->fulltext = $this->removeCode($article->fulltext);
                return;
                break;
            case 'manual':
                /*if (!preg_match($this->_plgCode, $article->text)) {
                    return;
                }*/
                break;
            case 'automatic':
            default:
                if (is_array($catids)) {
                    if ($catids[0] == '') {
                        $categories[] = $catid;
                    } else {
                        $categories = $catids;
                    }
                } elseif ($catids == '') {
                    $categories[] = $catid;
                } else {
                    $categories[] = $catids;
                }
                $text = @$article->text . @$article->fulltext . @$article->introtext;
                if (!(in_array($catid, $categories) || preg_match($this->_plgCode, $text))) {
                    return;
                }
                break;
        }
        //END OF CHECK
        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));

        if (in_array($article->access, $authorised)) {
            if ($option == "com_content") {
                if (!isset($article->catslug)) {
                    $article->catslug = $article->catid . ':' . $article->category_alias;
                }
                $this->_path = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug));
            } elseif ($option == "com_k2") {
                $this->_path = K2HelperRoute::getItemRoute($article->id, $article->catid);
            } else {
                $this->_path = "#";
            }

        } else {
            $this->_path = JRoute::_("index.php?option=com_user&view=login");
        }

        if (!preg_match('/^\//', $this->_path)) {
            //convert to relative url
            $this->_path = JURI::root(true) . '/' . $this->_path;
        }
        //convert to absolute url
        $this->_url = $this->getRootUrl() . $this->_path;

        $this->_sefurl = $this->convertUrl($this->_path);
        $this->_sefpath = $this->convertUrl($this->_path, true);

        $this->_postid_debate = $article->id;
		if($option == 'com_k2'){
			$this->_postid = sprintf('%s:%s:%d', $option, 'item', $article->id);
		}
		else if($option == 'com_content'){
			$this->_postid = sprintf('%s:%s:%d', $option, 'article', $article->id);
		}
		else{
			$this->_postid = '';
		}
		


        $this->commentContext = $commentContext;

        $provider = $this->provider;
        $output = $this->loadLayout($this->plugin, $provider);
        
        if ($mode == 'manual' && $commentContext == 'comment') {
    		$article->text = preg_replace($this->_plgCode, $output, $article->text);
    		if(isset($article->fulltext)) {
    			$article->fulltext = preg_replace($this->_plgCode, $output, $article->fulltext);
    		}
    		if(isset($article->introtext)) {
    			$article->introtext = preg_replace($this->_plgCode, $output, $article->introtext);
    		}
            
        } else {
            return $output;
        }
        return;
    }
    
    /**
     *
     * @return (string) - root url without last slashes
     */
    function getRootUrl()
    {
        $url = str_replace(JURI::root(true), '', JURI::root());
        $url = preg_replace('/\/+$/', '', $url);

        return $url;
    }


    /**
     *
     * Detect page detail for disqus
     * @return boolean
     */
    function isDetailPage()
    {
        $option = JRequest::getVar('option');
        $view = JRequest::getVar('view');
        //if its a detail page
        if (($option == 'com_k2' && $view == 'item') || ($option == 'com_content' && $view == 'article')) {
            return true;
        }
        return false;
    }


    /**
     *
     * Validate is content article
     * @param object $article
     * @return boolean
     */
    function isContentItem($article)
    {
        return isset($article->xreference) ? true : false;
    }


    /**
     * convert from normal format to sef format
     *
     * @param unknown_type $matches
     * @return unknown
     */
    function convertUrl($originalPath, $pathonly = false)
    {
        jimport('joomla.application.router');
        // Get the router
        $router = new JRouterSite(array('mode' => JROUTER_MODE_SEF));

        // Make sure that we have our router
        if (!$router) {
            if ($pathonly)
                return $originalPath;
            else
                return $this->getRootUrl() . $originalPath;
        }

        // Build route
        $path = str_replace(JURI::root(true), '', $originalPath);
        $uri = $router->build($path);

        $path = $uri->toString(array('path', 'query', 'fragment'));
        if (!preg_match('/^\/+/', $path)) {
            $path = "/{$path}";
        }

        //
        if ($pathonly) {
            return $path;
        } else {
            $url = $this->getRootUrl();
            return $url . $path;
        }
    }


    /**
     *
     * Process content after render
     * @return string
     */
	 /*
    function onAfterRender()
    {

        $body = JResponse::getBody();
        $countscript = "";

        if ($this->provider == 'disqus') {
            $countscript = $this->loadLayout($this->plugin, 'disqus_count');
            $body = str_replace('</body>', $countscript . "\n</body>", $body);
        }
        JResponse::setBody($body);
    }
	*/

    /**
     *
     * Remove comment tag code
     * @param string $content
     * @return string
     */
    function removeCode($content)
    {
        $content = preg_replace($this->_plgCode, '', $content);
        $content = preg_replace($this->_plgCodeDisable, '', $content);
        return $content;
    }


    /**
     *
     * Get Layout for display
     * @param object $plugin
     * @param string $layout
     * @return string
     */
    function getLayoutPath($plugin, $layout = 'default')
    {
        $app = JFactory::getApplication();

        // Build the template and base path for the layout
        $tPath = JPATH_BASE . '/templates/' . $app->getTemplate() . '/html/' . $plugin->name . '/' . $layout . '.php';
        $bPath = JPATH_BASE . '/plugins/' . $plugin->type . '/' . $plugin->name . '/tmpl/' . $layout . '.php';

        // If the template has a layout override use it
        if (file_exists($tPath)) {

            return $tPath;

        } elseif (file_exists($bPath)) {

            return $bPath;

        }
        return '';
    }


    /**
     *
     * Display content
     * @param object $plugin
     * @param string $layout
     * @return string
     */
    function loadLayout($plugin, $layout = 'default')
    {
        $layout_path = $this->getLayoutPath($plugin, $layout);
        if ($layout_path) {
            ob_start();
            require $layout_path;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
        return '';
    }


    /**
     *
     * Set style for content display
     * @param object $plugin
     * @return unknown
     */
    function stylesheet($plugin)
    {
        $app = JFactory::getApplication();
        JHTML::stylesheet('plugins/' . $plugin->type . '/' . $plugin->name . '/asset/style.css');
        if (is_file(JPATH_SITE . '/templates/' . $app->getTemplate() . '/css/' . $plugin->name . ".css"))
            JHTML::stylesheet('templates/' . $app->getTemplate() . '/css/' . $plugin->name . ".css");
    }
	
	/**
     *
     * Check component is existed
     * @param string $component component name
     * @return int return > 0 when component is installed
     */
    function checkComponent($component)
    {
        $db = JFactory::getDBO();
        $query = " SELECT Count(*) FROM #__extensions as e WHERE e.element ='$component' and e.enabled=1";
        $db->setQuery($query);
        return $db->loadResult();
    }
	
}