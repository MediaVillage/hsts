<?php
/**
 * Abstract element class
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

abstract class RF_Abstract_Element
{
    /**
     * @var null
     */
    protected $id = null;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $template = 'default.php';

    /**
     * @var
     */
    protected $template_type = 'shortcode';

    /**
     * @var array
     */
    protected $options = array();

    /**
     * RF_Abstract_Element constructor.
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
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
     * Get list of variables to expose to template
     *
     * @return array
     */
    protected function extract_variables()
    {
        return wp_parse_args($this->template_vars(), $this->get_options());
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
     * Extra variables to expose to the template
     *
     * @return array
     */
    protected function template_vars()
    {
        return array();
    }

    /**
     * Get display defaults
     *
     * @return array
     */
    protected function option_defaults()
    {
        return array(
            'title' => ''
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
     * The method all child classes will inherit and need to overwrite
     *
     * @param bool $echo
     * @return mixed
     */
    abstract public function render($echo = true);
}