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

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

use FloridaSwim\FloridaSwimCustomRegistration;

$plugin = new FloridaSwimCustomRegistration(__FILE__);
$plugin->run();


// shortcode to display form
function fscr_public_shortcode() {
  ob_start();
  include __DIR__ . '/public/template-parts/form-florida-swim-custom-registration.php';
  return ob_get_clean();
}
add_shortcode( 'fscr_form', 'fscr_public_shortcode' );

// enqueue styles
//function fscr_enqueue_styles() {   
  //wp_enqueue_style( 'flatpickr-css', '//cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', [], false, 'all' );
//}
//add_action('wp_enqueue_scripts', 'fscr_enqueue_styles');

// enqueue scripts
function fscr_enqueue_scripts() {   
  wp_enqueue_script( 'vue-js', '//cdn.jsdelivr.net/npm/vue/dist/vue.js', [], '1.0', true );
  wp_enqueue_script( 'axios-js', '//unpkg.com/axios/dist/axios.min.js', [], '1.0', true );
  wp_enqueue_script( 'datepicker-js', '//unpkg.com/vuejs-datepicker', [], '1.0', true );
  wp_enqueue_script( 'fscr_javascript', plugin_dir_url( __FILE__ ) . '/public/assets/js/main.js', array('vue-js', 'axios-js'), '1.0', true );
}
add_action('wp_enqueue_scripts', 'fscr_enqueue_scripts');

// add api routes
function fscr_api_register_routes() {
  $registrantController = new RegistrantController();
  $registrantController->registerRoutes();
  //$studentController = new StudentController();
  //$studentController->registerRoutes();
}
add_action( 'rest_api_init', 'fscr_api_register_routes' );
