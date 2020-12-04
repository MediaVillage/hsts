<div class="shop-panel">
    <div class="shop-panel__inner">
        <div class="shop-panel__header">
            <div class="shop-panel__header_inner">
                <span class="cart-heading">Cart</span>
                <a href="#" class="shop-panel-close">Close</a>
            </div>
        </div>
        <div class="shop-panel__products">
            <?php
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ):
                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                $variation_id_class = '';
                if ( $cart_item['variation_id'] > 0 ) {
                    $variation_id_class = 'product-var-id-' .  $cart_item['variation_id'];
                }
                ?>

                <div class="shop-panel__product cart-product-<?php echo $cart_item_key; ?>">


                    <div class="cart-product-thumbnail">
                        <?php
                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('cart_thumb'), $cart_item, $cart_item_key );
                        if ( ! $product_permalink ) {
                            echo $thumbnail;
                        } else {
                            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                        }
                        ?>
                    </div>
                    <div class="cart-product-details">
                        <div class="cart-product-title"><a href="<?php echo $product_permalink; ?>"><?php echo $_product->post->post_title; ?></a></div>
                        <div class="cart-product-price"><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?></div>
                    </div>

                    <?php
                    echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                        '<a href="%s" class="remove-product" title="%s" data-cart-item="%s" data-product_sku="%s">&times;</a>',
                        esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
                        __( 'Remove this item', 'woocommerce' ),
                        esc_attr( $cart_item_key ),
                        esc_attr( $_product->get_sku() )
                    ), $cart_item_key );
                    ?>

                </div>
            <?php endforeach; ?>
            <div class="shop-panel__products__empty">
                <p>There are currently no products in the cart.</p>
            </div>
        </div>

        <div class="shop-panel__footer">
            <div class="shop-panel__total">
                <span class="total-label">Subtotal</span>
                <span class="total-amount"><?php echo WC()->cart->get_cart_total(); ?>
            </div>
            <a href="<?php echo wc_get_cart_url(); ?>" class="btn-secondary btn-full">Edit Cart</a>
            <a href="<?php echo wc_get_checkout_url(); ?>" class="btn-primary btn-full">Checkout</a>
        </div>
    </div>
</div>