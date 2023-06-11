<?php


add_action("wp_head", 'ss_enque_style');
function ss_enque_style() {
    wp_enqueue_style( "ss-style", SS_PLUGIN_URL . 'assets/css/style.css', array(), time(), 'all' );
    wp_enqueue_style( "ss-imgup-style", SS_PLUGIN_URL . 'assets/vendor/image-uploader/image-uploader.min.css', array(), time(), 'all' );
}

add_action("wp_footer", "ss_enque_script");
function ss_enque_script() {
    wp_enqueue_script( 'ss-script', SS_PLUGIN_URL . 'assets/js/script.js', array( 'jquery' ), time(), false );
    wp_enqueue_script( 'ss-imgup-script', SS_PLUGIN_URL . 'assets/vendor/image-uploader/image-uploader.min.js', array( 'jquery' ), time(), false );
    
}



