<div class="form-row">
    <div class="col-md-12 price-type price-type-1" style="display:none">

        <div class="form-label-group form-group1">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                <div class="input-group-text">R</div>
                </div>
                <input type="text" id="ideal-price" name="ideal-price" class="form-control" placeholder="Minimum price you would accept?">
				<label for="reserve-price">Minimum price you would accept?</label>
            </div>
        </div>

        <div class="form-label-group form-group1">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                <div class="input-group-text">R</div>
                </div>
                <input type="text" id="buy-now-price" name="buy-now-price" class="form-control" placeholder="What price would you accept immediately?">
				<label for="buy-now-price">What price would you accept immediately?</label>
            </div>
        </div>

        <div class="form-label-group form-group1">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                <div class="input-group-text">R</div>
                </div>
                <input type="text" id="reserve-price" name="reserve-price" class="form-control" placeholder="What are you aiming to sell it for?">
				<label for="ideal-price">What are you aiming to sell it for?</label>
            </div>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="col-md-12 price-type price-type-2" style="display:none">

        <div class="form-label-group form-group1">
            
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                <div class="input-group-text">R</div>
                </div>
                <input type="text" class="form-control" id="retail-price" name="retail-price" placeholder="Retail Price">
				<label for="retail-price">Retail Price</label>
            </div>
        </div>

        <div class="form-label-group form-group1">
        
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                <div class="input-group-text">R</div>
                </div>
                <input type="text" id="selling-price" name="selling-price" class="form-control" placeholder="Selling Price">
				<label for="selling-price">Selling Price</label>
            </div>
        </div>

        <div class="form-label-group form-group1">
        
            <div class="input-group mb-2">
                
                <input type="text" id="stock-quantity" name="stock-quantity" class="form-control" placeholder="Stock Quantity">
				<label class="quantity_without_cur" for="stock-quantity">Stock Quantity</label>
            </div>
        </div>
    </div>
</div>

<div class="form-row">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="toc" value="" id="toc">
        <label class="form-check-label" for="defaultCheck1">
            I accept that all the information I entered is correct. Any information that is incorrect will effect the end price you will receive.
        </label>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="fee-acc" value="" id="fee-acc">
        <label class="form-check-label" for="defaultCheck1">
            I accept the fees structure as stipulated by SwingSave and all fees and responsibility associated with the sale of this item.
        </label>
    </div>
</div>

    <!--<input type="checkbox" name="toc" id="toc"> I accept that all the information i entered is correct. Any information that is incorrect will effect the end price you will receive
    <br>
    <input type="checkbox" name="fee-acc" id="fee-acc"> I accept the fee structure as stipulated by SwingSave and all the fees and responsibility associated with the sale of this item-->

<div class="modal-buttons text-center">
	<a href="javascript:void(0)" class="step5-back-button btn">BACK</a>
	<a href="javascript:void(0)" class="step5-next-button btn btn-default btn-block">NEXT</a>
</div>