<!--Confirm Modal-->
<div id="oercurr-confirm" class="oercurr-popups modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3><?php echo esc_html__('Eliminar módulo', OERCURR_CURRICULUM_SLUG) ?>?</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <strong><?php echo esc_html__('These items will be permanently deleted and cannot be recovered. Are you sure', OERCURR_CURRICULUM_SLUG) ?>?</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo esc_html__('Cancel', OERCURR_CURRICULUM_SLUG) ?></button>
                <button type="button" class="btn btn-danger" id="oercurr-delete-confirm"><?php echo esc_html__('Yes, Delete', OERCURR_CURRICULUM_SLUG) ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->