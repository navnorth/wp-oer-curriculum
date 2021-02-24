jQuery(document).ready(function($){
    // When the user scrolls down 50px from the top of the document, fixed the header to the top
    if(jQuery('.single-oer-curriculum .container .oer-curriculum-featured-section').length){
      var headerHeight = jQuery('#side-header.fusion-mobile-menu-design-classic').outerHeight();
      window.onscroll = function() {
          if (jQuery(window).width()<=600){
              var rightWidth = jQuery('.single-oer-curriculum .container .oer-curriculum-featured-section').width();
              var oerCurriculumTop = jQuery('.single-oer-curriculum .container .oer-curriculum-featured-section').offset().top;
              var leftedge = jQuery('.single-oer-curriculum .container .oer-curriculum-featured-section').offset().left;   
              if (document.body.scrollTop > oerCurriculumTop || document.documentElement.scrollTop > oerCurriculumTop) {
                  jQuery('.tc-oer-curriculum-details-header').css({
                      "background":"#ffffff",
                      "padding":"15px 50px",
                      "box-shadow": "1px 1px 5px 0px rgba(0,0,0,0.2)",
                      "z-index":"999999",
                      "top":"0"
                  });
                  jQuery('.tc-oer-curriculum-details-header').addClass('fixed');
              } else {
                  topPos = headerHeight - document.documentElement.scrollTop;
                  topPos += 60;
                  if (jQuery('#wpadminbar').length>0)
                      topPos = topPos + 32;
                  jQuery('.tc-oer-curriculum-details-header').css({
                      "background":"none",
                      "padding":"25px 0px",
                      "box-shadow":"none",
                      "z-index":"0",
                      "width":"100%",
                      "top": topPos + "px",
                      "width": rightWidth + "px",
                      "left": leftedge + "px"
                  });
                  jQuery('.tc-oer-curriculum-details-header').removeClass('fixed');
              }
          } else {
              var rightWidth = jQuery('.single-oer-curriculum .container .oer-curriculum-featured-section').width();
              var leftedge = jQuery('.single-oer-curriculum .container .oer-curriculum-featured-section').offset().left;    
              var oerCurriculumTop = jQuery('.single-oer-curriculum .container .oer-curriculum-featured-section').offset().top;      
              if (document.body.scrollTop > oerCurriculumTop || document.documentElement.scrollTop > oerCurriculumTop) {
                  jQuery('.tc-oer-curriculum-details-header').css({
                      "background":"#ffffff",
                      "padding":"15px 50px",
                      "box-shadow": "1px 1px 5px 0px rgba(0,0,0,0.2)",
                      "z-index":"999999",
                      "width": rightWidth + "px",
                      "left": leftedge + "px"
                  });
                  jQuery('.tc-oer-curriculum-details-header').addClass('fixed');
              } else {
                  jQuery('.tc-oer-curriculum-details-header').css({
                      "background":"none",
                      "padding":"25px 0px",
                      "box-shadow":"none",
                      "z-index":"0",
                      "width":"100%"
                  });
                  jQuery('.tc-oer-curriculum-details-header').removeClass('fixed');
              }
          }
      }
    
    }
    
    
    
    $('.oer-curriculum-nav-right-ajax').on("click", function(){
        var id = $(this).attr('data-id');
        var cnt = $(this).attr('data-count');
        var next = $(this);
        $.post(oer_curriculum_ajax_object.ajaxurl,
            {
            action:'oer_curriculum_get_source_callback',
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
                    $('.oer-curriculum-nav-left').parent().removeClass('ps-nav-hidden');
                    $('.oer-curriculum-nav-left').attr('data-id',prevId);
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
        $('.oer-curriculum-nav-right,.oer-curriculum-nav-left').attr('data-activetab', e.target.id);
        var activeContent = $(e.target).attr('href');
        if ($(activeContent).find('.tc-tab-content').height()<340){
            $(activeContent).find('.tc-read-more').addClass('tc-btn-hide');
        }
    });
    
    $(document).on("show.bs.collapse", '#tcHiddenFields.collapse', function (){
        $('#see-more-link').text("SEE LESS -");
    });
    
    $(document).on("hide.bs.collapse", '#tcHiddenFields.collapse', function (){
        $('#see-more-link').text("SEE MORE +");
    });
    
    $(document).on("show.bs.collapse", '.oer-curriculum-subject-hidden.collapse', function (){
        var more_count = $('.see-more-subjects').attr('data-count');
        $('.see-more-subjects').text("SEE " + more_count + " LESS -");
    });
    
    $(document).on("hide.bs.collapse", '.oer-curriculum-subject-hidden.collapse', function (){
        var more_count = $('.see-more-subjects').attr('data-count');
        $('.see-more-subjects').text("SEE " + more_count + " MORE +");
    });
    
    $(document).on("show.bs.collapse", '.tc-oer-curriculum-details-standard.collapse', function (){
        $(this).parent().find('.oer-curriculum-standard-toggle i').removeClass('fa-caret-right').addClass('fa-caret-down');
    });
    
    $(document).on("hide.bs.collapse", '.tc-oer-curriculum-details-standard.collapse', function (){
        $(this).parent().find('.oer-curriculum-standard-toggle i').removeClass('fa-caret-down').addClass('fa-caret-right');
    });
    
    $(document).on("click", '.oer-curriculum-read-more', function (){
        $('.oer-curriculum-excerpt').hide();
        $('.oer-curriculum-full-content').show();
    });
    
     $(document).on("click", '.oer-curriculum-read-less', function (){
        $('.oer-curriculum-excerpt').show();
        $('.oer-curriculum-full-content').hide();
    });
    
    
    $('.oer-curriculum-nav-right,.oer-curriculum-nav-left').on("click", function(e){
        e.preventDefault();
        var nav = $(this);
        var url = nav.attr('href');
        var tab = nav.attr('data-activetab');
        oer_curriculum_redirect_with_post(url, tab);
    });
    
    $('.question-popup-button').on("click", function(e){
        window.scroll({
            top: $('.ps-info-tabs-content').offset().top, 
            left: 0, 
            behavior: 'smooth'
        });
        tab_content = $(this).attr('href');
        $('.ps-info-tabs a[href="' + tab_content + '"]').tab("show");
        $('.ps-info-tabs a[href="' + tab_content + '"]').removeClass*('show');
    });
    
    if ($('.oer-curriculum-nav-right,.oer-curriculum-nav-left').is(":visible")) {
         $('.oer-curriculum-nav-right,.oer-curriculum-nav-left').attr('data-activetab', $('.ps-info-tabs li a.nav-link.active').attr('id'));
    }
    
    /*
    if (typeof wp.data !== "undefined") {
  		wp.data.subscribe(function(){
  			var isSav = wp.data.select('core/editor').isSavingPost();
  			var isAutosavingPost = wp.data.select('core/editor').isAutosavingPost();
  			
  			if (isSavingPost && !isAutosavingPost) {
  				window.tinyMCE.triggerSave();
  			}
  		});
  	}
    */
    
    // set external links to open in new window and have distinct style
    $('a').each(function() {
        if( location.hostname === this.hostname || !this.hostname.length) {
            $(this).addClass('local');
        } else {
            $(this).attr( 'target','_blank' );
            $(this).addClass( 'external_link' );
        }
        /*var a = new RegExp('' + window.location.host + '|mailto' , 'i');
        if(!a.test(this.href)) {
            $(this).attr( 'target','_blank' );
            $(this).addClass( 'external_link' );
        }*/
    });
    
    setTimeout(function(){
      jQuery('a[data-toggle="collapse"]').addClass('et_smooth_scroll_disabled');
    }, 500);
    
    
});

function oer_curriculum_redirect_with_post(url, tab) {
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