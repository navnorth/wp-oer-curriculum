<?php

add_filter('body_class', function($classes){
    $classes[] = 'primary-source-template';
    return $classes;
});

get_header();

$back_url = "";
$curriculum = get_query_var('curriculum');
if ($curriculum)
    $back_url = "lesson-plans/".$curriculum;
?>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <a href="<?php echo site_url($back_url); ?>" class="lp-back-button"><i class="fas fa-chevron-left"></i> <?php _e("Back to Inquiry Set", OER_LESSON_PLAN_SLUG)?></a>
        </div>
        <div class="col-md-8"></div>
    </div>
</div>
<div class="ps-header"></div>
<?php
get_footer();
?>