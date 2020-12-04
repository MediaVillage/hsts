<?php
/**
 * Define core functions for the framework
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get other templates passing attributes and including the file.
 *
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function rf_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
    if ( ! empty( $args ) && is_array( $args ) ) {
        extract( $args );
    }

    $located = rf_locate_template( $template_name, $template_path, $default_path );

    if ( ! file_exists( $located ) ) {
        _doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'woocommerce' ), '<code>' . $located . '</code>' ), '2.1' );
        return;
    }

    // Allow 3rd party plugin filter template file from their plugin.
    $located = apply_filters( 'rf_get_template', $located, $template_name, $args, $template_path, $default_path );
    include( $located );
}

/**
 * Like rf_get_template, but returns the HTML instead of outputting.
 *
 * @param string $template_name
 * @param array $args
 * @param string $template_path
 * @param string $default_path
 *
 * @return string
 */
function rf_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
    ob_start();
    rf_get_template( $template_name, $args, $template_path, $default_path );
    return ob_get_clean();
}

/**
 * Find a template overwrite in theme if not locate the default
 *
 * @param $template_name
 * @param $template_path
 * @param $default_path
 *
 * @return string
 */
function rf_locate_template( $template_name, $template_path = '', $default_path = '' )
{
    if ( ! $template_path ) {
        $template_path = RFFramework()->template_path();
    }

    if ( ! $default_path ) {
        $default_path = RFFramework()->path();
    }

    // Locate the template within the template structure
    $template = locate_template(
        array(
            trailingslashit( $template_path ) . $template_name
        )
    );

    if ( ! $template ) {
        $template = trailingslashit( $default_path ) . $template_name;
    }
    return $template;
}

/**
 * Converts a Visual Composer JSON string and formats as json
 * usable in data attributes
 * 
 * @param  [type] $value [description]
 * @return [type]        [description]
 */
function rf_convert_vc_json($value)
{
    $value = str_replace('``','"', $value);
    $value = preg_replace('/`\}`/', ']',$value);
    $value = preg_replace('/`\{`/','[',$value);
    return htmlspecialchars($value);
}

/**
 * Create a custom excerpt
 *
 * @param $content
 * @param $length
 * @return string
 */
function rf_excerpt($content, $length)
{
    $text = strip_shortcodes( $content );

    /** This filter is documented in wp-includes/post-template.php */
    $text = apply_filters( 'the_content', $text );
    $text = str_replace(']]>', ']]&gt;', $text);

    return wp_trim_words( $text, $length );
}