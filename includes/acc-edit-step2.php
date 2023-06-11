<div class="form-row">
    <div class="form-group form-label-group col-md-12">
        <input type="text" name="billing_address_1" class="form-control billing_address_1" id="billing_address_1" placeholder="Floating Label" value="<?php echo esc_attr( $billing_address_1 ?? null ); ?>">
        <label for="billing_address_1">Street Address </label>
    </div>
</div>
<div class="form-row">
    <div class="form-group form-label-group col-md-6">
        <input type="text" name="billing_city" class="form-control billing_city" id="billing_city" placeholder="Floating Label" value="<?php echo esc_attr( $billing_city ?? null ); ?>">
        <label for="billing_city">City/Town</label>
    </div>
    <div class="form-group form-label-group col-md-6">
        <input type="text" name="billing_postcode" class="form-control billing_postcode" id="billing_postcode" placeholder="Floating Label" value="<?php echo esc_attr( $billing_postcode ?? null ); ?>">
        <label for="billing_postcode">Postal Code</label>
    </div>
    
</div>
<div class="form-row">
    <div class="form-group form-label-group col-md-6">
        <select id="billing_state" name="billing_state" class="billing_state form-control custom-select shadow-none" tabindex="-1" aria-hidden="true">
            <option value="">Select Province</option>
            <option value="EC" <?php if(isset($billing_state) && $billing_state == "EC") { echo "selected"; } ?> >Eastern Cape</option>
            <option value="FS" <?php if(isset($billing_state) && $billing_state == "FS") { echo "selected"; } ?>>Free State</option>
            <option value="GP" <?php if(isset($billing_state) && $billing_state == "GP") { echo "selected"; } ?>>Gauteng</option>
            <option value="KZN" <?php if(isset($billing_state) && $billing_state == "KZN") { echo "selected"; } ?>>KwaZulu-Natal</option>
            <option value="LP" <?php if(isset($billing_state) && $billing_state == "LP") { echo "selected"; } ?>>Limpopo</option>
            <option value="MP" <?php if(isset($billing_state) && $billing_state == "MP") { echo "selected"; } ?>>Mpumalanga</option>
            <option value="NC" <?php if(isset($billing_state) && $billing_state == "NC") { echo "selected"; } ?>>Northern Cape</option>
            <option value="NW" <?php if(isset($billing_state) && $billing_state == "NW") { echo "selected"; } ?>>North West</option>
            <option value="WC" <?php if(isset($billing_state) && $billing_state == "WC") { echo "selected"; } ?>>Western Cape</option>
        </select>
        <label for="billing_state">Province</label>
    </div>
    <div class="form-group form-label-group col-md-6">
        <input type="text" name="billing_country" class="form-control" id="billing_country" placeholder="Floating Label" value="South Africa">
        <label for="billing_country">Country</label>
    </div>
</div>

<div class="modal-buttons text-center">
    <a href="javascript:void(0)" class="ea-step2-back-button btn">BACK</a>
    <a href="javascript:void(0)" class="ea-step2-next-button btn btn-default btn-block">NEXT</a>
</div>
