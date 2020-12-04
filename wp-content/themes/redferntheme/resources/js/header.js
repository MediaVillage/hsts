(function(window, $){
    var RFHeader = function(elem, options){
        this.elem = elem;
        this.$elem = $(elem);
        this.options = options;
        this.metadata = this.$elem.data();
    };

    RFHeader.prototype = {
        defaults: {
            scrollDelta: 10,
            scrollOffset: 150
        },
        init: function() {
            var scrolling = false,
                previousTop = 0,
                currentTop = 0;

            this.config = $.extend({}, this.defaults, this.options, this.metadata);

            var mainHeader = $('.site-header'),
                secondaryNavigation = $('.cd-secondary-nav'),
                //this applies only if secondary nav is below intro section
                belowNavHeroContent = $('.sub-nav-hero'),
                headerHeight = mainHeader.height();


            return this;
        }
    }

    RFHeader.defaults = RFHeader.prototype.defaults;

    $.fn.rfHeader = function(options) {        
        return this.each(function() {
            new RFHeader(this, options).init();
        });
    };

    window.RFHeader = RFHeader;
})(window, jQuery);