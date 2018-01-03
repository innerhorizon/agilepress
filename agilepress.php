<?php
/**
 * AgilePress
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://agilepress.io/products/agilepress/
 * @since             1.0.0
 * @package           AgilePress
 *
 * @agilepress
 * Plugin Name:       AgilePress
 * Plugin URI:        https://agilepress.io/products/agilepress/
 * Description:       AgilePress brings Agile product/project management to WordPress.
 * Version:           1.602.7
 * Author:            Vinland Media, LLC
 * Author URI:        https://vinlandmedia.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       agilepress
 * Domain Path:       /languages
 */
namespace vinlandmedia\agilepress;

require_once plugin_dir_path( __FILE__ ) . '/includes/agilepress-plugin-class.php';
require_once plugin_dir_path( __FILE__ ) . '/includes/agilepress-query-class.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/agilepress-admin-class.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/agilepress-metabox-class.php';
require_once plugin_dir_path( __FILE__ ) . '/admin/agilepress-act-deact-class.php';
require_once plugin_dir_path( __FILE__ ) . '/public/agilepress-boards-class.php';

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Activate Plugin
 *
 * This is called when the user activates the plugin via the WordPress
 * admin panel.  It registers the options used by the plugin.
 *
 * @param null
 * @return null
 *
 * @see https://codex.wordpress.org/Function_Reference/register_activation_hook
 * @see deactivate_plugin_agilepress
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function activate_plugin_agilepress($network_wide) {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	global $wpdb;

	$myActivator = new AgilePress_ActDeact;
	$charset_collate = $wpdb->get_charset_collate();

	if ((is_multisite()) && ($network_wide)) {
		$activated = array();

        $my_blogs = $wpdb->get_results('SELECT * FROM ' . $wpdb->blogs);

        foreach ($my_blogs as $my_blog) {
            switch_to_blog($my_blog->blog_id);
            $myActivator->add_options($my_blogs->blog_id);
            $myActivator->add_custom_tables($my_blogs->blog_id);
            $activated[] = $my_blogs->blog_id;
            restore_current_blog();
        }
	} else {
		$myActivator->add_options();
		$myActivator->add_custom_tables();
	}


	$current_user = wp_get_current_user();
	$current_user->add_role('agilepress_admin');

	// Development hook for other plugins
    do_action('agilepress_on_activation');

	// One-time switch used to determine if rediect to getstting started
	// page should fire
	add_option('agilepress_do_activation_redirect', true);

}
register_activation_hook( __FILE__, __NAMESPACE__.'\\activate_plugin_agilepress');


/**
 * Deactivate Plugin
 *
 * This is called when the user deactivates the plugin via the WordPress
 * admin panel.  It a) removes the global options/settings, b) unregisters the
 * custom post types, and c) removes the custom administration roles.
 *
 * @param null
 * @return null
 *
 * @see https://codex.wordpress.org/Function_Reference/register_deactivation_hook
 * @see activate_plugin_agilepress
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function deactivate_plugin_agilepress() {
	//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	//global $wpdb;

	delete_option('agilepress_do_activation_redirect');

	// Development hook for other plugins
    do_action('agilepress_on_deactivation');

	// flush rewrite cache
	flush_rewrite_rules();

}
register_deactivation_hook( __FILE__, __NAMESPACE__.'\\deactivate_plugin_agilepress');


/**
 * Admin Enqueue Scripts
 *
 * Used to register and load the JavaScript and CSS used in various part of the
 * back-end (admin panel).
 *
 * @param null
 * @return null
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_admin_scripts() {
    wp_register_style('agilepress-admin-css', plugins_url('agilepress') . '/admin/css/plugin-agilepress-admin.css');
    wp_enqueue_style('agilepress-admin-css');

	//wp_register_style('agilepress-datatables-css', plugins_url('agilepress') . '/admin/datatables/datatables.min.css');
    //wp_enqueue_style('agilepress-datatables-css');

    wp_register_script('agilepress-admin-js', plugins_url('agilepress') . '/admin/js/plugin-agilepress-admin.js', array('jquery', 'wp-api'));
	wp_localize_script('agilepress-admin-js', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
    wp_enqueue_script('agilepress-admin-js');

	//wp_register_script('agilepress-datatables-js', plugins_url('agilepress') . '/admin/datatables/datatables.min.js', array('jquery'));
    //wp_enqueue_script('agilepress-datatables-js');
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\agilepress_admin_scripts');


/**
 * Remove Admin Footer
 *
 * Unfortunately, the "Thank you for creating with Wordpress" was getting in my
 * way; this removes it.
 *
 * @param null
 * @return null
 *
 * @see https://developer.wordpress.org/reference/hooks/admin_footer_text/
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function remove_admin_footer() {
	echo '';
}
add_filter('admin_footer_text', __NAMESPACE__ . '\\remove_admin_footer');


/**
 * WP Enqueue Scripts
 *
 * Used to register and load the JavaScript and CSS used for front-end display
 * (i.e in posts, pages, etc.)
 *
 * @param null
 * @return null
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_wp_scripts() {
    wp_register_style('w3_public_css', plugins_url('agilepress') . '/public/css/w3-lite.css');
    wp_enqueue_style('w3_public_css');

	wp_register_style('agilepress_css', plugins_url('agilepress') . '/public/css/plugin-agilepress-public.css');
    wp_enqueue_style('agilepress_css');

	wp_register_style('fontawesome-js', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', null);
    wp_enqueue_style('fontawesome-js');

	//wp_register_script('noteicons-js', plugins_url('agilepress') . '/public/js/noteicons.js', array('wp-api'));
    //wp_localize_script('noteicons-js');

	wp_register_script('agilepress-js', plugins_url('agilepress') . '/public/js/agilepress.js', array('jquery', 'wp-api'));
	wp_localize_script('agilepress-js', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));

	wp_enqueue_script('jquery');
    wp_enqueue_script('agilepress-js');

	// Jquery Mobile
	//wp_register_script('ap-jqmobile-js', plugins_url('agilepress') . '/public/js/jquery.mobile.custom/jquery.mobile.custom.min.js', array('jquery'), '1.4.5');
	//wp_localize_script('ap-jqmobile-js', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
	//wp_register_style('ap-jqmobile-css', plugins_url('agilepress') . '/public/js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.min.css');
	//wp_enqueue_script('ap-jqmobile-js');
	//wp_enqueue_style('ap-jqmobile-css');

  }
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\agilepress_wp_scripts');


/**
 * Admin Menu
 *
 * Calls the method that creates the custom left-hand admin menu for the
 * plugin's features.
 *
 * @param null
 * @return null
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Admin::add_leftside_admin_menu()
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_admin_menu() {
	$myAgilePress_Admin = new AgilePress_Admin;

	$return_code = $myAgilePress_Admin->add_leftside_admin_menu();
}
add_action('admin_menu', __NAMESPACE__.'\\agilepress_admin_menu' );


/**
 * Initialization
 *
 * Performs various plugin initialization tasks: registers the custom post
 * types (CPT) and creates new user roles.
 *
 * @param null
 * @return null
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Init::register_product()
 * @uses \vinlandmedia\agilepress\AgilePress_Init::register_sprint()
 * @uses \vinlandmedia\agilepress\AgilePress_Init::register_task()
 * @uses \vinlandmedia\agilepress\AgilePress_Init::register_story()
 * @uses \vinlandmedia\agilepress\AgilePress_Init::setup_roles()
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/init
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_init($network_wide) {
	global $wp;

	$myAgilePress_Init = new AgilePress_Init;

	//register the products custom post type
	$args = $myAgilePress_Init->register_product();
	register_post_type('agilepress-products', $args);

	//register the sprints custom post type
	$args = $myAgilePress_Init->register_sprint();
	register_post_type('agilepress-sprints', $args);

	//register the tasks custom post type
	$args = $myAgilePress_Init->register_task();
	register_post_type('agilepress-tasks', $args);

	//register the user stories custom post type
	$args = $myAgilePress_Init->register_story();
	register_post_type('agilepress-stories', $args);

	//register all custom taxonomies
	$myAgilePress_Init->register_custom_taxonomies();

	// flush rewrite cache
    flush_rewrite_rules();

	$return_code = $myAgilePress_Init->setup_roles('agilepress_admin');
	$return_code = $myAgilePress_Init->setup_roles('agilepress_developer');
	$return_code = $myAgilePress_Init->setup_roles('agilepress_user');
	$return_code = $myAgilePress_Init->setup_roles('agilepress_viewer');

	// flush rewrite cache
    flush_rewrite_rules();

    $redirect_url = home_url(add_query_arg(array(), $wp->request));

	if (get_option('agilepress_do_activation_redirect', false)) {
	    delete_option('agilepress_do_activation_redirect');
	    if ((!is_multisite()) || (is_multisite() && $network_wide)) {
	        wp_redirect(get_site_url() . '/wp-admin/admin.php?page=ap_get_started_menu');
			exit;
	    } else {
	    	wp_redirect(get_site_url() . '/wp-admin/admin.php?page=ap_get_started_menu');
			exit;
	    }
	 }

	 // flush rewrite cache
     flush_rewrite_rules();

}
add_action('init', __NAMESPACE__.'\\agilepress_init');

/**
 * Product Columns
 *
 * The Admin-screen list display of the Products CPT needs to be explicitly
 * told what columns to show.
 *
 * @param $columns  Default column array for Products.
 * @return $new_columns  Modified column array for Products.
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Admin::custom_list_cols()
 *
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function add_new_agilepress_products_columns($columns) {
	$myAgilePress_Admin = new AgilePress_Admin;

	$new_columns = $myAgilePress_Admin->custom_list_cols('products');

    return $new_columns;
}
add_filter('manage_edit-agilepress-products_columns', __NAMESPACE__.'\\add_new_agilepress_products_columns');


/**
 * Sprint Columns
 *
 * The Admin-screen list display of the Sprints CPT needs to be explicitly
 * told what columns to show.
 *
 * @param $columns  Default column array for Sprints.
 * @return $new_columns  Modified column array for Sprints.
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Admin::custom_list_cols()
 *
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function add_new_agilepress_sprints_columns($columns) {
	$myAgilePress_Admin = new AgilePress_Admin;

	$new_columns = $myAgilePress_Admin->custom_list_cols('sprints');

    return $new_columns;
}
add_filter('manage_edit-agilepress-sprints_columns', __NAMESPACE__.'\\add_new_agilepress_sprints_columns');

/**
 * Task Columns
 *
 * The Admin-screen list display of the Tasks CPT needs to be explicitly
 * told what columns to show.
 *
 * @param $columns  Default column array for Tasks.
 * @return $new_columns  Modified column array for Tasks.
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Admin::custom_list_cols()
 *
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function add_new_agilepress_tasks_columns($columns) {
	$myAgilePress_Admin = new AgilePress_Admin;

	$new_columns = $myAgilePress_Admin->custom_list_cols('tasks');

    return $new_columns;
}
add_filter('manage_edit-agilepress-tasks_columns', __NAMESPACE__.'\\add_new_agilepress_tasks_columns');

/**
 * Story Columns
 *
 * The Admin-screen list display of the Stories CPT needs to be explicitly
 * told what columns to show.
 *
 * @param $columns  Default column array for Stories.
 * @return $new_columns  Modified column array for Stories.
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Admin::custom_list_cols()
 *
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function add_new_agilepress_stories_columns($columns) {
	$myAgilePress_Admin = new AgilePress_Admin;

	$new_columns = $myAgilePress_Admin->custom_list_cols('stories');

    return $new_columns;
}
add_filter('manage_edit-agilepress-stories_columns', __NAMESPACE__.'\\add_new_agilepress_stories_columns');

/**
 * List Custom Columns
 *
 * The custom columns in Admin post-list screens for CPTs need to have the contents
 * of the custom columns filled in by AgilePress.
 *
 * @param $column_name Column name
 * @param $post_ID Post ID
 * @return $string custom column information
 *
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
 *
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function list_custom_columns($column_name, $post_ID) {
	global $wpdb;

	$post_type = get_post_type($post_ID);

	switch ($post_type) {
		case 'agilepress-products':
			$agilepress_meta = get_post_meta($post_ID, '_agilepress_product_data', true);
			break;

		case 'agilepress-sprints':
			$agilepress_meta = get_post_meta($post_ID, '_agilepress_sprint_data', true);
			break;

		case 'agilepress-stories':
			$agilepress_meta = get_post_meta($post_ID, '_agilepress_story_data', true);
			break;

		case 'agilepress-tasks':
			$agilepress_meta = get_post_meta($post_ID, '_agilepress_task_data', true);
			break;

		default:
			$agilepress_meta = '';
			break;
	}

	switch ($column_name) {
		case 'product':
			if ((isset($agilepress_meta['product'])) && (!empty($agilepress_meta['product']))) {
				$post_product = $agilepress_meta['product'];

				$product_details = $wpdb->get_row("
					select p.ID, p.post_type, p.post_title, p.post_name
					from " . $wpdb->posts . " p
					where p.post_type = 'agilepress-products' and p.post_status = 'publish'
					and p.post_name = '" . $post_product . "'
					");

				$product_link = get_permalink($product_details->ID);
				$product_title = $product_details->post_title;
			} else {
				$post_product = '';
			}

			if ($post_product) {
				echo '<a href="' . $product_link . '">' . $product_title . '</a>';
			}
			break;

		case 'sprint':
			if ((isset($agilepress_meta['sprint'])) && (!empty($agilepress_meta['sprint']))) {
				$post_sprint = $agilepress_meta['sprint'];

				$sprint_details = $wpdb->get_row("
					select p.ID, p.post_type, p.post_title, p.post_name
					from " . $wpdb->posts . " p
					where p.post_type = 'agilepress-sprints' and p.post_status = 'publish'
					and p.post_name = '" . $post_sprint . "'
					");

				$sprint_link = get_permalink($sprint_details->ID);
				$sprint_title = $sprint_details->post_title;
			} else {
				$post_sprint = '';
			}

			if ($post_sprint) {
				echo '<a href="' . $sprint_link . '">' . $sprint_title . '</a>';
			}
			break;

		case 'has_task':
			if ((isset($agilepress_meta['story_status'])) && (!empty($agilepress_meta['story_status']))) {
				if ($agilepress_meta['story_status'] == 'hastasks') {
					$post_has_task = 'Yes';
				} else {
					$post_has_task = 'No';
				}
			} else {
				$post_has_task = '-';
			}

			if ($post_has_task) {
				echo $post_has_task;
			}
			break;

		case 'product_shortcode':
			$product_details = $wpdb->get_row("
				select p.ID, p.post_type, p.post_title, p.post_name
				from " . $wpdb->posts . " p
				where p.ID = '" . $post_ID . "'
				");

			echo '[agilepress board=backlog product=' . $product_details->post_name . ']';

			break;

		case 'kanban_shortcode':
			$product_details = $wpdb->get_row("
				select p.ID, p.post_type, p.post_title, p.post_name
				from " . $wpdb->posts . " p
				where p.ID = '" . $post_ID . "'
				");

			echo '[agilepress board=kanban product=' . $product_details->post_name . ']';

			break;

		case 'sprint_shortcode':
			$sprint_details = $wpdb->get_row("
				select p.ID, p.post_type, p.post_title, p.post_name
				from " . $wpdb->posts . " p
				where p.ID = '" . $post_ID . "'
				");

			$agilepress_meta = get_post_meta($sprint_details->ID, '_agilepress_sprint_data', true);

			echo '[agilepress board=sprint product=' . $agilepress_meta['product'] .
				' sprint=' . $sprint_details->post_name . ']';

			break;

		default:
			# code...
			break;
	}

}
add_action('manage_posts_custom_column', __NAMESPACE__.'\\list_custom_columns', 10, 2);

/**
 * Register Metaboxes
 *
 * Function to register the four metaboxes that will be used by the plugin (one
 * metabox for each CPT).
 *
 * @param null
 * @return null
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_register_meta_boxes() {
	add_meta_box('agilepress-product-meta', __('Product Information','agilepress'), __NAMESPACE__.'\\agilepress_product_meta_box', 'agilepress-products', 'normal', 'high');
	add_meta_box('agilepress-sprint-meta', __('Sprint Information','agilepress'), __NAMESPACE__.'\\agilepress_sprint_meta_box', 'agilepress-sprints', 'normal', 'high');
	add_meta_box('agilepress-task-meta', __('Task Information','agilepress'), __NAMESPACE__.'\\agilepress_task_meta_box', 'agilepress-tasks', 'normal', 'high');
	add_meta_box('agilepress-story-meta', __('Story Information','agilepress'), __NAMESPACE__.'\\agilepress_story_meta_box', 'agilepress-stories', 'normal', 'high');

	add_meta_box('agilepress-attachment-meta', __('Attachments','agilepress'), __NAMESPACE__.'\\agilepress_attachment_meta_box', 'agilepress-products', 'normal', 'default');
	add_meta_box('agilepress-attachment-meta', __('Attachments','agilepress'), __NAMESPACE__.'\\agilepress_attachment_meta_box', 'agilepress-sprints', 'normal', 'default');
	add_meta_box('agilepress-attachment-meta', __('Attachments','agilepress'), __NAMESPACE__.'\\agilepress_attachment_meta_box', 'agilepress-tasks', 'normal', 'default');
	add_meta_box('agilepress-attachment-meta', __('Attachments','agilepress'), __NAMESPACE__.'\\agilepress_attachment_meta_box', 'agilepress-stories', 'normal', 'default');
}
add_action('add_meta_boxes', __NAMESPACE__.'\\agilepress_register_meta_boxes');


/**
 * Product Metabox
 *
 * This function defines the form and function of the Product metabox.
 *
 * @param $post  The current post in The Loop.
 * @return null
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Meta::build_product_meta_box()
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_product_meta_box($post) {

	$myMetabox = new AgilePress_Meta;

    // retrieve our custom meta box values
    $agilepress_meta = get_post_meta($post->ID, '_agilepress_product_data', true);

	$metabox_display = $myMetabox->build_product_meta_box($agilepress_meta);

	echo $metabox_display;
}

/**
 * Save the Product metabox
 *
 * This function fascilitates the saving of Product metabox data.
 *
 * @var integer $post_id  The id of the post whose meta is being stored.
 * @return null
 *
 * @global $wpdb
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_save_product_meta_box($post_id) {
	//verify the post type is for AgilePress and metadata has been posted
	if (get_post_type($post_id) == 'agilepress-products' && isset($_POST['agilepress_product'])) {

		//if autosave skip saving data
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		//check nonce for security
		wp_verify_nonce('product-meta-box-save', 'agilepress');

		//store option values in a variable
        $agilepress_product_data = sanitize_meta('_agilepress_product_data', $_POST['agilepress_product'], 'post');

        //use array map function to sanitize option values
        $agilepress_product_data = array_map('sanitize_text_field', $agilepress_product_data);

        // save the meta box data as post metadata
        update_post_meta($post_id, '_agilepress_product_data', $agilepress_product_data);

	}
}
add_action('save_post', __NAMESPACE__.'\\agilepress_save_product_meta_box');


/**
 * Attachment Metabox
 *
 * This function defines the form and function of the Attachment metabox.
 *
 * @param $post  The current post in The Loop.
 * @return null
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Meta::build_attachment_meta_box()
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_attachment_meta_box($post) {

	$myMetabox = new AgilePress_Meta;

    // retrieve our custom meta box values
    $agilepress_meta = get_post_meta($post->ID, '_agilepress_attachment_data', false);

	$metabox_display = $myMetabox->build_attachment_meta_box($agilepress_meta, false);

	echo $metabox_display;
}

/**
 * Save the Attachment metabox
 *
 * This function fascilitates the saving of Attachment metabox data.
 *
 * @var integer $post_id  The id of the post whose meta is being stored.
 * @return null
 *
 * @global $wpdb
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_save_attachment_meta_box($post_id) {
	/* --- security verification --- */
	//if (!wp_verify_nonce('attachment-meta-box-save', 'agilepress')) {
	//  return $post_id;
	//} // end if

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	  return $post_id;
	}

	//check nonce for security
	wp_verify_nonce('attachment-meta-box-save', 'agilepress');

	/*
	if ('page' == $_POST['post_type']) {
	  if (!current_user_can('edit_page', $post_id)) {
		return $post_id;
	  } // end if
	} else {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		} // end if
	} // end if
	*/
	/* - end security verification - */


	// Make sure the file array isn't empty
	if (!empty($_FILES['agilepress_attachment']['name'])) {

		// Setup the array of supported file types. In this case, it's just PDF.
		//$supported_types = array('application/pdf');

		// Get the file type of the upload
		$arr_file_type = wp_check_filetype(basename($_FILES['agilepress_attachment']['name']));
		//$uploaded_type = $arr_file_type['type'];

		// Check if the type is supported. If not, throw an error.
		//if (in_array($uploaded_type, $supported_types)) {

			// Use the WordPress API to upload the file
			$upload = wp_upload_bits($_FILES['agilepress_attachment']['name'], null, file_get_contents($_FILES['agilepress_attachment']['tmp_name']));

			if (isset($upload['error']) && $upload['error'] != 0) {
				wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
			} else {
				add_post_meta($post_id, '_agilepress_attachment_data', $upload);
				//update_post_meta($post_id, '_agilepress_attachment_data', $upload);
			} // end if/else

		//} else {
			//wp_die("The file type that you've uploaded is not a PDF.");
		//} // end if/else

	} else {
		if ((isset($_POST['delete_attachment'])) && (!empty($_POST['delete_attachment']))) {
			$current_meta_values = get_post_meta($post_id, '_agilepress_attachment_data', false);
			foreach ($current_meta_values as $current_meta_value) {
				if ($current_meta_value['file'] == $_POST['delete_attachment']) {
					$current_meta = $current_meta_value;
					break;
				}
			}
			wp_delete_file($_POST['delete_attachment']);

			delete_post_meta($post_id, '_agilepress_attachment_data', $current_meta);
		}
	}

}
add_action('save_post', __NAMESPACE__.'\\agilepress_save_attachment_meta_box');



/**
 * Sprint Metabox
 *
 * This function defines the form and function of the Sprint metabox.
 *
 * @param object $post  The current post in The Loop.
 * @return null
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Meta::build_sprint_meta_box()
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_sprint_meta_box($post) {
	//global $wpdb;

	$myQuery = new AgilePress_Query;

	$products = $myQuery->get_products();

	$myMetabox = new AgilePress_Meta;

    // retrieve our custom meta box values
    $agilepress_meta = get_post_meta($post->ID, '_agilepress_sprint_data', true);

	$metabox_display = $myMetabox->build_sprint_meta_box($agilepress_meta, $products);

	echo $metabox_display;

}

/**
 * Save the Sprint metabox
 *
 * This function fascilitates the saving of Sprint metabox data.
 *
 * @var integer $post_id  The id of the post whose meta is being stored.
 * @return null
 *
 * @global $wpdb
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_save_sprint_meta_box($post_id) {

	//verify the post type is for AgilePress and metadata has been posted
	if (get_post_type($post_id) == 'agilepress-sprints' && isset($_POST['agilepress_sprint'])) {

		//if autosave skip saving data
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		//check nonce for security
		wp_verify_nonce('sprint-meta-box-save', 'agilepress');

        //store option values in a variable
        $agilepress_sprint_data = sanitize_meta('_agilepress_sprint_data', $_POST['agilepress_sprint'], 'post');

        //use array map function to sanitize option values
        $agilepress_sprint_data = array_map('sanitize_text_field', $agilepress_sprint_data);

        // save the meta box data as post metadata
        update_post_meta($post_id, '_agilepress_sprint_data', $agilepress_sprint_data);

		wp_set_object_terms($post_id, $agilepress_sprint_data['product'], 'sprint-products');
	}
}
add_action('save_post', __NAMESPACE__.'\\agilepress_save_sprint_meta_box');


/**
 * Task Metabox
 *
 * This function defines the form and function of the Task metabox.
 *
 * @param object $post  The current post in The Loop.
 * @return null
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Meta::build_task_meta_box()
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_task_meta_box($post) {
	//global $wpdb;

	$myQuery = new AgilePress_Query;

	$products = $myQuery->get_products();
	$sprints = $myQuery->get_sprints();
	$stories = $myQuery->get_stories();

	$myMetabox = new AgilePress_Meta;

    // retrieve our custom meta box values
    $agilepress_meta = get_post_meta($post->ID, '_agilepress_task_data', true);

	$metabox_display = $myMetabox->build_task_meta_box($agilepress_meta, $products, $sprints, $stories, true);

	echo $metabox_display;

}

/**
 * Save the Task metabox
 *
 * This function fascilitates the saving of Task metabox data.
 *
 * @var integer $post_id  The id of the post whose meta is being stored.
 * @return null
 *
 * @global $wpdb
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_save_task_meta_box($post_id) {

	//verify the post type is for AgilePress and metadata has been posted
	if (get_post_type($post_id) == 'agilepress-tasks' && isset($_POST['agilepress_task'])) {

		//if autosave skip saving data
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		//check nonce for security
		wp_verify_nonce('task-meta-box-save', 'agilepress');

        //store option values in a variable
        $agilepress_task_data = sanitize_meta('_agilepress_task_data', $_POST['agilepress_task'], 'post');

        //use array map function to sanitize option values
        $agilepress_task_data = array_map('sanitize_text_field', $agilepress_task_data);

		// set user
		$current_user = wp_get_current_user();
		$agilepress_task_data['task_assignee'] = $current_user->get('user_login');

		if ((!isset($agilepress_task_data['task_priority'])) || (empty($agilepress_task_data['task_priority']))
		 	|| ($agilepress_task_data['task_priority'] == '')) {
			$agilepress_task_data['task_priority'] = '2';
		}

		// save the meta box data as post metadata
        update_post_meta($post_id, '_agilepress_task_data', $agilepress_task_data);

		wp_set_object_terms($post_id, $agilepress_task_data['product'], 'task-products');
		wp_set_object_terms($post_id, $agilepress_task_data['sprint'], 'task-sprints');
	}
}
add_action('save_post', __NAMESPACE__.'\\agilepress_save_task_meta_box');


/**
 * Story Metabox
 *
 * This function defines the form and function of the Story metabox.
 *
 * @param object $post  The current post in The Loop.
 * @return null
 *
 * @uses \vinlandmedia\agilepress\AgilePress_Meta::build_story_meta_box()
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_story_meta_box($post) {
	global $wpdb;

	$myQuery = new AgilePress_Query;

	$products = $myQuery->get_products();
	$sprints = $myQuery->get_sprints();

	$myMetabox = new AgilePress_Meta;

    // retrieve our custom meta box values
    $agilepress_meta = get_post_meta($post->ID, '_agilepress_story_data', true);

	$metabox_display = $myMetabox->build_story_meta_box($agilepress_meta, $products, $sprints, true);

	echo $metabox_display;

}

/**
 * Save the Story metabox
 *
 * This function fascilitates the saving of Story metabox data.
 *
 * @var integer $post_id  The id of the post whose meta is being stored.
 * @return null
 *
 * @global $wpdb
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_save_story_meta_box($post_id) {

	//verify the post type is for AgilePress and metadata has been posted
	if (get_post_type($post_id) == 'agilepress-stories' && isset($_POST['agilepress_story'])) {

		//if autosave skip saving data
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		//check nonce for security
		wp_verify_nonce('story-meta-box-save', 'agilepress');

        //store option values in a variable
        $agilepress_story_data = sanitize_meta('_agilepress_story_data', $_POST['agilepress_story'], 'post');

        //use array map function to sanitize option values
        $agilepress_story_data = array_map('sanitize_text_field', $agilepress_story_data);

		// set user
		//$current_user = wp_get_current_user();
		//$agilepress_story_data['story_assignee'] = $current_user->get('user_login');

		if ((!isset($agilepress_story_data['story_priority'])) || (empty($agilepress_story_data['story_priority']))
		    || ($agilepress_story_data['story_priority'] == '')) {
			$agilepress_story_data['story_priority'] = '2';
		}

        // save the meta box data as post metadata
        update_post_meta($post_id, '_agilepress_story_data', $agilepress_story_data);

		wp_set_object_terms($post_id, $agilepress_story_data['product'], 'story-products');
	}
}
add_action( 'save_post', __NAMESPACE__.'\\agilepress_save_story_meta_box' );


/**
 * Informational Shortcodes
 *
 * Uses a shortcode with various parameters to display single items of metadata
 * as needed in posts and pages.
 *
 * @param string $atts  Shortcode attributes (to be expounded upon later!)
 * @return string $agilepress_show  Formatted shortcode output.
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_show_product_info($atts) {
    global $post;

	$fetch_data_atts = shortcode_atts( array(
		"type" => '',
		"value" => '',
		"show" => ''
    ), $atts );

	if ((!isset($fetch_data_atts['type'])) || (!isset($fetch_data_atts['show']))) {
		$agilepress_show = 'There is an error in your shortcode.';
	} else {
		$myQuery = new AgilePress_Query;
		switch ($fetch_data_atts['type']) {
			case 'product':
				$datarow = $myQuery->get_cpt_by_name($fetch_data_atts['value'], 'agilepress-products');
				$agilepress_product_data = get_post_meta($datarow->ID, '_agilepress_product_data', true);

				if ($fetch_data_atts['show'] == 'version') {
					if (isset($agilepress_product_data['version'])) {
						$return_value = $agilepress_product_data['version'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'target') {
					if (isset($agilepress_product_data['target'])) {
						$return_value = $agilepress_product_data['target'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'phase') {
					if (isset($agilepress_product_data['phase'])) {
						$return_value = $agilepress_product_data['phase'];
					} else {
						$return_value = '';
					}
				}

				break;

			case 'story':
				$datarow = $myQuery->get_cpt_by_name($fetch_data_atts['value'], 'agilepress-stories');
				$agilepress_story_data = get_post_meta($datarow->ID, '_agilepress_story_data', true);

				if ($fetch_data_atts['show'] == 'version') {
					if (isset($agilepress_story_data['version'])) {
						$return_value = $agilepress_story_data['version'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'story_status') {
					if (isset($agilepress_story_data['story_status'])) {
						$return_value = $agilepress_story_data['story_status'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'parent_epic') {
					if (isset($agilepress_story_data['parent_epic'])) {
						$return_value = $agilepress_story_data['parent_epic'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'story_sprint') {
					if (isset($agilepress_story_data['story_sprint'])) {
						$return_value = $agilepress_story_data['story_sprint'];
					} else {
						$return_value = '';
					}
				}

				break;

			case 'task':
				$datarow = $myQuery->get_cpt_by_name($fetch_data_atts['value'], 'agilepress-tasks');
				$agilepress_task_data = get_post_meta($datarow->ID, '_agilepress_task_data', true);

				if ($fetch_data_atts['show'] == 'product') {
					if (isset($agilepress_task_data['product'])) {
						$return_value = $agilepress_task_data['product'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'sprint') {
					if (isset($agilepress_task_data['sprint'])) {
						$return_value = $agilepress_task_data['sprint'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'parent_story') {
					if (isset($agilepress_task_data['parent_story'])) {
						$return_value = $agilepress_task_data['parent_story'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'task_status') {
					if (isset($agilepress_task_data['task_status'])) {
						$return_value = $agilepress_task_data['task_status'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'task_assignee') {
					if (isset($agilepress_task_data['task_assignee'])) {
						$return_value = $agilepress_task_data['task_assignee'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'task_priority') {
					if (isset($agilepress_task_data['task_priority'])) {
						$return_value = $agilepress_task_data['task_priority'];
					} else {
						$return_value = '';
					}
				}

				break;

			case 'sprint':
				$datarow = $myQuery->get_cpt_by_name($fetch_data_atts['value'], 'agilepress-sprints');
				$agilepress_sprint_data = get_post_meta($datarow->ID, '_agilepress_sprint_data', true);

				if ($fetch_data_atts['show'] == 'product') {
					if (isset($agilepress_sprint_data['product'])) {
						$return_value = $agilepress_sprint_data['product'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'start_date') {
					if (isset($agilepress_sprint_data['start_date'])) {
						$return_value = $agilepress_sprint_data['start_date'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'end_date') {
					if (isset($agilepress_sprint_data['end_date'])) {
						$return_value = $agilepress_sprint_data['end_date'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'status') {
					if (isset($agilepress_sprint_data['status'])) {
						$return_value = $agilepress_sprint_data['status'];
					} else {
						$return_value = '';
					}
				} elseif ($fetch_data_atts['show'] == 'backlog_target') {
					if (isset($agilepress_sprint_data['backlog_target'])) {
						$return_value = $agilepress_sprint_data['backlog_target'];
					} else {
						$return_value = '';
					}
				}

				break;


			default:
				# code...
				break;
		}
	}

	//return the shortcode value to display
    return $return_value;
}
add_shortcode('agilepress-data', __NAMESPACE__.'\\agilepress_show_product_info');


/**
 * Core Plugin Function
 *
 * This function, based upon a shortcode and parameters, returns one of three
 * functional Scrum boards (story board, product backlog, and sprint).
 *
 * @param string $atts  Shortcode attributes (to be expounded upon later!)
 * @return string $agilepress_show  Formatted Scrum board output.
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_main($atts)
{
	global $wpdb;

	$products_rowcount = 0;
	$sprints_rowcount = 0;
	$stories_rowcount = 0;
	$tasks_rowcount = 0;

	$fetch_data_atts = shortcode_atts( array(
        'board' => '',
        'product' => '',
        'sprint' => '',
		'public' => ''
    ), $atts );

	if (!isset($fetch_data_atts['product'])) {
		return 'A product is required for this board.';
	} elseif (!isset($fetch_data_atts['board'])) {
		return 'A board type is required.';
	}

	$public_board = false;

	if (isset($fetch_data_atts['public'])) {
		if ('yes' == strtolower($fetch_data_atts['public'])) {
			$public_board = true;
		}
	}

	$myAgilePress_Boards = new AgilePress_Boards;
	$myQuery = new AgilePress_Query;

	$myProduct = $fetch_data_atts['product'];

	$products_rowcount = $myQuery->get_row_count('_agilepress_product_data', $myProduct);

	$myBoard = $fetch_data_atts['board'];

	$mySprint = $fetch_data_atts['sprint'];

	if (!isset($mySprint)) {
		$sprints_rowcount = 0;
	} else {
		$sprints_rowcount = $myQuery->get_row_count('_agilepress_sprint_data', $myProduct);
	}

	//if (($myBoard == 'backlog') || ($myBoard == 'sprint')) {
		$stories_rowcount = $myQuery->get_row_count('_agilepress_story_data', $myProduct);
		if ($stories_rowcount > 0) {
			$stories = $wpdb->get_results(
				"select p.ID as id, p.post_title as story_title, p.post_name as story_name, " .
				"post_content, post_excerpt, m.meta_value, " .
				"substr(m.meta_value, locate(';s:', m.meta_value, locate('task_priority', m.meta_value))+6, 1) as priority " .
				"from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m " .
				"where p.ID = m.post_id and m.meta_key = '_agilepress_story_data' ".
				"and m.meta_value like '%" . $myProduct . "%' " .
				"order by 7 desc"
			);
		}

	//} elseif ($myBoard != 'backlog') {
		$tasks_rowcount = $myQuery->get_row_count('_agilepress_task_data', $myProduct);
		if ($tasks_rowcount > 0) {
			$tasks = $wpdb->get_results(
				"select p.ID as id, p.post_title as task_title, p.post_name as task_name, " .
				"post_content, post_excerpt, m.meta_value, " .
				"substr(m.meta_value, locate(';s:', m.meta_value, locate('task_priority', m.meta_value))+6, 1) as priority " .
				"from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m " .
				"where p.ID = m.post_id and m.meta_key = '_agilepress_task_data' ".
				"and m.meta_value like '%" . $myProduct . "%' " .
				"order by 7 desc"
			);
		}
	//}

	if ((!is_user_logged_in()) && (!$public_board)) {
		$display_board = "You must be logged in to view this board.";
	} elseif ((!user_can(wp_get_current_user(), 'transition_tasks')) &&
		(!user_can(wp_get_current_user(), 'view_boards')) && (!$public_board)) {
			$display_board = "You are not authorized to view Scrum boards.";
	} else {
		//if (($product_rowcount > 0) || ($myBoard == 'summary'))  {
			switch ($myBoard) {
				case 'summary':
					$page_heading = 'Product Summary';

					$display_board = $myAgilePress_Boards->summarypage($page_heading, $myProduct);
					break;

				case 'kanban':
					if ($tasks_rowcount > 0) {
						$page_heading = 'Kanban Board';
						$header_product_title = get_header_title($tasks, $fetch_data_atts['product']);

						$display_board = $myAgilePress_Boards->kanbanboard($page_heading, $tasks, $fetch_data_atts['product']);
					} else {
						$display_board = 'This board currently has no display items. If you believe this to be in error, please check the shortcode for the board and also the settings for the tasks or stories that you expected to see.';
					}
					if (user_can(wp_get_current_user(), 'transition_tasks')) {
						$display_board .= add_button( 'kanban', $fetch_data_atts['product'], null );
					}
					break;

				case 'backlog':
					if ($stories_rowcount > 0) {
						$page_heading = 'Product Backlog';

						$header_product_title = get_header_title($stories, $fetch_data_atts['product']);

						$display_board = $myAgilePress_Boards->backlogboard($page_heading, $header_product_title, $stories, $fetch_data_atts['product']);
					} else {
						$display_board = 'This board currently has no display items. If you believe this to be in error, please check the shortcode for the board and also the settings for the tasks or stories that you expected to see.';
					}
					if (user_can(wp_get_current_user(), 'transition_tasks')) {
						$display_board .= add_button( 'backlog', $fetch_data_atts['product'], null );
					}
					break;

				case 'sprint':
					if (($tasks_rowcount > 0) || ($stories_rowcount > 0)) {
						$page_heading = 'Product Sprint';

						if ($tasks) {
							$header_product_title = get_header_title($tasks, $fetch_data_atts['product']);
						} elseif ($stories) {
							$header_product_title = get_header_title($stories, $fetch_data_atts['product']);
						} else {
							$header_product_title = 'Unable to get product title';
						}

						$display_board = $myAgilePress_Boards->sprintboard($page_heading, $header_product_title, $stories, $tasks, $fetch_data_atts['product'], $fetch_data_atts['sprint']);
					} else {
						$display_board = 'This board currently has no display items. If you believe this to be in error, please check the shortcode for the board and also the settings for the tasks or stories that you expected to see.';
					}
					if (user_can(wp_get_current_user(), 'transition_tasks')) {
						$display_board .= add_button( 'sprint', $fetch_data_atts['product'], $fetch_data_atts['sprint'] );
					}
					break;

				default:
					$is_story = false;
					$is_backlog = false;
					$is_sprint = false;
					$display_board = 'Nothing to display.';
					break;
			}
		//} else {
			//$display_board = 'Nothing to display. Either their is an error in your shortcode or you do not have a product, stories, and/or tasks from which to produce a board.';
		//}

	}

	return $display_board;
}
add_shortcode('agilepress', __NAMESPACE__.'\\agilepress_main');


/**
 * Get Title (of Product) for Headings
 *
 * Used to get the corrent product name for use in page headings.
 *
 * @param object $tasks  List of product tasks (from database query).
 * @param string $passed_product  Inbound product code from the user's shortcode.
 * @return string $header_product_title  Formatted product title for headings.
 *
 * @global $wpdb
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function get_header_title($tasks = null, $passed_product = null) {
	global $wpdb;

	$header_product_title = '(unidentified)';

	// get page header from metadata
	while ($header_product_title == '(unidentified)') {
		foreach ($tasks as $task) {
			$products = $wpdb->get_results(
				"select p.post_name as product_name, p.post_title as product_title " .
				"from $wpdb->posts p, $wpdb->postmeta m " .
				"where p.ID = m.post_id and m.meta_key = '_agilepress_product_data'");
			foreach ($products as $product) {
				if ($product->product_name == $passed_product) {
					$header_product_title = $product->product_title;
					break;
				}
			}
		}
	}

	return $header_product_title;
}


/**
 * JavaScript drag-and-drop function
 *
 * The JavaScript that gets evoked when notes are dragged and dropped calls
 * this routine to update the database accordingly.
 *
 * @var integer $_POST['id']  ID of the note being dragged and dropped
 * @var string $_POST['status']  Status of the post after being dropped
 * @return string $result  Returns the values 'success' or 'failure'
 *
 * @global $wpdb
 *
 * @uses duplicate()
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function moveitem_ajax() {
	global $wpdb;

	// post ID and status passed from drag/drop function
	$id = sanitize_key($_POST['id']);
	$status = sanitize_text_field($_POST['status']);
	//$priority = sanitize_text_field($_POST['priority']);

	switch ($status) {
		case 'sendtosprint':
			$agilepress_story_meta = get_post_meta($id, '_agilepress_story_data', true);

			if ($agilepress_story_meta['story_sprint'] == '') {
				$current_product = $agilepress_story_meta['product'];
				// get all published sprints
				$sprints = $wpdb->get_results(
					"select ID, post_title, post_name from " . $wpdb->posts . " " .
					"where post_type = 'agilepress-sprints' and post_status = 'publish' ");

				foreach ($sprints as $sprint) {
					// here we want to find a sprint marked as a backlog target
					$agilepress_sprint_meta = get_post_meta($sprint->ID, '_agilepress_sprint_data', true);
					$start_date = $agilepress_sprint_meta['start_date'];
					$end_date = $agilepress_sprint_meta['end_date'];
					$target = $agilepress_sprint_meta['backlog_target'];
					$product = $agilepress_sprint_meta['product'];

					if (($target == 'yes') && ($product == $current_product)) {
						$next_sprint_id = $sprint->ID;
						$next_sprint_title = $sprint->post_title;
						$next_sprint_name = $sprint->post_name;
						break;
					}
				}

				$agilepress_story_meta['story_sprint'] = $next_sprint_name;
			}
				//$agilepress_story_meta['story_status'] = 'activepbi';
			$agilepress_story_meta['story_status'] = $status;
				//$agilepress_story_meta['product'] = $product;

				// create the story as task
				//$new_post_id = duplicate($id, 'agilepress-tasks', $next_sprint_name);

				//$agilepress_story_meta['sibling_id'] = $new_post_id;
			$updated = update_post_meta($id, '_agilepress_story_data', $agilepress_story_meta);

			break;

		case 'isepic':
		case 'isstory':
			$agilepress_story_meta = get_post_meta($id, '_agilepress_story_data', true);

			$agilepress_story_meta['story_status'] = $status;
			$agilepress_story_meta['story_sprint'] = '';

			// save the meta box data as post metadata
			$updated = update_post_meta( $id, '_agilepress_story_data', $agilepress_story_meta );
			break;

		case 'iscomplete':
			$agilepress_story_meta = get_post_meta($id, '_agilepress_story_data', true);

			$agilepress_story_meta['story_status'] = $status;
			$agilepress_story_meta['story_sprint'] = '';
			$agilepress_story_meta['completed_date'] = date("m/d/Y");

			//$sibling_id = $agilepress_story_meta['sibling_id'];

			// save the meta box data as post metadata
			$updated = update_post_meta( $id, '_agilepress_story_data', $agilepress_story_meta );

			// if there is a linked task on the sprint board, it should go away
			//if ($sibling_id != '') {
				//wp_delete_post($sibling_id, false);
			//}

			break;

		default:
			$agilepress_task_meta = get_post_meta($id, '_agilepress_task_data', true);

			$agilepress_task_meta['task_status'] = $status;

			// set completed date
			if ('done' == $status) {
				$agilepress_task_meta['completed_date'] = date("Y-m-d");
			} else {
				$agilepress_task_meta['completed_date'] = null;
			}

			// set user
			$current_user = wp_get_current_user();
			$agilepress_task_meta['task_assignee'] = $current_user->get('user_login');

			/*
			if (isset($status)) {
				$agilepress_task_meta['task_priority'] = $priority;
			} else {
				$agilepress_task_meta['task_priority'] = 0;
			}
			*/
			// save the meta box data as post metadata
			$updated = update_post_meta( $id, '_agilepress_task_data', $agilepress_task_meta );
			break;
	}

	if ($updated) {
		$result = 'success';
	} else {
		$result = 'failure';
	}

	echo $result;

	die();
}
add_action('wp_ajax_moveitem_ajax', __NAMESPACE__.'\\moveitem_ajax');

function nopriv_moveitem_ajax() {
	echo "You must be logged in.";
    die();
}
add_action('wp_ajax_nopriv_moveitem_ajax', __NAMESPACE__.'\\nopriv_moveitem_ajax');

/**
 * Duplicates a post & its meta and it returns the new duplicated Post ID
 * @param  [int] $post_id The Post you want to clone
 * @return [int] The duplicated Post ID
 */
function duplicate($post_id, $post_type, $sprint_name) {
	$old_post = get_post($post_id);

	$new_post = $old_post;

	$new_post->ID = "";
	$new_post->post_type = $post_type;
	$new_post->post_date = "";

  	$new_post_id = wp_insert_post($new_post);

	// Copy post metadata
	$agilepress_story_meta = get_post_meta($post_id, '_agilepress_story_data', true);

	// this is temporary?
	//$agilepress_story_meta['story_status'] = 'hastasks';

  	$agilepress_task_meta['task_status'] = 'activepbi';
  	$agilepress_task_meta['product'] = $agilepress_story_meta['product'];
	$agilepress_task_meta['sibling_id'] = $post_id;
	$agilepress_task_meta['sprint'] = $sprint_name;
	$agilepress_task_meta['task_assignee'] = 'unassigned';

  	// save the meta box data as post metadata
  	//$updated = update_post_meta($post_id, '_agilepress_story_data', $agilepress_story_meta);
  	$updated = add_post_meta($new_post_id, '_agilepress_task_data', $agilepress_task_meta, true);

  	return $new_post_id;
}

/**
 * Custom post tweaker
 *
 * Since content is currently not being used by AgilePress, this function moves
 * the contents of the excerpt field to the content field.  (Just so's content is
 * meaningful and not blank.)  Also, for all pages, it checks to make sure that
 * the user hasn't tried to display more than one board on a single page (not
 * that we have anything against that, it just doesn't work and we're not fixing
 * it yet).
 *
 * @return string $content The modified post/page contents
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function agilepress_cpt_tweaker($content) {
	if ((isset($content)) && (!empty($content))) {
		//global $post;
		$post_types = array(
			"agilepress-products",
			"agilepress-tasks",
			"agilepress-stories",
			"agilepress-sprints");

		if (in_array(get_post_type(), $post_types)) {
			$content = get_the_excerpt(get_the_ID());
		} else {
			$first_board = strpos($content, '[agilepress board=', 0);
			if ((is_page()) && ($first_board >= 0)) {
				if (strpos($content, '[agilepress board=', ($first_board + 1)) > 0) {
					$content = 'Please use only one instance of an AgilePress board per page.';
				}
			}
		}
	}

	return $content;
}
add_filter('the_content', __NAMESPACE__.'\\agilepress_cpt_tweaker');


/*
* Settings submenu
*/
function agilepress_settings_submenu() {
    add_options_page('AgilePress Options Page', 'AgilePress Options',
        'manage_options', __NAMESPACE__ . '\\agilepress_settings_menu',
        __NAMESPACE__ . '\\agilepress_settings_page');

    add_action('admin_init', __NAMESPACE__ . '\\agilepress_register_settings');
}
add_action('admin_menu', __NAMESPACE__ . '\\agilepress_settings_submenu');

/*
* Settings registration
*/
function agilepress_register_settings() {
    register_setting('agilepress-settings-group', 'agilepress_options',
        __NAMESPACE__ . '\\agilepress_sanitize_options');
}

/*
* Settings pages
*/

function agilepress_get_started_page() {
	require_once plugin_dir_path( __FILE__ ) . '/admin/partials/agilepress-get-started-display.php';
}

function agilepress_settings_page() {
	require_once plugin_dir_path( __FILE__ ) . '/admin/partials/agilepress-settings-display.php';
}
/*
function agilepress_reports_page() {
	require_once plugin_dir_path( __FILE__ ) . '/admin/partials/agilepress-reports-display.php';
}
*/
function agilepress_help_page() {
	require_once plugin_dir_path( __FILE__ ) . '/admin/partials/agilepress-help-display.php';
}

function agilepress_sanitize_options($options) {
	$options['agilepress_dummy_option'] = ( ! empty( $options['agilepress_dummy_option'] ) ) ? sanitize_text_field( $options['agilepress_dummy_option'] ) : '';

	return $options;
}

/*
* Settings initialize
*/
function agilepress_settings_init() {
    return 0;
}
add_action('admin_init', __NAMESPACE__ . '\\agilepress_settings_init');


function do_wiz_response() {
	global $wp;
	global $post;
	global $wpdb;

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

	    $product_id = sanitize_key($_POST["product_id"]);
		$sprint_id = sanitize_key($_POST["sprint_id"]);
		$task_id = sanitize_key($_POST["task_id"]);
		$story_id = sanitize_key($_POST["story_id"]);

		$wp_error = "";

		$myQuery = new AgilePress_Query;

		$product = $myQuery->get_single_row($product_id, 'agilepress-products');
		$sprint = $myQuery->get_single_row($sprint_id, 'agilepress-sprints');
		$task = $myQuery->get_single_row($task_id, 'agilepress-tasks');
		$story = $myQuery->get_single_row($story_id, 'agilepress-stories');

		$storyboard_page = array(
		    'post_title'    => 'Storyboard for ' . $product->post_title,
		    'post_content'  => '[agilepress board=story product=' . $product->post_name . ']',
		    'post_status'   => 'publish',
			'post_type'   	=> 'page'
		);

		$storyboard_page_id = wp_insert_post($storyboard_page, $wp_error);

		$backlog_page = array(
		    'post_title'    => 'Product Backlog for ' . $product->post_title,
		    'post_content'  => '[agilepress board=backlog product=' . $product->post_name . ']',
		    'post_status'   => 'publish',
			'post_type'   	=> 'page'
		);

		$backlog_page_id = wp_insert_post($backlog_page, $wp_error);

		$sprint_page = array(
		    'post_title'    => 'Current Sprint for ' . $product->post_title,
		    'post_content'  => '[agilepress board=sprint product=' . $product->post_name . ' sprint=' . $sprint->post_name . ']',
		    'post_status'   => 'publish',
			'post_type'   	=> 'page'
		);

		$sprint_page_id = wp_insert_post($sprint_page, $wp_error);

		$url = home_url($wp->request);

		wp_safe_redirect( $url );

	}

}
add_action( 'admin_post_nopriv_wizard_response', __NAMESPACE__ . '\\do_wiz_response' );
add_action( 'admin_post_wizard_response', __NAMESPACE__ . '\\do_wiz_response' );


/**
 * Model screen responses
 *
 * This function handles all responses from the modal screens that appear when
 * any of the Font Awesome icons across the bottom of the notes are clicked. These
 * are only active on the boards (front-end) although they mimic actions that, in
 * most cases, can also be done from the back-end Admin panel screens.
 *
 * @return null null Redirects to itself after taking required actions
 *
 * @global $wp
 * @global $post
 * @global $wp_rewrite
 *
 * @author Ken Kitchen ken@vinlandmedia.com
 * @author Vinland Media, LLC.
 * @package AgilePress
 */
function do_modal_response() {
	global $wp, $post, $wp_rewrite;

	$current_user = wp_get_current_user();

	$return_code = null;

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// handle the "add item" button
		if (isset($_POST["add-new-task"])) {
			$passed_url = esc_url_raw($_POST["current_url"]);

			$new_task_name = sanitize_text_field($_POST["post_name"]);
			$new_task_description = sanitize_text_field($_POST["post_excerpt"]);
			$new_task_product = sanitize_text_field($_POST["current_product"]);
			$new_task_sprint = sanitize_text_field($_POST["current_sprint"]);

			if ('backlog' == $_POST["board_type"]) {
				$new_post_type = 'agilepress-stories';
				$new_post_meta_name = '_agilepress_story_data';
				$agilepress_task_meta = array(
					'story_status' => 'isstory',
					'product' => $new_task_product,
				);
			} else {
				$new_post_type = 'agilepress-tasks';
				$new_post_meta_name = '_agilepress_task_data';
				$agilepress_task_meta = array(
					'task_status' => 'todo',
					'product' => $new_task_product,
					'sprint' => $new_task_sprint,
					'task_priority' => '2',
				);
			}

			$new_post_data = array(
				'post_content' => $new_task_description,
				'post_excerpt' => $new_task_description,
				'post_title' => $new_task_name,
				'post_status' => 'publish',
				'post_type' => $new_post_type,
			);

			$new_task_id = wp_insert_post($new_post_data);

			add_post_meta($new_task_id, $new_post_meta_name, $agilepress_task_meta);

			wp_safe_redirect($passed_url);
			exit;
		} else {

			$post_id = sanitize_key($_POST["post_id"]);

			$passed_id = sanitize_key($_POST["passed_id"]);
			$passed_action = sanitize_text_field($_POST["passed_action"]);
			$passed_url = esc_url_raw($_POST["current_url"]);

			$board_type = sanitize_text_field($_POST["board_type"]);
			$post_type = get_post_type($passed_id);

			$db_post = get_post($post_id);

			switch ($passed_action) {
				case 'info':
					$passed_excerpt = sanitize_text_field($_POST["post_excerpt"]);
					if ($db_post->post_excerpt != $passed_excerpt) {
						$updated_post = array(
							'ID'			=> $passed_id,
							'post_excerpt'  => $passed_excerpt,
						);
						$return_code = wp_update_post($updated_post);
					}
					break;

				case 'comment':
					$passed_comment = sanitize_text_field($_POST["passed_comment"]);
					$passed_user_email = sanitize_text_field($_POST["passed_user_email"]);
					if (is_user_logged_in()) {
						$this_user_name = $current_user->display_name;
						$this_user_email = $current_user->user_email;
					} else {
						$this_user_name = $passed_user_email;
						$this_user_email = $passed_user_email;
					}

					$new_comment = array(
						'comment_post_ID'	=> $passed_id,
						'comment_content'	=> $passed_comment,
						'comment_author'	=> $this_user_name,
						'comment_author_url'	=> null,
						'comment_author_email'	=> $this_user_email,
						'comment_type'		=> null,
						'comment_approved' => 1,
					);
					$return_code = wp_new_comment($new_comment);

					break;

				case 'transition':
					$passed_title = sanitize_text_field($_POST["passed_title"]);
					$passed_excerpt = sanitize_text_field($_POST["passed_excerpt"]);
					$passed_id = sanitize_key($_POST["post_id"]);

					$new_task_product = sanitize_text_field($_POST["current_product"]);

					$parent_post = get_post($passed_id);

					$myQuery = new AgilePress_Query();
					$target_sprint = $myQuery->get_target_sprint($new_task_product);

					$new_post = array(
						'post_content' => $passed_excerpt,
						'post_excerpt' => $passed_excerpt,
						'post_title' => $passed_title,
						'post_status' => 'publish',
						'post_type' => 'agilepress-tasks',
					);

					$new_task_id = wp_insert_post($new_post);

					$new_post_meta = array(
						'product' => $new_task_product,
						'sprint' => $target_sprint,
						'parent_story' => $parent_post->post_name,
						'task_status' => 'todo',
						'task_assignee' => ''
					);

					$return_code = add_post_meta($new_task_id, '_agilepress_task_data', $new_post_meta);

					break;

				case 'attachment':
					$passed_id = sanitize_key($_POST["post_id"]);
					agilepress_save_attachment_meta_box($passed_id, $_POST["agilepress_attachment"]);

					break;

				case 'settings':
					/*
					$passed_product = sanitize_text_field($_POST["passed_product"]);
					$passed_status = sanitize_text_field($_POST["passed_status"]);
					if (isset($_POST["passed_sprint"])) {
						$passed_sprint = sanitize_text_field($_POST["passed_sprint"]);
					} else {
						$passed_sprint = '';
					}
					*/
					switch ($post_type) {
						case 'agilepress-stories':
							$passed_id = sanitize_key($_POST["post_id"]);
							agilepress_save_story_meta_box($passed_id);
						/*
							$passed_epic = sanitize_text_field($_POST["passed_epic"]);

							$db_meta = get_post_meta($post_id, '_agilepress_story_data', true);
							if (((isset($db_meta['product'])) && ($db_meta['product'] != $passed_product))
								|| ((isset($db_meta['story_status'])) && ($db_meta['story_status'] != $passed_product))
								|| ((isset($db_meta['story_sprint'])) && ($db_meta['story_sprint'] != $passed_sprint))
								|| ((isset($db_meta['parent_epic'])) && ($db_meta['parent_epic'] != $passed_epci))) {

									$db_meta['product'] = $passed_product;
									$db_meta['story_status'] = $passed_status;
									$db_meta['story_sprint'] = $passed_sprint;
									$db_meta['parent_epic'] = $passed_epic;

									$return_code = update_post_meta($post_id, '_agilepress_story_data', $db_meta);
							}
							*/
							break;

						default:
							$passed_id = sanitize_key($_POST["post_id"]);
							agilepress_save_task_meta_box($passed_id);
							/*
							if (isset($_POST["passed_priority"])) {
								$passed_priority = sanitize_text_field($_POST["passed_priority"]);
							} else {
								$passed_priority = '';
							}

							$db_meta = get_post_meta($post_id, '_agilepress_task_data', true);
							if (((isset($db_meta['product'])) && ($db_meta['product'] != $passed_product))
								|| ((isset($db_meta['task_status'])) && ($db_meta['task_status'] != $passed_product))
								|| ((isset($db_meta['sprint'])) && ($db_meta['sprint'] != $passed_sprint))
								|| ((isset($db_meta['task_priority'])) && ($db_meta['task_priority'] != $passed_priority))) {

									$db_meta['product'] = $passed_product;
									$db_meta['task_status'] = $passed_status;
									$db_meta['sprint'] = $passed_sprint;
									$db_meta['task_priority'] = $passed_priority;

									$return_code = update_post_meta($post_id, '_agilepress_task_data', $db_meta);
							}
							*/
							break;
					}

					break;

				case 'remove':
					if (isset($_POST["passed_checkbox"])) {
						$passed_checkbox = sanitize_text_field($_POST["passed_checkbox"]);
						if ($_POST["passed_checkbox"] == 'remove') {
							$return_code = wp_delete_post($passed_id);
						}
					}
					break;

				default:
					break;
			}

			wp_safe_redirect($passed_url);

		}

	}
}
add_action( 'admin_post_nopriv_modal_response', __NAMESPACE__ . '\\do_modal_response' );
add_action( 'admin_post_modal_response', __NAMESPACE__ . '\\do_modal_response' );


function do_report_response() {

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	    $selected_product = sanitize_text_field($_POST["selected-product"]);
		$url = $_SERVER["REQUEST_URI"] . '/wp-admin/admin.php?page=scrum_reports_menu&selected-product=' . $selected_product;
	} else {
		$url = $_SERVER["REQUEST_URI"] . '/wp-admin/admin.php?page=scrum_reports_menu';
	}

	wp_safe_redirect($url);
}

add_action( 'admin_post_nopriv_report_response', __NAMESPACE__ . '\\do_report_response' );
add_action( 'admin_post_report_response', __NAMESPACE__ . '\\do_report_response' );


/**
 * Display a custom taxonomy dropdown in admin
 * @author Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
add_action('restrict_manage_posts', __NAMESPACE__ . '\\filter_post_type_by_taxonomy', 10, 2);
function filter_post_type_by_taxonomy($post_type, $which) {

	if ('agilepress-tasks' == $post_type) {
		create_dropdown_list('task-products');

		create_dropdown_list('task-sprints');
	}

	if ('agilepress-sprints' == $post_type) {
		create_dropdown_list('sprint-products');
	}

	if ('agilepress-stories' == $post_type) {
		create_dropdown_list('story-products');
	}

}


function create_dropdown_list($taxonomy = null) {
	$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
	$info_taxonomy = get_taxonomy($taxonomy);
	wp_dropdown_categories(array(
		'show_option_all' => __("Show All {$info_taxonomy->label}"),
		'taxonomy'        => $taxonomy,
		'name'            => $taxonomy,
		'orderby'         => 'name',
		'selected'        => $selected,
		'show_count'      => true,
		'hide_empty'      => true,
		'value_field'	  => 'slug',
	));
}


function update_edit_form() {
    echo ' enctype="multipart/form-data"';
}
add_action('post_edit_form_tag', __NAMESPACE__ . '\\update_edit_form');


function add_button($board_type = null, $product = null, $sprint = null) {
	$url = get_permalink();

	$button = '<br />';
	$button .= '<div>';
	$button .= '  <button class="w3-btn" value="empty" name="add-new" onclick="addButtonOpen(&#39;' . $board_type . '&#39;)">Add New Item</button><br />';
	$button .= '  <div id="ap-add-item" class="w3-modal">';
	$button .= '    <div class="w3-modal-content w3-card-4 w3-animate-top w3-leftbar w3-border-black">';
	$button .= '      <div class="w3-container">';
	$button .= '        <span id="ap-add-item-close" onclick="addButtonClose(&#39;' . $board_type . '&#39;)" class="w3-button w3-display-topright">&times;</span>';
	$button .= '        <div class="w3-container">';
	$button .= '          <div class="w3-panel w3-gray">';
	$button .= '            <p><h2 id="modalWindowHeader">Add an Item</h2></p>';
	$button .= '          </div>';
	$button .= '          <div>';
	$button .= '            <p class="so-noted">Add an Item</p>';
	$button .= '            <form action="' . admin_url('admin-post.php') . '" method="POST">';
	$button .= '              <input type="hidden" name="action" value="modal_response">';
	$button .= '              <input type="hidden" name="add-new-task" value="add-new-task">';
	$button .= '              <input type="hidden" name="current_product" value="' . $product . '">';
	$button .= '              <input type="hidden" name="current_sprint" value="' . $sprint . '">';
	$button .= '              <input type="hidden" name="board_type" value="' . $board_type . '">';
	if ('backlog' == $board_type) {
		$button .= '              <p><label for="post_name">Story Name</label>';
	} else {
		$button .= '              <p><label for="post_name">Task Name</label>';
	}
	$button .= '                <input class="w3-input" type="text" name="post_name" value="" autofocus></p>';
	if ('backlog' == $board_type) {
		$button .= '              <p><label for="post_excerpt">Story Description</label>';
	} else {
		$button .= '              <p><label for="post_excerpt">Task Description</label>';
	}
	$button .= '                <input class="w3-input" type="textarea" name="post_excerpt" value=""></p>';
	$button .= '              <br /><br />';
	$button .= '              <input type="submit" name="submit" value="Submit Action">';
	$button .= '              <br /><br />';
	$button .= '              <input type="hidden" name="current_url" value="' . esc_url($url) . '">';
	$button .= '            </form>';
	$button .= '          </div>';
	$button .= '        </div>';
	$button .= '      </div>';
	$button .= '    </div>';
	$button .= '';
	$button .= '  </div>';
	$button .= '</div>';

	// print_r($button);
	// die();

	return $button;
}


function excerpt_gettext($translation, $original, $domain)
{
	$post_type = get_post_type(get_the_ID());

	$rename_excerpt = null;
	$rename_description = null;

	if ($post_type) {
		switch ($post_type) {
			case 'agilepress-products':
				$rename_excerpt = 'Product Description';
				$rename_description = 'Use this box to enter a brief description of your product.';
				break;

			case 'agilepress-sprints':
				$rename_excerpt = 'Sprint Description';
				$rename_description = 'Use this box to enter a brief description of your sprint.';
				break;

			case 'agilepress-tasks':
				$rename_excerpt = 'Task Description';
				$rename_description = 'Use this box to enter the text of your task note.';
				break;

			case 'agilepress-stories':
				$rename_excerpt = 'Story Description';
				$rename_description = 'Use this box to enter the text of your story note.';
				break;

			default:
				$rename_excerpt = null;
				$rename_description = null;
				break;
		}
	}

    if (('Excerpt' == $original) && (null != $rename_excerpt)) {
        return $rename_excerpt;
    } else {
        $pos = strpos($original, 'Excerpts are optional hand-crafted summaries of your');
        if (($pos !== false) && (null != $rename_description)) {
            return $rename_description;
        }
    }
    return $translation;
}
add_filter('gettext', __NAMESPACE__ . '\\excerpt_gettext', 10, 3);


function multisite_new_blog_added($blog_id, $user_id, $domain, $path, $site_id, $meta) {
	$myActivator = new AgilePress_ActDeact;
	switch_to_blog($blog_id);
	$myActivator->add_options($blog_id);
	$myActivator->add_custom_tables($blog_id);
	restore_current_blog();

}
add_action('wpmu_new_blog', 'multisite_new_blog_added', 10, 6);
