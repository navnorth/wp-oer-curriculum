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
        'name'          => esc_html_x('Curriculum', 'post type general name', OERCURR_CURRICULUM_SLUG),
        'singular_name' => esc_html_x('Curriculum', 'post type singular name', OERCURR_CURRICULUM_SLUG),
        'add_new'       => esc_html__('Add New Curriculum', OERCURR_CURRICULUM_SLUG),
        'add_new_item'  => esc_html__('Add New Curriculum',OERCURR_CURRICULUM_SLUG),
        'edit_item'     => esc_html__('Edit Curriculum', OERCURR_CURRICULUM_SLUG),
        'new_item'      => esc_html__('Create Curriculum', OERCURR_CURRICULUM_SLUG),
        'all_items'     => esc_html__('All Curriculum', OERCURR_CURRICULUM_SLUG),
        'view_item'     => esc_html__('View Curriculum', OERCURR_CURRICULUM_SLUG),
        'search_items'  => esc_html__('Search', OERCURR_CURRICULUM_SLUG),
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
        'taxonomies'            => array('post_tag', 'resource-subject-area', OERCURR_GRADE_LEVEL_TAX_SLUG),
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions'),
        'register_meta_box_cb'  => 'oercurr_custom_meta_boxes',
        'show_in_rest'          => true
    );

    register_post_type('oer-curriculum', $args);
    
    
    if(OERCURR_INDI_GRADE_LEVEL){
    	$labels = array(
        'name'              => esc_html__( 'Grade Level',OERCURR_CURRICULUM_SLUG ),
  	    'singular_name'     => esc_html_x( 'Grade Level', 'taxonomy singular name', OERCURR_CURRICULUM_SLUG ),
  	    'search_items'      => esc_html__( 'Search Grade Levels',OERCURR_CURRICULUM_SLUG ),
  	    'all_items'         => esc_html__( 'All Grade Levels', OERCURR_CURRICULUM_SLUG ),
  	    'parent_item'       => esc_html__( 'Parent Grade Level',OERCURR_CURRICULUM_SLUG ),
  	    'parent_item_colon' => esc_html__( 'Parent Grade Level'.':',OERCURR_CURRICULUM_SLUG ),
  	    'edit_item'         => esc_html__( 'Edit Grade Level', OERCURR_CURRICULUM_SLUG ),
  	    'update_item'       => esc_html__( 'Update Grade Level', OERCURR_CURRICULUM_SLUG ),
  	    'add_new_item'      => esc_html__( 'Add New Grade Level',OERCURR_CURRICULUM_SLUG),
  	    'new_item_name'     => esc_html__( 'New Grade Level',OERCURR_CURRICULUM_SLUG),
  	    'menu_name'         => esc_html__( 'Grade Levels',OERCURR_CURRICULUM_SLUG ),
      );
      
      $args = array(
  	    'hierarchical'      => true,
  	    'labels'            => $labels,
  	    'show_ui'           => true,
  	    'show_admin_column' => true,
        'show_in_rest'		  => true,
  	    'query_var'         => true,
  	    'rewrite'           => array( 'slug' => OERCURR_GRADE_LEVEL_TAX_SLUG ),
        'sort'              => true,
        'orderby'           => 'term_order',
      );
    
      register_taxonomy( OERCURR_GRADE_LEVEL_TAX_SLUG, array( 'oer-curriculum' ), $args );
    }
    
    if(!get_option('oer_curriculum_details_curmetset_label')){oercurr_add_setting_options('oer_curriculum_details','label','Details');}
    if(!get_option('oer_curriculum_type_curmetset_label')){oercurr_add_setting_options('oer_curriculum_type','label','Type');}
    if(!get_option('oer_curriculum_type_other_curmetset_label')){oercurr_add_setting_options('oer_curriculum_type_other','label','Other Type');}
    if(!get_option('oer_curriculum_authors_curmetset_label')){oercurr_add_setting_options('oer_curriculum_authors','label','Author');}
    if(!get_option('oer_curriculum_standardsandobjectives_curmetset_label')){oercurr_add_setting_options('oer_curriculum_standardsandobjectives','label','Standards and Objectives');}
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

    
    if(!get_option('oer_curriculum_details_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_details','enable','checked');}
    if(!get_option('oer_curriculum_type_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_type','enable','checked');}
    if(!get_option('oer_curriculum_type_other_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_type_other','enable','checked');}
    if(!get_option('oer_curriculum_authors_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_authors','enable','checked');}
    if(!get_option('oer_curriculum_standardsandobjectives_curmetset_enable')){oercurr_add_setting_options('oer_curriculum_standardsandobjectives','enable','checked');}
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

// Display grade levels according to term_order in block editor sidebar
add_filter('rest_'.OERCURR_GRADE_LEVEL_TAX_SLUG.'_query','oercur_sort_grade_levels', 10, 2);
function oercur_sort_grade_levels($args, $request){
	$args['orderby'] = "term_order";
	return $args;
}

// Change order of grade level display on both edit tags page and in classic editor
add_filter( 'get_terms_args', 'oercurr_sort_grade_level_terms', 10, 2 );
function oercurr_sort_grade_level_terms( $args, $taxonomies ){
	global $pagenow;
	if (is_admin() && ($pagenow=='edit-tags.php' || $pagenow == 'post-new.php' || $pagenow == 'post.php') && in_array(OERCURR_GRADE_LEVEL_TAX_SLUG,$taxonomies) ){
		$args['orderby'] = 'term_order';
    	$args['order'] = 'ASC';
	}
  return $args;
}

function oercurr_custom_meta_boxes() {
    // Grade Levels
    /*
    $grade_levels_set = (trim(get_option('oer_curriculum_grades_curmetset_label'),' ') != '')?true:false;
    $grade_levels_enabled = (get_option('oer_curriculum_grades_curmetset_enable')=='checked')?true:false;
    if ($grade_levels_enabled) {
      add_meta_box( 'oer_curriculum_meta_grades', __(get_option('oer_curriculum_grades_curmetset_label'),OERCURR_CURRICULUM_SLUG), 'oercurr_grade_level_callback', 'oer-curriculum', 'side', 'high' );
    } */

    // Appropriate Age Levels
    $age_levels_set = (trim(get_option('oer_curriculum_age_levels_curmetset_label'),' ') != '')?true:false;
    $age_levels_enabled = (get_option('oer_curriculum_age_levels_curmetset_enable')=='checked')?true:false;
    if ($age_levels_enabled) {
        $label = get_option('oer_curriculum_age_levels_curmetset_label');
        add_meta_box( 'oer_curriculum_meta_age_levels', __($label,OERCURR_CURRICULUM_SLUG) , 'oercurr_age_levels_callback', 'oer-curriculum', 'side', 'high' );
    }

    //Suggested Instructional Time
    $suggested_time_set = (trim(get_option('oer_curriculum_suggested_instructional_time_curmetset_label'),' ') != '')?true:false;
    $suggested_time_enabled = (get_option('oer_curriculum_suggested_instructional_time_curmetset_enable')=='checked')?true:false;
    if (($suggested_time_set && $suggested_time_enabled) || !$suggested_time_set) {
        $label = get_option('oer_curriculum_suggested_instructional_time_curmetset_label');
        add_meta_box( 'oer_curriculum_meta_suggested_time', __($label,OERCURR_CURRICULUM_SLUG), 'oercurr_suggested_time_callback', 'oer-curriculum', 'side', 'high' );
    }
    add_meta_box('oer_curriculum_meta_boxid', __('Lesson Meta Fields',OERCURR_CURRICULUM_SLUG), 'oercurr_meta_fields_callback', 'oer-curriculum', 'advanced');

    // Add a download copy option
    $download_copy_set = (trim(get_option('oer_curriculum_download_copy_curmetset_label'),' ') != '')?true:false;
    $download_copy_enabled = (get_option('oer_curriculum_download_copy_curmetset_enable')=='checked')?true:false;
    if($download_copy_enabled){
        $label = get_option('oer_curriculum_download_copy_curmetset_label');
      add_meta_box( 'oer_curriculum_meta_download_copy', __($label,OERCURR_CURRICULUM_SLUG) , 'oercurr_download_copy_callback', 'oer-curriculum', 'side', 'high' );
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
            add_meta_box('oer_curriculum_meta_related', __($label,OERCURR_CURRICULUM_SLUG), 'oercurr_related_curriculum_callback', 'oer-curriculum', 'advanced');
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
    echo '<input type="text" class="form-control" name="oer_curriculum_age_levels" placeholder="'.esc_html__('Age Levels', OERCURR_CURRICULUM_SLUG).'" value="'. esc_attr($oer_curriculum_age_levels) .'">';
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
    echo '<input type="text" class="form-control" name="oer_curriculum_suggested_instructional_time" placeholder="'.esc_html__('Suggested Time', OERCURR_CURRICULUM_SLUG).'" value="'. esc_attr($oer_curriculum_suggested_time) .'">';
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
    ?><div class="oer_curriculum_grades pt-3"><?php
    foreach ($oer_curriculum_grade_options as $key => $oer_curriculum_grade_option) {
        $index++;
        if ($index % 7 == 1){
            if ($index<7){
                ?><div class="px-0 col-md-7 span2"><?php
            }else{
                ?><div class="px-0 col-md-5 span2"><?php
            } 
        }
        ?>
        <div class="form-checkbox">
        <input type="checkbox" name="oer_curriculum_grades[]" value="<?php echo esc_attr($key) ?>" id="oer_curriculum_grade_<?php echo esc_attr($key) ?>" <?php echo esc_html(oercurr_show_selected($key, $oer_curriculum_grades, 'checkbox')) ?> >
        <label class="oer_curriculum_radio_label" for="oer_curriculum_grade_'.esc_attr($key).'"><?php echo esc_html($oer_curriculum_grade_option) ?></label>
        </div>
        <?php
        if ($index % 7 == 0 ){
            ?></div><?php
        }
    }
    ?></div><?php
}


/**
 * Add a checkbox option to the sidebar
 * To download file
 */
function oercurr_download_copy_callback() {
    global $post;
    $post_meta_data = get_post_meta($post->ID );

    // Upload document
    $oer_curriculum_download_copy_document = (isset($post_meta_data['oer_curriculum_download_copy_document'][0]) ? $post_meta_data['oer_curriculum_download_copy_document'][0] : '');
    ?><div class="form-group"><?php
    ?><div class="input-group full-width"><?php
    ?><input type="hidden" class="form-control" name="oer_curriculum_download_copy_document" placeholder="<?php echo esc_html__('Select Document', OERCURR_CURRICULUM_SLUG) ?>" value="<?php echo esc_url($oer_curriculum_download_copy_document) ?>"><?php
    if (!empty($oer_curriculum_download_copy_document)){
      ?>
        <div class="oercurr-selected-section"><a href="<?php echo esc_url($oer_curriculum_download_copy_document) ?>" target="_blank"><?php echo esc_url($oer_curriculum_download_copy_document) ?></a> <span class="oercurr-remove-download-copy" title="Remove copy"><i class="fas fa-trash-alt"></i></span></div>
        <span class="oercurr-select-label oercurr-hidden"><?php echo esc_html__('Select Document', OERCURR_CURRICULUM_SLUG) ?></span> <div class="input-group-addon oercurr-download-copy-icon oercurr-hidden" title="Select Material"><i class="fa fa-upload"></i></div>
      <?php
    } else {
      ?>
        <div class="oercurr-selected-section oercurr-hidden"><a href="" target="_blank"></a> <span class="oercurr-remove-download-copy"><i class="fas fa-trash-alt"></i></span></div>
        <span class="oercurr-select-label"><?php echo esc_html__('Select Document', OERCURR_CURRICULUM_SLUG) ?></span> <div class="input-group-addon oercurr-download-copy-icon" title="Select Material"><i class="fa fa-upload"></i></div>
      <?php
    }
    ?></div></div><?php
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

        if ( ! did_action( 'wp_enqueue_media' ) ) {
  	        wp_enqueue_media();
  	    }
        wp_enqueue_script( 'media-upload' );
        wp_register_script('oercurr-script', OERCURR_CURRICULUM_URL . 'js/backend/oer-curriculum.js', array( 'jquery','media-upload' ));
        wp_localize_script('oercurr-script','lpScript',
          [
            "image_placeholder_url" => OERCURR_CURRICULUM_URL.'images/oer-curriculum-person-placeholder.png',
            'pluginDirUrl' => OERCURR_CURRICULUM_URL,
            'txtSetThumbnail' => esc_html__('Set Thumbnail', OERCURR_CURRICULUM_SLUG),
            'txtChangeThumbnail' => esc_html__('Change Thumbnail', OERCURR_CURRICULUM_SLUG),
            'txtUseMaterial' => esc_html__('Use Material', OERCURR_CURRICULUM_SLUG),
            'txtUseMaterials' => esc_html__('Use Materials', OERCURR_CURRICULUM_SLUG),
            'txtSelectMaterial' => esc_html__("Select Material", OERCURR_CURRICULUM_SLUG),
            'txtSelectMaterials' => esc_html__("Select Materials", OERCURR_CURRICULUM_SLUG),
            'txtAddMaterials' => esc_html__("Use Materials", OERCURR_CURRICULUM_SLUG),
            'txtSelectAuthorPicture' => esc_html__("Select Author Picture", OERCURR_CURRICULUM_SLUG),
            'txtUsePicture' => esc_html__("Use Picture", OERCURR_CURRICULUM_SLUG),
          ]
        );
        wp_enqueue_script('oercurr-script');
        wp_enqueue_script('oercurr-resource-selector-script', OERCURR_CURRICULUM_URL . 'js/backend/oer-curriculum-resource-selector.js' , array('jquery') , null, true);
        //wp_enqueue_script('oercurr-cfb-admin-jqueryui-core', admin_url( 'wp-includes/js/jquery/ui/core.min.js' ) ,array('jquery') , null, true);
        wp_enqueue_script('oercurr-cfb-admin-jqueryui-sortable', admin_url( 'wp-includes/js/jquery/ui/sortable.min.js' ) ,array('jquery, oercurr-cfb-admin-jqueryui-core') , null, true);
  }
  
  
  wp_enqueue_script('oercurr-resource-selector-script', OERCURR_CURRICULUM_URL . 'js/backend/oercurr-admin.js' , array('jquery') , null, true);
    
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
        global $root_slug;
        if (
            (isset($_GET['post_type']) && $_GET['post_type'] == 'oer-curriculum') ||
            (isset($post->post_type) && $post->post_type == 'oer-curriculum') ||
            (get_query_var($root_slug) !== '' && get_query_var('source') !== '')
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
                $un_sanitized_curriculum_type = $_POST['oer_curriculum_type'];
                update_post_meta($post->ID, 'oer_curriculum_type', sanitize_text_field($un_sanitized_curriculum_type));
            }

            //Save/update Other Type
            if (isset($_POST['oer_curriculum_type_other'])) {
                $un_sanitized_curriculum_type_other = $_POST['oer_curriculum_type_other'];
                update_post_meta($post->ID, 'oer_curriculum_type_other', sanitize_text_field($un_sanitized_curriculum_type_other));
            }

            //Save/update introduction
            if (isset($_POST['oer_curriculum_introduction'])) {
                $un_sanitized_curriculum_introduction = $_POST['oer_curriculum_introduction'];
                update_post_meta($post->ID, 'oer_curriculum_introduction', sanitize_text_field($un_sanitized_curriculum_introduction));
            }

            // Save authors data
            if (isset($_POST['oer_curriculum_authors'])) {
                $un_sanitized_authors = $_POST['oer_curriculum_authors'];
                $_sanitized_authors = array();                              
                foreach($un_sanitized_authors as $key=>$value){
                  foreach($value as $subkey=>$subvalue){
                    $_sanitized_authors['name'][$subkey] = sanitize_text_field($un_sanitized_authors['name'][$subkey]);
                    $_sanitized_authors['role'][$subkey] = sanitize_text_field($un_sanitized_authors['role'][$subkey]);
                    $_sanitized_authors['author_url'][$subkey] = esc_url_raw($un_sanitized_authors['author_url'][$subkey]);
                    $_sanitized_authors['institution'][$subkey] = sanitize_text_field($un_sanitized_authors['institution'][$subkey]);
                    $_sanitized_authors['institution_url'][$subkey] = esc_url_raw($un_sanitized_authors['institution_url'][$subkey]);
                    $_sanitized_authors['author_pic'][$subkey] = esc_url_raw($un_sanitized_authors['author_pic'][$subkey]);
                    update_post_meta($post->ID, 'oer_curriculum_authors', $_sanitized_authors);
                  }
                }
                update_post_meta($post->ID, 'oer_curriculum_authors', $_sanitized_authors);
            }

            // Save Standards
            if (isset($_POST['oer_curriculum_standards'])) {
                $un_sanitized_curriculum_standards = $_POST['oer_curriculum_standards'];
                update_post_meta($post->ID, 'oer_curriculum_standards', sanitize_text_field($un_sanitized_curriculum_standards));
            }

            // Save / update Standard and Objectives
            if (isset($_POST['oer_curriculum_related_objective'])) {
                $un_sanitized_related_objective = $_POST['oer_curriculum_related_objective'];
                $_sanitized_related_objective = array();
                foreach($un_sanitized_related_objective as $key=>$value){
                  $_sanitized_related_objective[$key] = sanitize_text_field($un_sanitized_related_objective[$key]);
                }
                update_post_meta($post->ID, 'oer_curriculum_related_objective', $_sanitized_related_objective);
            }

            // Save Investigative Question
            if (isset($_POST['oer_curriculum_iq'])) {
                $un_sanitized_investigative_question = $_POST['oer_curriculum_iq'];
                $_sanitized_investigative_question = array(
                    'question' => sanitize_text_field($un_sanitized_investigative_question['question']),
                    'excerpt' => wp_kses(stripslashes_deep($un_sanitized_investigative_question['excerpt']),$_oercurr_allowed_html),
                );
                update_post_meta($post->ID, 'oer_curriculum_iq', $_sanitized_investigative_question);
            }

            // Save Required Equipment Materials          
            if (isset($_POST['oer_curriculum_required_materials'])) {
                $un_sanitized_required_materials = $_POST['oer_curriculum_required_materials'];
                $_sanitized_required_materials = array();        
                foreach($un_sanitized_required_materials as $key=>$value){                  
                    foreach($un_sanitized_required_materials[$key] as $subkey=>$subvalue){
                      if($key == 'editor'){
                        $_sanitized_required_materials[$key][$subkey] = wp_kses(stripslashes_deep($un_sanitized_required_materials[$key][$subkey]),$_oercurr_allowed_html);
                      }else{
                        $_sanitized_required_materials[$key][$subkey] = sanitize_text_field($un_sanitized_required_materials[$key][$subkey]);
                      }
                    }
                }
                update_post_meta($post->ID, 'oer_curriculum_required_materials', $_sanitized_required_materials);
            }
            
            
            // Save Additional Sections
            if (isset($_POST['oer_curriculum_additional_sections'])) {
                $un_sanitized_additional_sections = $_POST['oer_curriculum_additional_sections'];
                $_sanitized_additional_sections = array();
                foreach($un_sanitized_additional_sections as $key=>$value){
                    foreach($un_sanitized_additional_sections[$key] as $subkey=>$subvalue){
                        if($key == 'label'){$_sanitized_additional_sections[$key][$subkey] = sanitize_text_field($un_sanitized_additional_sections[$key][$subkey]);}
                        if($key == 'editor'){$_sanitized_additional_sections[$key][$subkey] = wp_kses(stripslashes_deep($un_sanitized_additional_sections[$key][$subkey]),$_oercurr_allowed_html);}
                    }
                }
                update_post_meta($post->ID, 'oer_curriculum_additional_sections', $_sanitized_additional_sections);
            }

            // Save primary resource
            if (isset($_POST['oer_curriculum_primary_resources'])) {
                $un_sanitized_primary_resources = $_POST['oer_curriculum_primary_resources'];
                $_sanitized_primary_resources = array();
                foreach($un_sanitized_primary_resources as $key=>$value){
                    foreach($un_sanitized_primary_resources[$key] as $subkey=>$subvalue){
                      if($key == 'image'){$_sanitized_primary_resources[$key][$subkey] = esc_url_raw($un_sanitized_primary_resources[$key][$subkey]);}
                      if($key == 'resource'){$_sanitized_primary_resources[$key][$subkey] = sanitize_text_field($un_sanitized_primary_resources[$key][$subkey]);}
                      if($key == 'field_type'){$_sanitized_primary_resources[$key][$subkey] = sanitize_text_field($un_sanitized_primary_resources[$key][$subkey]);}
                      if($key == 'sensitive_material_value'){$_sanitized_primary_resources[$key][$subkey] = sanitize_text_field($un_sanitized_primary_resources[$key][$subkey]);}
                      if($key == 'title'){$_sanitized_primary_resources[$key][$subkey] = sanitize_text_field($un_sanitized_primary_resources[$key][$subkey]);}
                      if($key == 'description'){$_sanitized_primary_resources[$key][$subkey] = wp_kses(stripslashes_deep($un_sanitized_primary_resources[$key][$subkey]),$_oercurr_allowed_html);}
                    }
                }
                update_post_meta($post->ID, 'oer_curriculum_primary_resources', $_sanitized_primary_resources);
            } else {
                if (get_post_meta($post->ID, 'oer_curriculum_primary_resources'))
                    delete_post_meta($post->ID, 'oer_curriculum_primary_resources');
            }

            // Save materials
            if (isset($_POST['oer_curriculum_oer_materials'])) {
              $un_sanitized_oer_materials = $_POST['oer_curriculum_oer_materials'];
              $_sanitized_oer_materials = array();
              foreach($un_sanitized_oer_materials as $key=>$value){
                  foreach($un_sanitized_oer_materials[$key] as $subkey=>$subvalue){
                    if($key == 'url'){$_sanitized_oer_materials[$key][$subkey] = esc_url_raw($un_sanitized_oer_materials[$key][$subkey]);}
                    if($key == 'title'){$_sanitized_oer_materials[$key][$subkey] = sanitize_text_field($un_sanitized_oer_materials[$key][$subkey]);}
                    if($key == 'description'){$_sanitized_oer_materials[$key][$subkey] = wp_kses(stripslashes_deep($un_sanitized_oer_materials[$key][$subkey]),$_oercurr_allowed_html);}
                  }
              }
              update_post_meta($post->ID, 'oer_curriculum_oer_materials', $_sanitized_oer_materials);
            }

            if (isset($_POST['oer_curriculum_grades'])) {
                $un_sanitized_grades = $_POST['oer_curriculum_grades'];
                $_sanitized_grades = array();
                foreach($un_sanitized_grades as $key=>$value){
                  $_sanitized_grades[$key] = sanitize_text_field($un_sanitized_grades[$key]);
                }
                update_post_meta($post->ID, 'oer_curriculum_grades', $_sanitized_grades);
            }else{
                update_post_meta($post->ID, 'oer_curriculum_grades', false);
            }

            // Update Appropriate Age Levels
            if (isset($_POST['oer_curriculum_age_levels'])) {
                $un_sanitized_curriculum_age_levels = $_POST['oer_curriculum_age_levels'];
                update_post_meta($post->ID, 'oer_curriculum_age_levels', sanitize_text_field($un_sanitized_curriculum_age_levels));
            }

            // Update Suggested Instructional Time
            if (isset($_POST['oer_curriculum_suggested_instructional_time'])) {
                $un_sanitized_suggested_instructional_time = $_POST['oer_curriculum_suggested_instructional_time'];
                update_post_meta($post->ID, 'oer_curriculum_suggested_instructional_time', sanitize_text_field($un_sanitized_suggested_instructional_time));
            }

            // Save custom editor fields
            if (isset($_POST['oer_curriculum_custom_editor'])) {
                $un_sanitized_custom_editor = $_POST['oer_curriculum_custom_editor'];
                update_post_meta($post->ID, 'oer_curriculum_custom_editor', sanitize_text_field($un_sanitized_custom_editor));
            }

            // Save Additional Text Features
            if (isset($_POST['oer_curriculum_text_feature'])){
                $un_sanitized_text_feature = $_POST['oer_curriculum_text_feature'];
                update_post_meta($post->ID, 'oer_curriculum_text_feature', sanitize_text_field($un_sanitized_text_feature));
            }

            // Save elements Order
            if (isset($_POST['oer_curriculum_order'])) {
              $un_sanitized_curriculum_order = $_POST['oer_curriculum_order'];
              $_sanitized_curriculum_order = array();
              foreach ($un_sanitized_curriculum_order as $key => $value) {
                $_sanitized_curriculum_order[$key] = sanitize_text_field($un_sanitized_curriculum_order[$key]);
              }
              update_post_meta($post->ID, 'oer_curriculum_order', $_sanitized_curriculum_order);
            }

            //Save download file options
            if (isset($_POST['oer_curriculum_download_copy'])) {
                $un_sanitized_download_copy = $_POST['oer_curriculum_download_copy'];
                $oer_curriculum_download_copy = sanitize_text_field($un_sanitized_download_copy);
            } else {
                $oer_curriculum_download_copy = 'no';
            }
            update_post_meta($post->ID, 'oer_curriculum_download_copy', sanitize_text_field($oer_curriculum_download_copy));

            // Save download copy document
            if (isset($_POST['oer_curriculum_download_copy_document'])) {
                $un_sanitized_download_copy_document = $_POST['oer_curriculum_download_copy_document'];
                update_post_meta($post->ID, 'oer_curriculum_download_copy_document', esc_url_raw($un_sanitized_download_copy_document));
            }

            // Save related curriculum
            if (isset($_POST['oer_curriculum_related_curriculum'])) {
                $un_sanitized_related_curriculum = $_POST['oer_curriculum_related_curriculum'];
                $_sanitized_related_curriculum = array();
                foreach ($un_sanitized_related_curriculum as $key => $value) {
                  $_sanitized_related_curriculum[$key] = sanitize_text_field($un_sanitized_related_curriculum[$key]);
                }
                update_post_meta($post->ID, 'oer_curriculum_related_curriculum', $_sanitized_related_curriculum);
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
    $totalElements = isset($_REQUEST['row_id']) ? sanitize_text_field($_REQUEST['row_id']) : '15';
    ?>
    <div class="card col card-default oercurr-ac-item" id="oercurr-ac-item-<?php echo esc_attr($totalElements) ?>">
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
            <?php
            ob_start(); // Start output buffer
            wp_editor('',
                'oercurr-activity-detail-' . esc_attr($totalElements),
                $settings = array(
                    'textarea_name' => 'oer_curriculum_activity_detail[]',
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'drag_drop_upload' => true,
                    'teeny' => true,
                    'relative_urls' => false,
                )
            );
            echo ob_get_clean();
            ?>
            </div>
        </div>
    </div>';

    <?php
    exit();
}

/**
 * Add more primary resource
 */
add_action('wp_ajax_oercurr_add_more_prime_resource_callback', 'oercurr_add_more_prime_resource_callback');
add_action('wp_ajax_nopriv_oercurr_add_more_prime_resource_callback', 'oercurr_add_more_prime_resource_callback');

function oercurr_add_more_prime_resource_callback() {
    $totalElements = isset($_REQUEST['row_id']) ? sanitize_text_field($_REQUEST['row_id']) : '25';
    $prType = isset($_REQUEST['type']) ? sanitize_text_field($_REQUEST['type']) : 'resource';
    //RESOURCE FIELD TYPE
    
    if($prType == 'resource'){
      ?>
      <div class="card col card-default oercurr-primary-resource-element-wrapper" id="oercurr-primary-resource-element-wrapper-<?php echo esc_attr($totalElements) ?>">
          <div class="card-header">
              <h3 class="card-title oercurr-module-title">
                  <?php esc_html_e('Resource',OERCURR_CURRICULUM_SLUG); ?>
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
                    <label><?php esc_html_e("Thumbnail Image",OERCURR_CURRICULUM_SLUG) ?></label>
                    <div class="oer_primary_resource_thumbnail_holder"></div>
                    <button name="oer_curriculum_primary_resources_thumbnail_button" class="oer_curriculum_primary_resources_thumbnail_button" class="ui-button" alt="Set Thumbnail Image"><?php esc_html_e("Set Thumbnail",OERCURR_CURRICULUM_SLUG) ?></button>
                    <input type="hidden" name="oer_curriculum_primary_resources[image][]" class="oer_primary_resourceurl" value="" />
                </div>
            </div>
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <div class="oer_curriculum_primary_resources_image_wrappper">
                            <label><?php esc_html_e('Resource', OERCURR_CURRICULUM_SLUG) ?></label>
                            <div class="oer_curriculum_primary_resources_image">
                              <div class="oer_curriculum_primary_resources_image_preloader" style="display:none;">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                              </div>
                              <div class="oer_curriculum_primary_resources_image_display">
                                <div class="oer_curriculum_primary_resources_display"><p><?php esc_html_e('You have not selected a resource', OERCURR_CURRICULUM_SLUG) ?></p></div>
                                <input type="hidden" name="oer_curriculum_primary_resources[resource][]" value="">
                                <input type="button" class="button oercurr-resource-selector-button" value="<?php esc_html_e("Select Resource",OERCURR_CURRICULUM_SLUG) ?>">
                              </div>
                            </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-5">
                      <div class="checkbox pull-right">
                          <label>
                              <input type="hidden" name="oer_curriculum_primary_resources[field_type][]" value="<?php echo esc_attr($prType) ?> ">
                              <input type="hidden" name="oer_curriculum_primary_resources[sensitive_material_value][]" value="no">
                              <input type="checkbox" name="oer_curriculum_primary_resources[sensitive_material][]" value="yes">
                              <?php esc_html_e("Sensitive Material",OERCURR_CURRICULUM_SLUG) ?>
                          </label>
                      </div>
                  </div>
              </div>
      <?php        
      //TEXTBOX FIELD TYPE
      }else{
      ?>
              <div class="card col card-default oercurr-primary-resource-element-wrapper" id="oercurr-primary-resource-element-wrapper-<?php echo esc_attr($totalElements) ?>">
                  <div class="card-header">
                      <h3 class="card-title oercurr-module-title">
                          <?php esc_html_e('Textbox',OERCURR_CURRICULUM_SLUG); ?>
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
                                  <label><?php esc_html_e("Thumbnail Image",OERCURR_CURRICULUM_SLUG) ?></label>
                                  <div class="oer_primary_resource_thumbnail_holder"></div>
                                  <button name="oer_curriculum_primary_resources_thumbnail_button" class="oer_curriculum_primary_resources_thumbnail_button" class="ui-button" alt="Set Thumbnail Image"><?php esc_html_e("Set Thumbnail",OERCURR_CURRICULUM_SLUG) ?></button>
                                  <input type="hidden" name="oer_curriculum_primary_resources[image][]" class="oer_primary_resourceurl" value="" />
                              </div>
                              <div class="col-md-5">
                                  <div class="checkbox pull-right">
                                      <label>
                                          <input type="hidden" name="oer_curriculum_primary_resources[resource][]" value="">
                                          <input type="hidden" name="oer_curriculum_primary_resources[field_type][]" value="<?php echo esc_attr($prType) ?>">
                                          <input type="hidden" name="oer_curriculum_primary_resources[sensitive_material_value][]" value="no">
                                          <input type="checkbox" name="oer_curriculum_primary_resources[sensitive_material][]" value="yes">
                                          <?php esc_html_e("Sensitive Material",OERCURR_CURRICULUM_SLUG) ?>
                                      </label>
                                  </div>
                              </div>
                          </div>
                      </div>
                      
      <?php
      }
      ?>
      
                      <div class="form-group">
                          <label><?php esc_html_e("Title",OERCURR_CURRICULUM_SLUG) ?></label>
                          <input type="text"
                          class="form-control"
                          name="oer_curriculum_primary_resources[title][]"
                          placeholder="<?php esc_html_e("Resource Title",OERCURR_CURRICULUM_SLUG) ?>"
                          value="">
                      </div>
                      <div class="form-group">
                          <label><?php esc_html_e("Description",OERCURR_CURRICULUM_SLUG) ?></label>

                              <?php
                              ob_start(); // Start output buffer
                              wp_editor('',
                                  'oercurr-resource-student-' . esc_attr($totalElements),
                                  $settings = array(
                                      'textarea_name' => 'oer_curriculum_primary_resources[description][]',
                                      'media_buttons' => true,
                                      'textarea_rows' => 6,
                                      'drag_drop_upload' => true,
                                      'teeny' => true,
                                      'relative_urls' => false,
                                      'wpautop' => false
                                  )
                              );
                              echo ob_get_clean();
                              ?>

                      </div>
                  </div>
              </div>
    <?php

    
    exit();
}

/**
 * Create dynamic module
 */
add_action('wp_ajax_oercurr_create_module_callback', 'oercurr_create_module_callback');
add_action('wp_ajax_nopriv_oercurr_create_module_callback', 'oercurr_create_module_callback');

function oercurr_create_module_callback() {
    $module_type = isset($_REQUEST['module_type']) ? sanitize_text_field($_REQUEST['module_type']) : 'editor';
    $element_id = isset($_REQUEST['row_id']) ? sanitize_text_field($_REQUEST['row_id']) : '15';
    $_oercurr_allowed_html = oercurr_allowed_html();

    if ($module_type == 'editor') {
        echo wp_kses(oercurr_create_dynamic_editor($element_id), $_oercurr_allowed_html);
        exit();
    } elseif ($module_type == 'list') {
        echo wp_kses(oercurr_create_dynamic_text_list($element_id), $_oercurr_allowed_html);
    } elseif ($module_type == 'vocabulary') {
        echo wp_kses(oercurr_create_dynamic_vocabulary_list($element_id), $_oercurr_allowed_html);
    } elseif ($module_type == 'materials') {
        echo wp_kses(oercurr_create_dynamic_materials_module($element_id), $_oercurr_allowed_html);
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
      $_resid = sanitize_text_field($_POST['resid']);
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
ob_start(); // Start output buffer
    ?>
    <div class="card col card-default oercurr-element-wrapper oercurr-introduction-group" id="oercurr-custom-editor-group-<?php echo esc_attr($id) ?>">
        <input type="hidden" name="oer_curriculum_order[oer_curriculum_custom_editor_<?php echo esc_attr($id) ?>]" class="element-order" value="<?php echo esc_attr($id) ?>">
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
                <label><?php esc_html_e("Title",OERCURR_CURRICULUM_SLUG) ?></label>
                <input type="text" name="oer_curriculum_custom_editor_<?php echo esc_attr($id) ?>[title]" maxlength="512" class="form-control" placeholder="Text Module Title" />
            </div>
            <div class="form-group">
            <?php  
            wp_editor('',
                'oercurr-custom-editor-' . esc_attr($id),
                $settings = array(
                    'textarea_name' => 'oer_curriculum_custom_editor_' . esc_attr($id) . '[description]',
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'drag_drop_upload' => true,
                    'teeny' => true,
                    'relative_urls' => false,
                )
            );
            ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Create dynamic text list
 * @param $id
 * @return string
 */
function oercurr_create_dynamic_text_list($id) {
    ob_start(); // Start output buffer
    ?>
        <div class="card col card-default oercurr-element-wrapper" id="oercurr-text-list-group<?php echo esc_attr($id) ?>">
            <input type="hidden" name="oer_curriculum_order[oer_curriculum_custom_text_list_<?php echo esc_attr($id) ?>]" class="element-order" value="<?php echo esc_attr($id) ?>">
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
                    <div class="oercurr-text-list-row" id="oercurr-text-list-row<?php echo esc_attr($id) ?>">
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text"
                                       class="form-control"
                                       name="oer_curriculum_custom_text_list_<?php echo esc_attr($id) ?>[]"
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
        </div>

    <?php
    return ob_get_clean();
}

/**
 * Create dynamic vocabulary list
 * @param $id
 * @return string
 */
function oercurr_create_dynamic_vocabulary_list($id) {
    ob_start(); // Start output buffer
    ?>
        <div class="card col card-default oercurr-element-wrapper" id="oercurr-vocabulary-list-group<?php echo esc_attr($id) ?>">
            <input type="hidden" name="oer_curriculum_order[oer_curriculum_vocabulary_list_title_<?php echo esc_attr($id) ?>]" class="element-order" value="<?php echo esc_attr($id) ?>">
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
                           name="oer_curriculum_vocabulary_list_title_<?php echo esc_attr($id) ?>"
                    >
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="oer_curriculum_vocabulary_details_<?php echo esc_attr($id) ?>" rows="6"></textarea>
                </div>
            </div>
        </div>
    <?php
    return ob_get_clean();
}

if (! function_exists('oercurr_create_dynamic_materials_module')) {
    /**
     * Create dynamic vocabulary list
     * @param $id
     * @return string
     */
    function oercurr_create_dynamic_materials_module($id) {
        ob_start(); // Start output buffer
        ?>
            <div class="card col card-default oercurr-element-wrapper" id="oercurr-materials-<?php echo esc_attr($id) ?>">
                <input type="hidden" name="oer_curriculum_order[oer_curriculum_oer_materials_list_<?php echo esc_attr($id) ?>]" class="element-order" value="<?php echo esc_attr($id) ?>">
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
                    <button type="button" data-type="custom" data-name="oer_curriculum_oer_materials_list_<?php echo esc_attr($id) ?>" class="btn btn-default oercurr-add-materials"><i class="fa fa-plus"></i> Add Materials</button>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }
}
/**
 * Hide installation notice
 */
add_action('wp_ajax_oercurr_dismiss_notice_callback', 'oercurr_dismiss_notice_callback');
add_action('wp_ajax_nopriv_oercurr_dismiss_notice_callback', 'oercurr_dismiss_notice_callback');
function oercurr_dismiss_notice_callback() {
    update_option('oer_curriculum_setup_notification',false);
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
        $post_id = sanitize_text_field($_POST['post_id']);
    }
    if (isset($_POST['keyword'])){
        $keyword = sanitize_text_field($_POST['keyword']);
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
        $source = sanitize_text_field($_POST['next_source']);

    if (isset($_POST['curriculum']))
        $curriculum_id = sanitize_text_field($_POST['curriculum']);

    if (isset($_POST['index']))
        $source_id = sanitize_text_field($_POST['index']);

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

    $element_id = (isset($_REQUEST['row_id']))? sanitize_text_field($_REQUEST['row_id']):1;
    $ed_id = (isset($_REQUEST['editor_id'])? sanitize_text_field($_REQUEST['editor_id']):'oercurr-additional-section-');
    $req_mat = (isset($_REQUEST['required_material'])?true:false);
    $element_id++;

    if ($req_mat){
        $label_id = "oer_curriculum_required_materials[label][]";
        $editor_id = "oer_curriculum_required_materials[editor][]";
    } else {
        $label_id = "oer_curriculum_additional_sections[label][]";
        $editor_id = "oer_curriculum_additional_sections[editor][]";
    }
    ob_start(); // Start output buffer
    ?>
    <div class="card col card-default oercurr-section-element-wrapper" id="oer_curriculum_section_element_wrapper-<?php echo esc_attr($element_id) ?>">
       <div class="card-header">
           <h3 class="card-title oercurr-module-title">
               <?php esc_html_e("Section", OERCURR_CURRICULUM_SLUG); ?>
               <span class="oercurr-sortable-handle">
                   <i class="fa fa-arrow-down section-reorder-down" aria-hidden="true"></i>
                   <i class="fa fa-arrow-up section-reorder-up" aria-hidden="true"></i>
               </span>
               <button type="button" class="btn btn-danger btn-sm oercurr-remove-section" title="Delete"><i class="fa fa-trash"></i> </button>
           </h3>
       </div>
       <div class="card-body">
           <div class="form-group">
               <input type="text" class="form-control" name="<?php echo esc_attr($label_id) ?>" id="<?php echo esc_attr($label_id) ?>" placeholder="<?php echo esc_html__('Text Title', OERCURR_CURRICULUM_SLUG) ?>">
           </div>
           <div class="form-group">
               <div class="text-editor-group">
                            <?php
                            ob_start(); // Start output buffer
                            wp_editor('',
                                esc_attr($ed_id) . esc_attr($element_id),
                                $settings = array(
                                    'textarea_name' => esc_attr($editor_id),
                                    'media_buttons' => true,
                                    'textarea_rows' => 10,
                                    'drag_drop_upload' => true,
                                    'teeny' => true,
                                    'relative_urls' => false,
                                    'skin' => false
                                )
                            );
                            echo ob_get_clean();
                            ?>
               </div>
           </div>
       </div>
    </div>
    <?php
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
    add_submenu_page('edit.php?post_type=oer-curriculum',__('Settings',OERCURR_CURRICULUM_SLUG),__('Settings',OERCURR_CURRICULUM_SLUG),'add_users','oer_curriculum_settings','oercurr_settings_callback_func');
}
add_action( 'admin_menu', 'oercurr_add_curriculum_settings' );

function oercurr_settings_callback_func(){
    include_once( OERCURR_CURRICULUM_PATH."includes/oer-curriculum-settings.php" );
}

add_filter( 'wp_default_editor', function(){return "text";} );

function wpse120831_mce_css( $mce_css ) {
  if(is_admin()){
    if ( ! empty( $mce_css ) )
        $mce_css .= ',';
        
    $mce_css .= OERCURR_CURRICULUM_URL."css/backend/oer-curriculum-mce-style.css";
  }
  return $mce_css;
}

add_filter( 'mce_css', 'wpse120831_mce_css' );
