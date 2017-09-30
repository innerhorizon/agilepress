<?php

namespace vinlandmedia\agilepress;

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://agilepress.io
 * @since      1.0.0
 *
 * @package    AgilePress
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . '/admin/agilepress-act-deact-class.php';
global $wpdb;

$myDeactivator = new AgilePress_ActDeact();

$myDeactivator->delete_options();
$myDeactivator->remove_custom_tables();

if (is_multisite()) {
	$deactivated = array();

	$my_blogs = $wpdb->get_results('SELECT * FROM ' . $wpdb->blogs);

	foreach ($my_blogs as $my_blog) {
		switch_to_blog($my_blog->blog_id);
		$myDeactivator->delete_options();
		$myDeactivator->remove_custom_tables();
		$deactivated[] = $my_blog->blog_id;
		restore_current_blog();
	}

} else {
	$myDeactivator->delete_options();
	$myDeactivator->remove_custom_tables();
}
