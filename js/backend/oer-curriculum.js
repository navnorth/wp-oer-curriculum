/**
 * All backend custom JavaScript code are here
 *
 */
jQuery(document).ready(function ($) {

    var LessonPlan = {
        updateActivityTitle: function () {
            $(document).on('keyup', '.oer-curriculum-ac-item input[type=text]', function () {
                var InputValue = $(this).val();
                var ContainerId = $(this).closest('.oer-curriculum-ac-item').attr('id');
                $('a[href=#' + ContainerId +']').text(InputValue);
            });
        },

        // Add more time elements
        addMoreTimeElements: function () {
            $(document).on('click', '.oer-curriculum-add-time-element', function () {
                var ClonedDiv = $('.oer-curriculum-time-element-row:first').clone();
                ClonedDiv.insertAfter('div.oer-curriculum-time-element-row:last');
                ClonedDiv.find('input[type=text]').val('');
                ClonedDiv.find(':selected').removeAttr('selected');
                $('.remove-time-element').removeClass('disabled').prop('disabled', false);
            });
        },

        // Remove time elements
        removeTimeElements: function () {
            $(document).on('click', '.remove-time-element', function () {
                $(this).closest('.oer-curriculum-time-element-row').remove();
                if($('.oer-curriculum-time-element-row').length == 1) {
                    $('.oer-curriculum-time-element-row .remove-time-element ').addClass('disabled').prop('disabled', true);
                }
            });
        },

        // Add More Related Instructional Objectives
        addMoreObjectives: function () {
            $(document).on('click', '.oer-curriculum-add-related-objective', function () {
                var ClonedDiv = $('.oer-curriculum-related-objective-row:first').clone();
                ClonedDiv.insertAfter('div.oer-curriculum-related-objective-row:last');
                ClonedDiv.find('input[type=text]').val('');
                $('.oer-curriculum-remove-related-objective').prop('disabled', false);
            });
        },

        // Remove time elements
        removeObjectives: function () {
            $(document).on('click', '.oer-curriculum-remove-related-objective', function () {
                $(this).closest('.oer-curriculum-related-objective-row').remove();
                if($('.oer-curriculum-related-objective-row').length == 1) {
                    $('.oer-curriculum-related-objective-row .oer-curriculum-remove-related-objective').prop('disabled', true);
                }
            });
        },

        // Add Activity in Lesson
        addActivityInLesson: function () {
            $(document).on('click', '.oer-curriculum-add-ac-item', function () {
                var total_form_box = parseInt($('.oer-curriculum-ac-item').length, 10);
                $.post(ajaxurl, {action:'oer_curriculum_add_more_activity_callback', row_id: total_form_box}).done(function (response) {
                    if($('div.oer-curriculum-ac-item').length) {
                        $(response).insertAfter('div.oer-curriculum-ac-item:last');
                    } else {
                       $('.oer-curriculum-ac-inner-panel').html(response);
                    }

                    tinymce.execCommand( 'mceRemoveEditor', false, 'oer-curriculum-activity-detail-' + total_form_box );
                    tinymce.execCommand( 'mceAddEditor', false, 'oer-curriculum-activity-detail-' + total_form_box );

                    // Create dynamic elements on sidebar
                    var cloned = $('.sidebar-lesson-activities-title li:last').clone();
                    cloned.find('a').attr('href', '#oer-curriculum-ac-item-' + total_form_box);
                    cloned.find('a').text('Unnamed Activity');
                    cloned.insertAfter('.sidebar-lesson-activities-title li:last');
                    // Toggle reorder button
                    LessonPlan.toggleUpDownButton();
                });
            });
        },

        // Delete module
        deleteModule: function () {
            $(document).on('click', '.oer-curriculum-remove-module',function(e) {
                var moduleId = $(this).closest('.panel-default').attr('id');
                e.preventDefault();
                $('#oer-curriculum-confirm').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                .on('click', '#oer-curriculum-delete-confirm', function(e) {
                    $('#' + moduleId).remove();
                    $('a[href=#' + moduleId +']').parent('li').remove();
                    $('#oer-curriculum-confirm').modal('hide');
                });
            });
        },

        // Drag and drop elements
        lessonElementSortable: function () {

            $(document).on('click', '.reorder-up', function(){
                var $current = $(this).closest('.oer-curriculum-element-wrapper');
                var $previous = $current.prev('.oer-curriculum-element-wrapper');
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    LessonPlan.changeElementOrder();
                }
                return false;
            });

            $(document).on('click', '.reorder-down', function(){
                var $current = $(this).closest('.oer-curriculum-element-wrapper');
                var $next = $current.next('.oer-curriculum-element-wrapper');
                if($next.length !== 0){
                    $current.insertAfter($next);
                    LessonPlan.changeElementOrder();
                }
                return false;
            });

            // Author element reorder
            $(document).on('click', '.author-reorder-up', function(){
                var $current = $(this).closest('.oer-curriculum-author-element-wrapper');
                var $previous = $current.prev('.oer-curriculum-author-element-wrapper');
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    LessonPlan.toggleUpDownButton();
                }
                return false;
            });

            $(document).on('click', '.author-reorder-down', function(){
                var $current = $(this).closest('.oer-curriculum-author-element-wrapper');
                var $next = $current.next('.oer-curriculum-author-element-wrapper');
                if($next.length !== 0){
                    $current.insertAfter($next);
                    LessonPlan.toggleUpDownButton();
                }
                return false;
            });


            // For move inner module activity
            $(document).on('click', '.activity-reorder-up', function(){
                var $current = $(this).closest('.oer-curriculum-ac-item');
                var $previous = $current.prev('.oer-curriculum-ac-item');
                if($previous.length !== 0){
                    $current.insertBefore($previous);

                    $(".oer-curriculum-ac-item").each(function (index) {
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
                var $current = $(this).closest('.oer-curriculum-ac-item');
                var $next = $current.next('.oer-curriculum-ac-item');
                if($next.length !== 0){
                    $current.insertAfter($next);

                    $(".oer-curriculum-ac-item").each(function (index) {
                        var textAreaId = $(this).find('textarea').attr('id');

                        if (typeof textAreaId !== 'undefined') {
                            tinymce.execCommand( 'mceRemoveEditor', false, textAreaId );
                            tinymce.execCommand( 'mceAddEditor', false, textAreaId );
                        }
                    })
                }
                return false;
            });

            // Material element reorder
            $(document).on('click', '.material-reorder-up', function(){
                var $current = $(this).closest('.oer-curriculum-material-element-wrapper');
                var $previous = $current.prev('.oer-curriculum-material-element-wrapper');
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    LessonPlan.toggleUpDownButton();
                }
                return false;
            });

            $(document).on('click', '.material-reorder-down', function(){
                var $current = $(this).closest('.oer-curriculum-material-element-wrapper');
                var $next = $current.next('.oer-curriculum-material-element-wrapper');
                if($next.length !== 0){
                    $current.insertAfter($next);
                    LessonPlan.toggleUpDownButton();
                }
                return false;
            });
            
            // Primary Source element reorder
            $(document).on('click', '.resource-reorder-up', function(){
                var $current = $(this).closest('.oer-curriculum-primary-resource-element-wrapper');
                var $previous = $current.prev('.oer-curriculum-primary-resource-element-wrapper');
                var $x = $current.find('iframe').length;
                if($x > 0){
                  var ret = $current.find('iframe').attr('id').replace('_ifr','');
                  tinyMCE.execCommand('mceRemoveEditor', false, $('#'+ret).attr('id'));
                }
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    LessonPlan.toggleUpDownButton();
                    if($x > 0){
                      tinyMCE.execCommand('mceAddEditor', false, $('#'+ret).attr('id'));
                    }
                }
                return false;
            });

            $(document).on('click', '.resource-reorder-down', function(){
                var $current = $(this).closest('.oer-curriculum-primary-resource-element-wrapper');
                var $next = $current.next('.oer-curriculum-primary-resource-element-wrapper');
                var $x = $current.find('iframe').length;
                if($x > 0){
                  var ret = $current.find('iframe').attr('id').replace('_ifr','');
                  tinyMCE.execCommand('mceRemoveEditor', false, $('#'+ret).attr('id'));
                }
                if($next.length !== 0){
                    $current.insertAfter($next);
                    LessonPlan.toggleUpDownButton();
                    if($x > 0){
                      tinyMCE.execCommand('mceAddEditor', false, $('#'+ret).attr('id'));
                    }
                }
                return false;
            });
            
            // Section element reorder
            $(document).on('click', '.section-reorder-up', function(){
                var $current = $(this).closest('.oer-curriculum-section-element-wrapper');
                var $previous = $current.prev('.oer-curriculum-section-element-wrapper');
                var $x = $current.find('iframe').length;
                if($x > 0){
                  var ret = $current.find('iframe').attr('id').replace('_ifr','');
                  tinyMCE.execCommand('mceRemoveEditor', false, $('#'+ret).attr('id'));
                }
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    LessonPlan.toggleUpDownButton();
                    if($x > 0){
                      tinyMCE.execCommand('mceAddEditor', false, $('#'+ret).attr('id'));
                    }
                }
                return false;
            });

            $(document).on('click', '.section-reorder-down', function(){
                var $current = $(this).closest('.oer-curriculum-section-element-wrapper');
                var $next = $current.next('.oer-curriculum-section-element-wrapper');
                var $x = $current.find('iframe').length;
                if($x > 0){
                  var ret = $current.find('iframe').attr('id').replace('_ifr','');
                  tinyMCE.execCommand('mceRemoveEditor', false, $('#'+ret).attr('id'));
                }
                if($next.length !== 0){
                    $current.insertAfter($next);
                    LessonPlan.toggleUpDownButton();
                    if($x > 0){
                      tinyMCE.execCommand('mceAddEditor', false, $('#'+ret).attr('id'));
                    }
                }
                return false;
            });
        },

        // Change order value in hidden field and reinitialize the text editor
        changeElementOrder: function() {
            $("#oer-curriculum-sortable .oer-curriculum-element-wrapper").each(function (index) {
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
            $('.reorder-up').removeClass('hide');
            $('.reorder-down').removeClass('hide');
            $('.reorder-up').first().addClass('hide');
            $('.reorder-down').last().addClass('hide');

            // Toggle Activity button order
            $('.activity-reorder-up').removeClass('hide');
            $('.activity-reorder-down').removeClass('hide');
            $('.activity-reorder-up').first().addClass('hide');
            $('.activity-reorder-down').last().addClass('hide');

            // Toggle button from author module
            // Hide up button from first element
            // hide down button from last element
            $('.author-reorder-up').removeClass('hide');
            $('.author-reorder-down').removeClass('hide');
            $('.author-reorder-up').first().addClass('hide');
            $('.author-reorder-down').last().addClass('hide');

            // Show / Hide button on Materials module
            $('.material-reorder-up').removeClass('hide');
            $('.material-reorder-down').removeClass('hide');
            $('.material-reorder-up').first().addClass('hide');
            $('.material-reorder-down').last().addClass('hide');

            // Show / Hide button on Resource module
            $('.resource-reorder-up').removeClass('hide');
            $('.resource-reorder-down').removeClass('hide');
            $('.resource-reorder-up').first().addClass('hide');
            $('.resource-reorder-down').last().addClass('hide');
            
            // Show / Hide button on Additional Section module
            $('.section-reorder-up').removeClass('hide');
            $('.section-reorder-down').removeClass('hide');
            $('.section-reorder-up').first().addClass('hide');
            $('.section-reorder-down').last().addClass('hide');
        },

        // Create dynamic module
        createDynamicModule: function () {

            // Open modal when click one add module button
            $(document).on('click', '#oer-curriculum-create-dynamic-module', function (e) {
                e.preventDefault();
                $('#oer-curriculum-dynamic-module-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            });

            $(document).on('click','#oer-curriculum-create-module-btn', function () {
                var total_form_box = parseInt($('.oer-curriculum-element-wrapper').length, 10);
                var module_type = $('#module-type').val();

                $.post(ajaxurl, {action:'oer_curriculum_create_module_callback', module_type: module_type, row_id: total_form_box}).done(function (response) {
                    $(response).insertAfter('div.oer-curriculum-element-wrapper:last');

                    if (module_type == 'editor') {
                        tinymce.execCommand( 'mceRemoveEditor', false, 'oer-curriculum-custom-editor-' + total_form_box );
                        tinymce.execCommand( 'mceAddEditor', false, 'oer-curriculum-custom-editor-' + total_form_box );
                    }

                    $('#oer-curriculum-dynamic-module-modal').modal('hide');
                    // Toggle reorder button
                    LessonPlan.toggleUpDownButton();
                });
            });
        },

        // Dismiss the plugin installation message
        dismissInstallNotice: function () {
            $(document).on('click', '#oer-curriculum-dismissible', function () {
                $.post(ajaxurl, {action:'oer_curriculum_dismiss_notice_callback'}).done(function (response) {

                });
            });
        },
        // Add more author
        addMoreAuthor: function () {
            $(document).on('click', '#oer-curriculum-add-more-author', function () {
                var ClonedDiv = $('.oer-curriculum-author-element-wrapper:last').clone();
                ClonedDiv.insertAfter('div.oer-curriculum-author-element-wrapper:last');
                ClonedDiv.find('input[type=text]').val('');
                ClonedDiv.find('img.oer-curriculum-oer-person-placeholder').attr('src',lpScript.image_placeholder_url);
                $('.oer-curriculum-remove-author').removeAttr('disabled');
                LessonPlan.toggleUpDownButton();
            });
        },

        // Delete author
        deleteAuthor: function () {
            $(document).on('click', '.oer-curriculum-remove-author',function(e) {
                console.log("Clocked");
                var author = $(this).closest('.panel-default');
                var elementId = author.attr('id');
                e.preventDefault();
                $('#oer-curriculum-delete-author').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                    .on('click', '#oer-curriculum-author-delete-confirm', function(e) {
                        author.remove();
                        $('a[href="#' + elementId +'"]').parent('li').remove();
                        $('#oer-curriculum-delete-author').modal('hide');

                        // Disable delete button for author
                        if($('.oer-curriculum-author-element-wrapper').length === 1) {
                            $('.oer-curriculum-remove-author').attr('disabled', 'disabled');
                        }
                    });
            });
        },
        
        // Upload author image
        lpUploadAuthorImage: function () {
            $(document).on('click', '.oer-curriculum-oer-person-placeholder', function (e) {
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
            jQuery(document).on('click', '#oer-curriculum-select-standard', function (e) {
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
                        //var standardTitle = jQuery(this).next().next('div.oer-curriculum-notation-description').text();
                        var standardTitle = jQuery(this).next('.oer_stndrd_desc').text();

                        selectedHtml += '<span class="selected-standard-pill">';
                        selectedHtml += standardTitle;
                        selectedHtml += '<a href="javascript:void(0)" class="remove-ss-pill" data-id="'+standardId+'"><i class="fa fa-times"></i>';
                        selectedHtml += '</a></span>';
                    });
                    jQuery('#selected-standard-wrapper').html(selectedHtml);
                    var selectedStandardsIds = selectedStandards.join();
                    jQuery("input[name='oer_curriculum_standards']").val(selectedStandardsIds);
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
                var standardsIds =  jQuery("input[name='oer_curriculum_standards']").val();
                var standardsArr = standardsIds.split(",");
                standardsArr = jQuery.grep(standardsArr, function(value) {
                    return value != pillId;
                });

                standardsIds = standardsArr.join();
                jQuery("input[name='oer_curriculum_standards']").val(standardsIds);
                // Unchecked the checkbox from popup
                jQuery('#lpOerStandardModal input[value='+pillId+']').attr('checked', false);
            });
        },
        
        // Add materials to the module
        lpAddMaterials: function () {
            $(document).on('click', '.oer-curriculum-add-materials', function (e) {
                e.preventDefault();
                var moduleName = $(this).attr('data-name');
                var materialsContainer = $(this).prev('.oer-curriculum-materials-container');

                // Prepare input field name for the filed
                // Called this code on main materials module file selection
                // And When select file form the custom material module
                if (typeof moduleName !== 'undefined') {
                    var oer_curriculum_oer_materials_input = moduleName;
                } else {
                    var oer_curriculum_oer_materials_input = 'oer_curriculum_oer_materials';
                }

                var materialFrame;
                if (materialFrame) {
                    materialFrame.open();
                    return;
                }
                materialFrame = wp.media({
                    title: 'Select Materials',
                    button: { text: 'Use Materials' },
                    //library: { type: [ 'image' ] },
                    multiple:'add'
                });

                // Get selected files
                materialFrame.on('select', function(){
                    var materialHTML = "";
                    var selected = materialFrame.state().get('selection');
                    selected.map(function (attachment) {
                        attachment = attachment.toJSON();
                        // Get the file type and pic the icon according to that
                        var title = "";
                        var icon = "";
                        if ($.inArray(attachment.subtype, ['zip', 'x-7z-compressed']) !== -1) {
                            title = 'Archived';
                            icon = '<i class="fa fa-file-archive"></i>';
                        } else if($.inArray(attachment.subtype, ['plain']) !== -1) {
                            title = 'Plain text';
                            icon = '<i class="fa fa-file-alt"></i>';
                        } else if($.inArray(attachment.subtype, ['pdf']) !== -1) {
                            title = 'PDF';
                            icon = '<i class="fa fa-file-pdf"></i>';
                        } else if($.inArray(attachment.type, ['image']) !== -1) {
                            title = 'Image';
                            icon = '<i class="fa fa-file-image"></i>';
                        } else if($.inArray(attachment.subtype, ['msword', 'vnd.openxmlformats-officedocument.wordprocessingml.document']) !== -1) {
                            title = 'Microsoft Document';
                            icon = '<i class="fa fa-file-word"></i>';
                        } else if($.inArray(attachment.subtype,['vnd.ms-excel'])) {
                            title = 'Microsoft Excel';
                            icon = '<i class="fa fa-file-excel"></i>';
                        } else if($.inArray(attachment.subtype,['vnd.ms-powerpoint'])) {
                            title = 'Microsoft Powerpoint';
                            icon = '<i class="fa fa-file-powerpoint"></i>';
                        }

                        materialHTML += '<div class="panel panel-default oer-curriculum-material-element-wrapper">' +
                                            '<div class="panel-heading">' +
                                                '<h3 class="panel-title oer-curriculum-module-title">' +
                                                    '<span class="oer-curriculum-sortable-handle">' +
                                                        '<i class="fa fa-arrow-down material-reorder-down" aria-hidden="true"></i>' +
                                                        '<i class="fa fa-arrow-up material-reorder-up" aria-hidden="true"></i>' +
                                                    '</span>' +
                                                    '<span class="btn btn-danger btn-sm oer-curriculum-remove-material" title="Delete"><i class="fa fa-trash"></i></span>' +
                                                '</h3>' +
                                            '</div>' +
                                            '<div class="panel-body">' +
                                                '<div class="form-group">' +
                                                    '<div class="input-group">' +
                                                        '<input type="text" class="form-control" name="' + oer_curriculum_oer_materials_input + '[url][]" placeholder="URL" value="' + attachment.url + '">' +
                                                        '<div class="input-group-addon oer-curriculum-material-icon" title="'+ title +'">' + icon + '</div>' +
                                                    '</div>' +
                                                '</div>' +
                                                '<div class="form-group">' +
                                                   '<input type="text" class="form-control" name="' + oer_curriculum_oer_materials_input + '[title][]" placeholder="Title" value="' + attachment.name + '">' +
                                                '</div>' +
                                                '<div class="form-group">' +
                                                    '<textarea class="form-control" name="' + oer_curriculum_oer_materials_input + '[description][]" rows="6" placeholder="Description">' + attachment.description + '</textarea>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>';

                    });
                    if (materialsContainer.has('.oer-curriculum-material-element-wrapper').length) {
                        $(materialHTML).appendTo(materialsContainer);
                    } else {
                        materialsContainer.html(materialHTML);
                    }

                    /*if($('.oer-curriculum-material-element-wrapper').length) {
                        $(materialHTML).insertAfter('.oer-curriculum-material-element-wrapper:last');
                    } else {
                        $('#oer-curriculum-materials-container').html(materialHTML);
                    }*/
                    LessonPlan.toggleUpDownButton();
                });

                materialFrame.open();
            })
        },

        // Prepare the material icon based on the type of selected file
        lpPrepareMaterialIcon: function(attachment) {
            // Get the file type and pic the icon according to that
            var title = "";
            var icon = "";
            if ($.inArray(attachment.subtype, ['zip', 'x-7z-compressed']) !== -1) {
                title = 'Archived';
                icon = '<i class="fa fa-file-archive"></i>';
            } else if($.inArray(attachment.subtype, ['plain']) !== -1) {
                title = 'Plain text';
                icon = '<i class="fa fa-file-alt"></i>';
            } else if($.inArray(attachment.subtype, ['pdf']) !== -1) {
                title = 'PDF';
                icon = '<i class="fa fa-file-pdf"></i>';
            } else if($.inArray(attachment.type, ['image']) !== -1) {
                title = 'Image';
                icon = '<i class="fa fa-file-image"></i>';
            } else if($.inArray(attachment.subtype, ['msword', 'vnd.openxmlformats-officedocument.wordprocessingml.document']) !== -1) {
                title = 'Microsoft Document';
                icon = '<i class="fa fa-file-word"></i>';
            } else if($.inArray(attachment.subtype,['vnd.ms-excel'])) {
                title = 'Microsoft Excel';
                icon = '<i class="fa fa-file-excel"></i>';
            } else if($.inArray(attachment.subtype,['vnd.ms-powerpoint'])) {
                title = 'Microsoft Powerpoint';
                icon = '<i class="fa fa-file-powerpoint"></i>';
            }

            return {title, icon};
        },

        // Update material
        lpUpdateMaterial: function() {
            $(document).on('click', '.oer-curriculum-material-icon', function (e) {
                e.preventDefault();
                var dis = $(this);
                var elementWrapper = dis.closest('.oer-curriculum-material-element-wrapper');
                var wraperElementId = dis.closest('.oer-curriculum-element-wrapper').attr('id');
                    wraperElementId = wraperElementId.split("-");
                var elementNumber = wraperElementId[wraperElementId.length-1];

                var inputUrl = "input[name='oer_curriculum_oer_materials[url][]']";
                var inputTitle = "input[name='oer_curriculum_oer_materials[title][]']";
                var inputDescription = "textarea[name='oer_curriculum_oer_materials[description][]']";

                if ($.isNumeric(elementNumber)) {
                    inputUrl = "input[name='oer_curriculum_oer_materials_list_"+elementNumber+"[url][]']";
                    inputTitle = "input[name='oer_curriculum_oer_materials_list_"+elementNumber+"[title][]']";
                    inputDescription = "textarea[name='oer_curriculum_oer_materials_list_"+elementNumber+"[description][]']";
                }

                var materialFrame;
                if (materialFrame) {
                    materialFrame.open();
                    return;
                }
                materialFrame = wp.media({
                    title: 'Select Material',
                    button: { text: 'Use Material' },
                    multiple: false
                });

                materialFrame.on('select', function(){
                    var attachment = materialFrame.state().get('selection').first().toJSON();
                    var response = LessonPlan.lpPrepareMaterialIcon(attachment);

                    dis.html(response.icon);
                    $(elementWrapper).find(inputUrl).val(attachment.url);
                    $(elementWrapper).find(inputTitle).val(attachment.name);
                    $(elementWrapper).find(inputDescription).val(attachment.description);
                });

                materialFrame.open();
            });
        },

        // Delete Material module
        lpDeleteMaterials: function () {
            $(document).on('click', '.oer-curriculum-remove-material',function(e) {
                var material = $(this).closest('.panel-default');
                var elementId = material.attr('id');
                e.preventDefault();
                $('#oer-curriculum-delete-confirm-popup').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                    .on('click', '#oer-curriculum-delete-confirm-popup-btn', function(e) {
                        material.remove();
                        $('a[href=#' + elementId +']').parent('li').remove();
                        $('#oer-curriculum-delete-confirm-popup').modal('hide');

                        // Disable delete button for author
                        if($('.oer-curriculum-material-element-wrapper').length === 1) {
                            $('.oer-curriculum-remove-material').attr('disabled', 'disabled');
                        }
                    });
            });
        },

        // Search standards on modal
        lpSearchStandards: function () {

            //setup before functions
            var typingTimer;                //timer identifier
            var doneTypingInterval = 1000;  //time in ms, 5 second for example
            var $input = $('#oerLpSearchStandardInput');

            //on keyup, start the countdown
            $input.on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(LessonPlan.lpProcessingStandardSearch, doneTypingInterval);
            });

            //on keydown, clear the countdown
            $input.on('keydown', function () {
                clearTimeout(typingTimer);
            });

            $input.on('keypress', function (e) {
                if (e.which == 13) {
                    if ($(this).val() !== '') {
                        LessonPlan.lpProcessingStandardSearch();
                    } else {
                        $('.oer-curriculum-standard-search-result').addClass('hide');
                        $('.oer-curriculum-standard-default-result').removeClass('hide');
                    }
                }
            });
        },
        
        // Process the standard search
        lpProcessingStandardSearch: function () {
            var $input = $('#oerLpSearchStandardInput');

            if($input.val() == '') {
                $('.oer-curriculum-standard-search-result').addClass('hide');
                $('.oer-curriculum-standard-default-result').removeClass('hide');
                return false;
            }

            var data = {
                action: 'oer_curriculum_searched_standards_callback',
                post_id: $input.attr('data-post'),
                keyword: $input.val()
            };
            $.post(
                ajaxurl,
                data
            ).done(function (response) {
                var $resultContainer = $(".oer-curriculum-standard-search-result");
                $resultContainer.html(response);
                $resultContainer.removeClass('hide');
                $('.oer-curriculum-standard-default-result').addClass('hide');
            });
        },

        // Select lesson document for download copy
        lpDownloadCopyLesson: function() {
            $(document).on('click', 'input[name="oer_curriculum_download_copy_document"], .oer-curriculum-download-copy-icon', function (e) {
                e.preventDefault();
                var dis = $(this);

                var materialFrame;
                if (materialFrame) {
                    materialFrame.open();
                    return;
                }
                materialFrame = wp.media({
                    title: 'Select Material',
                    library: { type: [ 'application/msword', 'application/pdf' ] },
                    button: { text: 'Use Material' },
                    multiple: false
                });

                materialFrame.on('select', function(){
                    var attachment = materialFrame.state().get('selection').first().toJSON();
                    var response = LessonPlan.lpPrepareMaterialIcon(attachment);

                    var icon = response.icon;
                        icon = icon.replace('fa-2x', '');

                    $('.oer-curriculum-download-copy-icon').html(icon);
                    
                    $('input[name="oer_curriculum_download_copy_document"]').val(attachment.url);
                    if (dis.parent().find('.oer-curriculum-selected-section')) {
                        dis.parent().find('.oer-curriculum-selected-section a').attr('href',attachment.url);
                        dis.parent().find('.oer-curriculum-selected-section a').text(attachment.url);
                        dis.parent().find('.oer-curriculum-selected-section').removeClass('oer-curriculum-hidden');
                        dis.parent().find('.oer-curriculum-select-label').addClass('oer-curriculum-hidden');
                        dis.parent().find('.oer-curriculum-download-copy-icon').addClass('oer-curriculum-hidden');
                    }
                });

                materialFrame.open();
            });
        },

        // Add More Primary resources
        addMorePrimaryResource: function () {
            $(document).on('click', '.oer-curriculum-add-more-resource', function () {
                var resource_field_type = $(this).attr('typ');
                var total_form_box = parseInt($('.oer-curriculum-primary-resource-element-panel').find('.oer-curriculum-primary-resource-element-wrapper').length, 10);
                total_form_box += 1;
                $.post(ajaxurl, {action:'oer_curriculum_add_more_pr_callback', row_id: total_form_box, type: resource_field_type}).done(function (response) {
                    if($('div.oer-curriculum-primary-resource-element-wrapper').length) {
                        $(response).insertAfter('div.oer-curriculum-primary-resource-element-wrapper:last').tinymce_textareas();
                    } else {
                        $('.oer-curriculum-primary-resource-element-panel').html(response).tinymce_textareas();
                    }
                    
                    if (typeof( tinymce ) == "object" && typeof( tinymce.execCommand ) == "function" ) {
                        tinymce.execCommand( 'mceRemoveEditor', false, 'oer-curriculum-resource-teacher-' + total_form_box );
                        tinymce.execCommand( 'mceAddEditor', false, 'oer-curriculum-resource-teacher-' + total_form_box );
                        quicktags({ id: 'oer-curriculum-resource-teacher-' + total_form_box });
                        
                        tinymce.execCommand( 'mceRemoveEditor', false, 'oer-curriculum-resource-student-' + total_form_box );
                        tinymce.execCommand( 'mceAddEditor', false, 'oer-curriculum-resource-student-' + total_form_box );
                        quicktags({ id: 'oer-curriculum-resource-student-' + total_form_box });
                        
                        //$('#oer-curriculum-resource-student-'+total_form_box+'-html').trigger('click');
                        //$('#oer-curriculum-resource-student-'+total_form_box+'-tmce').trigger('click').focus();
                        
                          /*
                          wp.editor.initialize(
                          'oer-curriculum-resource-student-' + total_form_box ,
                          {
                            quicktags: true,
                            mediaButtons: true,
                            tinymce: {
                            plugins : 'lists link fullscreen',
                            toolbar1: 'bold italic underline blockquote strikethrough numlist bullist alignleft aligncenter alignright undo redo link fullscreen',
                            //toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv'
                            block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3'
                          }, 
                            }
                          );
                          quicktags({ id: 'oer-curriculum-resource-student-' + total_form_box });                
                          $('#oer-curriculum-resource-student-'+total_form_box+'-html').trigger('click');
                          $('#oer-curriculum-resource-student-'+total_form_box+'-tmce').trigger('click').focus();
                          */
                    }

                    // Toggle reorder button
                   LessonPlan.toggleUpDownButton();
                });
            });
            /*$.fn.tinymce_textareas = function(){
                tinyMCE.init({
                  mode: 'textareas',
                });
            }*/
              $.fn.tinymce_textareas = function(){
                tinyMCE.init({
                    //plugins: 'print preview fullpage powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable',
                    plugins: 'lists link fullscreen',
                    skin: 'lightgray',
                    mode: 'exact',
                    menubar: false,
                    //toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
                    toolbar: 'bold italic underline blockquote strikethrough numlist bullist alignleft aligncenter alignright undo redo link fullscreen'
                });
            }
        },

        // Delete source
        deletePrimarySource: function () {
            $(document).on('click', '.oer-curriculum-remove-source',function(e) {
                var source = $(this).closest('.panel-default');
                var elementId = source.attr('id');
                e.preventDefault();
                $('#oer-curriculum-delete-source').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                    .on('click', '#oer-curriculum-source-delete-confirm', function(e) {
                        source.remove();
                        $('a[href=#' + elementId +']').parent('li').remove();
                        $('#oer-curriculum-delete-source').modal('hide');

                        // Disable delete button for author
                        if($('.oer-curriculum-primary-source-element-wrapper').length === 1) {
                            $('.oer-curriculum-remove-source').attr('disabled', 'disabled');
                        }
                    });
            });
        },
        
        requireModuleTitle: function(){
            var validated = false;
            $(document).on('click', '#publishing-action #publish',function(e) {
                if (validated==true) {
                    validated = false;
                    return;
                }
                var custom_editor = $(".oer-curriculum-introduction-group[id^=oer-curriculum-custom-editor-group");
                var custom_visible = $(".oer-curriculum-introduction-group[id^=oer-curriculum-custom-editor-group").is(":visible");
                if (custom_editor.length>0 && custom_visible) {
                    e.preventDefault();
                    $.each(custom_editor, function(index, value){
                        var id = $(this).attr('id');
                        if ($(this).is(":visible")){
                            title = $(this).find("input[name$='[title]']");
                            if (title.val()!=="") {
                                validated = true;
                            } else {
                                $(document).scrollTop = title.scrollTop;
                                title.after("<span class='error' style='color:#ff0000;'>Please enter a title</span>")
                                title.focus();
                                validated = false;
                                return false;
                            }
                        }
                    });
                    if (validated==true) {
                        $(this).trigger("click");
                    }
                }
            });
        },
        // Select lesson document for download copy
        lpRemoveCopyLesson: function() {
            $(document).on('click', '.oer-curriculum-remove-download-copy', function (e) {
                e.preventDefault();
                var dis = $('input[name="oer_curriculum_download_copy_document"]');

                $('.oer-curriculum-selected-section').addClass('oer-curriculum-hidden');
                $('input[name="oer_curriculum_download_copy_document"]').val("");
                $('.oer-curriculum-select-label').removeClass('oer-curriculum-hidden');
                $('.oer-curriculum-download-copy-icon').removeClass('oer-curriculum-hidden').html('<i class="fa fa-upload"></i>');
                
                /*var materialFrame;
                if (materialFrame) {
                    materialFrame.open();
                    return;
                }
                materialFrame = wp.media({
                    title: 'Select Material',
                    library: { type: [ 'application/msword', 'application/pdf' ] },
                    button: { text: 'Use Material' },
                    multiple: false
                });

                materialFrame.on('select', function(){
                    var attachment = materialFrame.state().get('selection').first().toJSON();
                    var response = LessonPlan.lpPrepareMaterialIcon(attachment);

                    var icon = response.icon;
                        icon = icon.replace('fa-2x', '');

                    $('.oer-curriculum-download-copy-icon').html(icon);
                    
                    $('input[name="oer_curriculum_download_copy_document"]').val(attachment.url);
                    if (dis.parent().find('.oer-curriculum-selected-section')) {
                        dis.parent().find('.oer-curriculum-selected-section a').attr('href',attachment.url);
                        dis.parent().find('.oer-curriculum-selected-section a').text(attachment.url);
                        dis.parent().find('.oer-curriculum-selected-section').removeClass('oer-curriculum-hidden');
                        dis.parent().find('.oer-curriculum-select-label').addClass('oer-curriculum-hidden');
                        dis.parent().find('.oer-curriculum-download-copy-icon').addClass('oer-curriculum-hidden');
                    }
                });

                materialFrame.open();*/
            });
        },
        
        // Select lesson document for download copy
        lpPrimarySourceSensitiveMaterial: function() {
            $(document).on('change', 'input[name="oer_curriculum_primary_resources[sensitive_material][]"]', function (e) {
                e.preventDefault();
                var dis = $(this);
                var val = "no";
                if (dis.is(":checked")) {
                    val = "yes"
                } 
                dis.parent().find('input[name="oer_curriculum_primary_resources[sensitive_material_value][]"]').val(val);
            });
        },
        
        // Select Type
        lpOtherCurriculumType: function() {
            $(document).on('change', 'select[name="oer_curriculum_type"]', function (e) {
                var dis = $(this);
                if (dis.val()=="Other")
                    $('.other-type-group').removeClass('hidden').show();
                else
                    $('.other-type-group').hide();
            });
        },

        // Add Required Material
        lpAddRequiredMaterial: function(){
            $(document).on("click", "#addMatlBtn", function(e){
                e.preventDefault();
                var dis = $(this).closest('.button-row.form-group');
                
                var total_text_features = parseInt($('#oer-curriculum-required-materials .oer-curriculum-section-element-wrapper').length, 10);
                var id = total_text_features + 1;
                const editor_prefix = 'oer-curriculum-required-material-section-';
                $.post(ajaxurl,
                       {
                        action:'oer_curriculum_add_text_feature_callback',
                        row_id: total_text_features,
                        editor_id: editor_prefix,
                        required_material: true
                       }).done(function (response) {
                    if($('#oer-curriculum-required-materials div.oer-curriculum-section-element-wrapper').length) {
                        $(response).insertAfter('#oer-curriculum-required-materials div.oer-curriculum-section-element-wrapper:last').tinymce_textareas();
                    } else {
                        $('.oer-curriculum-section-element-panel').html(response).tinymce_textareas();
                    }
                    tinymce.execCommand( 'mceRemoveEditor', false, editor_prefix + id );
                    tinymce.execCommand( 'mceAddEditor', false, editor_prefix + id );
                    quicktags({ id: editor_prefix + id });
                    
                    LessonPlan.toggleUpDownButton();
                });
            });
        },
        
        // Add Text Feature
        lpAddTextFeature: function(){
            $(document).on("click", "#addTxtBtn", function(e){
                e.preventDefault();
                var dis = $(this).closest('.button-row.form-group');
                
                var total_text_features = parseInt($('#oer-curriculum-additional-sections .oer-curriculum-section-element-wrapper').length, 10);
                var id = total_text_features + 1;
                console.log(id);
                $.post(ajaxurl,
                       {
                        action:'oer_curriculum_add_text_feature_callback',
                        row_id: total_text_features
                       }).done(function (response) {
                    dis.before(response);
                    /*if($('#oer-curriculum-additional-sections div.oer-curriculum-section-element-wrapper').length) {
                        $(response).insertAfter('#oer-curriculum-additional-sections div.oer-curriculum-section-element-wrapper:last').tinymce_textareas();
                    } else {
                        $('.oer-curriculum-section-element-panel').html(response).tinymce_textareas();
                    }
                    LessonPlan.initializeEditor('oer-curriculum-additional-section-' + id);*/
                    tinymce.execCommand( 'mceRemoveEditor', false, 'oer-curriculum-additional-section-' + id );
                    tinymce.execCommand( 'mceAddEditor', false, 'oer-curriculum-additional-section-' + id );
                    quicktags({ id: 'oer-curriculum-additional-section-' + id });
                    
                    LessonPlan.toggleUpDownButton();
                });
            });
        },
        
        // Add Saving of TinyMCE data
        lpTinyMCESave: function(){
            if (typeof wp.data !== "undefined") {
                wp.data.subscribe(function(){
                    var isSavingPost = wp.data.select('core/editor').isSavingPost();
                    var isAutosavingPost = wp.data.select('core/editor').isAutosavingPost();
                    
                    if (isSavingPost && !isAutosavingPost) {
                        window.tinyMCE.triggerSave();
                    }
                });
            }
        },
        
        // Delete Section
        deleteSection: function () {
            $(document).on('click', '.oer-curriculum-remove-section',function(e) {
                var section = $(this).closest('.panel-default');
                var elementId = section.attr('id');
                e.preventDefault();
                $('#oer-curriculum-delete-confirm-popup').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                    .on('click', '#oer-curriculum-delete-confirm-popup-btn', function(e) {
                        section.remove();
                        $('a[href="#' + elementId +'"]').parent('li').remove();
                        $('#oer-curriculum-delete-confirm-popup').modal('hide');

                        // Disable delete button for section
                        if($('.oer-curriculum-section-element-wrapper').length === 1) {
                            $('.oer-curriculum-remove-section').attr('disabled', 'disabled');
                        }
                    });
            });
        },
        
        // Initialize WP Editor
        initializeEditor: function(id) {
            wp.editor.remove(id);
            wp.editor.initialize(
                id,
                {
                    tinymce: {
                        wpautop: true,
                        plugins: 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
                        toolbar1: 'formatselect bold italic | bullist numlist | blockquote | alignleft aligncenter alignright | link unlink | wp_more | spellchecker'
                    },
                    quicktags: true,
                    mediaButtons: true
                }
            );
        },
        
        /** Add Featured Image Selection on Add Textbox/Resource **/
        addFeaturedImageOnResourceTextBox: function(){
            var frame, metabox, btn, input, imageholder;
            $(document).on('click', 'button.oer_curriculum_primary_resources_thumbnail_button',function(e) {
                metabox = $(this).closest(".oer-curriculum-primary-resource-element-wrapper");
                btn = $(this);
                input = metabox.find('.oer_primary_resourceurl');
                imageholder = metabox.find('.oer_primary_resource_thumbnail_holder');
                
                e.preventDefault();
        
                if (frame) {
                    frame.open();
                    return;
                }
        
                frame = wp.media({
                    title: 'Select or upload thumbnail image',
                    button: {
                        text: "Use this image"
                    },
                    multiple:false
                });
        
                frame.on("select", function(){
                    imageholder.find(".resource-thumbnail,.oer-curriculum-remove-source-featured-image").remove();
                    var attachment = frame.state().get("selection").first().toJSON();
                    
                    input.val(attachment.url);
                    imageholder.append('<img src="' + attachment.url + '" class="resource-thumbnail" width="200"><span class="btn btn-danger btn-sm oer-curriculum-remove-source-featured-image" title="Remove Thumbnail"><i class="fas fa-minus-circle"></i></span>');
                    btn.text("Change Thumbnail");
                });
                
                frame.open();
            });
        },
        
        /** Remove Featured Image on Add Textbox/Resource Selection **/
        removeFeaturedImageInResourceSelection: function(){
            var frame, metabox, btn, input, imageholder;
            $(document).on('click', '.oer-curriculum-remove-source-featured-image',function(e) {
                metabox = $(this).closest(".oer-curriculum-primary-resource-element-wrapper");
                btn = $(this);
                input = metabox.find('.oer_primary_resourceurl');
                imageholder = metabox.find('.oer_primary_resource_thumbnail_holder');
                
                e.preventDefault();
        
                input.val("");
                imageholder.find("img").remove();
                btn.remove();
                metabox.find('button.oer_curriculum_primary_resources_thumbnail_button').text("Set Thumbnail");
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
    LessonPlan.addMoreAuthor();
    LessonPlan.deleteAuthor();
    LessonPlan.lpUploadAuthorImage();
    LessonPlan.lpSelectStandards();
    LessonPlan.lpRemoveStandardsFromList();
    LessonPlan.lpAddMaterials();
    LessonPlan.lpUpdateMaterial();
    LessonPlan.lpDeleteMaterials();
    LessonPlan.lpSearchStandards();
    LessonPlan.lpDownloadCopyLesson();
    LessonPlan.addMorePrimaryResource();
    LessonPlan.deletePrimarySource();
    LessonPlan.requireModuleTitle();
    LessonPlan.lpRemoveCopyLesson();
    LessonPlan.lpPrimarySourceSensitiveMaterial();
    LessonPlan.lpOtherCurriculumType();
    LessonPlan.lpAddRequiredMaterial();
    LessonPlan.lpAddTextFeature();
    LessonPlan.deleteSection();
    LessonPlan.lpTinyMCESave();
    LessonPlan.addFeaturedImageOnResourceTextBox();
    LessonPlan.removeFeaturedImageInResourceSelection();
});

//Process Initial Setup
function lpInitialSettings(form) {
    setTimeout(function() {
        var Top = document.documentElement.scrollTop || document.body.scrollTop;
        jQuery('.loader .loader-img').css({'padding-top':Top + 'px'});
        jQuery('.loader').show();
    } ,1000);
    return true;
}