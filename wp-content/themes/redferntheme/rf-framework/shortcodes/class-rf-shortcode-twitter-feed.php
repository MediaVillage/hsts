<?php
/**
 * Shortcode: Display a list of twitter feed items by given criteria in a carousel
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class RF_Shortcode_Twitter_Feed
 */
class RF_Shortcode_Twitter_Feed extends RF_Shortcode_Carousel {

    /**
     * @var string
     */
    public static $name = 'twitter_feed';

    /**
     * @var string
     */
    public static $shortcode = 'rf_twitter_feed';

    /**
     * Setup the shortcode in visual composer
     */
    public static function vc_setup()
    {
        vc_map( array(
            'name'          => __( 'Twitter Feed', RF_FRAMEWORK_DOMAIN),
            'base'          => static::$shortcode,
            'category'      => _x( 'Content', 'visual composer category', RF_FRAMEWORK_DOMAIN),
            'description'   => __( 'Display twitter feed in a carousel', RF_FRAMEWORK_DOMAIN ),
            'icon'          => RFFramework()->url() . '/assets/images/logo.jpg',
            'params' => array_merge( array(
                array(
                    'type'  => 'textfield',
                    'heading'   => __( 'Title', RF_FRAMEWORK_DOMAIN ),
                    'param_name'    => 'title',
                    'admin_label'   => true
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Screen Name', 'cls' ),
                    'param_name'    => 'screen_name',
                    'description'   => __( 'The screen name of the user for whom to return results for.', 'cls' )
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Count', 'cls' ),
                    'param_name'    => 'count',
                    'description'   => __( 'The total number of tweets to display', 'cls' ),
                    'value'         => 5
                ),
                array(
                    'type'          => 'checkbox',
                    'heading'       => __( 'Exclude Replies', 'cls' ),
                    'param_name'    => 'exclude_replies',
                    'description'   => __( 'Do you want to exclude replies from displaying?', 'cls' ),
                    'value'         => array(
                        __( 'Yes', 'cls' ) => 'yes'
                    )
                ),
                array(
                    'type'          => 'checkbox',
                    'heading'       => __( 'Include Retweets', 'cls' ),
                    'param_name'    => 'include_rts',
                    'description'   => __( 'Include retweets', 'cls' ),
                    'value'         => array(
                        __( 'Yes', 'cls' ) => 'yes'
                    )
                ),
            ),
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
        $title = $template = '';
        $carousel_items = $slide_by = $margin = $loop = $center = $stage_padding = $nav = $dots = $autoplay = $autoplay_timeout = '';
        $image_size = $extra_class = '';
        extract( shortcode_atts(array(
            'title'     => '',
            'template' => 'default.php',
            'count'             => 5,
            'exclude_replies'   => '',
            'screen_name'       => 'twitterapi',
            'include_rts'       => '',

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
            'autoplay_timeout' => 5000
        ), $atts ) );

        // Get list of tweets
        $twitter = new RF_Service_Twitter();
        $tweets = $twitter->getTweets( compact('screen_name', 'count', 'include_rts', 'exclude_replies') );

        // Create the data attributes for the carousel
        $items = (is_array($carousel_items)) ? json_encode($carousel_items) : str_replace('``','"', $carousel_items);
        $data_attributes = array(
            'slide_by', 'margin', 'loop', 'center', 'stage_padding', 'nav', 'dots', 'autoplay', 'autoplay_timeout'
        );
        $attributes = array("data-items='{$items}'");
        foreach($data_attributes as $key) {
            $data_key = str_replace('_','-', $key);
            $attributes[] = "data-{$data_key}='{$$key}'";
        }
        $data_atts = implode(' ', $attributes);

        // Output template
        ob_start();
        include( self::get_template( $template ) );
        return ob_get_clean();
    }

}