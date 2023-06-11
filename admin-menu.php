<?php

add_action('admin_menu', 'ss_add_admin_menu');

function ss_add_admin_menu() {
    add_menu_page( 
        'SwingSave Settings', 
        'SwingSave Settings', 
        'edit_posts', 
        'swingsave-settings', 
        'gh_add_admin_swingsave_settings', 
        'dashicons-admin-generic' 
    );
}

function gh_add_admin_swingsave_settings() {

    if(isset($_POST['submit'])) {
        if (FALSE === get_option('ss_auction_end_duration')) {
            add_option('ss_auction_end_duration',$_POST['ss_auction_end_duration'], '', 'yes');
        } else {
            update_option('ss_auction_end_duration',$_POST['ss_auction_end_duration'], '', 'yes');
        }
    }
    echo '<h1>SwingSave Settings</h1>';

?>
<form method="post" action="" novalidate="novalidate">

<table class="form-table" role="presentation">

<tbody>
    <tr>
        <th scope="row"><label for="ss-auction-end-duration">Default Auction End Duration (in days)</label></th>
        <td><input name="ss_auction_end_duration" type="text" id="ss-auction-end-duration" value="<?=get_option('ss_auction_end_duration');?>" class="regular-text"></td>
    </tr>
</tbody>
</table>
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p></form>
<?php
}

/*
add_action('admin_head', 'add_col_css');

function add_col_css() {
    ?>
    <style>
        .column-skip_image {
            width:10%;
        }
    </style>
    <?php
}
*/



// add_filter( 'manage_edit-product_columns', 'ss_skip_image_column', 20 );
// function ss_skip_image_column( $columns_array ) {

// 	// I want to display Brand column just after the product name column
// 	return array_slice( $columns_array, 0, 3, true )
// 	+ array( 'skip_image' => 'Skip Image' )
// 	+ array_slice( $columns_array, 3, NULL, true );


// }

// add_action( 'manage_posts_custom_column', 'ss_populate_skip_image' );
// function ss_populate_skip_image( $column_name ) {

// 	if( $column_name  == 'skip_image' ) {
		
// 		$x = get_post_meta( get_the_ID(), 'skip_image', true); 
// 		echo (!empty($x))?"Yes":"No";
// 	}

// }

