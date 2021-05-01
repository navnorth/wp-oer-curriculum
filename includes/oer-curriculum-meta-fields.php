<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;
global $wpdb;
global $oer_curriculum_default_structure;
global $oer_convert_info;
global $inquiryset_post;

$inquiryset_post = $post;
// Get all post meta for the post
$post_meta_data = get_post_meta($post->ID );
//echo "<pre>"; print_r(get_post_custom($post->ID, '', true ));

// Lesson activity data
$oer_curriculum_activity_title  = isset($post_meta_data['oer_curriculum_activity_title'][0]) ? unserialize($post_meta_data['oer_curriculum_activity_title'][0]) : array();
$oer_curriculum_activity_type   = isset($post_meta_data['oer_curriculum_activity_type'][0]) ? unserialize($post_meta_data['oer_curriculum_activity_type'][0]) : array();
$oer_curriculum_activity_detail = isset($post_meta_data['oer_curriculum_activity_detail'][0]) ? unserialize($post_meta_data['oer_curriculum_activity_detail'][0]) : array();

$elements_orders        = isset($post_meta_data['oer_curriculum_order'][0]) ? unserialize($post_meta_data['oer_curriculum_order'][0]) : array();
//was_selectable_admin_standards($post->ID, "oer_standard");
foreach ($elements_orders as $orderKey => $orderValue) {
    if (isset($post_meta_data[$orderKey]) && strpos($orderKey, 'oer_curriculum_custom_text_list_') !== false) {
      // print_r($post_meta_data[$orderKey]); echo  "<br/>";
    }
    //echo "Key -> " . $orderKey . "  value -> " . $orderValue ."<br/>";
}
$default = false;

// Check Metadata settings for label and if enabled
$type_other_set = (trim(get_option('oer_curriculum_type_other_curmetset_label'),' ') != '')?true:false;
$type_other_enabled = (get_option('oer_curriculum_type_other_curmetset_enable')=='checked')?true:false;
$author_set = (trim(get_option('oer_curriculum_authors_curmetset_label'),' ') != '')?true:false;
$author_enabled = (get_option('oer_curriculum_authors_curmetset_enable')=='checked')?true:false;
$primary_resources_set = (trim(get_option('oer_curriculum_primary_resources_curmetset_label'),' ') != '')?true:false;
$primary_resources_enabled = (get_option('oer_curriculum_primary_resources_curmetset_enable')=='checked')?true:false;
$materials_set = (trim(get_option('oer_curriculum_oer_materials_curmetset_label'),' ') != '')?true:false;
$materials_enabled = (get_option('oer_curriculum_oer_materials_curmetset_enable')=='checked')?true:false;
$iq_set = (trim(get_option('oer_curriculum_iq_curmetset_label'),' ') != '')?true:false;
$iq_enabled = (get_option('oer_curriculum_iq_curmetset_enable')=='checked')?true:false;
$req_materials_set = (trim(get_option('oer_curriculum_required_materials_curmetset_label'),' ') != '')?true:false;
$req_materials_enabled = (get_option('oer_curriculum_required_materials_curmetset_enable')=='checked')?true:false;
$additional_sections_set = (trim(get_option('oer_curriculum_additional_sections_curmetset_label'),' ') != '')?true:false;
$additional_sections_enabled = (get_option('oer_curriculum_additional_sections_curmetset_enable')=='checked')?true:false;
$history_bg_set = (trim(get_option('oer_curriculum_custom_editor_historical_background_curmetset_label'),' ') != '')?true:false;
$history_bg_enabled = (get_option('oer_curriculum_custom_editor_historical_background_curmetset_enable')=='checked')?true:false;
$type_set = (trim(get_option('oer_curriculum_type_curmetset_label'),' ') != '')?true:false;
$type_enabled = (get_option('oer_curriculum_type_curmetset_enable')=='checked')?true:false;
$standards_set = (trim(get_option('oer_curriculum_standards_curmetset_label'),' ') != '')?true:false;
$standards_enabled = (get_option('oer_curriculum_standards_curmetset_enable')=='checked')?true:false;
$objectives_set = (get_option('oer_curriculum_related_objective_curmetset_label'))?true:false;
$objectives_enabled = (get_option('oer_curriculum_related_objective_curmetset_enable') == 'checked')?true:false;
?>
<div class="oer_curriculum_meta_wrapper">
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <?php
                if (!empty($elements_orders) || isset($oer_curriculum_default_structure)) {
                    // Set order of modules
                    if (isset($oer_curriculum_default_structure) && empty($elements_orders)){
                        $default = true;
                        $index = 0;
                        foreach($oer_curriculum_default_structure as $module){
                            $index++;
                            $elements_orders[$module] = $index;
                        }
                    }
                    foreach ($elements_orders as $elementKey => $value) {
                        if($elementKey == 'oer_curriculum_introduction_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-introduction-group" title="Introduction">Introduction</a>
                            </li>
                        <?php } elseif ($elementKey == 'oer_curriculum_authors_order') {
                            if (($author_set && $author_enabled) || !$author_set) {
                            ?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-authors" title="Lesson Times">
                                    <?php
                                    echo oer_curriculum_get_field_label('oer_curriculum_authors');
                                    ?>
                                </a>
                            </li>
                        <?php }
                            } elseif ($elementKey == 'oer_curriculum_primary_resources') {
                            if (is_oer_plugin_installed()){
                                if (($primary_resources_set && $primary_resources_enabled) || !$primary_resources_set) {
                            ?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-primary-resources" title="Primary Resources">
                                    <?php
                                    echo oer_curriculum_get_field_label('oer_curriculum_primary_resources');
                                    ?>
                                </a>
                            </li>
                        <?php }
                            }
                        } elseif ($elementKey == 'oer_curriculum_oer_materials') {
                            if (($materials_set && $materials_enabled) || !$materials_set) {
                            ?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-materials" class="js-scroll-trigger" title="Materials">
                                <?php
                                echo oer_curriculum_get_field_label('oer_curriculum_oer_materials');
                                ?>
                                </a>
                            </li>
                        <?php }
                        } elseif ($elementKey == 'oer_curriculum_iq') {
                            if (($iq_set && $iq_enabled) || !$iq_set) {
                            ?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-iq" title="Investigative Question"><?php echo oer_curriculum_get_field_label('oer_curriculum_iq'); ?></a>
                            </li>
                        <?php }
                        } elseif ($elementKey == 'oer_curriculum_required_materials') {
                            if (($req_materials_set && $req_materials_enabled) || !$req_materials_set) {
                            ?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-required-materials" title="Required Materials"><?php echo oer_curriculum_get_field_label('oer_curriculum_required_materials');  ?></a>
                            </li>
                        <?php }
                        } elseif ($elementKey == 'oer_curriculum_additional_sections') {
                            if (($additional_sections_set && $additional_sections_enabled) || !$additional_sections_set) {
                            ?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-additional-sections" title="Additional Sections"><?php echo oer_curriculum_get_field_label('oer_curriculum_additional_sections');  ?></a>
                            </li>
                        <?php }
                        } elseif ($elementKey == 'oer_curriculum_lesson_times_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-times-group" title="Lesson Times">Lesson Times</a>
                            </li>
                        <?php } elseif ($elementKey == 'oer_curriculum_standard_order') {
                             if (($standards_set && $standards_enabled) || !$standards_set) { ?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-standards-group" title="Standards and Objectives"><?php echo oer_curriculum_get_field_label('oer_curriculum_standards');  ?></a>
                            </li>
                        <?php }
                        } elseif ($elementKey == 'oer_curriculum_activities_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-activities-group" title="Activities in this Lesson">Activities in this Lesson</a>
                                <ul class="list-group sidebar-lesson-activities-title">
                                    <?php
                                    if(!empty($oer_curriculum_activity_title)) {
                                        foreach ($oer_curriculum_activity_title as $key => $item) { ?>
                                            <li class="list-group-item">
                                                <strong>-</strong>
                                                <a href="#oer-curriculum-ac-item-<?php echo $key;?>" title="<?php echo $item; ?>"><?php echo $item; ?></a>
                                            </li>
                                        <?php } ?>
                                    <?php } else {
                                        for ($i = 0; $i < 5; $i++) { ?>
                                            <li class="list-group-item">
                                                <strong>-</strong>
                                                <a href="#oer-curriculum-ac-item-<?php echo $i;?>" title="Unnamed Activity">Unnamed Activity</a>
                                            </li>
                                        <?php }?>
                                    <?php }?>
                                </ul>
                            </li>
                        <?php } elseif ($elementKey == 'oer_curriculum_summative_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-summative-group" title="Summative Assessment">Summative Assessment</a>
                            </li>
                        <?php } elseif (strpos($elementKey, 'oer_curriculum_custom_editor_teacher_background') !== false) {?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-custom-editor-group-teacher-background" title="Teacher Background">Teacher Background</a>
                            </li>
                        <?php } elseif (strpos($elementKey, 'oer_curriculum_custom_editor_student_background') !== false) {?>
                            <li class="list-group-item">
                                <a href="#oer-curriculum-custom-editor-group-student-background" title="Student Background">Student Background</a>
                            </li>
                       <?php } 
                    }
                    if ($default==true)
                        $elements_orders = array();
                } else { ?>
                    <li class="list-group-item">
                        <a href="#oer-curriculum-introduction-group" title="Introduction">Introduction</a>
                    </li>
                    <?php if (($author_set && $author_enabled) || !$author_set) { ?>
                    <li class="list-group-item">
                        <a href="#oer-curriculum-authors" title="Authors"><?php echo oer_curriculum_get_field_label('oer_curriculum_authors'); ?></a>
                    </li>
                    <?php } ?>
                    <?php if (is_oer_plugin_installed()){ ?>
                    <?php if (($primary_resources_set && $primary_resources_enabled) || !$primary_resources_set) { ?>
                    <li class="list-group-item">
                        <a href="#oer-curriculum-primary-resources" title="Primary Resources"><?php echo oer_curriculum_get_field_label('oer_curriculum_primary_resources'); ?></a>
                    </li>
                    <?php } ?>
                    <?php } ?>
                    <?php if (($materials_set  && $materials_enabled ) || !$materials_set ) { ?>
                    <li class="list-group-item">
                        <a href="#oer-curriculum-materials" title="Materials"><?php echo oer_curriculum_get_field_label('oer_curriculum_oer_materials'); ?></a>
                    </li>
                    <?php } ?>
                    <?php if (($iq_set && $iq_enabled) || !$iq_set) { ?>
                    <li class="list-group-item">
                        <a href="#oer-curriculum-iq" title="Investigative Question"><?php echo oer_curriculum_get_field_label('oer_curriculum_iq'); ?></a>
                    </li>
                    <?php } ?>
                    <?php if (($req_materials_set && $req_materials_enabled) || !$req_materials_set) { ?>
                     <li class="list-group-item">
                        <a href="#oer-curriculum-required-materials" title="Required Materials"><?php echo oer_curriculum_get_field_label('oer_curriculum_required_materials'); ?></a>
                    </li>
                     <?php } ?>
                    <li class="list-group-item">
                        <a href="#oer-curriculum-times-group" title="Lesson Times">Lesson Times</a>
                    </li>
                    <?php if (($standards_set && $standards_enabled) || !$standards_set) { ?>
                    <li class="list-group-item">
                        <a href="#oer-curriculum-standards-group" title="Standards and Objectives"><?php echo oer_curriculum_get_field_label('oer_curriculum_standards');  ?></a>
                    </li>
                    <?php } ?>
                    <li class="list-group-item">
                        <a href="#oer-curriculum-activities-group" title="Activities in this Lesson">Activities in this Lesson</a>
                        <ul class="list-group sidebar-lesson-activities-title">
                            <?php
                            if(!empty($oer_curriculum_activity_title)) {
                                foreach ($oer_curriculum_activity_title as $key => $item) { ?>
                                    <li class="list-group-item">
                                        <strong>-</strong>
                                        <a href="#oer-curriculum-ac-item-<?php echo $key;?>" title="<?php echo $item; ?>"><?php echo $item; ?></a>
                                    </li>
                                <?php } ?>
                            <?php } else {
                                for ($i = 0; $i < 5; $i++) { ?>
                                    <li class="list-group-item">
                                        <strong>-</strong>
                                        <a href="#oer-curriculum-ac-item-<?php echo $i;?>" title="Unnamed Activity">Unnamed Activity</a>
                                    </li>
                                <?php }?>
                            <?php }?>
                        </ul>
                    </li>
                    <li class="list-group-item">
                        <a href="#oer-curriculum-summative-group" title="Summative Assessment">Summative Assessment</a>
                    </li>
                <?php }?>
                <li class="list-group-item">
                    <a href="#oer_curriculum_meta_related" title="Related Curriculum">Related Curriculum</a>
                </li>
            </ul>
        </div>
        <div class="col-md-8" id="oer-curriculum-sortable">
            <!-- Details Module -->
            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-details-group" id="oer-curriculum-details-group">
                <div class="card-header">
                    <h3 class="card-title oer-curriculum-module-title">
                        <?php _e("Details", OER_CURRICULUM_SLUG); ?>
                    </h3>
                </div>
                <div class="card-body">
                    <?php
                    $oer_curriculum_type = isset($post_meta_data['oer_curriculum_type'][0]) ? $post_meta_data['oer_curriculum_type'][0] : "";
                    $oer_curriculum_type_other = isset($post_meta_data['oer_curriculum_type_other'][0]) ? $post_meta_data['oer_curriculum_type_other'][0] : "";
                    $xclass = " hidden";
                    if ($type_enabled) {
                        if ($oer_curriculum_type=="Other" && $type_other_enabled)
                            $xclass = "";
                        
                    ?>
                    <div class="form-group">
                        <label for="oer_curriculum_type"><?php echo oer_curriculum_get_field_label('oer_curriculum_type'); ?></label>
                        <select name="oer_curriculum_type" id="oer_curriculum_type" class="form-control">
                            <?php echo oer_curriculum_get_curriculum_type($oer_curriculum_type); ?>
                        </select>
                    </div>
                    <div class="form-group other-type-group<?php echo $xclass; ?>">
                        <label for="oer_curriculum_type_other"><?php echo oer_curriculum_get_field_label('oer_curriculum_type_other'); ?></label>
                        <input type="text"
                               class="form-control"
                               name="oer_curriculum_type_other"
                               placeholder="Curriculum Type"
                               value="<?php echo isset($oer_curriculum_type_other) ? $oer_curriculum_type_other : "";?>"
                        >
                    </div>
                    <?php } ?>
                </div>
            </div>
            <!--For Introduction-->
            <?php if (!empty($elements_orders)) {
                foreach ($elements_orders as $elementKey => $value) {
                    if($elementKey == 'oer_curriculum_introduction_order') {?>
                        <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-introduction-group" id="oer-curriculum-introduction-group">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_introduction_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php _e("Introduction", OER_CURRICULUM_SLUG); ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php
                                $oer_curriculum_introduction = isset($post_meta_data['oer_curriculum_introduction'][0]) ? $post_meta_data['oer_curriculum_introduction'][0] : "";
                                wp_editor( $oer_curriculum_introduction,
                                    'oer-curriculum-introduction',
                                    $settings = array(
                                        'textarea_name' => 'oer_curriculum_introduction',
                                        'media_buttons' => true,
                                        'textarea_rows' => 10,
                                        'drag_drop_upload' => true,
                                        'teeny' => true,
                                    )
                                );
                                ?>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'oer_curriculum_authors_order') {
                        if (($author_set && $author_enabled) || !$author_set) { 
                        ?>
                        <?php
                        $authors = (isset($post_meta_data['oer_curriculum_authors'][0]) ? unserialize($post_meta_data['oer_curriculum_authors'][0]) : array());
                        if(!empty($authors)) { ?>
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-authors-group" id="oer-curriculum-authors">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_authors_order]" class="element-order" value="<?php echo $value;?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php echo oer_curriculum_get_field_label('oer_curriculum_authors'); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-group">
                                        <?php
                                        if (isset($authors['name']) && !empty($authors['name'])) {
                                            foreach ( $authors['name']as $authorKey => $authorName) { ?>
                                                <div class="card col card-default oer-curriculum-author-element-wrapper" id="author-<?php echo $authorKey;?>">
                                                    <div class="card-header">
                                                        <h3 class="card-title oer-curriculum-module-title">
                                                            <?php _e("Author", OER_CURRICULUM_SLUG); ?>
                                                            <span class="oer-curriculum-sortable-handle">
                                                                <i class="fa fa-arrow-down author-reorder-down" aria-hidden="true"></i>
                                                                <i class="fa fa-arrow-up author-reorder-up" aria-hidden="true"></i>
                                                            </span>
                                                            <button type="button" class="btn btn-danger btn-sm oer-curriculum-remove-author"
                                                                  title="Delete" 
                                                                  disabled="disabled"
                                                            ><i class="fa fa-trash"></i> </button>
                                                        </h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row oer-curriculum-authors-element-row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="oer_curriculum_authors[name][]"
                                                                           placeholder="Name"
                                                                           value="<?php echo $authorName;?>"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="oer_curriculum_authors[role][]"
                                                                           placeholder="Role"
                                                                           value="<?php echo isset($authors['role'][$authorKey]) ? $authors['role'][$authorKey] : "";?>"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="oer_curriculum_authors[author_url][]"
                                                                           placeholder="Author URL"
                                                                           value="<?php echo isset($authors['author_url'][$authorKey]) ? $authors['author_url'][$authorKey] : "";?>"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="oer_curriculum_authors[institution][]"
                                                                           placeholder="Institution"
                                                                           value="<?php echo isset($authors['institution'][$authorKey]) ? $authors['institution'][$authorKey] : "";?>"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="oer_curriculum_authors[institution_url][]"
                                                                           placeholder="Institution URL"
                                                                           value="<?php echo isset($authors['institution_url'][$authorKey]) ? $authors['institution_url'][$authorKey] : "";?>"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="hidden"
                                                                       name="oer_curriculum_authors[author_pic][]"
                                                                       value="<?php echo isset($authors['author_pic'][$authorKey]) ? $authors['author_pic'][$authorKey] : "";?>"
                                                                >
                                                                <?php
                                                                $image = (isset($authors['author_pic'][$authorKey]) ? $authors['author_pic'][$authorKey] : "");
                                                                if(empty($image)) {
                                                                    $image = OER_LESSON_PLAN_URL . "images/oer-curriculum-person-placeholder.png";
                                                                }
                                                                ?>
                                                                <img src="<?php echo $image;?>"
                                                                    class="img-circle oer-curriculum-oer-person-placeholder"
                                                                    width="50px"
                                                                    height="50px"/>
                                                            </div>
                                                        </div><!-- /.row -->
                                                    </div>
                                                </div>
                                            <?php }
                                        }
                                        ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button"
                                                    id="oer-curriculum-add-more-author"
                                                    class="btn btn-light oer-curriculum-add-more-author"
                                            ><i class="fa fa-plus"></i> Add More</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        } ?>
                    <?php } elseif ($elementKey == 'oer_curriculum_primary_resources') {
                        if (is_oer_plugin_installed()){
                            if (($primary_resources_set && $primary_resources_enabled) || !$primary_resources_set) { 
                        ?>
                        <!-- Primary Sources -->
                        <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-primary-resources" id="oer-curriculum-primary-resources">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_primary_resources]" class="element-order" value="<?php echo $value;?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php echo oer_curriculum_get_field_label('oer_curriculum_primary_resources'); ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="card-group oer-curriculum-primary-resource-element-panel">
                                    <?php
                                    $posts = get_posts([
                                        'post_type' => 'resource',
                                        'post_status' => 'publish',
                                        'numberposts' => -1,
                                        'orderby' => 'title',
                                        'order'    => 'ASC'
                                    ]);
                                    $primary_resources = (isset($post_meta_data['oer_curriculum_primary_resources'][0]) ? unserialize($post_meta_data['oer_curriculum_primary_resources'][0]) : array());
                                    if (count($primary_resources) && isset($primary_resources['resource'])) {
                                        foreach ($primary_resources['resource'] as $resourceKey => $resource) { ?>
                                            <?php
                                            $resource_field_type = (isset($primary_resources['field_type'][$resourceKey]))?$resource_field_type=$primary_resources['field_type'][$resourceKey] : $resource_field_type = 'resource';
                                            $sensitiveMaterial = (isset($primary_resources['sensitive_material'][$resourceKey]) ? $primary_resources['sensitive_material'][$resourceKey]: "");
                                            $sensitiveMaterialValue = (isset($primary_resources['sensitive_material_value'][$resourceKey]) ? $primary_resources['sensitive_material_value'][$resourceKey]: "");
                                            if ($sensitiveMaterialValue!=="")
                                                $sensitiveMaterial = $sensitiveMaterialValue;
                                                
                                            $teacherInfo = (isset($primary_resources['teacher_info'][$resourceKey]) ? $primary_resources['teacher_info'][$resourceKey]: "");
                                            $studentInfo = (isset($primary_resources['student_info'][$resourceKey]) ? $primary_resources['student_info'][$resourceKey]: "");
                                            $custom_thumbnail = (isset($primary_resources['image'][$resourceKey]) ? $primary_resources['image'][$resourceKey]: "");
                                            ?>
                                          
                                          <!-- RESOURCE FIELD TYPE --> 
                                          <div class="card col card-default oer-curriculum-primary-resource-element-wrapper">
                                          <?php if($resource_field_type == 'resource'){ ?>
                                                <div class="card-header">
                                                    <h3 class="card-title oer-curriculum-module-title">
                                                        <?php _e("Resource", OER_CURRICULUM_SLUG); ?>
                                                        <span class="oer-curriculum-sortable-handle">
                                                            <i class="fa fa-arrow-down resource-reorder-down" aria-hidden="true"></i>
                                                            <i class="fa fa-arrow-up resource-reorder-up" aria-hidden="true"></i>
                                                        </span>
                                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-source"
                                                              title="Delete"
                                                              <?php echo ((count($primary_resources) == 1) ? 'disabled="disabled"' : '');?>
                                                        ><i class="fa fa-trash"></i> </span>
                                                    </h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <label>Thumbnail Image</label>
                                                            <div class="oer_primary_resource_thumbnail_holder">
                                                                <?php if (!empty($custom_thumbnail)): ?>
                                                                <img src="<?php echo $custom_thumbnail; ?>" class="resource-thumbnail" width="200">
                                                                <span class="btn btn-danger btn-sm oer-curriculum-remove-source-featured-image" title="Remove Thumbnail"><i class="fas fa-minus-circle"></i></span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <button name="oer_curriculum_primary_resources_thumbnail_button" class="oer_curriculum_primary_resources_thumbnail_button" class="ui-button" alt="Set Thumbnail Image">Set Thumbnail</button>
                                                            <input type="hidden" name="oer_curriculum_primary_resources[image][]" class="oer_primary_resourceurl" value="<?php echo $custom_thumbnail; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <div class="oer_curriculum_primary_resources_image_wrappper">
                                                                  <label>Resource</label>
                                                              
                                                                  <?php $btn_text = (htmlspecialchars($resource) > '')? 'Change Resource' : 'Select Resource' ?>
                                                                  
                                                                  <div class="oer_curriculum_primary_resources_image">
                                                                    <div class="oer_curriculum_primary_resources_image_preloader" style="display:none;">
                                                                      <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                                                    </div>
                                                                    <div class="oer_curriculum_primary_resources_image_display">
                                                                      
                                                                      <div class="oer_curriculum_primary_resources_display">
                                                                        <?php 
                                                                        if(!empty($resource)){
                                                                            $rsrc = get_page_by_title($resource,OBJECT,"resource");
                                                                            $url = get_permalink($rsrc->ID);
                                                                            $type = get_post_meta($rsrc->ID,"oer_mediatype")[0];
                                                                            $rsrcThumbID = get_post_thumbnail_id($rsrc);
                                                                            $resource_img='';
                                                                            if (!empty($rsrcThumbID)){
                                                                                $resource_img = wp_get_attachment_image_url(get_post_thumbnail_id($rsrc), 'resource-thumbnail' );
                                                                                ?><a href="<?php echo $url; ?>" target="_blank"><img src="<?php echo $resource_img ?>"/></a><br><?php echo htmlspecialchars($resource);
                                                                            }else{
                                                                              $_avtr = getResourceIcon($type,$url);
                                                                              ?><a href="<?php echo $url; ?>" target="_blank"><div class="resource-avatar"><span class="dashicons <?php echo $_avtr; ?>"></span></div></a><?php
                                                                            }
                                                                        }else{
                                                                          ?><p>You have not selected a resources</p><?php
                                                                        }
                                                                        ?>
                                                                        <?php echo htmlspecialchars($resource);?>
                                                                      
                                                                      </div>
                                                                      <input type="hidden" name="oer_curriculum_primary_resources[resource][]" value="<?php echo htmlspecialchars($resource);?>">
                                                                      <input type="button" class="button oer-curriculum-resource-selector-button" value="<?php echo $btn_text; ?>">

                                                                    </div>
                                                                    
                                                                  </div>
                                                                  
                                                                  
                                                                </div>
                                                              
                                                                
                                                                <!--
                                                                <select name="oer_curriculum_primary_resources[resource][]" itm="1" class="form-control">
                                                                    <option value="">Select Resource</option>
                                                                    <?php
                                                                    if (count($posts)) {
                                                                        foreach ($posts as $post) {
                                                                            ?>
                                                                            <option value="<?php echo $post->post_title;?>" <?php echo ((htmlspecialchars($resource) == $post->post_title) ? 'selected="selected"' : "");?>><?php echo $post->post_title;?></option>
                                                                        <?php }
                                                                    }
                                                                    ?>
                                                                </select>
                                                              -->
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="checkbox pull-right">
                                                                <label>
                                                                    <input type="hidden" name="oer_curriculum_primary_resources[field_type][]" value="<?php echo $resource_field_type; ?>">
                                                                    <input type="hidden"
                                                                           name="oer_curriculum_primary_resources[sensitive_material_value][]"
                                                                           value="<?php echo (($sensitiveMaterial == 'yes')? 'yes' : 'no'); ?>"
                                                                    >
                                                                    <input type="checkbox"
                                                                           name="oer_curriculum_primary_resources[sensitive_material][]"
                                                                           value="yes"
                                                                           <?php echo (($sensitiveMaterial == 'yes')? 'checked="checked"' : '');?>
                                                                    >
                                                                    Sensitive Material
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                          <!-- TEXTBOX FIELD TYPE -->
                                          <?php }else{ ?>
                                                <div class="card-header">
                                                    <h3 class="card-title oer-curriculum-module-title">
                                                        <?php _e("Textbox", OER_CURRICULUM_SLUG); ?>
                                                        <span class="oer-curriculum-sortable-handle">
                                                            <i class="fa fa-arrow-down resource-reorder-down" aria-hidden="true"></i>
                                                            <i class="fa fa-arrow-up resource-reorder-up" aria-hidden="true"></i>
                                                        </span>
                                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-source"
                                                              title="Delete"
                                                              <?php echo ((count($primary_resources) == 1) ? 'disabled="disabled"' : '');?>
                                                        ><i class="fa fa-trash"></i> </span>
                                                    </h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <label>Thumbnail Image</label>
                                                                <div class="oer_primary_resource_thumbnail_holder">
                                                                    <?php if (!empty($custom_thumbnail)): ?>
                                                                    <img src="<?php echo $custom_thumbnail; ?>" class="resource-thumbnail" width="200">
                                                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-source-featured-image" title="Remove Thumbnail"><i class="fas fa-minus-circle"></i></span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <button name="oer_curriculum_primary_resources_thumbnail_button" class="oer_curriculum_primary_resources_thumbnail_button" class="ui-button" alt="Set Thumbnail Image">Set Thumbnail</button>
                                                                <input type="hidden" name="oer_curriculum_primary_resources[image][]" class="oer_primary_resourceurl" value="<?php echo $custom_thumbnail; ?>" />
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="checkbox pull-left">
                                                                    <label>
                                                                        <input type="hidden" name="oer_curriculum_primary_resources[resource][]" itm="2" value="">
                                                                        <input type="hidden" name="oer_curriculum_primary_resources[field_type][]" value="<?php echo $resource_field_type; ?>">
                                                                        <input type="hidden"
                                                                               name="oer_curriculum_primary_resources[sensitive_material_value][]"
                                                                               value="<?php echo (($sensitiveMaterial == 'yes')? 'yes' : 'no'); ?>"
                                                                        >
                                                                        <input type="checkbox"
                                                                               name="oer_curriculum_primary_resources[sensitive_material][]"
                                                                               value="yes"
                                                                               <?php echo (($sensitiveMaterial == 'yes')? 'checked="checked"' : '');?>
                                                                        >
                                                                        Sensitive Material
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                          <?php } ?>
                                                    <div class="form-group">
                                                        <label>Title</label>
                                                          <input type="text"
                                                              class="form-control"
                                                              name="oer_curriculum_primary_resources[title][]"
                                                              placeholder="Resource Title"
                                                              value="<?php echo isset($primary_resources['title'][$resourceKey]) ? $primary_resources['title'][$resourceKey] : "";?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <?php $resource_description = (isset($primary_resources['description'][$resourceKey]) ? $primary_resources['description'][$resourceKey]: ""); ?>
                                                        <label>Description</label>
                                                        <?php wp_editor( $resource_description,
                                                            'oer-curriculum-resource-student-' . $resourceKey,
                                                            $settings = array(
                                                                'textarea_name' => 'oer_curriculum_primary_resources[description][]',
                                                                'media_buttons' => true,
                                                                'textarea_rows' => 6,
                                                                'drag_drop_upload' => true,
                                                                'teeny' => true,
                                                                
                                                            )
                                                        ); ?>
                                                    </div>
                                                    <!--
                                                    <div class="form-group">
                                                        <?php if ($oer_convert_info): ?>
                                                        <label>Title</label>
                                                        <input type="text"
                                                            class="form-control"
                                                            name="oer_curriculum_primary_resources[title][]"
                                                            placeholder="Resource Title"
                                                            value="<?php echo isset($primary_resources['title'][$resourceKey]) ? $primary_resources['title'][$resourceKey] : "";?>">
                                                        <?php else: ?>
                                                        <label>Teacher Information</label>
                                                        <?php wp_editor( $teacherInfo,
                                                           'oer-curriculum-resource-teacher-' . $resourceKey,
                                                           $settings = array(
                                                               'textarea_name' => 'oer_curriculum_primary_resources[teacher_info][]',
                                                               'media_buttons' => true,
                                                               'textarea_rows' => 6,
                                                               'drag_drop_upload' => true,
                                                               'teeny' => true,
                                                           )
                                                       ); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php if ($oer_convert_info): 
                                                        $resource_description = (isset($primary_resources['description'][$resourceKey]) ? $primary_resources['description'][$resourceKey]: "");
                                                        ?>
                                                        <label>Description</label>
                                                        <?php wp_editor( $resource_description,
                                                            'oer-curriculum-resource-student-' . $resourceKey,
                                                            $settings = array(
                                                                'textarea_name' => 'oer_curriculum_primary_resources[description][]',
                                                                'media_buttons' => true,
                                                                'textarea_rows' => 6,
                                                                'drag_drop_upload' => true,
                                                                'teeny' => true,
                                                            )
                                                        ); ?>
                                                        <?php else: ?>
                                                        <label>Student Information</label>
                                                        <?php wp_editor( $studentInfo,
                                                            'oer-curriculum-resource-student-' . $resourceKey,
                                                            $settings = array(
                                                                'textarea_name' => 'oer_curriculum_primary_resources[student_info][]',
                                                                'media_buttons' => true,
                                                                'textarea_rows' => 6,
                                                                'drag_drop_upload' => true,
                                                                'teeny' => true,
                                                            )
                                                        ); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                  -->
                                                </div>
                                            </div>
                                        <?php }
                                    } else {?>
                                        <div class="card col card-default oer-curriculum-primary-source-element-wrapper">
                                            <div class="card-header">
                                                <h3 class="card-title oer-curriculum-module-title">
                                                    <?php _e("Resource", OER_CURRICULUM_SLUG); ?>
                                                    <span class="oer-curriculum-sortable-handle">
                                                    <i class="fa fa-arrow-down resource-reorder-down" aria-hidden="true"></i>
                                                    <i class="fa fa-arrow-up resource-reorder-up" aria-hidden="true"></i>
                                                </span>
                                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-source"
                                                          title="Delete"
                                                          disabled="disabled"
                                                    ><i class="fa fa-trash"></i> </span>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <?php
                                                    $posts = get_posts([
                                                        'post_type' => 'resource',
                                                        'post_status' => 'publish',
                                                        'numberposts' => -1,
                                                        'orderby' => 'title',
                                                        'order'    => 'ASC'
                                                    ]);
                                                    ?>
                                                    <select name="oer_curriculum_primary_resources[resource][]" itm="3" class="form-control">
                                                        <option>Select Resource</option>
                                                        <?php
                                                        if (count($posts)) {
                                                            foreach ($posts as $post) {
                                                                echo '<option value="'.$post->post_title.'">'.$post->post_title.'</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="checkbox pull-right">
                                                        <label>
                                                            <input type="hidden" name="oer_curriculum_primary_resources[field_type][]" value="resource">
                                                            <input type="hidden" name="oer_curriculum_primary_resources[sensitive_material_value][]" value="no">
                                                            <input type="checkbox" name="oer_curriculum_primary_resources[sensitive_material][]" value="yes">
                                                            Sensitive Material
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Title</label>
                                                      <input type="text"
                                                          class="form-control"
                                                          name="oer_curriculum_primary_resources[title][]"
                                                          placeholder="Resource Title"
                                                          value="<?php echo isset($primary_resources['title'][$resourceKey]) ? $primary_resources['title'][$resourceKey] : "";?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <?php wp_editor( '',
                                                        'oer-curriculum-resource-student-1',
                                                        $settings = array(
                                                            'textarea_name' => 'oer_curriculum_primary_resources[description][]',
                                                            'media_buttons' => true,
                                                            'textarea_rows' => 6,
                                                            'drag_drop_upload' => true,
                                                            'teeny' => true,
                                                        )
                                                    ); ?>
                                                </div>
                                                <!--
                                                <div class="form-group">
                                                    <label>Teacher Information</label>
                                                    <?php wp_editor( '',
                                                        'oer-curriculum-resource-teacher-1',
                                                        $settings = array(
                                                            'textarea_name' => 'oer_curriculum_primary_resources[teacher_info][]',
                                                            'media_buttons' => true,
                                                            'textarea_rows' => 6,
                                                            'drag_drop_upload' => true,
                                                            'teeny' => true,
                                                        )
                                                    ); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label>Student Information</label>
                                                    <?php wp_editor( '',
                                                        'oer-curriculum-resource-student-1',
                                                        $settings = array(
                                                            'textarea_name' => 'oer_curriculum_primary_resources[student_info][]',
                                                            'media_buttons' => true,
                                                            'textarea_rows' => 6,
                                                            'drag_drop_upload' => true,
                                                            'teeny' => true,
                                                        )
                                                    ); ?>
                                                </div>
                                                -->
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button"
                                                id="oer-curriculum-add-more-resource"
                                                class="btn btn-light oer-curriculum-add-more-resource" 
                                                typ="resource"
                                        ><i class="fa fa-plus"></i> Add a Resource</button>
                                        &nbsp;&nbsp;
                                        <button type="button"
                                                id="oer-curriculum-add-more-textbox"
                                                class="btn btn-light oer-curriculum-add-more-resource" 
                                                typ="textbox"
                                        ><i class="fa fa-plus"></i> Add Textbox</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                        }
                    } elseif ($elementKey == 'oer_curriculum_oer_materials') {
                        if (($materials_set && $materials_enabled) || !$materials_set) { 
                        ?>
                        <div class="card col card-default oer-curriculum-element-wrapper" id="oer-curriculum-materials">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_oer_materials]" class="element-order" value="3">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php echo get_option('oer_curriculum_oer_materials_curmetset_label') ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i></span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="card-group oer-curriculum-materials-container" id="oer-curriculum-materials-container">
                                    <?php
                                    $materials = (isset($post_meta_data['oer_curriculum_oer_materials'][0]) ? unserialize($post_meta_data['oer_curriculum_oer_materials'][0]) : array());
                                    if (!empty($materials['url'])) {
                                        foreach ($materials['url'] as $materialKey => $material) {?>
                                            <?php
                                            $file_response = get_file_type_from_url($material);
                                            ?>
                                            <div class="card col card-default oer-curriculum-material-element-wrapper">
                                                <div class="card-header">
                                                    <h3 class="card-title oer-curriculum-module-title">
                                                        <span class="oer-curriculum-sortable-handle">
                                                            <i class="fa fa-arrow-down material-reorder-down" aria-hidden="true"></i>
                                                            <i class="fa fa-arrow-up material-reorder-up" aria-hidden="true"></i>
                                                        </span>
                                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-material" title="Delete"><i class="fa fa-trash"></i></span>
                                                    </h3>
                                                    </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="oer_curriculum_oer_materials[url][]"
                                                                   placeholder="URL"
                                                                   value="<?php echo $material;?>"
                                                            >
                                                            <div class="input-group-addon oer-curriculum-material-icon"
                                                                 title="<?php echo isset($file_response['title']) ? $file_response['title'] : "";?>"
                                                            ><?php echo isset($file_response['icon']) ? $file_response['icon'] : "";?></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="oer_curriculum_oer_materials[title][]"
                                                               placeholder="Title"
                                                               value="<?php echo $materials['title'][$materialKey]?>"
                                                        >
                                                    </div>
                                                    <div class="form-group">
                                                        <textarea class="form-control"
                                                                  name="oer_curriculum_oer_materials[description][]"
                                                                  rows="6"
                                                                  placeholder="Description"
                                                        ><?php echo $materials['description'][$materialKey]?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                    }?>
                                </div>
                                <button type="button"
                                        id="oer-curriculum-add-materials"
                                        class="btn btn-light oer-curriculum-add-materials"
                                ><i class="fa fa-plus"></i> Add Materials</button>
                            </div>
                        </div>
                    <?php }
                    } elseif ($elementKey == 'oer_curriculum_iq') {
                        if (($iq_set && $iq_enabled) || !$iq_set) { 
                        ?>
                        <!--Investigative Question Module-->
                        <?php
                        $oer_curriculum_iq  = isset($post_meta_data['oer_curriculum_iq'][0]) ? unserialize($post_meta_data['oer_curriculum_iq'][0]) : array();
                        ?>
                        <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-iq" id="oer-curriculum-iq">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_iq]" class="element-order" value="<?php echo $value;?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php echo oer_curriculum_get_field_label('oer_curriculum_iq'); ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Investigative Question</label>
                                    <input type="text"
                                           name="oer_curriculum_iq[question]"
                                           maxlength="512"
                                           class="form-control"
                                           placeholder="Investigative Question"
                                           value="<?php echo (isset($oer_curriculum_iq['question']) ? $oer_curriculum_iq['question'] : "")?>"
                                    >
                                </div>
                                <div class="form-group">
                                    <label>Framework Excerpt</label>
                                    <?php wp_editor( (isset($oer_curriculum_iq['excerpt']) ? $oer_curriculum_iq['excerpt'] : ""),
                                        'oer_curriculum_iq_excerpt',
                                        $settings = array(
                                            'textarea_name' => 'oer_curriculum_iq[excerpt]',
                                            'media_buttons' => true,
                                            'textarea_rows' => 6,
                                            'drag_drop_upload' => true,
                                            'teeny' => true,
                                        )
                                    ); ?>
                                </div>
                            </div>
                        </div>
                    <?php }
                    } elseif ($elementKey == 'oer_curriculum_required_materials') {
                        if (($req_materials_set && $req_materials_enabled) || !$req_materials_set) {
                        ?>
                        <!--Required Equipment Materials Module-->
                        <?php
                        $oer_curriculum_required_materials  = isset($post_meta_data['oer_curriculum_required_materials'][0]) ? $post_meta_data['oer_curriculum_required_materials'][0] : array();
                        $oer_curriculum_required_materials_label = isset($post_meta_data['oer_curriculum_required_materials_label'][0]) ? $post_meta_data['oer_curriculum_required_materials_label'][0] : "";
                        ?>
                        <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-required-materials" id="oer-curriculum-required-materials">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_required_materials]" class="element-order" value="<?php echo $value;?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                <?php echo trim(get_option('oer_curriculum_required_materials_curmetset_label'),' ') ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php
                                $text_features = isset($post_meta_data['oer_curriculum_required_materials'][0]) ? unserialize($post_meta_data['oer_curriculum_required_materials'][0]) : array();
                                if (is_array($text_features) && !empty($text_features)){
                                    $label_id = "oer_curriculum_required_materials[label][]";
                                    $editor_id = "oer_curriculum_required_materials[editor][]";
                                    
                                    $cnt = 0;
                                    if (isset($text_features['label']))
                                        $cnt = count($text_features['label']);
                                    if (isset($text_features['editor'])){
                                        $cnt = (count($text_features['editor'])>$cnt) ? count($text_features['editor']) : $cnt;
                                    }
                                    for ($i=0;$i<$cnt;$i++){
                                ?>
                                <div class="card col card-default oer-curriculum-section-element-wrapper">
                                    <div class="card-header">
                                        <h3 class="card-title oer-curriculum-module-title">
                                            <?php _e("Section", OER_CURRICULUM_SLUG); ?>
                                            <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down section-reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up section-reorder-up" aria-hidden="true"></i>
                                        </span>
                                            <button type="button" class="btn btn-danger btn-sm oer-curriculum-remove-section"
                                                  title="Delete"
                                            ><i class="fa fa-trash"></i> </button>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        
                                       <?php
                                            echo '<div class="form-group">';
                                            echo '<input type="text" class="form-control" name="'.$label_id.'" id="'.$label_id.'" value="'.$text_features['label'][$i].'">';
                                            echo '</div>';
                                            echo '<div class="form-group">';
                                            wp_editor( (isset($text_features['editor'][$i]) ? $text_features['editor'][$i] : ""),
                                                'oer-curriculum-required-materials-section-' . ($i + 1),
                                                $settings = array(
                                                    'textarea_name' => $editor_id,
                                                    'media_buttons' => true,
                                                    'textarea_rows' => 10,
                                                    'drag_drop_upload' => true,
                                                    'teeny' => true,
                                                )
                                            );
                                            echo '</div>';
                                        ?>
                                        
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <div class="button-row form-group">
                                    <button id="addMatlBtn" class="btn btn-primary"><?php _e("Add Section", OER_CURRICULUM_SLUG); ?></button>
                                </div>
                            </div>
                        </div>
                    <?php }
                    } elseif ($elementKey == 'oer_curriculum_additional_sections') {
                        if (($additional_sections_set && $additional_sections_enabled) || !$additional_sections_set) {
                        ?>
                        <!--Required Equipment Materials Module-->
                        <?php
                        $oer_curriculum_additional_sections  = isset($post_meta_data['oer_curriculum_additional_sections'][0]) ? $post_meta_data['oer_curriculum_additional_sections'][0] : array();
                        $oer_curriculum_additional_sections_label = isset($post_meta_data['oer_curriculum_additional_sections_label'][0]) ? $post_meta_data['oer_curriculum_additional_sections_label'][0] : "";
                        ?>
                        <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-required-materials" id="oer-curriculum-additional-sections">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_additional_sections]" class="element-order" value="<?php echo $value;?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                <?php echo oer_curriculum_get_field_label('oer_curriculum_additional_sections'); ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php
                                $text_features = isset($post_meta_data['oer_curriculum_additional_sections'][0]) ? unserialize($post_meta_data['oer_curriculum_additional_sections'][0]) : array();
                                if (is_array($text_features)){
                                    $label_id = "oer_curriculum_additional_sections[label][]";
                                    $editor_id = "oer_curriculum_additional_sections[editor][]";
                                    
                                    $cnt = 0;
                                    if (isset($text_features['label']))
                                        $cnt = count($text_features['label']);
                                    if (isset($text_features['editor'])){
                                        $cnt = (count($text_features['editor'])>$cnt) ? count($text_features['editor']) : $cnt;
                                    }
                                    for ($i=0;$i<$cnt;$i++){
                                ?>
                                <div class="card col card-default oer-curriculum-section-element-wrapper">
                                    <div class="card-header">
                                        <h3 class="card-title oer-curriculum-module-title">
                                            <?php _e("Section", OER_CURRICULUM_SLUG); ?>
                                            <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down section-reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up section-reorder-up" aria-hidden="true"></i>
                                        </span>
                                            <button type="button" class="btn btn-danger btn-sm oer-curriculum-remove-section"
                                                  title="Delete" 
                                            ><i class="fa fa-trash"></i> </button>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        
                                       <?php
                                            echo '<div class="form-group">';
                                            echo '<input type="text" class="form-control" name="'.$label_id.'" id="'.$label_id.'" value="'.$text_features['label'][$i].'">';
                                            echo '</div>';
                                            echo '<div class="form-group">';
                                            wp_editor( (isset($text_features['editor'][$i]) ? $text_features['editor'][$i] : ""),
                                                'oer-curriculum-additional-sections-editor-' . ($i + 1),
                                                $settings = array(
                                                    'textarea_name' => $editor_id,
                                                    'media_buttons' => true,
                                                    'textarea_rows' => 10,
                                                    'drag_drop_upload' => true,
                                                    'teeny' => true,
                                                )
                                            );
                                            echo '</div>';
                                        ?>
                                        
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                                <div class="button-row form-group">
                                    <button id="addTxtBtn" class="btn btn-primary"><?php _e("Add Section", OER_CURRICULUM_SLUG); ?></button>
                                </div>
                            </div>
                        </div>
                    <?php }
                    } elseif ($elementKey == 'oer_curriculum_lesson_times_order') {?>
                        <!--For Lesson Times-->
                        <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-times-group" id="oer-curriculum-times-group">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_lesson_times_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php _e("Lesson Times", OER_CURRICULUM_SLUG); ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php
                                $oer_curriculum_times_label  = isset($post_meta_data['oer_curriculum_times_label'][0]) ? unserialize($post_meta_data['oer_curriculum_times_label'][0]) : array();
                                $oer_curriculum_times_number = isset($post_meta_data['oer_curriculum_times_number'][0]) ? unserialize($post_meta_data['oer_curriculum_times_number'][0]) : array();
                                $oer_curriculum_times_type   = isset($post_meta_data['oer_curriculum_times_type'][0]) ? unserialize($post_meta_data['oer_curriculum_times_type'][0]) : array();
                                ?>

                                <?php
                                /**
                                 * Check if lesson time data available the show the value pre fill
                                 */
                                if(!empty($oer_curriculum_times_label)){
                                    foreach ($oer_curriculum_times_label as $key => $item){?>
                                        <div class="row oer-curriculum-time-element-row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control"
                                                           name="oer_curriculum_times_label[]"
                                                           value="<?php echo $item;?>"
                                                           placeholder="label">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control"
                                                           name="oer_curriculum_times_number[]"
                                                           value="<?php echo isset($oer_curriculum_times_number[$key]) ? $oer_curriculum_times_number[$key] : '';?>"
                                                           placeholder="40">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="oer_curriculum_times_type[]" class="form-control">
                                                        <option value="minutes" <?php echo (isset($oer_curriculum_times_type[$key]) ? oer_curriculum_show_selected('minutes', $oer_curriculum_times_type[$key]) : '');?>>Minute(s)</option>
                                                        <option value="hours" <?php echo (isset($oer_curriculum_times_type[$key]) ? oer_curriculum_show_selected('hours', $oer_curriculum_times_type[$key]) : '');?>>Hour(s)</option>
                                                        <option value="days" <?php echo (isset($oer_curriculum_times_type[$key]) ? oer_curriculum_show_selected('days', $oer_curriculum_times_type[$key]) : '');?>>Days(s)</option>
                                                        <option value="class_periods" <?php echo (isset($oer_curriculum_times_type[$key]) ? oer_curriculum_show_selected('class_periods', $oer_curriculum_times_type[$key]) : '');?>>Class Period(s)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <button type="button"
                                                            class="btn btn-danger remove-time-element"
                                                        <?php if(count($oer_curriculum_times_label) == 1) echo 'disabled="disabled"';?>
                                                    ><i class="fa fa-trash"></i> </button>
                                                </div>
                                            </div>
                                        </div><!-- /.row -->
                                    <?php }?>
                                <?php } else {?>
                                    <div class="row oer-curriculum-time-element-row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_curriculum_times_label[]" placeholder="label">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_curriculum_times_number[]" placeholder="40">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select name="oer_curriculum_times_type[]" class="form-control">
                                                    <option value="minutes">Minute(s)</option>
                                                    <option value="hours">Hour(s)</option>
                                                    <option value="days">Days(s)</option>
                                                    <option value="class_periods">Class Period(s)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <button type="button"
                                                        class="btn btn-danger remove-time-element"
                                                        disabled="disabled"
                                                ><i class="fa fa-trash"></i> </button>
                                            </div>
                                        </div>
                                    </div><!-- /.row -->
                                <?php }?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button"
                                                class="btn btn-light oer-curriculum-add-time-element"
                                        ><i class="fa fa-plus"></i> Add Time Element</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'oer_curriculum_standard_order') { ?>
                        <!--For Standards and Objectives -->
                        <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-standards-group" id="oer-curriculum-standards-group">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_standard_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php _e("Standards and Objectives", OER_CURRICULUM_SLUG); ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php if (is_standards_plugin_installed()) {
                                    if (($standards_set && $standards_enabled) || !$standards_set) { ?>
                                <h4 class="page-title-inner"><?php _e("Standards", OER_CURRICULUM_SLUG); ?></h4>

                                <div id="selected-standard-wrapper">
                                    <?php
                                    $standards = (isset($post_meta_data['oer_curriculum_standards'][0])? $post_meta_data['oer_curriculum_standards'][0] : "");
                                    get_standard_notations_from_ids($standards, true);
                                    ?>
                                </div>
                                <input type="hidden" name="oer_curriculum_standards" value="<?php echo $standards;?>">
                                <div class="row">
                                    <div class="col-md-12 pb-4">
                                        <button type="button"
                                                id="oer-curriculum-select-standard"
                                                class="btn btn-primary"
                                        >Select Standards</button>
                                    </div>
                                </div>
                                <?php }
                                } ?>
                                
                                <?php if (($objectives_set && $objectives_enabled) || !$objectives_set) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><?php echo get_option('oer_curriculum_related_objective_curmetset_label') ?></h4>
                                    </div>

                                    <?php
                                    $oer_curriculum_related_objective  = isset($post_meta_data['oer_curriculum_related_objective'][0]) ? unserialize($post_meta_data['oer_curriculum_related_objective'][0]) : array();
                                    if(!empty($oer_curriculum_related_objective)) {
                                        foreach ( $oer_curriculum_related_objective as $key => $item) { ?>
                                            <div class="row col-12 oer-curriculum-related-objective-row" id="oer-curriculum-related-objective-row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="oer_curriculum_related_objective[]"
                                                               value="<?php echo $item;?>"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button"
                                                            class="btn btn-danger oer-curriculum-remove-related-objective"
                                                        <?php if(count($oer_curriculum_related_objective) == 1) echo 'disabled="disabled"';?>
                                                    ><i class="fa fa-trash"></i> </button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="row col-12 oer-curriculum-related-objective-row" id="oer-curriculum-related-objective-row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control"
                                                           name="oer_curriculum_related_objective[]"
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button"
                                                        class="btn btn-danger oer-curriculum-remove-related-objective"
                                                        disabled="disabled"
                                                ><i class="fa fa-trash"></i> </button>
                                            </div>
                                        </div>
                                    <?php } ?>    
                                </div><!-- /.row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button"
                                                class="btn btn-light oer-curriculum-add-related-objective"
                                        ><i class="fa fa-plus"></i> Add Objective</button>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'oer_curriculum_activities_order') {?>
                        <!--Activities in this lesson-->
                        <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-activities-group" id="oer-curriculum-activities-group">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_activities_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php _e("Activities in this Lesson", OER_CURRICULUM_SLUG); ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="card-group" id="oer-curriculum-ac-inner-panel">
                                    <?php
                                    if(!empty($oer_curriculum_activity_title)) {
                                        foreach ($oer_curriculum_activity_title as $key => $item) { ?>
                                            <div class="card col card-default oer-curriculum-ac-item" id="oer-curriculum-ac-item-<?php echo $key;?>">
                                                <!--<input type="hidden" name="oer_curriculum_activity_order[oer_curriculum_activities_order]" class="element-activity-order" value="">-->
                                                <!--<span class="oer-curriculum-inner-sortable-handle">
                                                    <i class="fa fa-arrow-down activity-reorder-down" aria-hidden="true"></i>
                                                    <i class="fa fa-arrow-up activity-reorder-up" aria-hidden="true"></i>
                                                </span>-->
                                                <div class="card-header">
                                                    <h3 class="card-title oer-curriculum-module-title">
                                                        <span class="oer-curriculum-sortable-handle">
                                                            <i class="fa fa-arrow-down activity-reorder-down" aria-hidden="true"></i>
                                                            <i class="fa fa-arrow-up activity-reorder-up" aria-hidden="true"></i>
                                                        </span>
                                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                                    </h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>Activity Title</label>
                                                        <input type="text"
                                                               name="oer_curriculum_activity_title[]"
                                                               class="form-control"
                                                               placeholder="Activity Title"
                                                               value="<?php echo $item; ?>"
                                                        >
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-8">
                                                            <label for="activity-title">Activity Type</label>
                                                            <select name="oer_curriculum_activity_type[]" class="form-control">
                                                                <option value=""> - Activity Type -</option>
                                                                <option value="hooks_set" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('hooks_set', $oer_curriculum_activity_type[$key]) : "");?>>Hooks / Set</option>
                                                                <option value="lecture" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('lecture', $oer_curriculum_activity_type[$key]) : "");?>>Lecture</option>
                                                                <option value="demonstration" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('demonstration', $oer_curriculum_activity_type[$key]) : "");?>>Demo / Modeling</option>
                                                                <option value="independent_practice" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('independent_practice', $oer_curriculum_activity_type[$key]) : "");?>>Independent Practice</option>
                                                                <option value="guided_practice" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('guided_practice', $oer_curriculum_activity_type[$key]) : "");?>>Guided Practice</option>
                                                                <option value="check_understanding" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('check_understanding', $oer_curriculum_activity_type[$key]) : "");?>>Check Understanding</option>
                                                                <option value="lab_shop" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('lab_shop', $oer_curriculum_activity_type[$key]) : "");?>>Lab / Shop</option>
                                                                <option value="group_work" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('group_work', $oer_curriculum_activity_type[$key]) : "");?>>Group Work</option>
                                                                <option value="projects" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('projects', $oer_curriculum_activity_type[$key]) : "");?>>Projects</option>
                                                                <option value="assessment" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('assessment', $oer_curriculum_activity_type[$key]) : "");?>>Formative Assessment</option>
                                                                <option value="closure" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('closure', $oer_curriculum_activity_type[$key]) : "");?>>Closure</option>
                                                                <option value="research" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('research', $oer_curriculum_activity_type[$key]) : "");?>>Research / Annotate</option>
                                                                <option value="other" <?php echo (isset($oer_curriculum_activity_type[$key]) ? oer_curriculum_show_selected('other', $oer_curriculum_activity_type[$key]) : "");?>>Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                        $content = isset($oer_curriculum_activity_detail[$key]) ? $oer_curriculum_activity_detail[$key] : "";
                                                        wp_editor( $content,
                                                            'oer-curriculum-activity-detail-'.$key,
                                                            $settings = array(
                                                                'textarea_name' => 'oer_curriculum_activity_detail[]',
                                                                'media_buttons' => true,
                                                                'textarea_rows' => 10,
                                                                'drag_drop_upload' => true,
                                                                'teeny' => true,
                                                            )
                                                        ); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php }?>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button"
                                                class="btn btn-light oer-curriculum-add-ac-item"
                                                data-url="<?php echo admin_url('admin-index.php')?>"
                                        ><i class="fa fa-plus"></i> Add Activity</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'oer_curriculum_summative_order') {?>
                        <!--Summative Assessment-->
                        <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-summative-group" id="oer-curriculum-summative-group">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_summative_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php _e("Summative Assessment", OER_CURRICULUM_SLUG); ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <h4><?php _e("Assessment Type(s):", OER_CURRICULUM_SLUG); ?></h4>
                                <div class="row">
                                    <?php
                                    $oer_curriculum_assessment_type = (isset($post_meta_data['oer_curriculum_assessment_type'][0]) ? unserialize($post_meta_data['oer_curriculum_assessment_type'][0]) : array());
                                    // Prepare array for the Assessment options checkboxes
                                    $assessment_options = array(
                                        'demonstrations' => 'Demonstrations',
                                        'interviews' => 'Interviews',
                                        'journals' => 'Journals',
                                        'observations' => 'Observations',
                                        'portfolios' => 'Portfolios',
                                        'projects' => 'Projects',
                                        'rubrics' => 'Rubrics',
                                        'surveys' => 'surveys',
                                        'teacher_made_test' => 'Teacher-Made Test',
                                        'writing_samples' => 'Writing Samples',
                                    );

                                    foreach ($assessment_options as $key => $assessment_option) { ?>
                                        <div class="col-md-3">
                                            <div class="checkbox oer-summative-checkbox">
                                                <label>
                                                    <input name="oer_curriculum_assessment_type[]"
                                                           type="checkbox"
                                                           value="<?php echo $key;?>"
                                                        <?php echo oer_curriculum_show_selected($key, $oer_curriculum_assessment_type, 'checkbox')?>
                                                    > <?php echo $assessment_option; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="row">
                                    <?php
                                    $oer_curriculum_other_assessment_type = (isset($post_meta_data['oer_curriculum_other_assessment_type'][0]) ? $post_meta_data['oer_curriculum_other_assessment_type'][0] : '');
                                    ?>
                                    <div class="form-group col-md-8">
                                        <label><?php _e("Other", OER_CURRICULUM_SLUG); ?></label>
                                        <input type="text"
                                               name="oer_curriculum_other_assessment_type"
                                               class="form-control"
                                               placeholder="Other Assessment Type(s)"
                                               value="<?php echo $oer_curriculum_other_assessment_type;?>"
                                        >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php
                                    $oer_curriculum_assessment = (isset($post_meta_data['oer_curriculum_assessment'][0]) ? $post_meta_data['oer_curriculum_assessment'][0] : '');
                                    wp_editor( $oer_curriculum_assessment,
                                        'oer-curriculum-other-assessment',
                                        $settings = array(
                                            'textarea_name' => 'oer_curriculum_assessment',
                                            'media_buttons' => true,
                                            'textarea_rows' => 10,
                                            'drag_drop_upload' => true,
                                            'teeny' => true,
                                        )
                                    ); ?>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'oer_curriculum_custom_editor_teacher_background' || $elementKey == 'oer_curriculum_custom_editor_student_background') {
                        $group_id = 'oer-curriculum-custom-editor-group-'.$key;
                        if ($elementKey == 'oer_curriculum_custom_editor_teacher_background')
                            $group_id = 'oer-curriculum-custom-editor-group-teacher-background';
                        else
                            $group_id = 'oer-curriculum-custom-editor-group-student-background';
                        $oer_curriculum_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                        ?>
                        <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-introduction-group" id="<?php echo $group_id; ?>">
                            <input type="hidden" name="oer_curriculum_order[<?php echo $elementKey;?>]" class="element-order" value="<?php echo $value;?>" value="1">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php echo $oer_curriculum_custom_editor['title']; ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                 <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="<?php echo $elementKey; ?>[title]" maxlength="512" class="form-control" placeholder="Text Module Title" value="<?php echo $oer_curriculum_custom_editor['title']; ?>" />
                                </div>
                                <div class="form-group">
                                <?php
                                wp_editor( $oer_curriculum_custom_editor['description'],
                                    'oer-curriculum-custom-editor-'.$value,
                                    $settings = array(
                                        'textarea_name' => $elementKey."[description]",
                                        'media_buttons' => true,
                                        'textarea_rows' => 10,
                                        'drag_drop_upload' => true,
                                        'teeny' => true,
                                    )
                                );
                                ?>
                                </div>
                            </div>
                        </div>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_custom_editor_') !== false) {?>
                        <?php
                        if ($elementKey!=="oer_curriculum_custom_editor_teacher_background" && $elementKey!=="oer_curriculum_custom_editor_student_background") {
                        $oer_curriculum_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                        ?>
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-introduction-group" id="oer-curriculum-custom-editor-group-<?php echo $key; ?>">
                                <input type="hidden" name="oer_curriculum_order[<?php echo $elementKey;?>]" class="element-order" value="<?php echo $value;?>" value="1">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php echo $oer_curriculum_custom_editor['title']; ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                     <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="oer_curriculum_custom_editor_<?php echo $value; ?>[title]" maxlength="512" class="form-control" placeholder="Text Module Title" value="<?php echo $oer_curriculum_custom_editor['title']; ?>" />
                                    </div>
                                    <div class="form-group">
                                    <?php
                                    wp_editor( $oer_curriculum_custom_editor['description'],
                                        'oer-curriculum-custom-editor-'.$value,
                                        $settings = array(
                                            'textarea_name' => "oer_curriculum_custom_editor_" . $value ."[description]",
                                            'media_buttons' => true,
                                            'textarea_rows' => 10,
                                            'drag_drop_upload' => true,
                                            'teeny' => true,
                                        )
                                    );
                                    ?>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_custom_text_list_') !== false) {?>
                        <?php
                        $oer_curriculum_custom_text_list = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : array());
                        //echo "<pre>"; echo $elementKey; print_r($post_meta_data[$elementKey]);
                        if (!empty($oer_curriculum_custom_text_list)) {
                            foreach ($oer_curriculum_custom_text_list as $key => $list) { ?>
                                <div class="card col card-default oer-curriculum-element-wrapper" id="oer-curriculum-text-list-group-<?php echo $key;?>">
                                    <input type="hidden" name="oer_curriculum_order[<?php echo $elementKey;?>]" class="element-order" value="<?php echo $value;?>">
                                    <div class="card-header">
                                        <h3 class="card-title oer-curriculum-module-title">
                                            <?php _e("Text List", OER_CURRICULUM_SLUG); ?>
                                            <span class="oer-curriculum-sortable-handle">
                                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                            </span>
                                            <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="oer-curriculum-text-list-row" id="oer-curriculum-text-list-row<?php echo $key;?>">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="<?php echo $elementKey;?>[]"
                                                               value="<?php echo $list;?>"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button"
                                                            class="btn btn-danger oer-curriculum-remove-text-list"
                                                            disabled="disabled"
                                                    ><i class="fa fa-trash"></i> </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        }
                        ?>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_oer_materials_list_') !== false) {?>
                        <div class="card col card-default oer-curriculum-element-wrapper" id="oer-curriculum-materials-<?php echo $value;?>">
                            <input type="hidden" name="<?php echo $elementKey?>" class="element-order" value="<?php echo $value?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php _e("Materials", OER_CURRICULUM_SLUG); ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i></span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="card-group oer-curriculum-materials-container" id="oer-curriculum-materials-container-<?php echo $value;?>">
                                    <?php
                                    $materials = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : array());
                                    if (!empty($materials['url'])) {
                                        foreach ($materials['url'] as $materialKey => $material) {?>
                                            <?php
                                            $file_response = get_file_type_from_url($material);
                                            ?>
                                            <div class="card col-12 card-default oer-curriculum-material-element-wrapper">
                                                <div class="card-header">
                                                    <h3 class="card-title oer-curriculum-module-title">
                                                        <span class="oer-curriculum-sortable-handle">
                                                            <i class="fa fa-arrow-down material-reorder-down" aria-hidden="true"></i>
                                                            <i class="fa fa-arrow-up material-reorder-up" aria-hidden="true"></i>
                                                        </span>
                                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-material" title="Delete"><i class="fa fa-trash"></i></span>
                                                    </h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="<?php echo $elementKey;?>[url][]"
                                                                   placeholder="URL"
                                                                   value="<?php echo $material;?>">
                                                            <div class="input-group-addon oer-curriculum-material-icon"
                                                                 title="<?php echo isset($file_response['title']) ? $file_response['title'] : "";?>"
                                                            ><?php echo isset($file_response['icon']) ? $file_response['icon'] : "";?></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="<?php echo $elementKey;?>[title][]"
                                                               placeholder="Title"
                                                               value="<?php echo $materials['title'][$materialKey]?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <textarea class="form-control"
                                                                  name="<?php echo $elementKey;?>[description][]"
                                                                  rows="6"
                                                                  placeholder="Description"
                                                        ><?php echo $materials['description'][$materialKey]?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                    }?>
                                </div>
                                <button type="button"
                                        id="oer-curriculum-add-materials"
                                        class="btn btn-light oer-curriculum-add-materials"
                                ><i class="fa fa-plus"></i> Add Materials</button>
                            </div>
                        </div>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_vocabulary_list_title_') !== false) {?>
                        <?php
                        $oer_curriculum_vocabulary_list_title = (isset($post_meta_data[$elementKey][0]) ? $post_meta_data[$elementKey][0] : "");
                        $oer_keys = explode('_', $elementKey);
                        $listOrder = end($oer_keys);
                        $oer_curriculum_vocabulary_details = (isset($post_meta_data['oer_curriculum_vocabulary_details_'.$listOrder][0]) ? $post_meta_data['oer_curriculum_vocabulary_details_'.$listOrder][0] : "");
                        ?>
                            <div class="card col card-default oer-curriculum-element-wrapper" id="oer-curriculum-vocabulary-list-group-<?php echo $key;?>">
                                <input type="hidden" name="oer_curriculum_order[<?php echo $elementKey?>]" class="element-order" value="<?php echo $value;?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php _e("Vocabulary List", OER_CURRICULUM_SLUG); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="text"
                                               class="form-control"
                                               name="<?php echo $elementKey;?>"
                                               value="<?php echo $oer_curriculum_vocabulary_list_title;?>"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="oer_curriculum_vocabulary_details_<?php echo $listOrder;?>" rows="6"><?php echo $oer_curriculum_vocabulary_details;?></textarea>
                                    </div>
                                </div>
                            </div>
                    <?php }
                }
            } else { ?>
                <?php
                 // Set order of modules
                if (!empty($oer_curriculum_default_structure)){
                    $index=0;
                    foreach($oer_curriculum_default_structure as $module){
                        $index++;
                        if ($module=="oer_curriculum_introduction_order") {
                            ?>
                            <!-- Introduction Module -->
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-introduction-group" id="oer-curriculum-introduction-group">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_introduction_order]" class="element-order" value="<?php echo $index; ?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php _e("Introduction", OER_CURRICULUM_SLUG); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $oer_curriculum_introduction = isset($post_meta_data['oer_curriculum_introduction'][0]) ? $post_meta_data['oer_curriculum_introduction'][0] : "";
                                    wp_editor( $oer_curriculum_introduction,
                                        'oer-curriculum-introduction',
                                        $settings = array(
                                            'textarea_name' => 'oer_curriculum_introduction',
                                            'media_buttons' => true,
                                            'textarea_rows' => 10,
                                            'drag_drop_upload' => true,
                                            'teeny' => true,
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                            <?php
                        } elseif ($module=="oer_curriculum_authors_order"){
                            if (($author_set && $author_enabled) || !$author_set) { 
                            ?>
                            <!--Authors-->
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-authors-group" id="oer-curriculum-authors">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_authors_order]" class="element-order" value="<?php echo $index; ?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php echo oer_curriculum_get_field_label('oer_curriculum_authors'); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-group">
                                        <div class="card col card-default oer-curriculum-author-element-wrapper">
                                            <div class="card-header">
                                                <h3 class="card-title oer-curriculum-module-title">
                                                    <?php _e("Author", OER_CURRICULUM_SLUG); ?>
                                                    <span class="oer-curriculum-sortable-handle">
                                                        <i class="fa fa-arrow-down author-reorder-down" aria-hidden="true"></i>
                                                        <i class="fa fa-arrow-up author-reorder-up" aria-hidden="true"></i>
                                                    </span>
                                                    <button type="button" class="btn btn-danger btn-sm oer-curriculum-remove-author"
                                                          title="Delete"
                                                          disabled="disabled"
                                                    ><i class="fa fa-trash"></i> </button>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row oer-curriculum-authors-element-row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="oer_curriculum_authors[name][]" placeholder="Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="oer_curriculum_authors[role][]" placeholder="Role">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="oer_curriculum_authors[author_url][]" placeholder="Author URL">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="oer_curriculum_authors[institution][]" placeholder="Institution">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="oer_curriculum_authors[institution_url][]" placeholder="Institution URL">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="hidden" name="oer_curriculum_authors[author_pic][]">
                                                        <img src="<?php echo OER_LESSON_PLAN_URL;?>images/oer-curriculum-person-placeholder.png"
                                                            class="img-circle oer-curriculum-oer-person-placeholder"
                                                            width="50px"
                                                            height="50px"/>
                                                    </div>
            
                                                </div><!-- /.row -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button"
                                                    id="oer-curriculum-add-more-author"
                                                    class="btn btn-light oer-curriculum-add-more-author"
                                            ><i class="fa fa-plus"></i> Add More</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }
                        } elseif ($module=="oer_curriculum_primary_resources"){
                            if (is_oer_plugin_installed()){
                                if (($primary_resources_set && $primary_resources_enabled) || !$primary_resources_set) { 
                            ?>
                            <!-- Primary Sources -->
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-primary-resources" id="oer-curriculum-primary-resources">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_primary_resources]" class="element-order" value="<?php echo $index; ?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php echo oer_curriculum_get_field_label('oer_curriculum_primary_resources'); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-group oer-curriculum-primary-resource-element-panel">
                                        <div class="card col card-default oer-curriculum-primary-resource-element-wrapper">
                                            <div class="card-header">
                                                <h3 class="card-title oer-curriculum-module-title">
                                                    <?php _e("Resource", OER_CURRICULUM_SLUG); ?>
                                                    <span class="oer-curriculum-sortable-handle">
                                                    <i class="fa fa-arrow-down resource-reorder-down" aria-hidden="true"></i>
                                                    <i class="fa fa-arrow-up resource-reorder-up" aria-hidden="true"></i>
                                                </span>
                                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-source"
                                                          title="Delete"
                                                          disabled="disabled"
                                                    ><i class="fa fa-trash"></i> </span>
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                <div class="col-md-12">
                                                    <label>Thumbnail Image</label>
                                                    <div class="oer_primary_resource_thumbnail_holder"></div>
                                                    <button name="oer_curriculum_primary_resources_thumbnail_button" class="oer_curriculum_primary_resources_thumbnail_button" class="ui-button" alt="Set Thumbnail Image">Set Thumbnail</button>
                                                    <input type="hidden" name="oer_curriculum_primary_resources[image][]" class="oer_primary_resourceurl" value="" />
                                                </div></div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="oer_curriculum_primary_resources_image_wrappper">
                                                              <label>Resource</label>
                                                              <?php
                                                              $posts = get_posts([
                                                                  'post_type' => 'resource',
                                                                  'post_status' => 'publish',
                                                                  'numberposts' => -1,
                                                                  'orderby' => 'title',
                                                                  'order'    => 'ASC'
                                                              ]);
                                                              ?>
                                                              <div class="oer_curriculum_primary_resources_image">
                                                                <div class="oer_curriculum_primary_resources_image_preloader" style="display:none;">
                                                                  <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                                                </div>
                                                                <div class="oer_curriculum_primary_resources_image_display">
                                                                  <div class="oer_curriculum_primary_resources_display"></div>
                                                                  <input type="hidden" name="oer_curriculum_primary_resources[resource][]" value="">
                                                                  <input type="button" class="button oer-curriculum-resource-selector-button" value="Select Resource">
                                                                </div>
                                                              </div>
                                                            </div>

                                                            <!--
                                                            <select name="oer_curriculum_primary_resources[resource][]" itm="4" class="form-control">
                                                                <option>Select Resource</option>
                                                                <?php
                                                                if (count($posts)) {
                                                                    foreach ($posts as $post) {
                                                                        echo '<option value="'.$post->post_title.'">'.$post->post_title.'</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            -->
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="checkbox pull-right">
                                                            <label>
                                                                <input type="hidden" name="oer_curriculum_primary_resources[field_type][]" value="resource">
                                                                <input type="hidden" name="oer_curriculum_primary_resources[sensitive_material_value][]" value="no">
                                                                <input type="checkbox" name="oer_curriculum_primary_resources[sensitive_material][]" value="yes">
                                                                Sensitive Material
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Title</label>
                                                      <input type="text"
                                                          class="form-control"
                                                          name="oer_curriculum_primary_resources[title][]"
                                                          placeholder="Resource Title"
                                                          value="">
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <?php wp_editor( '',
                                                        'oer-curriculum-resource-student-1',
                                                        $settings = array(
                                                            'textarea_name' => 'oer_curriculum_primary_resources[description][]',
                                                            'media_buttons' => true,
                                                            'textarea_rows' => 6,
                                                            'drag_drop_upload' => true,
                                                            'teeny' => true,
                                                        )
                                                    ); ?>
                                                </div>
                                                <!--
                                                <div class="form-group">
                                                    <label>Teacher Information</label>
                                                    <?php wp_editor( '',
                                                        'oer-curriculum-resource-teacher-1',
                                                        $settings = array(
                                                            'textarea_name' => 'oer_curriculum_primary_resources[teacher_info][]',
                                                            'media_buttons' => true,
                                                            'textarea_rows' => 6,
                                                            'drag_drop_upload' => true,
                                                            'teeny' => true,
                                                        )
                                                    ); ?>
                                                </div>
                                                <div class="form-group">
                                                    <label>Student Information</label>
                                                    <?php wp_editor( '',
                                                        'oer-curriculum-resource-student-1',
                                                        $settings = array(
                                                            'textarea_name' => 'oer_curriculum_primary_resources[student_info][]',
                                                            'media_buttons' => true,
                                                            'textarea_rows' => 6,
                                                            'drag_drop_upload' => true,
                                                            'teeny' => true,
                                                        )
                                                    ); ?>
                                                </div>
                                                -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button"
                                                    id="oer-curriculum-add-more-resource"
                                                    class="btn btn-light oer-curriculum-add-more-resource" 
                                                    typ="resource"
                                            ><i class="fa fa-plus"></i> Add a Resource</button>
                                            &nbsp;&nbsp;
                                            <button type="button"
                                                    id="oer-curriculum-add-more-textbox"
                                                    class="btn btn-light oer-curriculum-add-more-resource" 
                                                    typ="textbox"
                                            ><i class="fa fa-plus"></i> Add Textbox</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }
                            }
                        } elseif ($module=="oer_curriculum_oer_materials"){
                            if (($materials_set && $materials_enabled) || !$materials_set) { 
                            ?>
                            <!--Materials module-->
                            <div class="card col card-default oer-curriculum-element-wrapper" id="oer-curriculum-materials">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_oer_materials]" class="element-order" value="<?php echo $index; ?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php echo oer_curriculum_get_field_label('oer_curriculum_oer_materials'); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i></span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-group oer-curriculum-materials-container" id="oer-curriculum-materials-container">
                                    </div>
                                    <button type="button"
                                            id="oer-curriculum-add-materials"
                                            class="btn btn-light oer-curriculum-add-materials"
                                    ><i class="fa fa-plus"></i> Add Materials</button>
                                </div>
                            </div>
                            <?php }
                        } elseif ($module=="oer_curriculum_iq"){
                            if (($iq_set && $iq_enabled) || !$iq_set) {    
                            ?>
                            <!--Investigative Question Module-->
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-iq" id="oer-curriculum-iq">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_iq]" class="element-order" value="<?php echo $index; ?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php echo oer_curriculum_get_field_label('oer_curriculum_iq'); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Investigative Question</label>
                                        <input type="text"
                                               name="oer_curriculum_iq[question]"
                                               maxlength="512"
                                               class="form-control"
                                               placeholder="Investigative Question"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <label>Framework Excerpt</label>
                                        <?php wp_editor( '',
                                            'oer_curriculum_iq_excerpt',
                                            $settings = array(
                                                'textarea_name' => 'oer_curriculum_iq[excerpt]',
                                                'media_buttons' => true,
                                                'textarea_rows' => 6,
                                                'drag_drop_upload' => true,
                                                'teeny' => true,
                                            )
                                        ); ?>
                                    </div>
                                </div>
                            </div>
                            <?php }
                        } elseif ($module=="oer_curriculum_required_materials"){
                            if (($req_materials_set && $req_materials_enabled) || !$req_materials_set) {    
                            ?>
                            <!--Required Equipment Materials Module-->
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-required-materials" id="oer-curriculum-required-materials">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_required_materials]" class="element-order" value="<?php echo $index; ?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">                                        
                                        <?php echo oer_curriculum_get_field_label('oer_curriculum_required_materials'); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="card col card-default oer-curriculum-section-element-wrapper">
                                        <div class="card-header">
                                            <h3 class="card-title oer-curriculum-module-title">
                                                <?php _e("Section", OER_CURRICULUM_SLUG); ?>
                                                <span class="oer-curriculum-sortable-handle">
                                                <i class="fa fa-arrow-down section-reorder-down" aria-hidden="true"></i>
                                                <i class="fa fa-arrow-up section-reorder-up" aria-hidden="true"></i>
                                            </span>
                                                <button type="button" class="btn btn-danger btn-sm oer-curriculum-remove-section"
                                                      title="Delete"
                                                ><i class="fa fa-trash"></i> </button>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                               <input type="text" class="form-control" name="oer_curriculum_required_materials[label][]" placeholder="Label" id="oer_curriculum_additional_sections_label" value="">
                                           </div>
                                           <div class="form-group">
                                               <?php wp_editor( '',
                                                   'oer-curriculum-required-material-section-1',
                                                   $settings = array(
                                                       'textarea_name' => 'oer_curriculum_required_materials[editor][]',
                                                       'media_buttons' => true,
                                                       'textarea_rows' => 10,
                                                       'drag_drop_upload' => true,
                                                       'teeny' => true,
                                                   )
                                               ); ?>
                                           </div>
                                        </div>
                                    </div>
                                    <div class="button-row form-group">
                                        <button id="addMatlBtn" class="btn btn-primary"><?php _e("Add Section", OER_CURRICULUM_SLUG); ?></button>
                                    </div>
                                </div>
                            </div>
                            <?php }
                        } elseif ($module=="oer_curriculum_additional_sections"){
                            if (($additional_sections_set && $additional_sections_enabled) || !$additional_sections_set) {    
                            ?>
                            <!--Additional Sections Module-->
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-required-materials" id="oer-curriculum-additional-sections">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_additional_sections]" class="element-order" value="<?php echo $index; ?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">                                        
                                        <?php echo oer_curriculum_get_field_label('oer_curriculum_additional_sections'); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="card col card-default oer-curriculum-section-element-wrapper">
                                        <div class="card-header">
                                            <h3 class="card-title oer-curriculum-module-title">
                                                <?php _e("Section", OER_CURRICULUM_SLUG); ?>
                                                <span class="oer-curriculum-sortable-handle">
                                                <i class="fa fa-arrow-down section-reorder-down" aria-hidden="true"></i>
                                                <i class="fa fa-arrow-up section-reorder-up" aria-hidden="true"></i>
                                            </span>
                                                <button type="button" class="btn btn-danger btn-sm oer-curriculum-remove-section"
                                                      title="Delete"
                                                ><i class="fa fa-trash"></i> </button>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                               <input type="text" class="form-control" name="oer_curriculum_additional_sections[label][]" placeholder="Additional Section" id="oer_curriculum_additional_sections_label" value="">
                                           </div>
                                           <div class="form-group">
                                               <?php wp_editor( '',
                                                   'oer-curriculum-additional-section-1',
                                                   $settings = array(
                                                       'textarea_name' => 'oer_curriculum_additional_sections[editor][]',
                                                       'media_buttons' => true,
                                                       'textarea_rows' => 6,
                                                       'drag_drop_upload' => true,
                                                       'teeny' => true,
                                                   )
                                               ); ?>
                                           </div>
                                        </div>
                                    </div>
                                    <div class="button-row form-group">
                                        <button id="addTxtBtn" class="btn btn-primary"><?php _e("Add Section", OER_CURRICULUM_SLUG); ?></button>
                                    </div>
                                </div>
                            </div>
                            <?php }
                        } elseif ($module=="oer_curriculum_lesson_times_order"){
                            ?>
                            <!--For Lesson Times-->
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-times-group" id="oer-curriculum-times-group">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_lesson_times_order]" class="element-order" value="<?php echo $index; ?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php _e("Lesson Times", OER_CURRICULUM_SLUG); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row oer-curriculum-time-element-row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_curriculum_times_label[]" placeholder="label">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_curriculum_times_number[]" placeholder="40">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select name="oer_curriculum_times_type[]" class="form-control">
                                                    <option value="minutes">Minute(s)</option>
                                                    <option value="hours">Hour(s)</option>
                                                    <option value="days">Days(s)</option>
                                                    <option value="class_periods">Class Period(s)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <button type="button"
                                                        class="btn btn-danger remove-time-element"
                                                        disabled="disabled"
                                                ><i class="fa fa-trash"></i> </button>
                                            </div>
                                        </div>
                                    </div><!-- /.row -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button"
                                                    class="btn btn-light oer-curriculum-add-time-element"
                                            ><i class="fa fa-plus"></i> Add Time Element</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } elseif ($module=="oer_curriculum_standard_order"){
                            ?>
                            <!--For Standards and Objectives -->
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-standards-group" id="oer-curriculum-standards-group">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_standard_order]" class="element-order" value="<?php echo $index; ?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php _e("Standards and Objectives", OER_CURRICULUM_SLUG); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <?php if (is_standards_plugin_installed()) {
                                        if (($standards_set && $standards_enabled) || !$standards_set) { ?>
                                    <h4 class="page-title-inner"><?php _e("Standards", OER_CURRICULUM_SLUG); ?></h4>
                                    <div id="selected-standard-wrapper">
                                        <p><?php _e("You have not selected any academic standards", OER_CURRICULUM_SLUG); ?></p>
                                    </div>
                                    <input type="hidden" name="oer_curriculum_standards">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button"
                                                    id="oer-curriculum-select-standard"
                                                    class="btn btn-primary"
                                            >Select Standards</button>
                                        </div>
                                    </div>
                                    <?php }
                                    } ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Related Instructional Objectives (<span title="Students will be able to...">SWBAT...</span>)</h4>
                                        </div>
                                        <div class="row col-12 oer-curriculum-related-objective-row" id="oer-curriculum-related-objective-row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control"
                                                           name="oer_curriculum_related_objective[]"
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button"
                                                        class="btn btn-danger oer-curriculum-remove-related-objective"
                                                        disabled="disabled"
                                                ><i class="fa fa-trash"></i> </button>
                                            </div>
                                        </div>
                                    </div><!-- /.row -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button"
                                                    class="btn btn-light oer-curriculum-add-related-objective"
                                            ><i class="fa fa-plus"></i> Add Objective</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } elseif ($module=="oer_curriculum_activities_order"){
                            ?>
                            <!--Activities in this lesson-->
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-activities-group" id="oer-curriculum-activities-group">
                                <input type="hidden" name="oer_curriculum_order[oer_curriculum_activities_order]" class="element-order" value="<?php echo $index; ?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php _e("Activities in this Lesson", OER_CURRICULUM_SLUG); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="card-group" id="oer-curriculum-ac-inner-panel">
                                        <?php
                                        for ($i = 0; $i < 5; $i++) { ?>
                                            <div class="card col card-default oer-curriculum-ac-item" id="oer-curriculum-ac-item-<?php echo $i;?>">
                                                <div class="card-header">
                                                    <h3 class="card-title oer-curriculum-module-title">
                                                        <span class="oer-curriculum-sortable-handle">
                                                            <i class="fa fa-arrow-down activity-reorder-down" aria-hidden="true"></i>
                                                            <i class="fa fa-arrow-up activity-reorder-up" aria-hidden="true"></i>
                                                        </span>
                                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                                    </h3>
                                                </div>
            
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>Activity Title</label>
                                                        <input type="text" name="oer_curriculum_activity_title[]" class="form-control" placeholder="Activity Title">
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-8">
                                                            <label for="activity-title">Activity Type</label>
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
                                                    <div class="form-group">
                                                        <?php wp_editor( '',
                                                            'oer-curriculum-activity-detail-'.$i,
                                                            $settings = array(
                                                                'textarea_name' => 'oer_curriculum_activity_detail[]',
                                                                'media_buttons' => true,
                                                                'textarea_rows' => 10,
                                                                'drag_drop_upload' => true,
                                                                'teeny' => true,
                                                            )
                                                        ); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button"
                                                    class="btn btn-light oer-curriculum-add-ac-item"
                                                    data-url="<?php echo admin_url('admin-index.php')?>"
                                            ><i class="fa fa-plus"></i> Add Activity</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } elseif ($module=="oer_curriculum_summative_order"){
                            ?>
                            <!--Summative Assessment-->
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-summative-group" id="oer-curriculum-summative-group">
                            <input type="hidden" name="oer_curriculum_order[oer_curriculum_summative_order]" class="element-order" value="<?php echo $index; ?>">
                            <div class="card-header">
                                <h3 class="card-title oer-curriculum-module-title">
                                    <?php _e("Summative Assessment", OER_CURRICULUM_SLUG); ?>
                                    <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="card-body">
                                <h4><?php _e("Assessment Type(s):", OER_CURRICULUM_SLUG); ?></h4>
                                <div class="row">
                                    <?php
                                    $oer_curriculum_assessment_type = (isset($post_meta_data['oer_curriculum_assessment_type'][0]) ? unserialize($post_meta_data['oer_curriculum_assessment_type'][0]) : array());
                                    // Prepare array for the Assessment options checkboxes
                                    $assessment_options = array(
                                        'demonstrations' => 'Demonstrations',
                                        'interviews' => 'Interviews',
                                        'journals' => 'Journals',
                                        'observations' => 'Observations',
                                        'portfolios' => 'Portfolios',
                                        'projects' => 'Projects',
                                        'rubrics' => 'Rubrics',
                                        'surveys' => 'surveys',
                                        'teacher_made_test' => 'Teacher-Made Test',
                                        'writing_samples' => 'Writing Samples',
                                    );
            
                                    foreach ($assessment_options as $key => $assessment_option) { ?>
                                        <div class="col-md-3">
                                            <div class="checkbox oer-summative-checkbox">
                                                <label>
                                                    <input name="oer_curriculum_assessment_type[]"
                                                           type="checkbox"
                                                           value="<?php echo $key;?>"
                                                        <?php echo oer_curriculum_show_selected($key, $oer_curriculum_assessment_type, 'checkbox')?>
                                                    > <?php echo $assessment_option; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="row">
                                    <?php
                                    $oer_curriculum_other_assessment_type = (isset($post_meta_data['oer_curriculum_other_assessment_type'][0]) ? $post_meta_data['oer_curriculum_other_assessment_type'][0] : '');
                                    ?>
                                    <div class="form-group col-md-8">
                                        <label><?php _e("Other", OER_CURRICULUM_SLUG); ?></label>
                                        <input type="text"
                                               name="oer_curriculum_other_assessment_type"
                                               class="form-control"
                                               placeholder="Other Assessment Type(s)"
                                               value="<?php echo $oer_curriculum_other_assessment_type;?>"
                                        >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php
                                    $oer_curriculum_assessment = (isset($post_meta_data['oer_curriculum_assessment'][0]) ? $post_meta_data['oer_curriculum_assessment'][0] : '');
                                    wp_editor( $oer_curriculum_assessment,
                                        'oer-curriculum-other-assessment',
                                        $settings = array(
                                            'textarea_name' => 'oer_curriculum_assessment',
                                            'media_buttons' => true,
                                            'textarea_rows' => 10,
                                            'drag_drop_upload' => true,
                                            'teeny' => true,
                                        )
                                    ); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        } elseif($module=="oer_curriculum_custom_editor_teacher_background"){
                            ?>
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-introduction-group" id="oer-curriculum-custom-editor-group-teacher-background">
                                <input type="hidden" name="oer_curriculum_order[<?php echo $module; ?>]" class="element-order" value="<?php echo $index;?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php _e("Teacher Background", OER_CURRICULUM_SLUG); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                     <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="<?php echo $module; ?>[title]" maxlength="512" class="form-control" placeholder="Text Module Title" />
                                    </div>
                                    <div class="form-group">
                                    <?php
                                    wp_editor( '',
                                        'oer-curriculum-custom-editor-teacher-background',
                                        $settings = array(
                                            'textarea_name' => "" . $module ."[description]",
                                            'media_buttons' => true,
                                            'textarea_rows' => 10,
                                            'drag_drop_upload' => true,
                                            'teeny' => true,
                                        )
                                    );
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } elseif($module=="oer_curriculum_custom_editor_historical_background"){
                            ?>
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-introduction-group" id="oer-curriculum-custom-editor-group-historical-background">
                                <input type="hidden" name="oer_curriculum_order[<?php echo $module; ?>]" class="element-order" value="<?php echo $index;?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php _e("Historical Background", OER_CURRICULUM_SLUG); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                     <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="<?php echo $module; ?>[title]" maxlength="512" class="form-control" placeholder="" />
                                    </div>
                                    <div class="form-group">
                                    <?php
                                    wp_editor( '',
                                        'oer-curriculum-custom-editor-historical-background',
                                        $settings = array(
                                            'textarea_name' => "" . $module ."[description]",
                                            'media_buttons' => true,
                                            'textarea_rows' => 10,
                                            'drag_drop_upload' => true,
                                            'teeny' => true,
                                        )
                                    );
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                         } elseif($module=="oer_curriculum_custom_editor_student_background"){
                            ?>
                            <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-introduction-group" id="oer-curriculum-custom-editor-group-student-background">
                                <input type="hidden" name="oer_curriculum_order[<?php echo $module; ?>]" class="element-order" value="<?php echo $index;?>">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php _e("Student Background", OER_CURRICULUM_SLUG); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                     <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="<?php echo $module; ?>[title]" maxlength="512" class="form-control" placeholder="Text Module Title" />
                                    </div>
                                    <div class="form-group">
                                    <?php
                                    wp_editor( '',
                                        'oer-curriculum-custom-editor-student-background',
                                        $settings = array(
                                            'textarea_name' => "" . $module ."[description]",
                                            'media_buttons' => true,
                                            'textarea_rows' => 10,
                                            'drag_drop_upload' => true,
                                            'teeny' => true,
                                        )
                                    );
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                ?>
                <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-introduction-group" id="oer-curriculum-introduction-group">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_introduction_order]" class="element-order" value="1">
                    <div class="card-header">
                        <h3 class="card-title oer-curriculum-module-title">
                            <?php _e("Introduction", OER_CURRICULUM_SLUG); ?>
                            <span class="oer-curriculum-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php
                        $oer_curriculum_introduction = isset($post_meta_data['oer_curriculum_introduction'][0]) ? $post_meta_data['oer_curriculum_introduction'][0] : "";
                        wp_editor( $oer_curriculum_introduction,
                            'oer-curriculum-introduction',
                            $settings = array(
                                'textarea_name' => 'oer_curriculum_introduction',
                                'media_buttons' => true,
                                'textarea_rows' => 10,
                                'drag_drop_upload' => true,
                                'teeny' => true,
                            )
                        );
                        ?>
                    </div>
                </div>
                <!--Authors-->
                <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-authors-group" id="oer-curriculum-authors">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_authors_order]" class="element-order" value="2">
                    <div class="card-header">
                        <h3 class="card-title oer-curriculum-module-title">
                            <?php _e("Authors", OER_CURRICULUM_SLUG); ?>
                            <span class="oer-curriculum-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="card-group">
                            <div class="card col card-default oer-curriculum-author-element-wrapper">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php _e("Author", OER_CURRICULUM_SLUG); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                            <i class="fa fa-arrow-down author-reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up author-reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <button type="button" class="btn btn-danger btn-sm oer-curriculum-remove-author"
                                              title="Delete"
                                              disabled="disabled"
                                        ><i class="fa fa-trash"></i> </button>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row oer-curriculum-authors-element-row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_curriculum_authors[name][]" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_curriculum_authors[role][]" placeholder="Role">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_curriculum_authors[author_url][]" placeholder="Author URL">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_curriculum_authors[institution][]" placeholder="Institution">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_curriculum_authors[institution_url][]" placeholder="Institution URL">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="hidden" name="oer_curriculum_authors[author_pic][]">
                                            <img src="<?php echo OER_LESSON_PLAN_URL;?>images/oer-curriculum-person-placeholder.png"
                                                 class="img-circle oer-curriculum-oer-person-placeholder"
                                                 width="50px"
                                                 height="50px"/>
                                        </div>

                                    </div><!-- /.row -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button"
                                        id="oer-curriculum-add-more-author"
                                        class="btn btn-light oer-curriculum-add-more-author"
                                ><i class="fa fa-plus"></i> Add More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (is_oer_plugin_installed()){ ?>
                <!-- Primary Sources -->
                <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-primary-resources" id="oer-curriculum-primary-resources">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_primary_resources]" class="element-order" value="3">
                    <div class="card-header">
                        <h3 class="card-title oer-curriculum-module-title">
                            <?php _e("Primary Resources", OER_CURRICULUM_SLUG); ?>
                            <span class="oer-curriculum-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="card-group oer-curriculum-primary-resource-element-panel">
                            <div class="card col card-default oer-curriculum-primary-resource-element-wrapper">
                                <div class="card-header">
                                    <h3 class="card-title oer-curriculum-module-title">
                                        <?php _e("Resource", OER_CURRICULUM_SLUG); ?>
                                        <span class="oer-curriculum-sortable-handle">
                                        <i class="fa fa-arrow-down resource-reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up resource-reorder-up" aria-hidden="true"></i>
                                    </span>
                                        <span class="btn btn-danger btn-sm oer-curriculum-remove-source"
                                              title="Delete"
                                              disabled="disabled"
                                        ><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Resource</label>
                                                <?php
                                                $posts = get_posts([
                                                    'post_type' => 'resource',
                                                    'post_status' => 'publish',
                                                    'numberposts' => -1,
                                                    'orderby' => 'title',
                                                    'order'    => 'ASC'
                                                ]);
                                                ?>
                                                <select name="oer_curriculum_primary_resources[resource][]" itm="5" class="form-control">
                                                    <option value="">Select Resource</option>
                                                    <?php
                                                    if (count($posts)) {
                                                        foreach ($posts as $post) {
                                                            echo '<option value="'.$post->post_title.'">'.$post->post_title.'</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="checkbox pull-right">
                                                <label>
                                                    <input type="hidden" name="oer_curriculum_primary_resources[field_type][]" value="resource">
                                                    <input type="hidden" name="oer_curriculum_primary_resources[sensitive_material_value][]" value="no">
                                                    <input type="checkbox" name="oer_curriculum_primary_resources[sensitive_material][]" value="yes">
                                                    Sensitive Material
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Title</label>
                                          <input type="text"
                                              class="form-control"
                                              name="oer_curriculum_primary_resources[title][]"
                                              placeholder="Resource Title"
                                              value="<?php echo isset($primary_resources['title'][$resourceKey]) ? $primary_resources['title'][$resourceKey] : "";?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <?php wp_editor( '',
                                            'oer-curriculum-resource-student-1',
                                            $settings = array(
                                                'textarea_name' => 'oer_curriculum_primary_resources[description][]',
                                                'media_buttons' => true,
                                                'textarea_rows' => 6,
                                                'drag_drop_upload' => true,
                                                'teeny' => true,
                                            )
                                        ); ?>
                                    </div>
                                    <!--
                                    <div class="form-group">
                                        <label>Teacher Information</label>
                                        <?php wp_editor( '',
                                            'oer-curriculum-resource-teacher-1',
                                            $settings = array(
                                                'textarea_name' => 'oer_curriculum_primary_resources[teacher_info][]',
                                                'media_buttons' => true,
                                                'textarea_rows' => 6,
                                                'drag_drop_upload' => true,
                                                'teeny' => true,
                                            )
                                        ); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Student Information</label>
                                        <?php wp_editor( '',
                                            'oer-curriculum-resource-student-1',
                                            $settings = array(
                                                'textarea_name' => 'oer_curriculum_primary_resources[student_info][]',
                                                'media_buttons' => true,
                                                'textarea_rows' => 6,
                                                'drag_drop_upload' => true,
                                                'teeny' => true,
                                            )
                                        ); ?>
                                    </div>
                                    -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button"
                                        id="oer-curriculum-add-more-resource"
                                        class="btn btn-light oer-curriculum-add-more-resource"
                                ><i class="fa fa-plus"></i> Add More</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <!--Materials module-->
                <div class="card col card-default oer-curriculum-element-wrapper" id="oer-curriculum-materials">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_oer_materials]" class="element-order" value="4">
                    <div class="card-header">
                        <h3 class="card-title oer-curriculum-module-title">
                            <?php _e("Materials", OER_CURRICULUM_SLUG); ?>
                            <span class="oer-curriculum-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i></span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="card-group oer-curriculum-materials-container" id="oer-curriculum-materials-container">
                        </div>
                        <button type="button"
                                id="oer-curriculum-add-materials"
                                class="btn btn-light oer-curriculum-add-materials"
                        ><i class="fa fa-plus"></i> Add Materials</button>
                    </div>
                </div>
                <!--Investigative Question Module-->
                <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-iq" id="oer-curriculum-iq">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_iq]" class="element-order" value="4">
                    <div class="card-header">
                        <h3 class="card-title oer-curriculum-module-title">
                            <?php _e("Investigative Question", OER_CURRICULUM_SLUG); ?>
                            <span class="oer-curriculum-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Investigative Question</label>
                            <input type="text"
                                   name="oer_curriculum_iq[question]"
                                   maxlength="512"
                                   class="form-control"
                                   placeholder="Investigative Question"
                            >
                        </div>
                        <div class="form-group">
                            <label>Framework Excerpt</label>
                            <?php wp_editor( '',
                                'oer_curriculum_iq_excerpt',
                                $settings = array(
                                    'textarea_name' => 'oer_curriculum_iq[excerpt]',
                                    'media_buttons' => true,
                                    'textarea_rows' => 6,
                                    'drag_drop_upload' => true,
                                    'teeny' => true,
                                )
                            ); ?>
                        </div>
                    </div>
                </div>
                <!--For Lesson Times-->
                <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-times-group" id="oer-curriculum-times-group">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_lesson_times_order]" class="element-order" value="5">
                    <div class="card-header">
                        <h3 class="card-title oer-curriculum-module-title">
                            <?php _e("Lesson Times", OER_CURRICULUM_SLUG); ?>
                            <span class="oer-curriculum-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row oer-curriculum-time-element-row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="oer_curriculum_times_label[]" placeholder="label">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="oer_curriculum_times_number[]" placeholder="40">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="oer_curriculum_times_type[]" class="form-control">
                                        <option value="minutes">Minute(s)</option>
                                        <option value="hours">Hour(s)</option>
                                        <option value="days">Days(s)</option>
                                        <option value="class_periods">Class Period(s)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button type="button"
                                            class="btn btn-danger remove-time-element"
                                            disabled="disabled"
                                    ><i class="fa fa-trash"></i> </button>
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button"
                                        class="btn btn-light oer-curriculum-add-time-element"
                                ><i class="fa fa-plus"></i> Add Time Element</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--For Standards and Objectives -->
                <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-standards-group" id="oer-curriculum-standards-group">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_standard_order]" class="element-order" value="6">
                    <div class="card-header">
                        <h3 class="card-title oer-curriculum-module-title">
                            <?php _e("Standards and Objectives", OER_CURRICULUM_SLUG); ?>
                            <span class="oer-curriculum-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (is_standards_plugin_installed()) { ?>
                        <h4 class="page-title-inner"><?php _e("Standards", OER_CURRICULUM_SLUG); ?></h4>
                        <div id="selected-standard-wrapper">
                            <p><?php _e("You have not selected any academic standards", OER_CURRICULUM_SLUG); ?></p>
                        </div>
                        <input type="hidden" name="oer_curriculum_standards" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button"
                                        id="oer-curriculum-select-standard"
                                        class="btn btn-primary"
                                >Select Standards</button>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Related Instructional Objectives (<span title="Students will be able to...">SWBAT...</span>)</h4>
                            </div>
                            <div class="row col-12 oer-curriculum-related-objective-row" id="oer-curriculum-related-objective-row">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text"
                                               class="form-control"
                                               name="oer_curriculum_related_objective[]"
                                        >
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button"
                                            class="btn btn-danger oer-curriculum-remove-related-objective"
                                            disabled="disabled"
                                    ><i class="fa fa-trash"></i> </button>
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button"
                                        class="btn btn-light oer-curriculum-add-related-objective"
                                ><i class="fa fa-plus"></i> Add Objective</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Activities in this lesson-->
                <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-activities-group" id="oer-curriculum-activities-group">
                    <input type="hidden" name="oer_curriculum_order[oer_curriculum_activities_order]" class="element-order" value="7">
                    <div class="card-header">
                        <h3 class="card-title oer-curriculum-module-title">
                            <?php _e("Activities in this Lesson", OER_CURRICULUM_SLUG); ?>
                            <span class="oer-curriculum-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="card-group" id="oer-curriculum-ac-inner-panel">
                            <?php
                            for ($i = 0; $i < 5; $i++) { ?>
                                <div class="card col card-default oer-curriculum-ac-item" id="oer-curriculum-ac-item-<?php echo $i;?>">
                                    <div class="card-header">
                                        <h3 class="card-title oer-curriculum-module-title">
                                            <span class="oer-curriculum-sortable-handle">
                                                <i class="fa fa-arrow-down activity-reorder-down" aria-hidden="true"></i>
                                                <i class="fa fa-arrow-up activity-reorder-up" aria-hidden="true"></i>
                                            </span>
                                            <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                        </h3>
                                    </div>

                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Activity Title</label>
                                            <input type="text" name="oer_curriculum_activity_title[]" class="form-control" placeholder="Activity Title">
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-8">
                                                <label for="activity-title">Activity Type</label>
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
                                        <div class="form-group">
                                            <?php wp_editor( '',
                                                'oer-curriculum-activity-detail-'.$i,
                                                $settings = array(
                                                    'textarea_name' => 'oer_curriculum_activity_detail[]',
                                                    'media_buttons' => true,
                                                    'textarea_rows' => 10,
                                                    'drag_drop_upload' => true,
                                                    'teeny' => true,
                                                )
                                            ); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button"
                                        class="btn btn-light oer-curriculum-add-ac-item"
                                        data-url="<?php echo admin_url('admin-index.php')?>"
                                ><i class="fa fa-plus"></i> Add Activity</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Summative Assessment-->
                <div class="card col card-default oer-curriculum-element-wrapper oer-curriculum-summative-group" id="oer-curriculum-summative-group">
                <input type="hidden" name="oer_curriculum_order[oer_curriculum_summative_order]" class="element-order" value="8">
                <div class="card-header">
                    <h3 class="card-title oer-curriculum-module-title">
                        <?php _e("Summative Assessment", OER_CURRICULUM_SLUG); ?>
                        <span class="oer-curriculum-sortable-handle">
                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                        </span>
                        <span class="btn btn-danger btn-sm oer-curriculum-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                    </h3>
                </div>
                <div class="card-body">
                    <h4><?php _e("Assessment Type(s):", OER_CURRICULUM_SLUG); ?></h4>
                    <div class="row">
                        <?php
                        $oer_curriculum_assessment_type = (isset($post_meta_data['oer_curriculum_assessment_type'][0]) ? unserialize($post_meta_data['oer_curriculum_assessment_type'][0]) : array());
                        // Prepare array for the Assessment options checkboxes
                        $assessment_options = array(
                            'demonstrations' => 'Demonstrations',
                            'interviews' => 'Interviews',
                            'journals' => 'Journals',
                            'observations' => 'Observations',
                            'portfolios' => 'Portfolios',
                            'projects' => 'Projects',
                            'rubrics' => 'Rubrics',
                            'surveys' => 'surveys',
                            'teacher_made_test' => 'Teacher-Made Test',
                            'writing_samples' => 'Writing Samples',
                        );

                        foreach ($assessment_options as $key => $assessment_option) { ?>
                            <div class="col-md-3">
                                <div class="checkbox oer-summative-checkbox">
                                    <label>
                                        <input name="oer_curriculum_assessment_type[]"
                                               type="checkbox"
                                               value="<?php echo $key;?>"
                                            <?php echo oer_curriculum_show_selected($key, $oer_curriculum_assessment_type, 'checkbox')?>
                                        > <?php echo $assessment_option; ?>
                                    </label>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                    <div class="row">
                        <?php
                        $oer_curriculum_other_assessment_type = (isset($post_meta_data['oer_curriculum_other_assessment_type'][0]) ? $post_meta_data['oer_curriculum_other_assessment_type'][0] : '');
                        ?>
                        <div class="form-group col-md-8">
                            <label><?php _e("Other", OER_CURRICULUM_SLUG); ?></label>
                            <input type="text"
                                   name="oer_curriculum_other_assessment_type"
                                   class="form-control"
                                   placeholder="Other Assessment Type(s)"
                                   value="<?php echo $oer_curriculum_other_assessment_type;?>"
                            >
                        </div>
                    </div>
                    <div class="form-group">
                        <?php
                        $oer_curriculum_assessment = (isset($post_meta_data['oer_curriculum_assessment'][0]) ? $post_meta_data['oer_curriculum_assessment'][0] : '');
                        wp_editor( $oer_curriculum_assessment,
                            'oer-curriculum-other-assessment',
                            $settings = array(
                                'textarea_name' => 'oer_curriculum_assessment',
                                'media_buttons' => true,
                                'textarea_rows' => 10,
                                'drag_drop_upload' => true,
                                'teeny' => true,
                            )
                        ); ?>
                    </div>
                </div>
            </div>
            <?php }
            }?>

            <?php if (in_array('oer_curriculum_add_module', $oer_curriculum_default_structure)) { ?>
            <!--Add Extra Module-->
            <div class="row">
                <div class="col-md-12">
                    <button type="button"
                            id="oer-curriculum-create-dynamic-module"
                            class="btn btn-light oer-curriculum-create-dynamic-module"
                    ><i class="fa fa-plus"></i> Add Module</button>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
