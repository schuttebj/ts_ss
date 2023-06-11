<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

add_filter( 'automatewoo/triggers', 'ss_custom_triggers' );

/**
 * @param array $triggers
 * @return array
 */
function ss_custom_triggers( $triggers ) {

	include_once 'classes/SwingsaveCustomTrigger.php';

	// set a unique name for the trigger and then the class name
	$triggers['ss_custom_trigger'] = 'SwingsaveCustomTrigger';

	return $triggers;
}