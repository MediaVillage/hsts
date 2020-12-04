<?php
/** 
 * Widget: Display Social Icons
 * 
 * @author Red Fern
 * @version  1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RF_Widget_Social_Icons extends RF_Abstract_Widget {

	/**
	 * The name of the widget, used for the folder name for templates
	 * @var string
	 */
	public $widget_name = 'social_icons';

	/**
	 * The types of social medias
	 * @var array
	 */
	public $social_media_types = array(
		'facebook' => 'fa fa-facebook', 
		'twitter' => 'fa fa-twitter', 
		'instagram' => 'fa fa-instagram', 
		'googleplus' => 'fa fa-google-plus',
		'linkedin' => 'fa fa-linkedin',
	);

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'rf_widget_social_icons', // Base ID
			esc_html__( 'Social Icons', 'redferntheme' ), // Name
			array( 'description' => esc_html__( 'Displays social media icons', 'redferntheme' ), ) // Args
		);

		// Allow plugins/child themes to alter available social media types
		$this->social_media_types = apply_filters( 'rf_widget_social_media_types', $this->social_media_types );
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
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		// Load the template
		ob_start();
		include( $this->get_template( 'default.php' ) );
		echo ob_get_clean();
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
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'redferntheme' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php foreach( $this->social_media_types as $type => $icon ): ?>
			<?php $value = ! empty( $instance[$type] ) ? $instance[$type] : ''; ?>
			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( $type ) ); ?>"><?php esc_attr_e( ucfirst($type). ':', 'redferntheme' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $type ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $type ) ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>">
			</p>		
		<?php endforeach; ?>
		<?php 
	}
}