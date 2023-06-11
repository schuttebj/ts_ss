<div id="carouselExampleControls" class="bigscreen carousel slide"  data-interval="false">

    <div class="carousel-inner">
        <?php
        $i = 0;
        $count = count($parent_cat);

        foreach($parent_cat as $category) { 
            $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true ); 
            $image = wp_get_attachment_url( $thumbnail_id ); 
            ?>

            <?php if($i%8 == 0) { ?>

            <div class="carousel-item <?=($i==0)?'active':''?>">
            <div class="prod-categories clearfix">

            <?php } ?>

                <label class="text-center cat-image <?=$i?>">
                        <input type="radio" name="category_id" class="catradio" value="<?=$category->term_id?>" data-slug="<?=$category->slug;?>">
                        <?php if(empty($image)) { ?>
                            <img src="<?=DOKAN_PLUGIN_ASSEST?>/images/no-image-icon.png">    
                        <?php } else { ?>
                            <img src="<?=$image?>">
                        <?php } ?>
                        <?=$category->name?>
                </label>

            <?php  
                
                if(($i!=0 && $i%7 ==0 )|| $i == ($count-1)) {
                    echo "</div></div>";
                }
                $i++;
                
            } ?>
    </div>

    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>

    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>

</div>

<div id="carouselExampleControlsSmall" class="smallscreen carousel slide"  data-interval="false">

    <div class="carousel-inner">
        <?php
        $i = 0;
        $count = count($parent_cat);

        foreach($parent_cat as $category) { 
            $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true ); 
            $image = wp_get_attachment_url( $thumbnail_id ); 
            ?>

            <?php if($i%6 == 0) { ?>

            <div class="carousel-item <?=($i==0)?'active':''?>">
            <div class="prod-categories-small clearfix">

            <?php } ?>

                <label class="text-center cat-image">
                        <input type="radio" name="category_id" class="catradio" value="<?=$category->term_id?>" data-slug="<?=$category->slug;?>">
                        <?php if(empty($image)) { ?>
                            <img src="<?=DOKAN_PLUGIN_ASSEST?>/images/no-image-icon.png">    
                        <?php } else { ?>
                            <img src="<?=$image?>">
                        <?php } ?>
                        <?=$category->name?>
                </label>

            <?php  
                $i++;
                if(($i!=1 && $i%6 ==0 )|| $i == ($count)) {
                    echo "</div></div>";
                }
                
            } ?>
    </div>

    <a class="carousel-control-prev" href="#carouselExampleControlsSmall" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>

    <a class="carousel-control-next" href="#carouselExampleControlsSmall" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>

</div>

<div class="modal-buttons text-center">
    <a href="javascript:void(0)" class="step1-next-button btn btn-default btn-block mt-4">NEXT</a>
</div>