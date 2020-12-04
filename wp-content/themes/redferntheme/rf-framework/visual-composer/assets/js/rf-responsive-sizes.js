!(function($, window){

    var RFResponsiveSizes = function(elem, options) {
        this.elem = elem;
        this.$elem = $(elem);
        this.options = options;
        this.metadata = this.$elem.data();
    };

    RFResponsiveSizes.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.metadata);
            this.value = $.parseJSON(this.$elem.find('.wpb_vc_param_value').val()) || {};

            var that = this;
            this.$elem.find('.vc_responsive_size_field_height').on('blur', function(){
                var size = $(this).data('size');
                that.setHeight(size, $(this).val());
            });
            this.$elem.find('.vc_responsive_size_field_hidden').on('change', function(){
                var size = $(this).data('size');
                that.setHidden(size, $(this).is(':checked'));
            });
            this.$elem.find('.vc_responsive_size_field_width').on('change', function() {
                var size = $(this).data('size');
                that.setWidth(size, $(this).val());
            });

        },
        setWidth: function(key, value) {
            this.setValue(key, 'width', value);
        },
        setHeight: function(key, value) {
            this.setValue(key, 'height', value);
        },
        setHidden: function(key, value) {
            this.setValue(key, 'hidden', value);
        },
        setValue: function(key, field, value) {
            if ( key in this.value ) {
                this.value[key][field] = value;
            } else {
                this.value[key] = {};
                this.value[key][field] = value;
            }
            this.$elem.find('.wpb_vc_param_value').val( JSON.stringify(this.value) );
        }
    }

    $.fn.RFResponsiveSizes = function(options) {
        return this.each(function(){
            new RFResponsiveSizes(this, options).init();
        });
    }

    $(function(){
        $('.rf-vc-responsive-sizes').RFResponsiveSizes();
    });
})(jQuery, window);