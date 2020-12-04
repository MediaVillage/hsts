require('owl.carousel');

(function($){
    var RFCarousel = function(elem, options) {
        this.elem = elem;
        this.$elem = $(elem);
        this.options = options;
        this.metadata = this.$elem.data();
    };

    RFCarousel.prototype = {
        sizes: {
            lg: 1200,
            md: 992,
            sm: 768,
            xs: 0
        },
        defaults: {
            slideBy: 1,
            margin: 0,
            loop: false,
            center: false,
            stagePadding: 0,
            nav: false,
            dots: false,
            autoplay: false,
            autoplayTimeout: 5000,
            afterVc: false,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 4
                }
            }
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.metadata);
            this.initializeItems(this.config.items);

            if ( this.config.afterVc ) {
                $(document).on('vc-full-width-row', this.setupCarousel.bind(this));
            } else {
                this.setupCarousel();
            }
        },
        initializeItems: function(items) {
            var size_items = [];
            $.each(items, function(size, num_items){
                var size_val = this.sizes[size];
                size_items.push(parseInt(num_items));
                this.config.responsive[size_val]['items'] = parseInt(num_items);
            }.bind(this));

            // If the number of items to display is the same across all devices
            // then remove responsive setting and add base items config
            var unique_items = size_items.filter(function(elem, pos,arr) {
                return arr.indexOf(elem) == pos;
            });
            if ( unique_items.length == 1 ) {
                delete this.config.responsive;
                this.config.items = unique_items[0];
            }
        },
        setupCarousel: function() {
            this.$elem.owlCarousel(this.config);
        }
    }

    $.fn.rfCarousel = function(options) {
        return this.each(function(){
            new RFCarousel(this, options).init();
        });
    }

    window.RFCarousel = RFCarousel;

    $(function(){
        $('.RFCarousel').rfCarousel();
    });
})(jQuery);