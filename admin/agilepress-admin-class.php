<?php
/**
 * The plugin's backend (Admin panel) support class.
 *
 * @link       https://agilepress.io
 * @since      0.0.0
 *
 * @package    AgilePress
 * @subpackage AgilePress\admin
 */
namespace vinlandmedia\agilepress;

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

/**
 * This class is used for smaller, general functions relating to the Admin side
 * (back-end) of the plugin's functions.
 *
 * @author Vinland Media, LLC.
 * @package    AgilePress
 * @subpackage AgilePress\admin
 */
class AgilePress_Admin {

	/**
	 * This method is used to define the AgilePress menu that appears on the
	 * left-hand side of the Wordpress Admin panel.  It houses the four custom
	 * post type (CPT) menus (generated elsewhere) and the "Getting Started,"
	 * "Reports," and "Help" pages (referenced below).
	 *
	 * @param null
	 * @return 0 (not used)
	 *
	 * @uses \vinlandmedia\agilepress\AgilePress_Admin::custom_list_cols()
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_menu_page/
	 * @see https://developer.wordpress.org/reference/functions/add_submenu_page/
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function add_leftside_admin_menu() {

		add_menu_page(
			'AgilePress',
			'AgilePress',
			'view_boards',
			'agilepress-manager-menu',
			'', // Callback, leave empty
			'dashicons-media-spreadsheet',
			'25' // Position
		);


		add_submenu_page('agilepress-manager-menu', 'AgilePress Startup',
			'Get Started', 'view_boards', 'ap_get_started_menu', __NAMESPACE__ . '\\agilepress_get_started_page');
		/*
		add_submenu_page('agilepress-manager-menu', 'Scrum Boards Reports',
			'Reports', 'view_boards', 'ap_reports_menu', __NAMESPACE__ . '\\agilepress_reports_page');
			*/
		add_submenu_page('agilepress-manager-menu', 'AgilePress Help',
			'Get Help', 'view_boards', 'ap_help_menu', __NAMESPACE__ . '\\agilepress_help_page');

		// flush rewrite cache
	    flush_rewrite_rules();

		return 0;
	}

	/**
	 * In order for custom fields to be shown in the WP Admin post-list screens
	 * for the custom post types, the fields need to be defined.  This method
	 * lists the custom fields to be used for all of the CPTs used by AgilePress.
	 *
	 * @param string $type  Custom Post Type
	 * @return string $new_columns  All columns to be used on the list page for the type
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function custom_list_cols($type) {
		switch ($type) {
			case 'products':
				$new_columns['cb'] = '<input type="checkbox" />';

			    //$new_columns['id'] = __('ID');
			    $new_columns['title'] = _x('Product Name', 'column name');
			    //$new_columns['images'] = __('Images');
			    $new_columns['author'] = _x('Created by', 'column name');

				$new_columns['product_shortcode'] = _x('Backlog Shortcode', 'column name');
				$new_columns['kanban_shortcode'] = _x('Kanban Shortcode', 'column name');

			    $new_columns['categories'] = __('Categories');
			    $new_columns['tags'] = __('Tags');

			    $new_columns['date'] = _x('Date', 'column name');
				break;

			case 'sprints':
				$new_columns['cb'] = '<input type="checkbox" />';

			    //$new_columns['id'] = __('ID');
			    $new_columns['title'] = _x('Sprint Name', 'column name');
			    //$new_columns['images'] = __('Images');
			    $new_columns['author'] = _x('Created by', 'column name');

				$new_columns['product'] = __('Product');
				$new_columns['sprint_shortcode'] = _x('Shortcode', 'column name');

			    $new_columns['categories'] = __('Categories');
			    $new_columns['tags'] = __('Tags');

			    $new_columns['date'] = _x('Date', 'column name');
				break;

			case 'tasks':
				$new_columns['cb'] = '<input type="checkbox" />';

			    //$new_columns['id'] = __('ID');
			    $new_columns['title'] = _x('Task Name', 'column name');
			    //$new_columns['images'] = __('Images');
			    $new_columns['author'] = _x('Created by', 'column name');

				$new_columns['product'] = __('Product');
				$new_columns['sprint'] = __('Sprint');

			    $new_columns['categories'] = __('Categories');
			    $new_columns['tags'] = __('Tags');

			    $new_columns['date'] = _x('Date', 'column name');
				break;

			case 'stories':
				$new_columns['cb'] = '<input type="checkbox" />';

			    //$new_columns['id'] = __('ID');
			    $new_columns['title'] = _x('Story Name', 'column name');
			    //$new_columns['images'] = __('Images');
			    $new_columns['author'] = _x('Created by', 'column name');

				$new_columns['product'] = __('Product');
				$new_columns['has_task'] = __('Has Task(s)');

			    $new_columns['categories'] = __('Categories');
			    $new_columns['tags'] = __('Tags');

			    $new_columns['date'] = _x('Date', 'column name');
				break;

			default:
				$new_columns = -1;
				break;
		}

		return $new_columns;
	}

}
