<?php
/**
 * The Template for displaying all single Curriculum
 */

/***
 * Enqueue the assets
 */
wp_enqueue_style('lesson-plan-load-fa', OER_LESSON_PLAN_URL.'assets/lib/font-awesome/css/font-awesome.min.css');
wp_enqueue_style('lesson-plan-bootstrap', OER_LESSON_PLAN_URL.'assets/lib/bootstrap-3.3.7/css/bootstrap.min.css');

get_header();

global $post;
global $wpdb;
$post_meta_data = get_post_meta($post->ID );
if (have_posts()) : while (have_posts()) : the_post();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?php echo the_title(); ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p><?php echo the_content(); ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            if(
                isset($post_meta_data['oer_lp_introduction'][0]) &&
                !empty($post_meta_data['oer_lp_introduction'][0])
            )
            {?>
                <div class="panel panel-default oer-lp-introduction-group" id="oer-lp-introduction-group">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <span><?php _e("Introduction", OER_LESSON_PLAN_SLUG); ?></span>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <?php echo $post_meta_data['oer_lp_introduction'][0];?>
                    </div>
                </div>
            <?php }?>

            <!--For Lesson Times-->
            <?php
                $oer_lp_times_label  = isset($post_meta_data['oer_lp_times_label'][0]) ? unserialize($post_meta_data['oer_lp_times_label'][0]) : array();
                $oer_lp_times_number = isset($post_meta_data['oer_lp_times_number'][0]) ? unserialize($post_meta_data['oer_lp_times_number'][0]) : array();
                $oer_lp_times_type   = isset($post_meta_data['oer_lp_times_type'][0]) ? unserialize($post_meta_data['oer_lp_times_type'][0]) : array();
            ?>
            <?php if(!empty($oer_lp_times_label)) {?>
            <div class="panel panel-default oer-lp-times-group" id="oer-lp-times-group">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php _e("Lesson Times", OER_LESSON_PLAN_SLUG); ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <?php
                        foreach ($oer_lp_times_label as $key => $item){?>
                            <li class="list-group-item">
                                <?php echo $item;?> -
                                <?php echo isset($oer_lp_times_number[$key]) ? $oer_lp_times_number[$key] : '';?>
                                <?php echo (isset($oer_lp_times_type[$key]) ? $oer_lp_times_type[$key] : '');?>
                            </li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        <?php } ?>

            <?php
            $oer_lp_grades = (isset($post_meta_data['oer_lp_grades'][0]) ? unserialize($post_meta_data['oer_lp_grades'][0]) : array());
            if(!empty($oer_lp_grades))
            {?>
                <!--For industries/subject/grades-->
                <div class="panel panel-default oer-lp-industries-group" id="oer-lp-industries-group">
                    <div class="panel-heading">
                        <h3 class="panel-title lp-module-title">
                            <?php _e("Industries / Subjects / Grades", OER_LESSON_PLAN_SLUG); ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <h4>Grade Levels</h4>
                        <?php
                        foreach ($oer_lp_grades as $key => $oer_lp_grade) { ?>
                            <label><?php echo $oer_lp_grade; ?></label>,
                        <?php }?>
                    </div>
                </div>
            <?php }?>

            <!--For Standards and Objectives -->
            <?php
            $oer_lp_related_objective = isset($post_meta_data['oer_lp_related_objective'][0]) ? unserialize($post_meta_data['oer_lp_related_objective'][0]) : array();
            if(!empty($oer_lp_related_objective))
            {?>
                <div class="panel panel-default oer-lp-standards-group" id="oer-lp-standards-group">
                    <div class="panel-heading">
                        <h3 class="panel-title lp-module-title">
                            <?php _e("Standards and Objectives", OER_LESSON_PLAN_SLUG); ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        foreach ( $oer_lp_related_objective as $key => $item) { ?>
                            <div class="lp-related-objective-row" id="lp-related-objective-row">
                                <?php echo $item;?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php }?>

            <!--Activities in this lesson-->
            <?php
            // Lesson activity data
            $oer_lp_activity_title  = isset($post_meta_data['oer_lp_activity_title'][0]) ? unserialize($post_meta_data['oer_lp_activity_title'][0]) : array();
            $oer_lp_activity_type   = isset($post_meta_data['oer_lp_activity_type'][0]) ? unserialize($post_meta_data['oer_lp_activity_type'][0]) : array();
            $oer_lp_activity_detail = isset($post_meta_data['oer_lp_activity_detail'][0]) ? unserialize($post_meta_data['oer_lp_activity_detail'][0]) : array();

            if(!empty($oer_lp_activity_title))
            {?>
                <div class="panel panel-default oer-lp-activities-group" id="oer-lp-activities-group">
                    <div class="panel-heading">
                        <h3 class="panel-title lp-module-title">
                            <?php _e("Activities in this Lesson", OER_LESSON_PLAN_SLUG); ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="panel-group" id="lp-ac-inner-panel">
                            <?php foreach ($oer_lp_activity_title as $key => $item) { ?>
                                <div class="panel panel-default lp-ac-item" id="lp-ac-item-<?php echo $key;?>">
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label>Activity Title</label>
                                            <?php echo $item; ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Activity Type</label>
                                            <?php echo (isset($oer_lp_activity_type[$key]) ? $oer_lp_activity_type[$key] : "");?>
                                        </div>
                                        <div class="form-group">
                                            <label>Activity Description</label>
                                            <?php echo isset($oer_lp_activity_detail[$key]) ? $oer_lp_activity_detail[$key] : "";?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php }?>


            <!--Summative Assessment-->
            <?php
            $oer_lp_assessment_type = (isset($post_meta_data['oer_lp_assessment_type'][0]) ? unserialize($post_meta_data['oer_lp_assessment_type'][0]) : array());
            if (!empty($oer_lp_assessment_type)) {?>
                <div class="panel panel-default oer-lp-summative-group" id="oer-lp-summative-group">
                    <div class="panel-heading">
                        <h3 class="panel-title lp-module-title">
                            <?php _e("Summative Assessment", OER_LESSON_PLAN_SLUG); ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <h4><?php _e("Assessment Type(s):", OER_LESSON_PLAN_SLUG); ?></h4>
                        <ul>
                            <?php foreach ($oer_lp_assessment_type as $key => $oer_lp_assessment) { ?>
                                <li><?php echo ucfirst($oer_lp_assessment); ?></li>
                            <?php }?>
                        </ul>
                        <?php echo "Others: " . $oer_lp_other_assessment_type = (isset($post_meta_data['oer_lp_other_assessment_type'][0]) ? $post_meta_data['oer_lp_other_assessment_type'][0] : '');?>
                        <div class="form-group">
                            <?php
                            echo $oer_lp_assessment = (isset($post_meta_data['oer_lp_assessment'][0]) ? $post_meta_data['oer_lp_assessment'][0] : '');
                             ?>
                        </div>
                    </div>
                </div>
            <?php }?>

        </div>
    </div>
</div>
<?php
	// Display Activity Objects
 	endwhile; 
endif; 
get_footer();
