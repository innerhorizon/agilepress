<?php
/**
 * Presentation code for settings screen(s)
 *
 * @link       https://agilepress.io
 * @since      0.0.0
 *
 * @package    AgilePress
 * @subpackage AgilePress\admin\partials
 */
namespace vinlandmedia\agilepress;

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

?>
    <div class="ap-panel">
        <a href="https://agilepress.io" target="_blank"><img src="<?= plugins_url('agilepress') ?>/admin/partials/img/agilepress.png"></a>
<h3>AgilePress Settings</h3>
<div class="wrap">
<form method="post" action="options.php" class="form-style-5">
<?php settings_fields('agilepress-settings-group'); ?>
<?php $agilepress_options = get_option('agilepress_options'); ?>
<fieldset class="settings-boxes"><legend>Registration</legend><br />
<p>Email: <input type="email" name="agilepress_options[agilepress_registered_email]" value="<?= esc_attr($agilepress_options['agilepress_registered_email']) ?>"></p>
<p>Product Key: <input name="agilepress_options[agilepress_product_key]" type="password"></p>
</fieldset>
<fieldset class="settings-boxes"><legend>Colors</legend><br />
<table style="text-align: left; width: 100%;" border="1" cellpadding="2"
cellspacing="2">
<tbody>
<tr>
<td style="vertical-align: top;">Epic<br>
</td>
<td style="vertical-align: top; background: #fdfd96;"><input name="agilepress_options[agilepress_epic_color]"
value="yellow" <?php checked('yellow', $agilepress_options['agilepress_epic_color'], true) ?> type="radio">Yellow<br>
</td>
<td style="vertical-align: top; background: #ffd1dc;"><input name="agilepress_options[agilepress_epic_color]"
value="pink" <?php checked('pink', $agilepress_options['agilepress_epic_color'], true) ?> type="radio">Pink<br>
</td>
<td style="vertical-align: top; background: #77dd77;"><input name="agilepress_options[agilepress_epic_color]"
value="green" <?php checked('green', $agilepress_options['agilepress_epic_color'], true) ?> type="radio">Green<br>
</td>
<td style="vertical-align: top; background: #aec6cf;"><input name="agilepress_options[agilepress_epic_color]"
value="blue" <?php checked('blue', $agilepress_options['agilepress_epic_color'], true) ?> type="radio">Blue<br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">Story<br>
</td>
<td style="vertical-align: top; background: #fdfd96;"><input name="agilepress_options[agilepress_story_color]"
value="yellow" <?php checked('yellow', $agilepress_options['agilepress_story_color'], true) ?> type="radio">Yellow<br>
</td>
<td style="vertical-align: top; background: #ffd1dc;"><input name="agilepress_options[agilepress_story_color]"
value="pink" <?php checked('pink', $agilepress_options['agilepress_story_color'], true) ?> type="radio">Pink<br>
</td>
<td style="vertical-align: top; background: #77dd77;"><input name="agilepress_options[agilepress_story_color]"
value="green" <?php checked('green', $agilepress_options['agilepress_story_color'], true) ?> type="radio">Green<br>
</td>
<td style="vertical-align: top; background: #aec6cf;"><input name="agilepress_options[agilepress_story_color]"
value="blue" <?php checked('blue', $agilepress_options['agilepress_story_color'], true) ?> type="radio">Blue<br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">Story (sent to sprint)<br>
</td>
<td style="vertical-align: top; background: #fdfd96;"><input name="agilepress_options[agilepress_story2sprint_color]"
value="yellow" <?php checked('yellow', $agilepress_options['agilepress_story2sprint_color'], true) ?> type="radio">Yellow<br>
</td>
<td style="vertical-align: top; background: #ffd1dc;"><input name="agilepress_options[agilepress_story2sprint_color]"
value="pink" <?php checked('pink', $agilepress_options['agilepress_story2sprint_color'], true) ?> type="radio">Pink<br>
</td>
<td style="vertical-align: top; background: #77dd77;"><input name="agilepress_options[agilepress_story2sprint_color]"
value="green" <?php checked('green', $agilepress_options['agilepress_story2sprint_color'], true) ?> type="radio">Green<br>
</td>
<td style="vertical-align: top; background: #aec6cf;"><input name="agilepress_options[agilepress_story2sprint_color]"
value="blue" <?php checked('blue', $agilepress_options['agilepress_story2sprint_color'], true) ?> type="radio">Blue<br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">Story (completed)<br>
</td>
<td style="vertical-align: top; background: #fdfd96;"><input name="agilepress_options[agilepress_storydone_color]"
value="yellow" type="radio">Yellow<br>
</td>
<td style="vertical-align: top; background: #ffd1dc;"><input name="agilepress_options[agilepress_storydone_color]"
value="pink" type="radio" checked>Pink<br>
</td>
<td style="vertical-align: top; background: #77dd77;"><input name="agilepress_options[agilepress_storydone_color]"
value="green" type="radio">Green<br>
</td>
<td style="vertical-align: top; background: #aec6cf;"><input name="agilepress_options[agilepress_storydone_color]"
value="blue" type="radio">Blue<br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">Sprint Backlog Item (story)<br>
</td>
<td style="vertical-align: top; background: #fdfd96;"><input name="agilepress_options[agilepress_sprintblog_color]"
value="yellow" <?php checked('yellow', $agilepress_options['agilepress_sprintblog_color'], true) ?> type="radio">Yellow<br>
</td>
<td style="vertical-align: top; background: #ffd1dc;"><input name="agilepress_options[agilepress_sprintblog_color]"
value="pink" <?php checked('pink', $agilepress_options['agilepress_sprintblog_color'], true) ?> type="radio">Pink<br>
</td>
<td style="vertical-align: top; background: #77dd77;"><input name="agilepress_options[agilepress_sprintblog_color]"
value="green" <?php checked('green', $agilepress_options['agilepress_sprintblog_color'], true) ?> type="radio">Green<br>
</td>
<td style="vertical-align: top; background: #aec6cf;"><input name="agilepress_options[agilepress_sprintblog_color]"
value="blue" <?php checked('blue', $agilepress_options['agilepress_sprintblog_color'], true) ?> type="radio">Blue<br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">To-Do<br>
</td>
<td style="vertical-align: top; background: #fdfd96;"><input name="agilepress_options[agilepress_todo_color]"
value="yellow" <?php checked('yellow', $agilepress_options['agilepress_todo_color'], true) ?> type="radio">Yellow<br>
</td>
<td style="vertical-align: top; background: #ffd1dc;"><input name="agilepress_options[agilepress_todo_color]"
value="pink" <?php checked('pink', $agilepress_options['agilepress_todo_color'], true) ?> type="radio">Pink<br>
</td>
<td style="vertical-align: top; background: #77dd77;"><input name="agilepress_options[agilepress_todo_color]"
value="green" <?php checked('green', $agilepress_options['agilepress_todo_color'], true) ?> type="radio">Green<br>
</td>
<td style="vertical-align: top; background: #aec6cf;"><input name="agilepress_options[agilepress_todo_color]"
value="blue" <?php checked('blue', $agilepress_options['agilepress_todo_color'], true) ?> type="radio">Blue<br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">In Progress<br>
</td>
<td style="vertical-align: top; background: #fdfd96;"><input name="agilepress_options[agilepress_nprogress_color]"
value="yellow" <?php checked('yellow', $agilepress_options['agilepress_nprogress_color'], true) ?> type="radio">Yellow<br>
</td>
<td style="vertical-align: top; background: #ffd1dc;"><input name="agilepress_options[agilepress_nprogress_color]"
value="pink" <?php checked('pink', $agilepress_options['agilepress_nprogress_color'], true) ?> type="radio">Pink<br>
</td>
<td style="vertical-align: top; background: #77dd77;"><input name="agilepress_options[agilepress_nprogress_color]"
value="green" <?php checked('green', $agilepress_options['agilepress_nprogress_color'], true) ?> type="radio">Green<br>
</td>
<td style="vertical-align: top; background: #aec6cf;"><input name="agilepress_options[agilepress_nprogress_color]"
value="blue" <?php checked('blue', $agilepress_options['agilepress_nprogress_color'], true) ?> type="radio">Blue<br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">In Testing<br>
</td>
<td style="vertical-align: top; background: #fdfd96;"><input name="agilepress_options[agilepress_ntesting_color]"
value="yellow" <?php checked('yellow', $agilepress_options['agilepress_ntesting_color'], true) ?> type="radio">Yellow<br>
</td>
<td style="vertical-align: top; background: #ffd1dc;"><input name="agilepress_options[agilepress_ntesting_color]"
value="pink" <?php checked('pink', $agilepress_options['agilepress_ntesting_color'], true) ?> type="radio">Pink<br>
</td>
<td style="vertical-align: top; background: #77dd77;"><input name="agilepress_options[agilepress_ntesting_color]"
value="green" <?php checked('green', $agilepress_options['agilepress_ntesting_color'], true) ?> type="radio">Green<br>
</td>
<td style="vertical-align: top; background: #aec6cf;"><input name="agilepress_options[agilepress_ntesting_color]"
value="blue" <?php checked('blue', $agilepress_options['agilepress_ntesting_color'], true) ?> type="radio">Blue<br>
</td>
</tr>
<tr>
<td style="vertical-align: top;">Done (task)<br>
</td>
<td style="vertical-align: top; background: #fdfd96;"><input name="agilepress_options[agilepress_done_color]"
value="yellow" <?php checked('yellow', $agilepress_options['agilepress_done_color'], true) ?> type="radio">Yellow<br>
</td>
<td style="vertical-align: top; background: #ffd1dc;"><input name="agilepress_options[agilepress_done_color]"
value="pink" <?php checked('pink', $agilepress_options['agilepress_done_color'], true) ?> type="radio">Pink<br>
</td>
<td style="vertical-align: top; background: #77dd77;"><input name="agilepress_options[agilepress_done_color]"
value="green" <?php checked('green', $agilepress_options['agilepress_done_color'], true) ?> type="radio">Green<br>
</td>
<td style="vertical-align: top; background: #aec6cf;"><input name="agilepress_options[agilepress_done_color]"
value="blue" <?php checked('blue', $agilepress_options['agilepress_done_color'], true) ?> type="radio">Blue<br>
</td>
</tr>
</tbody>
</table>
</fieldset>
<fieldset class="settings-boxes">
    <legend>Fonts</legend><br />
    Google Font for Note Title:&nbsp;
    <select name="agilepress_options[agilepress_note_title_font]" id="note-header-font">
    <option <?php selected('allura', $agilepress_options['agilepress_note_title_font'], true) ?> value="allura">Allura</option>
    <option <?php selected('architects-daughter', $agilepress_options['agilepress_note_title_font'], true) ?> value="architects-daughter">Architect's Daughter</option>
    <option <?php selected('damion', $agilepress_options['agilepress_note_title_font'], true) ?> value="damion">Damion</option>
    <option <?php selected('homemade-apple', $agilepress_options['agilepress_note_title_font'], true) ?> value="homemade-apple">Homemade Apple</option>
    <option <?php selected('indie-flower', $agilepress_options['agilepress_note_title_font'], true) ?> value="indie-flower">Indie Flower</option>
    <option <?php selected('patrick-hand-sc', $agilepress_options['agilepress_note_title_font'], true) ?> value="patrick-hand-sc">Patrick Hand SC</option>
    <option <?php selected('permanent-marker', $agilepress_options['agilepress_note_title_font'], true) ?> value="permanent-marker">Permanent Marker</option>
    <option <?php selected('reenie-beanie', $agilepress_options['agilepress_note_title_font'], true) ?> value="reenie-beanie">Reenie Beanie</option>
    <option <?php selected('rock-salt', $agilepress_options['agilepress_note_title_font'], true) ?> value="rock-salt">Rock Salt</option>
    <option <?php selected('sacramento', $agilepress_options['agilepress_note_title_font'], true) ?> value="sacramento">Sacramento</option>
    <option <?php selected('shadows-into-light-two', $agilepress_options['agilepress_note_title_font'], true) ?> value="shadows-into-light-two">Shadows Into Light Two</option>
        <option <?php selected('ledger', $agilepress_options['agilepress_note_title_font'], true) ?> value="ledger">Ledger</option>
        <option <?php selected('nixie-one', $agilepress_options['agilepress_note_title_font'], true) ?> value="nixie-one">Nixie One</option>

    </select>
<br><br>
    Google Font for Note Body:&nbsp;
    <select name="agilepress_options[agilepress_note_body_font]" id="note-body-font">
        <option <?php selected('allura', $agilepress_options['agilepress_note_body_font'], true) ?> value="allura">Allura</option>
        <option <?php selected('architects-daughter', $agilepress_options['agilepress_note_body_font'], true) ?> value="architects-daughter">Architect's Daughter</option>
        <option <?php selected('damion', $agilepress_options['agilepress_note_body_font'], true) ?> value="damion">Damion</option>
        <option <?php selected('homemade-apple', $agilepress_options['agilepress_note_body_font'], true) ?> value="homemade-apple">Homemade Apple</option>
        <option <?php selected('indie-flower', $agilepress_options['agilepress_note_body_font'], true) ?> value="indie-flower">Indie Flower</option>
        <option <?php selected('patrick-hand-sc', $agilepress_options['agilepress_note_body_font'], true) ?> value="patrick-hand-sc">Patrick Hand SC</option>
        <option <?php selected('permanent-marker', $agilepress_options['agilepress_note_body_font'], true) ?> value="permanent-marker">Permanent Marker</option>
        <option <?php selected('reenie-beanie', $agilepress_options['agilepress_note_body_font'], true) ?> value="reenie-beanie">Reenie Beanie</option>
        <option <?php selected('rock-salt', $agilepress_options['agilepress_note_body_font'], true) ?> value="rock-salt">Rock Salt</option>
        <option <?php selected('sacramento', $agilepress_options['agilepress_note_body_font'], true) ?> value="sacramento">Sacramento</option>
        <option <?php selected('shadows-into-light-two', $agilepress_options['agilepress_note_body_font'], true) ?> value="shadows-into-light-two">Shadows Into Light Two</option>
        <option <?php selected('ledger', $agilepress_options['agilepress_note_body_font'], true) ?> value="ledger">Ledger</option>
        <option <?php selected('nixie-one', $agilepress_options['agilepress_note_body_font'], true) ?> value="nixie-one">Nixie One</option>
    </select>
</fieldset>
<fieldset class="settings-boxes">
    <legend>Other Options</legend><br />
    <table>
        <tr>
            <td style="vertical-align: top;">Show Completed Notes as Crinkled?<br>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;"><input name="agilepress_options[agilepress_note_crinkle]"
                                                    value="yes" <?php checked('yes', $agilepress_options['agilepress_note_crinkle'], true) ?> type="radio">Yes<br>
            </td>
            <td style="vertical-align: top;"><input name="agilepress_options[agilepress_note_crinkle]"
                                                    value="no" <?php checked('no', $agilepress_options['agilepress_note_crinkle'], true) ?> type="radio">No<br>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Allow Guests to Comment on Public Board Notes?<br>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;"><input name="agilepress_options[agilepress_guest_comments]"
                                                    value="yes" <?php checked('yes', $agilepress_options['agilepress_guest_comments'], true) ?> type="radio">Yes<br>
            </td>
            <td style="vertical-align: top;"><input name="agilepress_options[agilepress_guest_comments]"
                                                    value="no" <?php checked('no', $agilepress_options['agilepress_guest_comments'], true) ?> type="radio">No<br>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset class="settings-boxes">
    <legend>Column Headings</legend><br />
    <table>
    <tr colspan="5">
    <td style="vertical-align: top;">Custom Board Column Headings<br>
    </td>
    </tr>
    <tr>
    <td style="vertical-align: top;">
        <p>Epic Header: <input type="text" name="agilepress_options[agilepress_epic_header]" value="<?= esc_attr($agilepress_options['agilepress_epic_header']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
    <p>Story Header: <input type="text" name="agilepress_options[agilepress_story_header]" value="<?= esc_attr($agilepress_options['agilepress_story_header']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
    <p>Send to Sprint Header: <input type="text" name="agilepress_options[agilepress_story2sprint_header]" value="<?= esc_attr($agilepress_options['agilepress_story2sprint_header']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
    <p>Completed Story Header: <input type="text" name="agilepress_options[agilepress_storydone_header]" value="<?= esc_attr($agilepress_options['agilepress_storydone_header']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
    <p></p>
    </td>
    </tr>
    <tr>
    <td style="vertical-align: top;">
        <p>Epic Description: <input type="text" name="agilepress_options[agilepress_epic_text]" value="<?= esc_attr($agilepress_options['agilepress_epic_text']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
    <p>Story Description: <input type="text" name="agilepress_options[agilepress_story_text]" value="<?= esc_attr($agilepress_options['agilepress_story_text']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
    <p>Send to Sprint Description: <input type="text" name="agilepress_options[agilepress_story2sprint_text]" value="<?= esc_attr($agilepress_options['agilepress_story2sprint_text']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
    <p>Completed Story Description: <input type="text" name="agilepress_options[agilepress_storydone_text]" value="<?= esc_attr($agilepress_options['agilepress_storydone_text']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
    <p></p>
    </td>
    </tr>
        <hr />
    <tr>
    <td style="vertical-align: top;">
        <p>Sprint Backlog Header: <input type="text" name="agilepress_options[agilepress_sprintblog_header]" value="<?= esc_attr($agilepress_options['agilepress_sprintblog_header']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
        <p>To-Do Header: <input type="text" name="agilepress_options[agilepress_todo_header]" value="<?= esc_attr($agilepress_options['agilepress_todo_header']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
        <p>In Progess Header: <input type="text" name="agilepress_options[agilepress_nprogress_header]" value="<?= esc_attr($agilepress_options['agilepress_nprogress_header']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
        <p>Testing/Review Header: <input type="text" name="agilepress_options[agilepress_ntesting_header]" value="<?= esc_attr($agilepress_options['agilepress_ntesting_header']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
        <p>Task Done Header: <input type="text" name="agilepress_options[agilepress_done_header]" value="<?= esc_attr($agilepress_options['agilepress_done_header']) ?>"></p>
    </td>
    </tr>
    <tr>
    <td style="vertical-align: top;">
        <p>Sprint Backlog Description: <input type="text" name="agilepress_options[agilepress_sprintblog_text]" value="<?= esc_attr($agilepress_options['agilepress_sprintblog_text']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
        <p>To-Do Description: <input type="text" name="agilepress_options[agilepress_todo_text]" value="<?= esc_attr($agilepress_options['agilepress_todo_text']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
        <p>In Progess Description: <input type="text" name="agilepress_options[agilepress_nprogress_text]" value="<?= esc_attr($agilepress_options['agilepress_nprogress_text']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
        <p>Testing/Review Description: <input type="text" name="agilepress_options[agilepress_ntesting_text]" value="<?= esc_attr($agilepress_options['agilepress_ntesting_text']) ?>"></p>
    </td>
    <td style="vertical-align: top;">
        <p>Task Done Description: <input type="text" name="agilepress_options[agilepress_done_text]" value="<?= esc_attr($agilepress_options['agilepress_done_text']) ?>"></p>
    </td>
    </tr>
</table>
</fieldset>
<p class="submit">
    <input type="submit" class="button-primary" value="Save Changes" />
</p>
</form>
</div>
    </div>
<?php
