<?php
/**
 * Define media fields
 *
 * @author Red Fern
 * @version 1.0.0
 */
defined( 'ABSPATH' ) or die;

class RF_Media_Fields {

    /**
     * Initialize fields
     */
    public static function init()
    {
        add_filter( 'attachment_fields_to_edit', array( __CLASS__, 'add_attachment_fields' ), 11, 2 );
        add_filter( 'attachment_fields_to_save', array( __CLASS__, 'save_attachment_fields' ), 11, 2 );
    }

    /**
     * Add additional fields
     *
     * @param $form_fields
     * @param $post
     * @return array
     */
    public static function add_attachment_fields( $form_fields, $post )
    {
        // Add a Credit field
        $form_fields["credit_text"] = array(
            "label" => __("Credit"),
            "input" => "text", // this is default if "input" is omitted
            "value" => esc_attr( get_post_meta($post->ID, "_credit_text", true) ),
            "helps" => __("The owner of the image."),
        );

        return $form_fields;
    }

    /**
     * Save attachment fields
     *
     * @param $post
     * @param $attachment
     */
    public static function save_attachment_fields( $post, $attachment )
    {
        print '<pre>';
        print_r(compact('post', 'attachment'));
        print '</pre>';
        die;

        return $post;
    }
}

RF_Media_Fields::init();

