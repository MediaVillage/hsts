(function($){

	var RFMenu = function(elem, options){
		this.elem = elem;
		this.$elem = $(elem);
		this.options = options;
		this.metadata = this.$elem.data();
	};

	RFMenu.prototype = {
		init: function() {
			this.config = $.extend({}, this.defaults, this.options, this.metadata);

			this.addEventListeners();

			return this;
		},
		addEventListeners: function() {
			$('#menu-toggle').on('click', this.toggle.bind(this));
			//$('.menu-close').on('click', this.close.bind(this));
			this.$elem.on('click', '.menu-expand', this.toggleSubmenu);
			$(window).resize(this.onResize.bind(this));
		},

		toggle: function() {
			this.$elem.toggleClass('open');
			$('#menu-toggle').toggleClass('open');
			$('#page').toggleClass('menu-open');
		},
		// close: function() {
		// 	this.$elem.removeClass('open');
		// },
		toggleSubmenu: function() {
			$(this).parent().toggleClass('open');
			$(this).next().slideToggle();
		},

		onResize: function() {
            this.$elem.removeClass('open');
            this.$elem.find('.sub-menu').css('display', '');
            $('#menu-toggle').removeClass('open');
            $('#page').removeClass('menu-open');
        }

	}

	$.fn.rFMenu = function(options) {
		return this.each(function() {
			new RFMenu(this, options).init();
		});
	};

	$(function(){
		$('#site-navigation').rFMenu();
	});
})(jQuery);