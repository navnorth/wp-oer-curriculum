<?php

add_filter('body_class', function($classes){
    $classes[] = 'primary-source-template';
    return $classes;
});

get_header();

$back_url = "";
$source_id = 0;

// Back Button URL
$curriculum = get_query_var('curriculum');
$curriculum_details = get_page_by_path($curriculum, OBJECT, "lesson-plans");
$curriculum_id = $curriculum_details->ID;
if ($curriculum)
    $back_url = "lesson-plans/".$curriculum;

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
        if (isset($primary_resources['teacher_info']))
            $teacher_info = $primary_resources['teacher_info'][$index];
        if (isset($primary_resources['student_info']))
            $student_info = $primary_resources['student_info'][$index];
    }
}

?>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <a href="<?php echo site_url($back_url); ?>" class="lp-back-button"><i class="fas fa-chevron-left"></i> <?php _e("Back to Inquiry Set", OER_LESSON_PLAN_SLUG)?></a>
        </div>
        <div class="col-md-8"></div>
    </div>
</div>
<div class="ps-header" style="background:url(<?php echo $featured_image_url; ?>) no-repeat top left;">
    <span class="ps-nav-left"><a class="lp-nav-left"><i class="fas fa-chevron-left fa-2x"></i></a></span>
    <span class="ps-nav-right"><a class="lp-nav-right"><i class="fas fa-chevron-right fa-2x"></i></a></span>
    <span class="ps-expand"><a href="<?php echo $featured_image_url; ?>" class="lp-expand-img" target="_blank"><i class="fas fa-expand-arrows-alt"></i></a></span>
</div>
<div class="ps-info">
    <ul class="nav nav-tabs ps-info-tabs" id="ps-info-tabs-section" role="tablist">
        <li class="nav-item col-md-4 col-sm-4 padding-0">
            <a class="nav-link active" id="ps-information-tab" data-toggle="tab" href="#ps-information-tab-content" role="tabs" aria-controls="ps-information-tab-content" aria-selected="true" aria-expanded="false">
                Information    
            </a>
        </li>
        <li class="nav-item col-md-4 col-sm-4 padding-0">
            <a class="nav-link" id="ps-student-info-tab" data-toggle="tab" href="#ps-student-info-tab-content" role="tabs" aria-controls="ps-student-info-tab-content" aria-selected="true" aria-expanded="false">
                For The Student    
            </a>
        </li>
        <li class="nav-item col-md-4 col-sm-4 padding-0">
            <a class="nav-link" id="ps-teacher-info-tab" data-toggle="tab" href="#ps-teacher-info-tab-content" role="tabs" aria-controls="ps-teacher-info-tab-content" aria-selected="true" aria-expanded="false">
                For The Teacher    
            </a>
        </li>
    </ul>
</div>
<div class="ps-info-tabs-content">
    <div class="tab-pane clearfix fade active in" id="ps-information-tab-content" role="tabpanel" aria-labelledby="ps-information-tab">
        <?php
        $resource_meta = null;
        $subject_areas = null;
        if (function_exists('oer_get_resource_metadata')){
            $resource_meta = oer_get_resource_metadata($resource->ID);
        }
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
                <span class="ps-download-source ps-meta-icon"><a class="ps-download"><i class="fas fa-download"></i></a></span>
                <span class="ps-share-source ps-meta-icon"><a class="ps-share"><i class="fas fa-share-alt"></i></a></span>
            </div>
            <div style="display:none">
                <?php var_dump($resource_meta); ?>
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
            <?php if (isset($resource_meta['oer_mediatype'])) { ?>
            <div class="ps-meta-group">
                <label class="ps-label">Type:</label>
                <span class="ps-value"><?php echo ucwords($resource_meta['oer_mediatype'][0]); ?></span>
            </div>
            <?php } ?>
            <?php if (isset($resource_meta['oer_interactivity'])) { ?>
            <div class="ps-meta-group">
                <label class="ps-label">Interactivity:</label>
                <span class="ps-value"><?php echo ucwords($resource_meta['oer_interactivity'][0]); ?></span>
            </div>
            <?php } ?>
            <?php if (isset($resource_meta['oer_grade'])) {
                $grades = explode(",",$resource_meta['oer_grade'][0]);
                if (function_exists('oer_grade_levels'))
                    $grades = oer_grade_levels($grades);
            ?>
            <div class="ps-meta-group">
                <label class="ps-label">Grades:</label>
                <span class="ps-value"><?php echo $grades; ?></span>
            </div>
            <?php } ?>
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
    <div class="tab-pane clearfix fade in" id="ps-student-info-tab-content" role="tabpanel" aria-labelledby="ps-student-info-tab">
        <?php echo $student_info; ?>
    </div>
    <div class="tab-pane clearfix fade in" id="ps-teacher-info-tab-content" role="tabpanel" aria-labelledby="ps-teacher-info-tab">
        <?php echo $teacher_info; ?>
    </div>
</div>
<?php
get_footer();
?>