<?php
/**
 * The AgilePress notes class
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

require_once plugin_dir_path( __FILE__ ) . '/agilepress-funcbar-class.php';

//use WP_Query;

/**
 * Creates notes for use on boards
 *
 * This class is used to create the notes that are used to display stories
 * and tasks on boards.
 *
 * @author Vinland Media, LLC.
 *
 * @package    AgilePress
 * @subpackage AgilePress\public
 *
 * @todo Needs doc blocs
 */
class AgilePress_Notes {

	/**
	 * Produces...
	 *
	 * This method...
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
	public function create_note($id = null, $title = null, $name = null, $text = null, $status = null, $board = null, $priority = null) {
		// get saved options
		$agilepress_options = get_option('agilepress_options');

		// use the crinkled look for completed items if selected
		if ((isset($agilepress_options['agilepress_note_crinkle'])) && (!empty($agilepress_options['agilepress_note_crinkle']))) {
			$note_crinkle = $agilepress_options['agilepress_note_crinkle'];
		}

		// assign colors based on user preferences
		switch ($status) {
			case 'isepic':
				$notecolor = $agilepress_options['agilepress_epic_color'] . 'note';
				break;

			case 'isstory':
				$notecolor = $agilepress_options['agilepress_story_color'] . 'note';
				break;

			case 'sendtosprint':
				$notecolor = $agilepress_options['agilepress_story2sprint_color'] . 'note';
				break;

			case 'iscomplete':
				if ($note_crinkle == 'yes') {
					$notecolor = 'notewrinkled ' . $agilepress_options['agilepress_storydone_color'] . 'note';;
				} else {
					$notecolor = $agilepress_options['agilepress_storydone_color'] . 'note';
				}
				break;

			case 'todo':
				$notecolor = $agilepress_options['agilepress_todo_color'] . 'note';
				break;

			case 'inprogress':
				$notecolor = $agilepress_options['agilepress_nprogress_color'] . 'note';
				break;

			case 'intesting':
				$notecolor = $agilepress_options['agilepress_ntesting_color'] . 'note';
				break;

			case 'done':
				if ($note_crinkle == 'yes') {
					$notecolor = 'notewrinkled ' . $agilepress_options['agilepress_done_color'] . 'note';
				} else {
					$notecolor = $agilepress_options['agilepress_done_color'] . 'note';
				}
				break;

			default:
				$notecolor = 'yellownote';
				break;
		}

		// assign fonts based on user preferences
		$headerfont = 'notebody-' . $agilepress_options['agilepress_note_title_font'];
		$detailfont = 'notebody-' . $agilepress_options['agilepress_note_body_font'];

		$myFunctionBar = new AgilePress_FunctionBar($id);

		if ((isset($priority)) && (!empty($priority))) {
			switch ($priority) {
				case '5':
					$priority_display = '(Critical)';
					break;
				case '4':
					$priority_display = '(Major)';
					break;
				case '3':
					$priority_display = '(Normal)';
					break;
				case '2':
					$priority_display = '(Minor)';
					break;
				case '1':
					$priority_display = '(Trivial)';
					break;

				default:
					$priority_display = null;
					break;
			}

		} else {
			$priority_display = '';
		}

		if ((user_can(wp_get_current_user(), 'transition_tasks')) && ($board != 'sprint'))  {
			if (($status == 'sendtosprint') && ($board == 'backlog')) {
				$the_note = '<div id="' . $id . '" class="w3-card-4 w3-container default-margin ' . $notecolor . ' ' . $headerfont . ' sprintoverlay " ' .
				 'draggable="true" ondragstart="drag(event)"><span class="'. $status .'">'
					. $title . ' <span class="priority-text">' . $priority_display . '</span><p class="' . $detailfont . '">' . $text .
					'</p></span>' . $myFunctionBar->create_function_bar($id, $status, $board) . '</div>';
			} else {
				$the_note = '<div id="' . $id . '" class="w3-card-4 w3-container default-margin ' . $notecolor . ' ' . $headerfont . '" ' .
				 'draggable="true" ondragstart="drag(event)"><span class="'. $status .'">'
					. $title . ' <span class="priority-text">' . $priority_display . '</span><p class="' . $detailfont . '">' . $text .
					'</p></span>' . $myFunctionBar->create_function_bar($id, $status, $board) . '</div>';
			}
		} elseif ((user_can(wp_get_current_user(), 'transition_tasks')) && ($status != 'sendtosprint')) {
			$the_note = '<div id="' . $id . '" class="w3-card-4 w3-container default-margin ' . $notecolor . ' ' . $headerfont . '" ' .
			 'draggable="true" ondragstart="drag(event)"><span class="'. $status .'">'
				. $title . ' <span class="priority-text">' . $priority_display . '</span><p class="' . $detailfont . '">' . $text .
				'</p></span>' . $myFunctionBar->create_function_bar($id, $status, $board) . '</div>';
		} else {
			$the_note = '<div id="' . $id . '" class="w3-card-4 w3-container w3-margin ' . $notecolor . ' ' . $headerfont . '" ' .
			 'draggable="false"><span class="'. $status .'">'
				. $title . '<p class="' . $detailfont . '">' . $text .
				'</p></span>' . $myFunctionBar->create_function_bar($id, $status, $board) . '</div>';
		}

		return $the_note;
	}

}
