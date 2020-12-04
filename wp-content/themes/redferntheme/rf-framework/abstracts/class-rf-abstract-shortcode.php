<?php
/**
 * Abstract class for Shortcodes
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class RF_Abstract_Shortcode {

    /**
     * @var
     */
    public static $name;

    /**
     * @var
     */
    public static $shortcode;

    /**
     * Initialise wordpress hooks for the shortcode
     */
    public static function init()
    {
        add_action( 'vc_before_init', array( get_called_class(), 'vc_setup' ) );
        add_shortcode( static::$shortcode, array( get_called_class(), 'output') );
    }

    /**
     * Get the path to the shortcode
     *
     * @return string
     */
    public static function get_path()
    {
        $rc = new ReflectionClass( get_called_class() );
        $class_path = dirname($rc->getFileName());
        return $class_path;
    }

    /**
     * Get any templates associated with the shortcode
     *
     * @return array
     */
    public static function get_templates()
    {
        $options = [];

        // Find any default templates in the framework
        $default_path = self::get_default_template_path();
        if ( file_exists( $default_path ) ) {
            foreach(glob( "{$default_path}/*.php") as $filepath) {
                $filename = basename($filepath);
                $options[$filename] = $filename;
            }
        }

        // Get any custom templates from the theme
        $template_path = self::get_theme_overwrite_path();
        foreach(glob( "{$template_path}*.php") as $filepath) {
            $filename = basename($filepath);
            $options[$filename] = $filename;
        }
        return $options;
    }

    /**
     * Find a template for the shortcode in the theme or framework
     * 
     * @param $template_name
     * @return string
     */
    public static function get_template( $template_name )
    {
        $default_path = self::get_default_template_path();
        $template_path = RFFramework()->template_path() . 'shortcodes/' . trailingslashit(static::$name);

        return rf_locate_template( $template_name, $template_path, $default_path);
    }

    /**
     * Get a path to the shortcode overwrite in the theme
     *
     * @return string
     */
    protected static function get_theme_overwrite_path()
    {
        return get_stylesheet_directory() . '/' . RFFramework()->template_path() . 'shortcodes/' . trailingslashit(static::$name);
    }

    /**
     * Get the default shortcode template path
     *
     * @return string
     */
    protected static function get_default_template_path()
    {
        return RFFramework()->path() . '/templates/shortcodes/' . static::$name;
    }

    /**
     * Get list of post types
     * Useful for shortcodes requiring post type selection
     *
     * @param array $args
     * @return array
     */
    public static function get_post_types( $args = array() )
    {
        $post_types = get_post_types( $args );
        $post_types_list = array();
        if ( is_array( $post_types ) && ! empty( $post_types ) ) {
            foreach ( $post_types as $post_type ) {
                if ( $post_type !== 'revision' && $post_type !== 'nav_menu_item' ) {
                    $label = ucfirst( $post_type );
                    $post_types_list[$label] = $post_type;
                }
            }
        }
        return $post_types_list;
    }

    /**
     * Recursive method to return a list of page children
     *
     * @param $parent_id
     * @return array
     */
    protected static function getPageChildren($parent_id)
    {
        global $wpdb;
        $children = array();
        $children_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID from {$wpdb->posts} WHERE post_type = %s AND post_parent = %d AND post_status = %s", 'page', $parent_id, 'publish') );
        foreach($children_ids as $child_id) {
            $gchildren = self::getPageChildren($child_id);
            if ( !empty($gchildren) ) {
                $children = array_merge($children, $gchildren);
            }
        }
        $children = array_merge($children, $children_ids);
        return $children;
    }

    /**
     * Convert comma separated ids to array
     *
     * @param $value
     * @return array
     */
    protected static function commaListToArray($value)
    {
        return explode(',', str_replace(' ','',$value) );
    }

    /**
     * Convert an associative array to inline styles
     * 
     * @param  array $fields
     * @return string
     */
    protected static function get_inline_styles($fields)
    {
        $tmp = array();
        foreach($fields as $attribute => $value) {
            $tmp[] = "{$attribute}:{$value};";
        }
        return implode(' ',$tmp);
    }

    /**
     * Convert a associative array to a data attributes string
     * 
     * @param  array $fields
     * @return string
     */
    protected static function get_data_attributes($fields)
    {
        $data_atts = array();
        foreach($fields as $key => $value) {
            if ( $value ) {
                $data_atts[] = "data-{$key}=\"{$value}\"";
            }
        }
        return implode(' ', $data_atts);
    }

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
        );
    }

    /**
     * Get post options
     *
     * @return array
     */
    protected static function post_options()
    {
        $post_types_list = static::get_post_types();

        return array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Data Source', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'data_source',
                'value' => static::data_source_options()
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Post Types', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'post_types',
                'value' => $post_types_list,
                'save_always' => true,
                'description' => __( 'Select content types for the list.', RF_FRAMEWORK_DOMAIN ),
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'post' ),
                ),
            ),
            array(
                'type' => 'autocomplete',
                'heading' => __( 'Narrow data source', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'taxonomies',
                'settings' => array(
                    'multiple' => true,
                    'min_length' => 1,
                    'groups' => true,
                    // In UI show results grouped by groups, default false
                    'unique_values' => true,
                    // In UI show results except selected. NB! You should manually check values in backend, default false
                    'display_inline' => true,
                    // In UI show results inline view, default false (each value in own line)
                    'delay' => 500,
                    // delay for search. default 500
                    'auto_focus' => true,
                    // auto focus input, default true
                ),
                'param_holder_class' => 'vc_not-for-custom',
                'description' => __( 'Enter categories, tags or custom taxonomies.', RF_FRAMEWORK_DOMAIN ),
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'post' ),
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Taxonomy Relation', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'taxonomy_relation',
                'value' => array(
                    __( 'OR', RF_FRAMEWORK_DOMAIN ) => 'OR',
                    __( 'AND', RF_FRAMEWORK_DOMAIN ) => 'AND'
                ),
                'description' => __( 'Whether all taxonomies need to be assigned OR if at least one of them needs to be', RF_FRAMEWORK_DOMAIN ),
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'post' )
                )
            ),
            array(
                'type' => 'autocomplete',
                'heading' => __( 'Include only', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'include',
                'description' => __( 'Add posts, pages, etc. by title.', RF_FRAMEWORK_DOMAIN ),
                'settings' => array(
                    'multiple' => true,
                    'sortable' => true,
                    'groups' => true,
                ),
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'ids' ),
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Total items', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'max_items',
                'value' => 10, // default value
                'param_holder_class' => 'vc_not-for-custom',
                'description' => __( 'Set max limit for items in grid or enter -1 to display all (limited to 1000).', RF_FRAMEWORK_DOMAIN ),
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'post' ),
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Content Length', 'oj-theme' ),
                'param_name' => 'excerpt_length',
                'description' => __( 'The word count for the post excerpt/content, leave blank for full excerpt/content'),
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'post', 'ids' )
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Featured Image Size', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'image_size',
                'value' => 'thumbnail',
                'description' => __( 'The size of the featured image e.g. small, medium, full etc.', RF_FRAMEWORK_DOMAIN ),
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'post', 'ids' )
                ),
            ),
            // Template
            array(
                "type" => "dropdown",
                'heading'   => __( 'Template', RF_FRAMEWORK_DOMAIN ),
                "param_name" => "template",
                'description' => __( 'The template used to display the posts', RF_FRAMEWORK_DOMAIN ),
                'value' => self::get_templates(),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Extra Classes', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'extra_class',
                'description' => __( 'Additional classes added to the element', RF_FRAMEWORK_DOMAIN ),
            ),
            // Data settings
            array(
                'type' => 'dropdown',
                'heading' => __( 'Order by', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'orderby',
                'value' => array(
                    __( 'Date', RF_FRAMEWORK_DOMAIN ) => 'date',
                    __( 'Order by post ID', RF_FRAMEWORK_DOMAIN ) => 'ID',
                    __( 'Author', RF_FRAMEWORK_DOMAIN ) => 'author',
                    __( 'Title', RF_FRAMEWORK_DOMAIN ) => 'title',
                    __( 'Last modified date', RF_FRAMEWORK_DOMAIN ) => 'modified',
                    __( 'Post/page parent ID', RF_FRAMEWORK_DOMAIN ) => 'parent',
                    __( 'Number of comments', RF_FRAMEWORK_DOMAIN ) => 'comment_count',
                    __( 'Menu order/Page Order', RF_FRAMEWORK_DOMAIN ) => 'menu_order',
                    __( 'Meta value', RF_FRAMEWORK_DOMAIN ) => 'meta_value',
                    __( 'Meta value number', RF_FRAMEWORK_DOMAIN ) => 'meta_value_num',
                    __( 'Random order', RF_FRAMEWORK_DOMAIN ) => 'rand',
                    __( 'Order of include field (Used for list of ids source only)', RF_FRAMEWORK_DOMAIN ) => 'post__in',
                ),
                'description' => __( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', RF_FRAMEWORK_DOMAIN ),
                'group' => __( 'Data Settings', RF_FRAMEWORK_DOMAIN ),
                'param_holder_class' => 'vc_grid-data-type-not-ids',
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'post', 'ids' )
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Sort order', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'order',
                'group' => __( 'Data Settings', RF_FRAMEWORK_DOMAIN ),
                'value' => array(
                    __( 'Descending', RF_FRAMEWORK_DOMAIN ) => 'DESC',
                    __( 'Ascending', RF_FRAMEWORK_DOMAIN ) => 'ASC',
                ),
                'param_holder_class' => 'vc_grid-data-type-not-ids',
                'description' => __( 'Select sorting order.', RF_FRAMEWORK_DOMAIN ),
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'post', 'ids' )
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Meta key', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'meta_key',
                'description' => __( 'Input meta key for grid ordering.', RF_FRAMEWORK_DOMAIN ),
                'group' => __( 'Data Settings', RF_FRAMEWORK_DOMAIN ),
                'param_holder_class' => 'vc_grid-data-type-not-ids',
                'dependency' => array(
                    'element' => 'orderby',
                    'value' => array( 'meta_value', 'meta_value_num' ),
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Offset', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'offset',
                'description' => __( 'Number of grid elements to displace or pass over.', RF_FRAMEWORK_DOMAIN ),
                'group' => __( 'Data Settings', RF_FRAMEWORK_DOMAIN ),
                'param_holder_class' => 'vc_grid-data-type-not-ids',
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'post' ),
                ),
            ),
            array(
                'type' => 'autocomplete',
                'heading' => __( 'Exclude', RF_FRAMEWORK_DOMAIN ),
                'param_name' => 'exclude',
                'description' => __( 'Exclude posts, pages, etc. by title.', RF_FRAMEWORK_DOMAIN ),
                'group' => __( 'Data Settings', RF_FRAMEWORK_DOMAIN ),
                'settings' => array(
                    'multiple' => true,
                    'sortable' => true,
                    'groups' => true,
                ),
                'param_holder_class' => 'vc_grid-data-type-not-ids',
                'dependency' => array(
                    'element' => 'data_source',
                    'value' => array( 'post' ),
                    'callback' => 'vc_grid_exclude_dependency_callback',
                ),
            ),
        );
    }

    /**
     * Setup shortcodes in visual composer
     */
    public static function vc_setup() {}

    /**
     * Output the shortcode
     *
     * @param $atts
     * @param null $content
     */
    public static function output($atts, $content = null) {}
}