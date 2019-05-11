jQuery(document).ready(function($){
    $('.lp-nav-right-ajax').on("click", function(){
        var id = $(this).attr('data-id');
        var cnt = $(this).attr('data-count');
        var next = $(this);
        $.post(lp_ajax_object.ajaxurl,
            {
            action:'lp_get_source_callback',
            curriculum_id: next.attr('data-curriculum'),
            next_source: next.attr('data-nextsource'),
            index: next.attr('data-id')
            }).done(function (response) {
                response = JSON.parse(response);
                
                /** Navigation Arrow Updates **/
                $('.ps-header').css({'background':'url(' + response.featured_image + ') no-repeat top left'})
                $('.ps-header').attr('data-curid', id);
                $('.ps-expand > a').attr('href', response.featured_image);
                nextId = parseInt(id)+1;
                if (nextId>=cnt) {
                    next.attr('data-id',nextId);
                    next.parent().addClass('ps-nav-hidden');
                } else {
                    next.attr('data-id',nextId);
                }
                prevId = id-1;
                if (prevId>=0) {
                    $('.lp-nav-left').parent().removeClass('ps-nav-hidden');
                    $('.lp-nav-left').attr('data-id',prevId);
                }
                /** Update Resource Details **/
                $('.ps-info-title').text(response.resource.post_title);
                $('.ps-info-description').html(response.resource.post_content);
                $('.ps-resource-url .ps-value a').attr('href',response.resource_meta.oer_resource_url);
                $('.ps-resource-url .ps-value a').text(response.resource_meta.oer_resource_url);
                
                /** Update Student Info Tab Content **/
                $('#ps-student-info-tab-content').html("");
                $('#ps-student-info-tab-content').html(response.student_info);
                
                /** Update Teacher Info Tab Content **/
                $('#ps-teacher-info-tab-content').html("");
                $('#ps-teacher-info-tab-content').html(response.teacher_info);
            }
        );
    });
    
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('.lp-nav-right,.lp-nav-left').attr('data-activetab', e.target.id);
        var activeContent = $(e.target).attr('href');
        if ($(activeContent).find('.tc-tab-content').height()<340){
            $(activeContent).find('.tc-read-more').addClass('tc-btn-hide');
        }
    });
    
    $('.lp-nav-right,.lp-nav-left').on("click", function(e){
        e.preventDefault();
        var nav = $(this);
        var url = nav.attr('href');
        var tab = nav.attr('data-activetab');
        lp_redirect_with_post(url, tab);
    });
    
    $('.question-popup-button').on("click", function(e){
        e.preventDefault();
        tab_content = $(this).attr('href');
        $('.ps-info-tabs a[href="' + tab_content + '"]').tab("show");
        $('.ps-info-tabs a[href="' + tab_content + '"]').removeClass*('show');
        console.log($(tab_content));
        $('html,body').animate({
            scrollTop: $(tab_content).offset().top
        }, 2000);
    });
});

function lp_redirect_with_post(url, tab) {
    var form = document.createElement('form');
    form.action = url;
    form.method = 'POST';
    
    var hInput = document.createElement('input');
    hInput.type = 'hidden';
    hInput.name = 'activeTab';
    hInput.value = tab;
    form.appendChild(hInput);
    
    document.body.appendChild(form);
    form.submit();
}