<?php
/**
 * Handles the nuts and bolts of the various metaboxes.
 *
 * @link       https://agilepress.io
 * @since      0.0.0
 *
 */
namespace vinlandmedia\agilepress;

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

/**
 * This class is used to create the metaboxes for all of the custom post types
 * (CPT) used by AgilePress.
 *
 * @author Vinland Media, LLC.
 *
 * @package    AgilePress
 * @subpackage AgilePress\admin
 */
class AgilePress_Meta {

	/**
	 * This method constructs the metabox for the Story CPT.
	 *
	 * @param array $agilepress_meta  Custom post metadata
	 * @param object $products  Query results of Products search
	 * @param object $sprints  Query results of Sprints search
	 * @param boolean $use_shortcodes  Whether or not to show shortcode help
	 * @return string $story_metabox  Formatted (HTML/CSS/JavaScript) metabox panel
	 *
	 * @global $wpdb
	 *
	 * @uses \vinlandmedia\agilepress\AgilePress_Meta::meta_select_box()
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function build_story_meta_box($agilepress_meta, $products, $sprints, $use_shortcodes) {
		global $wpdb;

		$story_post_id = get_the_ID();
		$story_post_name = get_post_field('post_name', $story_post_id);

		$agilepress_product = (!empty($agilepress_meta['product'])) ? $agilepress_meta['product'] : '';
		$agilepress_status = (!empty($agilepress_meta['story_status'])) ? $agilepress_meta['story_status'] : '';
		$agilepress_sprint = (!empty($agilepress_meta['story_sprint'])) ? $agilepress_meta['story_sprint'] : '';
		$agilepress_parent_epic = (!empty($agilepress_meta['parent_epic'])) ? $agilepress_meta['parent_epic'] : '';
		$agilepress_priority = ( ! empty( $agilepress_meta['story_priority'] ) ) ? $agilepress_meta['story_priority'] : '';
		$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
		$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';

		//nonce field for security
		wp_nonce_field('story-meta-box-save', 'agilepress');

		// display meta box form
	    $story_metabox = '<table>';
		$story_metabox .= '<tr>';

		// start Product select box
		$story_metabox .= $this->meta_select_box(
			'Associated Product',
			'agilepress_story[product]',
			$agilepress_product,
			$products,
			null);
		// end select box

		$story_metabox .= '</tr><tr>';

		// start Status select box
		$story_status = array(
			['post_name' => 'isepic',
			'post_title' => 'Is Epic'],
			['post_name' => 'isstory',
			'post_title' => 'Is Story'],
			['post_name' => 'sendtosprint',
			'post_title' => 'Send to Sprint'],
			['post_name' => 'iscomplete',
			'post_title' => 'Completed'],
			['post_name' => 'archived',
			 'post_title' => 'Archived'],
		);

		$story_metabox .= $this->meta_select_box(
			'Story Status',
			'agilepress_story[story_status]',
			$agilepress_status,
			null,
			$story_status);
		// end select box

		$story_metabox .= '</tr><tr>';

		// start Parent Epic select box
		$epics = $wpdb->get_results(
			"select DISTINCT p.ID, p.post_title, p.post_name from " . $wpdb->posts . " p, " .
			$wpdb->postmeta . " m " . "where p.post_type = 'agilepress-stories' and p.post_status = 'publish' " .
			"and m.post_ID = p.ID and m.meta_key = '_agilepress_story_data' and m.meta_value like '%epic%'" .
			"order by post_name");

		$story_metabox .= $this->meta_select_box(
			'Parent Epic',
			'agilepress_story[parent_epic]',
			$agilepress_parent_epic,
			$epics,
			null);
		// end select box

		$story_metabox .= '</tr><tr>';

		// start Sprint select box
		$story_metabox .= $this->meta_select_box(
			'Associated Sprint',
			'agilepress_story[story_sprint]',
			$agilepress_sprint,
			$sprints,
			null);
		// end select box

		$story_metabox .= '</tr>';

		// start Priority select box
		$story_priority = array(
			['post_name' => '5',
			 'post_title' => 'Critical'],
			['post_name' => '4',
			 'post_title' => 'Major'],
			['post_name' => '3',
			 'post_title' => 'Normal'],
			['post_name' => '2',
			 'post_title' => 'Minor'],
			['post_name' => '1',
			 'post_title' => 'Trivial'],
		);

		$story_metabox .= $this->meta_select_box(
			'Story Priority',
			'agilepress_story[story_priority]',
			$agilepress_priority,
			null,
			$story_priority);
		// end select box

		$story_metabox .= '<tr>';
		$story_metabox .= '<td>' .__('Target Date', 'agilepress').':</td><td><input type="date" name="agilepress_story[target_date]" value="'.esc_attr( $agilepress_target_date ).'" size="24" ' . $this->is_disabled() . '></td>';
		$story_metabox .= '</tr><tr>';
		$story_metabox .= '<td>' .__('Completed Date', 'agilepress').':</td><td><input type="date" name="agilepress_story[completed_date]" value="'.esc_attr( $agilepress_completed_date ).'" size="24" ' . $this->is_disabled() . '></td>';
		$story_metabox .= '</tr>';

		$story_metabox .= '</tr>';

	    //display the meta box shortcode legend section
		if ($use_shortcodes) {
			$story_metabox .= '<tr><td colspan="2"><hr></td></tr>';
		    $story_metabox .= '<tr><td colspan="2"><strong>' .__( 'Shortcode Legend', 'agilepress' ).'</strong></td></tr>';
			$story_metabox .= '<tr><td colspan="2"><em>' .__( '(These shortcodes all you to show AgilePress datapoints in posts and pages as needed.)', 'agilepress' ).'</em></td></tr>';
			$story_metabox .= '<tr><td colspan="2"><hr /></td></tr>';
		    $story_metabox .= '<tr><td>' .__( 'Product', 'agilepress' ) .':</td><td>[agilepress-data type=story value=' . esc_html($story_post_name) . ' show=product]</td></tr>';
		    $story_metabox .= '<tr><td>' .__( 'Status', 'agilepress' ) .':</td><td>[agilepress-data type=story value=' . esc_html($story_post_name) . ' show=story_status]</td></tr>';
		    $story_metabox .= '<tr><td>' .__( 'Parent Epic', 'agilepress' ) .':</td><td>[agilepress-data type=story value=' . esc_html($story_post_name) . ' show=parent_epic]</td></tr>';
		    $story_metabox .= '<tr><td>' .__( 'Associated Sprint', 'agilepress' ) .':</td><td>[agilepress-data type=story value=' . esc_html($story_post_name) . ' show=story_sprint]</td></tr>';
		}
	    $story_metabox .= '</table>';

		return $story_metabox;
	}

	/**
	 * This method constructs the metabox for the Product CPT.
	 *
	 * @param array $agilepress_meta  Custom post metadata
	 * @return string $product_metabox  Formatted (HTML/CSS/JavaScript) metabox panel
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function build_product_meta_box($agilepress_meta) {
		$product_post_id = get_the_ID();
		$product_post_name = get_post_field('post_name', $product_post_id);

		$agilepress_version = (!empty($agilepress_meta['version'])) ? $agilepress_meta['version'] : '';
		$agilepress_target = (!empty($agilepress_meta['target'])) ? $agilepress_meta['target'] : '';
		$agilepress_phase = (!empty($agilepress_meta['phase'])) ? $agilepress_meta['phase'] : '';

		//nonce field for security
		wp_nonce_field( 'product-meta-box-save', 'agilepress' );

	    // meta box form
	    $product_metabox = '<table>';
	    $product_metabox .= '<tr>';
	    $product_metabox .= '<td>' .__('Current Version', 'agilepress').':</td><td><input type="text" name="agilepress_product[version]" value="'.esc_attr( $agilepress_version ).'" size="24" ' . $this->is_disabled() . '></td>';
	    $product_metabox .= '</tr><tr>';
	    $product_metabox .= '<td>' .__('Target Version', 'agilepress').':</td><td><input type="text" name="agilepress_product[target]" value="'.esc_attr( $agilepress_target ).'" size="24" ' . $this->is_disabled() . '></td>';
	    $product_metabox .= '</tr>';

		// start phase select box
		$product_development_phase = array(
			['post_name' => 'pre-alpha',
			'post_title' => 'Pre-Alpha'],
			['post_name' => 'alpha',
			'post_title' => 'Alpha'],
			['post_name' => 'closed-beta',
			'post_title' => 'Closed Beta'],
			['post_name' => 'open-beta',
			'post_title' => 'Open Beta'],
			['post_name' => 'release-candidate',
			'post_title' => 'Release Candidate'],
			['post_name' => 'release-to-web',
			'post_title' => 'Release to Web/MFG'],
			['post_name' => 'general-availability',
			'post_title' => 'General Availability'],
		);

		$product_metabox .= $this->meta_select_box(
			'In-Progress Version Status',
			'agilepress_product[phase]',
			$agilepress_phase,
			null,
			$product_development_phase);
		// end select box

	    //display the meta box shortcode legend section
	    $product_metabox .= '<tr><td colspan="2"><hr></td></tr>';
	    $product_metabox .= '<tr><td colspan="2"><strong>' .__( 'Shortcode Legend', 'agilepress' ).'</strong></td></tr>';
		$product_metabox .= '<tr><td colspan="2"><em>' .__( '(These shortcodes all you to show AgilePress datapoints in posts and pages as needed.)', 'agilepress' ).'</em></td></tr>';
		$product_metabox .= '<tr><td colspan="2"><hr /></td></tr>';
	    $product_metabox .= '<tr><td>' .__( 'Version', 'agilepress' ).':</td><td>[agilepress-data type=product value=' . esc_html($product_post_name) . ' show=version]</td></tr>';
		$product_metabox .= '<tr><td>' .__( 'Target', 'agilepress' ).':</td><td>[agilepress-data type=product value=' . esc_html($product_post_name) . ' show=target]</td></tr>';
		$product_metabox .= '<tr><td>' .__( 'Phase', 'agilepress' ).':</td><td>[agilepress-data type=product value=' . esc_html($product_post_name) . ' show=phase]</td></tr>';
	    $product_metabox .= '</table>';

		return $product_metabox;
	}

	/**
	 * This method constructs the metabox for the Task CPT.
	 *
	 * @param array $agilepress_meta  Custom post metadata
	 * @param object $products  Query results of Products search
	 * @param object $sprints  Query results of Sprints search
	 * @param object $stories  Query results of Stories search
	 * @param boolean $use_shortcodes  Whether or not to show shortcode help
	 * @return string $task_metabox  Formatted (HTML/CSS/JavaScript) metabox panel
	 *
	 * @global $wpdb
	 *
	 * @uses \vinlandmedia\agilepress\AgilePress_Meta::meta_select_box()
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function build_task_meta_box($agilepress_meta, $products, $sprints, $stories, $use_shortcodes) {
		global $wpdb;

		$task_post_id = get_the_ID();
		$task_post_name = get_post_field('post_name', $task_post_id);

		$agilepress_product = ( ! empty( $agilepress_meta['product'] ) ) ? $agilepress_meta['product'] : '';
	    $agilepress_sprint = ( ! empty( $agilepress_meta['sprint'] ) ) ? $agilepress_meta['sprint'] : '';
		$agilepress_task_status = ( ! empty( $agilepress_meta['task_status'] ) ) ? $agilepress_meta['task_status'] : '';
		$agilepress_parent_story = ( ! empty( $agilepress_meta['parent_story'] ) ) ? $agilepress_meta['parent_story'] : '';
		$agilepress_task_assignee = ( ! empty( $agilepress_meta['task_assignee'] ) ) ? $agilepress_meta['task_assignee'] : '';
		$agilepress_priority = ( ! empty( $agilepress_meta['task_priority'] ) ) ? $agilepress_meta['task_priority'] : '';
		$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
		$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';

		//nonce field for security
		wp_nonce_field( 'task-meta-box-save', 'agilepress' );

	    // display meta box form
	    $task_metabox = '<table>';
	    $task_metabox .= '<tr>';

		// start Product select box
		$task_metabox .= $this->meta_select_box(
			'Associated Product',
			'agilepress_task[product]',
			$agilepress_product,
			$products,
			null);
		// end select box

		$task_metabox .= '</tr><tr>';

		// start Sprint select box
		$task_metabox .= $this->meta_select_box(
			'Associated Sprint',
			'agilepress_task[sprint]',
			$agilepress_sprint,
			$sprints,
			null);
		// end select box

		$task_metabox .= '</tr><tr>';

		// start Parent Story select box
		$parent_stories = $wpdb->get_results(
			"select DISTINCT p.ID, p.post_title, p.post_name from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m " .
			"where p.post_type = 'agilepress-stories' and p.post_status = 'publish' " .
			"and m.post_ID = p.ID and m.meta_key = '_agilepress_story_data' and m.meta_value like '%story%'" .
			"order by post_name");

		$task_metabox .= $this->meta_select_box(
			'Parent Story',
			'agilepress_task[parent_story]',
			$agilepress_parent_story,
			$parent_stories,
			null);
		// end select box

	    $task_metabox .= '</tr><tr>';

		// start Status select box
		$task_status = array(
			['post_name' => 'sendtosprint',
			'post_title' => 'Project Backlog Items'],
			['post_name' => 'todo',
			'post_title' => 'To-Do'],
			['post_name' => 'inprogress',
			'post_title' => 'In Progress'],
			['post_name' => 'intesting',
			'post_title' => 'In Testing'],
			['post_name' => 'done',
			'post_title' => 'Done'],
			['post_name' => 'archived',
			 'post_title' => 'Archived'],
		);

		$task_metabox .= $this->meta_select_box(
			'Task Status',
			'agilepress_task[task_status]',
			$agilepress_task_status,
			null,
			$task_status);
		// end select box

	    $task_metabox .= '</tr><tr>';

		$args = array(
			'role__in'     => array(
				'agilepress_admin',
				'agilepress_developer',
			),
		 );
		 $ap_users = get_users($args);
		 $task_metabox .= '<td>' .__('Assignee', 'agilepress').':</td>';
		 $task_metabox .= '<td><select name="agilepress_task[task_assignee]">';
		 foreach($ap_users as $ap_user) {
			 $task_metabox .=  '<option value="' . esc_html($ap_user->user_login) . '" ' . selected($ap_user->user_login, esc_attr($agilepress_task_assignee), false) . '>' . esc_html($ap_user->user_login) . ' (' . esc_html($ap_user->user_email) . ')' .
				 '</option>';
		 }

		 $task_metabox .= '</select></td></tr>';

 		// start Priority select box
 		$task_priority = array(
 			['post_name' => '5',
 			'post_title' => 'Critical'],
 			['post_name' => '4',
 			'post_title' => 'Major'],
 			['post_name' => '3',
 			'post_title' => 'Normal'],
 			['post_name' => '2',
 			'post_title' => 'Minor'],
			['post_name' => '1',
 			'post_title' => 'Trivial'],
 		);

 		$task_metabox .= $this->meta_select_box(
 			'Task Priority',
 			'agilepress_task[task_priority]',
 			$agilepress_priority,
 			null,
 			$task_priority);
 		// end select box

		$task_metabox .= '<tr>';
		$task_metabox .= '<td>' .__('Target Date', 'agilepress').':</td><td><input type="date" name="agilepress_task[target_date]" value="'.esc_attr( $agilepress_target_date ).'" size="24" ' . $this->is_disabled() . '></td>';
		$task_metabox .= '</tr><tr>';
		$task_metabox .= '<td>' .__('Completed Date', 'agilepress').':</td><td><input type="date" name="agilepress_task[completed_date]" value="'.esc_attr( $agilepress_completed_date ).'" size="24" ' . $this->is_disabled() . '></td>';
		$task_metabox .= '</tr>';

	    //display the meta box shortcode legend section
		if ($use_shortcodes) {
		    $task_metabox .= '<tr><td colspan="2"><hr></td></tr>';
		    $task_metabox .= '<tr><td colspan="2"><strong>' .__( 'Shortcode Legend', 'agilepress' ).'</strong></td></tr>';
			$task_metabox .= '<tr><td colspan="2"><em>' .__( '(These shortcodes all you to show AgilePress datapoints in posts and pages as needed.)', 'agilepress' ).'</em></td></tr>';
			$task_metabox .= '<tr><td colspan="2"><hr /></td></tr>';
		    $task_metabox .= '<tr><td>' .__( 'Product', 'agilepress' ) .':</td><td>[agilepress-data type=task value=' . esc_html($task_post_name) . ' show=product]</td></tr>';
			$task_metabox .= '<tr><td>' .__( 'Sprint', 'agilepress' ).':</td><td>[agilepress-data type=task value=' . esc_html($task_post_name) . ' show=sprint]</td></tr>';
			$task_metabox .= '<tr><td>' .__( 'Parent Story', 'agilepress' ).':</td><td>[agilepress-data type=task value=' . esc_html($task_post_name) . ' show=parent_story]</td></tr>';
			$task_metabox .= '<tr><td>' .__( 'Task Status', 'agilepress' ).':</td><td>[agilepress-data type=task value=' . esc_html($task_post_name) . ' show=task_status]</td></tr>';
			$task_metabox .= '<tr><td>' .__( 'Assignee', 'agilepress' ).':</td><td>[agilepress-data type=task value=' . esc_html($task_post_name) . ' show=task_assignee]</td></tr>';
			$task_metabox .= '<tr><td>' .__( 'Task Priority', 'agilepress' ).':</td><td>[agilepress-data type=task value=' . esc_html($task_post_name) . ' show=task_priority]</td></tr>';
		}
	    $task_metabox .= '</table>';

		return $task_metabox;
	}

	/**
	 * This method constructs the metabox for the Sprint CPT.
	 *
	 * @param array $agilepress_meta  Custom post metadata
	 * @param object $products  Query results of Products search
	 * @return string $sprint_metabox  Formatted (HTML/CSS/JavaScript) metabox panel
	 *
	 * @uses \vinlandmedia\agilepress\AgilePress_Meta::meta_select_box()
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_nonce_field/
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function build_sprint_meta_box($agilepress_meta, $products) {
		$sprint_post_id = get_the_ID();
		$sprint_post_name = get_post_field('post_name', $sprint_post_id);

		$agilepress_product = ( ! empty( $agilepress_meta['product'] ) ) ? $agilepress_meta['product'] : '';
	    $agilepress_start_date = ( ! empty( $agilepress_meta['start_date'] ) ) ? $agilepress_meta['start_date'] : '';
		$agilepress_end_date = ( ! empty( $agilepress_meta['end_date'] ) ) ? $agilepress_meta['end_date'] : '';
		$agilepress_status = ( ! empty( $agilepress_meta['status'] ) ) ? $agilepress_meta['status'] : '';
		$agilepress_target = ( ! empty( $agilepress_meta['backlog_target'] ) ) ? $agilepress_meta['backlog_target'] : '';

		//nonce field for security
		wp_nonce_field( 'sprint-meta-box-save', 'agilepress' );

	    // display meta box form
	    $sprint_metabox = '<table>';
	    $sprint_metabox .= '<tr>';

		// start Product select box
		$sprint_metabox .= $this->meta_select_box(
			'Product',
			'agilepress_sprint[product]',
			$agilepress_product,
			$products,
			null);
		// end select box

		$sprint_metabox .= '</tr><tr>';
	    $sprint_metabox .= '<td>' .__('Start Date', 'agilepress').':</td><td><input type="date" name="agilepress_sprint[start_date]" value="'.esc_attr( $agilepress_start_date ).'" size="12" ' . $this->is_disabled() . '></td>';
	    $sprint_metabox .= '</tr><tr>';
	    $sprint_metabox .= '<td>' .__('End Date', 'agilepress').':</td><td><input type="date" name="agilepress_sprint[end_date]" value="'.esc_attr( $agilepress_end_date ).'" size="12" ' . $this->is_disabled() . '></td>';
	    $sprint_metabox .= '</tr><tr>';

		// start Backlog Target select box
		$sprint_status = array(
			['post_name' => 'planned',
			'post_title' => 'Planned'],
			['post_name' => 'in-progress',
			'post_title' => 'In Progress'],
			['post_name' => 'completed',
			'post_title' => 'Completed'],
		);

		$sprint_metabox .= $this->meta_select_box(
			'Sprint Status',
			'agilepress_sprint[status]',
			$agilepress_status,
			null,
			$sprint_status);
		// end select box

		$sprint_metabox .= '</tr><tr>';

		// start Backlog Target select box
		$backlog_target_status = array(
			['post_name' => 'yes',
			'post_title' => 'Yes'],
			['post_name' => 'no',
			'post_title' => 'No']
		);

		$sprint_metabox .= $this->meta_select_box(
			'Backlog Target (Y/N)',
			'agilepress_sprint[backlog_target]',
			$agilepress_target,
			null,
			$backlog_target_status);
		// end select box

		$sprint_metabox .= '</tr>';

	    //display the meta box shortcode legend section
	    $sprint_metabox .= '<tr><td colspan="2"><hr></td></tr>';
	    $sprint_metabox .= '<tr><td colspan="2"><strong>' .__( 'Shortcode Legend', 'agilepress' ).'</strong></td></tr>';
		$sprint_metabox .= '<tr><td colspan="2"><em>' .__( '(These shortcodes all you to show AgilePress datapoints in posts and pages as needed.)', 'agilepress' ).'</em></td></tr>';
		$sprint_metabox .= '<tr><td colspan="2"><hr /></td></tr>';
	    $sprint_metabox .= '<tr><td>' .__( 'Product', 'agilepress' ) .':</td><td>[agilepress-data type=sprint value=' . esc_html($sprint_post_name) . ' show=product]</td></tr>';
	    $sprint_metabox .= '<tr><td>' .__( 'Start Date', 'agilepress' ).':</td><td>[agilepress-data type=sprint value=' . esc_html($sprint_post_name) . ' show=start_date]</td></tr>';
		$sprint_metabox .= '<tr><td>' .__( 'End Date', 'agilepress' ).':</td><td>[agilepress-data type=sprint value=' . esc_html($sprint_post_name) . ' show=end_date]</td></tr>';
		$sprint_metabox .= '<tr><td>' .__( 'Status', 'agilepress' ).':</td><td>[agilepress-data type=sprint value=' . esc_html($sprint_post_name) . ' show=status]</td></tr>';
		$sprint_metabox .= '<tr><td>' .__( 'Backlog Target', 'agilepress' ).':</td><td>[agilepress-data type=sprint value=' . esc_html($sprint_post_name) . ' show=backlog_target]</td></tr>';
	    $sprint_metabox .= '</table>';

		return $sprint_metabox;
	}

	/**
	 * This method constructs the metabox for attachments.
	 *
	 * @param array $agilepress_meta  Custom post metadata
	 * @return string $attachment_metabox  Formatted (HTML/CSS/JavaScript) metabox panel
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	public function build_attachment_meta_box($agilepress_meta, $from_modal = false) {
		//nonce field for security
		wp_nonce_field('attachment-meta-box-save', 'agilepress');

		// meta box form
	    $attachment_metabox = '<table>';

		if (user_can(wp_get_current_user(), 'transition_tasks')) {
			$attachment_metabox .= '<tr>';
			$attachment_metabox .= '<td>' .__('Attachment', 'agilepress').':</td><td><input type="file" name="agilepress_attachment" value="" size="24" ' . '></td>';
		    $attachment_metabox .= '</tr>';
		}

		if ((isset($agilepress_meta) && (!empty($agilepress_meta)))) {
			foreach ($agilepress_meta as $my_attachment) {
				$agilepress_file = (!empty($my_attachment['file'])) ? $my_attachment['file'] : '';
				$agilepress_url = (!empty($my_attachment['url'])) ? $my_attachment['url'] : '';
				$agilepress_type = (!empty($my_attachment['type'])) ? $my_attachment['type'] : '';
				$agilepress_error = (!empty($my_attachment['error'])) ? $my_attachment['error'] : '';

				$file_array = explode('/', $agilepress_file);
				$uploaded_filename = end($file_array);

				if ((isset($agilepress_file)) && (!empty($agilepress_file))) {
					$attachment_metabox .= '<tr>';
					$attachment_metabox .= '<td><a href="' . esc_url($agilepress_url) . '" target="_blank">' . esc_html($uploaded_filename) . '</a></td>';
					if (user_can(wp_get_current_user(), 'transition_tasks')) {
						$attachment_metabox .= '<td><label for="delete_attachment">Remove attachment? </label>';
						$attachment_metabox .= '<input type="checkbox" name="delete_attachment" value="' . esc_html($agilepress_file) . '"></td>';
					}
				    $attachment_metabox .= '</tr>';
					$attachment_metabox .= '<input type="hidden" name="current_file" value="' . esc_html($agilepress_file) . '">';
				}
			}
		}

	    $attachment_metabox .= '</table>';

		return $attachment_metabox;
	}

	/**
	 * Because there are numerous HTML select boxes involved in the AgilePress
	 * CPT metaboxes, we use this method to generate them all.
	 *
	 * Send either $dataset or $static_values but never both.  If the former
	 * ($dataset) is not null, it will be used; if the former is null, the method
	 * will look for the presense of the latter ($static_values).
	 *
	 * @param string $title  Title of Select Element
	 * @param string $html_name  The name to be used for HTML to identify element
	 * @param string $current_value  The currently-stored value for this element
	 * @param object $dataset  The data to be used for the select box (if from DB)
	 * @param array $static_values  The data to be used (if hard-coded)
	 * @return string $select_box  Formatted (HTML/CSS/JavaScript) select box
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\admin
	 */
	private function meta_select_box($title = null, $html_name = null, $current_value = null, $dataset = null, $static_values = null) {

		$select_box = '<td>' .__($title, 'agilepress').':</td>';

	    $select_box .= '<td><select name="' . esc_html($html_name) . '">';
	    if ($current_value == '') {
	        $select_box .= '<option value="" selected>None</option>';
	    } else {
	        $select_box .= '<option value="">None</option>';
	    }
		if ($dataset) {
			foreach($dataset as $datarow) {
				$select_box .=  '<option value="' . esc_html($datarow->post_name) . '" ' . selected($datarow->post_name, esc_attr($current_value), false) . '>' . esc_html($datarow->post_title) .
					'</option>';
			}
		} elseif ($static_values) {
			foreach($static_values as $static_value) {
				$select_box .=  '<option value="' . esc_html($static_value['post_name']) . '" ' . selected($static_value['post_name'], esc_attr($current_value), false) . '>' .
				                esc_html($static_value['post_title']) . '</option>';
			}
		} else {
			$select_box .= '<option value="">(No ' . esc_html($title) . ' to display.)</option>';
		}
	    $select_box .= '</select ' . $this->is_disabled() . '></td>';

		return $select_box;
	}

	private function is_disabled() {
		if (!user_can(wp_get_current_user(), 'transition_tasks')) {
			return 'disabled';
		} else {
			return null;
		}
	}
}
