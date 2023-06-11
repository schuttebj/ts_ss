<div class="form-row">
    <div class="form-group form-label-group col-md-12">
        <select name="tradesafe_token_bank" id="tradesafe_token_bank" class="form-control custom-select shadow-none">
            <option value="">Select Your Bank</option>
            <?php foreach ( $banks as $bank_name => $bank_description ) { ?>
                <option value="<?=$bank_name;?>"><?=$bank_description;?></option>
            <?php } ?>
        </select>
        <label for="tradesafe_token_bank">Select Your Bank</label>
    </div>
</div>
<div class="form-row">
    <div class="form-group form-label-group col-md-12">
        <input type="text" name="tradesafe_token_bank_account_number" class="form-control" id="tradesafe_token_bank_account_number" placeholder="Floating Label" >
        <label for="tradesafe_token_bank_account_number">Account number </label>
    </div>
</div>
<div class="form-row">
    <div class="form-group form-label-group col-md-12">
    <select name="tradesafe_token_bank_account_type" id="tradesafe_token_bank_account_type" class="form-control custom-select shadow-none">
            <option value="">Account type</option>
            <?php foreach ( $bank_account_types as $bank_account_name => $bank_account_description ) { ?>
                <option value="<?=$bank_account_name;?>"><?=$bank_account_description;?></option>
            <?php } ?>
        </select>
        <label for="tradesafe_token_bank_account_type">Account type </label>
    </div>
</div>
<div class="form-row">
    <div class="form-group form-label-group col-md-12">
        <p class="text-payment-tradesafe-ss">Our payment provider is TradeSafe ensures your details are encrypted with the highest industry-specific standards (which can be found in most banks), making your information confidential, secure, and safe.</p>
    </div>
</div>


<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
<input type="hidden" name="action" value="save_account_details" />
<input type="hidden" name="ss_action" value="ss_save_account_details" />
<input type="hidden" name="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
<input type="hidden" name="tradesafe_token_id_type" value="NATIONAL">
<input type="hidden" name="tradesafe_token_id_country" value="ZAF">

<div class="modal-buttons text-center">
    <a href="javascript:void(0)" class="ea-step3-back-button btn">BACK</a>
    <button type="submit" name="save_account_details" class="ea-step3-next-button btn btn-default btn-block">SAVE</button>
</div>