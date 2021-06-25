<?php
namespace Rmcc;
use Timber\Timber;

class OrderbyWithCategories extends Timber {

  public function __construct() {
    parent::__construct();
    add_filter('timber/twig', array($this, 'add_to_twig'));
    add_filter('timber/context', array($this, 'add_to_context'));
    
    add_action('plugins_loaded', array($this, 'plugin_timber_locations'));
    add_action('plugins_loaded', array($this, 'plugin_text_domain_init')); 
    add_action('wp_enqueue_scripts', array($this, 'plugin_enqueue_assets'));
    
    // after plugins are loaded, do the checkout stuff. this is so other plugins have a chance to do their stuff first, including Woo! some actions require this
    add_action('plugins_loaded', array($this, 'on_plugins_loaded'));
    
    add_filter('wc_get_template', array($this, 'wc_get_template'), 10, 5); 
  }
  
  public function on_plugins_loaded() {
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
  }
  
  public function wc_get_template($located, $template_name, $args, $template_path, $default_path) {
    // if defult path deosnt exists, set it
    if (!$default_path) {
      global $woocommerce;
  		$default_path = $woocommerce->plugin_path() . '/templates/';
  	}

    $plugin_path = ORDERBY_WITH_CATEGORIES_PATH.'woocommerce/'.$template_name;
    
    // if the file exists in current plugin, set located to that
    if(@file_exists($plugin_path)) {
      $located = $plugin_path;
    } elseif(@file_exists($located) && !@file_exists($plugin_path)) {
      $located = $located;
    } elseif(!@file_exists($located) && !@file_exists($plugin_path) && @file_exists($default_path)) {
      $located = $default_path;
    }

    return $located;
  }
  
  public function plugin_timber_locations() {
    // if timber::locations is empty (another plugin hasn't already added to it), make it an array
    if(!Timber::$locations) Timber::$locations = array();
    // add a new views path to the locations array
    array_push(
      Timber::$locations, 
      ORDERBY_WITH_CATEGORIES_PATH . 'views'
    );
  }
  public function plugin_text_domain_init() {
    load_plugin_textdomain('orderby-with-categories', false, ORDERBY_WITH_CATEGORIES_BASE. '/languages');
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
  }
  
  public function add_to_twig($twig) { 
    if(!class_exists('Twig_Extension_StringLoader')){
      $twig->addExtension(new Twig_Extension_StringLoader());
    }
    return $twig;
  }
  public function add_to_context($context) {
    return $context;    
  }
}