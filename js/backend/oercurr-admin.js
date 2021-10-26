jQuery(document).ready(function($) {

  jQuery('#oercurr-dismissible-notice .notice-dismiss').on("click", function(e) {
                
		//* Data to make available via the $_POST variable
		data = {
			action: 'oercurr_dismiss_notice_callback',
			wp_ajax_oer_admin_nonce: wp_ajax_oer_admin.wp_ajax_oer_admin_nonce
		};

		//* Process the AJAX POST request
		$.post(
     ajaxurl,
     data,
     function(response) {
       
     }
    );

		return false;
	});

});