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

require __DIR__ . '/vendor/autoload.php';

use FloridaSwim\FloridaSwimCustomRegistration;
use FloridaSwim\Controllers\RegistrantController;
use FloridaSwim\Models\Person;

//var_dump(Person::find());
//exit;

global $fscr_plugin;
$fscr_plugin = new FloridaSwimCustomRegistration();

// handle plugin activation
function fscr_handle_activation() {
  global $fscr_plugin;
  $fscr_plugin->handleActivation();
}
register_activation_hook(__FILE__, 'fscr_handle_activation');

// handle plugin deactivation
function fscr_handle_deactivation() {
  global $fscr_plugin;
  $fscr_plugin->handleDeactivation();
}
register_deactivation_hook(__FILE__, 'fscr_handle_deactivation');

// shortcode to display form
function fscr_public_shortcode() {
  ob_start();
  include __DIR__ . '/public/template-parts/form-florida-swim-custom-registration.php';
  return ob_get_clean();
}
add_shortcode( 'fscr_form', 'fscr_public_shortcode' );

// enqueue scripts
function fscr_enqueue_script() {   
  wp_enqueue_script( 'vue-js', '//cdn.jsdelivr.net/npm/vue/dist/vue.js', [], '1.0', true );
  wp_enqueue_script( 'axios-js', '//unpkg.com/axios/dist/axios.min.js', [], '1.0', true );
  wp_enqueue_script( 'fscr_javascript', plugin_dir_url( __FILE__ ) . '/public/assets/js/main.js', array('vue-js', 'axios-js'), '1.0', true );
}
add_action('wp_enqueue_scripts', 'fscr_enqueue_script');

// add api routes
function fscr_api_register_routes() {
  $registrantController = new RegistrantController();
  $registrantController->registerRoutes();
}
add_action( 'rest_api_init', 'fscr_api_register_routes' );