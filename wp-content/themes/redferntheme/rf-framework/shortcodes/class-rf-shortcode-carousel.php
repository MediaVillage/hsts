<?php
/**
 * Shortcode: Display a list of posts by given criteria in a carousel
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Shortcode_Carousel extends RF_Abstract_Shortcode {

    /**
     * @var string
     */
    public static $name = 'carousel';

    /**
     * @var string
     */
    public static $shortcode = 'rf_carousel';

    /**
     * @var int
     */
    protected static $excerpt_length = 55;

    /**
     * Initialize hooks
     */
    public static function init()
    {
        parent::init();

        // Include posts
        add_filter( 'vc_autocomplete_rf_carousel_include_callback',
            'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_rf_carousel_include_render',
            'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
        // Narrow data taxonomies
        add_filter( 'vc_autocomplete_rf_carousel_taxonomies_callback',
            'vc_autocomplete_taxonomies_field_search', 10, 1 );
        add_filter( 'vc_autocomplete_rf_carousel_taxonomies_render',
            'vc_autocomplete_taxonomies_field_render', 10, 1 );
        // Exclude
        add_filter( 'vc_autocomplete_rf_carousel_exclude_callback',
            'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_rf_carousel_exclude_render',
            'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
    }

    /**
     * Get list of carousel specific visual composer parameters
     *
     * @return [type] [description]
     */
    public static function carousel_params()
    {
        return array(
            array(
                'type' => 'carousel_items',
                'heading' => __( 'Carousel Items' ),
                'param_name' => 'carousel_items',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'devices' => array('lg','md','sm','xs'),
                'description' => __( 'This is the number of items shown based on the width of the device', RF_FRAMEWORK_DOMAIN ),
                'value' => json_encode(array(
                    'lg' => 3,
                    'md' => 3,
                    'sm' => 2,
                    'xs' => 1,
                ))
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Slide by', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'slide_by',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'description' => __( 'Number of items to slide by', RF_FRAMEWORK_DOMAIN ),
                'value' => 1
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Margin', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'margin',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'description' => __( 'margin-right(px) on item.', RF_FRAMEWORK_DOMAIN ),
                'value' => 0
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Loop', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'loop',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'description' => __( 'Loop through slides once they have completed', RF_FRAMEWORK_DOMAIN ),
                'value' => array( __( 'Yes', RF_FRAMEWORK_DOMAIN ) => 'true' )
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Center', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'center',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'description' => __( 'Center item. Works well with even an odd number of items.', RF_FRAMEWORK_DOMAIN ),
                'value' => array( __( 'Yes', RF_FRAMEWORK_DOMAIN ) => 'true' )
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Stage Padding', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'stage_padding',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'description' => __( 'Padding left and right on stage (can see neighbours).', RF_FRAMEWORK_DOMAIN ),
                'value' => 0
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Navigation buttons', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'nav',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'description' => __( 'Show next/prev buttons.', RF_FRAMEWORK_DOMAIN ),
                'value' => array( __( 'Yes', RF_FRAMEWORK_DOMAIN ) => 'true' )
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Pagination', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'dots',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'description' => __( 'Show pagination dots.', RF_FRAMEWORK_DOMAIN ),
                'value' => array( __( 'Yes', RF_FRAMEWORK_DOMAIN ) => 'true' )
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Autoplay', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'autoplay',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'description' => __( 'Auto cycle through items.', RF_FRAMEWORK_DOMAIN ),
                'value' => array( __( 'Yes', RF_FRAMEWORK_DOMAIN ) => 'true' )
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Autoplay interval', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'autoplay_timeout',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'description' => __( 'The time between transitions', RF_FRAMEWORK_DOMAIN ),
                'value' => 5000,
                'dependency' => array(
                    'element' => 'autoplay',
                    'not_empty' => true,
                )
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'After Visual Composer', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'after_vc',
                'group' => __( 'Carousel Settings', RF_FRAMEWORK_DOMAIN ),
                'description' => __( 'Initialise after visual composer has set full width', RF_FRAMEWORK_DOMAIN ),
                'value' => array( __( 'Yes', RF_FRAMEWORK_DOMAIN ) => 'true' )
            ),
        );
    }

    /**
     * Setup the shortcode in visual composer
     */
    public static function vc_setup()
    {
        vc_map( array(
            'name'          => __( 'Carousel', RF_FRAMEWORK_DOMAIN),
            'base'          => static::$shortcode,
            'category'      => _x( 'Content', 'visual composer category', RF_FRAMEWORK_DOMAIN),
            'description'   => __( 'Display posts by given criteria in a carousel', RF_FRAMEWORK_DOMAIN ),
            'icon'          => RFFramework()->url() . '/assets/images/logo.jpg',
            'params' => array_merge( array(
                array(
                    'type'  => 'textfield',
                    'heading'   => __( 'Title', RF_FRAMEWORK_DOMAIN ),
                    'param_name'    => 'title',
                    'admin_label'   => true
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'ID', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'element_id',
                    'admin_label' => true,
                    'description' => __( 'Set a bespoke ID so that this specific shortcode can be modified', RF_FRAMEWORK_DOMAIN )
                )
            ),
                // Post Options
                self::post_options(),
                // Carousel settings
                self::carousel_params()
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
        $title = $template = $data_source = $post_types = $max_items = $taxonomies = $taxonomy_relation = $offset = $order = $orderby = $include = $exclude = $meta_key = '';
        $carousel_items = $slide_by = $margin = $loop = $center = $stage_padding = $nav = $dots = $autoplay = $autoplay_timeout = '';
        $image_size = $extra_class = $excerpt_length = $element_id = '';
        extract( shortcode_atts(array(
            'title'     => '',
            'data_source' => 'post',
            'post_types' => 'post',
            'taxonomies' => '',
            'taxonomy_relation' => 'OR',
            'include'   => '',
            'max_items' => '',
            'template' => 'default.php',
            'orderby'   => 'date',
            'order'     => 'DESC',
            'meta_key'  => '',
            'offset'    => '',
            'exclude'   => '',
            'image_size' => 'thumbnail',
            'extra_class' => '',
            'excerpt_length' => '',
            'element_id' => '',

            'carousel_items' => array(
                'lg' => 3,
                'md' => 3,
                'sm' => 2,
                'xs' => 1
            ),
            'items' => 1,
            'slide_by' => 1,
            'margin' => 0,
            'loop'  => 'false',
            'center' => 'false',
            'stage_padding' => 0,
            'nav' => 'false',
            'dots' => 'false',
            'autoplay' => 'false',
            'autoplay_timeout' => 5000,
            'after_vc' => 'false'
        ), $atts ) );

        $args = array(
            'post_status'       => 'publish',
            'posts_per_page'    => $max_items,
            'orderby'           => $orderby,
            'meta_key'          => $orderby === 'meta_value' ? $meta_key : '',
            'order'             => $order,
            'offset'            => $offset,
            'ignore_sticky_posts' => true
        );

        // Include only set ids
        if ( $data_source == 'ids' ) {
            $ids = array_map('intval',explode(',', trim($include)));
            $args['posts_per_page'] = -1;
            $args['post_type'] = 'any';
            $args['post__in'] = $ids;
        } else {
            $args['post_type'] = self::commaListToArray($post_types);
        }

        // Exclude any ids
        if ( $exclude ) {
            $args['post__not_in'] = array_map('intval',explode(',', trim($exclude)));
        }

        // Filter by taxonomy
        if ( $taxonomies ) {
            $vc_taxonomies_types = get_taxonomies( array( 'public' => true ) );
            $terms = get_terms( array_keys( $vc_taxonomies_types ), array(
                'hide_empty' => false,
                'include' => $taxonomies,
            ) );
            $args['tax_query'] = array();
            $tax_queries = array(); // List of taxnonimes
            foreach ( $terms as $t ) {
                if ( ! isset( $tax_queries[ $t->taxonomy ] ) ) {
                    $tax_queries[ $t->taxonomy ] = array(
                        'taxonomy' => $t->taxonomy,
                        'field' => 'id',
                        'terms' => array( $t->term_id ),
                        'relation' => 'IN'
                    );
                } else {
                    $tax_queries[ $t->taxonomy ]['terms'][] = $t->term_id;
                }
            }
            $args['tax_query'] = array_values( $tax_queries );
            $args['tax_query']['relation'] = $taxonomy_relation;
        }

        // Convert items to json string
        $items = (is_array($carousel_items)) ? json_encode($carousel_items) : str_replace('``','"', $carousel_items);

        // Setup the carousel element
        $carousel = (new RF_Element_Post_Carousel($element_id))
                ->query($args)
                ->carousel_settings(compact(
                    'items', 'slide_by', 'margin', 'loop', 'center', 'stage_padding',
                    'nav', 'dots', 'autoplay', 'autoplay_timeout', 'after_vc'
                ))
                ->options(compact('title', 'image_size', 'extra_class'))
                ->template($template);

        // Set the excerpt length
        if ( $excerpt_length ) {
            $carousel->excerpt_length($excerpt_length);
        }

        return $carousel->render(false);
    }
}