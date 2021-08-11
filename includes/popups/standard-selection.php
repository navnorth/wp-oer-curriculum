<!-- Modal -->
<?php global $inquiryset_post, $post;?>
<div class="oercurr-popups modal fade" id="lpOerStandardModal" tabindex="-1" role="dialog" aria-labelledby="lpOerStandardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="lpOerStandardModalLabel">Add Standard</h4>
            </div>
            <div id="oercurr-standards-list" class="modal-body">
                <div class="oercurr-standard-search-bar">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text"
                                   name="searchStandard"
                                   class="form-control"
                                   id="oerLpSearchStandardInput"
                                   placeholder="Search Standards"
                                   data-post="<?php echo esc_attr($post->ID);?>"
                            >
                            <div class="input-group-addon"><i class="fa fa-search"></i> </div>
                        </div>
                    </div>
                </div>
                <div class="oercurr-standard-search-result hide" id="oercurr-standard-search-result"></div>
                <div class="oercurr-standard-default-result">
                    <?php
                    if (function_exists('was_selectable_admin_standards')){
                        was_selectable_admin_standards($inquiryset_post->ID, 'oer_curriculum_standards');
                    }?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="lpSelectStandardSaveBtn" class="button btn-default btn-sm" data-dismiss="modal">Select</button>
            </div>
        </div>
    </div>
</div>