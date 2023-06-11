<div class="form-row">
    <div class="form-group form-label-group col-md-6">
        <input type="text" name="account_first_name" class="form-control" id="account_first_name" placeholder="Floating Label" value="<?php echo esc_attr( $user->first_name ); ?>">
        <label for="account_first_name">Name </label>
    </div>
    <div class="form-group form-label-group col-md-6">
        <input type="text" name="account_last_name" class="form-control" id="account_last_name" placeholder="Floating Label" value="<?php echo esc_attr( $user->last_name ); ?>">
        <label for="account_last_name">Surname</label>
    </div>
</div>
<div class="form-row">
    <div class="form-group form-label-group col-md-12">
        <input type="text" name="tradesafe_token_mobile" class="form-control" id="tradesafe_token_mobile" placeholder="Floating Label" value="<?php echo esc_attr( $user->mobile_number ); ?>">
        <label for="tradesafe_token_mobile">Contact Number</label>
    </div>
</div>
<div class="form-row">
    <div class="form-group form-label-group col-md-12">
        <input type="text" name="tradesafe_token_id_number" class="form-control" id="tradesafe_token_id_number" placeholder="Floating Label" value="<?php echo esc_attr( $token_data['user']['idNumber'] ?? null ); ?>">
        <label for="tradesafe_token_id_number">ID Number</label>
    </div>
</div>

<div class="modal-buttons text-center">
    <a href="javascript:void(0)" class="ea-step1-next-button btn btn-default btn-block mt-4">NEXT</a>
</div>