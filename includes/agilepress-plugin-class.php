<?php
/**
 * The plugin's initialization utility class.
 *
 * @link       https://agilepress.io
 * @since      0.0.0
 *
 * @package    AgilePress
 * @subpackage AgilePress\includes
 */
namespace vinlandmedia\agilepress;

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

/**
 * This class defines the custom post types (CPT) used by AgilePress and defines
 * the custom user roles.
 *
 * @author Vinland Media, LLC.
 *
 * @package    AgilePress
 * @subpackage AgilePress\includes
 */
class AgilePress_Init {

    /**
	 * This method defines and registers the Product CPT.
	 *
     * CPT registration uses the $labels array to make sure that the new post
     * type is properly referenced in Wordpress display screens.  It accepts the
     * $args array which defines the attributes of the CPT, such as upon which
     * menu it appears, the icon to use in the menu, which fields to display on
     * the edit screen ('title', 'thumbnail', 'excerpt', 'comments'), etc.
     *
	 * @return array $args  List of arguments required by Wordpress for the CPT
	 *
	 * @author Vinland Media, LLC.
     * @package AgilePress
     * @subpackage AgilePress\includes
	 */
    public function register_product() {
        //register the products custom post type
    	$labels = array(
            'name'               => __( 'Products', 'agilepress' ),
            'singular_name'      => __( 'Product', 'agilepress' ),
            'add_new'            => __( 'Add New', 'agilepress' ),
            'add_new_item'       => __( 'Add New Product', 'agilepress' ),
            'edit_item'          => __( 'Edit Product', 'agilepress' ),
            'new_item'           => __( 'New Product', 'agilepress' ),
            'all_items'          => __( 'All Products', 'agilepress' ),
            'view_item'          => __( 'View Product', 'agilepress' ),
            'search_items'       => __( 'Search Products', 'agilepress' ),
            'not_found'          =>  __( 'No products found', 'agilepress' ),
            'not_found_in_trash' => __( 'No products found in Trash', 'agilepress' ),
            'menu_name'          => __( 'Products', 'agilepress' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            //'show_in_menu'       => true,
    		'show_in_menu'       => 'agilepress-manager-menu',
    		'menu_icon'			 => 'dashicons-cart',
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'show_in_rest'       => true,
      		'rest_base'          => 'agilepress-products',
      		'rest_controller_class' => 'WP_REST_Posts_Controller',
            'supports'           => array( 'title', 'excerpt', 'comments'),
            'taxonomies'         => array( 'category' )
        );

        return $args;
    }

    /**
	 * This method defines and registers the Sprint CPT.
	 *
     * CPT registration uses the $labels array to make sure that the new post
     * type is properly referenced in Wordpress display screens.  It accepts the
     * $args array which defines the attributes of the CPT, such as upon which
     * menu it appears, the icon to use in the menu, which fields to display on
     * the edit screen ('title', 'thumbnail', 'excerpt', 'comments'), etc.
     *
	 * @return array $args  List of arguments required by Wordpress for the CPT
	 *
	 * @author Vinland Media, LLC.
     * @package    AgilePress
     * @subpackage AgilePress\includes
	 */
    public function register_sprint() {
        //register the sprints custom post type
    	$labels = array(
            'name'               => __( 'Sprints', 'agilepress' ),
            'singular_name'      => __( 'Sprint', 'agilepress' ),
            'add_new'            => __( 'Add New', 'agilepress' ),
            'add_new_item'       => __( 'Add New Sprint', 'agilepress' ),
            'edit_item'          => __( 'Edit Sprint', 'agilepress' ),
            'new_item'           => __( 'New Sprint', 'agilepress' ),
            'all_items'          => __( 'All Sprints', 'agilepress' ),
            'view_item'          => __( 'View Sprint', 'agilepress' ),
            'search_items'       => __( 'Search Sprints', 'agilepress' ),
            'not_found'          =>  __( 'No sprints found', 'agilepress' ),
            'not_found_in_trash' => __( 'No sprints found in Trash', 'agilepress' ),
            'menu_name'          => __( 'Sprints', 'agilepress' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
    		//'show_in_menu'       => true,
    		'show_in_menu'       => 'agilepress-manager-menu',
    		'menu_icon'			 => 'dashicons-calendar-alt',
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'show_in_rest'       => true,
      		'rest_base'          => 'agilepress-sprints',
      		'rest_controller_class' => 'WP_REST_Posts_Controller',
            'supports'           => array('title', 'excerpt', 'comments'),
            'taxonomies'         => array('category', 'sprint-products')
        );

        return $args;
    }

    /**
	 * This method defines and registers the Task CPT.
	 *
     * CPT registration uses the $labels array to make sure that the new post
     * type is properly referenced in Wordpress display screens.  It accepts the
     * $args array which defines the attributes of the CPT, such as upon which
     * menu it appears, the icon to use in the menu, which fields to display on
     * the edit screen ('title', 'thumbnail', 'excerpt', 'comments'), etc.
     *
	 * @return array $args  List of arguments required by Wordpress for the CPT
	 *
	 * @author Vinland Media, LLC.
     * @package    AgilePress
     * @subpackage AgilePress\includes
	 */
    public function register_task() {
        //register the tasks custom post type
        $labels = array(
            'name'               => __( 'Tasks', 'agilepress' ),
            'singular_name'      => __( 'Task', 'agilepress' ),
            'add_new'            => __( 'Add New', 'agilepress' ),
            'add_new_item'       => __( 'Add New Task', 'agilepress' ),
            'edit_item'          => __( 'Edit Task', 'agilepress' ),
            'new_item'           => __( 'New Task', 'agilepress' ),
            'all_items'          => __( 'All Tasks', 'agilepress' ),
            'view_item'          => __( 'View Tasks', 'agilepress' ),
            'search_items'       => __( 'Search Tasks', 'agilepress' ),
            'not_found'          =>  __( 'No tasks found', 'agilepress' ),
            'not_found_in_trash' => __( 'No tasks found in Trash', 'agilepress' ),
            'menu_name'          => __( 'Tasks', 'agilepress' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
    		//'show_in_menu'       => true,
    		'show_in_menu'       => 'agilepress-manager-menu',
    		'menu_icon'			 => 'dashicons-calendar-alt',
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'show_in_rest'       => true,
      		'rest_base'          => 'agilepress-tasks',
      		'rest_controller_class' => 'WP_REST_Posts_Controller',
            'supports'           => array('title', 'excerpt', 'comments'),
            'taxonomies'         => array('category', 'task-products', 'task-sprints')
        );

        return $args;
    }

    /**
	 * This method defines and registers the Story CPT.
	 *
     * CPT registration uses the $labels array to make sure that the new post
     * type is properly referenced in Wordpress display screens.  It accepts the
     * $args array which defines the attributes of the CPT, such as upon which
     * menu it appears, the icon to use in the menu, which fields to display on
     * the edit screen ('title', 'thumbnail', 'excerpt', 'comments'), etc.
     *
	 * @return array $args  List of arguments required by Wordpress for the CPT
	 *
	 * @author Vinland Media, LLC.
     * @package    AgilePress
     * @subpackage AgilePress\includes
	 */
    public function register_story() {
        //register the stories custom post type
        $labels = array(
            'name'               => __( 'Stories', 'agilepress' ),
            'singular_name'      => __( 'Story', 'agilepress' ),
            'add_new'            => __( 'Add New', 'agilepress' ),
            'add_new_item'       => __( 'Add New Story', 'agilepress' ),
            'edit_item'          => __( 'Edit Story', 'agilepress' ),
            'new_item'           => __( 'New Story', 'agilepress' ),
            'all_items'          => __( 'All Stories', 'agilepress' ),
            'view_item'          => __( 'View Story', 'agilepress' ),
            'search_items'       => __( 'Search Stories', 'agilepress' ),
            'not_found'          =>  __( 'No stories found', 'agilepress' ),
            'not_found_in_trash' => __( 'No stories found in Trash', 'agilepress' ),
            'menu_name'          => __( 'Stories', 'agilepress' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            //'show_in_menu'       => true,
    		'show_in_menu'       => 'agilepress-manager-menu',
    		'menu_icon'			 => 'dashicons-cart',
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'show_in_rest'       => true,
      		'rest_base'          => 'agilepress-stories',
      		'rest_controller_class' => 'WP_REST_Posts_Controller',
            'supports'           => array('title', 'excerpt', 'comments'),
            'taxonomies'         => array('category', 'story-products')
        );

        return $args;
    }

    /**
	 * This method defines the new user roles used by AgilePress.
     *
     * There are four roles defined here: agilepress_admin, agilepress_developer,
     * agilepress_user, and agilepress_viewer. The roles are designed so that admin has
     * full access, developer can do all but edit and delete things s/he didn't
     * create, and user
	 *
	 * @param string $role  The name of the new role being defined
	 * @return integer $return_code  Returns 0 for good; -1 for bad
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_role/
	 *
	 * @author Vinland Media, LLC.
     * @package    AgilePress
     * @subpackage AgilePress\includes
	 */
    public function setup_roles($role) {
        switch ($role) {
            case 'agilepress_admin':
                add_role(
                    'agilepress_admin',
                    'AgilePress Administrator',
                    [
                        'view_boards'           => true,
                        'view_stories'          => true,
                        'view_products'         => true,
                        'view_tasks'            => true,
                        'view_sprints'          => true,
                        'create_stories'        => true,
                        'create_products'       => true,
                        'create_tasks'		    => true,
                        'create_sprints'        => true,
                        'edit_own_stories'      => true,
                        'edit_own_products'     => true,
                        'edit_own_tasks'        => true,
                        'edit_own_sprints'      => true,
                        'edit_stories'          => true,
                        'edit_products'         => true,
                        'edit_tasks'            => true,
                        'edit_sprints'          => true,
                        'delete_own_stories'	=> true,
                        'delete_own_products'	=> true,
                        'delete_own_tasks'      => true,
                        'delete_own_sprints'	=> true,
                        'delete_stories'        => true,
                        'delete_products'       => true,
                        'delete_tasks'          => true,
                        'delete_sprints'        => true,
                        'transition_tasks'      => true,
                    ]
                );
                $return_code = 0;
                break;

            case 'agilepress_developer':
                add_role(
                    'agilepress_developer',
                    'AgilePress Developer',
                    [
                        'view_boards'           => true,
                        'view_stories'          => true,
                        'view_products'         => true,
                        'view_tasks'            => true,
                        'view_sprints'          => true,
                        'create_stories'        => true,
                        'create_products'       => true,
                        'create_tasks'		    => true,
                        'create_sprints'        => true,
                        'edit_own_stories'      => true,
                        'edit_own_products'     => true,
                        'edit_own_tasks'        => true,
                        'edit_own_sprints'      => true,
                        'edit_stories'          => false,
                        'edit_products'         => false,
                        'edit_tasks'            => false,
                        'edit_sprints'          => false,
                        'delete_own_stories'	=> true,
                        'delete_own_products'	=> true,
                        'delete_own_tasks'      => true,
                        'delete_own_sprints'	=> true,
                        'delete_stories'        => false,
                        'delete_products'       => false,
                        'delete_tasks'          => false,
                        'delete_sprints'        => false,
                        'transition_tasks'      => true,
                    ]
                );
                $return_code = 0;
                break;

            case 'agilepress_user':
                add_role(
                    'agilepress_user',
                    'AgilePress User',
                    [
                        'view_boards'           => true,
                        'view_stories'          => true,
                        'view_products'         => true,
                        'view_tasks'            => true,
                        'view_sprints'          => true,
                        'create_stories'        => false,
                        'create_products'       => false,
                        'create_tasks'		    => false,
                        'create_sprints'        => false,
                        'edit_own_stories'      => false,
                        'edit_own_products'     => false,
                        'edit_own_tasks'        => false,
                        'edit_own_sprints'      => false,
                        'edit_stories'          => false,
                        'edit_products'         => false,
                        'edit_tasks'            => false,
                        'edit_sprints'          => false,
                        'delete_own_stories'	=> false,
                        'delete_own_products'	=> false,
                        'delete_own_tasks'      => false,
                        'delete_own_sprints'	=> false,
                        'delete_stories'        => false,
                        'delete_products'       => false,
                        'delete_tasks'          => false,
                        'delete_sprints'        => false,
                        'transition_tasks'      => true,
                    ]
                );
                $return_code = 0;
                break;

            case 'agilepress_viewer':
                add_role(
                    'agilepress_viewer',
                    'AgilePress Viewer',
                    [
                        'view_boards'           => true,
                        'view_stories'          => true,
                        'view_products'         => true,
                        'view_tasks'            => true,
                        'view_sprints'          => false,
                        'create_stories'        => false,
                        'create_products'       => false,
                        'create_tasks'		    => false,
                        'create_sprints'        => false,
                        'edit_own_stories'      => false,
                        'edit_own_products'     => false,
                        'edit_own_tasks'        => false,
                        'edit_own_sprints'      => false,
                        'edit_stories'          => false,
                        'edit_products'         => false,
                        'edit_tasks'            => false,
                        'edit_sprints'          => false,
                        'delete_own_stories'	=> false,
                        'delete_own_products'	=> false,
                        'delete_own_tasks'      => false,
                        'delete_own_sprints'	=> false,
                        'delete_stories'        => false,
                        'delete_products'       => false,
                        'delete_tasks'          => false,
                        'delete_sprints'        => false,
                        'transition_tasks'      => false,
                    ]
                );
                $return_code = 0;
                break;

            default:
                $return_code = -1;
                break;
        }

        return $return_code;
    }

    /**
	 * Defines and registers custom taxonomies
	 *
     * This method sets up all of the custom taxonomies used by the CPTs in
     * AgilePress. ...
     *
	 * @return null
	 *
	 * @author Vinland Media, LLC.
     * @package    AgilePress
     * @subpackage AgilePress\includes
	 */
    public function register_custom_taxonomies() {
        // create a new taxonomy
    	register_taxonomy(
    		'task-products',
    		'agilepress-tasks',
    		array(
    			'label' => __('Products'),
    			'rewrite' => array('slug' => 'task-products'),
    			'capabilities' => array(
    				'assign_terms' => 'agilepress_admin',
    				'edit_terms' => 'agilepress_admin'
    			)
    		)
    	);

    	register_taxonomy(
    		'sprint-products',
    		'agilepress-sprints',
    		array(
    			'label' => __('Products'),
    			'rewrite' => array('slug' => 'sprint-products'),
    			'capabilities' => array(
    				'assign_terms' => 'agilepress_admin',
    				'edit_terms' => 'agilepress_admin'
    			)
    		)
    	);

    	register_taxonomy(
    		'story-products',
    		'agilepress-stories',
    		array(
    			'label' => __('Products'),
    			'rewrite' => array('slug' => 'story-products'),
    			'capabilities' => array(
    				'assign_terms' => 'agilepress_admin',
    				'edit_terms' => 'agilepress_admin'
    			)
    		)
    	);

    	register_taxonomy(
    		'task-sprints',
    		'agilepress-tasks',
    		array(
    			'label' => __('Sprints'),
    			'rewrite' => array('slug' => 'task-sprints'),
    			'capabilities' => array(
    				'assign_terms' => 'agilepress_admin',
    				'edit_terms' => 'agilepress_admin'
    			)
    		)
    	);

    }
}
