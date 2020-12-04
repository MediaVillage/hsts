<?php
/**
 * Element: Defines the backbone for all post list shortcodes
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Element_Post_List {
    /**
     * @var
     */
    protected $id;

    /**
     * @var string
     */
    protected $name = 'post_list';

    /**
     * @var array
     */
    protected $query_args = array();

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var string
     */
    protected $template = 'default.php';

    /**
     * @var
     */
    protected $excerpt_length;

    /**
     * @var
     */
    protected $template_type = 'shortcode';

    /**
     * RF_Element_Post_Carousel constructor.
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
    }

    /**
     * Render the element
     * @param bool $echo
     * @return string
     */
    public function render($echo = true)
    {
        // Expose variables for use in template
        extract( $this->extract_variables() );

        // Set the excerpt length if a length is set
        if ( $this->excerpt_length ) {
            add_filter( 'excerpt_length', array( $this, 'set_excerpt_length'), 999 );
        }

        // Create the query
        $the_query = new WP_Query($this->get_query_args());
        // Render the template
        ob_start();
        include( $this->get_template() );
        wp_reset_postdata();

        // Remove the filter if a length is set
        if ( $this->excerpt_length ) {
            remove_filter( 'excerpt_length', array( $this, 'set_excerpt_length'), 999 );
        }

        if ( ! $echo ) {
            return ob_get_clean();
        }
        echo ob_get_clean();
    }

    /**
     * Set the query arguments
     *
     * @param $query_args
     * @return $this
     */
    public function query($query_args)
    {
        $this->query_args = $query_args;

        return $this;
    }

    /**
     * Setup options that are passed to the display
     *
     * @param $options
     * @return $this
     */
    public function options($options)
    {
        foreach($options as $option => $value) {
            $this->option($option, $value);
        }

        return $this;
    }

    /**
     * Set a display option
     *
     * @param $option
     * @param $value
     * @return $this
     */
    public function option($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Get the query arguments
     */
    protected function get_query_args()
    {
        $arguments = wp_parse_args($this->query_args, $this->query_defaults());
        return ($this->id) ? apply_filters( 'rf_'. $this->name .'_' . $this->id . '_query_args', $arguments, $this) : $arguments;
    }

    /**
     * Get the display settings
     *
     * @return array
     */
    protected function get_options()
    {
        return wp_parse_args($this->options, $this->option_defaults());
    }

    /**
     * Get list of variables to expose to template
     *
     * @return array
     */
    protected function extract_variables()
    {
        return wp_parse_args($this->template_vars(), $this->get_options());
    }

    /**
     * Extra variables to expose to the template
     *
     * @return array
     */
    public function template_vars()
    {
        return array();
    }

    /**
     * Get the query defaults
     *
     * @return array
     */
    protected function query_defaults()
    {
        return array(
            'post_type' => 'post',
            'posts_per_page' => get_option('posts_per_page'),
            'post_status' => 'publish'
        );
    }

    /**
     * Get display defaults
     *
     * @return array
     */
    protected function option_defaults()
    {
        return array(
            'title' => '',
            'image_size' => 'thumbnail',
            'extra_class' => '',
            'excerpt_length' => $this->excerpt_length
        );
    }

    /**
     * Set the template to use
     *
     * @param $template
     * @param string $type
     * @return $this
     */
    public function template($template, $type = 'shortcode')
    {
        $this->template = $template;
        $this->template_type = $type;

        return $this;
    }

    /**
     * Get the template
     */
    protected function get_template()
    {
        if ( $this->template_type == 'widget' ) {
            return $this->widget_template();
        }
        return $this->shortcode_template();
    }

    /**
     * Get the template from the shortcode path
     *
     * @return string
     */
    protected function shortcode_template()
    {
        $default_path = RFFramework()->path() . '/templates/shortcodes/' . $this->name;
        $template_path = RFFramework()->template_path() . 'shortcodes/' . trailingslashit($this->name);
        return rf_locate_template( $this->template, $template_path, $default_path);
    }

    /**
     * Get the template from the widget path
     *
     * @return string
     */
    protected function widget_template()
    {
        $default_path =  RFFramework()->path() . '/templates/widgets/' . $this->name;
        $template_path = RFFramework()->template_path() . 'widgets/' . trailingslashit($this->name);
        return rf_locate_template( $this->template, $template_path, $default_path);
    }

    /**
     * Set the excerpt length
     *
     * @param $value
     * @return $this
     */
    public function excerpt_length($value)
    {
        $this->excerpt_length = $value;

        return $this;
    }

    /**
     * Set the excerpt length of the shortcode to the length set in the shortcode atts
     *
     * @param $length
     * @return int
     */
    public function set_excerpt_length( $length )
    {
        return $this->excerpt_length;
    }
}