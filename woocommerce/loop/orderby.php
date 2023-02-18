<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * custom orderby template rendered with timber/twig. 
 * this template overwrites woocommerce/templates/loop/orderby.php
 *
**/

$context = Timber::context();

$cats_args = array(
  'taxonomy' => 'product_cat',
  'hide_empty' => true,
  'orderby' => 'slug',
  'parent' => 0,
);
$context['cats'] = get_terms($cats_args);
$context['catalog_orderby_options'] = $catalog_orderby_options;
$context['orderby'] = $orderby;

Timber::render( 'orderby.twig', $context );