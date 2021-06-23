<?php
/*
Plugin Name: Orderby with Categories for Woocommerce by RMcC
Plugin URI: #
Description: Adds categories to the orderby/sorting filters on the shop page
Version: 1.0.0
Author: robmccormack89
Author URI: #
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: LICENSE
Text Domain: orderby-with-categories
Domain Path: /languages/
*/

// don't run if someone access this file directly
defined('ABSPATH') || exit;

// define some constants
if (!defined('ORDERBY_WITH_CATEGORIES_PATH')) define('ORDERBY_WITH_CATEGORIES_PATH', plugin_dir_path( __FILE__ ));
if (!defined('ORDERBY_WITH_CATEGORIES_URL')) define('ORDERBY_WITH_CATEGORIES_URL', plugin_dir_url( __FILE__ ));

// require the composer autoloader
if (file_exists($composer_autoload = __DIR__.'/vendor/autoload.php')) require_once $composer_autoload;

// then require the main plugin class. this class extends Timber/Timber which is required via composer
new Rmcc\OrderbyWithCategories;