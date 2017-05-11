<?php
/**
 * Created by PhpStorm.
 * User: andriy
 * Date: 08.05.17
 * Time: 11:33
 */

//REGISTER NEW POST TYPE
add_action('init', 'films_post_type');
function films_post_type()
{
    register_post_type(
        'films',
        array(
            'labels'        => array(
                'name'               => __('Films','twentyseventeen'),
                'singular_name'      => __('Film','twentyseventeen'),
                'add_new'            => __('Add Film','twentyseventeen'),
                'add_new_item'       => __('Add Film','twentyseventeen'),
                'edit_item'          => __('Edit Film','twentyseventeen'),
                'new_item'           => __('New Film','twentyseventeen'),
                'view_item'          => __('View Film','twentyseventeen'),
                'search_items'       => __('Search Films','twentyseventeen'),
                'not_found'          => __('Not Found','twentyseventeen'),
                'not_found_in_trash' => __('Not Found In Trash','twentyseventeen'),
                'parent_item_colon'  => __('', 'twentyseventeen'),
                'menu_name'          => __('Films','twentyseventeen'),
            ),
            'public'        => true,
            'menu_position' => 5,
            'supports'      => array(
                'title',
                'editor',
                'thumbnail',
                'excerpt',
                'custom-fields',
            ),
            'taxonomies'    => array(
                'films_taxonomy',
            ),
            'has_archive'   => true,
            'rewrite'       => array(
                'slug' => 'films',
            ),
        )
    );
}

//REGISTER TAXONOMY
add_action('init', 'films_register_taxonomy');
function films_register_taxonomy()
{
    register_taxonomy(
        'films_taxonomy',
        array(
            'films',
        ),
        array(
            'labels'            => array(
                'name'              => _x('Films Taxonomy', 'films', 'twentyseventeen'),
                'singular_name'     => _x('Film Taxonomy', 'films', 'twentyseventeen'),
                'menu_name'         => __('Film Taxonomy', 'twentyseventeen'),
                'all_items'         => __('All Taxonomy', 'twentyseventeen'),
                'edit_item'         => __('Edit taxonomy', 'twentyseventeen'),
                'view_item'         => __('View taxonomy', 'twentyseventeen'),
                'update_item'       => __('Update taxonomy', 'twentyseventeen'),
                'add_new_item'      => __('Add new taxonomy', 'twentyseventeen'),
                'new_item_name'     => __('Add name taxonomy', 'twentyseventeen'),
                'parent_item'       => __('Parent Category', 'twentyseventeen'),
                'parent_item_colon' => __('Parent Category:', 'twentyseventeen'),
                'search_items'      => __('Search Categories', 'twentyseventeen'),
            ),
            'publicly_queryable'    => false,
            'show_admin_column' => true,
            'hierarchical'      => true,
            'rewrite'           => array(
                'slug' => 'films/films_taxonomy',
            ),
        )
    );
}


/*********************************************************/
/******  ADD CUSTOM POST TYPE FILMS TO WOOCOMMERCE  ******/
/*********************************************************/


if ( class_exists( 'WooCommerce' ) ) {

    class WCCPT_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT
    {

        /**
         * Method to read a product from the database.
         * @param WC_Product
         */

        public function read(&$product)
        {

//        var_dump($product);
            $product->set_defaults();

            if (!$product->get_id() || !($post_object = get_post($product->get_id())) || !in_array($post_object->post_type, array('films', 'product'))) { // change birds with your post type
                throw new Exception(__('Invalid product.', 'woocommerce'));
            }

            $id = $product->get_id();

            $product->set_props(array(
                'name' => $post_object->post_title,
                'slug' => $post_object->post_name,
                'date_created' => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp($post_object->post_date_gmt) : null,
                'date_modified' => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp($post_object->post_modified_gmt) : null,
                'status' => $post_object->post_status,
                'description' => $post_object->post_content,
                'short_description' => $post_object->post_excerpt,
                'parent_id' => $post_object->post_parent,
                'menu_order' => $post_object->menu_order,
                'reviews_allowed' => 'open' === $post_object->comment_status,
            ));

            $this->read_attributes($product);
            $this->read_downloads($product);
            $this->read_visibility($product);
            $this->read_product_data($product);
            $this->read_extra_data($product);
            $product->set_object_read(true);
        }

        /**
         * Get the product type based on product ID.
         *
         * @since 3.0.0
         * @param int $product_id
         * @return bool|string
         */
        public function get_product_type($product_id)
        {
            $post_type = get_post_type($product_id);
            if ('product_variation' === $post_type) {
                return 'variation';
            } elseif (in_array($post_type, array('films', 'product'))) { // change birds with your post type
                $terms = get_the_terms($product_id, 'product_type');
                return !empty($terms) ? sanitize_title(current($terms)->name) : 'simple';
            } else {
                return false;
            }
        }
    }

}

add_filter( 'woocommerce_data_stores', 'woocommerce_data_stores' );

function woocommerce_data_stores ( $stores ) {
    $stores['product'] = 'WCCPT_Product_Data_Store_CPT';
    return $stores;
}

add_filter('woocommerce_get_price','reigel_woocommerce_get_price',20,2);
function reigel_woocommerce_get_price($price,$post){
    if ($post->post->post_type === 'films') // change this to your post type
        $price = get_post_meta($post->id, "price_film", true); // assuming your price meta key is price
    return $price;
}

add_filter('the_content','rei_add_to_cart_button', 20,1);
function rei_add_to_cart_button($content){
    global $post;
    if ($post->post_type !== 'films') {return $content; }

    ob_start();
    ?>
    <form action="" method="post" class="add-to-cart">
        <input name="add-to-cart" type="hidden" value="<?php echo $post->ID ?>" />
        <input name="quantity" type="number" value="1" min="1"  />
        <input name="submit" type="submit" value="Add to cart" />
    </form>
    <?php

    return $content . ob_get_clean();
}


/*********************************************************/
/*************  ADD SKYPE FIELD TO REGISTER FORM  ********/
/*********************************************************/

add_filter('add_to_cart_redirect', 'redirect_to_checkout');
function redirect_to_checkout()
{
    return wc_get_checkout_url();   

}

/*********************************************************/
/*************  ADD SKYPE FIELD TO REGISTER FORM  ********/
/*********************************************************/

function wooc_extra_register_fields() { ?>
    <p class="form-row form-row-first">
        <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
    </p>
    <p class="form-row form-row-last">
        <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
    </p>
    <p class="form-row form-row-wide">
        <label for="reg_billing_phone"><?php _e( 'Skype', 'woocommerce' ); ?></label>
        <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e( $_POST['billing_phone'] ); ?>" />
    </p>
    <div class="clear"></div>
    <?php
}
add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

/**
 * register fields Validating.
 */
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
    if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
        $validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
    }
    if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
        $validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
    }
    if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
        $validation_errors->add( 'billing_phone_name_error', __( '<strong>Error</strong>: Skype is required!.', 'woocommerce' ) );
    }
    return $validation_errors;
}

add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

/**
 * Below code save extra fields.
 */
function wooc_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['billing_first_name'] ) ) {
        //First name field which is by default
        update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        // First name field which is used in WooCommerce
        update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
    }
    if ( isset( $_POST['billing_last_name'] ) ) {
        // Last name field which is by default
        update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        // Last name field which is used in WooCommerce
        update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
    }
    if ( isset( $_POST['billing_phone'] ) ) {
        // Phone input filed which is used in WooCommerce
        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }

}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );


/*********************************************************/
/*****   Redirect after woocommerce registration   *******/
/*********************************************************/

add_filter('woocommerce_registration_redirect', 'ps_wc_registration_redirect');
function ps_wc_registration_redirect( $redirect_to ) {
    $redirect_to = home_url('/favourites');
    return $redirect_to;
}

/*********************************************************/
/********************  POPULAR COUNT  ********************/
/*********************************************************/

function wpb_set_post_views($postID) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function wpb_track_post_views($post_id) {
    if (!is_single()) return;
    if (empty ($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    wpb_set_post_views($post_id);
}

add_action('wp_head', 'wpb_track_post_views');
