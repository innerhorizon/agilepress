<?php
/**
 * Presentation code for reports screen(s)
 *
 * @link       https://agilepress.io
 * @since      0.0.0
 *
 * @package    AgilePress
 * @subpackage AgilePress\admin\partials
 */
namespace vinlandmedia\agilepress;

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once WP_PLUGIN_DIR . '/' . plugin_basename('agilepress')  . '/includes/agilepress-query-class.php';

$myQuery = new AgilePress_Query;

$products = $myQuery->get_products_with_meta();

 ?>
 <img src="<?= plugins_url('agilepress') ?>/admin/partials/img/agilepress.png">
 <h3>Reports</h3>
 Reserved for possible future use...<br>
 <?php
 if ($_SERVER["REQUEST_METHOD"] == "GET") {
     $selected_product = $_GET["selected-product"];
     echo '<p></p>You selected: ' . esc_html($selected_product) . '</p>';
 }
 ?>
 <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
     <input type="hidden" name="action" value="report_response">
     <?php
     $select_box = '<select name="selected-product">';
     if ($products) {
         foreach($products as $product) {
             $select_box .=  '<option value="' . esc_html($product->post_name) . '" ' . selected($product->post_name, esc_attr($selected_product), false) . '>' . esc_html($product->post_title) . ' (' . esc_html($product->post_name) . ')' .
                 '</option>';
         }
     } else {
         $select_box .= '<option value="empty">(No products to display.)</option>';
     }
     $select_box .= '</select><br /><br />';
     echo $select_box;
     ?>
<input type="submit" name="submit" value="Select Product" class="default-button">
 <br>
