jQuery(function($) {
  $(".woocommerce-ordering").on("change", "select.product_cats", function() {
    $(this).closest("form").trigger("submit")
  });
});