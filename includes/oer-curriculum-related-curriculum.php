<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;
global $wpdb;
global $inquiryset_post;

$inquiryset_post = $post;
$inquiry = get_post_meta($inquiryset_post->ID,'oer_curriculum_related_curriculum');
$inquiry = ((is_array($inquiry)&& count($inquiry)>0)?$inquiry[0]:array());
?>
<div class="oer_curriculum_meta_wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="card col-12 card-default oercurr-related-inquiry-wrapper oer-related-inquiry-sets" id="oer-related-inquiry-sets-group">
                <div class="card-body">
                    <?php
                    for ($i=1;$i<=3;$i++){
                        $inquirysets = oercurr_related_curriculum($inquiryset_post->ID);
                        $related_curriculum_set = (trim(get_option('oer_curriculum_related_curriculum_'.$i.'_curmetset_label'),' ') != '')?true:false;
                        $related_curriculum_set_enabled = (get_option('oer_curriculum_related_curriculum_'.$i.'_curmetset_enable') == 'checked')?true:false;
                        if (($related_curriculum_set && $related_curriculum_set_enabled) || !$related_curriculum_set) {
                            $label = get_option('oer_curriculum_related_curriculum_'.$i.'_curmetset_label');
                            ?>
                            <div class="form-group">
                                <label for="relatedInquirySet<?php echo esc_html($i); ?>"><?php echo esc_html__($label,OERCURR_CURRICULUM_SLUG); ?></label>
                                <select name="oer_curriculum_related_curriculum[]" id="relatedInquirySet<?php echo esc_html($i); ?>" class="form-control">
                                    <option value="0">-- <?php echo esc_html__('Select Curriculum', OERCURR_CURRICULUM_SLUG) ?> --</option>
                                    <?php if (count($inquirysets)>0) {
                                        foreach($inquirysets as $inquiryset) {
                                    ?>
                                        <option value="<?php echo esc_attr($inquiryset->ID);?>" <?php if (!empty($inquiry)) selected($inquiry[$i-1],$inquiryset->ID, true); ?>><?php echo esc_html($inquiryset->post_title);?></option>        
                                    <?php
                                        }
                                    } ?>
                                </select>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
