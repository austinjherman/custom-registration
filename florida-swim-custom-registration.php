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

require __DIR__ . '/backend/vendor/autoload.php';

use FloridaSwim\FloridaSwimCustomRegistration;

$plugin = new FloridaSwimCustomRegistration(__FILE__);
$plugin->run();
