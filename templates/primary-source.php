<?php

add_filter('body_class', function($classes){
    $classes[] = 'primary-source-template';
    return $classes;
});

get_header();

$back_url = "";
$source_id = 0;
$curriculum = get_query_var('curriculum');
if ($curriculum)
    $back_url = "lesson-plans/".$curriculum;
$psource = get_query_var('source');
$sources = explode("-",$psource);
if ($sources)
    $source_id = $sources[count($sources)-1];

$resource = get_post($source_id);
$featured_image_url = get_the_post_thumbnail_url($resource->ID, "full");

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
            <a class="nav-link active" id="ps-information-tab" data-toggle="tab" href="#ps-information-tab-content" role="tabs" aria-controls="ps-information-tab" aria-selected="true" aria-expanded="false">
                Information    
            </a>
        </li>
        <li class="nav-item col-md-4 col-sm-4 padding-0">
            <a class="nav-link" id="ps-information-tab" data-toggle="tab" href="#ps-information-tab-content" role="tabs" aria-controls="ps-information-tab" aria-selected="true" aria-expanded="false">
                For The Student    
            </a>
        </li>
        <li class="nav-item col-md-4 col-sm-4 padding-0">
            <a class="nav-link" id="ps-information-tab" data-toggle="tab" href="#ps-information-tab-content" role="tabs" aria-controls="ps-information-tab" aria-selected="true" aria-expanded="false">
                For The Teacher    
            </a>
        </li>
    </ul>
</div>
<div class="ps-info-tabs-content">
    
</div>
<?php
get_footer();
?>