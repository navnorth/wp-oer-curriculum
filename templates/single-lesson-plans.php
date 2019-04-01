<?php
/**
 * The Template for displaying all single Curriculum
 */

/**
 * Enqueue the assets
 */
wp_enqueue_style('lesson-plan-load-fa', OER_LESSON_PLAN_URL.'assets/lib/font-awesome/css/font-awesome.min.css');
wp_enqueue_style('lesson-plan-bootstrap', OER_LESSON_PLAN_URL.'assets/lib/bootstrap-3.3.7/css/bootstrap.min.css');

get_header();

global $post;
global $wpdb;
$post_meta_data = get_post_meta($post->ID );
$elements_orders = isset($post_meta_data['lp_order'][0]) ? unserialize($post_meta_data['lp_order'][0]) : array();
//Grade Level
$lp_grade = isset($post_meta_data['oer_lp_grades'][0])? unserialize($post_meta_data['oer_lp_grades'][0])[0]:"";
if ($lp_grade!=="pre-k" && $lp_grade!=="k")
    $lp_grade = "Grade ".$lp_grade;
    
// Download Copy
$download_copy = isset($post_meta_data['oer_lp_download_copy'][0])? true:false;
if (have_posts()) : while (have_posts()) : the_post();
?>
<div class="container">
    <div class="row lp-featured-section">
        <div class="col-md-6 col-sm-12 featured-image padding-0">
            <?php the_post_thumbnail(); ?>
            <div class="tc-lp-grade"><?php echo $lp_grade ?></div>
            <div class="tc-lp-controls">
                <a href=""><i class="fa fa-share-alt"></i></a>
                <?php if ($download_copy): ?>
                <a href=""><i class="fa fa-download"></i></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 curriculum-detail padding-0">
            <h1><?php echo the_title(); ?></h1>
            <p><?php echo the_content(); ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            if ($oer_lp_download_copy == 'yes') {
                $download_resource_url = (isset($post_meta_data['oer_lp_download_copy_document'][0]) ? $post_meta_data['oer_lp_download_copy_document'][0] : '');
                $download_resource = get_file_type_from_url($download_resource_url);

                echo 'Download Lesson Copy: <a href="'.$download_resource_url.'" target="_blank">'.$download_resource['icon'].'</a>';
            }

            if (!empty($elements_orders)) {
                foreach ($elements_orders as $elementKey => $value) {
                    if($elementKey == 'lp_introduction_order') {?>
                        <?php
                        if(
                            isset($post_meta_data['oer_lp_introduction'][0]) &&
                            !empty($post_meta_data['oer_lp_introduction'][0])
                        )
                        {?>
                            <div class="panel panel-default oer-lp-introduction-group" id="oer-lp-introduction-group">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <span><?php _e("Introduction", OER_LESSON_PLAN_SLUG); ?></span>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <?php echo $post_meta_data['oer_lp_introduction'][0];?>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif ($elementKey == 'lp_authors_order') {?>
                        <?php
                        $authors = (isset($post_meta_data['oer_lp_authors'][0]) ? unserialize($post_meta_data['oer_lp_authors'][0]) : array());
                        if (!empty($authors) && lp_scan_array($authors)) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title">
                                        <?php _e("Authors", OER_LESSON_PLAN_SLUG); ?>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="panel-group">
                                        <?php
                                        if (isset($authors['name']) && !empty($authors['name'])) {
                                            foreach ( $authors['name']as $authorKey => $authorName) { ?>
                                                <?php
                                                $role = isset($authors['role'][$authorKey]) ? $authors['role'][$authorKey] : "";
                                                $author_url = isset($authors['author_url'][$authorKey]) ? $authors['author_url'][$authorKey] : "";
                                                $institution = isset($authors['institution'][$authorKey]) ? $authors['institution'][$authorKey] : "";
                                                $institution_url = isset($authors['institution_url'][$authorKey]) ? $authors['institution_url'][$authorKey] : "";
                                                if(
                                                    empty($authorName) &&
                                                    empty($role) &&
                                                    empty($author_url) &&
                                                    empty($institution) &&
                                                    empty($institution_url)
                                                ) {
                                                    continue;
                                                }
                                                ?>
                                                <div class="panel panel-default lp-author-element-wrapper" id="author-<?php echo $authorKey;?>">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title lp-module-title">
                                                            <?php _e("Author " . $authorKey, OER_LESSON_PLAN_SLUG); ?>
                                                        </h3>
                                                    </div>
                                                    <div class="panel-body form-horizontal">
                                                        <?php if (!empty($authorName)) { ?>
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label">Name</label>
                                                                <div class="col-md-10">
                                                                    <?php echo ucwords($authorName);?>
                                                                    <?php
                                                                    $image = (isset($authors['author_pic'][$authorKey]) ? $authors['author_pic'][$authorKey] : "");
                                                                    if (!empty($image)) { ?>
                                                                        <img src="<?php echo $image?>" class="img-circle img-responsive oer-lp-author-img" width="100" />
                                                                    <?php }?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (!empty($role)) { ?>
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label">Role</label>
                                                                <div class="col-md-10">
                                                                    <?php echo ucwords($role);?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (!empty($author_url)) { ?>
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label">Author URL</label>
                                                                <div class="col-md-10">
                                                                    <a href="<?php echo addSchemeToUrl($author_url);?>" target="_blank"><?php echo $author_url;?></a>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (!empty($institution)) { ?>
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label">Institution</label>
                                                                <div class="col-md-10">
                                                                    <?php echo ucwords($institution);?>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (!empty($institution_url)) { ?>
                                                            <div class="form-group">
                                                                <label class="col-md-2 control-label">Institution URL</label>
                                                                <div class="col-md-10">
                                                                    <a href="<?php echo addSchemeToUrl($institution_url);?>" target="_blank"><?php echo $institution_url;?></a>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif ($elementKey == 'lp_primary_resources') {?>
                        <?php
                        $primary_resources = (isset($post_meta_data['oer_lp_primary_resources'][0]) ? unserialize($post_meta_data['oer_lp_primary_resources'][0]) : array());
                        if (!empty($primary_resources) && lp_scan_array($primary_resources)) {?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php _e("Primary Resources", OER_LESSON_PLAN_SLUG); ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="panel-group">
                                        <?php
                                        if (!empty(array_filter($primary_resources['resource']))) {
                                            foreach ($primary_resources['resource'] as $resourceKey => $resource) {
                                                $sensitiveMaterial = (isset($primary_resources['sensitive_material'][$resourceKey]) ? $primary_resources['sensitive_material'][$resourceKey]: "");
                                                $teacherInfo = (isset($primary_resources['teacher_info'][$resourceKey]) ? $primary_resources['teacher_info'][$resourceKey]: "");
                                                $studentInfo = (isset($primary_resources['student_info'][$resourceKey]) ? $primary_resources['student_info'][$resourceKey]: "");
                                                ?>
                                                <div class="panel panel-default">
                                                    <!--<div class="panel-heading">
                                                        <h3 class="panel-title"></h3>
                                                    </div>-->
                                                    <div class="panel-body">
                                                        <?php
                                                        if ($sensitiveMaterial == 'yes') {
                                                            echo 'Sensitive Material';
                                                        }
                                                        ?>
                                                        <?php if (!empty($resource)) { ?>
                                                            <div class="form-group">
                                                                <label>OER Resource:</label>
                                                                <?php echo $resource; ?>
                                                            </div>
                                                        <?php }?>
                                                        <?php if (!empty($teacherInfo)) { ?>
                                                            <div class="form-group">
                                                                <label>Teacher Information:</label>
                                                                <?php echo $teacherInfo; ?>
                                                            </div>
                                                        <?php }?>
                                                        <?php if (!empty($studentInfo)) { ?>
                                                            <div class="form-group">
                                                                <label>Student Information:</label>
                                                                <?php echo $studentInfo; ?>
                                                            </div>
                                                        <?php }?>

                                                    </div>
                                                </div>
                                            <?php }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif ($elementKey == 'lp_oer_materials') {?>
                        <?php
                        $materials = (isset($post_meta_data['lp_oer_materials'][0]) ? unserialize($post_meta_data['lp_oer_materials'][0]) : array());
                        if (!empty($materials) && lp_scan_array($materials)) {?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php _e("Materials", OER_LESSON_PLAN_SLUG); ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="panel-group">
                                        <?php
                                        if (!empty(array_filter($materials['url']))) {
                                            foreach ($materials['url'] as $materialKey => $material) {
                                                $file_response = get_file_type_from_url($material);
                                                ?>
                                                <div class="panel panel-default">
                                                    <!--<div class="panel-heading">
                                                        <h3 class="panel-title"></h3>
                                                    </div>-->
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <label>Material:</label>
                                                            <?php
                                                            if($oer_lp_download_copy == 'yes') { ?>
                                                                <a href="<?php echo $material?>" target="_blank"><?php echo $file_response['icon'];?></a>
                                                            <?php } else { ?>
                                                                <a href="javascript:void(0)"><?php echo $file_response['icon'];?></a>
                                                            <?php } ?>
                                                        </div>
                                                        <?php
                                                        if (isset($materials['title'][$materialKey]) &&
                                                            !empty($materials['title'][$materialKey])
                                                        ) {?>
                                                            <div class="form-group">
                                                                <label>Title:</label>
                                                                <span><?php echo $materials['title'][$materialKey];?></span>
                                                            </div>
                                                        <?php }?>
                                                        <?php
                                                        if (isset($materials['description'][$materialKey]) &&
                                                            !empty($materials['description'][$materialKey])
                                                        ) {?>
                                                            <div class="form-group">
                                                                <label>Description:</label>
                                                                <span><?php echo $materials['description'][$materialKey];?></span>
                                                            </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            <?php }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif ($elementKey == 'lp_lesson_times_order') {?>
                        <!--For Lesson Times-->
                        <?php
                        $oer_lp_times_label  = isset($post_meta_data['oer_lp_times_label'][0]) ? unserialize($post_meta_data['oer_lp_times_label'][0]) : array();
                        $oer_lp_times_number = isset($post_meta_data['oer_lp_times_number'][0]) ? unserialize($post_meta_data['oer_lp_times_number'][0]) : array();
                        $oer_lp_times_type   = isset($post_meta_data['oer_lp_times_type'][0]) ? unserialize($post_meta_data['oer_lp_times_type'][0]) : array();
                        ?>
                        <?php if(!empty(array_filter($oer_lp_times_label))) {?>
                            <div class="panel panel-default oer-lp-times-group" id="oer-lp-times-group">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <?php _e("Lesson Times", OER_LESSON_PLAN_SLUG); ?>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <ul class="list-group">
                                        <?php
                                        foreach ($oer_lp_times_label as $key => $item){?>
                                            <?php
                                            $times = ((isset($oer_lp_times_number[$key]) && (!empty($oer_lp_times_number[$key]))) ? $oer_lp_times_number[$key] : '0');
                                            $minutes = (isset($oer_lp_times_type[$key]) ? $oer_lp_times_type[$key] : '');
                                            if(
                                                empty($item) &&
                                                empty($times) &&
                                                ($minutes == 'minutes')
                                            ) {
                                                continue;
                                            }
                                            ?>
                                            <li class="list-group-item">
                                                <?php echo $item;?> -
                                                <?php echo $times;?>
                                                <?php echo $minutes;?>
                                            </li>
                                        <?php }?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } elseif ($elementKey == 'lp_industries_order') {?>
                        <?php
                        $oer_lp_grades = (isset($post_meta_data['oer_lp_grades'][0]) ? unserialize($post_meta_data['oer_lp_grades'][0]) : array());
                        if(!empty($oer_lp_grades)) {?>
                            <!--For industries/subject/grades-->
                            <div class="panel panel-default oer-lp-industries-group" id="oer-lp-industries-group">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title">
                                        <?php _e("Industries / Subjects / Grades", OER_LESSON_PLAN_SLUG); ?>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <h4>Grade Levels</h4>
                                    <?php
                                    foreach ($oer_lp_grades as $key => $oer_lp_grade) { ?>
                                        <label><?php echo $oer_lp_grade; ?></label>,
                                    <?php }?>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif ($elementKey == 'lp_standard_order') {?>
                        <!--For Standards and Objectives -->
                        <?php
                        $oer_lp_related_objective = isset($post_meta_data['oer_lp_related_objective'][0]) ? unserialize($post_meta_data['oer_lp_related_objective'][0]) : array();
                        $standards = (isset($post_meta_data['oer_lp_standards'][0])? $post_meta_data['oer_lp_standards'][0] : "");
                        if(!empty(array_filter($oer_lp_related_objective)) || !empty($standards)){?>
                            <div class="panel panel-default oer-lp-standards-group" id="oer-lp-standards-group">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title">
                                        <?php _e("Standards and Objectives", OER_LESSON_PLAN_SLUG); ?>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div id="selected-standard-wrapper">
                                        <?php
                                        get_standard_notations_from_ids($standards);
                                        ?>
                                    </div>
                                    <?php
                                    foreach ( $oer_lp_related_objective as $key => $item) { ?>
                                        <div class="lp-related-objective-row" id="lp-related-objective-row">
                                            <?php echo $item;?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif ($elementKey == 'lp_activities_order') {?>
                        <!--Activities in this lesson-->
                        <?php
                        // Lesson activity data
                        $oer_lp_activity_title  = isset($post_meta_data['oer_lp_activity_title'][0]) ? unserialize($post_meta_data['oer_lp_activity_title'][0]) : array();
                        $oer_lp_activity_type   = isset($post_meta_data['oer_lp_activity_type'][0]) ? unserialize($post_meta_data['oer_lp_activity_type'][0]) : array();
                        $oer_lp_activity_detail = isset($post_meta_data['oer_lp_activity_detail'][0]) ? unserialize($post_meta_data['oer_lp_activity_detail'][0]) : array();

                        if(!empty(array_filter($oer_lp_activity_title)))
                        {?>
                            <div class="panel panel-default oer-lp-activities-group" id="oer-lp-activities-group">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title">
                                        <?php _e("Activities in this Lesson", OER_LESSON_PLAN_SLUG); ?>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="panel-group" id="lp-ac-inner-panel">
                                        <?php foreach ($oer_lp_activity_title as $key => $item) { ?>
                                            <?php
                                            $title       = $item;
                                            $type        = (isset($oer_lp_activity_type[$key]) ? $oer_lp_activity_type[$key] : "");
                                            $description = (isset($oer_lp_activity_detail[$key]) ? $oer_lp_activity_detail[$key] : "");
                                            if(
                                                empty($title) &&
                                                empty($type) &&
                                                empty($description)
                                            ) {
                                                continue;
                                            }
                                            ?>
                                            <div class="panel panel-default lp-ac-item" id="lp-ac-item-<?php echo $key;?>">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <label>Activity Title</label>
                                                        <?php echo $title; ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Activity Type</label>
                                                        <?php echo $type;?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Activity Description</label>
                                                        <?php echo $description;?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif ($elementKey == 'lp_summative_order') {?>
                        <!--Summative Assessment-->
                        <?php
                        $oer_lp_assessment_type = (isset($post_meta_data['oer_lp_assessment_type'][0]) ? unserialize($post_meta_data['oer_lp_assessment_type'][0]) : array());
                        if (!empty($oer_lp_assessment_type)) {?>
                            <div class="panel panel-default oer-lp-summative-group" id="oer-lp-summative-group">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title">
                                        <?php _e("Summative Assessment", OER_LESSON_PLAN_SLUG); ?>
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <h4><?php _e("Assessment Type(s):", OER_LESSON_PLAN_SLUG); ?></h4>
                                    <ul>
                                        <?php foreach ($oer_lp_assessment_type as $key => $oer_lp_assessment) { ?>
                                            <li><?php echo ucfirst($oer_lp_assessment); ?></li>
                                        <?php }?>
                                    </ul>
                                    <?php
                                    $oer_lp_other_assessment_type = (isset($post_meta_data['oer_lp_other_assessment_type'][0]) ? $post_meta_data['oer_lp_other_assessment_type'][0] : '');
                                    if(!empty($oer_lp_other_assessment_type)) {
                                        echo "Others: " . $oer_lp_other_assessment_type;
                                    }
                                    ?>
                                    <div class="form-group">
                                        <?php
                                        echo $oer_lp_assessment = (isset($post_meta_data['oer_lp_assessment'][0]) ? $post_meta_data['oer_lp_assessment'][0] : '');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_custom_editor_') !== false) {?>
                        <!--For custom editor-->
                        <?php
                        $oer_lp_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                        if(!empty($oer_lp_custom_editor)) { ?>
                            <div class="panel panel-default lp-element-wrapper oer-lp-introduction-group" id="oer-lp-custom-editor-group-<?php echo $key; ?>">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title"><?php echo $oer_lp_custom_editor['title']; ?></h3>
                                </div>
                                <div class="panel-body">
                                    <?php echo $oer_lp_custom_editor['description'];?>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_custom_text_list_') !== false) {?>
                        <!--For list-->
                        <?php
                        $oer_lp_custom_text_list = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : array());
                        if (!empty(array_filter($oer_lp_custom_text_list))) {
                            foreach ($oer_lp_custom_text_list as $key => $list) { ?>
                                <div class="panel panel-default lp-element-wrapper" id="oer-lp-text-list-group-<?php echo $key;?>">
                                    <div class="panel-heading">
                                        <h3 class="panel-title lp-module-title">Text List</h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php echo $list;?>
                                    </div>
                                </div>
                            <?php }
                        }
                        ?>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_vocabulary_list_title_') !== false) {?>
                        <!--For vocabulary-->
                        <?php
                        $oer_lp_vocabulary_list_title = (isset($post_meta_data[$elementKey][0]) ? $post_meta_data[$elementKey][0] : "");
                        $listOrder = end(explode('_', $elementKey));
                        $oer_lp_vocabulary_details = (isset($post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0]) ? $post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0] : "");
                        if (!empty($oer_lp_vocabulary_list_title)) { ?>
                            <div class="panel panel-default lp-element-wrapper" id="oer-lp-vocabulary-list-group-<?php echo $key;?>">
                                <div class="panel-heading">
                                    <h3 class="panel-title lp-module-title">Vocabulary List</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <p><?php echo $oer_lp_vocabulary_list_title;?></p>
                                    </div>
                                    <div class="form-group">
                                        <?php echo $oer_lp_vocabulary_details;?>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'lp_oer_materials_list_') !== false) {?>
                        <?php
                        $materials = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : array());
                        if (!empty($materials) && lp_scan_array($materials)) {?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?php _e("Materials", OER_LESSON_PLAN_SLUG); ?></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="panel-group">
                                        <?php
                                        if (!empty(array_filter($materials['url']))) {
                                            foreach ($materials['url'] as $materialKey => $material) {
                                                $file_response = get_file_type_from_url($material);
                                                ?>
                                                <div class="panel panel-default">
                                                    <!--<div class="panel-heading">
                                                        <h3 class="panel-title"></h3>
                                                    </div>-->
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <label>Material:</label>
                                                            <a href="<?php echo $material?>" target="_blank"><?php echo $file_response['icon'];?></a>
                                                        </div>
                                                        <?php
                                                        if (isset($materials['title'][$materialKey]) &&
                                                            !empty($materials['title'][$materialKey])
                                                        ) {?>
                                                            <div class="form-group">
                                                                <label>Title:</label>
                                                                <span><?php echo $materials['title'][$materialKey];?></span>
                                                            </div>
                                                        <?php }?>
                                                        <?php
                                                        if (isset($materials['description'][$materialKey]) &&
                                                            !empty($materials['description'][$materialKey])
                                                        ) {?>
                                                            <div class="form-group">
                                                                <label>Description:</label>
                                                                <span><?php echo $materials['description'][$materialKey];?></span>
                                                            </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            <?php }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    <?php }
                }
            } else {?>
                <?php
                if(
                    isset($post_meta_data['oer_lp_introduction'][0]) &&
                    !empty($post_meta_data['oer_lp_introduction'][0])
                )
                {?>
                    <div class="panel panel-default oer-lp-introduction-group" id="oer-lp-introduction-group">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span><?php _e("Introduction", OER_LESSON_PLAN_SLUG); ?></span>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <?php echo $post_meta_data['oer_lp_introduction'][0];?>
                        </div>
                    </div>
                <?php }?>

                <!--For Lesson Times-->
                <?php
                $oer_lp_times_label  = isset($post_meta_data['oer_lp_times_label'][0]) ? unserialize($post_meta_data['oer_lp_times_label'][0]) : array();
                $oer_lp_times_number = isset($post_meta_data['oer_lp_times_number'][0]) ? unserialize($post_meta_data['oer_lp_times_number'][0]) : array();
                $oer_lp_times_type   = isset($post_meta_data['oer_lp_times_type'][0]) ? unserialize($post_meta_data['oer_lp_times_type'][0]) : array();
                ?>
                <?php if(!empty($oer_lp_times_label)) {?>
                    <div class="panel panel-default oer-lp-times-group" id="oer-lp-times-group">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <?php _e("Lesson Times", OER_LESSON_PLAN_SLUG); ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <?php
                                foreach ($oer_lp_times_label as $key => $item){?>
                                    <li class="list-group-item">
                                        <?php echo $item;?> -
                                        <?php echo isset($oer_lp_times_number[$key]) ? $oer_lp_times_number[$key] : '';?>
                                        <?php echo (isset($oer_lp_times_type[$key]) ? $oer_lp_times_type[$key] : '');?>
                                    </li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

                <?php
                $oer_lp_grades = (isset($post_meta_data['oer_lp_grades'][0]) ? unserialize($post_meta_data['oer_lp_grades'][0]) : array());
                if(!empty($oer_lp_grades))
                {?>
                    <!--For industries/subject/grades-->
                    <div class="panel panel-default oer-lp-industries-group" id="oer-lp-industries-group">
                        <div class="panel-heading">
                            <h3 class="panel-title lp-module-title">
                                <?php _e("Industries / Subjects / Grades", OER_LESSON_PLAN_SLUG); ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h4>Grade Levels</h4>
                            <?php
                            foreach ($oer_lp_grades as $key => $oer_lp_grade) { ?>
                                <label><?php echo $oer_lp_grade; ?></label>,
                            <?php }?>
                        </div>
                    </div>
                <?php }?>

                <!--For Standards and Objectives -->
                <?php
                $oer_lp_related_objective = isset($post_meta_data['oer_lp_related_objective'][0]) ? unserialize($post_meta_data['oer_lp_related_objective'][0]) : array();
                if(!empty($oer_lp_related_objective))
                {?>
                    <div class="panel panel-default oer-lp-standards-group" id="oer-lp-standards-group">
                        <div class="panel-heading">
                            <h3 class="panel-title lp-module-title">
                                <?php _e("Standards and Objectives", OER_LESSON_PLAN_SLUG); ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            foreach ( $oer_lp_related_objective as $key => $item) { ?>
                                <div class="lp-related-objective-row" id="lp-related-objective-row">
                                    <?php echo $item;?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php }?>

                <!--Activities in this lesson-->
                <?php
                // Lesson activity data
                $oer_lp_activity_title  = isset($post_meta_data['oer_lp_activity_title'][0]) ? unserialize($post_meta_data['oer_lp_activity_title'][0]) : array();
                $oer_lp_activity_type   = isset($post_meta_data['oer_lp_activity_type'][0]) ? unserialize($post_meta_data['oer_lp_activity_type'][0]) : array();
                $oer_lp_activity_detail = isset($post_meta_data['oer_lp_activity_detail'][0]) ? unserialize($post_meta_data['oer_lp_activity_detail'][0]) : array();

                if(!empty($oer_lp_activity_title))
                {?>
                    <div class="panel panel-default oer-lp-activities-group" id="oer-lp-activities-group">
                        <div class="panel-heading">
                            <h3 class="panel-title lp-module-title">
                                <?php _e("Activities in this Lesson", OER_LESSON_PLAN_SLUG); ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="panel-group" id="lp-ac-inner-panel">
                                <?php foreach ($oer_lp_activity_title as $key => $item) { ?>
                                    <div class="panel panel-default lp-ac-item" id="lp-ac-item-<?php echo $key;?>">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label>Activity Title</label>
                                                <?php echo $item; ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Activity Type</label>
                                                <?php echo (isset($oer_lp_activity_type[$key]) ? $oer_lp_activity_type[$key] : "");?>
                                            </div>
                                            <div class="form-group">
                                                <label>Activity Description</label>
                                                <?php echo isset($oer_lp_activity_detail[$key]) ? $oer_lp_activity_detail[$key] : "";?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php }?>

                <!--Summative Assessment-->
                <?php
                $oer_lp_assessment_type = (isset($post_meta_data['oer_lp_assessment_type'][0]) ? unserialize($post_meta_data['oer_lp_assessment_type'][0]) : array());
                if (!empty($oer_lp_assessment_type)) {?>
                    <div class="panel panel-default oer-lp-summative-group" id="oer-lp-summative-group">
                        <div class="panel-heading">
                            <h3 class="panel-title lp-module-title">
                                <?php _e("Summative Assessment", OER_LESSON_PLAN_SLUG); ?>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <h4><?php _e("Assessment Type(s):", OER_LESSON_PLAN_SLUG); ?></h4>
                            <ul>
                                <?php foreach ($oer_lp_assessment_type as $key => $oer_lp_assessment) { ?>
                                    <li><?php echo ucfirst($oer_lp_assessment); ?></li>
                                <?php }?>
                            </ul>
                            <?php echo "Others: " . $oer_lp_other_assessment_type = (isset($post_meta_data['oer_lp_other_assessment_type'][0]) ? $post_meta_data['oer_lp_other_assessment_type'][0] : '');?>
                            <div class="form-group">
                                <?php
                                echo $oer_lp_assessment = (isset($post_meta_data['oer_lp_assessment'][0]) ? $post_meta_data['oer_lp_assessment'][0] : '');
                                ?>
                            </div>
                        </div>
                    </div>
                <?php }?>

                <!--For custom editor-->
                <?php
                $oer_lp_custom_editor = (isset($post_meta_data['oer_lp_custom_editor'][0]) ? unserialize($post_meta_data['oer_lp_custom_editor'][0]) : array());
                if(!empty($oer_lp_custom_editor)) {
                    foreach ($oer_lp_custom_editor as $key => $editor) { ?>
                        <div class="panel panel-default lp-element-wrapper oer-lp-introduction-group" id="oer-lp-custom-editor-group-<?php echo $key; ?>">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">Text Editor</h3>
                            </div>
                            <div class="panel-body">
                                <?php
                                echo $editor;
                                ?>
                            </div>
                        </div>
                    <?php }
                }
                ?>

                <!--For list-->
                <?php
                $oer_lp_custom_text_list = (isset($post_meta_data['oer_lp_custom_text_list'][0]) ? unserialize($post_meta_data['oer_lp_custom_text_list'][0]) : array());
                if (!empty($oer_lp_custom_text_list)) {
                    foreach ($oer_lp_custom_text_list as $key => $list) { ?>
                        <div class="panel panel-default lp-element-wrapper" id="oer-lp-text-list-group-<?php echo $key;?>">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">Text List</h3>
                            </div>
                            <div class="panel-body">
                                <?php echo $list;?>
                            </div>
                        </div>
                    <?php }
                }
                ?>

                <!--For vocabulary-->
                <?php
                $oer_lp_vocabulary_list_title = (isset($post_meta_data['oer_lp_vocabulary_list_title'][0]) ? unserialize($post_meta_data['oer_lp_vocabulary_list_title'][0]) : array());
                $oer_lp_vocabulary_details = (isset($post_meta_data['oer_lp_vocabulary_details'][0]) ? unserialize($post_meta_data['oer_lp_vocabulary_details'][0]) : array());
                if (!empty($oer_lp_vocabulary_list_title)) {
                    foreach ($oer_lp_vocabulary_list_title as $key => $vocabulary) { ?>
                        <div class="panel panel-default lp-element-wrapper" id="oer-lp-vocabulary-list-group-<?php echo $key;?>">
                            <div class="panel-heading">
                                <h3 class="panel-title lp-module-title">Vocabulary List</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <p><?php echo $vocabulary;?></p>
                                </div>
                                <div class="form-group">
                                    <?php echo isset($oer_lp_vocabulary_details[$key]) ? $oer_lp_vocabulary_details[$key] : "";?>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
                ?>
            <?php }?>

        </div>
    </div>
</div>
<?php
	// Display Activity Objects
 	endwhile; 
endif; 
get_footer();
