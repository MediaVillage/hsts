<?php
/**
 * Shortcode: Display list of taxonomy terms
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Shortcode_Term_List extends RF_Abstract_Shortcode
{
    /**
     * @var string
     */
    public static $name = 'term_list';

    /**
     * @var string
     */
    public static $shortcode = 'rf_term_list';

    /**
     * Initialise wordpress hooks for the shortcode
     */
    public static function init()
    {
        parent::init();

        // Include
        add_filter( 'vc_autocomplete_rf_term_list_include_callback',
            'vc_autocomplete_taxonomies_field_search', 10, 1 );
        add_filter( 'vc_autocomplete_rf_term_list_include_render',
            'vc_autocomplete_taxonomies_field_render', 10, 1 );
        // Exclude
        add_filter( 'vc_autocomplete_rf_term_list_exclude_callback',
            'vc_autocomplete_taxonomies_field_search', 10, 1 );
        add_filter( 'vc_autocomplete_rf_term_list_exclude_render',
            'vc_autocomplete_taxonomies_field_render', 10, 1 );
        // Child of
        add_filter( 'vc_autocomplete_rf_term_list_child_of_callback',
            'vc_autocomplete_taxonomies_field_search', 10, 1 );
        add_filter( 'vc_autocomplete_rf_term_list_child_of_render',
            'vc_autocomplete_taxonomies_field_render', 10, 1 );
    }

    /**
     * Get list of taxonomies
     *
     * @return array
     */
    public static function get_taxonomies()
    {
        $taxonomy_types = get_taxonomies( array( 'public' => true ), 'objects' );
        $options = array();
        foreach($taxonomy_types as $slug => $taxonomy) {
            $options[$taxonomy->label] = $slug;
        }
        return $options;
    }

    /**
     * Setup the shortcode in Visual Composer
     */
    public static function vc_setup()
    {
        vc_map( array(
            'name'			=> __( 'Term List', RF_FRAMEWORK_DOMAIN ),
            'base'  		=> self::$shortcode,
            'description' 	=> __( 'Output a list of taxonomy terms', RF_FRAMEWORK_DOMAIN ),
            'category'		=> __( 'Red Fern', RF_FRAMEWORK_DOMAIN ),
            'icon'          => RFFramework()->url() . '/assets/images/logo.jpg',
            'params' 		=> array(
                array(
                    'type'  => 'textfield',
                    'heading'   => __( 'Title', RF_FRAMEWORK_DOMAIN ),
                    'param_name'    => 'title',
                    'admin_label'   => true,
                    'weight'        => 99
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Taxonomy', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'taxonomy',
                    'admin_label' => true,
                    'value' => self::get_taxonomies()
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Display Number', RF_FRAMEWORK_DOMAIN ),
                    'description' => __( 'Number of terms to display', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'number',
                    'value' => 20
                ),
                array(
                    'type' => 'autocomplete',
                    'heading' => __( 'Include', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'include',
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
                ),
                array(
                    'type' => 'autocomplete',
                    'heading' => __( 'Exclude', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'exclude',
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
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Hide Empty', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'hide_empty',
                    'value' => array(
                        __( 'Yes', RF_FRAMEWORK_DOMAIN ) => 'yes'
                    ),
                ),
                array(
                    'type' => 'autocomplete',
                    'heading' => __( 'Child Of', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'child_of',
                    'settings' => array(
                        'multiple' => false,
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
                    'description' => __( 'Enter the term to display children of, leave blank for top level.', RF_FRAMEWORK_DOMAIN ),
                ),
                array(
                    "type" => "dropdown",
                    'heading'   => __( 'Template', RF_FRAMEWORK_DOMAIN ),
                    "param_name" => "template",
                    'description' => __( 'The template used to display the terms', RF_FRAMEWORK_DOMAIN ),
                    'value' => self::get_templates(),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Order By', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'orderby',
                    'group' => __( 'Data Settings', RF_FRAMEWORK_DOMAIN ),
                    'value' => array(
                        __( 'Name', RF_FRAMEWORK_DOMAIN ) => 'name',
                        __( 'ID', RF_FRAMEWORK_DOMAIN ) => 'id',
                        __( 'Count', RF_FRAMEWORK_DOMAIN ) => 'count',
                        __( 'Slug', RF_FRAMEWORK_DOMAIN ) => 'slug',
                        __( 'Include Order', RF_FRAMEWORK_DOMAIN ) => 'include'
                    )
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Order Direction', RF_FRAMEWORK_DOMAIN ),
                    'param_name' => 'order',
                    'group' => __( 'Data Settings', RF_FRAMEWORK_DOMAIN ),
                    'value' => array(
                        __( 'Ascending', RF_FRAMEWORK_DOMAIN ) => 'asc',
                        __( 'Descending', RF_FRAMEWORK_DOMAIN ) => 'desc'
                    )
                )
            )
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
        $atts = shortcode_atts( array(
            'title'     => '',
            'taxonomy'  => 'category',
            'number'    => 20,
            'include'   => '',
            'exclude'   => '',
            'hide_empty' => false,
            'child_of'  => '',
            'orderby'   => 'name',
            'order'     => 'asc',
            'template'  => 'default.php'
        ), $atts );

        // Include any ids
        if ( $atts['include'] ) {
            $atts['include'] = array_map('intval',explode(',', trim($atts['include'])));
        }

        // Exclude any ids
        if ( $atts['exclude'] ) {
            $atts['exclude'] = array_map('intval',explode(',', trim($atts['exclude'])));
        }

        // Child of a given term
        if ( $atts['child_of'] ) {
            $atts['child_of'] = trim($atts['child_of']);
        }

        return (new RF_Element_Term_List())
            ->option('title', $atts['title'])
            ->query($atts)
            ->template($atts['template'])
            ->render(false);
    }
}