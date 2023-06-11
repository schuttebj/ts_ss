<?php
add_action("wp_footer", 'ss_add_modal');

function ss_add_modal() {

    $taxonomy     = 'product_cat';
    $orderby      = 'id';  
    $show_count   = 0;      // 1 for yes, 0 for no
    $pad_counts   = 0;      // 1 for yes, 0 for no
    $hierarchical = 1;      // 1 for yes, 0 for no  
    $title        = '';  
    $empty        = 0;
    $args = array(

            'taxonomy'     => $taxonomy,
            'orderby'      => $orderby,
            'show_count'   => $show_count,
            'pad_counts'   => $pad_counts,
            'hierarchical' => $hierarchical,
            'title_li'     => $title,
            'hide_empty'   => $empty,
            'exclude' => array(15)
    );

    $all_categories = get_categories( $args );
    $parent_cat = $sub_cat = array();

    foreach($all_categories as $category) {
        if(empty($category->category_parent)) {
            $parent_cat[] = $category;
        } else {
            $sub_cat[$category->category_parent][] = $category;
        }
    }

    $client = new \TradeSafe\Helpers\TradeSafeApiClient();
    $banks              = $client->getEnum( 'UniversalBranchCode' );
    $bank_account_types = $client->getEnum( 'BankAccountType' );
    $organization_types = $client->getEnum( 'OrganizationType' );
    $user_tradesafe_token_id = get_user_meta(get_current_user_id(),'tradesafe_prod_token_id',true);

?>

<script>
    let sub_cat_json = <?=json_encode($sub_cat)?>;
</script>

<div class="modal fade add-product-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-body add-product-modal-1 <?=(!is_user_logged_in())?'logout-user-modal':''; ?>">
        <div class="row">
            <div class="col-md-3 left-sidebar">
                <img style="margin-top:20px;" alt="SwingSave" src="https://swingsave.com/wp-content/uploads/2021/06/Logo_White_Final.png" />
                <ul class="StepProgress">
                    <li class="StepProgress-item pstep1 is-done">Information</li>
                    <li class="StepProgress-item pstep2">Condition</li>
                    <li class="StepProgress-item pstep3">Images</li>
                    <li class="StepProgress-item pstep4">Pricing Details</li>
                    <?php if(!is_user_logged_in()) { ?>
                    <li class="StepProgress-item pstep5">Account</li>
                    <li class="StepProgress-item pstep6">Banking</li>
                    <?php } else {
                        $tradesafe_token_id = get_user_meta(get_current_user_id(),'tradesafe_prod_token_id',true);
                        if(empty($tradesafe_token_id)) { ?>
                        <li class="StepProgress-item pstep6">Account Details</li>
                    <?php }
                    } ?>
                    
                    
                </ul>
            </div>

            <div class="col-md-9 right-sidebar">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
                <div style="display:none">
                <div class="popup-error alert alert-danger d-none"></div>
                </div>
                <form action="" method="post" id="dokan-modal-add-new-product-form" enctype="multipart/form-data">
                    <div class="product-form-container">
                        <div class="steps step1 show">
                            <?php  include('includes/step1.php'); ?>
                        </div>  

                        <div class="steps step2">
                            <?php  include('includes/step2.php'); ?>
                        </div>

                        <div class="steps step3">
                            <?php  include('includes/step3.php'); ?>
                        </div>

                        <div class="steps step4">
                            <?php  include('includes/step4.php'); ?>
                        </div>

                        <div class="steps step5">
                            <?php  include('includes/step5.php'); ?>
                        </div>
                        <div class="steps step6">
                            <?php  include('includes/step6.php'); ?>
                        </div>
                        <div class="steps step7">
                            <?php  include('includes/step7.php'); ?>
                        </div>

                        <div class="steps laststep">
                            <div class="row">
                                <div class="col-12 text-center pop-spinner">
                                    <h3>Please wait adding your product</h3>
                                    <img src="<?=SS_PLUGIN_URL . '/assets/img/Settings.gif'?>" alt="" class="mx-auto d-block">
                                </div>
                                <div class="col-12 text-center added-msg d-none">
                                    <img src="<?=SS_PLUGIN_URL . '/assets/img/tick.png'?>" alt="" class="mx-auto d-block mb-4" style="height:150px;">
                                    <p>Congratulations! Your product has been submitted and will be approved within the next 6 hours.
                                        Once approved your product is live and other golfers can view your listing.
                                        <br><br>
                                        If you need anything else contact us here.
                                        <br><br>
                                        If you want to add more listings just  click the button below to start.
                                    </p>
                                    <input type="hidden" id="pro_added" value="">
                                    <p><a href="<?=site_url( '/dashboard/products/' ) . '?showpop=1'?>" class="btn btn-default btn-block">Add more items</a></p>
                                    <a href="<?=site_url( '/whats-next/' );?>" class="btn btn-secondary btn-block close1">Finished</a>
                                </div>
        
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>






<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&libraries=places&language=en-AU&key=AIzaSyCxVI5mTw6G2eYl1CbarXy0t72Cyryh7gI"></script>
    

    <script>
            
    
            
            let autocomplete;
            let address1Field;
            let address2Field;
            let postalField;
            let billing_state;
            
            function initAutocomplete(ele) {
              address1Field = ele.find(".billing_address_1")[0];
              address2Field = ele.find(".billing_city")[0];
              postalField = ele.find(".billing_postcode")[0];
              billing_state = ele.find(".billing_postcode")[0];
              // Create the autocomplete object, restricting the search predictions to
              // addresses in the US and Canada.
              autocomplete = new google.maps.places.Autocomplete(address1Field, {
                componentRestrictions: { country: ["za"] },
                fields: ["address_components", "geometry"],
                types: ["address"],
              });
              address1Field.focus();

              // When the user selects an address from the drop-down, populate the
              // address fields in the form.
              autocomplete.addListener("place_changed", fillInAddress);
            }
            
            function fillInAddress() {
              // Get the place details from the autocomplete object.
              const place = autocomplete.getPlace();
              let address1 = "";
              let postcode = "";
            
              for (const component of place.address_components) {
                const componentType = component.types[0];
                console.log(component);
                switch (componentType) {
                  case "street_number": {
                    address1 = `${component.long_name} ${address1}`;
                    break;
                  }
            
                  case "route": {
                    address1 += component.short_name;
                    break;
                  }
            
                  case "postal_code": {
                    postcode = `${component.long_name}${postcode}`;
                    break;
                  }
            
                  
                  case "locality": {
                    address2Field.value = component.short_name;
                    break;
                  }
                  case "administrative_area_level_1": {
                    billing_state.value = component.short_name;
                    break;
                  }
                  
                }
              }
            
              address1Field.value = address1;
              postalField.value = postcode;
              // After filling the form with address components from the Autocomplete
              // prediction, set cursor focus on the second address line to encourage
              // entry of subpremise information such as apartment, unit, or floor number.
              
            }
                </script>
<script>
jQuery(document).ready(function(){

    
    
    
    jQuery('.ap-input-images').imageUploader({
        'label': 'Add gallery image',
        'imagesInputName': 'images'
    });
    jQuery('.ap-input-dentimages').imageUploader({
        'label': 'Add image for dents',
        'imagesInputName': 'dentimages'
    });
    jQuery(".step1-next-button").on("click", function(){
        jQuery(".popup-error").html("").addClass('d-none');
        var category_slug = jQuery(".catradio:checked").data('slug');
        var category_id = jQuery(".catradio:checked").val();
        
        if(typeof category_slug  === "undefined") {
            jQuery(".popup-error").html("Please select category").removeClass('d-none');
            return false;
        }
        jQuery(".steps").removeClass('show');
        jQuery(".step2").addClass('show');

        if(sub_cat_json[category_id]) {
            var html = '<div class="form-label-group form-group col-md-12 subcat"><select name="sub-cat" id="sub-cat" class="form-control custom-select shadow-none">';
            html += '<option value="">Select Sub Category</option>';
            jQuery.each(sub_cat_json[category_id], function(key,value){
                html += '<option value="' + value.term_id + '" data-slug="' + value.slug + '">' + value.name + '</option>';
            });
            html += '</select><label for="sub-cat">Sub Category</label></div>';
            jQuery(".attributes:first").before(html);
        }

        $(".category_name").html(category_slug);

        if(category_id == 352 || category_id == 342 || category_id == 161) {
            $("."+category_slug).show();
        } else {
            $(".brand").show();
        }
    });

    jQuery("#brand").on("change",function(){
        var category_slug = jQuery(".catradio:checked").data('slug');
        var category_id = jQuery(".catradio:checked").val();
        jQuery("."+category_slug).show();
    });

    jQuery(document).on("change","#sub-cat",function(){
        var category_slug = jQuery(".catradio:checked").data('slug');
        var category_id = jQuery(".catradio:checked").val();

        if(category_id == 162) {
            var sub_category_slug = jQuery(this).find(':selected').data('slug');
            jQuery(".clothing").hide();
            jQuery("."+sub_category_slug).show();
        }
    });

    jQuery(".step2-back-button").on("click", function(){
        jQuery(".pstep2").removeClass('is-done')
        jQuery(".popup-error").html("").addClass('d-none');
        jQuery(".attributes").each(function(){
            if(jQuery(this).is(":visible")) {
                if(jQuery(this).find("select").length > 0) {
                    jQuery(this).find("select").val("");
                } else {
                    jQuery(this).find("input").val("");
                }
            }
        });

        jQuery(".steps").removeClass('show');
        jQuery(".step1").addClass('show');
        jQuery(".subcat").remove();
        $(".attributes").hide();
    });

    jQuery(".step2-next-button").on("click", function(){
        jQuery(".pstep2").addClass('is-done')
        jQuery(".popup-error").html("").addClass('d-none');
        if(jQuery("#sub-cat").length == 1 &&  jQuery("#sub-cat").val() == "") {
            jQuery("#sub-cat").addClass("is-invalid");
            jQuery(".popup-error").html("Please select sub category").removeClass('d-none');
            return false;
        } else {
            jQuery("#sub-cat").removeClass("is-invalid");
        }

        var error_msg = "";
        jQuery(".attributes").each(function(){
            if(jQuery(this).is(":visible")) {
                if(jQuery(this).find("select").length > 0) {
                    if(jQuery(this).find("select").val() == "") {
                        label = jQuery(this).find("label").attr("for").replace(/[^\w\s!?]/g,' ').toUpperCase();
                        error_msg += label + " is required<br>";
                        jQuery(this).find("select").addClass("is-invalid");
                    }else {
                        jQuery(this).find("select").removeClass("is-invalid");
                    }
                } else {
                    if(jQuery(this).find("input").val() == "") {
                        label = jQuery(this).find("label").attr("for").replace(/[^\w\s!?]/g,' ').toUpperCase();
                        error_msg += label + " is required<br>";
                        jQuery(this).find("input").addClass("is-invalid");
                    } else {
                        jQuery(this).find("input").removeClass("is-invalid");
                    }
                }
            }
        });

        if(error_msg != "") {
            jQuery(".popup-error").html(error_msg).removeClass('d-none');
            return false;
        }

        jQuery(".steps").removeClass('show');

        var category_id = jQuery(".catradio:checked").val();

        if(category_id == 352 || category_id == 159) {
            $("#new").attr("checked","checked");
            $("#sale").attr("checked","checked");
            jQuery(".step4").addClass('show');
        } else {
            jQuery(".step3").addClass('show');
        }
    });

    jQuery(".condition-radio").on("click", function(){
        jQuery(".cond-type").hide();
        var cond = $(this).val();
        jQuery("."+cond).show();
    });

    jQuery(".step3-next-button").on("click", function(){
        
        jQuery(".popup-error").html("").addClass('d-none');
        jQuery(".dokan-product-gallery-second").addClass('d-none');

        if(jQuery(".condition-radio:checked").length == 0) {
            jQuery(".popup-error").html("Please select Condition").removeClass('d-none');
            return false;
        }else if(jQuery(".condition-radio:checked").val() == "new") {
            if(jQuery(".option-radio:checked").length == 0) {
                jQuery(".popup-error").html("Please select option").removeClass('d-none');
                return false;
            }
        }else if(jQuery(".condition-radio:checked").val() == "used") {
            jQuery(".dokan-product-gallery-second").removeClass('d-none');
            
            if(jQuery(".bigscreen").find(".condition-rating:checked").length == 0 && 
                (typeof jQuery(".smallscreen").find(".condition-rating").val() === "undefined" ||
                    jQuery(".smallscreen").find(".condition-rating").val() == "") 
                ) {
                jQuery(".popup-error").html("Please select rating").removeClass('d-none');
                return false;
            }
            if(jQuery(".bigscreen").find(".age:checked").length == 0 && 
                (typeof jQuery(".smallscreen").find(".age").val() === "undefined" ||
                    jQuery(".smallscreen").find(".age").val() == "") 
                ) {
                jQuery(".popup-error").html("Please select age").removeClass('d-none');
                return false;
            }
        }
        jQuery(".pstep3").addClass('is-done');
        jQuery(".steps").removeClass('show');
        jQuery(".step4").addClass('show');
    });

    jQuery(".step3-back-button").on("click", function(){
        jQuery(".pstep2").removeClass('is-done');
        jQuery(".popup-error").html("").addClass('d-none');
        jQuery(".popup-error").html("").addClass('d-none');
        jQuery(".steps").removeClass('show');
        jQuery(".step2").addClass('show');
    });

    jQuery(".skip-step4").on("click", function(){
        jQuery(".pstep4").addClass('is-done');
        jQuery(".popup-error").html("").addClass('d-none');
        jQuery(".price-type").hide();
        jQuery("#skip_image").val(1);

        if(jQuery(".condition-radio:checked").val() == "used" || jQuery(".option-radio:checked").val() == "auction") {
            jQuery(".price-type-1").show();
        } else {
            jQuery(".price-type-2").show();
        }

        var category_id = jQuery(".catradio:checked").val();

        jQuery(".notour").show();

        if(category_id == 352) {
            jQuery(".notour").hide();
        }

        jQuery(".steps").removeClass('show');
        jQuery(".step5").addClass('show');
    });

    jQuery(".step4-next-button").on("click", function(){
        
        jQuery(".popup-error").html("").addClass('d-none');
        if(jQuery(".ap-input-images").find(".uploaded-image").length == 0) {
            jQuery(".popup-error").html("Please select atleast one image").removeClass('d-none');
            jQuery(".ap-input-images-error").removeClass('d-none');
            return false;
        } else {
            jQuery(".ap-input-images-error").addClass('d-none');
        }

        jQuery(".price-type").hide();

        if(jQuery(".condition-radio:checked").val() == "used" || jQuery(".option-radio:checked").val() == "auction") {
            jQuery(".price-type-1").show();
        } else {
            jQuery(".price-type-2").show();
        }

        var category_id = jQuery(".catradio:checked").val();
        jQuery(".pstep4").addClass('is-done');
        jQuery(".notour").show();
        if(category_id == 352) {
            jQuery(".notour").hide();
        }
        jQuery(".steps").removeClass('show');
        jQuery(".step5").addClass('show');
    });

    jQuery(".step4-back-button").on("click", function(){
        jQuery(".pstep3").removeClass('is-done');
        jQuery(".popup-error").html("").addClass('d-none');
        jQuery(".steps").removeClass('show');

        var category_id = jQuery(".catradio:checked").val();

        if(category_id == 352 || category_id == 159) {
            jQuery(".step2").addClass('show');
        } else {
            jQuery(".step3").addClass('show');
        }
    });

    jQuery(".step5-back-button").on("click", function(){
        jQuery(".pstep4").removeClass('is-done');
        jQuery(".popup-error").html("").addClass('d-none');
        jQuery(".steps").removeClass('show');
        jQuery(".step4").addClass('show');
    });

    jQuery(".step5-next-button").on("click", function(){

        jQuery(".popup-error").html("").addClass('d-none');

        var category_id = jQuery(".catradio:checked").val();

        if(jQuery(".condition-radio:checked").val() == "used" || jQuery(".option-radio:checked").val() == "auction") {

            if(jQuery("#ideal-price").val() == "" || isNaN(jQuery("#ideal-price").val())) {
                jQuery(".popup-error").html("Please enter ideal price").removeClass('d-none');
                jQuery("#ideal-price").addClass("is-invalid");
                return false;
            } else {
                jQuery("#ideal-price").removeClass("is-invalid");
            }
            if(jQuery("#buy-now-price").val() == "" || isNaN(jQuery("#buy-now-price").val())) {
                jQuery(".popup-error").html("Please enter buy now price").removeClass('d-none');
                jQuery("#buy-now-price").addClass("is-invalid");
                return false;
            } else {
                jQuery("#buy-now-price").removeClass("is-invalid");
            }

            if(jQuery("#reserve-price").val() == "" || isNaN(jQuery("#reserve-price").val())) {
                jQuery(".popup-error").html("Please enter reserve price").removeClass('d-none');
                jQuery("#reserve-price").addClass("is-invalid");
                return false;
            } else {
                jQuery("#reserve-price").removeClass("is-invalid");
            }

            

            
        } else if(category_id == 352) {

            if(jQuery("#retail-price").val() == "" || isNaN(jQuery("#retail-price").val())) {
                jQuery(".popup-error").html("Please enter retail price").removeClass('d-none');
                jQuery("#retail-price").addClass("is-invalid");
                return false;
            } else {
                jQuery("#retail-price").addClass("is-invalid");
            }

        } else if(jQuery(".condition-radio:checked").val() == "new" ) {

            if(jQuery("#retail-price").val() == "" || isNaN(jQuery("#retail-price").val())) {
                jQuery(".popup-error").html("Please enter retail price").removeClass('d-none');
                jQuery("#retail-price").addClass("is-invalid");
                return false;
            } else {
                jQuery("#retail-price").addClass("is-invalid");
            }

            if(jQuery("#selling-price").val() == "" || isNaN(jQuery("#selling-price").val())) {
                jQuery(".popup-error").html("Please enter selling price").removeClass('d-none');
                jQuery("#selling-price").addClass("is-invalid");
                return false;
            } else {
                jQuery("#selling-price").removeClass("is-invalid");
            }

            if(jQuery("#stock-quantity").val() == "" || isNaN(jQuery("#stock-quantity").val())) {
                jQuery(".popup-error").html("Please enter retail price").removeClass('d-none');
                jQuery("#stock-quantity").addClass("is-invalid");
                return false;
            } else {
                jQuery("#stock-quantity").removeClass("is-invalid");
            }
        }

        if(!jQuery("#toc").is(":checked")) {
            jQuery(".popup-error").html("Please accept terms and conditions").removeClass('d-none');
            jQuery("#toc").addClass("is-invalid");
            return false;
        } else {
            jQuery("#toc").removeClass("is-invalid");
        }

        if(!jQuery("#fee-acc").is(":checked")) {
            jQuery(".popup-error").html("Please accept fee structure").removeClass('d-none');
            jQuery("#fee-acc").addClass("is-invalid");
            return false;
        } else {
            jQuery("#fee-acc").removeClass("is-invalid");
        }
        jQuery(".steps").removeClass('show');
        <?php if(!is_user_logged_in()) { ?>
            jQuery(".pstep5").addClass('is-done');
            jQuery(".step6").addClass('show');
        <?php  } else {
            $tradesafe_token_id = get_user_meta(get_current_user_id(),'tradesafe_prod_token_id',true);
            if(empty($tradesafe_token_id)) { ?>
                jQuery(".pstep6").addClass('is-done');
                jQuery(".step7").addClass('show');
                jQuery(".step7-1").removeClass('d-none');
            <?php } else { ?>
                jQuery(".step7").remove();
                jQuery(".pstep6").addClass('is-done');
                createProduct();
            <?php  }
        } ?>

    });

    
    jQuery(".login-button").on("click", function(){
        var ele = $(this);
        ele.html("login in...").prop("disabled","disabled");
        var data = {
            action:   'ss_pop_login',
            username: $("#log-username").val(),
            password: $("#log-password").val()
        };
        jQuery.post( dokan.ajaxurl, data, function( resp ) {
            resp = JSON.parse(resp);
            if(resp.error) {
                ele.html("Login").removeAttr("disabled");
                alert(resp.error);
            } else {
                if(!resp.user_tradesafe_token_id || resp.user_tradesafe_token_id == "") {
                    jQuery(".steps").removeClass('show');
                    jQuery(".pstep6").addClass('is-done');
                    jQuery(".step7").addClass('show');
                    jQuery(".step7-1").removeClass('d-none');
                } else {
                    jQuery(".step7").remove();
                    jQuery(".pstep6").addClass('is-done');
                    createProduct();
                }
            }
            
        });
    });

    
    jQuery(".register-button").on("click", function(){
        var ele = $(this);
        ele.html("registering...").prop("disabled","disabled");
        var data = {
            action:   'ss_pop_register',
            username: $("#reg-username").val(),
            password: $("#reg-password").val()
        };
        jQuery.post( dokan.ajaxurl, data, function( resp ) {
            resp = JSON.parse(resp);
            if(resp.error) {
                ele.html("Register").removeAttr("disabled");
                alert(resp.error);
            } else {
                jQuery(".steps").removeClass('show');
                jQuery(".step7").addClass('show');
                jQuery(".pstep6").addClass('is-done');
                jQuery(".step7-1").removeClass('d-none');
            }
        });
    });

    jQuery(".step71-button").on("click", function(){
        var valid = true;
        jQuery(".step71-input").each(function(){
            if(jQuery(this).val() == "") {
                jQuery(this).addClass("is-invalid");
                valid = false;
            } else {
                jQuery(this).removeClass("is-invalid");
            }
        });

        let text = jQuery(".step7").find("#tradesafe_token_id_number").val();
        let pattern = /(((\d{2}((0[13578]|1[02])(0[1-9]|[12]\d|3[01])|(0[13456789]|1[012])(0[1-9]|[12]\d|30)|02(0[1-9]|1\d|2[0-8])))|([02468][048]|[13579][26])0229))(( |-)(\d{4})( |-)(\d{3})|(\d{7}))/i;
        let result = text.match(pattern);

        if(result === null) {
            jQuery(".step7").find("#tradesafe_token_id_number").addClass("is-invalid");
            valid = false;
        }

        if(valid) {
            initAutocomplete(jQuery(".step7-2"));
            jQuery(".step7-1").addClass('d-none');
            jQuery(".step7-2").removeClass('d-none');
        }
    });

    $(".ea-step1-next-button").on("click",function(){
        initAutocomplete(jQuery(".ea-step2"));
    });
    
    jQuery(".step72-button").on("click", function(){
        var valid = true;
        jQuery(".step72-input").each(function(){
            if(jQuery(this).val() == "") {
                jQuery(this).addClass("is-invalid");
                valid = false;
            }
        });
        if(valid) {
            jQuery(".step7-2").addClass('d-none');
            jQuery(".step7-3").removeClass('d-none');       
        }
    });
    jQuery(".step73-button").on("click", function(){
        var valid = true;
        jQuery(".step73-input").each(function(){
            if(jQuery(this).val() == "") {
                jQuery(this).addClass("is-invalid");
                valid = false;
            }
        });
        if(valid) {
            createProduct();
        }
    });




    function createProduct() {
        
        var form =jQuery("#dokan-modal-add-new-product-form")[0];
        let formData = new FormData(form);
        formData.append('action', 'ss_dokan_create_new_product_new');
        formData.append('dokan_add_new_product_nonce', "<?=wp_create_nonce('dokan_create_new_product_new')?>");
        // var data = {
        //     action:   'dokan_create_new_product_new',
        //     postdata: formData,
        //     dokan_add_new_product_nonce : "<?=wp_create_nonce('dokan_create_new_product_new')?>"
        // };

        jQuery(".pop-spinner").removeClass('d-none');
        jQuery(".added-msg").addClass('d-none');


        $.ajax({
            url: dokan.ajaxurl,
            type: "POST",
            data: formData,
            processData: false,
            contentType:false,
            success:function(resp){
                jQuery(".pop-spinner").addClass('d-none');
                if(resp.error) {
                    alert(resp.message);
                } else {
                    jQuery(".added-msg").removeClass('d-none');
                }
            }
        });
        $("#pro_added").val(1);
        jQuery(".steps").removeClass('show');
        jQuery(".laststep").addClass('show');

        
    }
});




loggedin_user_id = <?=get_current_user_id()?>;
redirect_url = "<?=get_site_url() . '/my-account'?>";
<?php if(isset($_GET['showpop'])){ ?>
         loadpop = true;
<?php } ?>

</script>



<?php 
}
?>