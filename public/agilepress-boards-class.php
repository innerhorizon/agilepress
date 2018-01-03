<?php
/**
 * The various boards produced by AgilePress.
 *
 * @link       https://agilepress.io
 * @since      0.0.0
 *
 * @package    AgilePress
 * @subpackage AgilePress\public
 */
namespace vinlandmedia\agilepress;

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once plugin_dir_path( __FILE__ ) . '/agilepress-modals-class.php';
require_once plugin_dir_path( __FILE__ ) . '/agilepress-notes-class.php';

use WP_Query;

/**
 * Creates boards and summary page
 *
 * This class is used to create the boards that make up the main attraction of
 * AgilePress.
 *
 * @author Vinland Media, LLC.
 *
 * @package    AgilePress
 * @subpackage AgilePress\public
 *
 * @todo Break this up into smaller functions
 * @todo Needs doc blocs
 */
class AgilePress_Boards {
	private $epic_header;
	private $story_header;
	private $send2sprint_header;
	private $completed_header;

	private $epic_text;
	private $story_text;
	private $send2sprint_text;
	private $completed_text;

	private $sprintbacklog_header;
	private $todo_header;
	private $inprogress_header;
	private $intesting_header;
	private $done_header;

	private $sprintbacklog_text;
	private $todo_text;
	private $inprogress_text;
	private $intesting_text;
	private $done_text;

	public function __construct()
    {
		$agilepress_options = get_option('agilepress_options');

		if ((isset($agilepress_options['agilepress_epic_header'])) && (!empty($agilepress_options['agilepress_epic_header']))) {
			$this->epic_header = $agilepress_options['agilepress_epic_header'];
		} else {
			$this->epic_header = 'Epics';
		}
		if ((isset($agilepress_options['agilepress_story_header'])) && (!empty($agilepress_options['agilepress_story_header']))) {
			$this->story_header = $agilepress_options['agilepress_story_header'];
		} else {
			$this->story_header = 'Stories';
		}
		if ((isset($agilepress_options['agilepress_story2sprint_header'])) && (!empty($agilepress_options['agilepress_story2sprint_header']))) {
			$this->send2sprint_header = $agilepress_options['agilepress_story2sprint_header'];
		} else {
			$this->send2sprint_header = 'Send to Sprint';
		}
		if ((isset($agilepress_options['agilepress_storydone_header'])) && (!empty($agilepress_options['agilepress_storydone_header']))) {
			$this->completed_header = $agilepress_options['agilepress_storydone_header'];
		} else {
			$this->completed_header = 'Completed';
		}

		if ((isset($agilepress_options['agilepress_epic_text'])) && (!empty($agilepress_options['agilepress_epic_text']))) {
			$this->epic_text = $agilepress_options['agilepress_epic_text'];
		} else {
			$this->epic_text = '';
		}
		if ((isset($agilepress_options['agilepress_story_text'])) && (!empty($agilepress_options['agilepress_story_text']))) {
			$this->story_text = $agilepress_options['agilepress_story_text'];
		} else {
			$this->story_text = '';
		}
		if ((isset($agilepress_options['agilepress_story2sprint_text'])) && (!empty($agilepress_options['agilepress_story2sprint_text']))) {
			$this->send2sprint_text = $agilepress_options['agilepress_story2sprint_text'];
		} else {
			$this->send2sprint_text = '';
		}
		if ((isset($agilepress_options['agilepress_storydone_text'])) && (!empty($agilepress_options['agilepress_storydone_text']))) {
			$this->completed_text = $agilepress_options['agilepress_storydone_text'];
		} else {
			$this->completed_text = '';
		}

		if ((isset($agilepress_options['agilepress_sprintblog_header'])) && (!empty($agilepress_options['agilepress_sprintblog_header']))) {
			$this->sprintbacklog_header = $agilepress_options['agilepress_sprintblog_header'];
		} else {
			$this->sprintbacklog_header = 'Sprint Backlog Story';
		}
		if ((isset($agilepress_options['agilepress_todo_header'])) && (!empty($agilepress_options['agilepress_todo_header']))) {
			$this->todo_header = $agilepress_options['agilepress_todo_header'];
		} else {
			$this->todo_header = 'To-Do';
		}
		if ((isset($agilepress_options['agilepress_nprogress_header'])) && (!empty($agilepress_options['agilepress_nprogress_header']))) {
			$this->inprogress_header = $agilepress_options['agilepress_nprogress_header'];
		} else {
			$this->inprogress_header = 'In Progress';
		}
		if ((isset($agilepress_options['agilepress_ntesting_header'])) && (!empty($agilepress_options['agilepress_ntesting_header']))) {
			$this->intesting_header = $agilepress_options['agilepress_ntesting_header'];
		} else {
			$this->intesting_header = 'In Testing/Review';
		}
		if ((isset($agilepress_options['agilepress_done_header'])) && (!empty($agilepress_options['agilepress_done_header']))) {
			$this->done_header = $agilepress_options['agilepress_done_header'];
		} else {
			$this->done_header = 'Done';
		}

		if ((isset($agilepress_options['agilepress_sprintblog_text'])) && (!empty($agilepress_options['agilepress_sprintblog_text']))) {
			$this->sprintbacklog_text = $agilepress_options['agilepress_sprintblog_text'];
		} else {
			$this->sprintbacklog_text = '';
		}
		if ((isset($agilepress_options['agilepress_todo_text'])) && (!empty($agilepress_options['agilepress_todo_text']))) {
			$this->todo_text = $agilepress_options['agilepress_todo_text'];
		} else {
			$this->todo_text = '';
		}
		if ((isset($agilepress_options['agilepress_nprogress_text'])) && (!empty($agilepress_options['agilepress_nprogress_text']))) {
			$this->inprogress_text = $agilepress_options['agilepress_nprogress_text'];
		} else {
			$this->inprogress_text = '';
		}
		if ((isset($agilepress_options['agilepress_ntesting_text'])) && (!empty($agilepress_options['agilepress_ntesting_text']))) {
			$this->intesting_text = $agilepress_options['agilepress_ntesting_text'];
		} else {
			$this->intesting_text = '';
		}
		if ((isset($agilepress_options['agilepress_done_text'])) && (!empty($agilepress_options['agilepress_done_text']))) {
			$this->done_text = $agilepress_options['agilepress_done_text'];
		} else {
			$this->done_text = '';
		}

    }

	/**
	 * Produces product summary page
	 *
	 * This method creates the product summary report page.
	 *
	 * @param string $page_heading The display name for the top of the page
	 * @param string $product_name The product internal name (not display name)
	 * @return string $display A formated HTML string that displays the summary
	 *
	 * @global $wpdb
	 *
	 * @uses \wordpress\wpdb\get_results()
	 * @uses \wordpress\get_post_meta()
	 * @uses \wordpress\get_permalink()
	 *
 	 * @author Ken Kitchen ken@vinlandmedia.com
	 * @author Vinland Media, LLC.
	 * @package AgilePress
	 */
	public function summarypage($page_heading = null, $product_name = null) {
		$is_story = false;
		$is_backlog = false;
		$is_sprint = false;

		global $wpdb;

		$display = "";

		// summary query
		$product_details = $wpdb->get_results("
			select 'product' as record_type, p.ID, p.post_title, p.post_name, p.post_excerpt, m.meta_key, m.meta_value
			from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m
			where p.post_type = 'agilepress-products' and p.post_status = 'publish'
			and p.post_name = '" . $product_name . "'
			and m.post_id = p.ID
			and m.meta_key = '_agilepress_product_data'
			union all
			select 'story' as record_type, p.ID, p.post_title, p.post_name, p.post_excerpt, m.meta_key, m.meta_value
			from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m
			where p.post_type = 'agilepress-stories' and p.post_status = 'publish'
			and m.post_id = p.ID
			and m.meta_key = '_agilepress_story_data'
			and m.meta_value like '%" . $product_name . "%'
			union all
			select 'task' as record_type, p.ID, p.post_title, p.post_name, p.post_excerpt, m.meta_key, m.meta_value
			from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m
			where p.post_type = 'agilepress-tasks' and p.post_status = 'publish'
			and m.post_id = p.ID
			and m.meta_key = '_agilepress_task_data'
			and m.meta_value like '%" . $product_name . "%'
			union all
			select 'sprint' as record_type, p.ID, p.post_title, p.post_name, p.post_excerpt, m.meta_key, m.meta_value
			from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m
			where p.post_type = 'agilepress-sprints' and p.post_status = 'publish'
			and m.post_id = p.ID
			and m.meta_key = '_agilepress_sprint_data'
			and m.meta_value like '%" . $product_name . "%'
		");

		$display = '<table class="w3-table w3-bordered w3-hoverable w3-card-4">';
		$display .= '<tr><th>Product</th><th>Version</th><th>Target</th><th>Status</th></tr>';

		$story_header = false;
		$task_header = false;
		$sprint_header = false;

        foreach ($product_details as $product_detail) {
			$display .= '<tr>';

			switch ($product_detail->meta_key) {
				case '_agilepress_product_data':
					$agilepress_meta = get_post_meta($product_detail->ID, '_agilepress_product_data', true);
					$display .= '<td class="yellownote">' . esc_html($product_detail->post_title) . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['version'], 'n/a') . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['target'], 'n/a') . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['phase'], 'lookup') . '</td>';
					break;

				case '_agilepress_story_data':
					if (!$story_header) {
						$display .= '<tr><th>Stories</th><th>Status</th><th>Parent Epic</th><th>-</th></tr>';
						$story_header = true;
					}
					$agilepress_meta = get_post_meta($product_detail->ID, '_agilepress_story_data', true);
					$display .= '<td class="pinknote">' . esc_html($product_detail->post_title) . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['story_status'], 'lookup') . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['parent_epic'], 'db') . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['story_sprint'], 'db') . '</td>';
					break;

				case '_agilepress_task_data':
					if (!$task_header) {
						$display .= '<tr><th>Tasks</th><th>Sprint</th><th>Parent Story</th><th>Status</th></tr>';
						$task_header = true;
					}
					$agilepress_meta = get_post_meta($product_detail->ID, '_agilepress_task_data', true);
					$display .= '<td class="greennote">' . esc_html($product_detail->post_title) . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['sprint'], 'db') . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['parent_story'], 'db') . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['task_status'], 'lookup') . '</td>';
					break;

				case '_agilepress_sprint_data':
					if (!$sprint_header) {
						$display .= '<tr><th>Sprints</th><th>Start Date</th><th>End Date</th><th>Status</th></tr>';
						$sprint_header = true;
					}
					$agilepress_meta = get_post_meta($product_detail->ID, '_agilepress_sprint_data', true);
					$display .= '<td class="bluenote">' . esc_html($product_detail->post_title) . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['start_date'], 'n/a') . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['end_date'], 'n/a') . '</td>';
					$display .= '<td>' . $this->check_value($agilepress_meta['status'], 'lookup') . '</td>';
					break;

				default:
					# code...
					break;
			}

			$display .= '</tr>';
		}

		$display .= '</table>';

		return $display;
	}

	/**
	 * Produces Kanban board
	 *
	 * This method creates the Kanban board.
	 *
	 * @param string $page_heading The display name for the top of the page
	 * @param object $tasks The results of a query for tasks relating to product
	 * @param string $product The current product
	 * @return string $display_board A formated HTML string that displays the summary
	 *
	 * @uses \wordpress\get_post_meta()
	 * @uses \vinlandmedia\agilepress\AgilePress_Boards::create_note()
	 * @uses \vinlandmedia\agilepress\AgilePress_Modals::create_modals()
	 *
 	 * @author Ken Kitchen ken@vinlandmedia.com
	 * @author Vinland Media, LLC.
	 * @package AgilePress
	 */
	public function kanbanboard($page_heading = null, $tasks = null, $product = null) {
		$is_story = true;
		$is_backlog = false;
		$is_sprint = false;

		$myKanbanNotes = new AgilePress_Notes();

		$display_board = '
			<div id="scrumboard" class="w3-container">
				<div id="todo" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
					<h3 class="underline">' . $this->todo_header . '</h3><span style="font-size: .8em !important;"><p><em><center>' . $this->todo_text . '</center></em></p></span>';

		foreach($tasks as $task) {
			$agilepress_meta = get_post_meta($task->id, '_agilepress_task_data', true);

			$agilepress_product = ( ! empty( $agilepress_meta['product'] ) ) ? $agilepress_meta['product'] : '';
			$agilepress_sprint = ( ! empty( $agilepress_meta['sprint'] ) ) ? $agilepress_meta['sprint'] : '';
			$agilepress_task_status = ( ! empty( $agilepress_meta['task_status'] ) ) ? $agilepress_meta['task_status'] : '';
			$agilepress_task_priority = ( ! empty( $agilepress_meta['task_priority'] ) ) ? $agilepress_meta['task_priority'] : '';
			$agilepress_task_assignee = ( ! empty( $agilepress_meta['task_assignee'] ) ) ? $agilepress_meta['task_assignee'] : '';
			$agilepress_parent_story = ( ! empty( $agilepress_meta['parent_story'] ) ) ? $agilepress_meta['parent_story'] : '';
			$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
			$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';

			if (($agilepress_task_status == 'todo') && (empty($agilepress_sprint))) {
				$myKanbanNotes->set_id($task->id);
				$myKanbanNotes->set_title($task->task_title);
				$myKanbanNotes->set_text($task->post_excerpt);
				$myKanbanNotes->set_status('todo');
				$myKanbanNotes->set_board('kanban');
				$myKanbanNotes->set_priority($agilepress_task_priority);
				$myKanbanNotes->set_assignee($agilepress_task_assignee);
				$myKanbanNotes->set_target_date($agilepress_target_date);
				$myKanbanNotes->set_completed_date($agilepress_completed_date);

				$display_board .= $myKanbanNotes->create_note();
			}
		};

		$display_board .=
			'</div>
			<div id="inprogress" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
				<h3 class="underline">' . $this->inprogress_header . '</h3><span style="font-size: .8em !important;"><p><em><center>' . $this->inprogress_text . '</center></em></p></span>';

		foreach($tasks as $task) {
			$agilepress_meta = get_post_meta($task->id, '_agilepress_task_data', true);

			$agilepress_product = ( ! empty( $agilepress_meta['product'] ) ) ? $agilepress_meta['product'] : '';
			$agilepress_sprint = ( ! empty( $agilepress_meta['sprint'] ) ) ? $agilepress_meta['sprint'] : '';
			$agilepress_task_status = ( ! empty( $agilepress_meta['task_status'] ) ) ? $agilepress_meta['task_status'] : '';
			$agilepress_task_priority = ( ! empty( $agilepress_meta['task_priority'] ) ) ? $agilepress_meta['task_priority'] : '';
			$agilepress_task_assignee = ( ! empty( $agilepress_meta['task_assignee'] ) ) ? $agilepress_meta['task_assignee'] : '';
			$agilepress_parent_story = ( ! empty( $agilepress_meta['parent_story'] ) ) ? $agilepress_meta['parent_story'] : '';
			$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
			$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';

			if (($agilepress_task_status == 'inprogress') && (empty($agilepress_sprint))) {
				$myKanbanNotes->set_id($task->id);
				$myKanbanNotes->set_title($task->task_title);
				$myKanbanNotes->set_text($task->post_excerpt);
				$myKanbanNotes->set_status('inprogress');
				$myKanbanNotes->set_board('kanban');
				$myKanbanNotes->set_priority($agilepress_task_priority);
				$myKanbanNotes->set_assignee($agilepress_task_assignee);
				$myKanbanNotes->set_target_date($agilepress_target_date);
				$myKanbanNotes->set_completed_date($agilepress_completed_date);

				$display_board .= $myKanbanNotes->create_note();
			}
		};

		$display_board .=
			'</div>
			<div id="intesting" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
				<h3 class="underline">' . $this->intesting_header . '</h3><span style="font-size: .8em !important;"><p><em><center>' . $this->intesting_text . '</center></em></p></span>';

		foreach($tasks as $task) {
			$agilepress_meta = get_post_meta($task->id, '_agilepress_task_data', true);

			$agilepress_product = ( ! empty( $agilepress_meta['product'] ) ) ? $agilepress_meta['product'] : '';
			$agilepress_sprint = ( ! empty( $agilepress_meta['sprint'] ) ) ? $agilepress_meta['sprint'] : '';
			$agilepress_task_status = ( ! empty( $agilepress_meta['task_status'] ) ) ? $agilepress_meta['task_status'] : '';
			$agilepress_task_priority = ( ! empty( $agilepress_meta['task_priority'] ) ) ? $agilepress_meta['task_priority'] : '';
			$agilepress_task_assignee = ( ! empty( $agilepress_meta['task_assignee'] ) ) ? $agilepress_meta['task_assignee'] : '';
			$agilepress_parent_story = ( ! empty( $agilepress_meta['parent_story'] ) ) ? $agilepress_meta['parent_story'] : '';
			$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
			$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';

			if (($agilepress_task_status == 'intesting') && (empty($agilepress_sprint))) {
				$myKanbanNotes->set_id($task->id);
				$myKanbanNotes->set_title($task->task_title);
				$myKanbanNotes->set_text($task->post_excerpt);
				$myKanbanNotes->set_status('intesting');
				$myKanbanNotes->set_board('kanban');
				$myKanbanNotes->set_priority($agilepress_task_priority);
				$myKanbanNotes->set_assignee($agilepress_task_assignee);
				$myKanbanNotes->set_target_date($agilepress_target_date);
				$myKanbanNotes->set_completed_date($agilepress_completed_date);

				$display_board .= $myKanbanNotes->create_note();
			}
		};

		$display_board .=
			'</div>
			<div id="done" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
				<h3 class="underline">' . $this->done_header . '</h3><span style="font-size: .8em !important;"><p><em><center>' . $this->done_text . '</center></em></p></span>';

		foreach($tasks as $task) {
			$agilepress_meta = get_post_meta($task->id, '_agilepress_task_data', true);

			$agilepress_product = ( ! empty( $agilepress_meta['product'] ) ) ? $agilepress_meta['product'] : '';
			$agilepress_sprint = ( ! empty( $agilepress_meta['sprint'] ) ) ? $agilepress_meta['sprint'] : '';
			$agilepress_task_status = ( ! empty( $agilepress_meta['task_status'] ) ) ? $agilepress_meta['task_status'] : '';
			$agilepress_task_priority = ( ! empty( $agilepress_meta['task_priority'] ) ) ? $agilepress_meta['task_priority'] : '';
			$agilepress_task_assignee = ( ! empty( $agilepress_meta['task_assignee'] ) ) ? $agilepress_meta['task_assignee'] : '';
			$agilepress_parent_story = ( ! empty( $agilepress_meta['parent_story'] ) ) ? $agilepress_meta['parent_story'] : '';
			$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
			$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';

			if (($agilepress_task_status == 'done') && (empty($agilepress_sprint))) {
				$myKanbanNotes->set_id($task->id);
				$myKanbanNotes->set_title($task->task_title);
				$myKanbanNotes->set_text($task->post_excerpt);
				$myKanbanNotes->set_status('done');
				$myKanbanNotes->set_board('kanban');
				$myKanbanNotes->set_priority($agilepress_task_priority);
				$myKanbanNotes->set_assignee($agilepress_task_assignee);
				$myKanbanNotes->set_target_date($agilepress_target_date);
				$myKanbanNotes->set_completed_date($agilepress_completed_date);

				$display_board .= $myKanbanNotes->create_note();
			}
		};
		$display_board .= '</div>';
		$kanban_modal = new AgilePress_Modals();
		$display_board .= $kanban_modal->create_modals('tasks', $tasks);

		return $display_board;

	}

	/**
	 * Produces Sprint board
	 *
	 * This method creates the Sprint board.
	 *
	 * @param string $page_heading The display name for the top of the page
	 * @param string $header_product_title The display name for the Product
	 * @param object $tasks The results of a query for tasks relating to product
	 * @param string $products The current product
	 * @return string $display_board A formated HTML string that displays the summary
	 *
	 * @uses \wordpress\get_post_meta()
	 * @uses \vinlandmedia\agilepress\AgilePress_Boards::create_note()
	 * @uses \vinlandmedia\agilepress\AgilePress_Modals::create_modals()
	 *
 	 * @author Ken Kitchen ken@vinlandmedia.com
	 * @author Vinland Media, LLC.
	 * @package AgilePress
	 */
	public function sprintboard($page_heading = null, $header_product_title = null, $stories = null, $tasks = null, $product = null, $sprint = null) {
		$is_story = false;
		$is_backlog = false;
		$is_sprint = true;

		$mySprintNotes = new AgilePress_Notes();

		$display_board = '
			<div id="scrumboard" class="w3-container">
			<div id="sendtosprint" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
				<h3 class="underline">' . $this->sprintbacklog_header . '</h3><span style="font-size: .8em !important;"><p><em><center>' . $this->sprintbacklog_text . '</center></em></p></span>';

		if ((isset($stories)) && (!empty($stories))) {
			foreach($stories as $activepbi_story) {

				$agilepress_meta = get_post_meta($activepbi_story->id, '_agilepress_story_data', true);

				$agilepress_product = (!empty($agilepress_meta['product'])) ? $agilepress_meta['product'] : '';
				$agilepress_status = (!empty($agilepress_meta['story_status'])) ? $agilepress_meta['story_status'] : '';
				$agilepress_sprint = (!empty($agilepress_meta['story_sprint'])) ? $agilepress_meta['story_sprint'] : '';
				$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
				$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';
				$agilepress_story_priority = ( ! empty( $agilepress_meta['story_priority'] ) ) ? $agilepress_meta['story_priority'] : '';

				if (($agilepress_status == 'sendtosprint') && ($agilepress_sprint == $sprint)) {
					$mySprintNotes->set_id($activepbi_story->id);
					$mySprintNotes->set_title($activepbi_story->story_title);
					$mySprintNotes->set_text($activepbi_story->post_excerpt);
					$mySprintNotes->set_status('sendtosprint');
					$mySprintNotes->set_board('sprint');
					$mySprintNotes->set_priority($agilepress_story_priority);
					$mySprintNotes->set_target_date($agilepress_target_date);
					$mySprintNotes->set_completed_date($agilepress_completed_date);

					$display_board .= $mySprintNotes->create_note();
				}
			};

			$display_board .=
				'</div>
				<div id="todo" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
					<h3 class="underline">' . $this->todo_header . '</h3><span style="font-size: .8em !important;"><p><em><center>' . $this->todo_text . '</center></em></p></span>';

			if ((isset($tasks)) && (!empty($tasks))) {
				foreach($tasks as $todo_task) {

					$agilepress_meta = get_post_meta($todo_task->id, '_agilepress_task_data', true);

					$agilepress_product = ( ! empty( $agilepress_meta['product'] ) ) ? $agilepress_meta['product'] : '';
					$agilepress_sprint = ( ! empty( $agilepress_meta['sprint'] ) ) ? $agilepress_meta['sprint'] : '';
					$agilepress_task_status = ( ! empty( $agilepress_meta['task_status'] ) ) ? $agilepress_meta['task_status'] : '';
					$agilepress_task_priority = ( ! empty( $agilepress_meta['task_priority'] ) ) ? $agilepress_meta['task_priority'] : '';
					$agilepress_task_assignee = ( ! empty( $agilepress_meta['task_assignee'] ) ) ? $agilepress_meta['task_assignee'] : '';
					$agilepress_parent_story = ( ! empty( $agilepress_meta['parent_story'] ) ) ? $agilepress_meta['parent_story'] : '';
					$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
					$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';

					if (($agilepress_task_status == 'todo') && ($agilepress_sprint == $sprint)) {
						$mySprintNotes->set_id($todo_task->id);
						$mySprintNotes->set_title($todo_task->task_title);
						$mySprintNotes->set_text($todo_task->post_excerpt);
						$mySprintNotes->set_status('todo');
						$mySprintNotes->set_board('sprint');
						$mySprintNotes->set_priority($agilepress_task_priority);
						$mySprintNotes->set_assignee($agilepress_task_assignee);
						$mySprintNotes->set_target_date($agilepress_target_date);
						$mySprintNotes->set_completed_date($agilepress_completed_date);
						$mySprintNotes->set_parent($agilepress_parent_story);

						$display_board .= $mySprintNotes->create_note();
					}
				};
			}

			$display_board .=
				'</div>
				<div id="inprogress" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
					<h3 class="underline">' . $this->inprogress_header . '</h3><span style="font-size: .8em !important;"><p><em><center>' . $this->inprogress_text . '</center></em></p></span>';

			if ((isset($tasks)) && (!empty($tasks))) {
				foreach($tasks as $inprogress_task) {

					$agilepress_meta = get_post_meta($inprogress_task->id, '_agilepress_task_data', true);

					$agilepress_product = ( ! empty( $agilepress_meta['product'] ) ) ? $agilepress_meta['product'] : '';
					$agilepress_sprint = ( ! empty( $agilepress_meta['sprint'] ) ) ? $agilepress_meta['sprint'] : '';
					$agilepress_task_status = ( ! empty( $agilepress_meta['task_status'] ) ) ? $agilepress_meta['task_status'] : '';
					$agilepress_task_priority = ( ! empty( $agilepress_meta['task_priority'] ) ) ? $agilepress_meta['task_priority'] : '';
					$agilepress_task_assignee = ( ! empty( $agilepress_meta['task_assignee'] ) ) ? $agilepress_meta['task_assignee'] : '';
					$agilepress_parent_story = ( ! empty( $agilepress_meta['parent_story'] ) ) ? $agilepress_meta['parent_story'] : '';
					$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
					$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';

					if (($agilepress_task_status == 'inprogress') && ($agilepress_sprint == $sprint)) {
						$mySprintNotes->set_id($inprogress_task->id);
						$mySprintNotes->set_title($inprogress_task->task_title);
						$mySprintNotes->set_text($inprogress_task->post_excerpt);
						$mySprintNotes->set_status('inprogress');
						$mySprintNotes->set_board('sprint');
						$mySprintNotes->set_priority($agilepress_task_priority);
						$mySprintNotes->set_assignee($agilepress_task_assignee);
						$mySprintNotes->set_target_date($agilepress_target_date);
						$mySprintNotes->set_completed_date($agilepress_completed_date);
						$mySprintNotes->set_parent($agilepress_parent_story);

						$display_board .= $mySprintNotes->create_note();
					}
				};
			}

			$display_board .=
				'</div>
				<div id="done" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
					<h3 class="underline">' . $this->done_header . '</h3><span style="font-size: .8em !important;"><p><em><center>' . $this->done_text . '</center></em></p></span>';

			if ((isset($tasks)) && (!empty($tasks))) {
				foreach($tasks as $done_task) {

					$agilepress_meta = get_post_meta($done_task->id, '_agilepress_task_data', true);

					$agilepress_product = ( ! empty( $agilepress_meta['product'] ) ) ? $agilepress_meta['product'] : '';
					$agilepress_sprint = ( ! empty( $agilepress_meta['sprint'] ) ) ? $agilepress_meta['sprint'] : '';
					$agilepress_task_status = ( ! empty( $agilepress_meta['task_status'] ) ) ? $agilepress_meta['task_status'] : '';
					$agilepress_task_priority = ( ! empty( $agilepress_meta['task_priority'] ) ) ? $agilepress_meta['task_priority'] : '';
					$agilepress_task_assignee = ( ! empty( $agilepress_meta['task_assignee'] ) ) ? $agilepress_meta['task_assignee'] : '';
					$agilepress_parent_story = ( ! empty( $agilepress_meta['parent_story'] ) ) ? $agilepress_meta['parent_story'] : '';
					$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
					$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';

					if (($agilepress_task_status == 'done') && ($agilepress_sprint == $sprint)) {
						$mySprintNotes->set_id($done_task->id);
						$mySprintNotes->set_title($done_task->task_title);
						$mySprintNotes->set_text($done_task->post_excerpt);
						$mySprintNotes->set_status('done');
						$mySprintNotes->set_board('sprint');
						$mySprintNotes->set_priority($agilepress_task_priority);
						$mySprintNotes->set_assignee($agilepress_task_assignee);
						$mySprintNotes->set_target_date($agilepress_target_date);
						$mySprintNotes->set_completed_date($agilepress_completed_date);
						$mySprintNotes->set_parent($agilepress_parent_story);

						$display_board .= $mySprintNotes->create_note();
					}
				};
			}
			$display_board .= '</div>';
			$sprint_modal = new AgilePress_Modals();

			if ((isset($stories)) && (!empty($stories))) {
				$display_board .= $sprint_modal->create_modals('story', $stories);
			}

			if ((isset($tasks)) && (!empty($tasks))) {
				$display_board .= $sprint_modal->create_modals('task', $tasks);
			}

		} else {
			$display_board = 'Nothing to display!';
		}

		return $display_board;
	}

	/**
	 * Produces Backlog board
	 *
	 * This method creates the Backlog board.
	 *
	 * @param string $page_heading The display name for the top of the page
	 * @param string $header_product_title The display name for the Product
	 * @param object $stories The results of a query for stories relating to product
	 * @param string $product The current product
	 * @return string $display_board A formated HTML string that displays the summary
	 *
	 * @uses \wordpress\get_post_meta()
	 * @uses \vinlandmedia\agilepress\AgilePress_Boards::create_note()
	 * @uses \vinlandmedia\agilepress\AgilePress_Modals::create_modals()
	 *
 	 * @author Ken Kitchen ken@vinlandmedia.com
	 * @author Vinland Media, LLC.
	 * @package AgilePress
	 */
	public function backlogboard($page_heading = null, $header_product_title = null, $stories = null, $product = null) {
		$is_story = false;
		$is_backlog = true;
		$is_sprint = false;

		$myStoryNotes = new AgilePress_Notes();

		$display_board = '
			<div id="scrumboard" class="w3-container">
			<div id="isepic" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
				<h3 class="underline">' . esc_html($this->epic_header) . '</h3><span style="font-size: .8em !important;"><p><em><center>' . esc_html($this->epic_text) . '</center></em></p></span>';

		foreach($stories as $epic_story) {

			$agilepress_meta = get_post_meta($epic_story->id, '_agilepress_story_data', true);

			$agilepress_product = (!empty($agilepress_meta['product'])) ? $agilepress_meta['product'] : '';
			$agilepress_status = (!empty($agilepress_meta['story_status'])) ? $agilepress_meta['story_status'] : '';
			$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
			$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';
			$agilepress_story_priority = ( ! empty( $agilepress_meta['story_priority'] ) ) ? $agilepress_meta['story_priority'] : '';

			if ($agilepress_status == 'isepic') {
				$myStoryNotes->set_id($epic_story->id);
				$myStoryNotes->set_title($epic_story->story_title);
				$myStoryNotes->set_text($epic_story->post_excerpt);
				$myStoryNotes->set_status('isepic');
				$myStoryNotes->set_board('backlog');
				$myStoryNotes->set_priority($agilepress_story_priority);
				$myStoryNotes->set_target_date($agilepress_target_date);
				$myStoryNotes->set_completed_date($agilepress_completed_date);

				$display_board .= $myStoryNotes->create_note();
			}
		};

		$display_board .=
			'</div>
			<div id="isstory" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
				<h3 class="underline">' . esc_html($this->story_header) . '</h3><span style="font-size: .8em !important;"><p><em><center>' . esc_html($this->story_text) . '</center></em></p></span>';

		$myStoryNotes = new AgilePress_Notes();

		foreach($stories as $is_story) {

			$agilepress_meta = get_post_meta($is_story->id, '_agilepress_story_data', true);

			$agilepress_product = (!empty($agilepress_meta['product'])) ? $agilepress_meta['product'] : '';
			$agilepress_status = (!empty($agilepress_meta['story_status'])) ? $agilepress_meta['story_status'] : '';
			$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
			$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';
			$agilepress_story_priority = ( ! empty( $agilepress_meta['story_priority'] ) ) ? $agilepress_meta['story_priority'] : '';
			$agilepress_story_parent = ( ! empty( $agilepress_meta['parent_epic'] ) ) ? $agilepress_meta['parent_epic'] : '';

			if ($agilepress_status == 'isstory') {
				$myStoryNotes->set_id($is_story->id);
				$myStoryNotes->set_title($is_story->story_title);
				$myStoryNotes->set_text($is_story->post_excerpt);
				$myStoryNotes->set_status('isstory');
				$myStoryNotes->set_board('backlog');
				$myStoryNotes->set_priority($agilepress_story_priority);
				$myStoryNotes->set_target_date($agilepress_target_date);
				$myStoryNotes->set_completed_date($agilepress_completed_date);
				$myStoryNotes->set_parent($agilepress_story_parent);

				$display_board .= $myStoryNotes->create_note();
			}
		};


		$display_board .=
			'</div>
			<div id="sendtosprint" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
				<h3 class="underline">' . esc_html($this->send2sprint_header) . '</h3><span style="font-size: .8em !important;"><p><em><center>' . esc_html($this->send2sprint_text) . '</center></em></p></span>';

		$mySprintNotes = new AgilePress_Notes();

		foreach($stories as $sendtosprint_task) {

			$agilepress_meta = get_post_meta($sendtosprint_task->id, '_agilepress_story_data', true);

			$agilepress_product = (!empty($agilepress_meta['product'])) ? $agilepress_meta['product'] : '';
			$agilepress_status = (!empty($agilepress_meta['story_status'])) ? $agilepress_meta['story_status'] : '';
			$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
			$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';
			$agilepress_story_priority = ( ! empty( $agilepress_meta['story_priority'] ) ) ? $agilepress_meta['story_priority'] : '';

			if ($agilepress_status == 'sendtosprint') {
				$myStoryNotes->set_id($sendtosprint_task->id);
				$myStoryNotes->set_title($sendtosprint_task->story_title);
				$myStoryNotes->set_text($sendtosprint_task->post_excerpt);
				$myStoryNotes->set_status('sendtosprint');
				$myStoryNotes->set_board('backlog');
				$myStoryNotes->set_priority($agilepress_story_priority);
				$myStoryNotes->set_target_date($agilepress_target_date);
				$myStoryNotes->set_completed_date($agilepress_completed_date);

				$display_board .= $myStoryNotes->create_note();
			}
		};

		$display_board .=
			'</div>
			<div id="iscomplete" class="w3-col l3 ap-column" ondrop="drop(event)" ondragover="allowDrop(event)">
				<h3 class="underline">' . esc_html($this->completed_header) . '</h3><span style="font-size: .8em !important;"><p><em><center>' . esc_html($this->completed_text) . '</center></em></p></span>';

		$myCompletedNotes = new AgilePress_Notes();

		foreach($stories as $complete_story) {

			$agilepress_meta = get_post_meta($complete_story->id, '_agilepress_story_data', true);

			$agilepress_product = (!empty($agilepress_meta['product'])) ? $agilepress_meta['product'] : '';
			$agilepress_status = (!empty($agilepress_meta['story_status'])) ? $agilepress_meta['story_status'] : '';
			$agilepress_target_date = ( ! empty( $agilepress_meta['target_date'] ) ) ? $agilepress_meta['target_date'] : '';
			$agilepress_completed_date = ( ! empty( $agilepress_meta['completed_date'] ) ) ? $agilepress_meta['completed_date'] : '';
			$agilepress_story_priority = ( ! empty( $agilepress_meta['story_priority'] ) ) ? $agilepress_meta['story_priority'] : '';

			if ($agilepress_status == 'iscomplete') {
				$myStoryNotes->set_id($complete_story->id);
				$myStoryNotes->set_title($complete_story->story_title);
				$myStoryNotes->set_text($complete_story->post_excerpt);
				$myStoryNotes->set_status('iscomplete');
				$myStoryNotes->set_board('backlog');
				$myStoryNotes->set_priority($agilepress_story_priority);
				$myStoryNotes->set_target_date($agilepress_target_date);
				$myStoryNotes->set_completed_date($agilepress_completed_date);

				$display_board .= $myStoryNotes->create_note();
			}
		};
		$display_board .= '</div>';
		$backlog_modal = new AgilePress_Modals();
		$display_board .= $backlog_modal->create_modals('story', $stories);

		return $display_board;
	}

	function check_value(&$value = null, $lookup_method = null)
    {
		global $wpdb;

		$display_value = "";

		if (isset($value)) {

			switch ($lookup_method) {
				case 'lookup':
					$lookup_value_table_name = $wpdb->prefix . "agilepress_lookup_values";

					$lookup_value = $wpdb->get_var(
						"select lookup_value " .
						"from " . $lookup_value_table_name . " " .
						"where lookup_key = '" . $value . "' "
					);

					if ((isset($lookup_value)) && (!empty($lookup_value))) {
						$display_value = $lookup_value;
					}
					break;

				case 'db':
					$lookup_value = $wpdb->get_var(
						"select post_title " .
						"from " . $wpdb->posts . " " .
						"where post_type like 'agilepress-%' " .
						"and post_name = '" . $value . "'"
					);

					if ((isset($lookup_value)) && (!empty($lookup_value))) {
						$display_value = $lookup_value;
					}
					break;

				default:
					$display_value = "";
					break;
			}

		} else {
			$display_value = "";
		}

		return $display_value;

    }

}
