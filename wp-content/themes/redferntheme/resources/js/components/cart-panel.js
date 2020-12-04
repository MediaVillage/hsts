(function($){

    var CartPanel = function(elem, options) {
        this.elem = elem;
        this.$elem = $(elem);
        this.options = options;
        this.metadata = this.$elem.data();
    };

    CartPanel.prototype = {
        defaults: {},
        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.metadata);

            this.$overlay = $('.shop-overlay');

            this.addEventListeners();
        },
        addEventListeners: function() {
            $(document).on('click', '.cart-link', this.toggle.bind(this));
            $(document).on('click', '.shop-overlay, .shop-panel-close', this.hide.bind(this));
            //$(document).bind('added_to_cart', this.addedToCart.bind(this));
            $(document).on('click', '.remove-product', this.removeProduct.bind(this));
        },
        toggle: function(e) {
            e.preventDefault();
            this.$overlay.toggleClass('show');
            $('#page').toggleClass('cart-show');
        },
        show: function(e) {
            e.preventDefault();
            this.$overlay.addClass('show');
            $('#page').addClass('cart-show');
        },
        hide: function(e) {
            e.preventDefault();
            this.$overlay.removeClass('show');
            $('#page').removeClass('cart-show');
        },
        addedToCart: function(e) {
            this.show(e);
        },
        removeProduct: function(e) {
            e.preventDefault();

            var cart_item_key = $(e.target).attr('data-cart-item');

            // Remove the item from the cart
            var data = { action: 'rftheme_remove_from_cart', cart_item: cart_item_key };
            $.post(rftheme.ajaxurl, data, function(response) {
                var cartTotal = response.amount;
                // Update cart total
                $('.shop-panel__total .amount').replaceWith(cartTotal);
                // Update number indicator
                $('.cart-count').text(response.quantity);
                // Remove the item from the panel
                $('.cart-product-' + cart_item_key).remove();
            });
        }
    }

    $.fn.CartPanel = function(options) {
        return this.each(function(){
            new CartPanel(this, options).init();
        });
    }

    $(function(){
       new CartPanel(document.querySelector('.shop-panel')).init();
    });
})(jQuery);