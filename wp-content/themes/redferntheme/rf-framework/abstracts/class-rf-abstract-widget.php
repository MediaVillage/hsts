<?php
/**
 * Abstract class for Widgets
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class RF_Abstract_Widget extends WP_Widget {

    /**
     * Get list of post types
     * Useful for shortcodes requiring post type selection
     *
     * @param array $args
     * @return array
     */
    public function get_post_types( $args = array() ) {
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
     * Get any templates associated with the shortcode
     *
     * @return array
     */
    public function get_templates()
    {
        $options = [];

        // Find any default templates in the framework
        $default_path = $this->get_default_template_path();
        if ( file_exists( $default_path ) ) {
            foreach(glob( "{$default_path}/*.php") as $filepath) {
                $filename = basename($filepath);
                $options[$filename] = $filename;
            }
        }

        // Get any custom templates from the theme
        $template_path = $this->get_theme_overwrite_path();
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
    public function get_template( $template_name )
    {
        $default_path = $this->get_default_template_path();
        $template_path = RFFramework()->template_path() . 'widgets/' . trailingslashit($this->widget_name);
        return rf_locate_template( $template_name, $template_path, $default_path);
    }

    /**
     * Get a path to the shortcode overwrite in the theme
     *
     * @return string
     */
    private function get_theme_overwrite_path() 
    {
        return get_stylesheet_directory() . '/' . RFFramework()->template_path() . 'widgets/' . trailingslashit($this->widget_name);
    }

    /**
     * Get the default shortcode template path
     *
     * @return string
     */
    private function get_default_template_path()
    {
        return RFFramework()->path() . '/templates/widgets/' . $this->widget_name;
    }
}