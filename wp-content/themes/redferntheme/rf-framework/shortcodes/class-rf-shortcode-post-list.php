<?php
/**
 * Shortcode: Post Grid
 *
 * @author  Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class RF_Shortcode_Post_List extends RF_Abstract_Shortcode {

    /**
     * @var string
     */
    public static $name = 'post_list';

    /**
     * @var string
     */
    public static $shortcode = 'rf_post_list';

    /**
     * Initialise wordpress hooks for the shortcode
     */
    public static function init()
    {
        parent::init();

        add_filter( 'vc_autocomplete_rf_post_list_include_callback',
            'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_rf_post_list_include_render',
            'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
        // Narrow data taxonomies
        add_filter( 'vc_autocomplete_rf_post_list_taxonomies_callback',
            'vc_autocomplete_taxonomies_field_search', 10, 1 );
        add_filter( 'vc_autocomplete_rf_post_list_taxonomies_render',
            'vc_autocomplete_taxonomies_field_render', 10, 1 );
        // Exclude
        add_filter( 'vc_autocomplete_rf_post_list_exclude_callback',
            'vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_rf_post_list_exclude_render',
            'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
    }

    /**
     * Setup the shortcode in Visual Composer
     */
    public static function vc_setup()
    {
        vc_map( array(
            'name'			=> __( 'Post List', RF_FRAMEWORK_DOMAIN ),
            'base'  		=> self::$shortcode,
            'description' 	=> __( 'Output a list of posts in a grid', RF_FRAMEWORK_DOMAIN ),
            'category'		=> __( 'Red Fern', RF_FRAMEWORK_DOMAIN ),
            'icon'          => RFFramework()->url() . '/assets/images/logo.jpg',
            'params' 		=> array_merge( array(
                array(
                    'type'  => 'textfield',
                    'heading'   => __( 'Title', RF_FRAMEWORK_DOMAIN ),
                    'param_name'    => 'title',
                    'admin_label'   => true,
                    'weight'        => 99
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'ID', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'element_id',
                    'admin_label' => true,
                    'description' => __( 'Set a bespoke ID so that this specific shortcode can be modified', RF_FRAMEWORK_DOMAIN )
                )
            ), self::post_options() )
        ) );
    }

    /**
     * Output the shortcode
     *
     * @param  array $atts
     * @param  string $content
     * @return string
     */
    public static function output($atts, $content = null)
    {
        $title = $template = $data_source = $post_types = $max_items = $taxonomies = $taxonomy_relation = $offset = $order = $orderby = $include = $exclude = $meta_key = '';
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
            'element_id' => ''
        ), $atts ) );

        // Get the current page number
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

        $args = array(
            'post_status'       => 'publish',
            'posts_per_page'    => $max_items,
            'orderby'           => $orderby,
            'meta_key'          => $orderby === 'meta_value' ? $meta_key : '',
            'order'             => $order,
            'offset'            => $offset,
            'ignore_sticky_posts' => true,
            'paged'             => $paged
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

        // Load the post list element
        $post_list = (new RF_Element_Post_List($element_id))
                        ->query($args)
                        ->options(compact('title', 'image_size', 'extra_class'))
                        ->template($template);

        if ( $excerpt_length ) {
            $post_list->excerpt_length($excerpt_length);
        }

        return $post_list->render(false);
    }

    /**
     * Set the excerpt length of the shortcode to the length set in the shortcode atts
     *
     * @param $length
     * @return int
     */
    public static function set_excerpt_length( $length )
    {
        return self::$excerpt_length;
    }
}