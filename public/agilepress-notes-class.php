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

	// getters and setters
	public $id;
	public function set_id($id) {
		$this->id = $id;
	}
	public function get_id() {
		return $this->id;
	}

	public $title;
	public function set_title($title) {
		$this->title = $title;
	}
	public function get_title() {
		return $this->title;
	}

	public $text;
	public function set_text($text) {
		$this->text = $text;
	}
	public function get_text() {
		return $this->text;
	}

	public $status;
	public function set_status($status) {
		$this->status = $status;
	}
	public function get_status() {
		return $this->status;
	}

	public $board;
	public function set_board($board) {
		$this->board = $board;
	}
	public function get_board() {
		return $this->board;
	}

	public $priority;
	public function set_priority($priority) {
		$this->priority = $priority;
	}
	public function get_priority() {
		return $this->priority;
	}

	public $target_date;
	public function set_target_date($target_date) {
		$this->target_date = $target_date;
	}
	public function get_target_date() {
		return $this->target_date;
	}

	public $completed_date;
	public function set_completed_date($completed_date) {
		$this->completed_date = $completed_date;
	}
	public function get_completed_date() {
		return $this->completed_date;
	}

	public $assignee;
	public function set_assignee($assignee) {
		$this->assignee = $assignee;
	}
	public function get_assignee() {
		return $this->assignee;
	}

	public $parent;
	public function set_parent($parent) {
		$this->parent = $parent;
	}
	public function get_parent() {
		return $this->parent;
	}
	
	/**
	 * Produces...
	 *
	 * This method...
	 *
	 * @param string $page_heading The display name for the top of the page
	 * @param string $product_name The product internal name (not display name)
	 * @return string $display A formated HTML string that displays the summary
	 *
 	 * @author Ken Kitchen ken@vinlandmedia.com
	 * @author Vinland Media, LLC.
	 * @package AgilePress
	 */
	public function create_note() {
		// get saved options
		$agilepress_options = get_option('agilepress_options');

		// use the crinkled look for completed items if selected
		if ((isset($agilepress_options['agilepress_note_crinkle'])) && (!empty($agilepress_options['agilepress_note_crinkle']))) {
			$note_crinkle = $agilepress_options['agilepress_note_crinkle'];
		}

		// assign colors based on user preferences
		switch ($this->status) {
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

		$myFunctionBar = new AgilePress_FunctionBar($this->id);

		if ((isset($this->priority)) && (!empty($this->priority))) {
			switch ($this->priority) {
				case '5':
					$priority_display = 'Critical /';
					break;
				case '4':
					$priority_display = 'Major /';
					break;
				case '3':
					$priority_display = 'Normal /';
					break;
				case '2':
					$priority_display = 'Minor /';
					break;
				case '1':
					$priority_display = 'Trivial /';
					break;

				default:
					$priority_display = null;
					break;
			}

		} else {
			$priority_display = '';
		}


		$the_note = '<div id="' . $this->id
		            . '" class="w3-card-4 w3-container default-margin ' . $notecolor . ' ' . $headerfont . ' '
		            . $this->sprint_overlay($this->status, $this->board) . '" '
		            . $this->is_draggable($this->status, $this->board) . '>'
					. '<span class="' . $this->status .' priority-bar">' . $this->title;

		if ('' != $this->parent) {
			if (('isstory' == $this->status) && ('backlog' == $this->board)) {
				$the_note .= ' (' . $this->parent . ')';
			} elseif (('sendtosprint' != $this->status) && ('sprint' == $this->board) ) {
				$the_note .= ' (' . $this->parent . ')';
			}
		}

		$the_note .= '</span>'
		            . '<br /><br />'
		            . '<p class="' . $detailfont . '">' . $this->text . '</p></span>'
		            . '<br />'
		            . ' '  . '<div class="priority-bar"><span class="priority-text">' . $priority_display
		            . $this->make_my_date($this->status, $this->target_date, $this->completed_date) . '</span><br />';

		if (('kanban' == $this->board) || (('sprint' == $this->board) && ('sendtosprint' != $this->status))) {
			$the_note .= $this->show_assignee($this->assignee) . '</span><br />';
		}

		$the_note .= '</div><br />' . $myFunctionBar->create_function_bar($this->id, $this->status, $this->board)
		            . '</div>';

		return $the_note;
	}

	private function make_my_date($status, $target_date, $completed_date) {

		if (('done' == $status) || ('iscomplete' == $status)) {
			if ('' != $completed_date) {
				$return_date = ' C: ' . $completed_date;
			} else {
				$return_date = ' not complete';
			}
		} else {
			if ('' != $target_date) {
				$return_date = ' T: ' . $target_date ;
			} else {
				$return_date = ' no target set';
			}
		}

		return $return_date;

	}

	private function sprint_overlay($status, $board) {
		if (($status == 'sendtosprint') && ($board == 'backlog')) {
			$overlay = ' sprintoverlay ';
		} else {
			$overlay = ' ';
		}

		return $overlay;

	}

	private function show_assignee($assignee) {
		if ('' == $assignee) {
			$my_assignee = '#' . 'unassigned';
		} else {
			$my_assignee = '@' . $assignee;
		}

		return $my_assignee;

	}

	// todo this still needs to be brought up to previous functionality levels
	private function is_draggable($status, $board) {
		if (('sprint' == $board) && ('sendtosprint' == $status)) {
			$draggable = ' draggable="false" ';
		} elseif (1 == 1) {
			if (user_can(wp_get_current_user(), 'transition_tasks')) {
				$draggable =  ' draggable="true" ondragstart="drag(event)" ';
			} else {
				$draggable = ' draggable="false" ';
			}
		}

		return $draggable;

	}

}
