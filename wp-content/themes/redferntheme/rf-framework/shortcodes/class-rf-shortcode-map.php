<?php
/**
 * Shortcode: Display a Google Map with a marker
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Shortcode_Map extends RF_Abstract_Shortcode {

    /**
     * @var string
     */
    public static $name = 'map';

    /**
     * @var string
     */
    public static $shortcode = 'rf_map';

    /**
     * Get list of data source options
     *
     * @return array
     */
    protected static function data_source_options()
    {
        return array(
            array( 'post', __( 'Posts', RF_FRAMEWORK_DOMAIN ) ),
            array( 'ids', __( 'List of IDs', RF_FRAMEWORK_DOMAIN ) ),
            array( 'single', __( 'Single Marker', RF_FRAMEWORK_DOMAIN ) ),
        );
    }

    /**
     * Get list of marker templates
     *
     * @return array
     */
    protected static function get_marker_templates()
    {
        $options = [];

        // Find any default templates in the framework
        $default_path = self::get_default_template_path() . '/markers';
        if ( file_exists( $default_path ) ) {
            foreach(glob( "{$default_path}/*.php") as $filepath) {
                $filename = basename($filepath);
                $options[$filename] = $filename;
            }
        }

        // Get any custom templates from the theme
        $template_path = self::get_theme_overwrite_path() . 'markers';
        foreach(glob( "{$template_path}/*.php") as $filepath) {
            $filename = basename($filepath);
            $options[$filename] = $filename;
        }
        
        return $options;
    }

    /**
     * Setup the shortcode in visual composer
     */
    public static function vc_setup()
    {
        vc_map( array(
            'name'          => __( 'Map', RF_FRAMEWORK_DOMAIN),
            'base'          => static::$shortcode,
            'category'      => _x( 'Content', 'visual composer category', RF_FRAMEWORK_DOMAIN),
            'description'   => __( 'Display a Google Map', RF_FRAMEWORK_DOMAIN ),
            'icon'          => RFFramework()->url() . '/assets/images/logo.jpg',
            'params' => array_merge( static::post_options(), array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Field name', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'location_field_name',
                    'description' => __( 'The field name used in ACF which contains the map coordinates', RF_FRAMEWORK_DOMAIN ),
                    'value' => 'address',
                    'group' => __( 'Marker', RF_FRAMEWORK_DOMAIN ),
                    'dependency' => array(
                        'element' => 'data_source',
                        'value' => array( 'post', 'ids' ),
                    )
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Post Marker Image Field', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'post_marker_image',
                    'description' => __( 'The field name used for the marker image in the post (uses default marker if not set)', RF_FRAMEWORK_DOMAIN ),
                    'group' => __( 'Marker', RF_FRAMEWORK_DOMAIN ),
                    'dependency' => array(
                        'element' => 'data_source',
                        'value' => array( 'post', 'ids' ),
                    )
                ),
                array(
                    "type" => "dropdown",
                    'heading'   => __( 'Info Window Template', RF_FRAMEWORK_DOMAIN ),
                    "param_name" => "marker_template",
                    'description' => __( 'The template used for the info window of each marker', RF_FRAMEWORK_DOMAIN ),
                    'group' => __( 'Marker', RF_FRAMEWORK_DOMAIN ),
                    'value' => self::get_marker_templates(),
                ),
            	array(
            		'type' => 'textfield',
            		'heading' => __( 'Width', RF_FRAMEWORK_DOMAIN ),
            		'param_name' => 'width',
            		'value' => '100%',
                    'group' => __( 'Map Settings', RF_FRAMEWORK_DOMAIN ),
        		),
        		array(
            		'type' => 'textfield',
            		'heading' => __( 'Height', RF_FRAMEWORK_DOMAIN ),
            		'param_name' => 'height',
            		'value' => '300px',
                    'group' => __( 'Map Settings', RF_FRAMEWORK_DOMAIN ),
                ),
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Zoom', RF_FRAMEWORK_DOMAIN ),
        			'param_name' => 'zoom',
        			'value' => '16',
                    'group' => __( 'Map Settings', RF_FRAMEWORK_DOMAIN ),
                ),
	            array(
	                'type' => 'checkbox',
	                'heading' => __( 'Draggable', RF_FRAMEWORK_DOMAIN ),
	                'param_name' => 'draggable',
	                'description' => __( 'Can the map be dragged.', RF_FRAMEWORK_DOMAIN ),
	                'value' => array( __( 'Yes', RF_FRAMEWORK_DOMAIN ) => 'true' ),
                    'group' => __( 'Map Settings', RF_FRAMEWORK_DOMAIN ),
                ),
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Marker Lat', RF_FRAMEWORK_DOMAIN ),
        			'param_name' => 'marker_lat',
        			'description' => __( 'The latitude of the marker', RF_FRAMEWORK_DOMAIN ),
        			'group' => __( 'Marker', RF_FRAMEWORK_DOMAIN ),
                    'dependency' => array(
                        'element' => 'data_source',
                        'value' => 'single'
                    )
    			),
        		array(
        			'type' => 'textfield',
        			'heading' => __( 'Marker Lng', RF_FRAMEWORK_DOMAIN ),
        			'param_name' => 'marker_lng',
        			'description' => __( 'The longitude of the marker', RF_FRAMEWORK_DOMAIN ),
        			'group' => __( 'Marker', RF_FRAMEWORK_DOMAIN ),
                    'dependency' => array(
                        'element' => 'data_source',
                        'value' => 'single'
                    )
    			),
				array(
        			'type' => 'attach_image',
        			'heading' => __( 'Default Marker Image', RF_FRAMEWORK_DOMAIN ),
        			'param_name' => 'marker_image',
        			'group' => __( 'Marker', RF_FRAMEWORK_DOMAIN ),
                    'description' => __( 'The default marker image to use', RF_FRAMEWORK_DOMAIN ),
    			),
                array(
                	'type' => 'json',
                	'heading' => __( 'Map Style', RF_FRAMEWORK_DOMAIN ),
                	'param_name' => 'map_style',
                	'description' => __( 'Provide the snazzy maps settings data', RF_FRAMEWORK_DOMAIN ),
                	'group' => __( 'Style', RF_FRAMEWORK_DOMAIN )
            	)
            ) )
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
        $data_source = $post_types = $taxonomies = $include = $max_items = $template = $orderby = $order = $meta_key = $offset = $exclude = $image_size = $extra_class = $excerpt_length = '';
        $location_field_name = $post_marker_image = $marker_template = $width = $height = $draggable = $marker_lat = $marker_lng = $marker_image = $map_style = '';
        extract( shortcode_atts( array(
            'data_source' => 'post',
            'post_types' => 'post',
            'taxonomies' => '',
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

        	'location_field_name' => 'address',
        	'post_marker_image' => '',
            'marker_template' => 'default.php',
        	'width' 	=> '100%',
        	'height'	=> '300px',
    		'zoom'		=> '16',
    		'draggable' => '',
        	'marker_lat' => '',
        	'marker_lng' => '',
        	'marker_image' => '',
            'map_style' => '',
        ), $atts ) );

        // Convert the map styles
        $styles = rf_convert_vc_json($map_style);

        // New up the map element
        $map = (new RF_Element_Map())
            ->width($width)
            ->height($height)
            ->settings(compact('draggable', 'styles', 'zoom'))
            ->template($template)
            ->marker_template($marker_template);

        // Get the image for the marker
        $default_marker_image = ($marker_image) ? wp_get_attachment_image_url($marker_image, 'full') : '';

        // If a single marker is shown
        if ( $data_source == 'single' ) {
            // Add the single marker
            $map->marker(array(
                'lat' => $marker_lat,
                'lng' => $marker_lng,
                'image' => $default_marker_image
            ));
        }
        // Multiple markers from post type
        else {

            // Create the query arguments
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
                $args['tax_query']['relation'] = 'OR';
            }

            // Get list of posts
            $locations = get_posts($args);

            // Go through each post and get the latitude and long
            foreach($locations as $post) {
                // Get the address meta data
                $address = get_field($location_field_name, $post->ID);
                // Marker field
                $marker_value = ($post_marker_image) ? get_field($post_marker_image, $post->ID) : '';
                $marker_image = ($marker_value) ? wp_get_attachment_image_url($marker_value, 'full') : $default_marker_image;
                
                // Add the map markers
                $map->marker(array(
                    'lat' => $address['lat'],
                    'lng' => $address['lng'],
                    'image' => $marker_image,
                    'content' => $post
                ));
            }
            wp_reset_postdata();
        }

        ob_start();
        $map->render();
        return ob_get_clean();
    }

}