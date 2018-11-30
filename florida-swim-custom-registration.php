<?php

/*
Plugin Name:  Florida Swim Custom Registration
Description:  Custom registration functionality for Florida Swim Company,
Version:      1.0.0
Author:       Socius Marketing
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  florida-swim-custom-registration
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or die();

require __DIR__ . '/src/vendor/autoload.php';

use FloridaSwim\FloridaSwimCustomRegistration;

global $fscr_plugin;
$fscr_plugin = new FloridaSwimCustomRegistration();

function fscr_handle_activation() {
  global $fscr_plugin;
  $fscr_plugin->handleActivation();
}
register_activation_hook(__FILE__, 'fscr_handle_activation');

function fscr_handle_deactivation() {
  global $fscr_plugin;
  $fscr_plugin->handleDeactivation();
}
register_deactivation_hook(__FILE__, 'fscr_handle_deactivation');