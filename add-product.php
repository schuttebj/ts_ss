<?php


add_action( 'wp_ajax_ss_dokan_create_new_product_new', 'ss_dokan_create_new_product_new' );
add_action( 'wp_ajax_nopriv_ss_dokan_create_new_product_new', 'ss_dokan_create_new_product_new' );

if(!function_exists('ss_dokan_create_new_product_new')) {
    function ss_dokan_create_new_product_new() {


        if ( ! is_user_logged_in() ) {
            echo json_encode([
                'error' => true,
                'message' => 'Please login to continue'
            ]);
            exit;
        }
        
        
        if ( ! dokan_is_user_seller( get_current_user_id() ) ) {
            echo json_encode([
                'error' => true,
                'message' => 'Current user is not a seller'
            ]);
            exit;
        }

        // if ( ! isset( $_POST['dokan_add_new_product_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['dokan_add_new_product_nonce'] ), 'dokan_create_new_product_new' )  ) {
        //     echo json_encode([
        //         'error' => true,
        //         'message' => 'Invalid Access'
        //     ]);
        //     exit;
        // }

        

        $data = $_POST;

        $cat_id = $parent_cat_id = $data['category_id'];

        if(!empty($_FILES['images']['name'])) {
            $data['product_image_gallery'] = save_images_in_media($_FILES['images']);
        }
        if(!empty($_FILES['dentimages']['name'])) {
            $data['product_image_gallery_second'] = save_images_in_media($_FILES['dentimages']);
        }


        
        $user_id = get_current_user_id();
        $store_settings  = dokan_get_store_info( $user_id );
        
        $client = new \TradeSafe\Helpers\TradeSafeApiClient();

        $user = new WP_User( $user_id );
        if(isset($_POST['ss_action']) && $_POST['ss_action'] == "ss_save_account_details") {

            $user_data = wp_update_user( array( 
                'ID' => $user_id, 
                'first_name' => $_POST['account_first_name'],
                'last_name' => $_POST['account_last_name']
            ));

            
            unset($store_settings['address']);

            $store_settings['address']['street_1'] = $_POST['billing_address_1'];
            $store_settings['address']['city'] = $_POST['billing_city'];
            $store_settings['address']['zip'] = $_POST['billing_postcode'];
            $store_settings['address']['state'] = $_POST['billing_state'];
            $store_settings['address']['country'] = "ZA";
            
            update_user_meta( $user_id, 'dokan_profile_settings', $store_settings );

            update_user_meta( $user_id, 'dokan_store_phone', $_POST['tradesafe_token_mobile'] );
            $_REQUEST['save-account-details-nonce'] = wp_create_nonce( 'save_account_details' );
            $_POST['action'] = 'save_account_details';
            $_POST['account_email'] = $user->user_email;

            do_action( 'woocommerce_save_account_details', $user->ID );
            
        }

        if($cat_id == 352) {
            ss_create_tour_product($data);
            exit;
        }

        if(!empty($data['sub-cat'])) {
            $cat_id = $data['sub-cat'];
        }
        if ( $data['brand'] != 'Other'){
            $post_title = $data['brand'] .' ' .$data['model'];
            $post_content = $post_excerpt = '';
        }
        else{
            $post_title = $data['model'];  
            $post_content = $post_excerpt = '';
        }


        if(!empty($data['skip_image'])) {
            $product_status = "images-required";
        } else {
            $product_status = dokan_get_new_post_status();
        }
        if($data['option'] == "auction" || $data['condition'] == "used") {
            $post_data = apply_filters( 'dokan_insert_auction_product_post_data', array(
                'post_type'    => 'product',
                'post_status'  => $product_status,
                'post_title'   => $post_title,
                'post_content' => $post_content,
                'post_excerpt' => $post_excerpt,
                'post_author'  => dokan_get_current_user_id()
            ) );
        } else {
            $post_data = array(
                'post_type'    => 'product',
                'post_status'  => $product_status,
                'post_title'   => $post_title,
                'post_content' => $post_content,
                'post_excerpt' => $post_excerpt,
                'post_author'  => dokan_get_current_user_id()
            );
        }
        

        $product_id = wp_insert_post( $post_data );

 

    
        if ( isset( $data['product_image_gallery'] ) ) {
            $attachment_ids = array_filter( explode( ',', wc_clean( $data['product_image_gallery'] ) ) );
            if(!empty($attachment_ids)) {
                set_post_thumbnail( $product_id, $attachment_ids[0] );
                unset($attachment_ids[0]);
                if(!empty($attachment_ids)) {
                    update_post_meta( $product_id, '_product_image_gallery', implode( ',', $attachment_ids ) );
                }
            }
            
            
        }
        if ( isset( $data['product_image_gallery_second'] ) ) {
            $attachment_ids = array_filter( explode( ',', wc_clean( $data['product_image_gallery_second'] ) ) );
            update_post_meta( $product_id, 'second_hand_images', $attachment_ids );
        }

        wp_set_object_terms( $product_id, (int) $cat_id, 'product_cat' );



        if($data['option'] == "auction" || $data['condition'] == "used") {
            global $woocommerce_auctions;
            wp_set_object_terms( $product_id, 'auction', 'product_type' );
            $woocommerce_auctions->product_save_data( $product_id, get_post( $product_id ) );

            $days = get_option('ss_auction_end_duration') ?? 7;
            update_post_meta( $product_id, '_auction_dates_from', date('Y-m-d 00:00') );
            update_post_meta( $product_id, '_auction_dates_to', date('Y-m-d 00:00',strtotime('+' . $days . ' day')) );
            
            /**
             * re list for auction
             */
            $hours = $days*24;
            update_post_meta( $product_id, '_auction_automatic_relist', 'yes' );
            update_post_meta( $product_id, '_auction_relist_fail_time', 24 );
            update_post_meta( $product_id, '_auction_relist_not_paid_time', 48 );
            update_post_meta( $product_id, '_auction_relist_duration', 120 );


            $amount = 0;
            if(in_array($parent_cat_id, [153, 156, 155, 154, 157, 158, 342, 164, 343]) ) {
                $amount = 100;
            }elseif($parent_cat_id == 162) {
                $amount = 50;
            }elseif($parent_cat_id == 161) {
                $amount = 500;
            }
            
            update_post_meta( $product_id, '_auction_bid_increment', $amount );
            
            
        } else {
            wp_set_object_terms( $product_id, 'simple', 'product_type' );
        }
        
        

        $attributes_data = array();
        switch($cat_id) {
            
            case 153: //Drivers
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Hand',
                    'options' => array($data['hand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Loft',
                    'options' => array($data['loft']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Shaft Weight',
                    'options' => array($data['shaft-weight']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Shaft Flex',
                    'options' => array($data['shaft-flex']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'driver_shaft_model', $data['shaft'] );
                update_post_meta($product_id, '_weight', 0.5);
                update_post_meta($product_id, '_length', 25);
                update_post_meta($product_id, '_width', 25);
                update_post_meta($product_id, '_height', 130);
                break;
            case 154: //Woods
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Hand',
                    'options' => array($data['hand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Club Number',
                    'options' => array($data['number']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Loft',
                    'options' => array($data['loft']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Shaft Weight',
                    'options' => array($data['shaft-weight']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Shaft Flex',
                    'options' => array($data['shaft-flex']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'wood_shaft_model', $data['shaft'] );
                update_post_meta($product_id, '_weight', 0.5);
                update_post_meta($product_id, '_length', 20);
                update_post_meta($product_id, '_width', 20);
                update_post_meta($product_id, '_height', 130);
                break;
            case 155: //Utility Irons/Hybrids
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Hand',
                    'options' => array($data['hand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Club Number',
                    'options' => array($data['number']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Loft',
                    'options' => array($data['loft']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Shaft Flex',
                    'options' => array($data['shaft-flex']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'utility_shaft_model', $data['shaft'] );
                update_post_meta($product_id, '_weight', 0.5);
                update_post_meta($product_id, '_length', 20);
                update_post_meta($product_id, '_width', 20);
                update_post_meta($product_id, '_height', 130);
                break;
            case 156: //Irons
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Hand',
                    'options' => array($data['hand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Shaft Flex',
                    'options' => array($data['shaft-flex']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Club Range',
                    'options' => array($data['club-range']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'iron_shaft_model', $data['shaft'] );
                update_post_meta($product_id, '_weight', 4);
                update_post_meta($product_id, '_length', 25);
                update_post_meta($product_id, '_width', 25);
                update_post_meta($product_id, '_height', 115);
                break;
            case 157: //Wedges
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Hand',
                    'options' => array($data['hand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Shaft Flex',
                    'options' => array($data['shaft-flex']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Loft',
                    'options' => array($data['loft']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Bounce',
                    'options' => array($data['bounce']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'wedge_shaft_model', $data['shaft'] );
                update_post_meta($product_id, '_weight', 0.6);
                update_post_meta($product_id, '_length', 15);
                update_post_meta($product_id, '_width', 15);
                update_post_meta($product_id, '_height', 100);
                break;
            case 158: //Putters
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Hand',
                    'options' => array($data['hand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Putter Type',
                    'options' => array($data['type']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 0.6);
                update_post_meta($product_id, '_length', 15);
                update_post_meta($product_id, '_width', 20);
                update_post_meta($product_id, '_height', 100);
                break;
            case 159: //Balls
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 0.65);
                update_post_meta($product_id, '_length', 18);
                update_post_meta($product_id, '_width', 13);
                update_post_meta($product_id, '_height', 5);
                break;
            case 161://Carts
                $attributes_data = array([
                    'name' => 'Cart Type',
                    'options' => array($data['cart-type']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                break;
            case 301://Belts
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Clothing Sizes Inches',
                    'options' => array($data['clothing-sizes-inches']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 0.1);
                update_post_meta($product_id, '_length', 25);
                update_post_meta($product_id, '_width', 20);
                update_post_meta($product_id, '_height', 5);
                break;
            case 299://Shorts
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Clothing Sizes Inches',
                    'options' => array($data['clothing-sizes-inches']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 0.4);
                update_post_meta($product_id, '_length', 40);
                update_post_meta($product_id, '_width', 30);
                update_post_meta($product_id, '_height', 1);
                break;
            case 298://Trousers
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Clothing Sizes Inches',
                    'options' => array($data['clothing-sizes-inches']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 0.8);
                update_post_meta($product_id, '_length', 40);
                update_post_meta($product_id, '_width', 30);
                update_post_meta($product_id, '_height', 1);
                break;
            case 297://Shirts
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Clothing Sizes normal',
                    'options' => array($data['clothing-sizes-normal']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 0.25);
                update_post_meta($product_id, '_length', 40);
                update_post_meta($product_id, '_width', 30);
                update_post_meta($product_id, '_height', 1);
                break;
            case 300://Caps
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Clothing Sizes normal',
                    'options' => array($data['clothing-sizes-normal']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 0.2);
                update_post_meta($product_id, '_length', 30);
                update_post_meta($product_id, '_width', 20);
                update_post_meta($product_id, '_height', 20);
                break;
            case 302://Jackets
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Clothing Sizes normal',
                    'options' => array($data['clothing-sizes-normal']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 1.2);
                update_post_meta($product_id, '_length', 40);
                update_post_meta($product_id, '_width', 30);
                update_post_meta($product_id, '_height', 1);
                break;
            case 303://Shoes
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Shoe Sizes',
                    'options' => array($data['shoe-sizes']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 1.2);
                update_post_meta($product_id, '_length', 30);
                update_post_meta($product_id, '_width', 18);
                update_post_meta($product_id, '_height', 14);
                break;
            case 335://Range Finder
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'function', $data['function'] );
                update_post_meta($product_id, '_weight', 0.5);
                update_post_meta($product_id, '_length', 20);
                update_post_meta($product_id, '_width', 10);
                update_post_meta($product_id, '_height', 10);
                break;
            case 336://Watches
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'function', $data['function'] );
                update_post_meta($product_id, '_weight', 0.5);
                update_post_meta($product_id, '_length', 20);
                update_post_meta($product_id, '_width', 10);
                update_post_meta($product_id, '_height', 10);
                break;
            case 337://Push Carts
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'function', $data['function'] );
                update_post_meta($product_id, '_weight', 9);
                update_post_meta($product_id, '_length', 40);
                update_post_meta($product_id, '_width', 35);
                update_post_meta($product_id, '_height', 60);
                break;
            case 338://Towels
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'function', $data['function'] );
                update_post_meta($product_id, '_weight', 0.4);
                update_post_meta($product_id, '_length', 40);
                update_post_meta($product_id, '_width', 30);
                update_post_meta($product_id, '_height', 1);
                break;
            case 339://Training Aids
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'function', $data['function'] );
                update_post_meta($product_id, '_weight', 1);
                update_post_meta($product_id, '_length', 40);
                update_post_meta($product_id, '_width', 30);
                update_post_meta($product_id, '_height', 20);
                break;
            case 340://Other Accessories
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                add_post_meta( $product_id, 'function', $data['function'] );
                update_post_meta($product_id, '_weight', 2);
                update_post_meta($product_id, '_length', 40);
                update_post_meta($product_id, '_width', 30);
                update_post_meta($product_id, '_height', 20);
                break;
            case 342://Shafts
                $attributes_data = array([
                    'name' => 'Shaft Flex',
                    'options' => array($data['shaft-flex']),
                    'visible' => 1, 
                    'variation' => 0
                ],[
                    'name' => 'Shaft Weight',
                    'options' => array($data['shaft-weight']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 0.2);
                update_post_meta($product_id, '_length', 10);
                update_post_meta($product_id, '_width', 10);
                update_post_meta($product_id, '_height', 100);
                break;
            case 347://Stand Bag
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 1.5);
                update_post_meta($product_id, '_length', 35);
                update_post_meta($product_id, '_width', 30);
                update_post_meta($product_id, '_height', 100);
                break;
            case 348://Tour Bag
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 6);
                update_post_meta($product_id, '_length', 40);
                update_post_meta($product_id, '_width', 30);
                update_post_meta($product_id, '_height', 100);
                break;
            case 349://Cart Bag
                $attributes_data = array([
                    'name' => 'Brand',
                    'options' => array($data['brand']),
                    'visible' => 1, 
                    'variation' => 0
                ]);
                update_post_meta($product_id, '_weight', 2.5);
                update_post_meta($product_id, '_length', 40);
                update_post_meta($product_id, '_width', 30);
                update_post_meta($product_id, '_height', 100);
                break;
        }
        

        if($data['condition'] == "new") {
            if(!empty($data['stock-quantity'])) {
                update_post_meta($product_id, '_manage_stock', 'yes');
                update_post_meta($product_id, '_stock', $data['stock-quantity']);
            }
            array_push($attributes_data,[
                'name' => 'Condition',
                'options' => array('New'),
                'visible' => 1, 
                'variation' => 0
            ]);
        } elseif($data['condition'] == "used") {
            array_push($attributes_data,[
                'name' => 'Condition Rating',
                'options' => array($data['condition-rating']),
                'visible' => 1, 
                'variation' => 0
            ]);
            array_push($attributes_data,[
                'name' => 'Age',
                'options' => array($data['age']),
                'visible' => 1, 
                'variation' => 0
            ]);
            array_push($attributes_data,[
                'name' => 'Condition',
                'options' => array('Secondhand'),
                'visible' => 1, 
                'variation' => 0
            ]);
            add_post_meta( $product_id, '_auction_item_condition', $data['condition'] );
            
        }
        
        if(!empty($attributes_data)) {
            ss_set_attribute($product_id,$attributes_data);
        }
        
        

        update_post_meta( $product_id, '_visibility', 'visible' );
        update_post_meta( $product_id, '_stock_status', 'instock' );
        
        $useridfull = 'user_' .dokan_get_current_user_id();
        $entriestotal = get_field('entries_number', $useridfull);
        $entriestotal = $entriestotal + 1;
        update_field( 'entries_number', $entriestotal, $useridfull );
        update_field( 'active_listing', 0, $useridfull );

        //wight and dim
        
 
        
        setcookie("productpushiddinal", $product_id , time()+3600, "/");

        if($data['option'] == "auction" || $data['condition'] == "used") {
            
            update_post_meta($product_id, '_auction_start_price', $data['ideal-price']);
            update_post_meta($product_id, '_auction_reserved_price', $data['reserve-price']);
            update_post_meta($product_id, '_regular_price', $data['buy-now-price']);
            update_post_meta($product_id, '_stock', 1);
            update_post_meta($product_id, '_manage_stock', 'yes');
            update_post_meta($product_id, '_auction_type', 'normal');
            

        } else {
            update_post_meta($product_id, '_sale_price', $data['selling-price']);
            update_post_meta($product_id, '_price', $data['selling-price']);
            if(!empty($data['retail-price'])) {
                update_post_meta($product_id, '_regular_price', $data['retail-price']);
            }
        }


        echo json_encode([
            'error' => false
        ]);

        exit;

    }
}



function ss_set_attribute($product_id, $attributes_data) {
    if( sizeof($attributes_data) > 0 ){
        $attributes = array(); // Initializing
    
        // Loop through defined attribute data
        foreach( $attributes_data as $key => $attribute_array ) {
            if( isset($attribute_array['name']) && isset($attribute_array['options']) ){
                // Clean attribute name to get the taxonomy
                $taxonomy = 'pa_' . wc_sanitize_taxonomy_name( $attribute_array['name'] );
    
                $option_term_ids = array(); // Initializing
    
                // Loop through defined attribute data options (terms values)
                foreach( $attribute_array['options'] as $option ){
                    if( term_exists( $option, $taxonomy ) ){
                        // Save the possible option value for the attribute which will be used for variation later
                        wp_set_object_terms( $product_id, $option, $taxonomy, true );
                        // Get the term ID
                        $option_term_ids[] = get_term_by( 'name', $option, $taxonomy )->term_id;
                    } else {
                        
                        $insert= wp_insert_term($option, $taxonomy);
                        $option_term_ids[] = $insert['term_id'];
                    }
                }
            }
            // Loop through defined attribute data
            $attributes[$taxonomy] = array(
                'name'          => $taxonomy,
                'value'         => $option_term_ids, // Need to be term IDs
                'position'      => $key + 1,
                'is_visible'    => $attribute_array['visible'],
                'is_variation'  => $attribute_array['variation'],
                'is_taxonomy'   => '1'
            );
        }

        // Save the meta entry for product attributes
        update_post_meta( $product_id, '_product_attributes', $attributes );
    }
}



function ss_create_tour_product($data) {

    $post_title = $data['tour_title'];
    $cat_id = $data['category_id'];
    $post_content = $post_excerpt = '';
    $product_status = dokan_get_new_post_status();
    $post_data = array(
        'post_type'    => 'product',
        'post_status'  => $product_status,
        'post_title'   => $post_title,
        'post_content' => $post_content,
        'post_excerpt' => $post_excerpt,
        'post_author'  => dokan_get_current_user_id()
    );    
    
    $product_id = wp_insert_post( $post_data );


    if ( isset( $data['product_image_gallery'] ) ) {
        $attachment_ids = array_filter( explode( ',', wc_clean( $data['product_image_gallery'] ) ) );
        if(!empty($attachment_ids)) {
            set_post_thumbnail( $product_id, $attachment_ids[0] );
            unset($attachment_ids[0]);
            if(!empty($attachment_ids)) {
                update_post_meta( $product_id, '_product_image_gallery', implode( ',', $attachment_ids ) );
            }
        }
        
        
    }

    wp_set_object_terms( $product_id, (int) $cat_id, 'product_cat' );
    wp_set_object_terms( $product_id, 'simple', 'product_type' );

    

    add_post_meta( $product_id, 'available_from', $data['tour_available_from'] );
    add_post_meta( $product_id, 'available_to', $data['tour_available_to'] );
    add_post_meta( $product_id, 'number_of_people', $data['tour_number_of_people'] );
    add_post_meta( $product_id, 'price_per_person', $data['tour_price_per_person'] );
    add_post_meta( $product_id, 'location', $data['tour_location'] );

    update_post_meta($product_id, '_regular_price', $data['retail-price']);
    update_post_meta($product_id, '_price', $data['retail-price']);

    update_post_meta( $product_id, '_visibility', 'visible' );
    update_post_meta( $product_id, '_stock_status', 'instock' );
    
    $useridfull = 'user_' .dokan_get_current_user_id();
    $entriestotal = get_field('entries_number', $useridfull);
    $entriestotal = $entriestotal + 1;
    update_field( 'entries_number', $entriestotal, $useridfull );
    update_field( 'active_listing', 0, $useridfull );
    
      echo "
        <script>
          (function() {
            var el = document.createElement('script');
            el.setAttribute('src', '//cdn.trackmytarget.com/tracking/s/checkout.min.js');
            el.setAttribute('data-type', 'lead');
            el.setAttribute('data-offer-sid', 'l520fe');
            el.setAttribute('data-event-sid', 'v4723e');
            el.setAttribute('data-id', '<?php echo $product_id; ?>');
            document.body.appendChild(el);
          })();
        </script>";




    echo json_encode([
        'error' => false
    ]);
}


function save_images_in_media($images_arr) {
    require_once(__DIR__ ."/classes/ImageUpload.php");
    $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');

    $attachments = [];
    for($i = 0; $i < count($images_arr['name']); $i++) {
        if (in_array($images_arr['type'][$i], $arr_img_ext)) {
                $image_url = $images_arr['tmp_name'][$i];
                $create_image = new SsImageUpload( $image_url ,0 , $images_arr['name'][$i] );
                $image_id = $create_image->attachment_id;
                $attachments[] = $image_id;
        }
    }
    
    return implode(",", $attachments);
}








