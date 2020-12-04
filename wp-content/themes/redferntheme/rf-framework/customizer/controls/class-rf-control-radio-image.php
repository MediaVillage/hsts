<?php
/**
 * Define control to allow radio selection using images
 *
 * @author Red Fern
 * @version 1.0.0
 */
defined( 'ABSPATH' ) or die;

if ( class_exists( 'WP_Customize_Control' ) ) {

    /**
     * Class RF_Control_Radio_Image
     */
    class RF_Control_Radio_Image extends WP_Customize_Control {

        /**
         * @var string
         */
        public $type = 'rf_radio_image';

        /**
         * @param WP_Customize_Manager $manager
         * @param string $id
         * @param array $args
         */
        public function __construct($manager, $id, $args = array()) {
            $this->id = $id;
            $this->args = $args;

            parent::__construct($manager, $id, $args);
        }

        /**
         * Render the form element
         */
        protected function render_content(){}

        /**
         * Refresh the parameters passed to the JavaScript via JSON.
         *
         * @since 3.4.0
         * @since 4.2.0 Moved from WP_Customize_Upload_Control.
         *
         * @see WP_Customize_Control::to_json()
         */
        public function to_json() {
            parent::to_json();

            foreach ( $this->choices as $value => $args )
                $this->choices[ $value ]['url'] = esc_url( sprintf( $args['url'], RFFramework()->url() . '/assets' ) );

            $this->json['choices'] = $this->choices;
            $this->json['link']    = $this->get_link();
            $this->json['value']   = $this->value();
            $this->json['id']      = $this->id;
        }

        /**
         * Output the JS version of the template
         */
        public function content_template() {           
            ?>
            <# if ( ! data.choices ) {
                return;
            } #>

            <# if ( data.label ) { #>
                <span class="customize-control-title">{{ data.label }}</span>
            <# } #>

            <# if ( data.description ) { #>
                <span class="description customize-control-description">{{{ data.description }}}</span>
            <# } #>

            <div class="buttonset">
                <# for ( key in data.choices ) { #>
                    <input type="radio" value="{{ key }}" name="_customize-{{ data.type }}-{{ data.id }}" id="{{ data.id }}-{{ key }}" {{{ data.link }}} <# if ( key === data.value ) { #> checked="checked" <# } #> />
                    <label for="{{ data.id }}-{{ key }}">
                        <span class="screen-reader-text">{{ data.choices[ key ]['label'] }}</span>
                        <img src="{{ data.choices[ key ]['url'] }}" alt="{{ data.choices[ key ]['label'] }}" />
                    </label>
                <# } #>
            </div><!-- .buttonset -->
            <?php
        }

    }

}

