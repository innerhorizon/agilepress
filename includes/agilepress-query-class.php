<?php
/**
 * Handles repetitive database queries.
 *
 * @link       https://agilepress.io
 * @since      0.0.0
 *
 * @package    AgilePress
 * @subpackage AgilePress\includes
 */
namespace vinlandmedia\agilepress;

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

/**
 * This class is used to execute database queries that are needed throughout
 * the AgilePress plugin.  The intention is to not only DRY up the queries but
 * also to provide standardized responses.
 *
 * @author Vinland Media, LLC.
 *
 * @package    AgilePress
 * @subpackage AgilePress\includes
 */
class AgilePress_Query {

    /**
	 * The basic sprint query.
     *
     * Query result contains ID, post_title, and post_name. Published posts only.
	 *
	 * @return object $sprints  An unfiltered list of sprints.
	 *
	 * @global $wpdb
     *
	 * @uses \wordpress\wpdb\get_results()
	 *
	 * @see https://developer.wordpress.org/reference/classes/wpdb/get_results/
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\includes
	 */
    public function get_sprints() {
        global $wpdb;

        $sprints = $wpdb->get_results(
    		"select ID, post_title, post_name from " . $wpdb->posts . " " .
    	   	"where post_type = 'agilepress-sprints' and post_status = 'publish' " .
    		"order by post_name");

        if (!$sprints) {
            $sprints = "";
        }

        return $sprints;
    }

    /**
	 * The basic product query.
     *
     * Query result contains ID, post_title, and post_name. Published posts only.
	 *
	 * @return object $products  An unfiltered list of products.
	 *
	 * @global $wpdb
     *
	 * @uses \wordpress\wpdb\get_results()
	 *
	 * @see https://developer.wordpress.org/reference/classes/wpdb/get_results/
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\includes
	 */
    public function get_products() {
        global $wpdb;

        $products = $wpdb->get_results(
    		"select ID, post_title, post_name from " . $wpdb->posts . " " .
    	   	"where post_type = 'agilepress-products' and post_status = 'publish' " .
    		"order by post_name");

        if (!$products) {
            $products = "";
        }

        return $products;
    }

    /**
	 * The basic story query.
     *
     * Query result contains ID, post_title, and post_name. Published posts only.
	 *
	 * @return object $stories  An unfiltered list of stories (including epics).
	 *
	 * @global $wpdb
     *
	 * @uses \wordpress\wpdb\get_results()
	 *
	 * @see https://developer.wordpress.org/reference/classes/wpdb/get_results/
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\includes
	 */
    public function get_stories() {
        global $wpdb;

        $stories = $wpdb->get_results(
    		"select ID, post_title, post_name from " . $wpdb->posts . " " .
    	   	"where post_type = 'agilepress-stories' and post_status = 'publish' " .
    		"order by post_name");

        if (!$stories) {
            $stories = "";
        }

        return $stories;
    }

    /**
	 * Single-row Query
     *
     * Returns a single row (ID, post_title, post_name, and post_excerpt) given
     * an ID and a post_type.  The latter is extraneous but provides an extra
     * safety layer against getting the wrong post.
	 *
     * @param integer $post_id ID of post being queried
     * @param string $post_type Custom post type of post being queried
	 * @return object $result  A single post row
	 *
	 * @global $wpdb
     *
	 * @uses \wordpress\wpdb\get_row()
	 *
	 * @see https://developer.wordpress.org/reference/classes/wpdb/get_row/
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\includes
	 */
    public function get_single_row($post_id = null, $post_type = null) {
        global $wpdb;

        $result = $wpdb->get_row(
	        "select ID, post_title, post_name, post_excerpt from " . $wpdb->posts . " " .
	        "where post_type = '" . $post_type . "' and ID = '" . $post_id . "'");

        if (!$result) {
            $result = "";
        }

        return $result;
    }

    /**
	 * Row count query
     *
     * Returns a row count given a meta key and value.
	 *
     * @param string $meta_key An AgilePress meta key
     * @param string $meta_value Value for which to search
	 * @return integer $result  The number of rows meeting the criteria
	 *
	 * @global $wpdb
     *
	 * @uses \wordpress\wpdb\get_var()
	 *
	 * @see https://developer.wordpress.org/reference/classes/wpdb/get_var/
	 *
	 * @author Vinland Media, LLC.
	 * @package    AgilePress
	 * @subpackage AgilePress\includes
	 */
    public function get_row_count($meta_key = null, $meta_value = null) {
        global $wpdb;

        $result = $wpdb->get_var(
			"select count(*) " .
			"from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m " .
			"where p.ID = m.post_id and m.meta_key = '" . $meta_key . "' ".
			"and m.meta_value like '%" . $meta_value . "%'"
		);

        if (!$result) {
            $result = 0;
        }

        return $result;
    }

    public function get_products_with_meta() {
        global $wpdb;

        $result = $wpdb->get_results(
            "select p.ID, p.post_title, p.post_name, p.post_excerpt, m.meta_key, m.meta_value " .
            "from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m " .
            "where p.post_type = 'agilepress-products' and p.post_status = 'publish' " .
            "and m.post_id = p.ID " .
            "and m.meta_key = '_agilepress_product_data'"
        );

        if (!$result) {
            $result = "";
        }

        return $result;
    }

    public function get_stories_with_meta() {
        global $wpdb;

        $result = $wpdb->get_results(
            "select p.ID, p.post_title, p.post_name, p.post_excerpt, m.meta_key, m.meta_value " .
            "from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m " .
            "where p.post_type = 'agilepress-stories' and p.post_status = 'publish' " .
            "and m.post_id = p.ID " .
            "and m.meta_key = '_agilepress_story_data'"
        );

        if (!$result) {
            $result = "";
        }

        return $result;
    }

    public function get_tasks_with_meta() {
        global $wpdb;

        $result = $wpdb->get_results(
            "select p.ID, p.post_title, p.post_name, p.post_excerpt, m.meta_key, m.meta_value " .
            "from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m " .
            "where p.post_type = 'agilepress-tasks' and p.post_status = 'publish' " .
            "and m.post_id = p.ID " .
            "and m.meta_key = '_agilepress_task_data'"
        );

        if (!$result) {
            $result = "";
        }

        return $result;
    }

    public function get_sprints_with_meta() {
        global $wpdb;

        $result = $wpdb->get_results(
            "select p.ID, p.post_title, p.post_name, p.post_excerpt, m.meta_key, m.meta_value " .
            "from " . $wpdb->posts . " p, " . $wpdb->postmeta . " m " .
            "where p.post_type = 'agilepress-sprints' and p.post_status = 'publish' " .
            "and m.post_id = p.ID " .
            "and m.meta_key = '_agilepress_sprint_data'"
        );

        if (!$result) {
            $result = "";
        }

        return $result;
    }

    public function get_cpt_by_name($post_name = null, $post_type = null) {
        global $wpdb;

        $result = $wpdb->get_row(
            "select p.ID, p.post_type, p.post_title, p.post_name, p.post_excerpt " .
            "from " . $wpdb->posts . " p " .
            "where p.post_type = '" . $post_type . "' and p.post_status = 'publish' " .
            "and p.post_name = '" . $post_name . "'"
        );

        if (!$result) {
            $result = "";
        }

        return $result;
    }

    public function get_target_sprint($current_product) {
        global $wpdb;

        $sprints = $wpdb->get_results(
            "select ID, post_title, post_name from " . $wpdb->posts . " " .
            "where post_type = 'agilepress-sprints' and post_status = 'publish' " .
            "order by post_modified");

        foreach ($sprints as $sprint) {
            // here we want to find a sprint marked as a backlog target
            $agilepress_sprint_meta = get_post_meta($sprint->ID, '_agilepress_sprint_data', true);
            $start_date = $agilepress_sprint_meta['start_date'];
            $end_date = $agilepress_sprint_meta['end_date'];
            $target = $agilepress_sprint_meta['backlog_target'];
            $product = $agilepress_sprint_meta['product'];

            $next_sprint_id = $sprint->ID;
            $next_sprint_title = $sprint->post_title;
            $next_sprint_name = $sprint->post_name;

            if (($target == 'yes') && ($product == $current_product)) {
                break;
            }
        }

        return $next_sprint_name;
    }

}
