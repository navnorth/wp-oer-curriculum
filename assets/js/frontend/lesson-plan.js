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

    function toggleArrow(parentID, iconID) {
        if ($(parentID).is(':visible')) {
            $(`${iconID} i`).removeClass('fa-angle-up').addClass('fa-angle-down');
        } else {
            $(`${iconID} i`).removeClass('fa-angle-down').addClass('fa-angle-up');
        }
    }
    
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
            dialogClass: 'standards-dialog'
        });
        return false;
    });
    
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
})