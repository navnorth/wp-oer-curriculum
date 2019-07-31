$ = jQuery;

$(document).ready(function() {
    $('#show-excerpt').click(function() {
        toggleArrow('#framework-excerpt', '#show-excerpt');
        $('#framework-excerpt').slideToggle('slow', function() {
            if ($(this).is(':visible')) { 
                $(this).css({'display': 'flex', 'align-items': 'stretch'});
            } else {
            }
        })
    })
    
    $('#close-excerpt').click(function() {
        toggleArrow('#framework-excerpt', '#show-excerpt');
        $('#framework-excerpt').slideUp('slow');
    })
    
    $(".open-standards").click(function() {
        $("#standards-dialog").dialog({
            modal: true,
            width: 700,
            title: 'Standards',
            draggable: false,
            resizable: false,
            dialogClass: 'standards-dialog',
            create: function(event, ui) { 
                var widget = $(this).dialog("widget");
                $(".ui-dialog-titlebar-close span.ui-button-icon-primary", widget)
                    .removeClass("ui-icon-closethick ui-icon")
                    .addClass("fal fa-times");
            },
            open: function(event, ui) {
                $("body").addClass("modal-open");
            },
            close: function(event, ui) {
                $("body").removeClass("modal-open");
            }
        });
    });

    $("#standards-dialog").on("show", function () {
        $("body").addClass("modal-open");
      }).on("hidden", function () {
        $("body").removeClass("modal-open")
      });

    $(".open-tags").click(function() {
        $("#tags-dialog").dialog({
            modal: true,
            width: 700,
            minHeight: 200,
            title: 'Tags',
            draggable: false,
            resizeable: false,
            dialogClass: 'tags-dialog',
            create: function(event, ui) { 
                var widget = $(this).dialog("widget");
                $(".ui-dialog-titlebar-close span.ui-button-icon-primary", widget)
                    .removeClass("ui-icon-closethick ui-icon")
                    .addClass("fal fa-times");
             }
        });
    })
    
    if ($('.tc-home-tabs-content .active .tc-tab-content').height()<340) {
        $('.tc-home-tabs-content .active .tc-read-more').addClass('tc-btn-hide');
    }
    $(".tc-read-more").on("click",function(e){
        var tabContent = $(this).parent().find('.tc-tab-content');
        var minHeight = 340;
        if (tabContent.height()<=minHeight) {
            tabContent.addClass("slidedown");
            $(this).text("Close");
        } else {
            tabContent.removeClass("slidedown");
            $(this).text("Read More");
        }
    });
    
    if ($('.excerpt-section-custom-width').is(":visible")) {
        excerpt_height = $('.excerpt-section-custom-width').innerHeight();
        border_width = Math.floor(excerpt_height/2);
        $('.tc-pink-triangle').css({
            'border-top': border_width.toString() + 'px solid transparent',
            'border-bottom': border_width.toString() + 'px solid transparent'
        });
    }

    $('#tc-historical-background-tab').click();

    $('.tc-sensitive-material-section').on('click', function(e) {
        $('#sensitive-material-info').toggle();
    })
})

function toggleArrow(parentID, iconID) {
    if (!$(parentID) || $(!iconID)) { return; }

    if ($(parentID).is(':visible')) {
        $(`${iconID} i`).removeClass('fa-angle-up').addClass('fa-angle-down');
    } else {
        $(`${iconID} i`).removeClass('fa-angle-down').addClass('fa-angle-up');
    }
}

// Event Tracker Function
function curriculum_trackEvent(eventCategory, eventAction, eventLabel, eventValue = null) {
    eventLabel = eventLabel.toString();

    // To make all google event param in lower case
    eventLabel      = eventLabel.toLowerCase();
    eventAction     = eventAction.toLowerCase();
    eventCategory   = eventCategory.toLowerCase();
    
    if (typeof ga != 'undefined' && ga != null){
        if(eventValue == null)
          return ga('send', 'event',eventCategory,eventAction,eventLabel)
        else
          eventValue = eventValue.toLowerCase()
          return ga('send', 'event',eventCategory,eventAction,eventLabel,eventValue)
    }
    return 0;
}
