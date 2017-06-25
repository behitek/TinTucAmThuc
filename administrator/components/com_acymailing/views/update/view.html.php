<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.7.0
 * @author	acyba.com
 * @copyright	(C) 2009-2017 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class UpdateViewUpdate extends acymailingView{

    function display($tpl = null){

        $function = $this->getLayout();
        if(method_exists($this, $function)) $this->$function();

        parent::display($tpl);
    }

    function acysms(){
        $acyToolbar = acymailing::get('helper.toolbar');
        $acyToolbar->setTitle('AcySMS');
        $acyToolbar->display();

        $js = '
        function installAcySMS(){
            var progressbar = document.getElementById("progressbar");
            var information = document.getElementById("information");
            progressbar.style.width = "10%";
            information.innerHTML = "'.htmlspecialchars(acymailing_translation('ACY_DOWNLOADING'), ENT_QUOTES, 'UTF-8').'";

            try {
                var ajaxCall = new Ajax("index.php?option=com_acymailing&tmpl=component&ctrl=file&task=downloadAcySMS", {
                    method: "get",
                    onComplete: function (responseText, responseXML) {
                        if(responseText == "success") {
                            progressbar.style.width = "40%";
                            document.getElementById("information").innerHTML = "'.htmlspecialchars(acymailing_translation('ACY_INSTALLING'), ENT_QUOTES, 'UTF-8').'";
                            installPackage();
                        }else{
                            document.getElementById("information").innerHTML = "'.str_replace('"', '\"', acymailing_translation_sprintf('ACY_FAILED_INSTALL', '<a href="https://www.acyba.com/download-area/download/component-acysms/level-express.html" target="_blank">', '</a>')).'";
                        }
                    }
                }).request();
            } catch (err) {
                new Request({
                    url: "index.php?option=com_acymailing&tmpl=component&ctrl=file&task=downloadAcySMS",
                    method: "get",
                    onSuccess: function (responseText, responseXML) {
                        if(responseText == "success") {
                            progressbar.style.width = "40%";
                            document.getElementById("information").innerHTML = "'.htmlspecialchars(acymailing_translation('ACY_INSTALLING'), ENT_QUOTES, 'UTF-8').'";
                            installPackage();
                        }else{
                            document.getElementById("information").innerHTML = "'.str_replace('"', '\"', acymailing_translation_sprintf('ACY_FAILED_INSTALL', '<a href="https://www.acyba.com/download-area/download/component-acysms/level-express.html" target="_blank">', '</a>')).'";
                        }
                    }
                }).send();
            }
        }

        function installPackage(){
            var progress = 40;
            var interval = setInterval(function(){
                if(progress >= 70) clearInterval(interval);
                if(progressbar.style.width != "100%") {
                    progress += 10;
                    progressbar.style.width = progress + "%";
                }
            }, 4000);

            try{
                var ajaxCall = new Ajax("index.php?option=com_acymailing&tmpl=component&ctrl=file&task=installPackage",{
                    method: "get",
                    onComplete: function(responseText, responseXML) {
                        if(responseText == "success") {
                            progressbar.style.width = "100%";
                            setTimeout(function(){ 
                                document.getElementById("meter").style.display = "none"; 
                                document.getElementById("postinstall").style.display = ""; 
                            }, 2000);
                        }else{
                            document.getElementById("information").innerHTML = "'.str_replace('"', '\"', acymailing_translation_sprintf('ACY_FAILED_INSTALL', '<a href="https://www.acyba.com/download-area/download/component-acysms/level-express.html" target="_blank">', '</a>')).'";
                        }
                    }
                }).request();
            }catch(err){
                new Request({
                    url:"index.php?option=com_acymailing&tmpl=component&ctrl=file&task=installPackage",
                    method: "get",
                    onSuccess: function(responseText, responseXML) {
                        if(responseText == "success") {
                            progressbar.style.width = "100%";
                            setTimeout(function(){
                                document.getElementById("meter").style.display = "none";
                                document.getElementById("postinstall").style.display = "";
                            }, 2000);
                        }else{
                            document.getElementById("information").innerHTML = "'.str_replace('"', '\"', acymailing_translation_sprintf('ACY_FAILED_INSTALL', '<a href="https://www.acyba.com/download-area/download/component-acysms/level-express.html" target="_blank">', '</a>')).'";
                        }
                    }
                }).send();
            }
        }';

        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration($js);
    }
}
