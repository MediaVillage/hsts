<?php
/**
 * Element: Defines the backbone for all post carousel functionality
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Element_Post_Carousel extends RF_Element_Post_List {

    /**
     * @var string
     */
    protected $name = 'carousel';

    /**
     * @var array
     */
    protected $carousel_args = array();

    /**
     * @return array
     */
    public function template_vars()
    {
        return array(
            'data_atts' => $this->get_data_attributes()
        );
    }

    /**
     * Get the data attributes to be assigned to the HTML element
     */
    protected function get_data_attributes()
    {
        $data_attributes = array();

        // Add carousel args as data attributes
        $carousel_args = $this->get_carousel_args();
        foreach($carousel_args as $key => $value) {
            // Get the method name for specific overwrites
            $method = $this->carousel_arg_method_name($key);

            // If the method exists then call
            if ( method_exists($this, $method)) {
                $value = $this->$method($value);
            }

            $val = (is_array($value)) ? json_encode($value) : $value;
            $data_key = str_replace('_','-', $key);
            $data_attributes[] = "data-{$data_key}='{$val}'";
        }

        return implode(' ', $data_attributes);
    }

    /**
     * Setup the array of responsive sizes
     *
     * @param $value
     * @return array
     */
    protected function carousel_arg_items($value)
    {
        // If already an array then use
        if ( is_array($value) || substr($value,0,1) === '{' ) return $value;

        // Go through each breakpoint and set the value
        $sizes = array('xs', 'sm', 'md', 'lg');
        $tmp = array();
        foreach($sizes as $size) {
            $tmp[$size] = $value;
        }

        return $tmp;
    }

    /**
     * Get the method name for data attribute overwrites
     *
     * @param $key
     * @return string
     */
    protected function carousel_arg_method_name($key)
    {
        return "carousel_arg_{$key}";
    }

    /**
     * Add a carousel argument
     *
     * @param $setting
     * @param $value
     * @return $this
     */
    public function setting($setting, $value)
    {
        $this->carousel_args[$setting] = $value;

        return $this;
    }

    /**
     * Set the carousel settings
     *
     * @param $settings
     * @return $this
     */
    public function carousel_settings($settings)
    {
        foreach($settings as $setting => $value) {
            $this->setting($setting, $value);
        }

        return $this;
    }


    /**
     * Get the carousel arguments
     *
     * @return array
     */
    protected function get_carousel_args()
    {
        return wp_parse_args($this->carousel_args, $this->carousel_defaults());
    }

    /**
     * Get the carousel defaults
     *
     * @return array
     */
    protected function carousel_defaults()
    {
        return array(
            'items'             => 1,
            'slide_by'          => 1,
            'margin'            => 0,
            'loop'              => 'false',
            'center'            => 'false',
            'stage_padding'     => 0,
            'nav'               => 'false',
            'dots'              => 'false',
            'autoplay'          => 'false',
            'autoplay_timeout'  => 5000,
        );
    }
}