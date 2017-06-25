/** 
 *------------------------------------------------------------------------------
 * @package       T3 Framework for Joomla!
 *------------------------------------------------------------------------------
 * @copyright     Copyright (C) 2004-2013 JoomlArt.com. All Rights Reserved.
 * @license       GNU General Public License version 2 or later; see LICENSE.txt
 * @authors       JoomlArt, JoomlaBamboo, (contribute to this project at github 
 *                & Google group to become co-author)
 * @Google group: https://groups.google.com/forum/#!forum/t3fw
 * @Link:         http://t3-framework.org 
 *------------------------------------------------------------------------------
 */

var jActions = {};

(function($){
    // Switch tool
    jActions.switchClass = function ($btn) {
        var action = $btn.data('action'),
            target = $btn.data('target'),
            value = $btn.data('value'),
            key = $btn.data('key'),
            $target = $btn.parents(target);
        if (!$target.length) $target = $(target);
        // get all class to remove
        $target.find ('[data-action="' + action + '"]').not($btn).each(function(){
            $(this).removeClass ('active');
            $target.removeClass ($(this).data('value'));
        });
        $target.addClass (value);
        $btn.addClass ('active');
        // store into cookie
        $.cookie (action + '-' + key, $btn.data('cookie') != 'no' ? value : '', {path: '/'});
    };
    jActions.switchClassInit = function ($btn) {
        var action = $btn.data('action'),
            target = $btn.data('target'),
            defaultValue = $btn.data('value'),
            key = $btn.data('key'),
            cookieValue = $.cookie(action + '-' + key),
            value = cookieValue ? cookieValue : defaultValue,
            $target = $btn.parents(target);
        if (!$target.length) $target = $(target);
        // get all class to remove
        $target.find ('[data-action="' + action + '"]').each(function(){
            var $this = $(this);
            if ($this.data('value') == value) {
                $target.addClass (value);
                $this.addClass ('active');
            } else {
                $this.removeClass ('active');
                $target.removeClass ($this.data('value'));
            }
        });
        // store into cookie
        $.cookie (action + '-' + key, $btn.data('cookie') != 'no' ? value : '', {path: '/'});
    };


    // Switch tool
    jActions.onOff = function ($btn) {
        var action = $btn.data('action'),
            target = $btn.data('target'),
            cls = $btn.data('value'),
            key = $btn.data('key'),
            $target = $btn.parents(target),
            value = '';
        if (!$target.length) $target = $(target);
        value = $target.hasClass (cls) ? 'off' : 'on';
        // get all class to remove
        if (value == 'off') {
            $target.removeClass (cls);
            $btn.removeClass('on').addClass ('off');
        } else {
            $target.addClass (cls);
            $btn.removeClass('off').addClass ('on');
        }
        $.cookie (action + '-' + key, $btn.data('cookie') != 'no' ? value : '', {path: '/'});
    };
    jActions.onOffInit = function ($btn) {
        var action = $btn.data('action'),
            target = $btn.data('target'),
            cls = $btn.data('value'),
            defaultValue = $btn.data('default'),
            key = $btn.data('key'),
            cookieValue = $.cookie(action + '-' + key),
            value = cookieValue ? cookieValue : defaultValue,
            $target = $btn.parents(target);
        if (!$target.length) $target = $(target);
        // get all class to remove
        if (value == 'on') {
            $target.addClass (cls);
            $btn.addClass ('on');
        } else {
            $btn.addClass ('off');
        }
        // store into cookie
        $.cookie (action + '-' + key, $btn.data('cookie') != 'no' ? value : '', {path: '/'});
    };


    // next-prev actions tool
    jActions.nextPrev = function ($btn) {
        var action = $btn.data('action'),
            target = $btn.data('target'),
            prop = $btn.data('key'),
            value = parseInt($btn.data('value')),
            $target = $btn.parents(target),
            values = $btn.parent().data(prop + 's').split(','),
            curVal = $.cookie(action + '-' + prop),
            curValIdx = $.inArray(curVal, values),
            newValIdx = curValIdx + value;
        if (!$target.length) $target = $(target);
        if (!$btn.parent().data('loop') && (newValIdx < 0 || newValIdx >= values.length)) {
            return ;
        }
        newValIdx = newValIdx < 0 ? values.length-1 : (newValIdx >= values.length ? 0 : newValIdx);
        if (newValIdx != curValIdx) {
            $target.removeClass(prop + '-' + curVal.replace(' ', '-').toLowerCase()).addClass(prop + '-' + values[newValIdx].replace(' ', '-').toLowerCase());
            if ($btn.data('cookie') != 'no') $.cookie(action + '-' + prop, values[newValIdx], {path: '/'});
            $btn.parent().find ('strong').html (values[newValIdx]);
        }
    };
    jActions.nextPrevInit = function ($btn) {
        var action = $btn.data('action'),
            target = $btn.data('target'),
            prop = $btn.data('key'),
            defaultValue = $btn.data('default'),
            $target = $btn.parents(target),
            cookieValue = $.cookie(action + '-' + prop),
            value = cookieValue ? cookieValue : defaultValue;
        if (!$target.length) $target = $(target);
        if (value) {
            $target.addClass(prop + '-' + value.replace(' ', '-').toLowerCase());
            if ($btn.data('cookie') != 'no') $.cookie(action + '-' + prop, value, {path: '/'});
            $btn.parent().find ('strong').html (value);
        }
    };

    jActions.loadModuleNextPage = function(link, modid, container, callback) {
        var btn = $(link);
        var curPage = parseInt(btn.attr('data-page'));
        if(!curPage) curPage = 1;

        btn.attr('disabled', 'disabled');
        btn.find('.fa-spin').show();

        var url = btn.data('link');
        url += (url.indexOf('?') == -1 ? '?' : '&') + 't3action=module&style=raw&mid='+modid+'&_module_page=' + (curPage + 1);
        $.ajax({
            url: url,
            method: 'POST',
            success: function(data) {
                $(link).find('.fa-spin').hide();
                if(data && data.replace(/^\s+|\s+$/gm,'') != '') {
                    $('#'+container).append(data);
                    btn.attr('data-page', curPage + 1);
                    if(btn.data('maxpage') && curPage + 1 < btn.data('maxpage')) {
                        btn.removeAttr('disabled');
                    } else {
                        btn.html(Joomla.JText._('TPL_LOAD_MODULE_AJAX_DONE'));
                    }
                    if(typeof(callback) == 'function') {
                        callback();
                    }
                    $(data).find("script").each(function(i) {
                        eval($(this).text());
                    });
                } else {
                    btn.html(Joomla.JText._('TPL_LOAD_MODULE_AJAX_DONE'));
                }
            }
        });
        return false;
    };
})(jQuery);

jQuery(document).ready(function($){
    $('[data-action]').each (function() {
        // check & init default
        var $this = $(this),
            action = $this.data('action');
        if (jActions[action]) {
            $this.on('click', function() {
                jActions[action]($this);
                return false;
            });
        }
        if ($this.data('default') != undefined && jActions[action + 'Init']) {
            jActions[action + 'Init'] ($this);
        }
    });

    // prev / next article in reading-mode
    $('.pagenav li a').on ('click', function() {
        if ($('html').hasClass ('reading-mode')) {
            $.cookie ('onOff-reading-mode', 'on', {path: '/'});
        }
    });
		
		
		////////////////////////////////
		// equalheight for col
		////////////////////////////////
		var ehArray = ehArray2 = [],
			i = 0;
		
		$('.equal-height').each (function(){
			var $ehc = $(this);
			if ($ehc.has ('.equal-height')) {
				ehArray2[ehArray2.length] = $ehc;
			} else {
				ehArray[ehArray.length] = $ehc;
			}
		});
		for (i = ehArray2.length -1; i >= 0; i--) {
			ehArray[ehArray.length] = ehArray2[i];
		}
		
		var equalHeight = function() {
			for (i = 0; i < ehArray.length; i++) {
				var $cols = ehArray[i].children().filter('.col'),
					maxHeight = 0;
				$cols.css('min-height', 0).each (function() {
					maxHeight = Math.max(maxHeight, $(this).innerHeight());
				});
				$cols.css('min-height', maxHeight);
			}
			// store current size
			$('.equal-height > .col').each (function(){
				var $col = $(this);
				$col.data('old-width', $col.width()).data('old-height', $col.innerHeight());
			});
		};
		
		equalHeight();
		
		// monitor col width and fire equalHeight
		setInterval(function() {
			$('.equal-height > .col').each(function(){
				var $col = $(this);
				if (($col.data('old-width') && $col.data('old-width') != $col.width()) ||
						($col.data('old-height') && $col.data('old-height') != $col.innerHeight())) {
					equalHeight();
					// break each loop
					return false;
				}
			});
		}, 500);
	
	// special fix link popup for acymailing module. will remove when acymailing update fix for front end edit module.
	var acy_site = $('html.com_config').find('#t3-content').find('#modules-form').find('#jform_params_moduleclass_sfx').val();
	if (acy_site == 'acy-email-footer') {
		$('html.com_config').find('#t3-content').find('#modules-form').find('a.modal').each(function(i, e) {
			var acylhref = ja_base_uri+'/administrator/index.php?option=com_acymailing&tmpl=component&ctrl=chooselist&task='+$(this).prev().attr('id')+'&values='+$(this).prev().val()+'&control=';
			if (!($(this).attr('href').search("acyba.com") > 0)) {
				$(this).attr('href', acylhref);
			}
		});
	}
	// end special fix link popup for acymailing module.
	
	// temporary fix for acm popup module.
	$('html.com_config div#ja-acm-admin div#jatools-videos').find('a.modal').each(function(i, e) {
		var acms = $(this).attr('href');
		var acmn = acms.indexOf('?');
		acms = acms.substring(parseInt(acmn)+1);
		$(this).attr('href', ja_base_uri+'/administrator/index.php?'+acms);
	});
	
	// fix ipad calendar not close when click outsite calendar.
	$('html.com_users').on({ 'touchstart' : function(){ 
	    if (!$(event.target).closest(".calendar").length) {
	        $('.calendar').hide();
	    }
	}});
});