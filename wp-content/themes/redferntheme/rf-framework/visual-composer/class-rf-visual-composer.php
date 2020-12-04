<?php
/**
 * Define custom options for Visual Composer
 *
 * @author Red Fern
 * @version 1.0.0
 */
defined( 'ABSPATH' ) or die;

// Check if Visual Composer is enabled
if ( function_exists( 'vc_map' ) ) {

    class RF_Visual_Composer {

        /**
         * Initialize custom options
         */
        public static function init() {
            vc_add_shortcode_param(
                'image_select',
                array( __CLASS__, 'image_select_field' ),
                RFFramework()->url() . '/visual-composer/assets/js/rf-image-select.js'
            );
            vc_add_shortcode_param(
                'responsive_sizes',
                array( __CLASS__, 'param_responsive_sizes' ),
                RFFramework()->url() . '/visual-composer/assets/js/rf-responsive-sizes.js'
            );
            vc_add_shortcode_param(
                'carousel_items',
                array( __CLASS__, 'param_carousel_items' ),
                RFFramework()->url() . '/visual-composer/assets/js/rf-carousel-items.js'
            );
            vc_add_shortcode_param(
                'json',
                array( __CLASS__, 'param_json' ),
                RFFramework()->url() . '/visual-composer/assets/js/rf-json-field.js'
            );
        }

        /**
         * Add custom field to visual composer
         *
         * @param $settings
         * @param $value
         *
         * @return string
         */
        public static function image_select_field( $settings, $value ) {
            ob_start();
            ?>
            <div class="rf-vc-image-select">
                <?php if ( ! empty( $settings['value'] ) ): ?>
                    <?php foreach( $settings['value'] as $image => $template ): ?>
                        <div class="rf-vc-image-option <?php echo ( $value == $template ) ? 'selected' : ''; ?>" data-template="<?php echo $template; ?>">
                            <img src="<?php echo $image; ?>" alt=""/>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <input type="hidden" name="<?php echo esc_attr( $settings['param_name'] ); ?>" class="wpb_vc_param_value <?php echo esc_attr( $settings['param_name'] ); ?> <?php echo esc_attr( $settings['type'] ); ?>_field"
                       value="<?php echo esc_attr( $value ); ?>"/>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * Set different sizes based on the device
         *
         * @param $settings
         * @param $value
         * @return string
         */
        public static function param_responsive_sizes( $settings, $value ) {
            $grid_cols = array(
                '6' => 2,
                '4' => 3,
                '3' => 4,
                '2' => 6,
                '1' => 12,
            );

            $has_width = (is_array($settings['fields']) && in_array('width', $settings['fields']) );
            $has_height = (is_array($settings['fields']) && in_array('height', $settings['fields']) );
            $has_hidden = (is_array($settings['fields']) && in_array('hidden', $settings['fields']) );

            $values = json_decode($value);
            ob_start();
            ?>
            <div class="rf-vc-responsive-sizes">
                <input type="hidden" name="<?php echo esc_attr( $settings['param_name'] ); ?>"
                       class="wpb_vc_param_value <?php echo esc_attr( $settings['param_name'] ); ?>"
                       value="<?php echo esc_attr($value); ?>"/>
                <table class="vc_table vc_column-offset-table">
                    <tbody>
                    <tr>
                        <th>Device</th>
                        <?php if($has_width): ?><th>Width</th><?php endif; ?>
                        <?php if($has_height): ?><th>Height</th><?php endif; ?>
                        <?php if($has_hidden): ?><th>Hide on device?</th><?php endif; ?>
                    </tr>
                    <?php foreach($settings['devices'] as $size): ?>
                        <tr class="vc_size-<?php echo $size; ?>">
                            <td class="vc_screen-size vc_screen-size-<?php echo $size; ?>">
                                <span class="vc_icon"></span>
                            </td>

                            <?php if ( $has_width ): ?>
                                <td>
                                    <select name="vc_responsive_width_<?php echo $size; ?>_size" class="vc_responsive_size_field_width" data-size="<?php echo $size; ?>">
                                        <?php $width = (isset($values->$size->width)) ? $values->$size->width : ''; ?>
                                        <?php foreach($grid_cols as $label => $col): ?>
                                            <option value="<?php echo $col; ?>" <?php selected($col, $width); ?>><?php echo $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="vc_description vc_clearfix">Number of elements per row</span>
                                </td>
                            <?php endif; ?>

                            <?php if ( $has_height ): ?>
                                <td>
                                    <input name="vc_responsive_height_<?php echo $size; ?>_size" class="vc_responsive_size_field_height" data-size="<?php echo $size; ?>"
                                           value="<?php if (isset($values->$size->height)) { echo $values->$size->height;  } ?>"/>
                                </td>
                            <?php endif; ?>

                            <?php if ( $has_hidden ): ?>
                                <td>
                                    <label>
                                        <input type="checkbox" name="vc_hidden-<?php echo $size; ?>" value="yes" class="vc_responsive_size_field_hidden" data-size="<?php echo $size; ?>"
                                               <?php if (isset($values->$size->hidden) && (int)$values->$size->hidden): ?>checked<?php endif; ?>>
                                    </label>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * Param: Allows selecting the breakpoints for the number of items in the carousel based on the width
         *
         * @param $settings
         * @param $value
         * @return string
         */
        public function param_carousel_items( $settings, $value )
        {
            $values = json_decode($value);

            ob_start();
            ?>
            <div class="rf-vc-carousel-items">
                <input type="hidden" name="<?php echo esc_attr( $settings['param_name'] ); ?>"
                       class="wpb_vc_param_value <?php echo esc_attr( $settings['param_name'] ); ?>"
                       value="<?php echo esc_attr($value); ?>"/>
                <table class="vc_table vc_column-offset-table">
                    <tbody>
                    <tr>
                        <th>Device</th>
                        <th>Num Items</th>
                    </tr>
                    <?php foreach($settings['devices'] as $size): ?>
                        <tr class="vc_size-<?php echo $size; ?>">
                            <td class="vc_screen-size vc_screen-size-<?php echo $size; ?>">
                                <span class="vc_icon"></span>
                            </td>
                            <td>
                                <input type="text" name="vc_carousel_items_<?php echo $size; ?>_size" class="vc_carousel_items"
                                       data-size="<?php echo $size; ?>" value="<?php echo $values->$size; ?>"/>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
            return ob_get_clean();
        }

        /**
         * Param: Reformats added text to minify json for use with elements such as snazzy maps etc.
         * 
         * @param  array $settings
         * @param  string $value
         */
        public function param_json( $settings, $value )
        {
            ob_start();
            ?>
            <div class="rf-vc-json-field">
                <input type="hidden" name="<?php echo esc_attr($settings['param_name'] ); ?>"
                    class="wpb_vc_param_value <?php echo esc_attr( $settings['param_name'] ); ?>"
                    value="<?php echo esc_attr($value); ?>"/>
                <textarea class="json_param" rows="10"><?php echo esc_attr($value); ?></textarea>
            </div>
            <?php
            return ob_get_clean();
        }
    }

    RF_Visual_Composer::init();
}