<?php
namespace Rmcc;

use Timber\Timber;

class OrderbyWithCategories extends Timber
{

  public function __construct()
  {
    parent::__construct();
    add_filter('timber/twig', array($this, 'add_to_twig'));
    add_filter('timber/context', array($this, 'add_to_context'));

    add_action('plugins_loaded', array($this, 'plugin_timber_locations'));
    add_action('plugins_loaded', array($this, 'plugin_text_domain_init'));
    add_action('wp_enqueue_scripts', array($this, 'plugin_enqueue_assets'));

    add_action('plugins_loaded', array($this, 'woo_actions'));
    add_filter('wc_get_template', array($this, 'wc_get_template'), 10, 5);
  }

  public function woo_actions() {
    // remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20); // might as well leave result_count alone
  }

  public function wc_get_template($file, $template_name, $args, $template_path, $default_path) {

    global $woocommerce; // for getting the woocommerce plugin_path

    if (!$default_path) $default_path = $woocommerce->plugin_path() . '/templates/'; // if default_path not set, set it now
    $plugin_path = ORDERBY_WITH_CATEGORIES_PATH . 'woocommerce/' . $template_name; // set our plugin_path

    // we wanna reset the logic when woocommerce is looking for php template-parts to use
    // we wanna have it so our plugin's location takes precedence, then the original $file(theme?), then woocommerce templates folder

    // $file = $file;
    if (@file_exists($plugin_path)) $file = $plugin_path;
    if (!@file_exists($plugin_path) && @file_exists($file)) $file = $file;
    if (!@file_exists($plugin_path) && !@file_exists($file) && @file_exists($default_path)) $file = $default_path;

    return $file;
  }

  public function plugin_timber_locations() {
    // if timber::locations is empty (another plugin hasn't already added to it), make it an array
    if (!Timber::$locations)
      Timber::$locations = array();
    // add a new views path to the locations array
    array_push(
      Timber::$locations,
      ORDERBY_WITH_CATEGORIES_PATH . 'views'
    );
  }
  public function plugin_text_domain_init() {
    load_plugin_textdomain('orderby-with-categories', false, ORDERBY_WITH_CATEGORIES_BASE . '/languages');
  }
  public function plugin_enqueue_assets() {
    wp_enqueue_style(
      'orderby-with-categories',
      ORDERBY_WITH_CATEGORIES_URL . 'public/css/orderby-with-categories.css'
    );
    wp_enqueue_script(
      'orderby-with-categories',
      ORDERBY_WITH_CATEGORIES_URL . 'public/js/orderby-with-categories.js',
      'jquery',
      '1.0.0',
      true
    );
    wp_enqueue_style(
      'uikit',
      'https://cdn.jsdelivr.net/npm/uikit@3.15.24/dist/css/uikit.min.css'
    );
    wp_enqueue_script(
      'uikit',
      'https://cdn.jsdelivr.net/npm/uikit@3.15.24/dist/js/uikit.min.js',
      array(),
      '3.15.24',
      false
    );
    wp_enqueue_script(
      'uikit-icons',
      'https://cdn.jsdelivr.net/npm/uikit@3.15.24/dist/js/uikit-icons.min.js',
      array(),
      '3.15.24',
      false
    );
  }

  public function add_to_twig($twig) {
    if (!class_exists('Twig_Extension_StringLoader')) {
      $twig->addExtension(new Twig_Extension_StringLoader());
    }
    return $twig;
  }
  public function add_to_context($context) {
    return $context;
  }
}