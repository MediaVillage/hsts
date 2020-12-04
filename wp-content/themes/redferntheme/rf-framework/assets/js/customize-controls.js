(function(api, $) {
    /**
     * Radio Image Control
     */
    api.controlConstructor.rf_radio_image = api.Control.extend({
        ready: function() {
            var control = this;
            this.container.on('change', 'input[type="radio"]',
                function() {
                    control.setting.set( $(this).val() );
                }
            );
        }
    });

})(wp.customize, jQuery);