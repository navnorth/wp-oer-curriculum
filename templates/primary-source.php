<?php

add_filter('body_class', function($classes){
    $classes[] = 'primary-source-template';
    return $classes;
});

get_header();
?>
<div class="container">
    
</div>
<?php
get_footer();
?>