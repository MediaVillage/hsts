<?php
/**
 * Defines helper class to fetch and display tweets
 *
 * @author CLS
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Pull in twitter library
 */
require_once( __DIR__ . '/../libraries/twitteroauth/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Class RF_Service_Twitter
 */
class RF_Service_Twitter {

    private $option_key = 'rf_twitter';
    private $consumer_key;
    private $consumer_secret;
    private $access_token;
    private $access_token_secret;
    private $defaults = array(
        'count' => 5,
        'exclude_replies' => true,
        'screen_name'     => 'twitterapi',
        'include_rts'     => true
    );

    /**
     * Setup the twitter settings
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = array()) {
        $twitter_settings = !empty($attributes) ? $attributes : get_option( $this->option_key, array() );
        if ( ! empty( $twitter_settings ) ) {
            foreach($twitter_settings as $key => $setting) {
                $this->$key = $setting;
            }
        }
    }

    /**
     * Fetch a list of tweets
     *
     * @param array $options
     * @return array|object
     */
    public function getTweets($options = array() ) {
        // Merge with defaults and overwrite if set
        $args = wp_parse_args($options, $this->defaults);

        // Allow other parties to edit the options
        $args = apply_filters( 'rf_twitter_options', $args );

        // Setup twitter connection
        $connection = new TwitterOAuth(
            $this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret
        );

        // Return the list of tweets
        return $connection->get("statuses/user_timeline", $args);
    }

    /**
     * Format tweet text to link to tags and urls
     *
     * @param $tweetText
     * @return mixed
     */
    public static function formatTweet($tweetText) {
        // Make links active
        $tweet = preg_replace('/((http)+(s)?:\/\/[^<>\s]+)/i', '<a href="$0" target="_blank">$0</a>', $tweetText );
        // Linkify user mentions
        $tweet = preg_replace('/[@]+([A-Za-z0-9-_]+)/', '<a href="http://twitter.com/$1" target="_blank">$1</a>', $tweet );
        // Linkify tags
        $tweet = preg_replace('/[#]+([A-Za-z0-9-_]+)/', '<a href="http://twitter.com/search?q=%23$1" target="_blank">$0</a>', $tweet );
        return $tweet;
    }

    /**
     * Convert twitter created_at date to time ago format
     *
     * @param $a
     * @return string
     */
    public static function toTimeAgo($a) {
        //get current timestampt
        $b = strtotime("now");
        //get timestamp when tweet created
        $c = strtotime($a);
        //get difference
        $d = $b - $c;
        //calculate different time values
        $minute = 60;
        $hour = $minute * 60;
        $day = $hour * 24;
        $week = $day * 7;

        if(is_numeric($d) && $d > 0) {
            //if less then 3 seconds
            if($d < 3) return "right now";
            //if less then minute
            if($d < $minute) return floor($d) . " seconds ago";
            //if less then 2 minutes
            if($d < $minute * 2) return "about 1 minute ago";
            //if less then hour
            if($d < $hour) return floor($d / $minute) . " minutes ago";
            //if less then 2 hours
            if($d < $hour * 2) return "about 1 hour ago";
            //if less then day
            if($d < $day) return floor($d / $hour) . " hours ago";
            //if more then day, but less then 2 days
            if($d > $day && $d < $day * 2) return "yesterday";
            //if less then year
            if($d < $day * 365) return floor($d / $day) . " days ago";
            //else return more than a year
            return "over a year ago";
        }
    }
}