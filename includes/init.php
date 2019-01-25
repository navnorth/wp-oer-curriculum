<?php
/**
 * Initialize the plugin installation
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Create menu item under the OER menu
add_action( 'init' , 'oer_lesson_plan_creation' );

function oer_lesson_plan_creation()
{
    global $_use_gutenberg;
    $labels = array(
        'name'               => _x( 'Curriculum', 'post type general name' ),
        'singular_name'      => _x( 'Curriculum', 'post type singular name' ),
        'add_new'            => _x( 'Create Curriculum', 'book' ),
        'add_new_item'       => __( 'Create Curriculum' ),
        'edit_item'          => __( 'Edit Curriculum' ),
        'new_item'           => __( 'Create Curriculum' ),
         'all_items'          => __( 'All Curriculum' ),
        'view_item'          => __( 'View Curriculum' ),
        'search_items'       => __( 'Search' ),
        'menu_name'          => 'Curriculum'
    );

    $args =array(
        'labels'                => $labels,
        'public'                => true,
        'show_ui'               => true,
        'has_archive'           => true,
        'show_in_menu'          => true,//'edit.php?post_type=resource',
        'public'                => true,
        'publicly_queryable'    => true,
        'exclude_from_search'   => false,
        'query_var'             => true,
        'menu_position'         => 26,
        'menu_icon'             => 'dashicons-welcome-learn-more',
        'taxonomies'            => array('post_tag', 'resource-subject-area'),
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions' ),
        'register_meta_box_cb'  => 'oer_lesson_plan_custom_meta_boxes'
    );

    if ($_use_gutenberg=="on" or $_use_gutenberg=="1")
        $args['show_in_rest'] = true;

    register_post_type( 'lesson-plans', $args);
}

function oer_lesson_plan_custom_meta_boxes()
{
    add_meta_box('oer_lesson_plan_meta_boxid','Lesson Meta Fields','oer_lesson_plan_meta_callback','lesson-plans','advanced');
}

//Meta fields callback
function oer_lesson_plan_meta_callback()
{
    include_once(OER_LESSON_PLAN_PATH.'includes/lesson-plan-meta-fields.php');
}

/**
 * Enqueue the assets into the admin
 * Scripts and styles
 */
add_action('admin_enqueue_scripts', 'oer_lesson_plan_assets');

function oer_lesson_plan_assets()
{
    global $post;

    if(
        (isset($_GET['post_type']) && $_GET['post_type'] == 'lesson-plans') ||
        (isset($post->post_type) && $post->post_type=='lesson-plans')
    )
    {
        wp_enqueue_style('lesson-plan-load-fa', OER_LESSON_PLAN_URL.'assets/lib/font-awesome/css/font-awesome.min.css');
        wp_enqueue_style('lesson-plan-bootstrap', OER_LESSON_PLAN_URL.'assets/lib/bootstrap-3.3.7/css/bootstrap.min.css');
        wp_enqueue_style('admin-lesson-plan', OER_LESSON_PLAN_URL.'assets/css/backend/lesson-plan-style.css');

        //Enqueue script
        wp_enqueue_script('admin-lp-bootstrap', OER_LESSON_PLAN_URL.'assets/lib/bootstrap-3.3.7/js/bootstrap.min.js');
        wp_enqueue_script('lesson-plan', OER_LESSON_PLAN_URL.'assets/js/backend/lesson-plan.js');
    }
}

/**
 * Save post meta fields into the post meta table
 */
add_action('save_post', 'lp_save_custom_fields');
function lp_save_custom_fields()
{
    global $post, $wpdb, $_oer_prefix;

    //Check first if $post is not empty
    if ($post)
    {
        if($post->post_type == 'lesson-plans')
        {
            //Save/update introduction
            if(isset($_POST['oer_lp_introduction']))
            {
                update_post_meta( $post->ID , 'oer_lp_introduction' , $_POST['oer_lp_introduction']);
            }

            //Save/update lesson times
            if(isset($_POST['oer_lp_times_label']))
            {
                update_post_meta( $post->ID , 'oer_lp_times_label' , $_POST['oer_lp_times_label']);
            }

            if(isset($_POST['oer_lp_times_number']))
            {
                update_post_meta( $post->ID , 'oer_lp_times_number' , $_POST['oer_lp_times_number']);
            }

            if(isset($_POST['oer_lp_times_type']))
            {
                update_post_meta( $post->ID , 'oer_lp_times_type' , $_POST['oer_lp_times_type']);
            }

            if (isset($_POST['oer_lp_grades']))
            {
                update_post_meta( $post->ID , 'oer_lp_grades' , $_POST['oer_lp_grades']);
            }

            // Save / update Standard and Objectives
            if(isset($_POST['oer_lp_related_objective']))
            {
                update_post_meta( $post->ID , 'oer_lp_related_objective' , $_POST['oer_lp_related_objective']);
            }

            // Save / update activity in this lesson
            if(isset($_POST['oer_lp_activity_title']))
            {
                update_post_meta( $post->ID , 'oer_lp_activity_title' , $_POST['oer_lp_activity_title']);
            }
            if(isset($_POST['oer_lp_activity_type']))
            {
                update_post_meta( $post->ID , 'oer_lp_activity_type' , $_POST['oer_lp_activity_type']);
            }
            if(isset($_POST['oer_lp_activity_detail']))
            {
                update_post_meta( $post->ID , 'oer_lp_activity_detail' , $_POST['oer_lp_activity_detail']);
            }

            // Save / update assessment
            if(isset($_POST['oer_lp_assessment_type']))
            {
                update_post_meta( $post->ID , 'oer_lp_assessment_type' , $_POST['oer_lp_assessment_type']);
            }
            if(isset($_POST['oer_lp_other_assessment_type']))
            {
                update_post_meta( $post->ID , 'oer_lp_other_assessment_type' , sanitize_text_field($_POST['oer_lp_other_assessment_type']));
            }
            if(isset($_POST['oer_lp_assessment']))
            {
                update_post_meta( $post->ID , 'oer_lp_assessment' , $_POST['oer_lp_assessment']);
            }
        }
    }
}

// Ajax Requests
/**
 * chat form submit
 */
add_action('wp_ajax_lp_add_more_activity_callback', 'lp_add_more_activity_callback');
add_action('wp_ajax_nopriv_lp_add_more_activity_callback','lp_add_more_activity_callback');

function lp_add_more_activity_callback()
{
    $totalElements = isset($_REQUEST['row_id']) ? $_REQUEST['row_id'] : '15';
    $content = '<div class="panel panel-default lp-ac-item" id="lp-ac-item-'.$totalElements.'">
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label>Activity Title</label>
                                <input type="text" name="oer_lp_activity_title[]" class="form-control" placeholder="Activity Title">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="activity-title">Activity Title</label>
                                <select name="oer_lp_activity_type[]" class="form-control">
                                    <option value=""> - Activity Type -</option>
                                    <option value="hooks_set">Hooks / Set</option>
                                    <option value="lecture">Lecture</option>
                                    <option value="demonstration">Demo / Modeling</option>
                                    <option value="independent_practice">Independent Practice</option>
                                    <option value="guided_practice">Guided Practice</option>
                                    <option value="check_understanding">Check Understanding</option>
                                    <option value="lab_shop">Lab / Shop</option>
                                    <option value="group_work">Group Work</option>
                                    <option value="projects">Projects</option>
                                    <option value="assessment">Formative Assessment</option>
                                    <option value="closure">Closure</option>
                                    <option value="research">Research / Annotate</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">';
                            ob_start(); // Start output buffer
                            wp_editor( '',
                                'oer-lp-activity-detail-'.$totalElements,
                                $settings = array(
                                    'textarea_name' => 'oer_lp_activity_detail[]',
                                    'media_buttons' => true,
                                    'textarea_rows' => 10,
                                    'drag_drop_upload' => true,
                                    'teeny' => true,
                                )
                            );
                            $content .= ob_get_clean();
            $content .= '</div>
                    </div>
                </div>';

    echo $content;
    exit();
}
