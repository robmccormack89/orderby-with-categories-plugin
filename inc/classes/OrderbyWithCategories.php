<?php
namespace Rmcc;
use Timber\Timber;

class OrderbyWithCategories extends Timber {

  public function __construct() {
    parent::__construct();
    
    // timber stuff. the usual stuff
    add_filter('timber/twig', array($this, 'add_to_twig'));
    add_filter('timber/context', array($this, 'add_to_context'));
    
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    
    // enqueue plugin assets
    add_action('wp_enqueue_scripts', array($this, 'orderby_with_categories_assets'));
    
    add_filter('wc_get_template', array($this, 'wc_get_template'), 10, 5); 
  }
  
  public function orderby_with_categories_assets() {
    wp_enqueue_style(
      'orderby-with-categories',
      ORDERBY_WITH_CATEGORIES_URL . 'public/css/orderby-with-categories.css'
    );
  }
  
  public function add_to_twig($twig) { 
    $twig->addExtension(new \Twig_Extension_StringLoader());
    return $twig;
  }

  public function add_to_context($context) {
    return $context;    
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

}