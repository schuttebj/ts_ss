(function( $ ){

    $(document).ready(function(){
        if(typeof loadpop !== "undefined") {
            ss_open_ap_modal();
        }

        $(".dokan-cutom-add-new-product").on("click", function(){
            ss_open_ap_modal();
        });

        function ss_open_ap_modal() {
            let width = screen.width;
            $("#pro_added").val(0);
            if(width <= 768) {
                $(".bigscreen").remove();
            } else {
                $(".smallscreen").remove();
            }
            $("#dokan-modal-add-new-product-form")[0].reset();
            $(".steps").removeClass('show');
            $(".step1").addClass('show');
            $(".add-product-modal").modal('show');
        }

        $('.add-product-modal').on('hidden.bs.modal', function () {
            if($("#pro_added").val() < 1) {
                $.ajax({
                    url: dokan.ajaxurl,
                    type:"post",
                    data:{action:"ss_add_product_popup_closed"}
                });
            }
            
        });

    })

 })( jQuery );





 function dokan_ss_show_edit_modal(e, product_id) {
    e.preventDefault();

    jQuery(".edit-img-modal-spinner").removeClass('d-none');
    jQuery(".dokan-product-gallery-third").addClass('d-none');
    jQuery(".dokan-product-gallery-fourth").addClass('d-none');
    jQuery(".ex-img").remove();
    jQuery("#product_image_gallery_third").val("");
    jQuery("#product_image_gallery_third").val("");
    jQuery("#ss_img_product_id").val(product_id);
    jQuery("#ss_img_product_type").val("");
    jQuery(".ss_add_edit_image_modal").modal('show');

    var data = {
        action:'dokan_ss_get_product_image',
        product_id:product_id
    };

    jQuery.post( dokan.ajaxurl, data, function( resp ) {
        jQuery(".edit-img-modal-spinner").addClass('d-none');
        resp = JSON.parse(resp);
        if(resp.product_image_gallery_html!="") {
            jQuery(resp.product_image_gallery_html).insertBefore( jQuery(".product_images_third").find('li.add-image-third') );
            jQuery("#product_image_gallery_third").val(resp.product_image_gallery_ids);
        }

        jQuery(".dokan-product-gallery-third").removeClass('d-none');

        if(resp.auction_item_condition == "used") {
            jQuery("#ss_img_product_type").val("used");
            jQuery(".dokan-product-gallery-fourth").removeClass('d-none');

            if(resp.second_hand_images_html!="") {
                jQuery(resp.second_hand_images_html).insertBefore( jQuery(".product_images_fourth").find('li.add-image-fourth') );
                jQuery("#product_image_gallery_fourth").val(resp.second_hand_images_ids);
            }
        }
    });
}


