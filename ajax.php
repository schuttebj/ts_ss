<?php


add_action( 'wp_ajax_nopriv_ss_pop_login', 'ss_pop_login' );
function ss_pop_login() {
    $creds = array(
        'user_login'    => $_POST['username'],
        'user_password' => $_POST['password'],
        'remember'      => true
    );
 
    $user = wp_signon( $creds, false );
 
    if ( is_wp_error( $user ) ) {
        echo json_encode([
            'error' => 'Invalid Username/Password'
        ]);
        exit;
    }
    $user_tradesafe_token_id = get_user_meta($user->ID,'tradesafe_prod_token_id',true);
    echo json_encode([
        'error' => false,
        'user_tradesafe_token_id' => $user_tradesafe_token_id
    ]);
    exit;
}


add_action('wp_ajax_nopriv_ss_pop_register', 'ss_pop_register');
function ss_pop_register() {

    if(!filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'error' => 'Please enter valid email'
        ]);
        exit;
    }
    $new_user_email = stripcslashes($_POST['username']);
    $new_user_password = $_POST['password'];
    $nickname_arr = explode("@", $_POST['username']);
    $user_nice_name = strtolower($nickname_arr[0]);
    $user_data = array(
        'user_login' => $new_user_email,
        'user_email' => $new_user_email,
        'user_pass' => $new_user_password,
        'user_nicename' => $user_nice_name,
        'display_name' => $user_nice_name,
        'role' => 'seller'
    );
    $user_id = wp_insert_user($user_data);
    if (!is_wp_error($user_id)) {
        $creds = array(
            'user_login'    => $_POST['username'],
            'user_password' => $_POST['password'],
            'remember'      => true
        );
     
        $user = wp_signon( $creds, false );
        echo json_encode([
            'error' => false,
            'user_tradesafe_token_id' => ''
        ]); 
    } else {
        if (isset($user_id->errors['empty_user_login'])) {
            echo json_encode([
                'error' => 'User Name and Email are mandatory'
            ]);
        } elseif (isset($user_id->errors['existing_user_login'])) {
            echo json_encode([
                'error' => 'User name already exixts.'
            ]);
        } else {
            echo json_encode([
                'error' => 'The email you enterd has already been used. Please login or use an alternative email address.'
            ]);
        }
    }
    die;
}

add_action( 'wp_ajax_nopriv_ss_add_product_popup_closed', 'ss_add_product_popup_closed' );
add_action( 'wp_ajax_ss_add_product_popup_closed', 'ss_add_product_popup_closed' );
function ss_add_product_popup_closed() {
    $user_id = get_current_user_id();

    if($user_id > 0) {
        do_action('incomplete_add_product_popup_closed', $user_id );
    }
    exit;
}