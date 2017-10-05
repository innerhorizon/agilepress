<?php
/**
 * AgilePress
 *
 */
namespace vinlandmedia\agilepress;

/**
 * This class is used for smaller, general functions relating to the Admin side
 * (back-end) of the plugin's functions.
 *
 * @author Vinland Media, LLC.
 * @package    AgilePress
 * @subpackage AgilePress\admin
 */
class AgilePress_ActDeact {

    /**
	 * This method...
	 *
	 * @param null
	 * @return 0 (not used)
	 *
	 * @uses \vinlandmedia\agilepress\AgilePress_Admin::custom_list_cols()
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function add_options() {

        // build options array
    	$agilepress_options_arr = array(
            'agilepress_registered_email' => '',
    		'agilepress_product_key' => '',
    		// colors
    		'agilepress_epic_color' => 'blue',
    		'agilepress_story_color' => 'green',
    		'agilepress_story2sprint_color' => 'yellow',
    		'agilepress_storydone_color' => 'pink',
    		'agilepress_sprintblog_color' => 'yellow',
    		'agilepress_todo_color' => 'blue',
    		'agilepress_nprogress_color' => 'yellow',
    		'agilepress_ntesting_color' => 'pink',
    		'agilepress_done_color' => 'green',
    		// fonts
    		'agilepress_note_title_font' => 'permanent-marker',
    		'agilepress_note_body_font' => 'indie-flower',
    		// option
    		'agilepress_note_crinkle' => 'no',
    		'agilepress_guest_comments' => 'no',
    		// headings
    		'agilepress_epic_header' => 'Epics',
    		'agilepress_epic_text' => 'These are meta-stories, large enough that they should be broken into seperate PBIs.',
    		'agilepress_story_header' => 'Stories',
    		'agilepress_story_text' => 'These are stories that we plan to do but have not yet added to a sprint for active work.',
    		'agilepress_story2sprint_header' => 'Send to Sprint',
    		'agilepress_story2sprint_text' => 'Drop a story in this column to convert it to a task and send to the target/active sprint.',
    		'agilepress_storydone_header' => 'Completed',
    		'agilepress_storydone_text' => 'Stories and Epics that are completed, accepted, and deployed.',
    		'agilepress_sprintblog_header' => 'Sprint Backlog Story',
    		'agilepress_sprintblog_text' => 'Stories (the "what") to be completed in this sprint. Items here cannot be dragged to other columns.',
    		'agilepress_todo_header' => 'To-Do',
    		'agilepress_todo_text' => '(this is your To-Do List)',
    		'agilepress_nprogress_header' => 'In Progress',
    		'agilepress_nprogress_text' => '(the Tasks upon which you are currently working)',
    		'agilepress_ntesting_header' => 'In Testing/Review',
    		'agilepress_ntesting_text' => '(the Tasks that you feel are done but need some form of confirmation before marking as completed)',
    		'agilepress_done_header' => 'Done',
    		'agilepress_done_text' => '(whew! these Tasks are in the can!)',

        );

    	// add the options using the array
        update_option('agilepress_options', $agilepress_options_arr);

    }

    /**
	 * This method...
	 *
	 * @param null
	 * @return 0 (not used)
	 *
	 * @uses \vinlandmedia\agilepress\AgilePress_Admin::custom_list_cols()
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function remove_options() {

    }

	/**
	 * This method...
	 *
	 * @uses \vinlandmedia\agilepress\AgilePress_Admin::custom_list_cols()
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function delete_options() {
		unregister_post_type('agilepress-products');
		unregister_post_type('agilepress-sprints');
		unregister_post_type('agilepress-tasks');
		unregister_post_type('agilepress-stories');

		delete_option('agilepress_registered_email');
		delete_option('agilepress_product_key');
		// colors
		delete_option('agilepress_epic_color');
		delete_option('agilepress_story_color');
		delete_option('agilepress_story2sprint_color');
		delete_option('agilepress_storydone_color');
		delete_option('agilepress_sprintblog_color');
		delete_option('agilepress_todo_color');
		delete_option('agilepress_nprogress_color');
		delete_option('agilepress_ntesting_color');
		delete_option('agilepress_done_color');
		// fonts
		delete_option('agilepress_note_title_font');
		delete_option('agilepress_note_body_font');
		// option
		delete_option('agilepress_note_crinkle');
		delete_option('agilepress_guest_comments');
		// column headings
		delete_option('agilepress_epic_header');
		delete_option('agilepress_epic_text');
		delete_option('agilepress_story_header');
		delete_option('agilepress_story_text');
		delete_option('agilepress_story2sprint_header');
		delete_option('agilepress_story2sprint_text');
		delete_option('agilepress_storydone_header');
		delete_option('agilepress_storydone_text');
		delete_option('agilepress_sprintblog_header');
		delete_option('agilepress_sprintblog_text');
		delete_option('agilepress_todo_header');
		delete_option('agilepress_todo_text');
		delete_option('agilepress_nprogress_header');
		delete_option('agilepress_nprogress_text');
		delete_option('agilepress_ntesting_header');
		delete_option('agilepress_ntesting_text');
		delete_option('agilepress_done_header');
		delete_option('agilepress_done_text');

		remove_role('agilepress_admin');
		remove_role('agilepress_developer');
		remove_role('agilepress_user');
		remove_role('agilepress_viewer');

		// flush rewrite cache
		flush_rewrite_rules();

    }

    /**
	 * This method...
	 *
	 * @param null
	 * @return 0 (not used)
	 *
	 * @uses \vinlandmedia\agilepress\AgilePress_Admin::custom_list_cols()
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function add_custom_tables() {
	    //require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

    	$lookup_type_table_name = $wpdb->prefix . "agilepress_lookup_types";

    	$sql = "CREATE TABLE IF NOT EXISTS " . $lookup_type_table_name . " (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    		lookup_type	VARCHAR(16),
            created DATETIME DEFAULT NULL,
            modified DATETIME DEFAULT NULL,
    		INDEX ndx_lookup_type (lookup_type)
        ) ENGINE=INNODB " . $charset_collate;

        dbDelta($sql);

    	$wpdb->insert(
            $lookup_type_table_name,
            array(
                'lookup_type' => 'story-status',
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$story_status_id = $wpdb->get_var(
    		"select id " .
    		"from " . $lookup_type_table_name . " " .
    		"where lookup_type = 'story-status' "
    	);

    	$wpdb->insert(
            $lookup_type_table_name,
            array(
                'lookup_type' => 'task-status',
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$task_status_id = $wpdb->get_var(
    		"select id " .
    		"from " . $lookup_type_table_name . " " .
    		"where lookup_type = 'task-status' "
    	);

    	$wpdb->insert(
            $lookup_type_table_name,
            array(
                'lookup_type' => 'product-phase',
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$product_phase_id = $wpdb->get_var(
    		"select id " .
    		"from " . $lookup_type_table_name . " " .
    		"where lookup_type = 'product-phase' "
    	);

    	$wpdb->insert(
            $lookup_type_table_name,
            array(
                'lookup_type' => 'sprint-status',
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$sprint_status_id = $wpdb->get_var(
    		"select id " .
    		"from " . $lookup_type_table_name . " " .
    		"where lookup_type = 'sprint-status' "
    	);


    	$lookup_value_table_name = $wpdb->prefix . "agilepress_lookup_values";

    	$sql = "CREATE TABLE IF NOT EXISTS " . $lookup_value_table_name . " (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    		lookup_key VARCHAR(32),
    		lookup_value VARCHAR(64),
    		lookup_type_id INT UNSIGNED,
            created DATETIME DEFAULT NULL,
            modified DATETIME DEFAULT NULL,
    		INDEX ndx_lookup_key (lookup_key),
    		INDEX ndx_lookup_type_id (lookup_type_id),
    		CONSTRAINT FOREIGN KEY fk_lookup_type_id (lookup_type_id) REFERENCES " . $lookup_type_table_name . " (id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=INNODB " . $charset_collate;

        dbDelta($sql);

    	// Story statuses
    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'isepic',
                'lookup_value' => 'Epic',
                'lookup_type_id' => $story_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'isstory',
                'lookup_value' => 'Story',
                'lookup_type_id' => $story_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'sendtosprint',
                'lookup_value' => 'Send to Sprint',
                'lookup_type_id' => $story_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'iscomplete',
                'lookup_value' => 'Completed',
                'lookup_type_id' => $story_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	// task statuses
    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'sendtosprint',
                'lookup_value' => 'Sprint Backlog Story',
                'lookup_type_id' => $task_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'todo',
                'lookup_value' => 'To-Do',
                'lookup_type_id' => $task_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'inprogress',
                'lookup_value' => 'In Progress',
                'lookup_type_id' => $task_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'intesting',
                'lookup_value' => 'In Testing',
                'lookup_type_id' => $task_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'done',
                'lookup_value' => 'Done',
                'lookup_type_id' => $task_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

		$wpdb->insert(
			$lookup_value_table_name,
			array(
				'lookup_key' => 'archived',
				'lookup_value' => 'Archived',
				'lookup_type_id' => $task_status_id,
				'created' => current_time('timestamp'),
				'modified' => current_time('timestamp')
			)
		);

    	// product phases
    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'pre-alpha',
                'lookup_value' => 'Pre-Alpha',
                'lookup_type_id' => $product_phase_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'alpha',
                'lookup_value' => 'Alpha',
                'lookup_type_id' => $product_phase_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'closed-beta',
                'lookup_value' => 'Closed Beta',
                'lookup_type_id' => $product_phase_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'open-beta',
                'lookup_value' => 'Open Beta',
                'lookup_type_id' => $product_phase_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'release-candidate',
                'lookup_value' => 'Release Candidate',
                'lookup_type_id' => $product_phase_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'release-to-web',
                'lookup_value' => 'Release to Web/MFG',
                'lookup_type_id' => $product_phase_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'general-availability',
                'lookup_value' => 'General Availability',
                'lookup_type_id' => $product_phase_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	// sprint statuses
    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'planned',
                'lookup_value' => 'Planned',
                'lookup_type_id' => $sprint_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
            $lookup_value_table_name,
            array(
                'lookup_key' => 'in-progress',
                'lookup_value' => 'In Progress',
                'lookup_type_id' => $sprint_status_id,
                'created' => current_time('timestamp'),
                'modified' => current_time('timestamp')
            )
        );

    	$wpdb->insert(
    		$lookup_value_table_name,
    		array(
    			'lookup_key' => 'completed',
    			'lookup_value' => 'Completed',
    			'lookup_type_id' => $sprint_status_id,
    			'created' => current_time('timestamp'),
    			'modified' => current_time('timestamp')
    		)
    	);

    }

	/**
	 * This method...
	 *
	 * @param null
	 * @return 0 (not used)
	 *
	 * @uses \vinlandmedia\agilepress\AgilePress_Admin::custom_list_cols()
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function remove_custom_tables() {
	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$lookup_value_table_name = $wpdb->prefix . "agilepress_lookup_values";

		$wpdb->query(
			"TRUNCATE TABLE " . $lookup_value_table_name
		);

		$wpdb->query(
			"DROP TABLE IF EXISTS " . $lookup_value_table_name
		);

		$lookup_type_table_name = $wpdb->prefix . "agilepress_lookup_types";

		$wpdb->query(
			"TRUNCATE TABLE " . $lookup_type_table_name
		);

		$wpdb->query(
			"DROP TABLE IF EXISTS " . $lookup_type_table_name
		);

	}

}
