/**
 * All backend custom JavaScript code are here
 *
 */
jQuery(document).ready(function ($) {

    var LessonPlan = {
        UpdateActivityTitle: function () {
            $(document).on('keyup', '.lp-ac-item input[type=text]', function () {
                var InputValue = $(this).val();
                var ContainerId = $(this).closest('.lp-ac-item').attr('id');
                $('a[href=#' + ContainerId +']').text(InputValue);
            });
        },

        // Add more time elements
        AddMoreTimeElements: function () {
            $(document).on('click', '.lp-add-time-element', function () {
                var ClonedDiv = $('.lp-time-element-row:first').clone();
                ClonedDiv.insertAfter('div.lp-time-element-row:last');
                ClonedDiv.find('input[type=text]').val('');
                ClonedDiv.find(':selected').removeAttr('selected');
                $('.remove-time-element').removeClass('disabled').prop('disabled', false);
            });
        },

        // Remove time elements
        RemoveTimeElements: function () {
            $(document).on('click', '.remove-time-element', function () {
                $(this).closest('.lp-time-element-row').remove();
                if($('.lp-time-element-row').length == 1) {
                    $('.lp-time-element-row .remove-time-element ').addClass('disabled').prop('disabled', true);
                }
            });
        },

        // Add More Related Instructional Objectives
        AddMoreObjectives: function () {
            $(document).on('click', '.lp-add-related-objective', function () {
                var ClonedDiv = $('.lp-related-objective-row:first').clone();
                ClonedDiv.insertAfter('div.lp-related-objective-row:last');
                ClonedDiv.find('input[type=text]').val('');
                $('.lp-remove-related-objective').prop('disabled', false);
            });
        },

        // Remove time elements
        RemoveObjectives: function () {
            $(document).on('click', '.lp-remove-related-objective', function () {
                $(this).closest('.lp-related-objective-row').remove();
                if($('.lp-related-objective-row').length == 1) {
                    $('.lp-related-objective-row .lp-remove-related-objective').prop('disabled', true);
                }
            });
        },

        // Add Activity in Lesson
        AddActivityInLesson: function () {
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
                });
            });
        },

        // Delete module
        DeleteModule: function () {
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
        LessonElementSortable: function () {

            $(document).on('click', '.reorder-up', function(){
                var $current = $(this).closest('.lp-element-wrapper');
                var $previous = $current.prev('.lp-element-wrapper');
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    LessonPlan.ChangeElementOrder();
                }
                return false;
            });

            $(document).on('click', '.reorder-down', function(){
                var $current = $(this).closest('.lp-element-wrapper');
                var $next = $current.next('.lp-element-wrapper');
                if($next.length !== 0){
                    $current.insertAfter($next);
                    LessonPlan.ChangeElementOrder();
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
        ChangeElementOrder: function() {
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

            LessonPlan.ToggleUpDownButton();
        },

        // Show/Hide up/down button
        ToggleUpDownButton: function() {
            // Hide the up button in the first child
            $('.reorder-up').removeClass('hide');
            $('.reorder-down').removeClass('hide');
            $('.reorder-up').first().addClass('hide');
            $('.reorder-down').last().addClass('hide');
        },

        // Create dynamic module
        CreateDynamicModule: function () {

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


                    // Create dynamic elements on sidebar
                   /* var cloned = $('.sidebar-lesson-activities-title li:last').clone();
                    cloned.find('a').attr('href', '#lp-ac-item-' + total_form_box);
                    cloned.find('a').text('Unnamed Activity');
                    cloned.insertAfter('.sidebar-lesson-activities-title li:last');*/

                    $('#lp-dynamic-module-modal').modal('hide');
                });
            });
        },

        // Dismiss the plugin installation message
        DismissInstalNotice: function () {
            $(document).on('click', '#oep-lp-dismissible', function () {
                $.post(ajaxurl, {action:'lp_dismiss_notice_callback'}).done(function (response) {

                });
            });
        }
    };

    // Initialize all function on ready state
    LessonPlan.UpdateActivityTitle();
    LessonPlan.AddMoreTimeElements();
    LessonPlan.RemoveTimeElements();
    LessonPlan.AddMoreObjectives();
    LessonPlan.RemoveObjectives();
    LessonPlan.AddActivityInLesson();
    LessonPlan.DeleteModule();
    LessonPlan.LessonElementSortable();
    LessonPlan.CreateDynamicModule();
    LessonPlan.ToggleUpDownButton();
    LessonPlan.DismissInstalNotice();
});
