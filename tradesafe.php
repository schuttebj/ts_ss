<?php





if ( ! defined( 'ABSPATH' ) ) {



	exit;



}







add_action("wp_footer", 'ss_tadesafe_footer');



function ss_tadesafe_footer() {
    
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



        // $data = array(

        //     // 'billing_address_1'     => $_POST['billing_address_1'],

        //     // 'billing_city'          => $_POST['billing_city'],

        //     // 'billing_postcode'      => $_POST['billing_postcode'],

        //     // 'billing_state'         => $_POST['billing_state'],

        // );
		
		unset($store_settings['address']);

        $store_settings['address']['street_1'] = $_POST['billing_address_1'];
        $store_settings['address']['city'] = $_POST['billing_city'];
        $store_settings['address']['zip'] = $_POST['billing_postcode'];
        $store_settings['address']['state'] = $_POST['billing_state'];
        $store_settings['address']['country'] = "ZA";

        update_user_meta( $user_id, 'dokan_profile_settings', $store_settings );

        update_user_meta( $user_id, 'dokan_store_phone', $_POST['tradesafe_token_mobile'] );

        do_action( 'woocommerce_save_account_details', $user->ID );
        ?>
        <script>
		location.href='<?=get_site_url(). "/dashboard/"?>'
		</script>
<?php
    }

    $user = new WP_User( $user_id );

    $meta_key = 'tradesafe_token_id';

    if ( tradesafe_is_prod() ) {
        $meta_key = 'tradesafe_prod_token_id';
    }

    $token_id           = get_user_meta( $user->ID, $meta_key, true );

    $banks              = $client->getEnum( 'UniversalBranchCode' );

    $bank_account_types = $client->getEnum( 'BankAccountType' );

    $organization_types = $client->getEnum( 'OrganizationType' );

    $token_data         = null;
    $billing_address_1 = $store_settings['address']['street_1'] ?? "";

    $billing_city = $store_settings['address']['city'] ?? "";

    $billing_postcode = $store_settings['address']['zip'] ?? "";

    $billing_state = $store_settings['address']['state'] ?? "";

    $dokan_store_phone = get_user_meta( $user->ID, 'dokan_store_phone', true );



    if ( $token_id ) {

        $token_data = $client->getToken( $token_id );

    }

    $user_role = [];

    if ( !empty( $user->roles ) && is_array( $user->roles ) ) {

        foreach ( $user->roles as $role )

            $user_role[] = $role;

    }



    if(in_array("seller",$user_role)) {

        ?>







<div class="modal fade acc-edit-modal" tabindex="-1" role="dialog">

  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

    <div class="modal-content">



      <div class="modal-body acc-edit-modal-1">

        <div class="row">

            <div class="col-md-4 left-sidebar">

                <img style="margin-top:20px;" alt="SwingSave" src="https://swingsave.com/wp-content/uploads/2021/06/Logo_White_Final.png" />

                <ul class="StepProgress">

                    <li class="StepProgress-item eastep1 is-done">Your INFO</li>

                    <li class="StepProgress-item eastep2">Address</li>

                    <li class="StepProgress-item eastep3">Payment Info</li>

                </ul>

            </div>



            <div class="col-md-8 right-sidebar">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                <span aria-hidden="true">&times;</span>

            </button>



                <form  class="woocommerce-EditAccountForm edit-account" action="" method="post">

                    <div class="acc-edit-form-container">

                        <div class="ea-steps ea-step1 show">

                            <?php  include('includes/acc-edit-step1.php'); ?>

                        </div>  



                        <div class="ea-steps ea-step2">

                            <?php  include('includes/acc-edit-step2.php'); ?>

                        </div>



                        <div class="ea-steps ea-step3">

                            <?php  include('includes/acc-edit-step3.php'); ?>

                        </div>

                    </div>

                </form>

            </div>

        </div>

      </div>

    </div>

  </div>

</div>









        <script>
            let user_role = "seller";
            let user_tradesafe_token_id = "<?=get_user_meta(get_current_user_id(),'tradesafe_prod_token_id',true);?>";
            let no_token_redirect_url = "<?=get_site_url() . '/dashboard'?>";


            jQuery(document).ready(function(){

                $(".dokan-dashboard-content").find('.notice-warning').html('<h3>Your account is incomplete!</h3><p>Our payment service provider is TradeSafe Escrow. TradeSafe keeps the funds safe in the middle and will release the funds to you once delivery is completed successfully. Sellers are guaranteed payment.</p><p>TradeSafe forces HTTPS for all services using TLS (SSL) including their public website and the Application. All bank account details are encrypted with AES-256. Decryption keys are stored on separate machines from the application. In English, your details are encrypted with the highest industry-specific standards (which can be found in most banks), making your information confidential, secure, and safe.</p><p><a href="javascript:void(0)"  class="open-acc-edit-modal button-secondary button alt button-large button-next">Update Account</a></p>');



                $(document).on("click", ".open-acc-edit-modal",function(){

                    $(".ea-steps").hide();

                    $('.acc-edit-modal').modal('show');

                });



                $(".ea-step1-next-button").on("click",function(){

                    var valid = true;

                    $(".ea-step1").find('input').each(function(){

                        if($(this).val().trim() == "") {

                            $(this).addClass("is-invalid");

                            valid = false;

                        } else {

                            $(this).removeClass("is-invalid");

                        }

                    });

                    let text = jQuery(".ea-step1").find("#tradesafe_token_id_number").val();
                    let pattern = /(((\d{2}((0[13578]|1[02])(0[1-9]|[12]\d|3[01])|(0[13456789]|1[012])(0[1-9]|[12]\d|30)|02(0[1-9]|1\d|2[0-8])))|([02468][048]|[13579][26])0229))(( |-)(\d{4})( |-)(\d{3})|(\d{7}))/i;
                    let result = text.match(pattern);

                    if(result === null) {
                        jQuery(".ea-step1").find("#tradesafe_token_id_number").addClass("is-invalid");
                        valid = false;
                    }

                    if(!valid) {

                        return;

                    }

                    $(".ea-steps").removeClass('show');

                    $(".ea-step2").addClass('show');

                    $(".eastep2").addClass('is-done');
                    

                });

                $(".ea-step2-next-button").on("click",function(){

                    var valid = true;

                    $(".ea-step2").find('input').each(function(){

                        if($(this).val().trim() == "") {

                            $(this).addClass("is-invalid");

                            valid = false;

                        } else {

                            $(this).removeClass("is-invalid");

                        }

                    });

                    if(!valid) {

                        return;

                    }

                    $(".ea-steps").removeClass('show');

                    $(".ea-step3").addClass('show');

                    $(".eastep3").addClass('is-done');

                });

                $(".ea-step3-next-button").on("click",function(e){

                    e.preventDefault();

                    var valid = true;

                    $(".ea-step3").find('input,select').each(function(){

                        if($(this).val().trim() == "") {

                            $(this).addClass("is-invalid");

                            valid = false;

                        } else {

                            $(this).removeClass("is-invalid");

                        }

                    });

                    if(!valid) {

                        return false;

                    }

                    $(this).closest('form').submit();

                });

                $(".ea-step2-back-button").on("click",function(){

                    $(".ea-steps").removeClass('show');

                    $(".ea-step1").addClass('show');

                    $(".eastep2").removeClass('is-done');

                });

                $(".ea-step3-back-button").on("click",function(){

                    $(".ea-steps").removeClass('show');

                    $(".ea-step2").addClass('show');

                    $(".eastep3").removeClass('is-done');

                });







            });

        </script>

        <?php

    }

}