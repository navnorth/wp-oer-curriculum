$ = jQuery;
$('#show-excerpt').click(function() {
    $('#framework-excerpt').slideToggle('slow', function() {
        if ($(this).is(':visible')) { 
            $(this).css({'display': 'flex', 'align-items': 'stretch'});
        }
    })
})

$('#close-excerpt').click(function() {
    $('#framework-excerpt').slideUp('slow');
})