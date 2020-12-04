<?php
/**
 * Element: Defines the backbone for all post carousel functionality
 *
 * @author Red Fern
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class RF_Element_Post_Grid extends RF_Element_Post_List
{
    /**
     * @var string
     */
    protected $name = 'post_grid';

    /**
     * @var
     */
    protected $columns = array(
        'lg' => 3,
        'md' => 4,
        'sm' => 6,
        'xs' => 12
    );

    /**
     * @var
     */
    protected $use_filter = false;

    /**
     * @var
     */
    protected $filter_taxonomy;

    /**
     * @var
     */
    protected $filter_all = false;

    /**
     * @var
     */
    protected $posts = array();

    /**
     * @return array
     */
    public function template_vars()
    {
        return array(
            'use_filter' => $this->use_filter,
            'filter_all' => $this->filter_all,
            'filters' => $this->get_filters()
        );
    }

    /**
     * Render the element
     * 
     * @param bool $echo
     * @return string
     */
    public function render($echo = true)
    {
        if ( $this->use_filter ) {
            // Preload the posts to find the filters
            $this->posts = get_posts($this->get_query_args());
            wp_reset_postdata();
        }

        if ( ! $echo ) {
            return parent::render(false);
        }
        parent::render();
    }


    /**
     * Set the number of columns for a given device
     *
     * @param $num
     * @param $device
     * @return $this
     */
    public function column($num, $device)
    {
        $this->columns[$device] = (12 / $num);

        return $this;
    }

    /**
     * Set the list of responsive columns
     *
     * @param $cols
     * @return $this
     */
    public function columns($cols)
    {
        $num_columns = (is_array($cols)) ? json_encode($cols) : str_replace('``','"', $cols);
        $columns = json_decode($num_columns);
        if ( ! empty($columns) ) {
            foreach($columns as $size => $column) {
                $this->columns[$size] = $column->width;
            }
        }
        return $this;
    }

    /**
     * Get list of classes used for each grid item
     *
     * @param null $post_id
     * @return string
     */
    public function get_item_classes($post_id = null)
    {
        // Get list of classes per item
        $classes = $this->get_column_classes();

        // Get classes matching taxonomy for filters
        if ( $this->use_filter ) {
            $post_id = ($post_id) ? $post_id : $GLOBALS['post']->ID;
            $classes = array_merge($classes, $this->get_filter_classes($post_id) );
        }

        return implode(' ', $classes);
    }

    /**
     * Set the grid to be filterable by a taxonomy
     *
     * @param $taxonomy
     * @return $this
     */
    public function filterBy($taxonomy)
    {
        $this->use_filter = true;
        $this->filter_taxonomy = $taxonomy;

        return $this;
    }

    /**
     * Set whether a show all filter will show
     *
     * @param bool $show
     * @return $this
     */
    public function filterShowAll($show = true)
    {
        $this->filter_all = $show;

        return $this;
    }

    /**
     * Get the list of filters
     */
    public function get_filters()
    {
        return wp_get_object_terms( $this->get_post_ids(), $this->filter_taxonomy );
    }

    /**
     * Get list of post ids
     *
     * @return array
     */
    protected function get_post_ids()
    {
        return array_map(function($post){
            return $post->ID;
        }, $this->posts);
    }

    /**
     * Get the bootstrap classes for the columns
     *
     * @param bool $as_string
     * @return array
     */
    public function get_column_classes($as_string = false)
    {
        $classes = array();
        foreach($this->columns as $size => $column) {
            $classes[] = "col-{$size}-{$column}";
        }
        return ($as_string) ? implode(' ', $classes) : $classes;
    }

    /**
     * Get the specific term classes per post
     *
     * @param $post_id
     * @return array
     */
    protected function get_filter_classes($post_id)
    {
        $terms = wp_get_post_terms( $post_id, $this->filter_taxonomy );
        $classes = array();
        foreach($terms as $term) {
            $classes[] = $term->slug;
        }
        return $classes;
    }
}