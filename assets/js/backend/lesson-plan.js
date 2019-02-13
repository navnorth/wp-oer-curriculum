/**
 * All backend custom JavaScript code are here
 *
 */
jQuery(document).ready(function ($) {

    var LessonPlan = {
        updateActivityTitle: function () {
            $(document).on('keyup', '.lp-ac-item input[type=text]', function () {
                var InputValue = $(this).val();
                var ContainerId = $(this).closest('.lp-ac-item').attr('id');
                $('a[href=#' + ContainerId +']').text(InputValue);
            });
        },

        // Add more time elements
        addMoreTimeElements: function () {
            $(document).on('click', '.lp-add-time-element', function () {
                var ClonedDiv = $('.lp-time-element-row:first').clone();
                ClonedDiv.insertAfter('div.lp-time-element-row:last');
                ClonedDiv.find('input[type=text]').val('');
                ClonedDiv.find(':selected').removeAttr('selected');
                $('.remove-time-element').removeClass('disabled').prop('disabled', false);
            });
        },

        // Remove time elements
        removeTimeElements: function () {
            $(document).on('click', '.remove-time-element', function () {
                $(this).closest('.lp-time-element-row').remove();
                if($('.lp-time-element-row').length == 1) {
                    $('.lp-time-element-row .remove-time-element ').addClass('disabled').prop('disabled', true);
                }
            });
        },

        // Add More Related Instructional Objectives
        addMoreObjectives: function () {
            $(document).on('click', '.lp-add-related-objective', function () {
                var ClonedDiv = $('.lp-related-objective-row:first').clone();
                ClonedDiv.insertAfter('div.lp-related-objective-row:last');
                ClonedDiv.find('input[type=text]').val('');
                $('.lp-remove-related-objective').prop('disabled', false);
            });
        },

        // Remove time elements
        removeObjectives: function () {
            $(document).on('click', '.lp-remove-related-objective', function () {
                $(this).closest('.lp-related-objective-row').remove();
                if($('.lp-related-objective-row').length == 1) {
                    $('.lp-related-objective-row .lp-remove-related-objective').prop('disabled', true);
                }
            });
        },

        // Add Activity in Lesson
        addActivityInLesson: function () {
            $(document).on('click', '.lp-add-ac-item', function () {
                var total_form_box = parseInt($('.lp-ac-item').length, 10);
                $.post(ajaxurl, {action:'lp_add_more_activity_callback', row_id: total_form_box}).done(function (response) {
                    if($('div.lp-ac-item').length) {
                        $(response).insertAfter('div.lp-ac-item:last');
                    } else {
                       $('.lp-ac-inner-panel').html(response);
                    }

                    tinymce.execCommand( 'mceRemoveEditor', false, 'oer-lp-activity-detail-' + total_form_box );
                    tinymce.execCommand( 'mceAddEditor', false, 'oer-lp-activity-detail-' + total_form_box );

                    // Create dynamic elements on sidebar
                    var cloned = $('.sidebar-lesson-activities-title li:last').clone();
                    cloned.find('a').attr('href', '#lp-ac-item-' + total_form_box);
                    cloned.find('a').text('Unnamed Activity');
                    cloned.insertAfter('.sidebar-lesson-activities-title li:last');
                    // Toggle reorder button
                    LessonPlan.toggleUpDownButton();
                });
            });
        },

        // Delete module
        deleteModule: function () {
            $(document).on('click', '.lp-remove-module',function(e) {
                var moduleId = $(this).closest('.panel-default').attr('id');
                e.preventDefault();
                $('#lp-confirm').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                .on('click', '#lp-delete-confirm', function(e) {
                    $('#' + moduleId).remove();
                    $('a[href=#' + moduleId +']').parent('li').remove();
                    $('#lp-confirm').modal('hide');
                });
            });
        },

        // Drag and drop elements
        lessonElementSortable: function () {

            $(document).on('click', '.reorder-up', function(){
                var $current = $(this).closest('.lp-element-wrapper');
                var $previous = $current.prev('.lp-element-wrapper');
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    LessonPlan.changeElementOrder();
                }
                return false;
            });

            $(document).on('click', '.reorder-down', function(){
                var $current = $(this).closest('.lp-element-wrapper');
                var $next = $current.next('.lp-element-wrapper');
                if($next.length !== 0){
                    $current.insertAfter($next);
                    LessonPlan.changeElementOrder();
                }
                return false;
            });

            // Author element reorder
            $(document).on('click', '.author-reorder-up', function(){
                var $current = $(this).closest('.lp-author-element-wrapper');
                var $previous = $current.prev('.lp-author-element-wrapper');
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    LessonPlan.toggleUpDownButton();
                }
                return false;
            });

            $(document).on('click', '.author-reorder-down', function(){
                var $current = $(this).closest('.lp-author-element-wrapper');
                var $next = $current.next('.lp-author-element-wrapper');
                if($next.length !== 0){
                    $current.insertAfter($next);
                    LessonPlan.toggleUpDownButton();
                }
                return false;
            });


            // For move inner module activity
            $(document).on('click', '.activity-reorder-up', function(){
                var $current = $(this).closest('.lp-ac-item');
                var $previous = $current.prev('.lp-ac-item');
                if($previous.length !== 0){
                    $current.insertBefore($previous);

                    $(".lp-ac-item").each(function (index) {
                        var textAreaId = $(this).find('textarea').attr('id');

                        if (typeof textAreaId !== 'undefined') {
                            tinymce.execCommand( 'mceRemoveEditor', false, textAreaId );
                            tinymce.execCommand( 'mceAddEditor', false, textAreaId );
                        }
                    })
                }
                return false;
            });

            $(document).on('click', '.activity-reorder-down', function(){
                var $current = $(this).closest('.lp-ac-item');
                var $next = $current.next('.lp-ac-item');
                if($next.length !== 0){
                    $current.insertAfter($next);

                    $(".lp-ac-item").each(function (index) {
                        var textAreaId = $(this).find('textarea').attr('id');

                        if (typeof textAreaId !== 'undefined') {
                            tinymce.execCommand( 'mceRemoveEditor', false, textAreaId );
                            tinymce.execCommand( 'mceAddEditor', false, textAreaId );
                        }
                    })
                }
                return false;
            });
        },

        // Change order value in hidden field and reinitialize the text editor
        changeElementOrder: function() {
            $("#oer-lp-sortable .lp-element-wrapper").each(function (index) {
                var count = index + 1;

                var position = $(this).find('.element-order').val();
                var newvalue = $(this).find('.element-order').val(count);
                // reassign all of the numbers once it's loaded.

                var textAreaId = $(this).find('textarea').attr('id');

                if (typeof textAreaId !== 'undefined') {
                    tinymce.execCommand( 'mceRemoveEditor', false, textAreaId );
                    tinymce.execCommand( 'mceAddEditor', false, textAreaId );
                }
            });

            LessonPlan.toggleUpDownButton();
        },

        // Show/Hide up/down button
        toggleUpDownButton: function() {
            // Hide the up button in the first child
            jQuery('.reorder-up').removeClass('hide');
            jQuery('.reorder-down').removeClass('hide');
            jQuery('.reorder-up').first().addClass('hide');
            jQuery('.reorder-down').last().addClass('hide');

            // Toggle Activity button order
            jQuery('.activity-reorder-up').removeClass('hide');
            jQuery('.activity-reorder-down').removeClass('hide');
            jQuery('.activity-reorder-up').first().addClass('hide');
            jQuery('.activity-reorder-down').last().addClass('hide');

            // Toggle button from author module
            // Hide up button from first element
            // hide down button from last element
            jQuery('.author-reorder-up').removeClass('hide');
            jQuery('.author-reorder-down').removeClass('hide');
            jQuery('.author-reorder-up').first().addClass('hide');
            jQuery('.author-reorder-down').last().addClass('hide');
        },

        // Create dynamic module
        createDynamicModule: function () {

            // Open modal when click one add module button
            $(document).on('click', '#lp-create-dynamic-module', function (e) {
                e.preventDefault();
                $('#lp-dynamic-module-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            });

            $(document).on('click','#lp-create-module-btn', function () {
                var total_form_box = parseInt($('.lp-element-wrapper').length, 10);
                var module_type = $('#module-type').val();

                $.post(ajaxurl, {action:'lp_create_module_callback', module_type: module_type, row_id: total_form_box}).done(function (response) {
                    $(response).insertAfter('div.lp-element-wrapper:last');

                    if (module_type == 'editor') {
                        tinymce.execCommand( 'mceRemoveEditor', false, 'oer-lp-custom-editor-' + total_form_box );
                        tinymce.execCommand( 'mceAddEditor', false, 'oer-lp-custom-editor-' + total_form_box );
                    }

                    $('#lp-dynamic-module-modal').modal('hide');
                    // Toggle reorder button
                    LessonPlan.toggleUpDownButton();
                });
            });
        },

        // Dismiss the plugin installation message
        dismissInstallNotice: function () {
            $(document).on('click', '#oep-lp-dismissible', function () {
                $.post(ajaxurl, {action:'lp_dismiss_notice_callback'}).done(function (response) {

                });
            });
        },
        // Add more author
        AddMoreAuthor: function () {
            $(document).on('click', '#lp-add-more-author', function () {
                console.log("clicked");
                var ClonedDiv = $('.lp-author-element-wrapper:last').clone();
                ClonedDiv.insertAfter('div.lp-author-element-wrapper:last');
                ClonedDiv.find('input[type=text]').val('');
                $('.lp-remove-author').removeAttr('disabled');
                LessonPlan.toggleUpDownButton();
            });
        },

        // Delete author
        deleteAuthor: function () {
            $(document).on('click', '.lp-remove-author',function(e) {
                var author = $(this).closest('.panel-default');
                var elementId = author.attr('id');
                e.preventDefault();
                $('#lp-delete-author').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                    .on('click', '#lp-author-delete-confirm', function(e) {
                        author.remove();
                        $('a[href=#' + elementId +']').parent('li').remove();
                        $('#lp-delete-author').modal('hide');

                        // Disable delete button for author
                        if($('.lp-author-element-wrapper').length === 1) {
                            $('.lp-remove-author').attr('disabled', 'disabled');
                        }
                    });
            });
        },

        // Upload author image
        lpUploadAuthorImage: function () {
            $(document).on('click', '.lp-oer-person-placeholder', function (e) {
                var frame;
                e.preventDefault();
                var dis = $(this);
                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'Select Author Picture',
                    button: { text: 'Use Picture' },
                    library: { type: [ 'image' ] },
                    multiple:false
                });

                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    var attachment_url = attachment.url;
                    dis.prev('input').val(attachment_url);
                    dis.attr('src', attachment_url);
                });

                frame.open();
            });
        },

        // Select standards
        lpSelectStandards: function () {
            jQuery(document).on('click', '#lp-select-standard', function (e) {
                e.preventDefault();
                // Open modal
                jQuery('#lpOerStandardModal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                .on('click', '#lpSelectStandardSaveBtn', function (e) {
                    e.preventDefault();
                    var selectedStandards = [];
                    var selectedHtml = "";
                    jQuery.each(jQuery('#lpOerStandardModal input[type=checkbox]:checked'), function(){
                        var standardId = jQuery(this).val();
                        selectedStandards.push(standardId);
                        var standardTitle = jQuery(this).next().next('div.lp-notation-description').text();

                        selectedHtml += '<span class="selected-standard-pill">';
                        selectedHtml += standardTitle;
                        selectedHtml += '<a href="javascript:void(0)" class="remove-ss-pill" data-id="'+standardId+'"><i class="fa fa-times"></i>';
                        selectedHtml += '</a></span>';
                    });
                    jQuery('#selected-standard-wrapper').html(selectedHtml);
                    var selectedStandardsIds = selectedStandards.join();
                    jQuery("input[name='oer_lp_standards']").val(selectedStandardsIds);
                    jQuery('#lpOerStandardModal').modal('hide');
                });
            });
        },

        // Remove selected standards from the list
        lpRemoveStandardsFromList: function () {
            jQuery(document).on('click', 'a.remove-ss-pill', function (e) {
                e.preventDefault();
                var dis = jQuery(this);
                var pillId = dis.attr('data-id');
                dis.parent().remove();

                // Update the selected ids in input fields
                var standardsIds =  jQuery("input[name='oer_lp_standards']").val();
                var standardsArr = standardsIds.split(",");
                standardsArr = jQuery.grep(standardsArr, function(value) {
                    return value != pillId;
                });

                standardsIds = standardsArr.join();
                jQuery("input[name='oer_lp_standards']").val(standardsIds);
                // Unchecked the checkbox from popup
                jQuery('#lpOerStandardModal input[value='+pillId+']').attr('checked', false);
            });
        }
    };

    // Initialize all function on ready state
    LessonPlan.updateActivityTitle();
    LessonPlan.addMoreTimeElements();
    LessonPlan.removeTimeElements();
    LessonPlan.addMoreObjectives();
    LessonPlan.removeObjectives();
    LessonPlan.addActivityInLesson();
    LessonPlan.deleteModule();
    LessonPlan.lessonElementSortable();
    LessonPlan.createDynamicModule();
    LessonPlan.toggleUpDownButton();
    LessonPlan.dismissInstallNotice();
    LessonPlan.AddMoreAuthor();
    LessonPlan.deleteAuthor();
    LessonPlan.lpUploadAuthorImage();
    LessonPlan.lpSelectStandards();
    LessonPlan.lpRemoveStandardsFromList();
});
