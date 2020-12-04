<?php
/** 
 * Widget: Output a twitter feed
 * 
 * @author  Red Fern
 * @version  1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RF_Widget_Twitter_Feed extends RF_Abstract_Widget {

	/**
	 * Name of widget, used for template overwrite
	 * @var string
	 */
	public $widget_name = 'twitter_feed';

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'rf_widget_twitter_feed', // Base ID
			esc_html__( 'Twitter Feed', 'redferntheme' ), // Name
			array( 'description' => esc_html__( 'Displays a twitter feed', 'redferntheme' ), ) // Args
		);
	}

		/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$count             = ! empty( $instance['count'] ) ? $instance['count'] : 5;
        $exclude_replies   = ! empty( $instance['exclude_replies'] ) ? $instance['exclude_replies'] : false;
        $screen_name       = ! empty( $instance['screen_name'] ) ? $instance['screen_name'] : 'twitterapi';
        $include_rts       = ! empty( $instance['include_rts'] ) ? $instance['include_rts'] : false;

	    // Get list of tweets
        $twitter = new RF_Service_Twitter();
        $tweets = $twitter->getTweets( compact('screen_name', 'count', 'include_rts', 'exclude_replies') );

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		// If there is an error then display
		if ( isset($tweets->errors) ) {
			echo 'Please set your authentication details';
		} else {
			// Load the template
			ob_start();
			include( $this->get_template( 'default.php' ) );
			echo ob_get_clean();
		}

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $screen_name       = ! empty( $instance['screen_name'] ) ? $instance['screen_name'] : 'twitterapi';
		$count             = ! empty( $instance['count'] ) ? $instance['count'] : 5;
        $exclude_replies   = ! empty( $instance['exclude_replies'] ) ? $instance['exclude_replies'] : false;
        $include_rts       = ! empty( $instance['include_rts'] ) ? $instance['include_rts'] : false;
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'redferntheme' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'screen_name' ) ); ?>"><?php esc_attr_e( 'Screen Name:', 'redferntheme' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'screen_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'screen_name' ) ); ?>" type="text" value="<?php echo esc_attr( $screen_name ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_attr_e( 'Count:', 'redferntheme' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'include_rts' ) ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'include_rts' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'include_rts' ) ); ?>" value="1" <?php checked($include_rts, 1); ?>> 
			<?php esc_attr_e( 'Include Retweets', 'redferntheme' ); ?>
		</label> 
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'exclude_replies' ) ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'exclude_replies' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'exclude_replies' ) ); ?>" value="1" <?php checked($exclude_replies, 1); ?>> 
			<?php esc_attr_e( 'Exclude Replies', 'redferntheme' ); ?>
		</label> 
		</p>
		<?php 
	}

}