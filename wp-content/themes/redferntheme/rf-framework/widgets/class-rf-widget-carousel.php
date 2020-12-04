<?php
/**
 * Widget: Output posts in a carousel
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Widget_Carousel extends RF_Abstract_Widget
{
    /**
     * The name of the widget, used for the folder name for templates
     * @var string
     */
    public $widget_name = 'carousel';

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'rf_widget_carousel', // Base ID
            esc_html__( 'Post Carousel', 'redferntheme' ), // Name
            array( 'description' => esc_html__( 'Displays posts in a carousel', 'redferntheme' ), ) // Args
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
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $title = isset($instance['title']) ? $instance['title'] : '';
        $image_size = isset($instance['image_size']) ? $instance['image_size'] : '';
        $extra_class = isset($instance['extra_class']) ? $instance['extra_class'] : '';

        // Query args
        $query_args = array(
            'post_type'             => isset($instance['post_type']) ? $instance['post_type'] : 'any',
            'post_status'           => 'publish',
            'posts_per_page'        => isset($instance['max_items']) ? $instance['max_items'] : 10,
            'orderby'               => 'date',
            'meta_key'              => '',
            'order'                 => 'DESC',
            'offset'                => '',
            'ignore_sticky_posts'   => true
        );

        // Setup the carousel element
        $carousel = (new RF_Element_Post_Carousel())
            ->query($query_args)
            ->carousel_settings(array(
                'items'             => $instance['display'],
                'slide_by'          => $instance['slide_by'],
                'margin'            => $instance['margin'],
                'stage_padding'     => $instance['stage_padding'],
                'loop'              => isset($instance['loop']) ? 'true' : 'false',
                'nav'               => isset($instance['loop']) ? 'true' : 'false',
                'dots'              => isset($instance['dots']) ? 'true' : 'false',
                'autoplay'          => isset($instance['autoplay']) ? 'true' : 'false',
                'autoplay_timeout'  => $instance['autoplay_timeout']
            ))
            ->options(compact('title', 'image_size', 'extra_class'))
            ->template($instance['template'], 'widget');

        $carousel->render();
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     * @return string|void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $post_type = ! empty( $instance['post_type'] ) ? $instance['post_type'] : '';
        $max_items =  ! empty( $instance['max_items'] ) ? $instance['max_items'] : 5;
        $image_size = ! empty( $instance['image_size'] ) ? $instance['image_size'] : 'thumbnail';
        $template = ! empty( $instance['template'] ) ? $instance['template'] : '';
        $slide_by = ! empty( $instance['slide_by'] ) ? $instance['slide_by'] : 1;
        $margin = ! empty( $instance['margin'] ) ? $instance['margin'] : '';
        $autoplay_timeout = ! empty( $instance['autoplay_timeout'] ) ? $instance['autoplay_timeout'] : 5000;
        $stage_padding = ! empty( $instance['stage_padding'] ) ? $instance['stage_padding'] : '';
        $loop = isset( $instance['loop'] ) ? 1 : 0;
        $nav = isset( $instance['nav'] ) ? 1 : 0;
        $dots = isset( $instance['dots'] ) ? 1 : 0;
        $autoplay = isset( $instance['autoplay'] ) ? 1 : 0;
        $display_lg = ! empty( $instance['display']['lg'] ) ? $instance['display']['lg'] : '3';
        $display_md = ! empty( $instance['display']['md'] ) ? $instance['display']['md'] : '3';
        $display_sm = ! empty( $instance['display']['sm'] ) ? $instance['display']['sm'] : '2';
        $display_xs = ! empty( $instance['display']['xs'] ) ? $instance['display']['xs'] : '1';

        $unique_ref = "{$this->id_base}-{$this->number}";
        $types = $this->get_post_types();
        $templates = $this->get_templates();

        ?>
        <div id="<?php echo $unique_ref; ?>" class="widget-tabs">
            <ul>
                <li><a href="#<?php echo $unique_ref; ?>-1">General</a></li>
                <li><a href="#<?php echo $unique_ref; ?>-3">Carousel</a></li>
            </ul>
            <div id="<?php echo $unique_ref; ?>-1">

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'redferntheme' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
                </p>
                <p>
                    <label for="<?php echo esc_attr($this->get_field_id('post_type')); ?>"><?php esc_attr_e( 'Post Type:', 'redferntheme' ); ?></label>
                    <select name="<?php echo esc_attr( $this->get_field_name('post_type') ); ?>" id="<?php echo esc_attr( $this->get_field_id('post_type') ); ?>" class="widefat">
                        <?php foreach($types as $ptype => $post_type_name): ?>
                            <option value="<?php echo $ptype; ?>" <?php selected($post_type, $ptype); ?>><?php echo $post_type_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'max_items' ) ); ?>"><?php esc_attr_e( 'Number of Items:', 'redferntheme' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'max_items' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'max_items' ) ); ?>" type="text" value="<?php echo esc_attr( $max_items ); ?>">
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>"><?php esc_attr_e( 'Image Size:', 'redferntheme' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_size' ) ); ?>" type="text" value="<?php echo esc_attr( $image_size ); ?>">
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>"><?php esc_attr_e( 'Template:', 'redferntheme' ); ?></label>
                    <select name="<?php echo esc_attr( $this->get_field_name( 'template' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>" class="widefat">
                        <?php foreach($templates as $t): ?>
                            <option value="<?php echo $t; ?>" <?php selected($template, $t); ?>><?php echo $t; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>

            </div>
            <div id="<?php echo $unique_ref; ?>-3">

                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Device</th>
                            <th>Number of items</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Large Desktops</td>
                            <td><input type="text" name="<?php echo $this->get_field_name( 'display[lg]'); ?>" class="widefat" value="<?php echo $display_lg; ?>"></td>
                        </tr>
                        <tr>
                            <td>Desktops</td>
                            <td><input type="text" name="<?php echo $this->get_field_name( 'display[md]'); ?>" class="widefat" value="<?php echo $display_md; ?>"></td>
                        </tr>
                        <tr>
                            <td>Tablets</td>
                            <td><input type="text" name="<?php echo $this->get_field_name( 'display[sm]'); ?>" class="widefat" value="<?php echo $display_sm; ?>"></td>
                        </tr>
                        <tr>
                            <td>Mobiles</td>
                            <td><input type="text" name="<?php echo $this->get_field_name( 'display[xs]'); ?>" class="widefat" value="<?php echo $display_xs; ?>"></td>
                        </tr>
                    </tbody>
                </table>

                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'slide_by' ) ); ?>"><?php esc_attr_e( 'Slide By:', 'redferntheme' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'slide_by' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slide_by' ) ); ?>" type="text" value="<?php echo esc_attr( $slide_by ); ?>">
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'margin' ) ); ?>"><?php esc_attr_e( 'Margin:', 'redferntheme' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'margin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'margin' ) ); ?>" type="text" value="<?php echo esc_attr( $margin ); ?>">
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'stage_padding' ) ); ?>"><?php esc_attr_e( 'Stage Padding:', 'redferntheme' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'stage_padding' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'stage_padding' ) ); ?>" type="text" value="<?php echo esc_attr( $stage_padding ); ?>">
                </p>
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'loop')); ?>" name="<?php echo esc_attr( $this->get_field_name('loop')); ?>" type="checkbox" value="1" <?php checked($loop, 1); ?>>
                    &nbsp;<label for="<?php echo esc_attr( $this->get_field_id('loop')); ?>">Loop through slides</label>
                </p>
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'nav')); ?>" name="<?php echo esc_attr( $this->get_field_name('nav')); ?>" type="checkbox" value="1" <?php checked($nav, 1); ?>>
                    &nbsp;<label for="<?php echo esc_attr( $this->get_field_id('nav')); ?>">Show next/prev buttons</label>
                </p>
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'dots')); ?>" name="<?php echo esc_attr( $this->get_field_name('dots')); ?>" type="checkbox" value="1" <?php checked($dots, 1); ?>>
                    &nbsp;<label for="<?php echo esc_attr( $this->get_field_id('dots')); ?>">Show pagination dots</label>
                </p>
                <p>
                    <input id="<?php echo esc_attr( $this->get_field_id( 'autoplay')); ?>" name="<?php echo esc_attr( $this->get_field_name('autoplay')); ?>" type="checkbox" value="1" <?php checked($autoplay, 1); ?>>
                    &nbsp;<label for="<?php echo esc_attr( $this->get_field_id('autoplay')); ?>">Autoplay the carousel</label>
                </p>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'autoplay_timeout' ) ); ?>"><?php esc_attr_e( 'Autoplay Interval:', 'redferntheme' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'autoplay_timeout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'autoplay_timeout' ) ); ?>" type="text" value="<?php echo esc_attr( $autoplay_timeout ); ?>">
                </p>

            </div>
        </div>
        <?php
    }
}