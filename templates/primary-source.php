<?php

add_filter('body_class', function($classes){
    $classes[] = 'primary-source-template';
    return $classes;
});

get_header();

$active_tab = null;
$back_url = "";
$source_id = 0;
$lp_prev_class = "";
$lp_next_class = "";
$prev_url = "";
$next_url = "";

if (isset($_POST['activeTab']))
    $active_tab = $_POST['activeTab'];

// Back Button URL
$curriculum = get_query_var('curriculum');
$curriculum_details = get_page_by_path($curriculum, OBJECT, "lesson-plans");
$curriculum_id = $curriculum_details->ID;
if ($curriculum)
    $back_url = site_url("inquiry-sets/".$curriculum);

// Get Resource ID
$psource = get_query_var('source');
$sources = explode("-",$psource);
if ($sources)
    $source_id = $sources[count($sources)-1];

$resource = get_post($source_id);

// Get Featured Image Url
$featured_image_url = get_the_post_thumbnail_url($resource->ID, "full");

// Get Curriculum Meta for Primary Sources
$post_meta_data = get_post_meta($curriculum_id);
$primary_resources = (isset($post_meta_data['oer_lp_primary_resources'][0]) ? unserialize($post_meta_data['oer_lp_primary_resources'][0]) : array());
$index = 0;
$teacher_info = "";
$student_info = "";
if (!empty($primary_resources) && lp_scan_array($primary_resources)) {
    if (!empty(array_filter($primary_resources['resource']))) {
        foreach ($primary_resources['resource'] as $resourceKey => $source) {
            if ($source==$resource->post_title)
                break;
            $index++;
        }
        if (isset($primary_resources['resource'][$index-1])){
            $prev_resource = oer_lp_get_resource_details($primary_resources['resource'][$index-1]);
            $prev_url = $back_url."/source/".sanitize_title($prev_resource->post_title)."-".$prev_resource->ID;
        }
        if (isset($primary_resources['resource'][$index+1])){
            $next_resource = oer_lp_get_resource_details($primary_resources['resource'][$index+1]);
            $next_url = $back_url."/source/".sanitize_title($next_resource->post_title)."-".$next_resource->ID;
        }
        if ($index==0)
            $lp_prev_class = "ps-nav-hidden";
        if ($index==count($primary_resources['resource'])-1)
            $lp_next_class = "ps-nav-hidden";
        if (isset($primary_resources['teacher_info']))
            $teacher_info = $primary_resources['teacher_info'][$index];
        if (isset($primary_resources['student_info']))
            $student_info = $primary_resources['student_info'][$index];
    }
}
?>
<a class="back-button" href="<?php echo $back_url; ?>"><i class="fal fa-arrow-left"></i><?php _e("Back to Inquiry Set", OER_LESSON_PLAN_SLUG)?></a>
<div class="ps-header" style="background:url(<?php echo $featured_image_url; ?>) no-repeat top left;" data-curid="<?php echo $index; ?>">
    <span class="ps-nav-left <?php echo $lp_prev_class; ?>"><a class="lp-nav-left" href="<?php echo $prev_url; ?>" data-activetab="" data-id="<?php echo $index-1; ?>" data-count="<?php echo count($primary_resources['resource']); ?>" data-curriculum="<?php echo $curriculum_id; ?>" data-prevsource="<?php echo $primary_resources['resource'][$index-1]; ?>"><i class="fal fa-chevron-left fa-2x"></i></a></span>
    <span class="ps-nav-right <?php echo $lp_next_class; ?>"><a class="lp-nav-right" href="<?php echo $next_url; ?>" data-activetab="" data-id="<?php echo $index+1; ?>" data-count="<?php echo count($primary_resources['resource']); ?>" data-curriculum="<?php echo $curriculum_id; ?>" data-nextsource="<?php echo $primary_resources['resource'][$index+1]; ?>"><i class="fal fa-chevron-right fa-2x"></i></a></span>
    <span class="ps-expand"><a href="<?php echo $featured_image_url; ?>" class="lp-expand-img" target="_blank"><i class="fal fa-expand-arrows-alt"></i></a></span>
</div>
<div class="ps-info">
    <ul class="nav nav-tabs ps-info-tabs" id="ps-info-tabs-section" role="tablist">
        <li class="nav-item col-md-4 col-sm-4 padding-0">
            <a class="nav-link <?php if ($active_tab=="ps-information-tab" || !$active_tab): ?>active<?php endif; ?>" id="ps-information-tab" data-toggle="tab" href="#ps-information-tab-content" role="tabs" aria-controls="ps-information-tab-content" aria-selected="true" aria-expanded="false">
                Information    
            </a>
        </li>
        <li class="nav-item col-md-4 col-sm-4 padding-0">
            <a class="nav-link <?php if ($active_tab=="ps-student-info-tab"): ?>active<?php endif; ?>" id="ps-student-info-tab" data-toggle="tab" href="#ps-student-info-tab-content" role="tabs" aria-controls="ps-student-info-tab-content" aria-selected="true" aria-expanded="false">
                For The Student    
            </a>
        </li>
        <li class="nav-item col-md-4 col-sm-4 padding-0">
            <a class="nav-link <?php if ($active_tab=="ps-teacher-info-tab"): ?>active<?php endif; ?>" id="ps-teacher-info-tab" data-toggle="tab" href="#ps-teacher-info-tab-content" role="tabs" aria-controls="ps-teacher-info-tab-content" aria-selected="true" aria-expanded="false">
                For The Teacher    
            </a>
        </li>
    </ul>
</div>
<div class="ps-info-tabs-content">
    <div class="tab-pane clearfix fade <?php if ($active_tab=="ps-information-tab" || !$active_tab): ?>active<?php endif; ?> in" id="ps-information-tab-content" role="tabpanel" aria-labelledby="ps-information-tab">
        <?php
        $resource_meta = null;
        $subject_areas = null;
        if (function_exists('oer_get_resource_metadata')){
            $resource_meta = oer_get_resource_metadata($resource->ID);
        }
        $isFile = false;
        if (!function_exists('is_file_resource'))
            $isFile = is_file_resource($resource_meta['oer_resourceurl'][0]);
        if (!function_exists('is_pdf_resource') && $isFile==false)
            $isFile = is_pdf_resource($resource_meta['oer_resourceurl'][0]);
        if (!function_exists('is_image_resource') && $isFile==false)
            $isFile = is_image_resource($resource_meta['oer_resourceurl'][0]);
        ?>
        <div class="col-md-8">
            <h1 class="ps-info-title"><?php echo $resource->post_title; ?></h1>
            <div class="ps-info-description">
                <?php echo $resource->post_content; ?>
            </div>
            <?php if (isset($resource_meta['oer_resourceurl'])) { ?>
            <div class="ps-meta-group ps-resource-url">
                <label class="ps-label">Original Resource:</label>
                <span class="ps-value"><a href="<?php echo $resource_meta['oer_resourceurl'][0]; ?>"><?php echo $resource_meta['oer_resourceurl'][0]; ?></a></span>
            </div>
            <?php } ?>
        </div>
        <div class="col-md-4">
            <div class="ps-meta-icons">
                <?php if ($isFile==true) : ?>
                <span class="ps-download-source ps-meta-icon"><a href="<?php echo $resource_meta['oer_resourceurl'][0]; ?>" class="ps-download"><i class="fal fa-download"></i></a></span>
                <?php endif; ?>
                <div class="sharethis-inline-share-buttons"></div>
            </div>
            <?php
            if (function_exists('oer_get_subject_areas')){
                $subject_areas = oer_get_subject_areas($resource->ID);
            }
            if (is_array($subject_areas) && count($subject_areas)>0) {
                $subjects = array_unique($subject_areas, SORT_REGULAR);
            ?>
            <div class="ps-tagcloud">
                <?php foreach($subjects as $subject){ ?>
                    <span><a class="ps-button"><?php echo ucwords($subject->name); ?></a></span>
                <?php } ?>
            </div>
            <?php } ?>
            <?php if (isset($resource_meta['oer_authorname']) && $resource_meta['oer_authorname'][0]!=="") {
                $author_url = "";
                if (isset($resource_meta['oer_authorurl']))
                    $author_url = $resource_meta['oer_authorurl'][0];
            ?>
            <div class="ps-meta-group">
                <label class="ps-label">Author:</label>
                <?php if ($author_url=="") : ?>
                    <span class="ps-value"><?php echo $resource_meta['oer_authorname'][0]; ?></span>
                <?php else: ?>
                    <span class="ps-value"><a href="<?php echo $author_url; ?>" target="_blank"><?php echo $resource_meta['oer_authorname'][0]; ?></a></span>
                <?php endif; ?>
            </div>
            <?php } ?>
            <?php if (isset($resource_meta['oer_publishername']) && $resource_meta['oer_publishername'][0]!=="") {
                $publisher_url = "";
                if (isset($resource_meta['oer_publisherurl']))
                    $publisher_url = $resource_meta['oer_publisherurl'][0];
            ?>
            <div class="ps-meta-group">
                <label class="ps-label">Publisher:</label>
                <?php if ($publisher_url=="") : ?>
                <span class="ps-value"><?php echo $resource_meta['oer_publishername'][0]; ?></span>
                <?php else: ?>
                <span class="ps-value"><a href="<?php echo $publisher_url;  ?>" target="_blank"><?php echo $resource_meta['oer_publishername'][0]; ?></a></span>
                <?php endif; ?>
            </div>
            <?php } ?>
            <?php if (isset($resource_meta['oer_mediatype'][0])) { ?>
            <div class="ps-meta-group">
                <label class="ps-label">Type:</label>
                <span class="ps-value"><?php echo ucwords($resource_meta['oer_mediatype'][0]); ?></span>
            </div>
            <?php } ?>
            <?php if (isset($resource_meta['oer_interactivity'][0])) { ?>
            <div class="ps-meta-group">
                <label class="ps-label">Interactivity:</label>
                <span class="ps-value"><?php echo ucwords($resource_meta['oer_interactivity'][0]); ?></span>
            </div>
            <?php } ?>
            <?php if (isset($resource_meta['oer_grade'][0])) {
                $grades = explode(",",$resource_meta['oer_grade'][0]);
                if (is_array($grades) && !empty($grades) && $grades[0]!=="" ){
                    if (function_exists('oer_grade_levels'))
                        $grades = oer_grade_levels($grades);
            ?>
            <div class="ps-meta-group">
                <label class="ps-label">Grades:</label>
                <span class="ps-value"><?php echo $grades; ?></span>
            </div>
            <?php }
            } ?>
            <div class="ps-meta-group">
                <label class="ps-label">Keywords:</label>
            </div>
            <?php
            $keywords = wp_get_post_tags($resource->ID);
            if(!empty($keywords)) { ?>
            <div class="ps-tagcloud ps-keywords">
                <?php foreach($keywords as $keyword){ ?>
                    <span><a href="<?php echo esc_url(get_tag_link($keyword->term_id)); ?>" class="ps-button"><?php echo ucwords($keyword->name); ?></a></span>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="tab-pane clearfix fade <?php if ($active_tab=="ps-student-info-tab" || !$active_tab): ?>active<?php endif; ?> in" id="ps-student-info-tab-content" role="tabpanel" aria-labelledby="ps-student-info-tab">
        <?php echo $student_info; ?>
    </div>
    <div class="tab-pane clearfix fade <?php if ($active_tab=="ps-teacher-info-tab" || !$active_tab): ?>active<?php endif; ?> in" id="ps-teacher-info-tab-content" role="tabpanel" aria-labelledby="ps-teacher-info-tab">
        <?php echo $teacher_info; ?>
    </div>
</div>
<div class="lp-ajax-loader" role="status">
    <div class="lp-ajax-loader-img">
        <img src="<?php echo OER_LESSON_PLAN_URL."/assets/images/load.gif"; ?>" />
    </div>
</div>
<?php
get_footer();
?>