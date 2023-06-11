<div class="step7-1 d-none">

<div class="form-row">

    <div class="form-group form-label-group col-md-6">

        <input type="text" name="account_first_name" class="step71-input form-control" id="account_first_name" placeholder="Floating Label">

        <label for="account_first_name">Name </label>

    </div>

    <div class="form-group form-label-group col-md-6">

        <input type="text" name="account_last_name" class="step71-input form-control" id="account_last_name" placeholder="Floating Label">

        <label for="account_last_name">Surname</label>

    </div>

</div>

<div class="form-row">

    <div class="form-group form-label-group col-md-12">

        <input type="text" name="tradesafe_token_mobile" class="step71-input form-control" pattern="^0[678][0-9][0-9]{7}" id="tradesafe_token_mobile" placeholder="Floating Label">

        <label for="tradesafe_token_mobile">Contact Number</label>

    </div>

</div>

<div class="form-row">

    <div class="form-group form-label-group col-md-12">

        <input type="text" name="tradesafe_token_id_number" class="step71-input form-control" pattern="(((\d{2}((0[13578]|1[02])(0[1-9]|[12]\d|3[01])|(0[13456789]|1[012])(0[1-9]|[12]\d|30)|02(0[1-9]|1\d|2[0-8])))|([02468][048]|[13579][26])0229))(( |-)(\d{4})( |-)(\d{3})|(\d{7}))" id="tradesafe_token_id_number" placeholder="Floating Label">

        <label for="tradesafe_token_id_number">ID Number</label>

    </div>

</div>

<div class="modal-buttons text-center">
<a href="javascript:void(0)" class="step71-button btn btn-default btn-block">Next</a>
</div>

</div>

<div class="step7-2  d-none"> 

<div class="form-row">

    <div class="form-group form-label-group col-md-12">

        <input type="text" name="billing_address_1" class="step72-input  form-control billing_address_1" id="billing_address_1" placeholder="Floating Label">

        <label for="billing_address_1">Street Address </label>

    </div>

</div>

<div class="form-row">

    <div class="form-group form-label-group col-md-6">

        <input type="text" name="billing_city" class="step72-input  form-control  billing_city" id="billing_city" placeholder="Floating Label">

        <label for="billing_city">City/Town</label>

    </div>

    <div class="form-group form-label-group col-md-6">

        <input type="text" name="billing_postcode" class="step72-input form-control billing_postcode" id="billing_postcode" placeholder="Floating Label">

        <label for="billing_postcode">Postal Code</label>

    </div>

    

</div>

<div class="form-row">

    <div class="form-group form-label-group col-md-6">

        <select id="billing_state" name="billing_state" class="billing_state step72-input  form-control custom-select shadow-none" tabindex="-1" aria-hidden="true">

            <option value="">Select Province</option>

            <option value="EC" >Eastern Cape</option>

            <option value="FS" >Free State</option>

            <option value="GP" >Gauteng</option>

            <option value="KZN">KwaZulu-Natal</option>

            <option value="LP" >Limpopo</option>

            <option value="MP" >Mpumalanga</option>

            <option value="NC" >Northern Cape</option>

            <option value="NW" >North West</option>

            <option value="WC" >Western Cape</option>

        </select>

        <label for="billing_state">Province</label>

    </div>

    <div class="form-group form-label-group col-md-6">

        <input type="text" name="billing_country" class="form-control" id="billing_country" placeholder="Floating Label" value="South Africa">

        <label for="billing_country">Country</label>

    </div>

</div>
<div class="modal-buttons text-center">
<a href="javascript:void(0)" class="step72-button btn btn-default btn-block">Next</a>
</div>
</div>

<div class="step7-3 d-none">

  <div class="form-row">

      <div class="form-group form-label-group col-md-12">

          <select name="tradesafe_token_bank" id="tradesafe_token_bank" class="form-control custom-select step73-input shadow-none">

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

          <input type="text" name="tradesafe_token_bank_account_number" class="form-control step73-input" id="tradesafe_token_bank_account_number" placeholder="Floating Label" >

          <label for="tradesafe_token_bank_account_number">Account number </label>

      </div>

  </div>

  <div class="form-row">

      <div class="form-group form-label-group col-md-12">

      <select name="tradesafe_token_bank_account_type" id="tradesafe_token_bank_account_type" class="form-control custom-select shadow-none step73-input">

              <option value="">Account type</option>

              <?php foreach ( $bank_account_types as $bank_account_name => $bank_account_description ) { ?>

                  <option value="<?=$bank_account_name;?>"><?=$bank_account_description;?></option>

              <?php } ?>

          </select>

          <label for="tradesafe_token_bank_account_type">Account type </label>

      </div>

  </div>





  <input type="hidden" name="sav_action" value="save_account_details" />

  <input type="hidden" name="ss_action" value="ss_save_account_details" />

  <input type="hidden" name="tradesafe_token_id_type" value="NATIONAL">

  <input type="hidden" name="tradesafe_token_id_country" value="ZAF">


<div class="modal-buttons text-center">
  <a href="javascript:void(0)" class="step73-button btn btn-default btn-block">Next</a>
</div>
</div>