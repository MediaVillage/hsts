<?php
/**
 * Element: Output a list of taxonomy terms
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Element_Term_List extends RF_Abstract_Element
{
    /**
     * @var null
     */
    protected $id = null;

    /**
     * @var string
     */
    protected $name = 'term_list';

    /**
     * @var
     */
    protected $query_args = array();

    /**
     * The method all child classes will inherit and need to overwrite
     *
     * @param bool $echo
     * @return mixed
     */
    public function render($echo = true)
    {
        // Extract the variables for the template
        extract( $this->extract_variables() );

        // Get the list of terms
        $terms = get_terms($this->get_term_args());

        // Render the template
        ob_start();
        include( $this->get_template() );
        wp_reset_postdata();

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
     * Get the term arguments
     *
     * @return array|mixed|void
     */
    protected function get_term_args()
    {
        $arguments = wp_parse_args($this->query_args, $this->query_defaults());
        return ($this->id) ? apply_filters( 'rf_term_list_' . $this->id . '_query_args', $arguments, $this) : $arguments;
    }

    /**
     * Default query arguments
     *
     * @return array
     */
    protected function query_defaults()
    {
        return array(
            'taxonomy'               => null,
            'object_ids'             => null,
            'orderby'                => 'name',
            'order'                  => 'ASC',
            'hide_empty'             => true,
            'include'                => array(),
            'exclude'                => array(),
            'exclude_tree'           => array(),
            'number'                 => '',
            'offset'                 => '',
            'fields'                 => 'all',
            'count'                  => false,
            'name'                   => '',
            'slug'                   => '',
            'term_taxonomy_id'       => '',
            'hierarchical'           => true,
            'search'                 => '',
            'name__like'             => '',
            'description__like'      => '',
            'pad_counts'             => false,
            'get'                    => '',
            'child_of'               => 0,
            'parent'                 => '',
            'childless'              => false,
            'cache_domain'           => 'core',
            'update_term_meta_cache' => true,
            'meta_query'             => '',
            'meta_key'               => '',
            'meta_value'             => '',
            'meta_type'              => '',
            'meta_compare'           => '',
        );
    }
}