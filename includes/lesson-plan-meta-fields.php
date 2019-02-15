<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;
global $wpdb;

// Get all post meta for the post
$post_meta_data = get_post_meta($post->ID );
//echo "<pre>"; print_r(get_post_custom($post->ID, '', true ));

// Lesson activity data
$oer_lp_activity_title  = isset($post_meta_data['oer_lp_activity_title'][0]) ? unserialize($post_meta_data['oer_lp_activity_title'][0]) : array();
$oer_lp_activity_type   = isset($post_meta_data['oer_lp_activity_type'][0]) ? unserialize($post_meta_data['oer_lp_activity_type'][0]) : array();
$oer_lp_activity_detail = isset($post_meta_data['oer_lp_activity_detail'][0]) ? unserialize($post_meta_data['oer_lp_activity_detail'][0]) : array();

$elements_orders        = isset($post_meta_data['lp_order'][0]) ? unserialize($post_meta_data['lp_order'][0]) : array();
//was_selectable_admin_standards($post->ID, "oer_standard");
foreach ($elements_orders as $orderKey => $orderValue) {
    if (isset($post_meta_data[$orderKey]) && strpos($orderKey, 'oer_lp_custom_text_list_') !== false) {
      // print_r($post_meta_data[$orderKey]); echo  "<br/>";
    }
    //echo "Key -> " . $orderKey . "  value -> " . $orderValue ."<br/>";
}
?>
<div class="lesson_plan_meta_wrapper">
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <?php
                if (!empty($elements_orders)) {
                    foreach ($elements_orders as $elementKey => $value) {
                        if($elementKey == 'lp_introduction_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-lp-introduction-group" title="Introduction">Introduction</a>
                            </li>
                        <?php } elseif ($elementKey == 'lp_authors_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-lp-authors" title="Lesson Times">Authors</a>
                            </li>
                        <?php } elseif ($elementKey == 'lp_lesson_times_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-lp-times-group" title="Lesson Times">Lesson Times</a>
                            </li>
                        <?php } elseif ($elementKey == 'lp_industries_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-lp-industries-group" title="Industries / Subjects / Grades">Industries / Subjects / Grades</a>
                            </li>
                        <?php } elseif ($elementKey == 'lp_standard_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-lp-standards-group" title="Standards and Objectives">Standards and Objectives</a>
                            </li>
                        <?php } elseif ($elementKey == 'lp_activities_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-lp-activities-group" title="Activities in this Lesson">Activities in this Lesson</a>
                                <ul class="list-group sidebar-lesson-activities-title">
                                    <?php
                                    if(!empty($oer_lp_activity_title)) {
                                        foreach ($oer_lp_activity_title as $key => $item) { ?>
                                            <li class="list-group-item">
                                                <strong>-</strong>
                                                <a href="#lp-ac-item-<?php echo $key;?>" title="<?php echo $item; ?>"><?php echo $item; ?></a>
                                            </li>
                                        <?php } ?>
                                    <?php } else {
                                        for ($i = 0; $i < 5; $i++) { ?>
                                            <li class="list-group-item">
                                                <strong>-</strong>
                                                <a href="#lp-ac-item-<?php echo $i;?>" title="Unnamed Activity">Unnamed Activity</a>
                                            </li>
                                        <?php }?>
                                    <?php }?>
                                </ul>
                            </li>
                        <?php } elseif ($elementKey == 'lp_summative_order') {?>
                            <li class="list-group-item">
                                <a href="#oer-lp-summative-group" title="Summative Assessment">Summative Assessment</a>
                            </li>
                       <?php }
                    }
                } else { ?>
                    <li class="list-group-item">
                        <a href="#oer-lp-introduction-group" title="Introduction">Introduction</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#oer-lp-authors" title="Introduction">Authors</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#oer-lp-times-group" title="Lesson Times">Lesson Times</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#oer-lp-industries-group" title="Industries / Subjects / Grades">Industries / Subjects / Grades</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#oer-lp-standards-group" title="Standards and Objectives">Standards and Objectives</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#oer-lp-activities-group" title="Activities in this Lesson">Activities in this Lesson</a>
                        <ul class="list-group sidebar-lesson-activities-title">
                            <?php
                            if(!empty($oer_lp_activity_title)) {
                                foreach ($oer_lp_activity_title as $key => $item) { ?>
                                    <li class="list-group-item">
                                        <strong>-</strong>
                                        <a href="#lp-ac-item-<?php echo $key;?>" title="<?php echo $item; ?>"><?php echo $item; ?></a>
                                    </li>
                                <?php } ?>
                            <?php } else {
                                for ($i = 0; $i < 5; $i++) { ?>
                                    <li class="list-group-item">
                                        <strong>-</strong>
                                        <a href="#lp-ac-item-<?php echo $i;?>" title="Unnamed Activity">Unnamed Activity</a>
                                    </li>
                                <?php }?>
                            <?php }?>
                        </ul>
                    </li>
                    <li class="list-group-item">
                        <a href="#oer-lp-summative-group" title="Summative Assessment">Summative Assessment</a>
                    </li>
                <?php }?>
            </ul>
        </div>
        <div class="col-md-8" id="oer-lp-sortable">
            <!--For Introduction-->
            <?php if (!empty($elements_orders)) {
                foreach ($elements_orders as $elementKey => $value) {
                    if($elementKey == 'lp_introduction_order') {?>
                        <div class="panel panel-default lp-element-wrapper oer-lp-introduction-group" id="oer-lp-introduction-group">
                            <input type="hidden" name="lp_order[lp_introduction_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">
                                    <?php _e("Introduction", OER_LESSON_PLAN_SLUG); ?>
                                    <span class="lp-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <?php
                                $oer_lp_introduction = isset($post_meta_data['oer_lp_introduction'][0]) ? $post_meta_data['oer_lp_introduction'][0] : "";
                                wp_editor( $oer_lp_introduction,
                                    'oer-lp-introduction',
                                    $settings = array(
                                        'textarea_name' => 'oer_lp_introduction',
                                        'media_buttons' => true,
                                        'textarea_rows' => 10,
                                        'drag_drop_upload' => true,
                                        'teeny' => true,
                                    )
                                );
                                ?>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'lp_authors_order') {?>
                        <?php
                        $authors = (isset($post_meta_data['oer_lp_authors'][0]) ? unserialize($post_meta_data['oer_lp_authors'][0]) : array());
                        if(!empty($authors)) { ?>
                            <div class="panel panel-default lp-element-wrapper oer-lp-authors-group" id="oer-lp-authors">
                                <input type="hidden" name="lp_order[lp_authors_order]" class="element-order" value="2">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title">
                                        <?php _e("Authors", OER_LESSON_PLAN_SLUG); ?>
                                        <span class="lp-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="panel-group">
                                        <?php
                                        if (isset($authors['name']) && !empty($authors['name'])) {
                                            foreach ( $authors['name']as $authorKey => $authorName) { ?>
                                                <div class="panel panel-default lp-author-element-wrapper" id="author-<?php echo $authorKey;?>">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title lp-module-title">
                                                            <?php _e("Author", OER_LESSON_PLAN_SLUG); ?>
                                                            <span class="lp-sortable-handle">
                                                                <i class="fa fa-arrow-down author-reorder-down" aria-hidden="true"></i>
                                                                <i class="fa fa-arrow-up author-reorder-up" aria-hidden="true"></i>
                                                            </span>
                                                            <span class="btn btn-danger btn-sm lp-remove-author"
                                                                  title="Delete"
                                                            ><i class="fa fa-trash"></i> </span>
                                                        </h3>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row lp-authors-element-row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="oer_lp_authors[name][]"
                                                                           placeholder="Name"
                                                                           value="<?php echo $authorName;?>"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="oer_lp_authors[role][]"
                                                                           placeholder="Role"
                                                                           value="<?php echo isset($authors['role'][$authorKey]) ? $authors['role'][$authorKey] : "";?>"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="oer_lp_authors[author_url][]"
                                                                           placeholder="Author URL"
                                                                           value="<?php echo isset($authors['author_url'][$authorKey]) ? $authors['author_url'][$authorKey] : "";?>"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="oer_lp_authors[institution][]"
                                                                           placeholder="Institution"
                                                                           value="<?php echo isset($authors['institution'][$authorKey]) ? $authors['institution'][$authorKey] : "";?>"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           class="form-control"
                                                                           name="oer_lp_authors[institution_url][]"
                                                                           placeholder="Institution URL"
                                                                           value="<?php echo isset($authors['institution_url'][$authorKey]) ? $authors['institution_url'][$authorKey] : "";?>"
                                                                    >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="hidden"
                                                                       name="oer_lp_authors[author_pic][]"
                                                                       value="<?php echo isset($authors['author_pic'][$authorKey]) ? $authors['author_pic'][$authorKey] : "";?>"
                                                                >
                                                                <?php
                                                                $image = (isset($authors['author_pic'][$authorKey]) ? $authors['author_pic'][$authorKey] : "");
                                                                if(empty($image)) {
                                                                    $image = OER_LESSON_PLAN_URL . "assets/images/lp-oer-person-placeholder.png";
                                                                }
                                                                ?>
                                                                <img src="<?php echo $image;?>"
                                                                     class="img-circle lp-oer-person-placeholder"
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
                                                    id="lp-add-more-author"
                                                    class="btn btn-default lp-add-more-author"
                                            ><i class="fa fa-plus"></i> Add More</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif ($elementKey == 'lp_oer_materials') {?>
                        <div class="panel panel-default lp-element-wrapper" id="oer-lp-materials">
                            <input type="hidden" name="lp_order[lp_oer_materials]" class="element-order" value="3">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">
                                    <?php _e("Materials", OER_LESSON_PLAN_SLUG); ?>
                                    <span class="lp-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i></span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="panel-group" id="lp-materials-container">
                                    <?php
                                    $materials = (isset($post_meta_data['lp_oer_materials'][0]) ? unserialize($post_meta_data['lp_oer_materials'][0]) : array());
                                    if (!empty($materials['url'])) {
                                        foreach ($materials['url'] as $materialKey => $material) {?>
                                            <?php
                                            $file_response = get_file_type_from_url($material);
                                            ?>
                                            <div class="panel panel-default lp-material-element-wrapper">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title lp-module-title">
                                                        <span class="lp-sortable-handle">
                                                            <i class="fa fa-arrow-down material-reorder-down" aria-hidden="true"></i>
                                                            <i class="fa fa-arrow-up material-reorder-up" aria-hidden="true"></i>
                                                        </span>
                                                        <span class="btn btn-danger btn-sm lp-remove-material" title="Delete"><i class="fa fa-trash"></i></span>
                                                    </h3>
                                                    </div>
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="lp_oer_materials[url][]"
                                                                   placeholder="URL"
                                                                   value="<?php echo $material;?>">
                                                            <div class="input-group-addon"
                                                                 title="<?php echo isset($file_response['title']) ? $file_response['title'] : "";?>"
                                                            ><?php echo isset($file_response['icon']) ? $file_response['icon'] : "";?></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="lp_oer_materials[title][]"
                                                               placeholder="Title"
                                                               value="<?php echo $materials['title'][$materialKey]?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <textarea class="form-control"
                                                                  name="lp_oer_materials[description][]"
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
                                        id="lp-add-materials"
                                        class="btn btn-default lp-add-materials"
                                ><i class="fa fa-plus"></i> Add Materials</button>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'lp_lesson_times_order') {?>
                        <!--For Lesson Times-->
                        <div class="panel panel-default lp-element-wrapper oer-lp-times-group" id="oer-lp-times-group">
                            <input type="hidden" name="lp_order[lp_lesson_times_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">
                                    <?php _e("Lesson Times", OER_LESSON_PLAN_SLUG); ?>
                                    <span class="lp-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <?php
                                $oer_lp_times_label  = isset($post_meta_data['oer_lp_times_label'][0]) ? unserialize($post_meta_data['oer_lp_times_label'][0]) : array();
                                $oer_lp_times_number = isset($post_meta_data['oer_lp_times_number'][0]) ? unserialize($post_meta_data['oer_lp_times_number'][0]) : array();
                                $oer_lp_times_type   = isset($post_meta_data['oer_lp_times_type'][0]) ? unserialize($post_meta_data['oer_lp_times_type'][0]) : array();
                                ?>

                                <?php
                                /**
                                 * Check if lesson time data available the show the value pre fill
                                 */
                                if(!empty($oer_lp_times_label)){
                                    foreach ($oer_lp_times_label as $key => $item){?>
                                        <div class="row lp-time-element-row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control"
                                                           name="oer_lp_times_label[]"
                                                           value="<?php echo $item;?>"
                                                           placeholder="label">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control"
                                                           name="oer_lp_times_number[]"
                                                           value="<?php echo isset($oer_lp_times_number[$key]) ? $oer_lp_times_number[$key] : '';?>"
                                                           placeholder="40">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select name="oer_lp_times_type[]" class="form-control">
                                                        <option value="minutes" <?php echo (isset($oer_lp_times_type[$key]) ? oer_lp_show_selected('minutes', $oer_lp_times_type[$key]) : '');?>>Minute(s)</option>
                                                        <option value="hours" <?php echo (isset($oer_lp_times_type[$key]) ? oer_lp_show_selected('hours', $oer_lp_times_type[$key]) : '');?>>Hour(s)</option>
                                                        <option value="days" <?php echo (isset($oer_lp_times_type[$key]) ? oer_lp_show_selected('days', $oer_lp_times_type[$key]) : '');?>>Days(s)</option>
                                                        <option value="class_periods" <?php echo (isset($oer_lp_times_type[$key]) ? oer_lp_show_selected('class_periods', $oer_lp_times_type[$key]) : '');?>>Class Period(s)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <button type="button"
                                                            class="btn btn-danger remove-time-element"
                                                        <?php if(count($oer_lp_times_label) == 1) echo 'disabled="disabled"';?>
                                                    ><i class="fa fa-trash"></i> </button>
                                                </div>
                                            </div>
                                        </div><!-- /.row -->
                                    <?php }?>
                                <?php } else {?>
                                    <div class="row lp-time-element-row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_lp_times_label[]" placeholder="label">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="oer_lp_times_number[]" placeholder="40">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select name="oer_lp_times_type[]" class="form-control">
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
                                                class="btn btn-default lp-add-time-element"
                                        ><i class="fa fa-plus"></i> Add Time Element</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'lp_industries_order') {?>
                        <!--For industries/subject/grades-->
                        <div class="panel panel-default lp-element-wrapper oer-lp-industries-group" id="oer-lp-industries-group">
                            <input type="hidden" name="lp_order[lp_industries_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">
                                    <?php _e("Industries / Subjects / Grades", OER_LESSON_PLAN_SLUG); ?>
                                    <span class="lp-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#lp-industries-content"
                                           id="lp-industries-tab"
                                           data-toggle="tab"
                                           role="tab"
                                           aria-controls="lp-industries-content"
                                           aria-selected="true"
                                        >Industries / Pathways</a>
                                    </li>
                                    <li>
                                        <a href="#lp-grades-content"
                                           id="lp-grades-tab"
                                           data-toggle="tab"
                                           role="tab"
                                           aria-controls="lp-grades-content"
                                           aria-selected="false"
                                        >Grade Levels</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="nav-tab-content">
                                    <div class="tab-pane active" id="lp-industries-content" role="tabpanel" aria-labelledby="lp-industries-tab">
                                        <!--s-->
                                    </div>
                                    <div class="tab-pane fade" id="lp-grades-content" role="tabpanel" aria-labelledby="lp-grades-tab">
                                        <div class="row">
                                            <?php
                                            $oer_lp_grade_options = array(
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

                                            // Display options
                                            $oer_lp_grades = (isset($post_meta_data['oer_lp_grades'][0]) ? unserialize($post_meta_data['oer_lp_grades'][0]) : array());
                                            foreach ($oer_lp_grade_options as $key => $oer_lp_grade_option) { ?>
                                                <div class="col-md-3">
                                                    <div class="checkbox oer-summative-checkbox">
                                                        <label>
                                                            <input name="oer_lp_grades[]"
                                                                   type="checkbox"
                                                                   value="<?php echo $key;?>"
                                                                <?php echo oer_lp_show_selected($key, $oer_lp_grades, 'checkbox')?>
                                                            > <?php echo $oer_lp_grade_option; ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'lp_standard_order') {?>
                        <!--For Standards and Objectives -->
                        <div class="panel panel-default lp-element-wrapper oer-lp-standards-group" id="oer-lp-standards-group">
                            <input type="hidden" name="lp_order[lp_standard_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">
                                    <?php _e("Standards and Objectives", OER_LESSON_PLAN_SLUG); ?>
                                    <span class="lp-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <h4 class="page-title-inner"><?php _e("Standards", OER_LESSON_PLAN_SLUG); ?></h4>

                                <div id="selected-standard-wrapper">
                                    <?php
                                    $standards = (isset($post_meta_data['oer_lp_standards'][0])? $post_meta_data['oer_lp_standards'][0] : "");
                                    get_standard_notations_from_ids($standards, true);
                                    ?>
                                </div>
                                <input type="hidden" name="oer_lp_standards" value="<?php echo $standards;?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button"
                                                id="lp-select-standard"
                                                class="btn btn-primary"
                                        >Select Standards</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Related Instructional Objectives (<span title="Students will be able to...">SWBAT...</span>)</h4>
                                    </div>

                                    <?php
                                    $oer_lp_related_objective  = isset($post_meta_data['oer_lp_related_objective'][0]) ? unserialize($post_meta_data['oer_lp_related_objective'][0]) : array();
                                    if(!empty($oer_lp_related_objective)) {
                                        foreach ( $oer_lp_related_objective as $key => $item) { ?>
                                            <div class="lp-related-objective-row" id="lp-related-objective-row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <input type="text"
                                                               class="form-control"
                                                               name="oer_lp_related_objective[]"
                                                               value="<?php echo $item;?>"
                                                        >
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button"
                                                            class="btn btn-danger lp-remove-related-objective"
                                                        <?php if(count($oer_lp_related_objective) == 1) echo 'disabled="disabled"';?>
                                                    ><i class="fa fa-trash"></i> </button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="lp-related-objective-row" id="lp-related-objective-row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <input type="text"
                                                           class="form-control"
                                                           name="oer_lp_related_objective[]"
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button"
                                                        class="btn btn-danger lp-remove-related-objective"
                                                        disabled="disabled"
                                                ><i class="fa fa-trash"></i> </button>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div><!-- /.row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button"
                                                class="btn btn-default lp-add-related-objective"
                                        ><i class="fa fa-plus"></i> Add Objective</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'lp_activities_order') {?>
                        <!--Activities in this lesson-->
                        <div class="panel panel-default lp-element-wrapper oer-lp-activities-group" id="oer-lp-activities-group">
                            <input type="hidden" name="lp_order[lp_activities_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">
                                    <?php _e("Activities in this Lesson", OER_LESSON_PLAN_SLUG); ?>
                                    <span class="lp-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="panel-group" id="lp-ac-inner-panel">
                                    <?php
                                    if(!empty($oer_lp_activity_title)) {
                                        foreach ($oer_lp_activity_title as $key => $item) { ?>
                                            <div class="panel panel-default lp-ac-item" id="lp-ac-item-<?php echo $key;?>">
                                                <!--<input type="hidden" name="lp_activity_order[lp_activities_order]" class="element-activity-order" value="">-->
                                                <span class="lp-inner-sortable-handle">
                                                    <i class="fa fa-arrow-down activity-reorder-down" aria-hidden="true"></i>
                                                    <i class="fa fa-arrow-up activity-reorder-up" aria-hidden="true"></i>
                                                </span>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-8">
                                                            <label>Activity Title</label>
                                                            <input type="text"
                                                                   name="oer_lp_activity_title[]"
                                                                   class="form-control"
                                                                   placeholder="Activity Title"
                                                                   value="<?php echo $item; ?>"
                                                            >
                                                        </div>
                                                        <div class="col-md-2 lp-ac-delete-container">
                                                            <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-8">
                                                            <label for="activity-title">Activity Type</label>
                                                            <select name="oer_lp_activity_type[]" class="form-control">
                                                                <option value=""> - Activity Type -</option>
                                                                <option value="hooks_set" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('hooks_set', $oer_lp_activity_type[$key]) : "");?>>Hooks / Set</option>
                                                                <option value="lecture" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('lecture', $oer_lp_activity_type[$key]) : "");?>>Lecture</option>
                                                                <option value="demonstration" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('demonstration', $oer_lp_activity_type[$key]) : "");?>>Demo / Modeling</option>
                                                                <option value="independent_practice" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('independent_practice', $oer_lp_activity_type[$key]) : "");?>>Independent Practice</option>
                                                                <option value="guided_practice" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('guided_practice', $oer_lp_activity_type[$key]) : "");?>>Guided Practice</option>
                                                                <option value="check_understanding" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('check_understanding', $oer_lp_activity_type[$key]) : "");?>>Check Understanding</option>
                                                                <option value="lab_shop" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('lab_shop', $oer_lp_activity_type[$key]) : "");?>>Lab / Shop</option>
                                                                <option value="group_work" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('group_work', $oer_lp_activity_type[$key]) : "");?>>Group Work</option>
                                                                <option value="projects" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('projects', $oer_lp_activity_type[$key]) : "");?>>Projects</option>
                                                                <option value="assessment" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('assessment', $oer_lp_activity_type[$key]) : "");?>>Formative Assessment</option>
                                                                <option value="closure" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('closure', $oer_lp_activity_type[$key]) : "");?>>Closure</option>
                                                                <option value="research" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('research', $oer_lp_activity_type[$key]) : "");?>>Research / Annotate</option>
                                                                <option value="other" <?php echo (isset($oer_lp_activity_type[$key]) ? oer_lp_show_selected('other', $oer_lp_activity_type[$key]) : "");?>>Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                        $content = isset($oer_lp_activity_detail[$key]) ? $oer_lp_activity_detail[$key] : "";
                                                        wp_editor( $content,
                                                            'oer-lp-activity-detail-'.$key,
                                                            $settings = array(
                                                                'textarea_name' => 'oer_lp_activity_detail[]',
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
                                                class="btn btn-default lp-add-ac-item"
                                                data-url="<?php echo admin_url('admin-index.php')?>"
                                        ><i class="fa fa-plus"></i> Add Activity</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($elementKey == 'lp_summative_order') {?>
                        <!--Summative Assessment-->
                        <div class="panel panel-default lp-element-wrapper oer-lp-summative-group" id="oer-lp-summative-group">
                            <input type="hidden" name="lp_order[lp_summative_order]" class="element-order" value="<?php echo $value;?>">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">
                                    <?php _e("Summative Assessment", OER_LESSON_PLAN_SLUG); ?>
                                    <span class="lp-sortable-handle">
                                        <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <h4><?php _e("Assessment Type(s):", OER_LESSON_PLAN_SLUG); ?></h4>
                                <div class="row">
                                    <?php
                                    $oer_lp_assessment_type = (isset($post_meta_data['oer_lp_assessment_type'][0]) ? unserialize($post_meta_data['oer_lp_assessment_type'][0]) : array());
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
                                                    <input name="oer_lp_assessment_type[]"
                                                           type="checkbox"
                                                           value="<?php echo $key;?>"
                                                        <?php echo oer_lp_show_selected($key, $oer_lp_assessment_type, 'checkbox')?>
                                                    > <?php echo $assessment_option; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>
                                <div class="row">
                                    <?php
                                    $oer_lp_other_assessment_type = (isset($post_meta_data['oer_lp_other_assessment_type'][0]) ? $post_meta_data['oer_lp_other_assessment_type'][0] : '');
                                    ?>
                                    <div class="form-group col-md-8">
                                        <label><?php _e("Other", OER_LESSON_PLAN_SLUG); ?></label>
                                        <input type="text"
                                               name="oer_lp_other_assessment_type"
                                               class="form-control"
                                               placeholder="Other Assessment Type(s)"
                                               value="<?php echo $oer_lp_other_assessment_type;?>"
                                        >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php
                                    $oer_lp_assessment = (isset($post_meta_data['oer_lp_assessment'][0]) ? $post_meta_data['oer_lp_assessment'][0] : '');
                                    wp_editor( $oer_lp_assessment,
                                        'oer-lp-other-assessment',
                                        $settings = array(
                                            'textarea_name' => 'oer_lp_assessment',
                                            'media_buttons' => true,
                                            'textarea_rows' => 10,
                                            'drag_drop_upload' => true,
                                            'teeny' => true,
                                        )
                                    ); ?>
                                </div>
                            </div>
                        </div>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_custom_editor_') !== false) {?>
                        <?php
                        $oer_lp_custom_editor = (isset($post_meta_data[$elementKey][0]) ? $post_meta_data[$elementKey][0] : "");
                        ?>
                            <div class="panel panel-default lp-element-wrapper oer-lp-introduction-group" id="oer-lp-custom-editor-group-<?php echo $key; ?>">
                                <input type="hidden" name="lp_order[<?php echo $elementKey;?>]" class="element-order" value="<?php echo $value;?>" value="1">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title">
                                        <?php _e("Text Editor", OER_LESSON_PLAN_SLUG); ?>
                                        <span class="lp-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    wp_editor( $oer_lp_custom_editor,
                                        'oer-lp-custom-editor-'.$value,
                                        $settings = array(
                                            'textarea_name' => $elementKey,
                                            'media_buttons' => true,
                                            'textarea_rows' => 10,
                                            'drag_drop_upload' => true,
                                            'teeny' => true,
                                        )
                                    );
                                    ?>
                                </div>
                            </div>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_custom_text_list_') !== false) {?>
                        <?php
                        $oer_lp_custom_text_list = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : array());
                        //echo "<pre>"; echo $elementKey; print_r($post_meta_data[$elementKey]);
                        if (!empty($oer_lp_custom_text_list)) {
                            foreach ($oer_lp_custom_text_list as $key => $list) { ?>
                                <div class="panel panel-default lp-element-wrapper" id="oer-lp-text-list-group-<?php echo $key;?>">
                                    <input type="hidden" name="lp_order[<?php echo $elementKey;?>]" class="element-order" value="<?php echo $value;?>">
                                    <div class="panel-heading">
                                        <h3 class="panel-title lp-module-title">
                                            <?php _e("Text List", OER_LESSON_PLAN_SLUG); ?>
                                            <span class="lp-sortable-handle">
                                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                            </span>
                                            <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="lp-text-list-row" id="lp-text-list-row<?php echo $key;?>">
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
                                                            class="btn btn-danger lp-remove-text-list"
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

                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_vocabulary_list_title_') !== false) {?>
                        <?php
                        $oer_lp_vocabulary_list_title = (isset($post_meta_data[$elementKey][0]) ? $post_meta_data[$elementKey][0] : "");
                        $listOrder = end(explode('_', $elementKey));
                        $oer_lp_vocabulary_details = (isset($post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0]) ? $post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0] : "");
                        ?>
                            <div class="panel panel-default lp-element-wrapper" id="oer-lp-vocabulary-list-group-<?php echo $key;?>">
                                <input type="hidden" name="lp_order[<?php echo $elementKey?>]" class="element-order" value="<?php echo $value;?>">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title">
                                        <?php _e("Vocabulary List", OER_LESSON_PLAN_SLUG); ?>
                                        <span class="lp-sortable-handle">
                                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <input type="text"
                                               class="form-control"
                                               name="<?php echo $elementKey;?>"
                                               value="<?php echo $oer_lp_vocabulary_list_title;?>"
                                        >
                                    </div>
                                    <div class="form-group">
                                        <textarea class="form-control" name="oer_lp_vocabulary_details_<?php echo $listOrder;?>" rows="6"><?php echo $oer_lp_vocabulary_details;?></textarea>
                                    </div>
                                </div>
                            </div>
                    <?php }
                }
            } else { ?>
            <div class="panel panel-default lp-element-wrapper oer-lp-introduction-group" id="oer-lp-introduction-group">
                <input type="hidden" name="lp_order[lp_introduction_order]" class="element-order" value="1">
                <div class="panel-heading">
                    <h3 class="panel-title lp-module-title">
                        <?php _e("Introduction", OER_LESSON_PLAN_SLUG); ?>
                        <span class="lp-sortable-handle">
                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                        </span>
                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <?php
                    $oer_lp_introduction = isset($post_meta_data['oer_lp_introduction'][0]) ? $post_meta_data['oer_lp_introduction'][0] : "";
                    wp_editor( $oer_lp_introduction,
                        'oer-lp-introduction',
                        $settings = array(
                            'textarea_name' => 'oer_lp_introduction',
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
            <div class="panel panel-default lp-element-wrapper oer-lp-authors-group" id="oer-lp-authors">
                <input type="hidden" name="lp_order[lp_authors_order]" class="element-order" value="2">
                <div class="panel-heading">
                    <h3 class="panel-title lp-module-title">
                        <?php _e("Authors", OER_LESSON_PLAN_SLUG); ?>
                        <span class="lp-sortable-handle">
                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                        </span>
                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="panel-group">
                        <div class="panel panel-default lp-author-element-wrapper">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">
                                    <?php _e("Author", OER_LESSON_PLAN_SLUG); ?>
                                    <span class="lp-sortable-handle">
                                        <i class="fa fa-arrow-down author-reorder-down" aria-hidden="true"></i>
                                        <i class="fa fa-arrow-up author-reorder-up" aria-hidden="true"></i>
                                    </span>
                                    <span class="btn btn-danger btn-sm lp-remove-author"
                                          title="Delete"
                                          disabled="disabled"
                                    ><i class="fa fa-trash"></i> </span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row lp-authors-element-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="oer_lp_authors[name][]" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="oer_lp_authors[role][]" placeholder="Role">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="oer_lp_authors[author_url][]" placeholder="Author URL">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="oer_lp_authors[institution][]" placeholder="Institution">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="oer_lp_authors[institution_url][]" placeholder="Institution URL">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="hidden" name="oer_lp_authors[author_pic][]">
                                        <img src="<?php echo OER_LESSON_PLAN_URL;?>assets/images/lp-oer-person-placeholder.png"
                                             class="img-circle lp-oer-person-placeholder"
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
                                    id="lp-add-more-author"
                                    class="btn btn-default lp-add-more-author"
                            ><i class="fa fa-plus"></i> Add More</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--Materials module-->
            <div class="panel panel-default lp-element-wrapper" id="oer-lp-materials">
                <input type="hidden" name="lp_order[lp_oer_materials]" class="element-order" value="3">
                <div class="panel-heading">
                    <h3 class="panel-title lp-module-title">
                        <?php _e("Materials", OER_LESSON_PLAN_SLUG); ?>
                        <span class="lp-sortable-handle">
                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                        </span>
                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i></span>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="panel-group" id="lp-materials-container">
                    </div>
                    <button type="button"
                            id="lp-add-materials"
                            class="btn btn-default lp-add-materials"
                    ><i class="fa fa-plus"></i> Add Materials</button>
                </div>
            </div>
            <!--For Lesson Times-->
            <div class="panel panel-default lp-element-wrapper oer-lp-times-group" id="oer-lp-times-group">
                <input type="hidden" name="lp_order[lp_lesson_times_order]" class="element-order" value="4">
                <div class="panel-heading">
                    <h3 class="panel-title lp-module-title">
                        <?php _e("Lesson Times", OER_LESSON_PLAN_SLUG); ?>
                        <span class="lp-sortable-handle">
                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                        </span>
                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row lp-time-element-row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <input type="text" class="form-control" name="oer_lp_times_label[]" placeholder="label">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <input type="text" class="form-control" name="oer_lp_times_number[]" placeholder="40">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="oer_lp_times_type[]" class="form-control">
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
                                    class="btn btn-default lp-add-time-element"
                            ><i class="fa fa-plus"></i> Add Time Element</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--For industries/subject/grades-->
            <div class="panel panel-default lp-element-wrapper oer-lp-industries-group" id="oer-lp-industries-group">
                <input type="hidden" name="lp_order[lp_industries_order]" class="element-order" value="5">
                <div class="panel-heading">
                    <h3 class="panel-title lp-module-title">
                        <?php _e("Industries / Subjects / Grades", OER_LESSON_PLAN_SLUG); ?>
                        <span class="lp-sortable-handle">
                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                        </span>
                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#lp-industries-content"
                               id="lp-industries-tab"
                               data-toggle="tab"
                               role="tab"
                               aria-controls="lp-industries-content"
                               aria-selected="true"
                            >Industries / Pathways</a>
                        </li>
                        <li>
                            <a href="#lp-grades-content"
                               id="lp-grades-tab"
                               data-toggle="tab"
                               role="tab"
                               aria-controls="lp-grades-content"
                               aria-selected="false"
                            >Grade Levels</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="nav-tab-content">
                        <div class="tab-pane active" id="lp-industries-content" role="tabpanel" aria-labelledby="lp-industries-tab">
                            <!--s-->
                        </div>
                        <div class="tab-pane fade" id="lp-grades-content" role="tabpanel" aria-labelledby="lp-grades-tab">
                            <div class="row">
                                <?php
                                $oer_lp_grade_options = array(
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

                                // Display options
                                $oer_lp_grades = (isset($post_meta_data['oer_lp_grades'][0]) ? unserialize($post_meta_data['oer_lp_grades'][0]) : array());
                                foreach ($oer_lp_grade_options as $key => $oer_lp_grade_option) { ?>
                                    <div class="col-md-3">
                                        <div class="checkbox oer-summative-checkbox">
                                            <label>
                                                <input name="oer_lp_grades[]"
                                                       type="checkbox"
                                                       value="<?php echo $key;?>"
                                                    <?php echo oer_lp_show_selected($key, $oer_lp_grades, 'checkbox')?>
                                                > <?php echo $oer_lp_grade_option; ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--For Standards and Objectives -->
            <div class="panel panel-default lp-element-wrapper oer-lp-standards-group" id="oer-lp-standards-group">
                <input type="hidden" name="lp_order[lp_standard_order]" class="element-order" value="6">
                <div class="panel-heading">
                    <h3 class="panel-title lp-module-title">
                        <?php _e("Standards and Objectives", OER_LESSON_PLAN_SLUG); ?>
                        <span class="lp-sortable-handle">
                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                        </span>
                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <h4 class="page-title-inner"><?php _e("Standards", OER_LESSON_PLAN_SLUG); ?></h4>
                    <div id="selected-standard-wrapper">
                        <p><?php _e("You have not selected any academic standards", OER_LESSON_PLAN_SLUG); ?></p>
                    </div>
                    <input type="hidden" name="oer_lp_standards">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button"
                                    id="lp-select-standard"
                                    class="btn btn-primary"
                            >Select Standards</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Related Instructional Objectives (<span title="Students will be able to...">SWBAT...</span>)</h4>
                        </div>
                        <div class="lp-related-objective-row" id="lp-related-objective-row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text"
                                           class="form-control"
                                           name="oer_lp_related_objective[]"
                                    >
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button"
                                        class="btn btn-danger lp-remove-related-objective"
                                        disabled="disabled"
                                ><i class="fa fa-trash"></i> </button>
                            </div>
                        </div>
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button"
                                    class="btn btn-default lp-add-related-objective"
                            ><i class="fa fa-plus"></i> Add Objective</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--Activities in this lesson-->
            <div class="panel panel-default lp-element-wrapper oer-lp-activities-group" id="oer-lp-activities-group">
                <input type="hidden" name="lp_order[lp_activities_order]" class="element-order" value="7">
                <div class="panel-heading">
                    <h3 class="panel-title lp-module-title">
                        <?php _e("Activities in this Lesson", OER_LESSON_PLAN_SLUG); ?>
                        <span class="lp-sortable-handle">
                            <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                            <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                        </span>
                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="panel-group" id="lp-ac-inner-panel">
                        <?php
                        for ($i = 0; $i < 5; $i++) { ?>
                            <div class="panel panel-default lp-ac-item" id="lp-ac-item-<?php echo $i;?>">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title">
                                        <span class="lp-sortable-handle">
                                            <i class="fa fa-arrow-down activity-reorder-down" aria-hidden="true"></i>
                                            <i class="fa fa-arrow-up activity-reorder-up" aria-hidden="true"></i>
                                        </span>
                                        <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                                    </h3>
                                </div>

                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>Activity Title</label>
                                        <input type="text" name="oer_lp_activity_title[]" class="form-control" placeholder="Activity Title">
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <label for="activity-title">Activity Type</label>
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
                                    <div class="form-group">
                                        <?php wp_editor( '',
                                            'oer-lp-activity-detail-'.$i,
                                            $settings = array(
                                                'textarea_name' => 'oer_lp_activity_detail[]',
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
                                    class="btn btn-default lp-add-ac-item"
                                    data-url="<?php echo admin_url('admin-index.php')?>"
                            ><i class="fa fa-plus"></i> Add Activity</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--Summative Assessment-->
            <div class="panel panel-default lp-element-wrapper oer-lp-summative-group" id="oer-lp-summative-group">
                    <input type="hidden" name="lp_order[lp_summative_order]" class="element-order" value="8">
                    <div class="panel-heading">
                        <h3 class="panel-title lp-module-title">
                            <?php _e("Summative Assessment", OER_LESSON_PLAN_SLUG); ?>
                            <span class="lp-sortable-handle">
                                <i class="fa fa-arrow-down reorder-down" aria-hidden="true"></i>
                                <i class="fa fa-arrow-up reorder-up" aria-hidden="true"></i>
                            </span>
                            <span class="btn btn-danger btn-sm lp-remove-module" title="Delete"><i class="fa fa-trash"></i> </span>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <h4><?php _e("Assessment Type(s):", OER_LESSON_PLAN_SLUG); ?></h4>
                        <div class="row">
                            <?php
                            $oer_lp_assessment_type = (isset($post_meta_data['oer_lp_assessment_type'][0]) ? unserialize($post_meta_data['oer_lp_assessment_type'][0]) : array());
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
                                            <input name="oer_lp_assessment_type[]"
                                                   type="checkbox"
                                                   value="<?php echo $key;?>"
                                                <?php echo oer_lp_show_selected($key, $oer_lp_assessment_type, 'checkbox')?>
                                            > <?php echo $assessment_option; ?>
                                        </label>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                        <div class="row">
                            <?php
                            $oer_lp_other_assessment_type = (isset($post_meta_data['oer_lp_other_assessment_type'][0]) ? $post_meta_data['oer_lp_other_assessment_type'][0] : '');
                            ?>
                            <div class="form-group col-md-8">
                                <label><?php _e("Other", OER_LESSON_PLAN_SLUG); ?></label>
                                <input type="text"
                                       name="oer_lp_other_assessment_type"
                                       class="form-control"
                                       placeholder="Other Assessment Type(s)"
                                       value="<?php echo $oer_lp_other_assessment_type;?>"
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <?php
                            $oer_lp_assessment = (isset($post_meta_data['oer_lp_assessment'][0]) ? $post_meta_data['oer_lp_assessment'][0] : '');
                            wp_editor( $oer_lp_assessment,
                                'oer-lp-other-assessment',
                                $settings = array(
                                    'textarea_name' => 'oer_lp_assessment',
                                    'media_buttons' => true,
                                    'textarea_rows' => 10,
                                    'drag_drop_upload' => true,
                                    'teeny' => true,
                                )
                            ); ?>
                        </div>
                    </div>
                </div>
            <?php }?>

            <!--Add Extra Module-->
            <div class="row">
                <div class="col-md-12">
                    <button type="button"
                            id="lp-create-dynamic-module"
                            class="btn btn-default lp-create-dynamic-module"
                    ><i class="fa fa-plus"></i> Add Module</button>
                </div>
            </div>
        </div>
    </div>

    <!--Dynamic module modal-->
    <?php include_once(OER_LESSON_PLAN_PATH.'includes/popups/create-module.php');?>
    <!--Delete module confirm popup-->
    <?php include_once(OER_LESSON_PLAN_PATH.'includes/popups/delete-module.php');?>
    <!--Delete author confirm popup-->
    <?php include_once(OER_LESSON_PLAN_PATH.'includes/popups/delete-author.php');?>
    <?php include_once(OER_LESSON_PLAN_PATH.'includes/popups/delete-confirm-popup.php');?>
    <?php include_once(OER_LESSON_PLAN_PATH.'includes/popups/standard-selection.php');?>
</div>
