<div class="form-row">
  <div class="col-12 condition">
    <label for="condition">Is this a new or secondhand <span class="category_name"></span>?</label>
    <ul class="rad-btn">
      <li>
        <input type="radio" class="condition-radio" id="new" name="condition" value="new">
        <label for="new">New</label>
      </li>
      <li>
        <input type="radio" class="condition-radio" id="second" name="condition" value="used">
        <label for="second">Secondhand</label>
      </li>
    </ul>
  </div>
  <div class="col-12 cond-type new" style="display:none">
    <label for="condition">Is this action sale or normal sale?</label>
    <ul class="rad-btn">
      <li>
        <input type="radio" class="option-radio" id="sale" name="option" value="sale">
        <label for="sale">Normal Sale</label>
      </li>
      <li>
        <input type="radio" class="option-radio" id="auction" name="option" value="auction">
        <label for="auction">Auction</label>
      </li>
    </ul>
  </div>
  <div class="col-12 cond-type  used" style="display:none">
    <div>
      <label class="bigscreen" for="condition-rating">What condition is the <span class="category_name"></span>?</label>
      <?php

        $terms = get_terms([
            'taxonomy' => "pa_condition-rating",
            'hide_empty' => false,
            'orderby' => 'id', 
            'order' => 'ASC', 
        ]);

        ?>

        <ul class="rad-btn bigscreen">
        <?php foreach($terms as $term){ ?>
          <li>
        <input type="radio" name="condition-rating" class="condition-rating" id="condition-rating<?=$term->term_id;?>" value="<?=$term->name;?>">
        <label for="condition-rating<?=$term->term_id;?>"><?=$term->name;?>
        <small>
        <?php  
          switch($term->term_id){
            case 260:
                echo "This item is in perfect condition as if it is brand new";
              break;
            case 261:
              echo "This item is in close to perfect condition and has minimal to no scratches";
              break;
            case 262:
              echo "This item is still in a good condition with minor scratches";
              break;
            case 263:
              echo "This item has been used and worn with scratches and minor dents";
              break;
            case 264:
              echo "This item has lot of scratches and/or dents but still works as intended";
              break;
          }
        ?>
        </small>
        </label>
        </li>
        <?php } ?>
        </ul>
        <div class="smallscreen form-label-group">
        <select name="condition-rating" class="form-control condition-rating custom-select shadow-none" id="condition-rating<?=$term->term_id;?>">
          <option value="">Select Condition</option>
          <?php foreach($terms as $term){ ?>
            <option value="<?=$term->name;?>"><?=$term->name;?></option>
          <?php } ?>
        </select>
        <label for="condition-rating">What condition is the <span class="category_name"></span>?</label>
        </div>
    </div>

    <div>
      <label class="bigscreen" for="age">How old is your <span class="category_name"></span>?</label>
      <?php

        $terms = get_terms([
            'taxonomy' => "pa_age",
            'hide_empty' => false,
        ]);

        ?>

      <div class="bigscreen">
      <?php foreach($terms as $term){ ?>
        <div class="form-check form-check-inline">
          <div class="age-radio"><label class="form-check-label" for="age<?=$term->term_id;?>"><?=$term->name;?></label></div>
          <input type="radio" name="age" class="form-check-input age" id="age<?=$term->term_id;?>" value="<?=$term->name;?>"> 
        </div>
      <?php } ?>
      </div>
      <div class="smallscreen form-label-group">
        <select name="age" class="age form-control custom-select shadow-none">
          <option value="">Select Age</option>
          <?php foreach($terms as $term){ ?>
              <option value="<?=$term->name;?>"><?=$term->name;?></option>
          <?php } ?>
        </select>
        <label for="age">How old is your <span class="category_name"></span>?</label>
      </div>
    </div>
  </div>
</div>

<div class="modal-buttons text-center">
	<a href="javascript:void(0)" class="step3-back-button btn">BACK</a>
	<a href="javascript:void(0)" class="step3-next-button btn btn-default btn-block">NEXT</a>
</div>