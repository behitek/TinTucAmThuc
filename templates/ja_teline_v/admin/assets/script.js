(function ($) {
    var jaTools = {};
    jaTools.getVal = function (elem) {
        var $elem = $(elem),
            name = $elem.prop('name'),
            type = $elem.attr('type');

        if (type == 'checkbox') {
            return $elem.prop ('checked');
        }
        if (type == 'radio' && !$elem.prop('checked')) return undefined;

        return $elem.val();
    };

    jaTools.setVal = function ($elem, value, $parent) {
        if (!$elem.length) return;
        var type = $elem.attr('type'),
            tag = $elem.prop('tagName');
        if (type == 'radio') {
            $parent.find ('[name="' + $elem.prop('name') + '"]').prop('checked', false).filter('[value="' + value + '"]').prop('checked', true);
            // $elem.removeAttr('checked').filter('[value="' + value + '"]').prop('checked', true);
        } else if (type == 'checkbox') {
            $elem.prop('checked', value);
        } else if (tag == 'TEXTAREA') {
            $elem.val(value);
        } else if (tag == 'SELECT') {
            $elem.val(value);
            if ($elem.val() != value) {
                $elem.val($elem.find('option:first').val());
            }
        } else {
            $elem.val(value);
        }
    };


    jaTools.getJSon = function (str) {
        var result = {};
        try {
            result = JSON.parse(str.trim());
        } catch (e) {
            return {};
        }
        return $.isPlainObject(result) ? result : {};
    };


    var SubLayout = function (element) {
        var $element = this.$element = $(element);
        this.$styles = this.$element.find('.sublayout-styles');
        console.log (this.$styles);
        this.$config = this.$element.find('.sublayout-config');

        // bind switch style event
        this.$styles.on('change', this.switchStyle.bind(this));

        // build Form
        this.bindData();

        // store
        $element.data('sublayout-object', this);

        // trigger updated event for element after built
        setTimeout(function(){$element.trigger('updated')}, 100);
    };

    SubLayout.prototype.switchStyle = function() {
        // find active style
        var style = this.$styles.val();
        this.$activeForm = this.$element.find('.sublayout-form-' + style);
        this.$element.find ('.sublayout-form').addClass('hide');
        this.$activeForm.removeClass ('hide');
    };

    SubLayout.prototype.storeData = function () {
        this.$config.val(JSON.stringify(this.getData()));
    };

    SubLayout.prototype.getData = function () {
        if (!this.$activeForm || !this.$activeForm.length) return {};

        var $items = this.$activeForm.find ('input, textarea, select'),
            result = {};

        // get style value
        result['style'] = this.$styles.val();
        result['sublayout'] = this.$element.find('[name="sublayout"]').val();

        $items.each (function () {
            var $this = $(this),
                match = $this.prop('name').match(/\[([^\]]*)\]$/),
                name = match ? match[1] : $this.prop('name'),
                value = jaTools.getVal ($this);

            if (!$this.data('ignoresave') && name && value != undefined) {
                result[name] = value;
            }
        });

        return result;
    };

    SubLayout.prototype.bindData = function () {
        var data = jaTools.getJSon(this.$config.val()),
            $activeForm = this.$activeForm;
        if (!data || !data['style']) return ;
        this.$styles.val (data['style']);

        // call switch style to show form
        this.switchStyle();

        // update form value
        var $items = this.$activeForm.find ('input, textarea, select');

        $items.each (function () {
            var $this = $(this),
                match = $this.prop('name').match(/\[([^\]]*)\]$/),
                name = match ? match[1] : $this.prop('name'),
                value = name ? data[name] : undefined;

            if (value != undefined) {
                jaTools.setVal ($this, value, $activeForm);
            }
        });

    };

    function Plugin() {
        return new SubLayout(this);
    }

    $.fn.sublayoutInit              = Plugin;
    $.fn.sublayoutInit.Constructor  = SubLayout;

    $(document).ready(function(){
        // bind submit event for form
        document.adminForm.onsubmit = function () {
            $('.sublayout-group').each (function(){
                var $obj = $(this).data('sublayout-object');
                if ($obj) {
                    $obj.storeData();
                }
            })
        };
    })
})(jQuery);

