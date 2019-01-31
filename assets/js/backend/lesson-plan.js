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
                    if($('div.lp-ac-item').length)
                    {
                        console.log('if');
                        $(response).insertAfter('div.lp-ac-item:last');
                    }
                    else
                    {
                        console.log('else');
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
                console.log('id',moduleId);
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
            // Sort the parents
            $( "#oer-lp-sortable").sortable({
                containment: "document",
                connectWith: '#oer-lp-sortable',
                items: "> div",
                handle: ".lp-sortable-handle",
                tolerance: "pointer",
                cursor: "move",
                opacity: 0.7,
               // revert: 300,
                //delay: 150,
                placeholder: "movable-placeholder",
                start: function(e, ui) {
                    //$("#oer-lp-sortable .panel.panel-default").addClass('ui-sortable-start');
                    $(".panel-body").addClass("hide");
                    //ui.placeholder.height(ui.helper.outerHeight());
                    ui.placeholder.height(37);
                }
            });

            $('#oer-lp-sortable').on("sortstop", function (event, ui) {

                $( "#oer-lp-sortable .panel.panel-default").removeClass('ui-sortable-start')
                $(".panel-body").removeClass("hide");
                console.log("Stop");

                $("#oer-lp-sortable .lp-element-wrapper").each(function (index) {
                    var count = index + 1;

                    var position = $(this).find('.element-order').val();
                    console.log("position value is"  + position);
                    var newvalue = $(this).find('.element-order').val(count);
                    // reassign all of the numbers once it's loaded.

                    var textAreaId = $(this).find('textarea').attr('id');

                    if (typeof textAreaId !== 'undefined') {
                        console.log("Element id  " + textAreaId);
                        tinymce.execCommand( 'mceRemoveEditor', false, textAreaId );
                        tinymce.execCommand( 'mceAddEditor', false, textAreaId );
                    }
                })
            });

            // Inner child activities element sortable
            $( "#lp-ac-inner-panel").sortable({
                containment: "document",
                items: "> div",
                handle: ".lp-inner-sortable-handle",
                tolerance: "pointer",
                cursor: "move",
                opacity: 0.7,
                // revert: 300,
                //delay: 150,
                placeholder: "movable-placeholder",
                start: function(e, ui) {
                    //$("#oer-lp-sortable .panel.panel-default").addClass('ui-sortable-start');
                    //$(".panel-body").addClass("hide");
                    ui.placeholder.height(ui.helper.outerHeight());
                    //ui.placeholder.height(37);
                }
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
});
