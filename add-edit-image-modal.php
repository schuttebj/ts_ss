<?php
add_action('init', 'ss_init_function');


function ss_init_function() {
    
    if(!empty($_POST['ss_img_product_btn'])) {
        $product_id = $_POST['ss_img_product_id'];
        if(empty($_POST['product_image_gallery_third'])) {
            header("location: " . get_site_url(). "/dashboard/products?message=invalid-input");
            exit;
        }

        $attachment_ids = array_filter( explode( ',', wc_clean( $_POST['product_image_gallery_third'] ) ) );
        set_post_thumbnail( $product_id, $attachment_ids[0] );
        unset($attachment_ids[0]);
        update_post_meta( $product_id, '_product_image_gallery', implode( ',', $attachment_ids ) );

        if($_POST['ss_img_product_type'] == "used") {
            $second_hand_images = array_filter( explode( ',', wc_clean( $_POST['product_image_gallery_fourth'] ) ) );
            
            
            update_post_meta( $product_id, 'second_hand_images',  $second_hand_images);
        }

        $product_status = dokan_get_new_post_status();
        wp_update_post( array(
            'ID'    =>  $product_id,
            'post_status'   =>  $product_status
        ));

        header("location: " . get_site_url(). "/dashboard/products?message=img-updated");
        exit;
    }
}

add_action("wp_footer", 'ss_add_edit_image_modal');

function ss_add_edit_image_modal() {
    ?>

    <form id="add-edit-image" method="post" action="">

        <div class="modal fade ss_add_edit_image_modal" tabindex="-1" role="dialog" aria-labelledby="ss_add_edit_image_modal" aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Edit Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class='edit-img-modal-spinner'>
                    Checking for exiting images
                    <img src="<?=SS_PLUGIN_URL . '/assets/img/Spinner-3.gif'?>" alt="" width="10%">
                    </div>

                    <div class="dokan-product-gallery-third d-none">

                        <label for="">Select product images</label>

                        <div class="dokan-side-body" id="dokan-product-images-third">

                            <div id="product_images_container_third">

                                <ul class="product_images_third dokan-clearfix">



                                    <li class="add-image-third add-product-images-third tips" data-title="<?php esc_attr_e( 'Add product images', 'dokan-lite' ); ?>">

                                        <a href="#" class="add-product-images-third"><i class="fa fa-plus" aria-hidden="true"></i></a>

                                    </li>

                                </ul>

                                <input type="hidden" id="product_image_gallery_third" name="product_image_gallery_third" value="">

                            </div>

                        </div>

                    </div>



                    <div class="dokan-product-gallery-fourth d-none">

                        <label for="">Select product dent and defect image</label>

                        <div class="dokan-side-body" id="dokan-product-images-fourth">

                            <div id="product_images_container_fourth">

                                <ul class="product_images_fourth dokan-clearfix">



                                    <li class="add-image-fourth add-product-images-fourth tips" data-title="<?php esc_attr_e( 'Add image for dents', 'dokan-lite' ); ?>">

                                        <a href="#" class="add-product-images-fourth"><i class="fa fa-plus" aria-hidden="true"></i></a>

                                    </li>

                                </ul>

                                <input type="hidden" id="product_image_gallery_fourth" name="product_image_gallery_fourth" value="">

                            </div>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">

                    <input type="hidden" id="ss_img_product_id" name="ss_img_product_id" value="">

                    <input type="hidden" id="ss_img_product_type" name="ss_img_product_type" value="">

                    <input type="submit" name="ss_img_product_btn" class="save-images dokan-btn dokan-btn-theme " value="Save">

                </div>
                </div>

            </div>

        </div>

    </form>

    <?php

}





add_action( 'wp_ajax_dokan_ss_get_product_image', 'dokan_ss_get_product_image' );



function dokan_ss_get_product_image() {

    $product_id = $_POST['product_id'];



    $product   = wc_get_product( $product_id );

    $image_id  = $product->get_image_id();



    $_auction_item_condition = get_post_meta($product_id, '_auction_item_condition', true);

    $_product_image_gallery = get_post_meta($product_id, '_product_image_gallery', true);

    $second_hand_images = get_post_meta($product_id, 'second_hand_images', true);

    

    $_product_image_gallery_html = "";

    $_product_image_gallery_ids = "";



    if(!empty($image_id)) {

        $image_url = wp_get_attachment_image_url( $image_id );

        $_product_image_gallery_html .= '<li class="ex-img image" data-attachment_id="' . $image_id . '">

        <img src="' . $image_url . '" />

        <a href="#" class="action-delete-third">&times;</a>

        </li>';

        $_product_image_gallery_ids = $image_id;

    }



    if(!empty($_product_image_gallery)) {

        $_product_image_gallery_array = explode(",", $_product_image_gallery);

        foreach($_product_image_gallery_array as $image_id) {

            $image_url = wp_get_attachment_image_url( $image_id );

            $_product_image_gallery_html .= '<li class="ex-img image" data-attachment_id="' . $image_id . '">

            <img src="' . $image_url . '" />

            <a href="#" class="action-delete-third">&times;</a>

            </li>';

        }

        if(empty($_product_image_gallery_ids)) {

            $_product_image_gallery_ids = $_product_image_gallery;

        } else {

            $_product_image_gallery_ids = $_product_image_gallery_ids.",".$_product_image_gallery;

        }

    }



    $second_hand_images_html = "";

    $second_hand_images_ids = "";
    
    if(!empty($second_hand_images)) {

        $second_hand_images_array = $second_hand_images;

        foreach($second_hand_images_array as $image_id) {

            $image_url = wp_get_attachment_image_url( $image_id );

            $second_hand_images_html .= '<li class="ex-img image" data-attachment_id="' . $image_id . '">

            <img src="' . $image_url . '" />

            <a href="#" class="action-delete-fourth">&times;</a>

            </li>';

        }

        $second_hand_images_ids = implode(",",$second_hand_images);

    }







    echo json_encode([

        'auction_item_condition' => $_auction_item_condition,

        'product_image_gallery_html' => $_product_image_gallery_html,

        'product_image_gallery_ids' => $_product_image_gallery_ids,

        'second_hand_images_html' => $second_hand_images_html,

        'second_hand_images_ids' => $second_hand_images_ids,

    ]);







    

    

    //$image_url = wp_get_attachment_image_url( $image_id );

    





    exit;

}