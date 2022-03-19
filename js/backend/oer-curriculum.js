/**
 * All backend custom JavaScript code are here
 *
 */
jQuery(document).ready(function ($) {

    var OerCurriculum = {
        updateActivityTitle: function () {
            $(document).on('keyup', '.oercurr-ac-item input[type=text]', function () {
                var InputValue = $(this).val();
                var ContainerId = $(this).closest('.oercurr-ac-item').attr('id');
                $('a[href=#' + ContainerId +']').text(InputValue);
            });
        },

        // Add more time elements
        addMoreTimeElements: function () {
            $(document).on('click', '.oercurr-add-time-element', function () {
                var ClonedDiv = $('.oercurr-time-element-row:first').clone();
                ClonedDiv.insertAfter('div.oercurr-time-element-row:last');
                ClonedDiv.find('input[type=text]').val('');
                ClonedDiv.find(':selected').removeAttr('selected');
                $('.remove-time-element').removeClass('disabled').prop('disabled', false);
            });
        },

        // Remove time elements
        removeTimeElements: function () {
            $(document).on('click', '.remove-time-element', function () {
                $(this).closest('.oercurr-time-element-row').remove();
                if($('.oercurr-time-element-row').length == 1) {
                    $('.oercurr-time-element-row .remove-time-element ').addClass('disabled').prop('disabled', true);
                }
            });
        },

        // Add More Related Instructional Objectives
        addMoreObjectives: function () {
            $(document).on('click', '.oercurr-add-related-objective', function () {
                var ClonedDiv = $('.oercurr-related-objective-row:first').clone();
                ClonedDiv.insertAfter('div.oercurr-related-objective-row:last');
                ClonedDiv.find('input[type=text]').val('');
                $('.oercurr-remove-related-objective').prop('disabled', false);
            });
        },

        // Remove time elements
        removeObjectives: function () {
            $(document).on('click', '.oercurr-remove-related-objective', function () {
                $(this).closest('.oercurr-related-objective-row').remove();
                if($('.oercurr-related-objective-row').length == 1) {
                    $('.oercurr-related-objective-row .oercurr-remove-related-objective').prop('disabled', true);
                }
            });
        },

        // Add Activity in Lesson
        addActivityInLesson: function () {
            $(document).on('click', '.oercurr-add-ac-item', function () {
                var total_form_box = parseInt($('.oercurr-ac-item').length, 10);
                $.post(ajaxurl, {action:'oercurr_add_more_activity_callback', row_id: total_form_box}).done(function (response) {
                    if($('div.oercurr-ac-item').length) {
                        $(response).insertAfter('div.oercurr-ac-item:last');
                    } else {
                       $('.oercurr-ac-inner-panel').html(response);
                    }

                    tinymce.execCommand( 'mceRemoveEditor', false, 'oercurr-activity-detail-' + total_form_box );
                    tinymce.execCommand( 'mceAddEditor', false, 'oercurr-activity-detail-' + total_form_box );

                    // Create dynamic elements on sidebar
                    var cloned = $('.sidebar-lesson-activities-title li:last').clone();
                    cloned.find('a').attr('href', '#oercurr-ac-item-' + total_form_box);
                    cloned.find('a').text('Unnamed Activity');
                    cloned.insertAfter('.sidebar-lesson-activities-title li:last');
                    // Toggle reorder button
                    OerCurriculum.toggleUpDownButton();
                });
            });
        },

        // Delete module
        deleteModule: function () {
            $(document).on('click', '.oercurr-remove-module',function(e) {
                var moduleId = $(this).closest('.card-default').attr('id');
                e.preventDefault();
                $('#oercurr-confirm').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                .on('click', '#oercurr-delete-confirm', function(e) {
                    $('#' + moduleId).remove();
                    $('a[href=#' + moduleId +']').parent('li').remove();
                    $('#oercurr-confirm').modal('hide');
                });
            });
        },

        // Drag and drop elements
        lessonElementSortable: function () {

            $(document).on('click', '.reorder-up', function(){
                var $current = $(this).closest('.oercurr-element-wrapper');
                var $previous = $current.prev('.oercurr-element-wrapper');
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    OerCurriculum.changeElementOrder();
                }
                return false;
            });

            $(document).on('click', '.reorder-down', function(){
                var $current = $(this).closest('.oercurr-element-wrapper');
                var $next = $current.next('.oercurr-element-wrapper');
                if($next.length !== 0){
                    $current.insertAfter($next);
                    OerCurriculum.changeElementOrder();
                }
                return false;
            });

            // Author element reorder
            $(document).on('click', '.author-reorder-up', function(){
                var $current = $(this).closest('.oercurr-author-element-wrapper');
                var $previous = $current.prev('.oercurr-author-element-wrapper');
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    OerCurriculum.toggleUpDownButton();
                }
                return false;
            });

            $(document).on('click', '.author-reorder-down', function(){
                var $current = $(this).closest('.oercurr-author-element-wrapper');
                var $next = $current.next('.oercurr-author-element-wrapper');
                if($next.length !== 0){
                    $current.insertAfter($next);
                    OerCurriculum.toggleUpDownButton();
                }
                return false;
            });


            // For move inner module activity
            $(document).on('click', '.activity-reorder-up', function(){
                var $current = $(this).closest('.oercurr-ac-item');
                var $previous = $current.prev('.oercurr-ac-item');
                if($previous.length !== 0){
                    $current.insertBefore($previous);

                    $(".oercurr-ac-item").each(function (index) {
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
                var $current = $(this).closest('.oercurr-ac-item');
                var $next = $current.next('.oercurr-ac-item');
                if($next.length !== 0){
                    $current.insertAfter($next);

                    $(".oercurr-ac-item").each(function (index) {
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
                var $current = $(this).closest('.oercurr-material-element-wrapper');
                var $previous = $current.prev('.oercurr-material-element-wrapper');
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    OerCurriculum.toggleUpDownButton();
                }
                return false;
            });

            $(document).on('click', '.material-reorder-down', function(){
                var $current = $(this).closest('.oercurr-material-element-wrapper');
                var $next = $current.next('.oercurr-material-element-wrapper');
                if($next.length !== 0){
                    $current.insertAfter($next);
                    OerCurriculum.toggleUpDownButton();
                }
                return false;
            });
            
            // Primary Source element reorder
            $(document).on('click', '.resource-reorder-up', function(){
                var $current = $(this).closest('.oercurr-primary-resource-element-wrapper');
                var $previous = $current.prev('.oercurr-primary-resource-element-wrapper');
                var $x = $current.find('iframe').length;
                if($x > 0){
                  var ret = $current.find('iframe').attr('id').replace('_ifr','');
                  tinyMCE.execCommand('mceRemoveEditor', false, $('#'+ret).attr('id'));
                }
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    OerCurriculum.toggleUpDownButton();
                    if($x > 0){
                      tinyMCE.execCommand('mceAddEditor', false, $('#'+ret).attr('id'));
                    }
                }
                return false;
            });

            $(document).on('click', '.resource-reorder-down', function(){
                var $current = $(this).closest('.oercurr-primary-resource-element-wrapper');
                var $next = $current.next('.oercurr-primary-resource-element-wrapper');
                var $x = $current.find('iframe').length;
                if($x > 0){
                  var ret = $current.find('iframe').attr('id').replace('_ifr','');
                  tinyMCE.execCommand('mceRemoveEditor', false, $('#'+ret).attr('id'));
                }
                if($next.length !== 0){
                    $current.insertAfter($next);
                    OerCurriculum.toggleUpDownButton();
                    if($x > 0){
                      tinyMCE.execCommand('mceAddEditor', false, $('#'+ret).attr('id'));
                    }
                }
                return false;
            });
            
            // Section element reorder
            $(document).on('click', '.section-reorder-up', function(){
                var $current = $(this).closest('.oercurr-section-element-wrapper');
                var $previous = $current.prev('.oercurr-section-element-wrapper');
                var $x = $current.find('iframe').length;
                if($x > 0){
                  var ret = $current.find('iframe').attr('id').replace('_ifr','');
                  tinyMCE.execCommand('mceRemoveEditor', false, $('#'+ret).attr('id'));
                }
                if($previous.length !== 0){
                    $current.insertBefore($previous);
                    OerCurriculum.toggleUpDownButton();
                    if($x > 0){
                      tinyMCE.execCommand('mceAddEditor', false, $('#'+ret).attr('id'));
                    }
                }
                return false;
            });

            $(document).on('click', '.section-reorder-down', function(){
                var $current = $(this).closest('.oercurr-section-element-wrapper');
                var $next = $current.next('.oercurr-section-element-wrapper');
                var $x = $current.find('iframe').length;
                if($x > 0){
                  var ret = $current.find('iframe').attr('id').replace('_ifr','');
                  tinyMCE.execCommand('mceRemoveEditor', false, $('#'+ret).attr('id'));
                }
                if($next.length !== 0){
                    $current.insertAfter($next);
                    OerCurriculum.toggleUpDownButton();
                    if($x > 0){
                      tinyMCE.execCommand('mceAddEditor', false, $('#'+ret).attr('id'));
                    }
                }
                return false;
            });
        },

        // Change order value in hidden field and reinitialize the text editor
        changeElementOrder: function() {
            $("#oercurr-sortable .oercurr-element-wrapper").each(function (index) {
                var count = index + 1;

                var position = $(this).find('.element-order').val();
                var newvalue = $(this).find('.element-order').val(count);
                // reassign all of the numbers once it's loaded.

                
                var textAreaId = $(this).find('textarea').attr('id');

                if (typeof textAreaId !== 'undefined') {
                    //tinymce.execCommand( 'mceRemoveEditor', false, textAreaId );
                    //tinymce.execCommand( 'mceAddEditor', false, textAreaId );
                }
                
                
                $(this).find('textarea').each(function (i,obj) {
                  var txelmid = $(obj).attr('id');
                  if (typeof txelmid !== 'undefined') {
                    tinymce.execCommand( 'mceRemoveEditor', false, txelmid );
                    tinymce.execCommand( 'mceAddEditor', false, txelmid );
                  }
                });
                
                
            });

            OerCurriculum.toggleUpDownButton();
        },

        // Show/Hide up/down button
        toggleUpDownButton: function() {
            // Hide the up button in the first child
            $('.reorder-up').show();
            $('.reorder-down').show();
            $('.reorder-up').first().hide();
            $('.reorder-down').last().hide();

            // Toggle Activity button order
            $('.activity-reorder-up').show();
            $('.activity-reorder-down').show();
            $('.activity-reorder-up').first().hide();
            $('.activity-reorder-down').last().hide();

            // Toggle button from author module
            // Hide up button from first element
            // hide down button from last element
            $('.author-reorder-up').show();
            $('.author-reorder-down').show();
            $('.author-reorder-up').first().hide();
            $('.author-reorder-down').last().hide();

            // Show / Hide button on Materials module
            $('.material-reorder-up').show();
            $('.material-reorder-down').show();
            $('.material-reorder-up').first().hide();
            $('.material-reorder-down').last().hide();

            // Show / Hide button on Resource module
            $('.resource-reorder-up').show();
            $('.resource-reorder-down').show();
            $('.resource-reorder-up').first().hide();
            $('.resource-reorder-down').last().hide();
            
            // Show / Hide button on Additional Section module
            $('#oercurr-additional-sections .section-reorder-up').show();
            $('#oercurr-additional-sections .section-reorder-down').show();
            $('#oercurr-additional-sections .section-reorder-up').first().hide();
            $('#oercurr-additional-sections .section-reorder-down').last().hide();
            
            // Show / Hide button on Required Materials Section module
            $('#oercurr-required-materials .section-reorder-up').show();
            $('#oercurr-required-materials .section-reorder-down').show();
            $('#oercurr-required-materials .section-reorder-up').first().hide();
            $('#oercurr-required-materials .section-reorder-down').last().hide();
            
            
        },

        // Create dynamic module
        createDynamicModule: function () {

            // Open modal when click one add module button
            $(document).on('click', '#oercurr-create-dynamic-module', function (e) {
                e.preventDefault();
                $('#oercurr-dynamic-module-modal').modal({
                    backdrop: 'static',
                    keyboard: false
                })
            });

            $(document).on('click','#oercurr-create-module-btn', function () {
                var total_form_box = parseInt($('.oercurr-element-wrapper').length, 10);
                var module_type = $('#module-type').val();

                $.post(ajaxurl, {action:'oercurr_create_module_callback', module_type: module_type, row_id: total_form_box}).done(function (response) {
                    $(response).insertAfter('div.oercurr-element-wrapper:last');

                    if (module_type == 'editor') {
                        tinymce.execCommand( 'mceRemoveEditor', false, 'oercurr-custom-editor-' + total_form_box );
                        tinymce.execCommand( 'mceAddEditor', false, 'oercurr-custom-editor-' + total_form_box );
                    }

                    $('#oercurr-dynamic-module-modal').modal('hide');
                    // Toggle reorder button
                    OerCurriculum.toggleUpDownButton();
                });
            });
        },

        // Dismiss the plugin installation message
        dismissInstallNotice: function () {
            /* $(document).on('click', '#oercurr-dismissible-notice .notice-dismiss', function () {
                $.post(ajaxurl, {action:'oercurr_dismiss_notice_callback'}).done(function (response) {
                });
            }); */
        },
        
        // Add more author
        addMoreAuthor: function () {
            $(document).on('click', '#oercurr-add-more-author', function () {
                var ClonedDiv = $('.oercurr-author-element-wrapper:last').clone();
                ClonedDiv.insertAfter('div.oercurr-author-element-wrapper:last');
                ClonedDiv.find('input[type=text]').val('');
                ClonedDiv.find('img.oercurr-oer-person-placeholder').attr('src',lpScript.image_placeholder_url);
                $('.oercurr-remove-author').removeAttr('disabled');
                OerCurriculum.toggleUpDownButton();
            });
        },

        // Delete author
        deleteAuthor: function () {
            $(document).on('click', '.oercurr-remove-author',function(e) {
                var author = $(this).closest('.card-default');
                var elementId = author.attr('id');
                e.preventDefault();
                $('#oercurr-delete-author').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                $('#oercurr-delete-author').modal({"show" : true})
                    .on('click', '#oercurr-author-delete-confirm', function(e) {
                        author.remove();
                        $('a[href="#' + elementId +'"]').parent('li').remove();
                        $('#oercurr-delete-author').modal('hide');

                        // Disable delete button for author
                        
                        oercurr_RefreshSectionDeleteButtons(jQuery(".oercurr-author-element-wrapper").find('.oercurr-remove-author'));
                        OerCurriculum.toggleUpDownButton();
                    });
            });
        },
        
        // Upload author image
        lpUploadAuthorImage: function () {
            $(document).on('click', '.oercurr-oer-person-placeholder', function (e) {
                var frame;
                e.preventDefault();
                var dis = $(this);
                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: lpScript.txtSelectAuthorPicture,
                    button: { text: lpScript.txtUsePicture },
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
            jQuery(document).on('click', '#oercurr-select-standard', function (e) {
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
                        //var standardTitle = jQuery(this).next().next('div.oercurr-notation-description').text();
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
                jQuery('#lpOerStandardModal input[value="'+pillId+'"]').prop('checked', false);
            });
        },
        
        // Add materials to the module
        lpAddMaterials: function () {
            $(document).on('click', '.oercurr-add-materials', function (e) {
                e.preventDefault();
                var moduleName = $(this).attr('data-name');
                var materialsContainer = $(this).prev('.oercurr-materials-container');

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
                    title: lpScript.txtSelectMaterials,
                    button: { text: lpScript.txtUseMaterials },
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

                        materialHTML += '<div class="card col-12 card-default oercurr-material-element-wrapper">' +
                                            '<div class="card-header">' +
                                                '<h3 class="card-title oercurr-module-title">' +
                                                    '<span class="oercurr-sortable-handle">' +
                                                        '<i class="fa fa-arrow-down material-reorder-down" aria-hidden="true"></i>' +
                                                        '<i class="fa fa-arrow-up material-reorder-up" aria-hidden="true"></i>' +
                                                    '</span>' +
                                                    '<span class="btn btn-danger btn-sm oercurr-remove-material" title="Delete"><i class="fa fa-trash"></i></span>' +
                                                '</h3>' +
                                            '</div>' +
                                            '<div class="card-body">' +
                                                '<div class="form-group">' +
                                                    '<div class="input-group">' +
                                                        '<input type="text" class="form-control" name="' + oer_curriculum_oer_materials_input + '[url][]" placeholder="URL" value="' + attachment.url + '">' +
                                                        '<div class="input-group-addon oercurr-material-icon" title="'+ title +'">' + icon + '</div>' +
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
                    if (materialsContainer.has('.oercurr-material-element-wrapper').length) {
                        $(materialHTML).appendTo(materialsContainer);
                    } else {
                        materialsContainer.html(materialHTML);
                    }

                    OerCurriculum.toggleUpDownButton();
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
            $(document).on('click', '.oercurr-material-icon', function (e) {
                e.preventDefault();
                var dis = $(this);
                var elementWrapper = dis.closest('.oercurr-material-element-wrapper');
                var wraperElementId = dis.closest('.oercurr-element-wrapper').attr('id');
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
                    title: lpScript.txtSelectMaterial,
                    button: { text: lpScript.txtUseMaterial },
                    multiple: false
                });

                materialFrame.on('select', function(){
                    var attachment = materialFrame.state().get('selection').first().toJSON();
                    var response = OerCurriculum.lpPrepareMaterialIcon(attachment);

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
            $(document).on('click', '.oercurr-remove-material',function(e) {
                var material = $(this).closest('.card-default');
                var elementId = material.attr('id');
                e.preventDefault();
                $('#oercurr-delete-confirm-popup').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                    .on('click', '#oercurr-delete-confirm-popup-btn', function(e) {
                        material.remove();
                        $('a[href=#' + elementId +']').parent('li').remove();
                        $('#oercurr-delete-confirm-popup').modal('hide');

                        // Disable delete button for author
                        if($('.oercurr-material-element-wrapper').length === 1) {
                            $('.oercurr-remove-material').attr('disabled', 'disabled');
                        }
                        OerCurriculum.toggleUpDownButton();
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
                typingTimer = setTimeout(OerCurriculum.lpProcessingStandardSearch, doneTypingInterval);
            });

            //on keydown, clear the countdown
            $input.on('keydown', function () {
                clearTimeout(typingTimer);
            });

            $input.on('keypress', function (e) {
                if (e.which == 13) {
                    if ($(this).val() !== '') {
                        OerCurriculum.lpProcessingStandardSearch();
                    } else {
                        $('.oercurr-standard-search-result').addClass('hide');
                        $('.oercurr-standard-default-result').removeClass('hide');
                    }
                }
            });
        },
        
        // Process the standard search
        lpProcessingStandardSearch: function () {
            var $input = $('#oerLpSearchStandardInput');

            if($input.val() == '') {
                $('.oercurr-standard-search-result').addClass('hide');
                $('.oercurr-standard-default-result').removeClass('hide');
                return false;
            }

            var data = {
                action: 'oercurr_searched_standards_callback',
                post_id: $input.attr('data-post'),
                keyword: $input.val()
            };
            $.post(
                ajaxurl,
                data
            ).done(function (response) {
                var $resultContainer = $(".oercurr-standard-search-result");
                $resultContainer.html(response);
                $resultContainer.removeClass('hide');
                $('.oercurr-standard-default-result').addClass('hide');
            });
        },

        // Select lesson document for download copy
        lpDownloadCopyLesson: function() {
            $(document).on('click', 'input[name="oer_curriculum_download_copy_document"], .oercurr-download-copy-icon', function (e) {
                e.preventDefault();
                var dis = $(this);

                var materialFrame;
                if (materialFrame) {
                    materialFrame.open();
                    return;
                }
                materialFrame = wp.media({
                    title: lpScript.txtSelectMaterial,
                    library: { type: [ 'application/msword', 'application/pdf' ] },
                    button: { text: lpScript.txtUseMaterial},
                    multiple: false
                });

                materialFrame.on('select', function(){
                    var attachment = materialFrame.state().get('selection').first().toJSON();
                    var response = OerCurriculum.lpPrepareMaterialIcon(attachment);

                    var icon = response.icon;
                        icon = icon.replace('fa-2x', '');

                    $('.oercurr-download-copy-icon').html(icon);
                    
                    $('input[name="oer_curriculum_download_copy_document"]').val(attachment.url);
                    if (dis.parent().find('.oercurr-selected-section')) {
                        dis.parent().find('.oercurr-selected-section a').attr('href',attachment.url);
                        dis.parent().find('.oercurr-selected-section a').text(attachment.url);
                        dis.parent().find('.oercurr-selected-section').removeClass('oercurr-hidden');
                        dis.parent().find('.oercurr-select-label').addClass('oercurr-hidden');
                        dis.parent().find('.oercurr-download-copy-icon').addClass('oercurr-hidden');
                    }
                });

                materialFrame.open();
            });
        },

        // Add More Primary resources
        addMorePrimaryResource: function () {
            $(document).on('click', '.oercurr-add-more-resource', function () {
                var resource_field_type = $(this).attr('typ');
                var total_form_box = parseInt($('.oercurr-primary-resource-element-panel').find('.oercurr-primary-resource-element-wrapper').length, 10);
                total_form_box += 1;
                $.post(ajaxurl, {action:'oercurr_add_more_prime_resource_callback', row_id: total_form_box, type: resource_field_type}).done(function (response) {
                    if($('div.oercurr-primary-resource-element-wrapper').length) {
                        $(response).insertAfter('div.oercurr-primary-resource-element-wrapper:last').tinymce_textareas();
                    } else {
                        $('.oercurr-primary-resource-element-panel').html(response).tinymce_textareas();
                    }
                    
                    if (typeof( tinymce ) == "object" && typeof( tinymce.execCommand ) == "function" ) {
                        tinymce.execCommand( 'mceRemoveEditor', false, 'oercurr-resource-student-' + total_form_box );
                        tinymce.execCommand( 'mceAddEditor', false, 'oercurr-resource-student-' + total_form_box );
                        quicktags({ id: 'oercurr-resource-teacher-' + total_form_box });
                        quicktags({ id: 'oercurr-resource-student-' + total_form_box });
                        tinyMCE.init({
                            plugins: "lists link fullscreen imagetools",
                            selector: "textarea#oercurr-resource-student-" + total_form_box,
                            content_css: lpScript['pluginDirUrl']+"/css/backend/oer-curriculum-mce-style.css",
                            mode: 'exact',
                            menubar: false,
                            relative_urls : false,
                            toolbar: 'bold italic underline blockquote strikethrough bullist numlist alignleft  aligncenter alignright undo redo link fullscreen',
                            content_langs: [
                              { title: 'Spanish', code: 'es' }
                            ]
                        });
                    }

                    // Toggle reorder button
                   OerCurriculum.toggleUpDownButton();
                });
            });

              $.fn.tinymce_textareas = function(){
                tinyMCE.init({
                    //plugins: 'print preview fullpage powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable',
                    plugins: 'lists link fullscreen imagetools',
                    skin: 'lightgray',
                    mode: 'exact',
                    menubar: false,
                    relative_urls : false,
                    toolbar: 'bold italic underline blockquote strikethrough numlist bullist alignleft aligncenter alignright undo redo link fullscreen'
                });
            }
        },

        // Delete source
        deletePrimarySource: function () {
            $(document).on('click', '.oercurr-remove-source',function(e) {
                var source = $(this).closest('.card-default');
                var elementId = source.attr('id');
                e.preventDefault();
                $('#oercurr-delete-source').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                    .on('click', '#oercurr-source-delete-confirm', function(e) {
                        source.remove();
                        $('a[href=#' + elementId +']').parent('li').remove();
                        $('#oercurr-delete-source').modal('hide');

                        // Disable delete button for author
                        if($('.oercurr-primary-source-element-wrapper').length === 1) {
                            $('.oercurr-remove-source').attr('disabled', 'disabled');
                        }
                        OerCurriculum.toggleUpDownButton();
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
                var custom_editor = $(".oercurr-introduction-group[id^=oercurr-custom-editor-group");
                var custom_visible = $(".oercurr-introduction-group[id^=oercurr-custom-editor-group").is(":visible");
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
            $(document).on('click', '.oercurr-remove-download-copy', function (e) {
                e.preventDefault();
                var dis = $('input[name="oer_curriculum_download_copy_document"]');

                $('.oercurr-selected-section').addClass('oercurr-hidden');
                $('input[name="oer_curriculum_download_copy_document"]').val("");
                $('.oercurr-select-label').removeClass('oercurr-hidden');
                $('.oercurr-download-copy-icon').removeClass('oercurr-hidden').html('<i class="fa fa-upload"></i>');
                
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
                
                var total_text_features = parseInt($('#oercurr-required-materials .oercurr-section-element-wrapper').length, 10);
                var id = total_text_features + 1;
                const editor_prefix = 'oercurr-required-material-section-';
                $.post(ajaxurl,
                       {
                        action:'oercurr_add_text_feature_callback',
                        row_id: total_text_features,
                        editor_id: editor_prefix,
                        required_material: true
                       }).done(function (response) {
                    if($('#oercurr-required-materials div.oercurr-section-element-wrapper').length) {
                        $(response).insertAfter('#oercurr-required-materials div.oercurr-section-element-wrapper:last').tinymce_textareas();
                    } else {
                        $('.oercurr-section-element-panel').html(response).tinymce_textareas();
                    }
                    quicktags({ id: editor_prefix + id });
                    tinyMCE.init({
                        plugins: "lists link fullscreen imagetools",
                        selector: "textarea#"+editor_prefix + id,
                        content_css: lpScript['pluginDirUrl']+"/css/backend/oer-curriculum-mce-style.css",
                        mode: 'exact',
                        menubar: false,
                        relative_urls : false,
                        toolbar: 'bold italic underline blockquote strikethrough bullist numlist alignleft  aligncenter alignright undo redo link fullscreen'
                    });
                    $('#oercurr-required-materials .oercurr-remove-section').removeAttr('disabled');
                    OerCurriculum.toggleUpDownButton();
                });
            });
        },
        
        // Add Additional Section
        lpAddTextFeature: function(){
            $(document).on("click", "#addTxtBtn", function(e){
              e.preventDefault();
              var dis = $(this).closest('.button-row.form-group');
              
              var total_text_features = parseInt($('#oercurr-additional-sections .oercurr-section-element-wrapper').length, 10);
              var id = total_text_features + 1;
              const editor_prefix = 'oercurr-additional-section-';
              $.post(ajaxurl,
                     {
                      action:'oercurr_add_text_feature_callback',
                      row_id: total_text_features,
                      editor_id: editor_prefix
                     }).done(function (response) {
                  if($('#oercurr-additional-sections div.oercurr-section-element-wrapper').length) {
                      $(response).insertAfter('#oercurr-additional-sections div.oercurr-section-element-wrapper:last').tinymce_textareas();
                  } else {
                      $('.oercurr-section-element-panel').html(response).tinymce_textareas();
                  }
                  quicktags({ id: editor_prefix + id });
                  tinyMCE.init({
                      plugins: "lists link fullscreen imagetools",
                      selector: "textarea#"+editor_prefix + id,
                      content_css: "/wp-content/plugins/wp-curriculum/css/backend/oer-curriculum-mce-style.css",
                      mode: 'exact',
                      menubar: false,
                      relative_urls : false,
                      toolbar: 'bold italic underline blockquote strikethrough bullist numlist alignleft  aligncenter alignright undo redo link fullscreen'
                  });
                  $('#oercurr-additional-sections .oercurr-remove-section').removeAttr('disabled');
                  OerCurriculum.toggleUpDownButton();
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
            $(document).on('click', '.oercurr-remove-section',function(e) {
                var section = $(this).closest('.card-default');
                var elementId = section.attr('id');
                e.preventDefault();
                $('#oercurr-delete-confirm-popup').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                    .on('click', '#oercurr-delete-confirm-popup-btn', function(e) {
                        section.remove();
                        $('a[href="#' + elementId +'"]').parent('li').remove();
                        $('#oercurr-delete-confirm-popup').modal('hide');

                        // Disable delete button for section
                        if($('.oercurr-section-element-wrapper').length === 1) {
                            $('.oercurr-remove-section').attr('disabled', 'disabled');
                        }
                        
                        oercurr_RefreshSectionDeleteButtons(jQuery("#oercurr-required-materials").find('.oercurr-remove-section'));
                        oercurr_RefreshSectionDeleteButtons(jQuery("#oercurr-additional-sections").find('.oercurr-remove-section'));
                        OerCurriculum.toggleUpDownButton();
                        
                    });
            });
        },
        
        // Initialize WP Editor
        initializeEditor: function(id) {
            wp.editor.remove(id);
            wp.oldEditor.initialize(
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
                metabox = $(this).closest(".oercurr-primary-resource-element-wrapper");
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
                    imageholder.find(".resource-thumbnail,.oercurr-remove-source-featured-image").remove();
                    var attachment = frame.state().get("selection").first().toJSON();
                    
                    input.val(attachment.url);
                    imageholder.append('<img src="' + attachment.url + '" class="resource-thumbnail" width="200"><span class="btn btn-danger btn-sm oercurr-remove-source-featured-image" title="Remove Thumbnail"><i class="fas fa-minus-circle"></i></span>');
                    btn.text(lpScript.txtChangeThumbnail);
                });
                
                frame.open();
            });
        },
        
        /** Remove Featured Image on Add Textbox/Resource Selection **/
        removeFeaturedImageInResourceSelection: function(){
            var frame, metabox, btn, input, imageholder;
            $(document).on('click', '.oercurr-remove-source-featured-image',function(e) {
                metabox = $(this).closest(".oercurr-primary-resource-element-wrapper");
                btn = $(this);
                input = metabox.find('.oer_primary_resourceurl');
                imageholder = metabox.find('.oer_primary_resource_thumbnail_holder');
                
                e.preventDefault();
        
                input.val("");
                imageholder.find("img").remove();
                btn.remove();
                metabox.find('button.oer_curriculum_primary_resources_thumbnail_button').text(lpScript.txtSetThumbnail);
            });
        },
        
        switchToVisualWorkaround: function(){
          setTimeout(function() {
              jQuery('.switch-tmce').trigger('click');
          } ,1000);
        }
        
    };
    
    // Initialize all function on ready state
    OerCurriculum.updateActivityTitle();
    OerCurriculum.addMoreTimeElements();
    OerCurriculum.removeTimeElements();
    OerCurriculum.addMoreObjectives();
    OerCurriculum.removeObjectives();
    OerCurriculum.addActivityInLesson();
    OerCurriculum.deleteModule();
    OerCurriculum.lessonElementSortable();
    OerCurriculum.createDynamicModule();
    OerCurriculum.toggleUpDownButton();
    OerCurriculum.dismissInstallNotice();
    OerCurriculum.addMoreAuthor();
    OerCurriculum.deleteAuthor();
    OerCurriculum.lpUploadAuthorImage();
    OerCurriculum.lpSelectStandards();
    OerCurriculum.lpRemoveStandardsFromList();
    OerCurriculum.lpAddMaterials();
    OerCurriculum.lpUpdateMaterial();
    OerCurriculum.lpDeleteMaterials();
    OerCurriculum.lpSearchStandards();
    OerCurriculum.lpDownloadCopyLesson();
    OerCurriculum.addMorePrimaryResource();
    OerCurriculum.deletePrimarySource();
    OerCurriculum.requireModuleTitle();
    OerCurriculum.lpRemoveCopyLesson();
    OerCurriculum.lpPrimarySourceSensitiveMaterial();
    OerCurriculum.lpOtherCurriculumType();
    OerCurriculum.lpAddRequiredMaterial();
    OerCurriculum.lpAddTextFeature();
    OerCurriculum.deleteSection();
    OerCurriculum.lpTinyMCESave();
    OerCurriculum.addFeaturedImageOnResourceTextBox();
    OerCurriculum.removeFeaturedImageInResourceSelection();
    OerCurriculum.switchToVisualWorkaround();
    
    /* Float Secton Anchor */
    let oercurrMetaboxScrollTargetCntr = 0;
    let oercurrMetaboxScrollTarget = setInterval(function(){
      oercurrMetaboxScrollTargetCntr++;
      var target_elm = jQuery('.interface-interface-skeleton__content');
      if(target_elm.length){
        // target element presence is confirmed, load event listeners
        clearInterval(oercurrMetaboxScrollTarget);
        document.querySelector('.interface-interface-skeleton__content').addEventListener('scroll', () => {
          var scrtop = jQuery("#oer_curriculum_meta_boxid").offset().top;
          if(scrtop < 11){
            jQuery('.oer_curriculum_left_column').addClass('float');
            jQuery('.oer_curriculum_left_column .list-group').width(jQuery('.oer_curriculum_left_column').width());
          }else{
              jQuery('.oer_curriculum_left_column').removeClass('float');
          }
        });      
        window.addEventListener('resize', function () {
          jQuery('.oer_curriculum_left_column .list-group').width(jQuery('.oer_curriculum_left_column').width());
        })
        // terminate observer if target element is not loaded within 3 minutes
        if(oercurrMetaboxScrollTargetCntr > 1800){
          clearInterval(oercurrMetaboxScrollTarget);
        }
      }
    }, 100);

    
    
    jQuery(document).on('click','.handle-order-higher',function(e){
      oercurr_reinstate_editor_content_on_move(this);
    })
    
    jQuery(document).on('click','.handle-order-lower',function(e){
      oercurr_reinstate_editor_content_on_move(this);
    })
    
    function oercurr_reinstate_editor_content_on_move(obj){
      if(jQuery(obj).closest('.postbox').find('.oer_curriculum_meta_wrapper .oercurr-element-wrapper .form-group iframe').length){
        setTimeout(function(){
          jQuery('.edit-post-layout__metaboxes .oercurr-element-wrapper .form-group iframe').each(function(index,obj){
            var elmid = jQuery(obj).attr('id').replace("_ifr", "");
            tinymce.execCommand( 'mceRemoveEditor', false, elmid);
            tinymce.execCommand( 'mceAddEditor', false, elmid );
          })
        }, 1300);
      }
    }
    
});

jQuery(window).bind("load", function() { 
  let MetaboxeRenderIntervalCntr = 0;
  let MetaboxeRenderInterval = setInterval(function(){
    MetaboxeRenderIntervalCntr++;
    if(jQuery('.edit-post-layout__metaboxes').length){
      jQuery('.handle-order-lower').removeClass('hidden');
      jQuery('.handle-order-higher').removeClass('hidden');
    }else{
      if(MetaboxeRenderIntervalCntr > 1800){clearInterval(MetaboxeRenderInterval)};
    }
  }, 100);
  
  //custom taxonomy duplicate $message
  jQuery(document).on('click','.editor-post-taxonomies__hierarchical-terms-submit',function(e){
    var inputval = jQuery(this).siblings('input.editor-post-taxonomies__hierarchical-terms-input').val();
    console.log(inputval);
    if(inputval != ""){
      jQuery(this).closest('.components-panel__body').find('.editor-post-taxonomies__hierarchical-terms-list .editor-post-taxonomies__hierarchical-terms-choice').each(function(index,obj){
        if(inputval == jQuery(obj).text()){
          //console.log(jQuery(obj).text() + ' -> match');
        }else{
          //console.log(jQuery(obj).text());
        }
      })
    }
  })
  
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

//Enable Fields Checkbox status change
jQuery(document).on('change','.oercurr-enabled-checkbox',function(e){
   if(jQuery(this).is(':checked')){
     jQuery(this).val('1');
   }else{
     jQuery(this).val('0');
   }
});

//Refresh delete section mediaButtons
jQuery(window).load(function() {
  oercurr_RefreshSectionDeleteButtons(jQuery("#oercurr-required-materials").find('.oercurr-remove-section'));
  oercurr_RefreshSectionDeleteButtons(jQuery("#oercurr-additional-sections").find('.oercurr-remove-section'));
  oercurr_RefreshSectionDeleteButtons(jQuery("#oercurr-authors").find('.oercurr-remove-author'));
  setTimeout(function() {
      jQuery('.switch-tmce').trigger('click');
  } ,1000);
  
});

function oercurr_RefreshSectionDeleteButtons(obj){
  var cnt = 0;
  if(obj.length > 1){
    jQuery(obj).removeAttr('disabled');
  }else{
    jQuery(obj).attr('disabled','disabled');
  }
}

/* Set Subject Area Main Icon */
jQuery(document).on('click','#main_icon_button',function() {
  invoker = jQuery(this).attr('id');
  formfield = jQuery('#mainIcon').attr('name');
  showMediaUpload(invoker, formfield);
});

/* Set Subject Area Hover Icon */
jQuery(document).on('click','#hover_icon_button',function() {
  invoker = jQuery(this).attr('id');
  formfield = jQuery('#hoverIcon').attr('name');
  showMediaUpload(invoker, formfield);
});

/** Remove Main Icon **/
jQuery(document).on('click','#remove_main_icon_button',function() {
  jQuery('#mainIcon').val('');
  jQuery('.main_icon_button_img').remove();
  jQuery(this).addClass('hidden');
});

/** Remove Hover Icon **/
jQuery(document).on('click','#remove_hover_icon_button',function() {
  jQuery('#hoverIcon').val('');
  jQuery('.hover_icon_button_img').remove();
  jQuery(this).addClass('hidden');
});

function showMediaUpload(invoker, formfield){
	var button = jQuery(this),
	custom_uploader = wp.media({
	    title: 'Insert image',
	    library : {
	        type : 'image'
	    },
	    button: {
	        text: 'Use this image' // button label text
	    },
	    multiple: false // multiple image selection set to false
	}).on('select', function() { // it also has "open" and "close" events 
	    var attachment = custom_uploader.state().get('selection').first().toJSON();
	    let html = '<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />';
	    
	    imgurl = attachment.url;
		jQuery("#"+formfield).val(imgurl);
		if (jQuery("."+invoker+"_img").length>0) {
			jQuery("."+invoker+"_img").remove();
		}
		jQuery("#"+invoker).before('<div class="' + invoker + '_img">'+html+'</div>');
		jQuery("#remove_"+invoker).removeClass("hidden");
	})
	.open();
}