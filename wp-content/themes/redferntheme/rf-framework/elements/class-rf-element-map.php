<?php
/**
 * Element: Creates as Google Map with markers
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Element_Map
{
    /**
     * @var
     */
    protected $id;

    /**
     * @var string
     */
    protected $name = 'map';

    /**
     * @var array
     */
    protected $markers = array();

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var string
     */
    protected $width = '100%';

    /**
     * @var string
     */
    protected $height = '400px';

    /**
     * @var string
     */
    protected $template = 'default.php';

    /**
     * @var string
     */
    protected $marker_template = 'default.php';

    /**
     * RF_Element_Map constructor.
     * @param $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Add a new marker
     *
     * @param $marker
     * @return $this
     */
    public function marker($marker)
    {
        $this->markers[] = $marker;

        return $this;
    }

    /**
     * Set a map setting
     *
     * @param $setting
     * @param $value
     * @return $this
     */
    public function setting($setting, $value)
    {
        $this->settings[$setting] = $value;

        return $this;
    }

    /**
     * Set the map settings
     *
     * @param $settings
     * @return $this
     */
    public function settings($settings)
    {
        foreach($settings as $setting => $value) {
            $this->setting($setting, $value);
        }

        return $this;
    }

    /**
     * Set the width of the map
     *
     * @param $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set the height of the map
     *
     * @param $height
     * @return $this
     */
    public function height($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get the classes of the main map container
     *
     * @return string
     */
    public function get_classes()
    {
        // Add class that is required for script
        $classes = array( 'rf-map' );

        // If there is an id then look for a filter for extra classes
        if ( $this->id ) {
            $classes += apply_filters( "rf_map_{$this->id}_classes", array() );
        }

        return implode(' ', $classes);
    }

    /**
     * Get the inline styles for the map
     *
     * @return string
     */
    protected function get_styles()
    {
        $styles = array(
            'width' => $this->width,
            'height' => $this->height
        );

        // If there is an id then check for any 3rd party alterations to the map styles
        if ( $this->id ) {
            $styles = apply_filters( "rf_map_{$this->id}_styles", $styles );
        }

        // Concatenate the styles into a string
        $style = '';
        foreach($styles as $attribute => $value) {
            $style .= "{$attribute}: {$value};";
        }
        return $style;
    }

    /**
     * Get the data attribute arguments
     *
     * @return array
     */
    protected function data_attribute_settings()
    {
        return wp_parse_args($this->settings, $this->setting_defaults());
    }

    /**
     * Render the map
     */
    public function render()
    {
        // Load Google Maps
        wp_enqueue_script( 'rf-google-maps' );

        ob_start();
        include( $this->get_template() );
        wp_reset_postdata();
        echo ob_get_clean();
    }

    /**
     * Render each marker
     *
     * @param $marker
     */
    public function render_marker($marker)
    {
        // If no lat and long then dont render
        if ( ! isset($marker['lat']) || ! isset($marker['lng']) ) return;

        // Check if there is any html in the marker, for use in an info window
        $content = '';
        if ( isset($marker['content']) ) {
            $content = (is_object($marker['content'])) ? $marker['content']->post_content : $marker['content'];
            // If the content is an object then set it up as the main post
            if ( is_object($marker['content']) ) {
                global $post;
                setup_postdata($post = $marker['content']);
            }
            unset($marker['content']);
        }

        // Parse the marker attributes into a data attribute string
        $marker_data_atts = $this->parse_data_attributes(array_filter($marker));

        // Render the marker template into the output buffer
        ob_start();
        include( $this->get_marker_template() );
        $marker_content = ob_get_clean();

        // Render the marker template
        echo sprintf('<div class="marker" %s>%s</div>', $marker_data_atts, $marker_content);
        wp_reset_postdata();
    }

    /**
     * Set the template to use
     *
     * @param $template
     * @return $this
     */
    public function template($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the template
     */
    protected function get_template()
    {
        $default_path = $this->get_default_template_path();
        $template_path = RFFramework()->template_path() . 'shortcodes/' . trailingslashit($this->name);

        return rf_locate_template( $this->template, $template_path, $default_path);
    }

    /**
     * Get the default shortcode template path
     *
     * @return string
     */
    protected function get_default_template_path()
    {
        return RFFramework()->path() . '/templates/shortcodes/' . $this->name;
    }

    /**
     * Set the template to use for marker info windows
     *
     * @param $template
     * @return $this
     */
    public function marker_template($template)
    {
        $this->marker_template = $template;

        return $this;
    }

    /**
     * Get the template
     */
    protected function get_marker_template()
    {
        $default_path = $this->get_default_template_path() . '/markers';
        $template_path = RFFramework()->template_path() . 'shortcodes/' . trailingslashit($this->name) . 'markers/';

        return rf_locate_template( $this->marker_template, $template_path, $default_path);
    }

    /**
     * Parse data attributes
     *
     * @param $attributes
     * @return string
     */
    protected function parse_data_attributes($attributes)
    {
        $data_attributes = array();
        foreach($attributes as $key => $value) {
            $val = (is_array($value)) ? json_encode($value) : $value;
            $data_key = str_replace('_','-', $key);
            $data_attributes[] = "data-{$data_key}='{$val}'";
        }
        return implode(' ', $data_attributes);
    }

    /**
     * Convert camel case for data attributes
     *
     * @param $key
     * @return string
     */
    function convert_data_key($key)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $key, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }

    /**
     * Get the data attributes to be assigned to the HTML element
     */
    protected function get_data_attributes()
    {
        $data_attributes = array();

        // Add carousel args as data attributes
        $settings = $this->data_attribute_settings();
        foreach($settings as $key => $value) {
            // Get the method name for specific overwrites
            $method = $this->attribute_method_name($key);

            // If the method exists then call
            if ( method_exists($this, $method)) {
                $value = $this->$method($value);
            }

            $val = (is_array($value)) ? json_encode($value) : $value;
            $data_key = $this->convert_data_key(str_replace('_','-', $key));
            $data_attributes[] = "data-{$data_key}='{$val}'";
        }

        return implode(' ', $data_attributes);
    }

    /**
     * Get the method name for data attribute overwrites
     *
     * @param $key
     * @return string
     */
    protected function attribute_method_name($key)
    {
        return "map_arg_{$key}";
    }

    /**
     * The default settings
     *
     * @return array
     */
    protected function setting_defaults()
    {
        return array(
            'zoom'              => 16,
            'scrollwheel'       => 'false',
            'draggable'         => 'true',
        );
    }
}