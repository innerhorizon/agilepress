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

/**
 * Creates the AgilePress Fucntion Bar
 *
 * This class is used to create the Font Awesome function bar used with
 * all sticky notes
 *
 * @author Vinland Media, LLC.
 *
 * @package    AgilePress
 * @subpackage AgilePress\public
 *
 * @todo Needs doc blocs
 */
class AgilePress_FunctionBar {

	/**
	 * Produces the AgilePress function bar.
	 *
	 * This method displays the five Font Awesome icons that are displayed at the bottom of all board notes.  The
	 * icons are: fa-pencil (edit the title and body of a note), fa-comment (shows the comment bubble with a number
	 * if comments exist), fa-paperclip (attachments), fa-cog (settings), and fa-remove (delete).
	 *
	 * @param string $note_id The id of the current note
	 * @return string $display A formated HTML string that displays the function bar
	 *
 	 * @author Ken Kitchen ken@vinlandmedia.com
	 * @author Vinland Media, LLC.
	 * @package AgilePress
	 */
	public function create_function_bar($note_id, $status, $board) {

		// comment icon should show a count if there are any comments
		$comment_number = get_comments_number($note_id);
		if ($comment_number > 0) {
			$fa_comment_icon = 'fa-comment-o';
			//$comment_icon = '<sup class="tiny-text">' . $comment_number . '</sup>';
		} else {
			$fa_comment_icon = 'fa-comment';
			//$comment_icon = null;
		}

		if ($this->has_attachments($note_id)) {
			$fa_attachment_icon = 'fa-files-o';
		} else {
			$fa_attachment_icon = 'fa-file';
		}

		// if we have a logged-in user who is eligible for updates then give the full-function bar
		if (user_can(wp_get_current_user(), 'transition_tasks'))  {
			// build the Font Awesome bar for regular users
			$function_bar = '<br /><div class="function-bar"><i class="fa fa-pencil" onclick="noticons(' . $note_id . ', &#39;info&#39;)"></i>' . ' ' .
			                '<i class="fa ' . $fa_comment_icon . '" onclick="noticons(' . $note_id . ', &#39;comment&#39;)"></i>' . ' ' .
			                $this->use_multiaction($note_id, $status, $board) .
			                '<i class="fa ' . $fa_attachment_icon . '" onclick="noticons(' . $note_id . ', &#39;attachment&#39;)"></i>' . ' ' .
			                '<i class="fa fa-cog" onclick="noticons(' . $note_id . ', &#39;settings&#39;)"></i>' . ' ' .
			                '<i class="fa fa-remove" onclick="noticons(' . $note_id . ', &#39;remove&#39;)"></i></div>';
		} else {
			// build the Font Awesome bar for public
			$function_bar = '<br /><div class="function-bar">' .
			                '<i class="fa ' . $fa_comment_icon . '" onclick="noticons(' . $note_id . ', &#39;comment&#39;)"></i>' . ' ' .
			                '<i class="fa ' . $fa_attachment_icon . '" onclick="noticons(' . $note_id . ', &#39;attachment&#39;)"></i></div>';
		}

		return $function_bar;
	}

	/**
	 * Show the mutlt-action icon.
	 *
	 * This method ...
	 *
	 * @param string $note_id  The id of the current note
	 * @param string $status  The current status being processed
	 * @param sting $board  The current board being processed
	 * @return string  The fa-plus icon and related action HTML/JS or a blank string
	 *
	 * @author Ken Kitchen ken@vinlandmedia.com
	 * @author Vinland Media, LLC.
	 * @package AgilePress
	 */
	private function use_multiaction($note_id = null, $status = null, $board = null)
    {
        //if ((in_array($status, array('sendtosprint')))) {
		if (('sendtosprint' == $status) && ('sprint' == $board)) {
			return '<i class="fa fa-plus" onclick="noticons(' . $note_id . ', &#39;transition&#39;)"></i> ';
		} else {
			return "";
		}
    }

    private function has_attachments($note_id) {
	    $agilepress_meta = get_post_meta($note_id, '_agilepress_attachment_data', false);

	    if ($agilepress_meta) {
	    	return true;
	    } else {
	    	return false;
	    }
    }

}
