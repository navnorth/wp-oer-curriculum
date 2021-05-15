<?php
/**
 * Initialize the plugin installation
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Create menu item under the OER menu
add_action('init', 'oercurr_create_menu_item');

function oercurr_create_menu_item() {
    global $_use_gutenberg;
    $labels = array(
        'name'          => _x('Curriculum', 'post type general name'),
        'singular_name' => _x('Curriculum', 'post type singular name'),
        'add_new'       => _x('Add New Curriculum', 'book'),
        'add_new_item'  => __('Add New Curriculum'),
        'edit_item'     => __('Edit Curriculum'),
        'new_item'      => __('Create Curriculum'),
        'all_items'     => __('All Curriculum'),
        'view_item'     => __('View Curriculum'),
        'search_items'  => __('Search'),
        'menu_name'     => 'Curriculum'
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'show_ui'               => true,
        'has_archive'           => false,
        'show_in_menu'          => true,//'edit.php?post_type=resource',
        'public'                => true,
        'publicly_queryable'    => true,
        'exclude_from_search'   => false,
        'query_var'             => true,
        'menu_position'         => 26,
        'menu_icon'             => 'dashicons-welcome-learn-more',
        'taxonomies'            => array('post_tag', 'resource-subject-area'),
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions'),
        'register_meta_box_cb'  => 'oercurr_custom_meta_boxes',
        'show_in_rest'          => true
    );

    register_post_type('oer-curriculum', $args);
    
    if(!get_option('oer_curriculum_type_curmetset_label')){oercurr_add_setting_options('oer_curriculum_type','label','Type');}
    if(!get_option('oer_curriculum_type_other_curmetset_label')){oercurr_add_setting_options('oer_curriculum_type_other','label','Other Type');}
    if(!get_option('oer_curriculum_authors_curmetset_label')){oercurr_add_setting_options('oer_curriculum_authors','label','Author');}
    if(!get_option('oer_curriculum_primary_resources_curmetset_label')){oercurr_add_setting_options('oer_curriculum_primary_resources','label','Primary Resources');}
    if(!get_option('oer_curriculum_iq_curmetset_label')){oercurr_add_setting_options('oer_curriculum_iq','label','Investigative Question');}
    if(!get_option('oer_curriculum_required_materials_curmetset_label')){oercurr_add_setting_options('oer_curriculum_required_materials','label','Required Equipment Materials');}
    if(!get_option('oer_curriculum_additional_sections_curmetset_label')){oercurr_add_setting_options('oer_curriculum_additional_sections','label','Additional Sections');}
    if(!get_option('oer_curriculum_grades_curmetset_label')){oercurr_add_setting_options('oer_curriculum_grades','label','Grade Level');}
    if(!get_option('oer_curriculum_age_levels_curmetset_label')){oercurr_add_setting_options('oer_curriculum_age_levels','label','Appropriate Age Levels');}
    if(!get_option('oer_curriculum_suggested_instructional_time_curmetset_label')){oercurr_add_setting_options('oer_curriculum_suggested_instructional_time','label','Suggested Instructional Time');}
    if(!get_option('oer_curriculum_standards_curmetset_label')){oercurr_add_setting_options('oer_curriculum_standards','label','Standards');}
    if(!get_option('oer_curriculum_related_objective_curmetset_label')){oercurr_add_setting_options('oer_curriculum_related_objective','label','Related Instructional Objectives (SWBAT...)');}
    //if(!get_option('oer_curriculum_order_curmetset_label')){oercurr_add_setting_options('oer_curriculum_order','label','Order');}
    if(!get_option('oer_curriculum_download_copy_curmetset_label')){oercurr_add_setting_options('oer_curriculum_download_copy','label','Download Copy');}
    //if(!get_option('oer_curriculum_download_copy_document_curmetset_label')){oercurr_add_setting_options('oer_curriculum_download_copy_document','label','Download Copy Document');}
    if(!get_option('oer_curriculum_oer_materials_curmetset_label')){oercurr_add_setting_options('oer_curriculum_oer_materials','label','Additional Materials');}
    if(!get_option('oer_curriculum_related_curriculum_curmetset_label')){oercurr_add_setting_options('oer_curriculum_related_curriculum','label','Related Curriculum');}
    if(!get_option('oer_curriculum_related_curriculum_1_curmetset_label')){oercurr_add_setting_options('oer_curriculum_related_curriculum_1','label','Related Curriculum 1');}
    if(!get_option('oer_curriculum_related_curriculum_2_curmetset_label')){oercurr_add_setting_options('oer_curriculum_related_curriculum_2','label','Related Curriculum 2');}
    if(!get_option('oer_curriculum_related_curriculum_3_curmetset_label')){oercurr_add_setting_options('oer_curriculum_related_curriculum_3','label','Related Curriculum 3');}


    if(!get_option('oer_curriculum_type_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_type','enable','checked');}
    if(!get_option('oer_curriculum_type_other_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_type_other','enable','checked');}
    if(!get_option('oer_curriculum_authors_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_authors','enable','checked');}
    if(!get_option('oer_curriculum_primary_resources_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_primary_resources','enable','checked');}
    if(!get_option('oer_curriculum_iq_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_iq','enable','checked');}
    if(!get_option('oer_curriculum_required_materials_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_required_materials','enable','checked');}
    if(!get_option('oer_curriculum_additional_sections_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_additional_sections','enable','checked');}
    if(!get_option('oer_curriculum_grades_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_grades','enable','checked');}
    if(!get_option('oer_curriculum_age_levels_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_age_levels','enable','checked');}
    if(!get_option('oer_curriculum_suggested_instructional_time_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_suggested_instructional_time','enable','checked');}
    if(!get_option('oer_curriculum_standards_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_standards','enable','checked');}
    if(!get_option('oer_curriculum_related_objective_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_related_objective','enable','checked');}
    //if(!get_option('oer_curriculum_order_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_order','enable','checked');}
    if(!get_option('oer_curriculum_download_copy_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_download_copy','enable','checked');}
    //if(!get_option('oer_curriculum_download_copy_document_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_download_copy_document','enable','checked');}
    if(!get_option('oer_curriculum_oer_materials_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_oer_materials','enable','checked');}
    if(!get_option('oer_curriculum_related_curriculum_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_related_curriculum','enable','checked');}
    if(!get_option('oer_curriculum_related_curriculum_1_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_related_curriculum_1','enable','checked');}
    if(!get_option('oer_curriculum_related_curriculum_2_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_related_curriculum_2','enable','checked');}
    if(!get_option('oer_curriculum_related_curriculum_3_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_related_curriculum_3','enable','checked');}
    
}

function oercurr_custom_meta_boxes() {
    // Grade Levels
    $grade_levels_set = (trim(get_option('oer_curriculum_grades_curmetset_label'),' ') != '')?true:false;
    $grade_levels_enabled = (get_option('oer_curriculum_grades_curmetset_enable')=='checked')?true:false;
    if ($grade_levels_enabled) {
      add_meta_box( 'oer_curriculum_meta_grades', get_option('oer_curriculum_grades_curmetset_label'), 'oercurr_grade_level_callback', 'oer-curriculum', 'side', 'high' );
    }
    
    // Appropriate Age Levels
    $age_levels_set = (trim(get_option('oer_curriculum_age_levels_curmetset_label'),' ') != '')?true:false;
    $age_levels_enabled = (get_option('oer_curriculum_age_levels_curmetset_enable')=='checked')?true:false;
    if ($age_levels_enabled) {
        $label = get_option('oer_curriculum_age_levels_curmetset_label');
        add_meta_box( 'oer_curriculum_meta_age_levels', $label , 'oercurr_age_levels_callback', 'oer-curriculum', 'side', 'high' );
    }

    //Suggested Instructional Time
    $suggested_time_set = (trim(get_option('oer_curriculum_suggested_instructional_time_curmetset_label'),' ') != '')?true:false;
    $suggested_time_enabled = (get_option('oer_curriculum_suggested_instructional_time_curmetset_enable')=='checked')?true:false;
    if (($suggested_time_set && $suggested_time_enabled) || !$suggested_time_set) {
        $label = get_option('oer_curriculum_suggested_instructional_time_curmetset_label');
        add_meta_box( 'oer_curriculum_meta_suggested_time', $label, 'oercurr_suggested_time_callback', 'oer-curriculum', 'side', 'high' );
    }
    add_meta_box('oer_curriculum_meta_boxid', 'Lesson Meta Fields', 'oercurr_meta_fields_callback', 'oer-curriculum', 'advanced');

    // Add a download copy option
    $download_copy_set = (trim(get_option('oer_curriculum_download_copy_curmetset_label'),' ') != '')?true:false;
    $download_copy_enabled = (get_option('oer_curriculum_download_copy_curmetset_enable')=='checked')?true:false;
    if($download_copy_enabled){
        $label = get_option('oer_curriculum_download_copy_curmetset_label');
      add_meta_box( 'oer_curriculum_meta_download_copy', $label , 'oercurr_download_copy_callback', 'oer-curriculum', 'side', 'high' );
    }
    // Add Related Curriculum metabox
    $related_curriculum_set = (trim(get_option('oer_curriculum_related_curriculum_curmetset_label'),' ') != '')?true:false;
    $related_curriculum_enabled = (get_option('oer_curriculum_related_curriculum_curmetset_enable') == 'checked')?true:false;
    if (($related_curriculum_set && $related_curriculum_enabled) || !$related_curriculum_set) {
        if (!$related_curriculum_set){
            $related_curriculum_enabled = true;
        } else {
            for ($i=1;$i<=3;$i++){
                $enabled = (get_option('oer_curriculum_related_curriculum_'.$i.'_enabled'))?true:false;
                if ($enabled) {
                    $related_curriculum_enabled = true;
                    break;
                }
            }
        }
        if ($related_curriculum_enabled) {
            $label = oercurr_get_field_label('oer_curriculum_related_curriculum');
            add_meta_box('oer_curriculum_meta_related', $label, 'oercurr_related_curriculum_callback', 'oer-curriculum', 'advanced');
        }
    }
}

//Meta fields callback
function oercurr_meta_fields_callback() {
    include_once(OERCURR_CURRICULUM_PATH . 'includes/oer-curriculum-meta-fields.php');
}

// Related Curriculum Callback
function oercurr_related_curriculum_callback(){
    include_once(OERCURR_CURRICULUM_PATH . 'includes/oer-curriculum-related-curriculum.php');
}

// Age Levels Callback
function oercurr_age_levels_callback(){
    global $post;
    
    $post_meta_data = get_post_meta($post->ID );
    $oer_curriculum_age_levels = (isset($post_meta_data['oer_curriculum_age_levels'][0]) ? $post_meta_data['oer_curriculum_age_levels'][0] : "");
    
    echo '<div class="form-group oer_curriculum_age_levels">';
    echo '<div class="input-group full-width">';
    echo '<input type="text" class="form-control" name="oer_curriculum_age_levels" placeholder="Age Levels" value="'. $oer_curriculum_age_levels .'">';
    echo '</div>';
    echo '</div>';
}

// Suggested Instructional Time Callback
function oercurr_suggested_time_callback(){
    global $post;
    
    $post_meta_data = get_post_meta($post->ID );
    $oer_curriculum_suggested_time = (isset($post_meta_data['oer_curriculum_suggested_instructional_time'][0]) ? $post_meta_data['oer_curriculum_suggested_instructional_time'][0] : "");
    
    echo '<div class="form-group oer_curriculum_age_levels">';
    echo '<div class="input-group full-width">';
    echo '<input type="text" class="form-control" name="oer_curriculum_suggested_instructional_time" placeholder="Suggested Time" value="'. $oer_curriculum_suggested_time .'">';
    echo '</div>';
    echo '</div>';
}

/**
 * Display the grade level into the side bar
 */
function oercurr_grade_level_callback() {
    global $post;
    $post_meta_data = get_post_meta($post->ID );
    $oer_curriculum_grade_options = array(
        'pre-k' => 'Pre-K',
        'k' => 'K (Kindergarten)',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
        '11' => '11',
        '12' => '12'
    );
    $oer_curriculum_grades = (isset($post_meta_data['oer_curriculum_grades'][0]) ? unserialize($post_meta_data['oer_curriculum_grades'][0]) : array());
    $index = 0;
    echo '<div class="row oer_curriculum_grades">';
    foreach ($oer_curriculum_grade_options as $key => $oer_curriculum_grade_option) {
        $index++;
        $checkbox = "";
        if ($index % 7 == 1){
            if ($index<7)
                $checkbox .= '<div class="col-md-7 span2">';
            else
                $checkbox .= '<div class="col-md-5 span2">';
        }
        $checkbox .= '<div class="form-checkbox">';
        $checkbox .= '<input type="checkbox" name="oer_curriculum_grades[]" value="'.$key.'" id="oer_curriculum_grade_'.$key.'" '.oercurr_show_selected($key, $oer_curriculum_grades, 'checkbox').'>';
        $checkbox .= '<label class="oer_curriculum_radio_label" for="oer_curriculum_grade_'.$key.'">'.$oer_curriculum_grade_option.'</label>';
        $checkbox .= '</div>';
        if ($index % 7 == 0 )
            $checkbox .= '</div>';
        echo $checkbox;
    }
    echo '</div>';
}

/**
 * Add a checkbox option to the sidebar
 * To download file
 */
function oercurr_download_copy_callback() {
    global $post;
    $post_meta_data = get_post_meta($post->ID );
    $icon = null;
    
    // Upload document
    $oer_curriculum_download_copy_document = (isset($post_meta_data['oer_curriculum_download_copy_document'][0]) ? $post_meta_data['oer_curriculum_download_copy_document'][0] : '');
    // Icon
    if (!empty($oer_curriculum_download_copy_document)) {
        $icon = oercurr_get_file_type_from_url($oer_curriculum_download_copy_document);
        $icon = $icon['icon'];
    } else {
        $icon = '<i class="fa fa-upload"></i>';
    }
    $checkbox = '<div class="form-group">';
    $checkbox .= '<div class="input-group full-width">';
    $checkbox .= '<input type="hidden" class="form-control" name="oer_curriculum_download_copy_document" placeholder="Select Document" value="'.$oer_curriculum_download_copy_document.'">';
    if (!empty($oer_curriculum_download_copy_document)){
        $checkbox .= '<div class="oercurr-selected-section"><a href="'.esc_url($oer_curriculum_download_copy_document).'" target="_blank">'.$oer_curriculum_download_copy_document.'</a> <span class="oercurr-remove-download-copy" title="Remove copy"><i class="fas fa-trash-alt"></i></span></div>';
        $checkbox .= '<span class="oercurr-select-label oercurr-hidden">Select Document</span> <div class="input-group-addon oercurr-download-copy-icon oercurr-hidden" title="Select Material">'.$icon.'</div>';   
    } else {
        $checkbox .= '<div class="oercurr-selected-section oercurr-hidden"><a href="" target="_blank"></a> <span class="oercurr-remove-download-copy"><i class="fas fa-trash-alt"></i></span></div>';
        $checkbox .= '<span class="oercurr-select-label">Select Document</span> <div class="input-group-addon oercurr-download-copy-icon" title="Select Material">'.$icon.'</div>';
    }
    $checkbox .= '</div></div>';
    echo $checkbox;
}



/**
 * Enqueue the assets into the admin
 * Scripts and styles
 */
add_action('admin_enqueue_scripts', 'oercurr_enqueue_admin_assets');

function oercurr_enqueue_admin_assets() {
    
    global $post;
    wp_enqueue_editor();
    if (
        (isset($_GET['post_type']) && $_GET['post_type'] == 'oer-curriculum') ||
        (isset($post->post_type) && $post->post_type == 'oer-curriculum')
    ) {
        wp_enqueue_style('oercurr-load-fa', OERCURR_CURRICULUM_URL . 'lib/fontawesome/css/all.min.css');
        //wp_enqueue_style('oercurr-bootstrap', OERCURR_CURRICULUM_URL . 'lib/bootstrap/css/bootstrap.min.css');
        wp_enqueue_style('oercurr-admin-style', OERCURR_CURRICULUM_URL . 'css/backend/oer-curriculum-style.css');
        wp_enqueue_style('oercurr-resource-selector-style', OERCURR_CURRICULUM_URL . 'css/backend/oer-curriculum-resource-selector-style.css', array() , null, 'all');
        
        
        //Enqueue script
        if (!wp_script_is('oercurr-admin-bootstrap', 'enqueued')) {
            wp_enqueue_script('oercurr-admin-bootstrap', OERCURR_CURRICULUM_URL . 'lib/bootstrap/js/bootstrap.min.js',array('jquery') , null, true);
        }
        
        wp_register_script('oercurr-script', OERCURR_CURRICULUM_URL . 'js/backend/oer-curriculum.js');
        wp_localize_script('oercurr-script','lpScript', array("image_placeholder_url" => OERCURR_CURRICULUM_URL.'images/oer-curriculum-person-placeholder.png'));
        wp_enqueue_script('oercurr-script');
        wp_enqueue_script('oercurr-resource-selector-script', OERCURR_CURRICULUM_URL . 'js/backend/oer-curriculum-resource-selector.js' , array('jquery') , null, true);      
        //wp_enqueue_script('oercurr-cfb-admin-jqueryui-core', admin_url( 'wp-includes/js/jquery/ui/core.min.js' ) ,array('jquery') , null, true);
        wp_enqueue_script('oercurr-cfb-admin-jqueryui-sortable', admin_url( 'wp-includes/js/jquery/ui/sortable.min.js' ) ,array('jquery, oercurr-cfb-admin-jqueryui-core') , null, true);
  }
    
}

function oercurr_dequeue_oer_scripts(){
  global $post;
  if (
      (isset($_GET['post_type']) && $_GET['post_type'] == 'oer-curriculum') ||
      (isset($post->post_type) && $post->post_type == 'oer-curriculum')
  ) {
    wp_dequeue_style( 'bootstrap-style' );
    wp_deregister_style( 'bootstrap-style' );
  }
}
global $pagenow;
if ($pagenow=="post.php" || $pagenow=="edit.php" || $pagenow=="post-new.php"){
	//add_action('admin_enqueue_scripts', 'oercurr_dequeue_oer_scripts',999);
}

/**
 * Enqueue the scripts and style into the frontend
 */
add_action('wp_enqueue_scripts', 'oercurr_enqueue_frontend_scripts_and_styles');
if (!function_exists('oercurr_enqueue_frontend_scripts_and_styles')) {
    function oercurr_enqueue_frontend_scripts_and_styles() {
        global $post;
        if (
            (isset($_GET['post_type']) && $_GET['post_type'] == 'oer-curriculum') ||
            (isset($post->post_type) && $post->post_type == 'oer-curriculum')
        ) {
            //Enqueue script
            if (!wp_script_is('bootstrap-js', 'enqueued')) {
                wp_enqueue_script('bootstrap-js', OERCURR_CURRICULUM_URL . 'lib/bootstrap/js/bootstrap.min.js',array('jquery') , null, true);
            }

            if (!wp_style_is('oercurr-load-fa', 'enqueued') && 
                !wp_style_is('fontawesome-style', 'enqueued') && 
                !wp_style_is('fontawesome', 'enqueued')) {
                wp_enqueue_style('oercurr-load-fa', OERCURR_CURRICULUM_URL . 'lib/fontawesome/css/all.min.css');
            }
            wp_enqueue_style('oercurr-bootstrap', OERCURR_CURRICULUM_URL . 'lib/bootstrap/css/bootstrap.min.css');
            wp_enqueue_style('oercurr-style', OERCURR_CURRICULUM_URL . 'css/frontend/oer-curriculum-style.css');
            wp_enqueue_script('oercurr-script', OERCURR_CURRICULUM_URL . 'js/frontend/oer-curriculum-script.js', array('jquery'));
            wp_localize_script( 'oercurr-script', 'oer_curriculum_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        }
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_style('oercurr-load-fa', OERCURR_CURRICULUM_URL . 'lib/fontawesome/css/all.min.css');
    }
}

/**
 * Save post meta fields into the post meta table
 */
add_action('save_post', 'oercurr_save_custom_fields');
function oercurr_save_custom_fields() {
    global $post, $wpdb, $_oer_prefix; $allowedposttags;
    $_oercurr_allowed_html = oercurr_allowed_html();
    //Check first if $post is not empty
    if ($post) {
        if ($post->post_type == 'oer-curriculum') {
            //Save/update Type
            if (isset($_POST['oer_curriculum_type'])) {
                update_post_meta($post->ID, 'oer_curriculum_type', sanitize_text_field($_POST['oer_curriculum_type']));
            }
            
            //Save/update Other Type
            if (isset($_POST['oer_curriculum_type_other'])) {
                update_post_meta($post->ID, 'oer_curriculum_type_other', sanitize_text_field($_POST['oer_curriculum_type_other']));
            }
            
            //Save/update introduction
            if (isset($_POST['oer_curriculum_introduction'])) {
                update_post_meta($post->ID, 'oer_curriculum_introduction', sanitize_text_field($_POST['oer_curriculum_introduction']));
            }

            // Save authors data
            
            if (isset($_POST['oer_curriculum_authors'])) {    
                $_POST['oer_curriculum_authors']['name'][0] = sanitize_text_field($_POST['oer_curriculum_authors']['name'][0]);
                $_POST['oer_curriculum_authors']['role'][0] = sanitize_text_field($_POST['oer_curriculum_authors']['role'][0]);
                $_POST['oer_curriculum_authors']['author_url'][0] = esc_url_raw($_POST['oer_curriculum_authors']['author_url'][0]);
                $_POST['oer_curriculum_authors']['institution'][0] = sanitize_text_field($_POST['oer_curriculum_authors']['institution'][0]);
                $_POST['oer_curriculum_authors']['institution_url'][0] = esc_url_raw($_POST['oer_curriculum_authors']['institution_url'][0]);
                $_POST['oer_curriculum_authors']['author_pic'][0] = esc_url_raw($_POST['oer_curriculum_authors']['author_pic'][0]);
                update_post_meta($post->ID, 'oer_curriculum_authors', $_POST['oer_curriculum_authors']);
            }
            
            // Save Standards
            if (isset($_POST['oer_curriculum_standards'])) {
                update_post_meta($post->ID, 'oer_curriculum_standards', sanitize_text_field($_POST['oer_curriculum_standards']));
            }
            
            // Save / update Standard and Objectives
            if (isset($_POST['oer_curriculum_related_objective'])) {
                foreach($_POST['oer_curriculum_related_objective'] as $key=>$value){
                  $_POST['oer_curriculum_related_objective'][$key] = sanitize_text_field($_POST['oer_curriculum_related_objective'][$key]);
                }
                update_post_meta($post->ID, 'oer_curriculum_related_objective', $_POST['oer_curriculum_related_objective']);
            }
            
            // Save Investigative Question
            if (isset($_POST['oer_curriculum_iq'])) {
                $_POST['oer_curriculum_iq']['question'] = sanitize_text_field($_POST['oer_curriculum_iq']['question']);
                $_POST['oer_curriculum_iq']['excerpt'] = wp_kses(stripslashes_deep($_POST['oer_curriculum_iq']['excerpt']),$_oercurr_allowed_html);        
                update_post_meta($post->ID, 'oer_curriculum_iq', $_POST['oer_curriculum_iq']);
            }

            // Save Required Equipment Materials
            if (isset($_POST['oer_curriculum_required_materials'])) {
                foreach($_POST['oer_curriculum_required_materials'] as $key=>$value){
                  foreach($_POST['oer_curriculum_required_materials'][$key] as $subkey=>$subvalue){
                    if($key == 'editor'){
                      $_POST['oer_curriculum_required_materials'][$key][$subkey] = wp_kses(stripslashes_deep($_POST['oer_curriculum_required_materials'][$key][$subkey]),$_oercurr_allowed_html);
                    }else{
                      $_POST['oer_curriculum_required_materials'][$key][$subkey] = sanitize_text_field($_POST['oer_curriculum_required_materials'][$key][$subkey]);
                    }
                  }
                }
                update_post_meta($post->ID, 'oer_curriculum_required_materials', $_POST['oer_curriculum_required_materials']);
            }
            
            // Save Additional Sections
            if (isset($_POST['oer_curriculum_additional_sections'])) {                
                foreach($_POST['oer_curriculum_additional_sections'] as $key=>$value){
                    foreach($_POST['oer_curriculum_additional_sections'][$key] as $subkey=>$subvalue){
                        if($key == 'label'){$_POST['oer_curriculum_additional_sections'][$key][$subkey] = sanitize_text_field($_POST['oer_curriculum_additional_sections'][$key][$subkey]);}
                        if($key == 'editor'){$_POST['oer_curriculum_additional_sections'][$key][$subkey] = wp_kses(stripslashes_deep($_POST['oer_curriculum_additional_sections'][$key][$subkey]),$_oercurr_allowed_html);}
                    }  
                }
                update_post_meta($post->ID, 'oer_curriculum_additional_sections', $_POST['oer_curriculum_additional_sections']);
            }
            
            // Save primary resource
            if (isset($_POST['oer_curriculum_primary_resources'])) {
                foreach($_POST['oer_curriculum_primary_resources'] as $key=>$value){
                    foreach($_POST['oer_curriculum_primary_resources'][$key] as $subkey=>$subvalue){
                      if($key == 'image'){$_POST['oer_curriculum_primary_resources'][$key][$subkey] = esc_url_raw($_POST['oer_curriculum_primary_resources'][$key][$subkey]);}
                      if($key == 'resource'){$_POST['oer_curriculum_primary_resources'][$key][$subkey] = sanitize_text_field($_POST['oer_curriculum_primary_resources'][$key][$subkey]);}
                      if($key == 'field_type'){$_POST['oer_curriculum_primary_resources'][$key][$subkey] = sanitize_text_field($_POST['oer_curriculum_primary_resources'][$key][$subkey]);}
                      if($key == 'sensitive_material_value'){$_POST['oer_curriculum_primary_resources'][$key][$subkey] = sanitize_text_field($_POST['oer_curriculum_primary_resources'][$key][$subkey]);} 
                      if($key == 'title'){$_POST['oer_curriculum_primary_resources'][$key][$subkey] = sanitize_text_field($_POST['oer_curriculum_primary_resources'][$key][$subkey]);}
                      if($key == 'description'){$_POST['oer_curriculum_primary_resources'][$key][$subkey] = wp_kses(stripslashes_deep($_POST['oer_curriculum_primary_resources'][$key][$subkey]),$_oercurr_allowed_html);}
                    }
                }
                update_post_meta($post->ID, 'oer_curriculum_primary_resources', $_POST['oer_curriculum_primary_resources']);
            } else {
                if (get_post_meta($post->ID, 'oer_curriculum_primary_resources'))
                    delete_post_meta($post->ID, 'oer_curriculum_primary_resources');
            }
              
            // Save materials
            if (isset($_POST['oer_curriculum_oer_materials'])) {
              foreach($_POST['oer_curriculum_oer_materials'] as $key=>$value){
                  foreach($_POST['oer_curriculum_oer_materials'][$key] as $subkey=>$subvalue){
                    if($key == 'url'){$_POST['oer_curriculum_oer_materials'][$key][$subkey] = esc_url_raw($_POST['oer_curriculum_oer_materials'][$key][$subkey]);}
                    if($key == 'title'){$_POST['oer_curriculum_oer_materials'][$key][$subkey] = sanitize_text_field($_POST['oer_curriculum_oer_materials'][$key][$subkey]);}
                    if($key == 'description'){$_POST['oer_curriculum_oer_materials'][$key][$subkey] = wp_kses(stripslashes_deep($_POST['oer_curriculum_oer_materials'][$key][$subkey]),$_oercurr_allowed_html);}
                  }
              }              
              update_post_meta($post->ID, 'oer_curriculum_oer_materials', $_POST['oer_curriculum_oer_materials']);
            }
            
            if (isset($_POST['oer_curriculum_grades'])) {
                foreach($_POST['oer_curriculum_grades'] as $key=>$value){
                  if($key == 'title'){$_POST['oer_curriculum_grades'][$key] = sanitize_text_field($_POST['oer_curriculum_grades'][$key]);}
                }
                update_post_meta($post->ID, 'oer_curriculum_grades', $_POST['oer_curriculum_grades']);
            }else{
                update_post_meta($post->ID, 'oer_curriculum_grades', false);
            }
            
            // Update Appropriate Age Levels
            if (isset($_POST['oer_curriculum_age_levels'])) {
                update_post_meta($post->ID, 'oer_curriculum_age_levels', sanitize_text_field($_POST['oer_curriculum_age_levels']));
            }
            
            // Update Suggested Instructional Time
            if (isset($_POST['oer_curriculum_suggested_instructional_time'])) {
                update_post_meta($post->ID, 'oer_curriculum_suggested_instructional_time', sanitize_text_field($_POST['oer_curriculum_suggested_instructional_time']));
            }
            
            // Save custom editor fields
            if (isset($_POST['oer_curriculum_custom_editor'])) {
                update_post_meta($post->ID, 'oer_curriculum_custom_editor', $_POST['oer_curriculum_custom_editor']);
            }

            // Save custom modules
            if (isset($_POST['oer_curriculum_order'])) {
                foreach ($_POST['oer_curriculum_order'] as $moduleKey => $order) {
                    if (isset($_POST[$moduleKey])) {
                        update_post_meta($post->ID, $moduleKey, $_POST[$moduleKey]);
                        // Check for vocabulary and save the vocabulary details
                        /*
                        if (strpos($moduleKey, 'oer_curriculum_vocabulary_list_title_') !== false) {
                            $listOrder = end(explode('_', $moduleKey));
                            if (isset($_POST['oer_curriculum_vocabulary_details_' . $listOrder])) {
                                update_post_meta($post->ID, 'oer_curriculum_vocabulary_details_' . $listOrder, $_POST['oer_curriculum_vocabulary_details_' . $listOrder]);
                            }
                        }
                        */
                    }
                }
            }
            
            
             // Save Required Equipment Materials Label
            /*
            if (isset($_POST['oer_curriculum_required_materials_label'])) {
                update_post_meta($post->ID, 'oer_curriculum_required_materials_label', $_POST['oer_curriculum_required_materials_label']);
            }

             // Save Additional Sections Label
            if (isset($_POST['oer_curriculum_additional_sections_label'])) {
                update_post_meta($post->ID, 'oer_curriculum_additional_sections_label', $_POST['oer_curriculum_additional_sections_label']);
            }

            //Save/update lesson times
            if (isset($_POST['oer_curriculum_times_label'])) {
                update_post_meta($post->ID, 'oer_curriculum_times_label', $_POST['oer_curriculum_times_label']);
            }

            if (isset($_POST['oer_curriculum_times_number'])) {
                update_post_meta($post->ID, 'oer_curriculum_times_number', $_POST['oer_curriculum_times_number']);
            }

            if (isset($_POST['oer_curriculum_times_type'])) {
                update_post_meta($post->ID, 'oer_curriculum_times_type', $_POST['oer_curriculum_times_type']);
            }

            // Save / update activity in this lesson    
            if (isset($_POST['oer_curriculum_activity_title'])) {
                update_post_meta($post->ID, 'oer_curriculum_activity_title', $_POST['oer_curriculum_activity_title']);
            }
              
            // Save activity types
            if (isset($_POST['oer_curriculum_activity_type'])) {
                update_post_meta($post->ID, 'oer_curriculum_activity_type', $_POST['oer_curriculum_activity_type']);
            }
            
            // Save activity details
            if (isset($_POST['oer_curriculum_activity_detail'])) {
                update_post_meta($post->ID, 'oer_curriculum_activity_detail', $_POST['oer_curriculum_activity_detail']);
            }
            
            // Save / update assessment
            if (isset($_POST['oer_curriculum_assessment_type'])) {
                update_post_meta($post->ID, 'oer_curriculum_assessment_type', $_POST['oer_curriculum_assessment_type']);
            }

            // Save assessment type
            if (isset($_POST['oer_curriculum_other_assessment_type'])) {
                update_post_meta($post->ID, 'oer_curriculum_other_assessment_type', sanitize_text_field($_POST['oer_curriculum_other_assessment_type']));
            }

            // Save assessment
            if (isset($_POST['oer_curriculum_assessment'])) {
                update_post_meta($post->ID, 'oer_curriculum_assessment', $_POST['oer_curriculum_assessment']);
            }
            */
            
            // Save Additional Text Features
            if (isset($_POST['oer_curriculum_text_feature'])){
                update_post_meta($post->ID, 'oer_curriculum_text_feature', sanitize_text_field($_POST['oer_curriculum_text_feature']));
            }

            // Save elements Order
            if (isset($_POST['oer_curriculum_order'])) {            
              foreach ($_POST['oer_curriculum_order'] as $key => $value) {
                $_POST['oer_curriculum_order'][$key] = sanitize_text_field($_POST['oer_curriculum_order'][$key]);
              }
              update_post_meta($post->ID, 'oer_curriculum_order', $_POST['oer_curriculum_order']);
            }
            
            
            //Save download file options
            if (isset($_POST['oer_curriculum_download_copy'])) {
                $oer_curriculum_download_copy = sanitize_text_field($_POST['oer_curriculum_download_copy']);
            } else {
                $oer_curriculum_download_copy = 'no';
            }
            update_post_meta($post->ID, 'oer_curriculum_download_copy', sanitize_text_field($oer_curriculum_download_copy));

            // Save download copy document
            if (isset($_POST['oer_curriculum_download_copy_document'])) {
                update_post_meta($post->ID, 'oer_curriculum_download_copy_document', esc_url_raw($_POST['oer_curriculum_download_copy_document']));
            }

            // Save related curriculum
            if (isset($_POST['oer_curriculum_related_curriculum'])) {
                foreach ($_POST['oer_curriculum_related_curriculum'] as $key => $value) {
                  $_POST['oer_curriculum_related_curriculum'][$key] = sanitize_text_field($_POST['oer_curriculum_related_curriculum'][$key]);
                }
                update_post_meta($post->ID, 'oer_curriculum_related_curriculum', $_POST['oer_curriculum_related_curriculum']);
            }
        }
    }
}

// Ajax Requests
/**
 * Create dynamic more activity editor
 */
add_action('wp_ajax_oercurr_add_more_activity_callback', 'oercurr_add_more_activity_callback');
add_action('wp_ajax_nopriv_oercurr_add_more_activity_callback', 'oercurr_add_more_activity_callback');

function oercurr_add_more_activity_callback() {
    $totalElements = isset($_REQUEST['row_id']) ? $_REQUEST['row_id'] : '15';
    $content = '<div class="card col card-default oercurr-ac-item" id="oercurr-ac-item-' . $totalElements . '">
                    <span class="oercurr-inner-sortable-handle">
                        <i class="fa fa-arrow-down activity-reorder-down hide" aria-hidden="true"></i>
                        <i class="fa fa-arrow-up activity-reorder-up" aria-hidden="true"></i>
                    </span>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label>Activity Title</label>
                                <input type="text" name="oer_curriculum_activity_title[]" class="form-control" placeholder="Activity Title">
                            </div>
                            <div class="col-md-2 oercurr-ac-delete-container">
                                <span class="btn btn-danger btn-sm oercurr-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="activity-title">Activity Title</label>
                                <select name="oer_curriculum_activity_type[]" class="form-control">
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
    wp_editor('',
        'oercurr-activity-detail-' . $totalElements,
        $settings = array(
            'textarea_name' => 'oer_curriculum_activity_detail[]',
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

/**
 * Add more primary resource
 */
add_action('wp_ajax_oercurr_add_more_prime_resource_callback', 'oercurr_add_more_prime_resource_callback');
add_action('wp_ajax_nopriv_oercurr_add_more_prime_resource_callback', 'oercurr_add_more_prime_resource_callback');

function oercurr_add_more_prime_resource_callback() {
    $totalElements = isset($_REQUEST['row_id']) ? $_REQUEST['row_id'] : '25';
    $prType = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'resource';
    //RESOURCE FIELD TYPE
    if($prType == 'resource'){
      $content = '<div class="card col card-default oercurr-primary-resource-element-wrapper" id="oercurr-primary-resource-element-wrapper-' . $totalElements . '">
                      <div class="card-header">
                          <h3 class="card-title oercurr-module-title">
                              Resource
                              <span class="oercurr-sortable-handle">
                                  <i class="fa fa-arrow-down resource-reorder-down" aria-hidden="true"></i>
                                  <i class="fa fa-arrow-up resource-reorder-up" aria-hidden="true"></i>
                              </span>
                              <span class="btn btn-danger btn-sm oercurr-remove-source"
                                    title="Delete"
                              ><i class="fa fa-trash"></i> </span>
                          </h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <label>Thumbnail Image</label>
                                <div class="oer_primary_resource_thumbnail_holder"></div>
                                <button name="oer_curriculum_primary_resources_thumbnail_button" class="oer_curriculum_primary_resources_thumbnail_button" class="ui-button" alt="Set Thumbnail Image">Set Thumbnail</button>
                                <input type="hidden" name="oer_curriculum_primary_resources[image][]" class="oer_primary_resourceurl" value="" />
                            </div>
                        </div>
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <div class="oer_curriculum_primary_resources_image_wrappper">
                                        <label>Resource</label>
                                        <div class="oer_curriculum_primary_resources_image">
                                          <div class="oer_curriculum_primary_resources_image_preloader" style="display:none;">
                                            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                          </div>
                                          <div class="oer_curriculum_primary_resources_image_display">
                                            <div class="oer_curriculum_primary_resources_display"><p>You have not selected a resource</p></div>
                                            <input type="hidden" name="oer_curriculum_primary_resources[resource][]" value="">
                                            <input type="button" class="button oercurr-resource-selector-button" value="Select Resource">
                                          </div>                                    
                                        </div>
                                      </div>';
                                      
                                      /*
                                      <select name="oer_curriculum_primary_resources[resource][]" class="form-control">';
                                          $content .= oercurr_primary_resource_dropdown();
                                      </select>
                                      */
                                      
                     $content .= '</div>
                              </div>
                              <div class="col-md-5">
                                  <div class="checkbox pull-right">
                                      <label>
                                          <input type="hidden" name="oer_curriculum_primary_resources[field_type][]" value="' . $prType .'">
                                          <input type="hidden" name="oer_curriculum_primary_resources[sensitive_material_value][]" value="no">
                                          <input type="checkbox" name="oer_curriculum_primary_resources[sensitive_material][]" value="yes">
                                          Sensitive Material
                                      </label>
                                  </div>
                              </div>
                          </div>';
      //TEXTBOX FIELD TYPE 
      }else{                   
        $content = '<div class="card col card-default oercurr-primary-resource-element-wrapper" id="oercurr-primary-resource-element-wrapper-' . $totalElements . '">
                        <div class="card-header">
                            <h3 class="card-title oercurr-module-title">
                                Texbox
                                <span class="oercurr-sortable-handle">
                                    <i class="fa fa-arrow-down resource-reorder-down" aria-hidden="true"></i>
                                    <i class="fa fa-arrow-up resource-reorder-up" aria-hidden="true"></i>
                                </span>
                                <span class="btn btn-danger btn-sm oercurr-remove-source"
                                      title="Delete"
                                ><i class="fa fa-trash"></i> </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-7">
                                        <label>Thumbnail Image</label>
                                        <div class="oer_primary_resource_thumbnail_holder"></div>
                                        <button name="oer_curriculum_primary_resources_thumbnail_button" class="oer_curriculum_primary_resources_thumbnail_button" class="ui-button" alt="Set Thumbnail Image">Set Thumbnail</button>
                                        <input type="hidden" name="oer_curriculum_primary_resources[image][]" class="oer_primary_resourceurl" value="" />
                                    </div>
                                    <div class="col-md-5">
                                        <div class="checkbox pull-right">
                                            <label>
                                                <input type="hidden" name="oer_curriculum_primary_resources[resource][]" value="">
                                                <input type="hidden" name="oer_curriculum_primary_resources[field_type][]" value="'.$prType.'">
                                                <input type="hidden" name="oer_curriculum_primary_resources[sensitive_material_value][]" value="no">
                                                <input type="checkbox" name="oer_curriculum_primary_resources[sensitive_material][]" value="yes">
                                                Sensitive Material
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>';
      }                
            $content .= '<div class="form-group">
                              <label>Title</label>
                              <input type="text"
                              class="form-control"
                              name="oer_curriculum_primary_resources[title][]"
                              placeholder="Resource Title"
                              value="">';
                              ob_start(); // Start output buffer
                              //wp_editor('',
                              //    'oercurr-resource-teacher-' . $totalElements,
                              //    $settings = array(
                              //        'textarea_name' => 'oer_curriculum_primary_resources[teacher_info][]',
                              //        'media_buttons' => true,
                              //        'textarea_rows' => 6,
                              //        'drag_drop_upload' => true,
                              //        'teeny' => true,
                              //        'quicktags' => true,
                              //        'tinymce' => true
                              //    )
                              //);
                          $content .= ob_get_clean();
                          $content .= '</div>';
                          $content .= '<div class="form-group">
                              <label>Description</label>';
                              
                              //$content .= '<textarea name="oer_curriculum_primary_resources[description][]" id="oercurr-resource-student-'.$totalElements.'" cols="40"></textarea>';
                              
                              
                              ob_start(); // Start output buffer
                              wp_editor('',
                                  'oercurr-resource-student-' . $totalElements,
                                  $settings = array(
                                      'textarea_name' => 'oer_curriculum_primary_resources[description][]',
                                      'media_buttons' => true,
                                      'textarea_rows' => 6,
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

/**
 * Create dynamic module
 */
add_action('wp_ajax_oercurr_create_module_callback', 'oercurr_create_module_callback');
add_action('wp_ajax_nopriv_oercurr_create_module_callback', 'oercurr_create_module_callback');

function oercurr_create_module_callback() {
    $module_type = isset($_REQUEST['module_type']) ? $_REQUEST['module_type'] : 'editor';
    $element_id = isset($_REQUEST['row_id']) ? $_REQUEST['row_id'] : '15';

    if ($module_type == 'editor') {
        echo oercurr_create_dynamic_editor($element_id);
        exit();
        /* echo json_encode(
             array(
                 'status' => 'ok',
                 'result' => oercurr_create_dynamic_editor($element_id)
             )
         );*/
    } elseif ($module_type == 'list') {
        echo oercurr_create_dynamic_text_list($element_id);
    } elseif ($module_type == 'vocabulary') {
        echo oercurr_create_dynamic_vocabulary_list($element_id);
    } elseif ($module_type == 'materials') {
        echo oercurr_create_dynamic_materials_module($element_id);
    }
    exit();
}


// Ajax Requests
/**
 * Get Resource Information
 */
add_action('wp_ajax_oercurr_get_resource_info_callback', 'oercurr_get_resource_info_callback');
add_action('wp_ajax_nopriv_oercurr_get_resource_info_callback', 'oercurr_get_resource_info_callback');

function oercurr_get_resource_info_callback() {
  
  $_arr = array();
  if(!empty($_POST['resid'])){
      $_resid = $_POST['resid'];
      $_resource= get_post($_resid);
      $_arr['p_title'] = $_resource->post_title;
      $_arr['p_url'] = get_permalink($_resource->ID);
      $_arr['p_resourceurl'] = trim(get_post_meta($_resource->ID, "oer_resourceurl", true)," ");
      $_arr['p_type'] = get_post_meta($_resource->ID,"oer_mediatype")[0];
      $rsrcThumbID = get_post_thumbnail_id($_resource);
      $resource_img='';
      if (!empty($rsrcThumbID)){
          $resource_img = wp_get_attachment_image_url(get_post_thumbnail_id($_resource), 'Thumbnail' );
          $_arr['p_imgtyp'] = 'image';
          $_arr['p_img'] = $resource_img;
      }else{
        $_avtr = oer_getResourceIcon($_arr['p_type'],$_arr['p_resourceurl']);
        $_arr['p_imgtyp'] = 'avatar';
        $_arr['p_img'] = $_avtr;
      }
  }
  echo json_encode($_arr);
  exit();
}

/**
 * Create dynamic text editor
 * @param $id
 * @return string
 */
function oercurr_create_dynamic_editor($id) {

    $content = '<div class="card col card-default oercurr-element-wrapper oercurr-introduction-group" id="oercurr-custom-editor-group-' . $id . '">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_custom_editor_' . $id . ']" class="element-order" value="' . $id . '">
                    <div class="card-header">
                        <h3 class="card-title oercurr-module-title">
                            Text Editor
                            <span class="oercurr-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down hide" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oercurr-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="oer_curriculum_custom_editor_'. $id.'[title]" maxlength="512" class="form-control" placeholder="Text Module Title" />
                        </div>
                        <div class="form-group">';
                        ob_start(); // Start output buffer
                        wp_editor('',
                            'oercurr-custom-editor-' . $id,
                            $settings = array(
                                'textarea_name' => 'oer_curriculum_custom_editor_' . $id . '[description]',
                                'media_buttons' => true,
                                'textarea_rows' => 10,
                                'drag_drop_upload' => true,
                                'teeny' => true,
                            )
                        );
    $content .= ob_get_clean();
    $content .= '</div></div>
                </div>';

    return $content;
}

/**
 * Create dynamic text list
 * @param $id
 * @return string
 */
function oercurr_create_dynamic_text_list($id) {
    $content = '<div class="card col card-default oercurr-element-wrapper" id="oercurr-text-list-group' . $id . '">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_custom_text_list_' . $id . ']" class="element-order" value="' . $id . '">
                    <div class="card-header">
                        <h3 class="card-title oercurr-module-title">
                            Text List
                            <span class="oercurr-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down hide" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oercurr-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="oercurr-text-list-row" id="oercurr-text-list-row' . $id . '">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text"
                                               class="form-control"
                                               name="oer_curriculum_custom_text_list_' . $id . '[]"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button"
                                            class="btn btn-danger oercurr-remove-text-list"
                                            disabled="disabled"
                                    ><i class="fa fa-trash"></i> </button>
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>';

    return $content;
}

/**
 * Create dynamic vocabulary list
 * @param $id
 * @return string
 */
function oercurr_create_dynamic_vocabulary_list($id) {
    $content = '<div class="card col card-default oercurr-element-wrapper" id="oercurr-vocabulary-list-group' . $id . '">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_vocabulary_list_title_' . $id . ']" class="element-order" value="' . $id . '">
                    <div class="card-header">
                        <h3 class="card-title oercurr-module-title">
                            Vocabulary List
                            <span class="oercurr-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down hide" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oercurr-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="text"
                                   class="form-control"
                                   name="oer_curriculum_vocabulary_list_title_' . $id . '"
                            >
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="oer_curriculum_vocabulary_details_' . $id . '" rows="6"></textarea>
                        </div>   
                    </div>
                </div>';

    return $content;
}

if (! function_exists('oercurr_create_dynamic_materials_module')) {
    /**
     * Create dynamic vocabulary list
     * @param $id
     * @return string
     */
    function oercurr_create_dynamic_materials_module($id) {
        $content = '<div class="card col card-default oercurr-element-wrapper" id="oercurr-materials-'.$id.'">
                        <input type="hidden" name="oer_curriculum_order[oer_curriculum_oer_materials_list_'.$id.']" class="element-order" value="'.$id.'">
                        <div class="card-header">
                            <h3 class="card-title oercurr-module-title">
                                Materials
                                <span class="oercurr-sortable-handle">
                                    <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                    <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                </span>
                                <span class="btn btn-danger btn-sm oercurr-remove-module" title="Delete"><i class="fa fa-trash"></i></span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="panel-group oercurr-materials-container" id="oercurr-materials-container">
                            </div>
                            <button type="button" data-type="custom" data-name="oer_curriculum_oer_materials_list_'.$id.'" class="btn btn-default oercurr-add-materials"><i class="fa fa-plus"></i> Add Materials</button>
                        </div>
                    </div>';
        return $content;
    }
}
/**
 * Hide installation notice
 */
add_action('wp_ajax_oercurr_dismiss_notice_callback', 'oercurr_dismiss_notice_callback');
add_action('wp_ajax_nopriv_oercurr_dismiss_notice_callback', 'oercurr_dismiss_notice_callback');

function oercurr_dismiss_notice_callback() {
    update_option('oer_curriculum_setup_notification', true);
}

/**
 * Search standards in modal
 */
add_action('wp_ajax_oercurr_searched_standards_callback', 'oercurr_searched_standards_callback');
add_action('wp_ajax_nopriv_oercurr_searched_standards_callback', 'oercurr_searched_standards_callback');

function oercurr_searched_standards_callback() {
    $post_id = null;
    $keyword = null;
    $meta_key = "oer_curriculum_standards";

    if (isset($_POST['post_id'])){
        $post_id = $_POST['post_id'];
    }
    if (isset($_POST['keyword'])){
        $keyword = $_POST['keyword'];
    }

    if (!$post_id){
        echo "Invalid Post ID";
        die();
    }

    if (!$keyword){
        was_selectable_admin_standards($post_id);
        die();
    }

    if (function_exists('was_search_standards')){
        was_search_standards($post_id,$keyword,$meta_key);
    }
    die();
}

add_action('wp_ajax_oercurr_get_source_callback', 'oercurr_get_source_callback');
add_action('wp_ajax_nopriv_oercurr_get_source_callback', 'oercurr_get_source_callback');
function oercurr_get_source_callback(){
    $source = null;
    $curriculum_id = null;
    $data = null;
    $source_id = null;
    $teacher_info = "";
    $student_info = "";
    $resource_meta = null;
    $subject_areas = null;
    $subjects = null;
    
    if (isset($_POST['next_source']))
        $source = $_POST['next_source'];
    
    if (isset($_POST['curriculum']))
        $curriculum_id = $_POST['curriculum'];
    
    if (isset($_POST['index']))
        $source_id = $_POST['index'];
    
    // Get Resource Details
    $resource = get_page_by_title($source,OBJECT,"resource");
    $resource_img = get_the_post_thumbnail_url($resource);
    
    // Get Resource Meta
    if (function_exists('oer_get_resource_metadata')){
        $resource_meta = oer_get_resource_metadata($resource->ID);
    }
    
    if (function_exists('oer_get_subject_areas')){
        $subject_areas = oer_get_subject_areas($resource->ID);
    }
    if (is_array($subject_areas) && count($subject_areas)>0) {
        $subjects = array_unique($subject_areas, SORT_REGULAR);
    }
    
    // Get Curriculum Details
    $post_meta_data = get_post_meta($curriculum_id);
    $primary_resources = (isset($post_meta_data['oer_curriculum_primary_resources'][0]) ? unserialize($post_meta_data['oer_curriculum_primary_resources'][0]) : array());
     if (isset($primary_resources['teacher_info']))
        $teacher_info = $primary_resources['teacher_info'][$source_id];
    if (isset($primary_resources['student_info']))
        $student_info = $primary_resources['student_info'][$source_id];
    
    $data['resource'] = $resource;
    $data['featured_image'] = esc_url($resource_img);
    $data['teacher_info'] = $teacher_info;
    $data['student_info'] = $student_info;
    $data['resource_meta'] = $resource_meta;
    
    echo json_encode($data);
    die();
}

/**
 * Add Text Feature
 */
add_action('wp_ajax_oercurr_add_text_feature_callback', 'oercurr_add_text_feature_callback');
add_action('wp_ajax_nopriv_oercurr_add_text_feature_callback', 'oercurr_add_text_feature_callback');

function oercurr_add_text_feature_callback() {
    
    $element_id = (isset($_REQUEST['row_id']))?$_REQUEST['row_id']:1;
    $ed_id = (isset($_REQUEST['editor_id'])?$_REQUEST['editor_id']:'oercurr-additional-section-');
    $req_mat = (isset($_REQUEST['required_material'])?true:false);
    $element_id++;

    if ($req_mat){
        $label_id = "oer_curriculum_required_materials[label][]";
        $editor_id = "oer_curriculum_required_materials[editor][]";
    } else {
        $label_id = "oer_curriculum_additional_sections[label][]";
        $editor_id = "oer_curriculum_additional_sections[editor][]";
    }
    $content = '<div class="card col card-default oercurr-section-element-wrapper" id="oer_curriculum_section_element_wrapper-'.$element_id.'">';
    $content .= '   <div class="card-header">';
    $content .= '       <h3 class="card-title oercurr-module-title">';
    $content .=             __("Section", OERCURR_CURRICULUM_SLUG);
    $content .= '           <span class="oercurr-sortable-handle">';
    $content .= '               <i class="fa fa-arrow-down section-reorder-down" aria-hidden="true"></i>';
    $content .= '               <i class="fa fa-arrow-up section-reorder-up" aria-hidden="true"></i>';
    $content .= '           </span>';
    $content .= '           <button type="button" class="btn btn-danger btn-sm oercurr-remove-section" title="Delete"><i class="fa fa-trash"></i> </button>';
    $content .= '       </h3>';
    $content .= '   </div>';
    $content .= '   <div class="card-body">';
    $content .= '       <div class="form-group">';
    $content .= '           <input type="text" class="form-control" name="'.$label_id.'" id="'.$label_id.'" placeholder="Text Title">';
    $content .= '       </div>';
    $content .= '       <div class="form-group">';
    $content .= '           <div class="text-editor-group">';
                            ob_start(); // Start output buffer
                            wp_editor('',
                                $ed_id . $element_id,
                                $settings = array(
                                    'textarea_name' => $editor_id,
                                    'media_buttons' => true,
                                    'textarea_rows' => 10,
                                    'drag_drop_upload' => true,
                                    'teeny' => true,
                                )
                            );
    $content .=             ob_get_clean();
    $content .= '           </div>';
    $content .= '       </div>';
    $content .= '   </div>';
    $content .= '</div>';
    echo $content;
    exit();
}

function oercurr_change_post_types_slug( $args, $post_type ) {
   global $root_slug;
   /*item post type slug*/   
   if ( 'oer-curriculum' === $post_type ) {
      $args['rewrite']['slug'] = $root_slug;
   }

   return $args;
}
add_filter( 'register_post_type_args', 'oercurr_change_post_types_slug', 10, 2 );

function oercurr_add_modals_to_footer(){
    $screen = get_current_screen();
    if ( 'post' == $screen->base && 'oer-curriculum' == $screen->id ){
        include_once(OERCURR_CURRICULUM_PATH.'includes/popups/create-module.php');
        include_once(OERCURR_CURRICULUM_PATH.'includes/popups/delete-module.php');
        include_once(OERCURR_CURRICULUM_PATH.'includes/popups/delete-author.php');
        include_once(OERCURR_CURRICULUM_PATH.'includes/popups/delete-source.php');
        include_once(OERCURR_CURRICULUM_PATH.'includes/popups/delete-section.php');
        include_once(OERCURR_CURRICULUM_PATH.'includes/popups/delete-confirm-popup.php');
        include_once(OERCURR_CURRICULUM_PATH.'includes/popups/standard-selection.php');
        include_once(OERCURR_CURRICULUM_PATH . 'includes/oer-curriculum-resource-selector.php');
    }
}
add_action( 'admin_footer', 'oercurr_add_modals_to_footer', 10 );


function oercurr_add_curriculum_settings(){
    add_submenu_page('edit.php?post_type=oer-curriculum','Settings','Settings','add_users','oer_curriculum_settings','oercurr_settings_callback_func');
}
add_action( 'admin_menu', 'oercurr_add_curriculum_settings' );

function oercurr_settings_callback_func(){
    include_once( OERCURR_CURRICULUM_PATH."includes/oer-curriculum-settings.php" );
}

add_filter( 'wp_default_editor', function(){return "html";} );