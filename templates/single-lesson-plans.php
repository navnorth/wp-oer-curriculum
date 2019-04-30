<?php
/**
 * The Template for displaying all single Curriculum
 */

/**
 * Enqueue the assets
 */
wp_enqueue_style('lesson-plan-load-fa', OER_LESSON_PLAN_URL.'assets/lib/font-awesome/css/all.min.css');
wp_enqueue_style('lesson-plan-bootstrap', OER_LESSON_PLAN_URL.'assets/lib/bootstrap-3.3.7/css/bootstrap.min.css');
wp_enqueue_script('lesson-plan-frontend', OER_LESSON_PLAN_URL.'assets/js/frontend/lesson-plan.js', array('jquery'), null, true);
wp_enqueue_script( 'jquery-ui-slider' );

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
$oer_lp_download_copy_document = (isset($post_meta_data['oer_lp_download_copy_document'][0]) ? $post_meta_data['oer_lp_download_copy_document'][0] : '');
$oer_lp_standards = isset($post_meta_data['oer_lp_standards'][0])?unserialize($post_meta_data['oer_lp_standards'][0]):"";
$tags = get_the_terms($post->ID,"post_tag");
$authors = (isset($post_meta_data['oer_lp_authors'][0]) ? unserialize($post_meta_data['oer_lp_authors'][0]) : array());
if (have_posts()) : while (have_posts()) : the_post();
?>
<div class="container">
    <div class="row lp-featured-section">
        <div class="col-md-6 col-sm-12 featured-image padding-0">
            <?php the_post_thumbnail(); ?>
            <div class="tc-lp-grade"><?php echo $lp_grade ?></div>
            <div class="tc-lp-controls">
                <a href=""><i class="fal fa-share-alt"></i></a>
                <?php if ($oer_lp_download_copy_document): ?>
                <a href="<?php echo $oer_lp_download_copy_document; ?>"><i class="fal fa-download"></i></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 curriculum-detail padding-0">
            <div class="tc-lp-details">
                <div class="tc-lp-details-header">
                    <h1 class="tc-lp-title"><?php echo the_title(); ?></h1>
                    <div class="tc-lp-authors-list">
                        <?php
                        if (!empty($authors)){
                            $aIndex = 0;
                            
                            foreach($authors['name'] as $author){
                                $author_url = $authors['author_url'][$aIndex];
                                
                                if (isset($author_url))
                                    echo "<span class='tc-lp-author'><a href='".$author_url."'>".$authors['name'][$aIndex]."</a></span>";
                                else
                                    echo "<span class='tc-lp-author'>".$authors['name'][$aIndex]."</span>";
                                    
                                $aIndex++;
                            }
                        } 
                        ?>
                    </div>
                </div>
                <div class="tc-lp-details-description">
                    <?php echo the_content(); ?>
                </div>
                <button class="open-standards">Standards</button>
                <div id="standards-dialog" class="tc-lp-details-standards-list">
                    <?php
                    if (is_array($oer_lp_standards)):
                        foreach($oer_lp_standards as $standard){
                            $standard_details = "";
                            if (function_exists('was_standard_details'))
                                $standard_details = was_standard_details($standard);
                        ?>
                        <div class="tc-lp-details-standard">
                            <a href="javascript:void(0)"><?php if ($standard_details): echo $standard_details->description; endif; ?></a>
                        </div>
                        <?php
                        }
                    endif;
                    ?>
                </div>
                <div class="tc-lp-details-tags-list">
                    <?php
                    if ($tags):
                    foreach($tags as $tag){
                    ?>
                    <a href="javascript:void(0)" class="tc-lp-details-tag"><?php echo $tag->name; ?></a>
                    <?php
                    }
                    endif;
                    ?>
                </div>
                <div class="tc-sensitive-material-section">
                    <p><i class="fal fa-exclamation-triangle"></i><span class="sensitive-material-text">Sensitive Material</span></p>
                    <button class="question-popup-button"><i class="fal fa-question-circle"></i></button>
                </div>
            </div>
        </div>
    </div>
    <?php
    $oer_lp_iq = isset($post_meta_data['oer_lp_iq'][0])?unserialize($post_meta_data['oer_lp_iq'][0]):array();
    if (!empty($oer_lp_iq)){
    ?>
    <div class="row tc-investigative-section">
        <div class="col-md-2 col-sm-2 col-xs-12 padding-0 custom-pink-bg investigate-section-custom-width">
            <div class="investigate-question-section">
                <h2>Investigative Question</h2>
            </div>
        </div>
        <div class="col-md-10 col-sm-10 col-xs-12 padding-0 custom-dark-pink-bg excerpt-section-custom-width">
            <div class="col-md-1 col-sm-1 hidden-xs padding-0">
                <div class="tc-pink-triangle"></div>
            </div>
            <div class="col-md-11 col-sm-11">
                <div class="excerpt-section">
                    <h2><?php echo $oer_lp_iq['question']; ?></h2>
                    <div class="show-excerpt-section text-right">
                        <button id="show-excerpt" type="button" class="excerpt-button">Framework Excerpt<i class="fal fa-angle-right"></i></button>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div id="framework-excerpt" class="investigative-section-answer custom-dark-pink-bg">
            <div class="col-md-3 col-sm-3 col-xs-12"></div>
            <div class="excerpt-content col-md-9 col-sm-9 col-xs-12">
                <?php echo $oer_lp_iq['excerpt']; ?>
                <button type="button" id="close-excerpt" class="excerpt-button float-right">CLOSE<i class="fal fa-angle-up"></i></button>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="row">
        <?php
        $primary_resources = (isset($post_meta_data['oer_lp_primary_resources'][0]) ? unserialize($post_meta_data['oer_lp_primary_resources'][0]) : array());
        if (!empty($primary_resources) && lp_scan_array($primary_resources)) {
            if (!empty(array_filter($primary_resources['resource']))) {
                foreach ($primary_resources['resource'] as $resourceKey => $resource) {
                    $resource = get_page_by_title($resource,OBJECT,"resource");
                    $resource_img = get_the_post_thumbnail_url($resource);
                    $sensitiveMaterial = (isset($primary_resources['sensitive_material'][$resourceKey]) ? $primary_resources['sensitive_material'][$resourceKey]: "");
                ?>
                <div class="col-md-3 col-sm-3 padding-0">
                    <div class="media-image">
                        <div class="image-thumbnail">
                            <div class="image-section">
                                <?php if ($resource_img!==""):
                                $ps_url = site_url("inquiry-sets/".sanitize_title($post->post_name)."/source/".sanitize_title($resource->post_title)."-".$resource->ID);
                                ?>
                                <a href="<?php echo $ps_url;  ?>">
                                    <img src="<?php echo $resource_img; ?>" alt="" class="img-thumbnail-square img-responsive img-loaded">
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($sensitiveMaterial!==""): ?>
                    <div class="sensitive-source">
                        <p><i class="fal fa-exclamation-triangle"></i></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php
                }
            }
        }
        ?>
    </div>
    <div class="row custom-bg-dark custom-bg-dark-row"></div>
    <div class="row">
        <ul class="nav nav-tabs tc-home-tabs" id="tc-home-tabs-section" role="tablist">
            <?php
            if (!empty($elements_orders)) {
                $col = 0;
                $keys = array(
                    "lp_introduction_order",
                    "lp_primary_resources",
                    "lp_oer_materials",
                    "lp_lesson_times_order",
                    "lp_industries_order",
                    "lp_standard_order",
                    "lp_activities_order",
                    "lp_summative_order",
                    "lp_authors_order",
                    "lp_iq"
                );
                foreach($elements_orders as $element=>$order){
                    if (!in_array($element,$keys))
                        $col++;
                }
                if($col==0)
                    $_col = 12;
                else
                    $_col = floor(12/$col);
                foreach ($elements_orders as $elementKey => $value) {
                    if (strpos($elementKey, 'oer_lp_custom_editor_teacher_background') !== false) {
                        $oer_lp_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                    ?>
                    <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                        <a class="nav-link active" id="tc-teacher-background-tab" data-toggle="tab" href="#tc-teacher-background-tab-content" role="tab" aria-controls="tc-teacher-background-tab" aria-selected="true" aria-expanded="false">
                            <?php echo $oer_lp_custom_editor['title']; ?>
                        </a>
                    </li>
                    <?php  } elseif (strpos($elementKey, 'oer_lp_custom_editor_student_background') !== false) {
                        $oer_lp_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                    ?>
                    <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                        <a class="nav-link" id="tc-student-background-tab" data-toggle="tab" href="#tc-student-background-tab-content" role="tab" aria-controls="tc-student-background-tab" aria-selected="false" aria-expanded="false">
                            <?php echo $oer_lp_custom_editor['title']; ?>
                        </a>
                    </li>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_custom_editor_') !== false) {
                        $oer_lp_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                        if(!empty($oer_lp_custom_editor)) {
                        ?>
                        <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                            <a class="nav-link" id="tc-<?php echo sanitize_title($oer_lp_custom_editor['title']); ?>-tab" data-toggle="tab" href="#tc-<?php echo sanitize_title($oer_lp_custom_editor['title']); ?>-tab-content" role="tab" aria-controls="tc-<?php echo sanitize_title($oer_lp_custom_editor['title']); ?>-tab" aria-selected="false" aria-expanded="false">
                                <?php echo $oer_lp_custom_editor['title']; ?>
                            </a>
                        </li>
                        <?php } ?>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_custom_text_list_') !== false) {
                        ?>
                        <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                            <a class="nav-link" id="tc-text-list-tab" data-toggle="tab" href="#tc-text-list-tab-content" role="tab" aria-controls="tc-text-list-tab" aria-selected="false" aria-expanded="false">
                                Text List
                            </a>
                        </li>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_vocabulary_list_title_') !== false) {
                        $oer_lp_vocabulary_list_title = (isset($post_meta_data[$elementKey][0]) ? $post_meta_data[$elementKey][0] : "");
                        $listOrder = end(explode('_', $elementKey));
                        $oer_lp_vocabulary_details = (isset($post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0]) ? $post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0] : "");
                        if (!empty($oer_lp_vocabulary_list_title)) { ?>
                        <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                            <a class="nav-link" id="tc-<?php echo sanitize_title($oer_lp_vocabulary_list_title); ?>-tab" data-toggle="tab" href="#tc-<?php echo sanitize_title($oer_lp_vocabulary_list_title); ?>-tab-content" role="tab" aria-controls="tc-<?php echo sanitize_title($oer_lp_vocabulary_list_title); ?>-tab" aria-selected="false" aria-expanded="false">
                                <?php echo $oer_lp_vocabulary_list_title; ?>
                            </a>
                        </li>
                        <?php } ?>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'lp_oer_materials_list_') !== false) {?>
                        <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                            <a class="nav-link" id="tc-materials-list-tab" data-toggle="tab" href="#tc-materials-list-tab-content" role="tab" aria-controls="tc-materials-list-tab" aria-selected="false" aria-expanded="false">
                                Materials
                            </a>
                        </li>
                        <?php
                        }
                    //}
                }
            }
            ?>
        </ul>
    </div>
    <div class="row tab-content tc-home-tabs-content col-md-12 padding-0">
        <?php
        if (!empty($elements_orders)) {
            foreach ($elements_orders as $elementKey => $value) {
                if (strpos($elementKey, 'oer_lp_custom_editor_teacher_background') !== false || strpos($elementKey, 'oer_lp_custom_editor_student_background') !== false || strpos($elementKey, 'oer_lp_custom_editor_') !== false) {
                    $tab_id = "";
                    $active = false;
                    $oer_lp_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                    if ($elementKey=="oer_lp_custom_editor_teacher_background"){
                        $tab_id = "tc-teacher-background-tab-content";
                        $active = true;
                    }
                    elseif ($elementKey == "oer_lp_custom_editor_student_background" ){
                        $tab_id = "tc-student-background-tab-content";
                    }
                    else{
                        $tab_id = "tc-".sanitize_title($oer_lp_custom_editor['title'])."-tab-content";
                    }
                    if(!empty($oer_lp_custom_editor)) {
                    ?>
                    <div class="tab-pane clearfix fade <?php if ($active): echo "active"; endif; ?> in" id="<?php echo $tab_id; ?>" role="tabpanel" aria-labelledby="">
                        <div class="tc-tab-content">
                            <p><?php echo $oer_lp_custom_editor['description'];?></p>
                        </div>
                    <button class="tc-read-more">Read More</button>
                    </div>
                    <?php
                    } ?>
                <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_custom_text_list_') !== false) {
                    $oer_lp_custom_text_list = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : array());
                    if (!empty(array_filter($oer_lp_custom_text_list))) {
                    ?>
                    <div class="tab-pane clearfix fade" id="tc-text-list-tab-content" role="tabpanel" aria-labelledby="">
                        <div class="tc-tab-content">
                            <ul>
                            <?php foreach ($oer_lp_custom_text_list as $key => $list) { ?>
                                <li><?php echo $list; ?></li>
                            <?php } ?>
                            </ul>
                        </div>
                        <button class="tc-read-more">Read More</button>
                    </div>
                    <?php
                    }
                    ?>
                <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_vocabulary_list_title_') !== false) {
                    $oer_lp_vocabulary_list_title = (isset($post_meta_data[$elementKey][0]) ? $post_meta_data[$elementKey][0] : "");
                    $listOrder = end(explode('_', $elementKey));
                    $oer_lp_vocabulary_details = (isset($post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0]) ? $post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0] : "");
                    if (!empty($oer_lp_vocabulary_list_title)) {
                        $tab_id = "tc-".sanitize_title($oer_lp_vocabulary_list_title)."-tab-content"
                    ?>
                    <div class="tab-pane clearfix fade" id="<?php echo $tab_id; ?>" role="tabpanel" aria-labelledby="">
                        <div class="tc-tab-content">
                            <p><?php echo $oer_lp_vocabulary_details;?></p>
                        </div>
                        <button class="tc-read-more">Read More</button>
                    </div>
                    <?php } ?>
                <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'lp_oer_materials_list_') !== false) {
                    $materials = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : array());
                    if (!empty($materials) && lp_scan_array($materials)) {
                    ?>
                    <div class="tab-pane clearfix fade" id="tc-materials-list-tab-content" role="tabpanel" aria-labelledby="">
                        <div class="tc-tab-content">
                        <?php
                         if (!empty(array_filter($materials['url']))) {
                            foreach ($materials['url'] as $materialKey => $material) {
                                $file_response = get_file_type_from_url($material);
                                ?>
                                <div class="form-group">
                                    <label>Material:</label>
                                    <a href="<?php echo $material; ?>" target="_blank"><?php echo $file_response['icon'];?></a>
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
                                <?php }
                            }
                        }
                        ?>
                        </div>
                        <button class="tc-read-more">Read More</button>
                    </div>
                    <?php } ?>
                <?php }
            }
        }
        ?>
    </div>
    <?php
    $related_inquiry_sets = (isset($post_meta_data['oer_lp_related_inquiry_set'][0]) ? unserialize($post_meta_data['oer_lp_related_inquiry_set'][0]) : array());
    if (count($related_inquiry_sets)>0) {
    ?>
    <div class="row">
        <div class="tc-related-inquiry-sets-topbar clearfix">
            <div class="col-md-6 col-sm-6 col-xs-6 padding-0 tc-custom-bg-orange"></div>
            <div class="col-md-6 col-sm-6 col-xs-6 padding-0 tc-custom-bg-pink"></div>
            <div class="tc-related-inquiry-section">
                <p>Related Inquiry Sets</p>
            </div>
        </div>
        <div class="tc-related-inquiry-grids-section clearfix">
            <?php
            foreach($related_inquiry_sets as $inquiry_set) {
                $inquiry = oer_lp_get_inquiry_set_details($inquiry_set);
                $inquiry_link = get_permalink($inquiry_set);
                $inquiry_img = get_the_post_thumbnail_url($inquiry);
                $inquiry_meta_data = oer_lp_get_inquiry_set_metadata($inquiry_set);
                if ($inquiry) {
            ?>
            <div class="col-md-4 col-sm-6 tc-related-inquiry-blocks-padding">
                <div class="media-image">
                    <div class="image-thumbnail">
                        <div class="image-section">
                            <img src="<?php echo $inquiry_img; ?>" alt="" class="img-thumbnail-square img-responsive img-loaded">
                        </div>
                    </div>
                </div>
                <?php
                $grades = (isset($inquiry_meta_data['oer_lp_grades'][0]) ? unserialize($inquiry_meta_data['oer_lp_grades'][0]) : array());
                if (count($grades)>0){
                    $grade = "";
                    if ($grades) {
                        if ($grades=="pre-k")
                            $grade = "Pre-K";
                        elseif ($grades=="k")
                            $grade = "Kindergarten";
                        else {
                            if (function_exists('oer_grade_levels'))
                                $grades = oer_grade_levels($grades);
                            $grade = "Grade ".$grades;
                        }
                ?>
                <div class="tc-related-inquiry-grades">
                    <span><?php echo $grade; ?></span>
                </div>
                <?php }
                } ?>
                <div class="custom-bg-dark custom-bg-dark-inquiry-sets"></div>
                <div class="tc-related-inquiry-set-description">
                   <h4><a href="<?php echo $inquiry_link; ?>"><?php echo $inquiry->post_title; ?></a></h4>
                </div>
            </div>
            <?php }
            } ?>
        </div>
    </div>
    <?php } ?>
</div>
<?php
	// Display Activity Objects
 	endwhile; 
endif; 
get_footer();
