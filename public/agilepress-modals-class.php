<?php
/**
 * The modal forms used with boards.
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

require_once plugin_dir_path( __FILE__ ) . '../admin/agilepress-metabox-class.php';
require_once plugin_dir_path( __FILE__ ) . '../includes/agilepress-query-class.php';

use WP_Query;

/**
 * Produces the modal screens used by the row of Font Awesome icons (the "FA bar")
 * that appears at the bottom of all notes.  These allow the user to do most functions
 * from the front end.
 *
 * @author Vinland Media, LLC.
 *
 * @package    AgilePress
 * @subpackage AgilePress\public
 *
 * @todo Break this up into smaller functions
 * @todo Needs doc blocs
 */
class AgilePress_Modals {

	/**
	 * Creates the modal dialogs for all boards
	 *
	 * This method creates the modal dialogs that are invoked from the FA (Font
	 * Awesome) bar that appears at the bottom of all notes. (This should probably
	 * be broken up into smaller routines but it's not happening this release!)
	 *
	 * @param string $board_type The type of board (backlog, kanban, etc.)
	 * @param object $board_data The details to be displayed in the modal
	 * @return string $modal_window A formated HTML string that displays modal data
	 *
	 * @uses \vinlandmedia\agilepress\AgilePress_Modals::modal_inner_form()
	 * @uses \vinlandmedia\agilepress\AgilePress_Query::get_products()
	 * @uses \vinlandmedia\agilepress\AgilePress_Query::get_sprints()
	 * @uses \vinlandmedia\agilepress\AgilePress_Query::get_stories()
	 * @uses \vinlandmedia\agilepress\AgilePress_Meta::build_task_meta_box()
	 *
	 * @author Vinland Media, LLC.
	 * @package AgilePress
	 */
	public function create_modals($note_type = null, $board_data = null) {
		global $wp;
		global $wpdb;

		$myQuery = new AgilePress_Query();

		$modal_window = '';

		// get saved options
		$agilepress_options = get_option('agilepress_options');

		// guests are allowed to comment on public boards
		if ((isset($agilepress_options['agilepress_guest_comments'])) && (!empty($agilepress_options['agilepress_guest_comments']))) {
			$guest_comments = $agilepress_options['agilepress_guest_comments'];
		} else {
			$guest_comments = null;
		}

		switch ($note_type) {
			case 'story':
				foreach ($board_data as $note) {
					$agilepress_meta = get_post_meta($note->id, '_agilepress_story_data', true);
					$current_status = (!empty($agilepress_meta['story_status'])) ? $agilepress_meta['story_status'] : '';
					$current_product = (!empty($agilepress_meta['product'])) ? $agilepress_meta['product'] : '';

					/*
					* Info
					*/
					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'Change the note\'s excerpt (i.e. the text body of the note).', 	// description
						$note->id, 						// id
						'info', 						// action
						$note, 							// board_data
						'header' 						// section
					);

					// $modal_window .= '</div>';
					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';
					$modal_window .= '<input class="w3-input" type="textarea" name="post_excerpt" value="' . esc_html($note->post_excerpt) . '" autofocus ' . $this->is_disabled('info') . '>';

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" name="submit" value="Submit Action" ' . $this->is_disabled('info') . '>';

					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'Change the note\'s excerpt (i.e. the text body of the note).', 	// description
						$note->id, 						// id
						'info', 						// action
						$note, 							// board_data
						'footer' 						// section
					);

					/*
					* Comment
					*/
					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'Recent comments (use the box below to add your own).', 				// description
						$note->id, 						// id
						'comment', 						// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';

					$args = array(
						'post_id' => $note->id,
						'number'  => '3',
						//'status' => 'approve'
					);
					$comments = get_comments($args);
					foreach ($comments as $comment) {
						$modal_window .= '<p>Comment: ' . esc_html($comment->comment_content) . '<br />';
						$modal_window .= 'Posted by: ' . esc_html($comment->comment_author)  . ' @ ' . esc_html($comment->comment_date) . '</p>';
						$modal_window .= '<hr>';
					}

					$modal_window .= '<label for="passed_comment" class="so-noted">Your Comment</label>';
					$modal_window .= '<input class="w3-input" type="textarea" name="passed_comment" value="" autofocus ' . $this->is_disabled('comment') . '>';

					if (('yes' == $guest_comments) && (!is_user_logged_in())) {
						$modal_window .= '<label for="passed_user_email" class="so-noted">Your Email Address</label>';
						$modal_window .= '<input class="w3-input" type="textarea" name="passed_user_email" value="" autofocus ' . $this->is_disabled('comment') . '>';
					}

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" name="submit" value="Submit Action" ' . $this->is_disabled('comment') . '>';

					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'Recent comments (use the box below to add your own).', 				// description
						$note->id, 						// id
						'comment', 						// action
						$note, 							// board_data
						'footer' 						// section
					);

					/*
					* Transition
					*/
					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'Create a new task from this story. This allows you to break your story (the "what") into to-do items (the "how").', 	// description
						$note->id, 						// id
						'transition', 					// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';

					$modal_window .= '<label for="passed_title" class="so-noted">New Task Title</label>';
					$modal_window .= '<input class="w3-input" type="text" name="passed_title" value="" placeholder="Enter new task title" autofocus ' . $this->is_disabled('transition') . '>';
					$modal_window .= '<label for="passed_excerpt" class="so-noted">New Task Info</label>';
					$modal_window .= '<input class="w3-input" type="textarea" name="passed_excerpt" value="" placeholder="Enter new task info" ' . $this->is_disabled('transition') . '>';

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" name="submit" value="Submit Action" ' . $this->is_disabled('transition') . '>';

					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'Create a new task from this story. This allows you to break your story (the "what") into to-do items (the "how").', 	// description
						$note->id, 						// id
						'transition', 					// action
						$note, 							// board_data
						'footer' 						// section
					);


					/*
					* Attachments
					*/
					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'View attachments related to this story. To add or remove attachments, visit the Admin panel entry for this story.', 			// description
						$note->id, 						// id
						'attachment', 					// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';

					//$products = $myQuery->get_products();
					//$sprints = $myQuery->get_sprints();

					$myMetadata = new AgilePress_Meta;

					$agilepress_attachment_meta = get_post_meta($note->id, '_agilepress_attachment_data', false);

					$meta_section = $myMetadata->build_attachment_meta_box($agilepress_attachment_meta, true);

					$modal_window .= $meta_section;

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" name="submit" value="Submit Action" autofocus ' . $this->is_disabled('attachment') . '>';

					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'View attachments related to this story. To add or remove attachments, visit the Admin panel entry for this story.', 			// description
						$note->id, 						// id
						'attachment', 					// action
						$note, 							// board_data
						'footer' 						// section
					);


					/*
					* Settings
					*/
					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'Change any setting related to the story. (Bear in mind, changing the settings could result in a change in the story status or even remove it from this board).', // description
						$note->id, 						// id
						'settings', 					// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';

					$products = $myQuery->get_products();
					$sprints = $myQuery->get_sprints();

					$myMetadata = new AgilePress_Meta;

					$meta_section = $myMetadata->build_story_meta_box($agilepress_meta, $products, $sprints, false);
					//print_r($meta_section);
					//$meta_section = str_replace('agilepress_story[product]', 'passed_product', $meta_section);
					//$meta_section = str_replace('agilepress_story[story_status]', 'passed_status', $meta_section);
					//$meta_section = str_replace('agilepress_story[story_sprint]', 'passed_sprint', $meta_section);
					//$meta_section = str_replace('agilepress_story[parent_epic]', 'passed_epic', $meta_section);
					//$meta_section = str_replace('agilepress_story[parent_epic]', 'passed_epic', $meta_section);

					$modal_window .= $meta_section;

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" name="submit" value="Submit Action" autofocus ' . $this->is_disabled('settings') . '>';

					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'Change any setting related to the story. (Bear in mind, changing the settings could result in a change in the story status or even remove it from this board).', // description
						$note->id, 						// id
						'settings', 					// action
						$note, 							// board_data
						'footer' 						// section
					);

					/*
					* Remove
					*/
					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'Delete this item?', 			// description
						$note->id, 						// id
						'remove', 						// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';

					$modal_window .= '<label for="passed_checkbox" class="so-noted">Check to Confirm Deletion of "' . esc_html($note->story_title) . '":  </label>';
					$modal_window .= ' <input class="w3-check" type="checkbox" name="passed_checkbox" value="remove">';

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" id="submit-remove" name="submit" value="Submit Action" autofocus ' . $this->is_disabled('remove') . '>';

					$modal_window .= $this->modal_inner_form(
						'backlogboard', 				// board_type
						'story', 						// note_type
						$current_product,
						null,
						$note->story_title,				// note_title
						'Delete this item?', 			// description
						$note->id, 						// id
						'remove', 						// action
						$note, 							// board_data
						'footer' 						// section
					);

				}
				break;

			default:
				foreach ($board_data as $note) {

					/*
					* Info
					*/
					$agilepress_meta = get_post_meta($note->id, '_agilepress_task_data', true);
					$current_status = (!empty($agilepress_meta['task_status'])) ? $agilepress_meta['task_status'] : '';
					$current_product = (!empty($agilepress_meta['product'])) ? $agilepress_meta['product'] : '';
					$current_sprint = (!empty($agilepress_meta['sprint'])) ? $agilepress_meta['sprint'] : '';

					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'task', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'Change the note\'s excerpt (i.e. the text body of the note).', 	// description
						$note->id, 						// id
						'info', 						// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';
					$modal_window .= '<input class="w3-input" type="textarea" name="post_excerpt" value="' . esc_html($note->post_excerpt) . '" autofocus> ' . $this->is_disabled('info') . '';

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" name="submit" value="Submit Action" ' . $this->is_disabled('info') . '>';

					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'task', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'Change the note\'s excerpt (i.e. the text body of the note).', 	// description
						$note->id, 						// id
						'info', 						// action
						$note, 							// board_data
						'footer' 						// section
					);


					/*
					* Comment
					*/

					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'task', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'Recent comments (use the box below to add your own).', 				// description
						$note->id, 						// id
						'comment', 						// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';


					$args = array(
						'post_id' => $note->id,
						'number'  => '3',
						//'status' => 'approve'
					);
					$comments = get_comments($args);
					foreach ($comments as $comment) {
						$modal_window .= '<p>Comment: ' . esc_html($comment->comment_content) . '<br />';
						$modal_window .= 'Posted by: ' . esc_html($comment->comment_author)  . ' @ ' . esc_html($comment->comment_date) . '</p>';
						$modal_window .= '<hr>';
					}

					$modal_window .= '<label for="passed_comment" class="so-noted">Your Comment</label>';
					$modal_window .= '<input class="w3-input" type="textarea" name="passed_comment" value="" autofocus ' . $this->is_disabled('comment') . '>';

					if (('yes' == $guest_comments) && (!is_user_logged_in())) {
						$modal_window .= '<label for="passed_user_email" class="so-noted">Your Email Address</label>';
						$modal_window .= '<input class="w3-input" type="textarea" name="passed_user_email" value="" autofocus ' . $this->is_disabled('comment') . '>';
					}

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" name="submit" value="Submit Action" ' . $this->is_disabled('comment') . '>';

					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'task', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'Recent comments (use the box below to add your own).', 				// description
						$note->id, 						// id
						'comment', 						// action
						$note, 							// board_data
						'footer' 						// section
					);


					/*
					* Transition
					*/
					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'story', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'Create a new task from this story. This allows you to break your story (the "what") into to-do items (the "how").', 	// description
						$note->id, 						// id
						'transition', 					// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';

					$modal_window .= '<label for="passed_title" class="so-noted">New Task Title</label>';
					$modal_window .= '<input class="w3-input" type="text" name="passed_title" value="" placeholder="Enter new task title" autofocus ' . $this->is_disabled('transition') . '>';
					$modal_window .= '<label for="passed_excerpt" class="so-noted">New Task Info</label>';
					$modal_window .= '<input class="w3-input" type="textarea" name="passed_excerpt" value="" placeholder="Enter new task info" ' . $this->is_disabled('transition') . '>';

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" name="submit" value="Submit Action" ' . $this->is_disabled('transition') . '>';

					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'story', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'Create a new task from this story. This allows you to break your story (the "what") into to-do items (the "how").', 	// description
						$note->id, 						// id
						'transition', 					// action
						$note, 							// board_data
						'footer' 						// section
					);


					/*
					* Attachments
					*/
					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'task', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'View attachments related to this task. To add or remove attachments, visit the Admin panel entry for this task.', 			// description
						$note->id, 						// id
						'attachment', 					// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form enctype="multipart/form-data" action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';

					//$products = $myQuery->get_products();
					//$sprints = $myQuery->get_sprints();

					$agilepress_attachment_meta = get_post_meta($note->id, '_agilepress_attachment_data', false);

					$myMetadata = new AgilePress_Meta;
					$meta_section = $myMetadata->build_attachment_meta_box($agilepress_attachment_meta, true);

					$modal_window .= $meta_section;

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" name="submit" value="Submit Action" autofocus ' . $this->is_disabled('attachment') . '>';

					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'task', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'View attachments related to this story. To add or remove attachments, visit the Admin panel entry for this story.', 			// description
						$note->id, 						// id
						'attachment', 					// action
						$note, 							// board_data
						'footer' 						// section
					);

					/*
					* Settings
					*/
					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'task', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'Change any setting related to the task. (Bear in mind, changing the settings could result in a change in the task status or even remove it from this board).', 			// description
						$note->id, 						// id
						'settings', 					// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';

					$products = $myQuery->get_products();
					$sprints = $myQuery->get_sprints();
					$stories = $myQuery->get_stories();

					$myMetadata = new AgilePress_Meta;

					$meta_section = $myMetadata->build_task_meta_box($agilepress_meta, $products, $sprints, $stories, false);
					//print_r($meta_section);

					//$meta_section = str_replace('agilepress_task[product]', 'passed_product', $meta_section);
					//$meta_section = str_replace('agilepress_task[task_status]', 'passed_status', $meta_section);
					//$meta_section = str_replace('agilepress_task[sprint]', 'passed_sprint', $meta_section);
					///$meta_section = str_replace('agilepress_task[parent_story]', 'passed_parent', $meta_section);
					//$meta_section = str_replace('agilepress_task[task_priority]', 'passed_priority', $meta_section);

					$modal_window .= $meta_section;

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" name="submit" value="Submit Action" autofocus ' . $this->is_disabled('settings') . '>';

					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'task', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'Change any setting related to the task. (Bear in mind, changing the settings could result in a change in the task status or even remove it from this board).', 			// description
						$note->id, 						// id
						'settings', 					// action
						$note, 							// board_data
						'footer' 						// section
					);

					/*
					* Remove
					*/
					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'task', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'Delete this item?', 			// description
						$note->id, 						// id
						'remove', 						// action
						$note, 							// board_data
						'header' 						// section
					);

					$modal_window .= '<form action="' . admin_url('admin-post.php') . '" method="POST">';
					$modal_window .= '<input type="hidden" name="action" value="modal_response">';

					$modal_window .= '<label for="passed_checkbox" class="so-noted">Check to Confirm Deletion of "' . esc_html($note->task_title) . '":  </label>';
					$modal_window .= ' <input class="w3-check" type="checkbox" name="passed_checkbox" value="remove">';

					$modal_window .= '<br /><br />';
					$modal_window .= '<input type="submit" id="submit-remove" name="submit" value="Submit Action" autofocus ' . $this->is_disabled('remove') . '>';

					$modal_window .= $this->modal_inner_form(
						'sprintboard', 					// board_type
						'task', 						// note_type
						$current_product,
						$current_sprint,
						$note->task_title,				// note_title
						'Delete this item?', 			// description
						$note->id, 						// id
						'remove', 						// action
						$note, 							// board_data
						'footer' 						// section
					);

				}
				break;
		}

		return $modal_window;
	}

	/**
	 * Creates the redundant parts of each modal
	 *
	 * This method creates the head and tail of all modals.
	 *
	 * @param string $board_type The type of board (backlog, kanban, etc.)
	 * @param string $note_type Task or Story
	 * @param string $note_title Display name for note
	 * @param string $description What the modal does
	 * @param integer $id The ID of the note being affected
	 * @param string $action The action being taking (i.e. comment, transition, etc.)
	 * @param object $board_data The contents of the affected note
	 * @param string $section Whether to do the top or the bottom
	 * @return string $partial_modal A formated HTML string
	 *
	 * @author Vinland Media, LLC.
	 * @package AgilePress
	 */
	private function modal_inner_form($board_type = null, $note_type = null,
			$current_product = null, $current_sprint = null, $note_title = null,
			$description = null, $id = null, $action = null, $board_data = null,
			$section = null) {

		global $wp;

		$partial_modal = '';
		$url = home_url(add_query_arg(array(),$wp->request));//home_url($wp->request);

		switch ($section) {
			case 'header':
				$window_id = 'id-' . $action . '-' . $id;

				$title = '$board_data->' . $note_type . '_title';

				$partial_modal .= '<div id="' . esc_html($window_id) . '" class="w3-modal">';
				$partial_modal .= '<div class="w3-modal-content w3-card-4 w3-animate-left w3-leftbar w3-border-black">';
				$partial_modal .= '<div class="w3-container">';
				$partial_modal .= '<span onclick="document.getElementById(\'' . esc_html($window_id) . '\').style.display=\'none\'" class="w3-button w3-display-topright">&times;</span>';
				$partial_modal .= '<div class="w3-container">';
				$partial_modal .= '<div class="w3-panel w3-gray">';

				$partial_modal .= '<p><h2 id="modalWindowHeader">' . esc_html($note_title) . '</h2></p>';
				$partial_modal .= '</div>';
				$partial_modal .= '<div>';
				$partial_modal .= '<p class="so-noted">' . esc_html($description) . '</p>';

				$partial_modal .= '</div>';
				break;

			case 'footer':
				$partial_modal .= '<input type="hidden" id="board_type" name="board_type" value="' . esc_html($board_type) . '">';
				$partial_modal .= '<input type="hidden" id="passed_id" name="passed_id" value="' . esc_html($id) . '">';
				$partial_modal .= '<input type="hidden" id="passed_action" name="passed_action" value="' . esc_html($action) . '">';
				$partial_modal .= '<input type="hidden" name="post_id" value="' . esc_html($id) . '">';
				$partial_modal .= '<input type="hidden" name="current_url" value="' . esc_url($url) . '">';
				$partial_modal .= '<input type="hidden" name="current_product" value="' . $current_product . '">';
				$partial_modal .= '<input type="hidden" name="current_sprint" value="' . $current_sprint . '">';
				$partial_modal .= '<br /><br />';
				$partial_modal .= '</form>';
				$partial_modal .= '</div>';
				$partial_modal .= '</div>';
				$partial_modal .= '</div>';
				$partial_modal .= '</div>';
				break;

			default:
				$partial_modal .= '';
				break;
		}

		return $partial_modal;
	}

	/**
	 * Disables HTML Inputs
	 *
	 * This method is used to disable HTML input elements for users who are not
	 * authorized to edit/change them.
	 *
	 * @param string $action  The action currently being processed
	 * @return string $result The word "disabled" or null
	 *
	 * @author Vinland Media, LLC.
	 * @package AgilePress
	 */
	private function is_disabled($action = null) {
		if ('comment' == $action) {
			// get saved options
			$agilepress_options = get_option('agilepress_options');

			// guests are allowed to comment on public boards
			if ((isset($agilepress_options['agilepress_guest_comments'])) && (!empty($agilepress_options['agilepress_guest_comments']))) {
				$guest_comments = $agilepress_options['agilepress_guest_comments'];
			} else {
				$guest_comments = null;
			}
		}

		if ((user_can(wp_get_current_user(), 'transition_tasks')) || ('yes' == $guest_comments && 'comment' == $action))  {
			$result = null;
		} else {
			$result = 'disabled';
		}

		return $result;
	}
}
