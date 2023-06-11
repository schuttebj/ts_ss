<?php

/*

Plugin Name: Swingsave Helper

Description: Helper Plugin for Swingsave

Author: Suresh Patel

*/



if ( ! defined( 'ABSPATH' ) ) {

	exit;

}





/**

 * Constants

 */

DEFINE('SS_PLUGIN_DIR', __DIR__);

DEFINE('SS_PLUGIN_URL', plugin_dir_url(__FILE__));







/**

 * DB version

 */

global $ss_db_version;

$ss_db_version = "1.0";





/**

 * Required FIles

 */

require(SS_PLUGIN_DIR . "/assets.php");

require(SS_PLUGIN_DIR . "/popup.php");

require(SS_PLUGIN_DIR . "/add-product.php");

require(SS_PLUGIN_DIR . "/admin-menu.php");

require(SS_PLUGIN_DIR . "/add-edit-image-modal.php");

require(SS_PLUGIN_DIR . "/tradesafe.php");

require(SS_PLUGIN_DIR . "/ajax.php");

require(SS_PLUGIN_DIR . "/automatewoo-ext.php");

require(SS_PLUGIN_DIR . "/classes/SwingSaveTradeSafe.php");












