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
    <span class="ps-expand"><a class="lp-expand-img"><i class="fas fa-expand-arrows-alt"></i></a></span>
</div>
<?php
get_footer();
?>