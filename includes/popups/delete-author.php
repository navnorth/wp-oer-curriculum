<!--Delete author-->
<div id="oercurr-delete-author" class="oercurr-popups modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3><?php esc_html_e('Delete Author', OERCURR_CURRICULUM_SLUG) ?>?</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong><?php esc_html_e('These items will be permanently deleted and cannot be recovered. Are you sure', OERCURR_CURRICULUM_SLUG) ?>?</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Cancel', OERCURR_CURRICULUM_SLUG) ?></button>
                <button type="button" class="btn btn-danger" id="oercurr-author-delete-confirm"><?php esc_html_e('Yes, Delete', OERCURR_CURRICULUM_SLUG) ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->