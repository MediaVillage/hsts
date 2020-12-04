<?php
/**
 * Menu specific settings
 *
 * @author Red Fern
 * @version 1.0.0
 */
defined( 'ABSPATH' ) or die;

class RF_Admin_Menu_Settings {

    /**
     * @var array
     */
    public static $menu_items = array();

    /**
     * Initialize actions/filters
     */
    public static function init()
    {
        add_filter( 'wp_nav_menu_objects', array( __CLASS__, 'menu_options' ), 10, 2 );
        add_action( 'wp_ajax_rf_menu_items', array( __CLASS__, 'ajax_menu_items' ) );
        add_action( 'wp_ajax_rf_menu_item_settings', array( __CLASS__, 'ajax_update_menu_item_settings' ) );

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', array( __CLASS__, 'menu_admin_scripts' ) );
        }
    }

    /**
     * Enqueue menu admin scripts
     * @param $hook
     */
    public static function menu_admin_scripts( $hook )
    {
        if( 'nav-menus.php' != $hook )
            return;

        wp_enqueue_style(
            'rf-menu-admin',
            RFFramework()->url() . '/assets/css/menu-admin.css'
        );
        wp_enqueue_script(
            'rf-menu-admin',
            get_template_directory_uri() . '/js/menu-admin.js',
            array( 'jquery', 'backbone', 'jquery-ui-sortable' ),
            false,
            true
        );
    }

    /**
     * Fetch menu item children
     */
    public static function ajax_menu_items()
    {
        $menu_id = $_GET['menu_id'];
        $menu_item_id = $_GET['menu_item_id'];

        if ( empty( self::$menu_items ) ) {
            $items = wp_get_nav_menu_items( $menu_id );
            self::$menu_items = $items ? self::buildTree( $items, 0 ) : null;
        }

        foreach(self::$menu_items as $item_id => $item) {
            if ( $menu_item_id == $item_id && isset($item->children) ) {
                wp_send_json(array_values($item->children));
                exit;
            }
        }

        wp_send_json(array());
        exit;
    }

    /**
     * Save the menu item settings
     * 
     * @return [type] [description]
     */
    public function ajax_update_menu_item_settings()
    {
        print '<pre>';
        print_r('TEST');
        print '</pre>';
        die;
    }

    /**
     * Build the navigation tree
     *
     * @param array $elements
     * @param int $parentId
     * @return array
     */
    public static function buildTree( array &$elements, $parentId = 0 )
    {
        $branch = array();
        foreach ( $elements as &$element )
        {
            if ( $element->menu_item_parent == $parentId )
            {
                $children = self::buildTree( $elements, $element->ID );
                if ( $children )
                    $element->children = $children;

                $branch[$element->ID] = $element;
                unset( $element );
            }
        }
        return $branch;
    }


    /**
     * Menu options
     *
     * @param $menu_items
     * @param $args
     * @return mixed
     */
    public static function menu_options($menu_items, $args)
    {
        return $menu_items;
    }
}

RF_Admin_Menu_Settings::init();