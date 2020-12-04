<?php
/**
 * Shortcode: Icon/Image Content box
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Shortcode_Ibox extends RF_Abstract_Shortcode {

    /**
     * @var string
     */
    public static $name = 'ibox';

    /**
     * @var string
     */
    public static $shortcode = 'rf_ibox';

    /**
     * Setup the shortcode in visual composer
     */
    public static function vc_setup()
    {
        vc_map( array(
            "name"          => __( "Icon/Image Box", RF_FRAMEWORK_DOMAIN),
            "base"          => static::$shortcode,
            "category"      => _x( "Content", 'visual composer category', RF_FRAMEWORK_DOMAIN),
            "description"   => __( 'Display an icon/image with accompanying text', RF_FRAMEWORK_DOMAIN ),
            "icon"          => RFFramework()->url() . '/assets/images/logo.jpg',
            "params" => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Title', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'title',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Link', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'link'
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Display type', RF_FRAMEWORK_DOMAIN ),
                    'value' => array(
                        __( 'Icon', RF_FRAMEWORK_DOMAIN ) => 'icon',
                        __( 'Image', RF_FRAMEWORK_DOMAIN ) => 'image',
                    ),
                    'admin_label' => true,
                    'param_name' => 'display_type',
                ),
                // Total Items
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Icon library', RF_FRAMEWORK_DOMAIN ),
                    'value' => array(
                        __( 'Font Awesome', RF_FRAMEWORK_DOMAIN ) => 'fontawesome',
                    ),
                    'param_name' => 'type',
                    'description' => __( 'Select icon library.', RF_FRAMEWORK_DOMAIN ),
                    'dependency' => array(
                        'element' => 'display_type',
                        'value' => 'icon',
                    ),
                ),
                array(
                    'type' => 'iconpicker',
                    'heading' => __( 'Icon', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'icon_fontawesome',
                    'value' => 'fa fa-adjust', // default value to backend editor admin_label
                    'settings' => array(
                        'emptyIcon' => false,
                        // default true, display an "EMPTY" icon?
                        'iconsPerPage' => 4000,
                        // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value' => 'fontawesome',
                    ),
                    'description' => __( 'Select icon from library.', RF_FRAMEWORK_DOMAIN ),
                ),
                array(
                    'type' => 'iconpicker',
                    'heading' => __( 'Icon', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'icon_openiconic',
                    'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
                    'settings' => array(
                        'emptyIcon' => false, // default true, display an "EMPTY" icon?
                        'type' => 'openiconic',
                        'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value' => 'openiconic',
                    ),
                    'description' => __( 'Select icon from library.', RF_FRAMEWORK_DOMAIN ),
                ),
                array(
                    'type' => 'iconpicker',
                    'heading' => __( 'Icon', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'icon_typicons',
                    'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
                    'settings' => array(
                        'emptyIcon' => false, // default true, display an "EMPTY" icon?
                        'type' => 'typicons',
                        'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value' => 'typicons',
                    ),
                    'description' => __( 'Select icon from library.', RF_FRAMEWORK_DOMAIN ),
                ),
                array(
                    'type' => 'iconpicker',
                    'heading' => __( 'Icon', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'icon_entypo',
                    'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
                    'settings' => array(
                        'emptyIcon' => false, // default true, display an "EMPTY" icon?
                        'type' => 'entypo',
                        'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value' => 'entypo',
                    ),
                ),
                array(
                    'type' => 'iconpicker',
                    'heading' => __( 'Icon', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'icon_linecons',
                    'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
                    'settings' => array(
                        'emptyIcon' => false, // default true, display an "EMPTY" icon?
                        'type' => 'linecons',
                        'iconsPerPage' => 4000, // default 100, how many icons per/page to display
                    ),
                    'dependency' => array(
                        'element' => 'type',
                        'value' => 'linecons',
                    ),
                    'description' => __( 'Select icon from library.', RF_FRAMEWORK_DOMAIN ),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __( 'Icon Color', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'icon_colour',
                    'description' => __( 'Select custom icon color.', RF_FRAMEWORK_DOMAIN ),
                    'dependency' => array(
                        'element' => 'type',
                        'not_empty' => true
                    )
                ),
                array(
                    'type' => 'attach_image',
                    'heading' => __( 'Image', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'image',
                    'value' => '',
                    'description' => __( 'Select image from media library.', RF_FRAMEWORK_DOMAIN ),
                    'dependency' => array(
                        'element' => 'display_type',
                        'value' => 'image',
                    ),
                ),
                array(
                    'type'  => 'textfield',
                    'heading' => __( 'Image Size', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'image_size',
                    'description' => __( 'The image size to use (if blank will use \'thumbnail\')', RF_FRAMEWORK_DOMAIN),
                    'dependency' => array(
                        'element' => 'display_type',
                        'value' => 'image',
                    ),
                ),
                array(
                    'type'  => 'checkbox',
                    'heading' => __( 'Has Overlay', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'has_overlay',
                    'description' => __( 'Add an overlay over the image when hovered over', RF_FRAMEWORK_DOMAIN),
                    'dependency' => array(
                        'element' => 'display_type',
                        'value' => 'image',
                    ),
                ),
                array(
                    'type'  => 'textfield',
                    'heading' => __( 'Overlay text', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'overlay_text',
                    'dependency' => array(
                        'element' => 'has_overlay',
                        'not_empty' => true,
                    ),
                ),
                // Template
                array(
                    "type" => "dropdown",
                    'heading'   => __( 'Template', RF_FRAMEWORK_DOMAIN ),
                    "param_name" => "template",
                    'description' => __( 'The style of the item to display', RF_FRAMEWORK_DOMAIN ),
                    'value' => self::get_templates(),
                ),
                array(
                    'type' => 'textarea_html',
                    'holder' => 'div',
                    'heading' => __( 'Text', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'content',
                    'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', RF_FRAMEWORK_DOMAIN )
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Extra Class Name', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'extra_class',
                    'description' => __( 'Extra class added to the main element for custom styling', RF_FRAMEWORK_DOMAIN )
                )
            )
        ) );
    }

    /**
     * Output shortcode content
     *
     * @param $atts
     * @param null $content
     *
     * @return string|void
     */
    public static function output($atts, $content = null)
    {
        $title = $link = $display_type = $type = $icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypo = $icon_linecons = $template = $image = $image_size = $icon_colour = $has_overlay = $overlay_text = $extra_class = '';
        extract( shortcode_atts( array(
            'title'                 => '',
            'link'                  => '',
            'display_type'          => 'icon',
            'type'                  => 'fontawesome',
            'icon_fontawesome'      => 'fa fa-adjust',
            'icon_openiconic'       => 'vc-oi vc-oi-dial',
            'icon_typicons'         => 'typcn typcn-adjust-brightness',
            'icon_entypo'           => 'entypo-icon entypo-icon-note',
            'icon_linecons'         => 'vc_li vc_li-heart',
            'template'              => 'default.php',
            'image'                 => '',
            'image_size'            => 'thumbnail',
            'icon_colour'           => '',
            'has_overlay'           => false,
            'overlay_text'          => '',
            'extra_class'           => ''
        ), $atts));

        $link_data = vc_build_link($link);
        $link_url = $link_data['url'];
        $link_title = $link_data['title'];
        $link_target = $link_data['target'];

        // Determine which icon library and set to use
        switch ( $type )
        {
            case 'openiconic':
                $icon_class = $icon_openiconic;
                break;
            case 'typicons':
                $icon_class = $icon_typicons;
                break;
            case 'entypo':
                $icon_class = $icon_entypo;
                break;
            case 'linecons':
                $icon_class = $icon_linecons;
                break;
            default:
                $icon_class = $icon_fontawesome;
                break;
        }

        // Set the title
        if ( $title && $link_url ) {
            $title = sprintf('<a href="%s" title="%s" target="%s">%s</a>', $link_url, $link_title, $link_target, $title);
        }

        // Set the media icon/image output
        if ( $display_type == 'image' ) {
            $media = wp_get_attachment_image( $image, $image_size, false, array('class' => 'img-responsive') );
            $media_url = wp_get_attachment_image_url( $image, $image_size );
        } else {
            $media = '<i class="'.$icon_class.'" style="color:'.$icon_colour.';"></i>';
            $media_url = '';
        }

        if ( $link_url ) {
            $media = sprintf('<a href="%s" title="%s" target="%s">%s</a>', $link_url, $link_title, $link_target, $media);
        }

        $overlay = '';
        if ( $has_overlay ) {
            $overlay_text = ($link_url) ? sprintf('<a href="%s" title="%s" target="%s">%s</a>', $link_url, $link_title, $link_target, $overlay_text) : $overlay_text;
            $overlay = '<div class="RFIbox__overlay">'.$overlay_text.'</div>';
        }

        ob_start();
        include( self::get_template( $template ) );
        return ob_get_clean();
    }
}

new RF_Shortcode_Ibox();