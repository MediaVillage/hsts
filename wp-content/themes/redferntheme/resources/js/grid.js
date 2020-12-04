var Isotope = require('isotope-layout');
var imagesLoaded = require('imagesloaded');

(function($){

    var RFGrid = function(elem, options) {
        this.elem = elem;
        this.$elem = $(elem);
        this.options = options;
        this.metadata = this.$elem.data();
    };

    RFGrid.prototype = {
        defaults: {
            layout: 'fitRows'
        },
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.metadata);

            this.legacyCheck();

            this.$elem.find('[data-filter]').on('click', this.filterGrid.bind(this));
            this.$currentFilter = this.$elem.find('[data-filter="*"]');

            this.setupGrid();
        },
        legacyCheck: function() {
            var child = this.$elem.children('.RFPostGrid__sizer').first();
            this.$items = (child.length) ? this.$elem : this.$elem.find('.RFPostsGrid__items');
        },
        setupGrid: function() {
            imagesLoaded( this.elem, () => {
                var gridSettings = $.extend({}, {
                    itemSelector: '.RFPostsGrid__item',
                    percentPosition: true,
                }, this.layoutSettings());

                this.iso = new Isotope( this.$items[0], gridSettings);
            });
        },
        layoutSettings: function() {
            var data = { layoutMode: this.config.layout };
            if ( this.config.layout == 'masonry') {
                data.masonry = {
                    columnWidth: '.RFPostsGrid__sizer'
                }
            }
            return data;
        },
        filterGrid: function(e) {
            e.preventDefault();
            if ( this.$currentFilter ) this.$currentFilter.removeClass('active');
            $(e.target).addClass('active');
            this.iso.arrange({ filter: $(e.target).data('filter') });
            this.$currentFilter = $(e.target);
        }
    };

    $.fn.rfGrid = function() {
        return this.each(function(){
            new RFGrid( $(this)[0] ).init();
        });
    }


    $(function(){
        $('.RFPostsGrid').rfGrid();
    });
})(jQuery);