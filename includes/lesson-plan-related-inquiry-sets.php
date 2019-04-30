<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;
global $wpdb;
global $inquiryset_post;

$inquiry = get_post_meta($inquiryset_post->ID,'oer_lp_related_inquiry_set');
$inquiry = (is_array($inquiry)?$inquiry[0]:array());
var_dump($inquiry);
?>
<div class="lesson_plan_meta_wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default lp-related-inquiry-wrapper oer-related-inquiry-sets" id="oer-related-inquiry-sets-group">
                <div class="panel-body">
                    <?php
                    for ($i=1;$i<=3;$i++){
                        $inquirysets = oer_lp_related_inquiry_sets($inquiryset_post->ID);
                    ?>
                    <div class="form-group">
                        <label for="relatedInquirySet<?php echo $i; ?>">Inquiry Set <?php echo $i; ?></label>
                        <select name="oer_lp_related_inquiry_set[]" id="relatedInquirySet<?php echo $i; ?>" class="form-control">
                            <option value="0">-- Select Inquiry Set --</option>
                            <?php if (count($inquirysets)>0) {
                                foreach($inquirysets as $inquiryset) {
                            ?>
                                <option value="<?php echo $inquiryset->ID;?>" <?php echo (($inquiry[$i-1] == $inquiryset->ID) ? 'selected="selected"' : "");?>><?php echo $inquiryset->post_title;?></option>        
                            <?php
                                }
                            } ?>
                        </select>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>