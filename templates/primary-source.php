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
$resource_url = get_post_meta($resource->ID, "oer_resourceurl", true);
$youtube = oer_is_youtube_url($resource_url);
$isPDF = is_pdf_resource($resource_url);

// Get Curriculum Meta for Primary Sources
$post_meta_data = get_post_meta($curriculum_id);
$primary_resources = (isset($post_meta_data['oer_lp_primary_resources'][0]) ? unserialize($post_meta_data['oer_lp_primary_resources'][0]) : array());
$index = 0;
$teacher_info = "";
$student_info = "";
$embed = "";
$prev_url = null;
$next_url = null;
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
if ($youtube || $isPDF)
    $featured_image_url = "";
if (function_exists('oer_get_resource_metadata')){
    $resource_meta = oer_get_resource_metadata($resource->ID);
}
?>
<div class="lp-nav-block"><a class="back-button" href="<?php echo $back_url; ?>"><i class="fas fa-arrow-left"></i><?php echo $curriculum_details->post_title; ?></a></div>
<div class="ps-media-image col-md-4 col-sm-12" data-curid="<?php echo $index; ?>">
    <?php if ($youtube): ?>
    <div class="ps-youtube-video">
        <?php
            echo '<div class="youtubeVideoWrapper">';
            if (function_exists('oer_generate_youtube_embed_code'))
                $embed = oer_generate_youtube_embed_code($resource_url);
            echo $embed;
            echo '</div>';
        ?>
    </div>
    <?php elseif ($isPDF): ?>
    <div class="ps-pdf-block">
        <?php
            echo '<div class="psPDFWrapper">';
            oer_display_pdf_embeds($resource_url);
            echo '</div>';
        ?>
    </div>
    <?php else: ?>
    <div class="ps-image-block">
       <img src="<?php echo $featured_image_url; ?>" alt="<?php echo $resource->post_title; ?>" />
    </div>
    <?php endif; ?>
    <span class="ps-expand"><a href="<?php echo $featured_image_url; ?>" class="lp-expand-img" target="_blank"><i class="fas fa-external-link-alt"></i></a></span>
    <div class="lp-center">
        <?php if (isset($resource_meta['oer_resourceurl'])) { ?>
        <div class="ps-meta-group ps-resource-url">
            <a href="<?php echo $resource_meta['oer_resourceurl'][0]; ?>" class="tc-view-button" target="_blank"><?php _e("View Original", OER_LESSON_PLAN_SLUG); ?></a>
        </div>
        <?php } ?>
    </div>
</div>
<?php
$resource_meta = null;
$subject_areas = null;
$transcription_display = false;
$sensitive_material_display = false;
$tab_count = 3;
if (isset($resource_meta['oer_transcription']) && $resource_meta['oer_transcription'][0]!==""){
    $transcription_display = true;
    $tab_count++;
}

if (isset($resource_meta['oer_sensitive_material']) && $resource_meta['oer_sensitive_material'][0]!==""){
    $sensitive_material_display = true;
    $tab_count++;
}
$tabs = floor(12/$tab_count);   

if (in_array($active_tab,array("ps-transcription-info-tab","ps-sensitive-info-tab"))){
    if (($active_tab=="ps-transcription-info-tab" && !$transcription_display) || ($active_tab=="ps-sensitive-info-tab" && !$sensitive_material_display))
        $active_tab = null;
}
?>
<div class="ps-details col-md-8 col-sm-12">
<?php if ($sensitive_material_display==true) : ?>
<div class="tc-sensitive-material-section tc-primary-source-sensitive-material-section">
    <p><i class="fal fa-exclamation-triangle"></i><span class="sensitive-material-text">Sensitive Material</span></p>
    <button class="question-popup-button" role="button" data-toggle="tab" data-tabid="ps-sensitive-info-tab" href="#ps-sensitive-info-tab-content"><i class="fal fa-question-circle"></i></button>
</div>
<?php endif; ?>
<div class="ps-info">
    <h1 class="ps-info-title"><?php echo $resource->post_title; ?></h1>
    <div class="ps-info-description">
        <?php echo $resource->post_content; ?>
    </div>
</div>
</div>
<div class="ps-related-sources">
    <span class="ps-nav-left <?php echo $lp_prev_class; ?>"><a class="lp-nav-left" href="<?php echo $prev_url; ?>" data-activetab="" data-id="<?php echo $index-1; ?>" data-count="<?php echo count($primary_resources['resource']); ?>" data-curriculum="<?php echo $curriculum_id; ?>" data-prevsource="<?php echo $primary_resources['resource'][$index-1]; ?>"><i class="fal fa-chevron-left fa-2x"></i></a></span>
    <span class="ps-nav-right <?php echo $lp_next_class; ?>"><a class="lp-nav-right" href="<?php echo $next_url; ?>" data-activetab="" data-id="<?php echo $index+1; ?>" data-count="<?php echo count($primary_resources['resource']); ?>" data-curriculum="<?php echo $curriculum_id; ?>" data-nextsource="<?php echo $primary_resources['resource'][$index+1]; ?>"><i class="fal fa-chevron-right fa-2x"></i></a></span>
</div>
<div class="lp-ajax-loader" role="status">
    <div class="lp-ajax-loader-img">
        <img src="<?php echo OER_LESSON_PLAN_URL."/assets/images/load.gif"; ?>" />
    </div>
</div>
<?php
get_footer();
?>