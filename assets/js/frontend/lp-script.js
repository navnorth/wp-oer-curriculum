jQuery(document).ready(function ($) {
    $('.lp-nav-right').on("click", function(){
        $.post(lp_ajax_object.ajaxurl,
            {
            action:'lp_get_source_callback',
            curriculum_id: $(this).attr('data-curriculum'),
            next_source: $(this).attr('data-nextsource')
            }).done(function (response) {
                response = JSON.parse(response);
                console.log(response);
                $('.ps-header').css({'background':'url(' + response.featured_image + ') no-repeat top left'})
            }
        );
    });
});