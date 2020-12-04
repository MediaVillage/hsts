<?php
/**
 * Component: A Woocommerce cart panel that displays in an offcanvas
 * 
 * @author Red Fern
 * @version  1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RF_Component_Cart_Panel {

	/**
	 * The component name (used for template directory)
	 * @var string
	 */
	public static $name = 'cart_panel';

	/**
	 * Initialize cart panel hooks
	 */
	public static function init()
	{
		// If Woocommerce is not installed then dont continue
		if ( ! class_exists('WooCommerce') ) return;

  		// Add the offcanvas element to the footer as its on every page
        add_action( 'wp_footer', array( __CLASS__, 'footer' ) );

         // Update the cart count on add to cart
        add_filter( 'add_to_cart_fragments', array( __CLASS__, 'add_to_cart_count_fragment' ) );
        add_filter( 'add_to_cart_fragments', array( __CLASS__, 'side_panel_fragment' ) );

        // Remove items from cart
        add_action( 'wp_ajax_rftheme_remove_from_cart', array( __CLASS__, 'remove_from_cart' ) );
        add_action( 'wp_ajax_nopriv_rftheme_remove_from_cart', array( __CLASS__, 'remove_from_cart' ) );
	}

    /**
     * Add shop overlay to footer with cart offcanvas
     */
    public static function footer()
    {
        echo '<div class="shop-overlay"></div>';
        self::render();
    }

	/**
     * Render the panel HTML
     */
    public static function render() {
        $default_path = RFFramework()->path() . '/templates/components/' . static::$name;
        $template_path = RFFramework()->template_path() . 'components/' . trailingslashit(static::$name);
        ob_start();
        include_once( rf_locate_template( 'default.php', $template_path, $default_path) );
        echo ob_get_clean();
    }

    /**
     * Update the cart count on cart item add
     *
     * @param $fragments
     * @return mixed
     */
    public static function add_to_cart_count_fragment( $fragments ) {
        global $woocommerce;
        ob_start();
        ?>
        <span class="cart-count"><?php echo count(WC()->cart->get_cart()); ?></span>
        <?php
        $fragments['span.cart-count'] = ob_get_clean();
        return $fragments;
    }

    /**
     * Update the cart fragment on add
     *
     * @param $fragments
     * @return mixed
     */
    public static function side_panel_fragment( $fragments ) {
        global $woocommerce;
        ob_start();
        self::render();
        $fragments['div.shop-panel'] = ob_get_clean();
        return $fragments;
    }

    /**
     * Remove an item from the cart
     */
    public static function remove_from_cart() {
        global $woocommerce;
        $cart_item_key = isset($_POST['cart_item']) ? sanitize_text_field( $_POST['cart_item'] ) : '';

        if ( $cart_item_key ) {
            $cart = $woocommerce->cart;
            $cart->set_quantity($cart_item_key,0);
        }

        if ( $woocommerce->tax_display_cart == 'excl' ) {
            $totalamount  = wc_price($woocommerce->cart->get_total());
        } else {
            $totalamount  = wc_price($woocommerce->cart->cart_contents_total + $woocommerce->cart->tax_total);
        }

        wp_send_json(array('amount' => $totalamount, 'quantity' => count(WC()->cart->get_cart())));
        die;
    }
}

/**
 * Display the button which will toggle the cart panel
 * 
 * @return html
 */
function rf_cart_panel_toggle()
{
	ob_start();
	?>
	<a href="#" class="cart-link">
		<span class="cart-icon"><span class="hidden-text">Cart</span>
    	<span class="cart-count">0</span>
    </span></a>
    <?php
    $html = ob_get_clean();

    // Allow child theme, 3rd party plugins to overwrite html
    echo apply_filters( 'rf_cart_panel_toggle', $html );
}