    <div class="form-row">
        <div class="form-label-group form-group col-md-12 attributes brand">

            <?php
            $terms = get_terms([
                'taxonomy' => "pa_brand",
                'hide_empty' => false,
            ]);
            ?>

            <select name="brand" id="brand" class="form-control custom-select shadow-none">
                <option value="">Select Brand</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="brand">What brand is your <span class="category_name"></span>?</label>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group form-label-group col-md-12 attributes model driver wood irons wedge putter accessory clothing bag shaft cart balls hybrid">
            <input type="text" name="model" class="form-control" id="model" placeholder="Floating Label">
			<label for="model">What model is your <span class="category_name"></span>?</label>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-label-group form-group col-md-4 attributes hand driver wood irons wedge putter hybrid">
            
            <?php
            $terms = get_terms([
                'taxonomy' => "pa_hand",
                'hide_empty' => false,
            ]);
            ?>

            <select name="hand" id="hand" class="form-control custom-select ">
                <option value="">Select Hand</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="hand">Right or Left Handed</label>
        </div>
    
        <div class="form-label-group form-group col-md-4 attributes wood hybrid">
            
            <?php
            $terms = get_terms([
                'taxonomy' => "pa_club-number",
                'hide_empty' => false,
            ]);
            ?>

            <select name="number" id="number" class="form-control custom-select">
                <option value="">Select Club Number</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="number">Number</label>
        </div>

        <div class="form-label-group form-group col-md-4 attributes driver wood wedge hybrid">
            
            <?php
            $terms = get_terms([
                'taxonomy' => "pa_loft",
                'hide_empty' => false,
            ]);
            ?>
            <select name="loft" id="loft" class="form-control custom-select">
                <option value="">Select Driver Loft</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="loft"><span class="category_name text-capitalize"></span> Loft</label>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-label-group form-group col-md-12 attributes driver wood irons wedge  hybrid">          
            <input type="text" name="shaft" class="form-control" id="shaft" placeholder="What shaft do you have?">
			<label for="shaft">What shaft do you have?</label>
        </div>
    </div>

    <div class="form-row"> 
        <div class="form-label-group form-group col-6 attributes driver wood shaft">
            
            <?php
            $terms = get_terms([
                'taxonomy' => "pa_shaft-weight",
                'hide_empty' => false,
            ]);
            ?>

            <select name="shaft-weight" id="shaft-weight" class="form-control custom-select">
                <option value="">Select Shaft Weight</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="shaft-weight">Shaft Weight</label>
        </div>

        <div class="form-label-group form-group col-6 attributes driver wood irons wedge shaft hybrid">

            <?php
            $terms = get_terms([
                'taxonomy' => "pa_shaft-flex",
                'hide_empty' => false,
            ]);
            ?>

            <select name="shaft-flex" id="shaft-flex" class="form-control custom-select">
                <option value="">Select Shaft Flex</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="shaft-flex">Shaft Flex</label>
        </div>

        <div class="form-label-group form-group col-6 attributes irons">

            <?php
            $terms = get_terms([
                'taxonomy' => "pa_club-range",
                'hide_empty' => false,
            ]);
            ?>

            <select name="club-range" id="club-range" class="form-control custom-select">
                <option value="">Select Range</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="club-range">Range</label>
        </div>

        <div class="form-label-group form-group col-6 attributes wedge">

            <?php
            $terms = get_terms([
                'taxonomy' => "pa_bounce",
                'hide_empty' => false,
            ]);
            ?>

            <select name="bounce" id="bounce" class="form-control custom-select">
                <option value="">Select Bounce</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="bounce">Bounce</label>
        </div>

        <div class="form-label-group form-group col-6 attributes putter">
            
            <?php
            $terms = get_terms([
                'taxonomy' => "pa_putter-type",
                'hide_empty' => false,
            ]);
            ?>

            <select name="type" id="type" class="form-control custom-select">
                <option value="">Select Putter Type</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="type">Putter Type</label>
        </div>

        <div class="form-label-group form-group col-6 attributes accessory">
            <textarea name="function" class="form-control" id="function" placeholder="Function" rows="4"></textarea>
			<label for="function">Function</label>
        </div>

        <div class="form-label-group form-group col-md-12 attributes clothing belts shorts trousers">
            <?php
            $terms = get_terms([
                'taxonomy' => "pa_clothing-sizes-inches",
                'hide_empty' => false,
            ]);
            ?>

            <select name="clothing-sizes-inches" id="clothing-sizes-inches" class="form-control custom-select">
                <option value="">Select Clothing size</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="clothing-sizes-inches">Clothing sizes inches</label>
        </div>

        <div class="form-label-group form-group col-md-12 attributes clothing shirts caps jackets">
            
            <?php
            $terms = get_terms([
                'taxonomy' => "pa_clothing-sizes-normal",
                'hide_empty' => false,
            ]);
            ?>

            <select name="clothing-sizes-normal" id="clothing-sizes-normal" class="form-control custom-select">
                <option value="">Select Clothing size</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="clothing-sizes-normal">Clothing Sizes Normal</label>
        </div>

        <div class="form-label-group form-group col-6 attributes clothing shoes-clothing">
            
            <?php
            $terms = get_terms([
                'taxonomy' => "pa_shoe-sizes",
                'hide_empty' => false,
            ]);

            ?>

            <select name="shoe-sizes" id="shoe-sizes" class="form-control custom-select">
                <option value="">Select Shoe size</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="shoe-sizes">Shoe Sizes</label>
        </div>

        <div class="form-label-group form-group col-md-12 attributes cart">
            <?php
            $terms = get_terms([
                'taxonomy' => "pa_cart-type",
                'hide_empty' => false,
            ]);

            ?>

            <select name="cart-type" id="cart-type" class="form-control custom-select">
                <option value="">Select Cart Type</option>
                <?php foreach($terms as $term){ ?>
                    <option value="<?=$term->name;?>"><?=$term->name;?></option>
                <?php } ?>
            </select>
			<label for="cart-type">Cart Type</label>
        </div>
    </div>

    <div class="form-row">
        <div class="form-label-group form-group col-md-12 attributes tours">
            <input type="text" name="tour_title" id="tour_title" class="form-control" placeholder="Title">
			<label for="tour-title">Title</label>
        </div>

        <div class="form-label-group form-group col-md-6 attributes tours">
            <input type="date" name="tour_available_from" id="tour_available_from" class="form-control" placeholder="From Date">
			<label for="tour_available_from">From Date</label>
        </div>

        <div class="form-label-group form-group col-md-6 attributes tours">
            <input type="date" name="tour_available_to" id="tour_available_to" class="form-control" placeholder="To Date">
			<label for="tour_available_to">To Date</label>
        </div>

        <div class="form-label-group form-group col-md-6 attributes tours">           
            <input type="text" name="tour_number_of_people" id="tour_number_of_people" class="form-control" placeholder="Number of people">
			<label for="tour_number_of_people">Number of people</label>
        </div>

        <div class="form-label-group form-group col-md-6 attributes tours"> 
            <input type="text" name="tour_price_per_person" id="tour_price_per_person" class="form-control" placeholder="Price per person">
			<label for="tour_price_per_person">Price per person</label>
        </div>

        <div class="form-label-group form-group col-md-12 attributes tours">
            <input type="text" name="tour_location" id="tour_location" class="form-control" placeholder="Location">
			<label for="tour_location">Location</label>
        </div>

    </div>

<div class="modal-buttons text-center">
    <a href="javascript:void(0)" class="step2-back-button btn">BACK</a>
    <a href="javascript:void(0)" class="step2-next-button btn btn-default btn-block">NEXT</a>
</div>