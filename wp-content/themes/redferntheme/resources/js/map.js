(function($){
	class RFMap {

		defaults() {
			return {
				zoom		: 16,
				center		: new google.maps.LatLng(0, 0),
				mapTypeId	: google.maps.MapTypeId.ROADMAP,
				scrollwheel : false,
				draggable   : false,
				styles 		: [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-100},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-100},{"lightness":40}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"saturation":-10},{"lightness":30}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":-60},{"lightness":10}]},{"featureType":"landscape.natural","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":-60},{"lightness":60}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"},{"saturation":-100},{"lightness":60}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"},{"saturation":-100},{"lightness":60}]}]
			}
		}

		constructor(elem, options) {
			this.elem = elem;
			this.$elem = $(elem);
			this.config = $.extend({}, this.defaults(), options, this.$elem.data());
			this.$markers = this.$elem.find('.marker');
			this.markers = [];
		}

		setupMap() {
			// Setup the map
			this.map = new google.maps.Map( this.elem, this.mapConfig() );
			// Setup the markers
			this.setupMarkers();
			// Center the map
			this.centerMap();
		}

		mapConfig() {
			this.setControlPositions();
			return this.config;
		}

		setControlPositions() {
			var keys = ['zoomControlOptions', 'streetViewControlOptions'];
			for(var i = 0; i < keys.length; i++) {
				var key = keys[i];
                // Setup zoom control options if there is some
                if ( typeof this.config[key] !== 'undefined' ) {
                	var position = this.config[key].position;
                    this.config[key].position = google.maps.ControlPosition[position.toUpperCase()];
                }
			}
		}

		setupMarkers() {
			this.markers = [];
			var self = this;
			this.$markers.each(function(e) {
				self.addMarker( $(this) );
			});
		}

		addMarker( $marker ) {
			// var
			var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
			var image = $marker.attr('data-image') || '';

			// create marker
			var marker = new google.maps.Marker({
				position	: latlng,
				map			: this.map,
				icon		: image
			});

			// add to array
			this.markers.push( marker );

			// if marker contains HTML, add it to an infoWindow
			if( $marker.html() )
			{
				// create info window
				var infowindow = new google.maps.InfoWindow({
					content		: $marker.html()
				});

				// show info window when marker is clicked
				google.maps.event.addListener(marker, 'click', function() {
					infowindow.open( this.map, marker );
				});
			}

		}

		centerMap() {
			// vars
			var bounds = new google.maps.LatLngBounds();

			// loop through all markers and create bounds
			$.each( this.markers, function( i, marker ){
				var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
				bounds.extend( latlng );
			});

			// only 1 marker?
			if( this.markers.length == 1 ) {
				// set center of map
			    this.map.setCenter( bounds.getCenter() );
			    this.map.setZoom( this.config.zoom );
			} else {
				// fit to bounds
				this.map.fitBounds( bounds );
			}
		}
	}

	$.fn.rfMap = function(options) {
		return this.each(function() {
            if ( ! $(this).hasClass('map-loaded') ) {
                new RFMap(this, options).setupMap();
                $(this).addClass('map-loaded');
            }
		});
	};

	$(function(){
		if ( typeof google !== 'undefined' ) {
            $('.rf-map, .acf-map').rfMap();
        } else {
            console.warn('Google has not been defined, please make sure there is an API key.');
		}
	});
})(jQuery);