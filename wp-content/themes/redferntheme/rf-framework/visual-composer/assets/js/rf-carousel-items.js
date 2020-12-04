(function($){
    var RFVCCarouselItems = function(elem, options) {
        this.elem = elem;
        this.$elem = $(elem);
        this.options = options;
        this.metadata = this.$elem.data();
    };

    RFVCCarouselItems.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.metadata);
            this.value = $.parseJSON(this.$elem.find('.wpb_vc_param_value').val()) || {};

            var that = this;
            this.$elem.find('.vc_carousel_items').keyup(function(){
                var size = $(this).data('size');
                that.setItems(size, $(this).val());
            });
        },
        setItems: function(size, value) {
            this.value[size] = value;
            this.$elem.find('.wpb_vc_param_value').val( JSON.stringify(this.value) );
        }
    }

    $.fn.rfVCCarouselItems = function(options) {
        return this.each(function(){
            new RFVCCarouselItems(this, options).init();
        });
    }

    window.RFVCCarouselItems = RFVCCarouselItems;

    $(function(){
        $('.rf-vc-carousel-items').rfVCCarouselItems();
    });
})(jQuery);