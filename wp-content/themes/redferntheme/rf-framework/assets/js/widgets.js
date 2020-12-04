(function($){
    $(function() {
        rf_widget_initialize('', 'loaded');

        $(document).on('widget-updated', function(event, widget) {
            rf_widget_initialize( widget, 'updated' );
        });
        $(document).on('widget-added', function(event, widget) {
            rf_widget_initialize( widget, 'added' );
        });

        function rf_widget_initialize(widget, task) {
            if ( widget ) {
                widget.find('.widget-tabs').tabs();
            } else {
                $('.widget-tabs').each(function(){
                   $(this).tabs();
                });
            }
        }
    });
})(jQuery);