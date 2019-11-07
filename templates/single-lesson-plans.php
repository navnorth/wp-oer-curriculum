<?php
/**
 * The Template for displaying all single Curriculum
 */

/**
 * Enqueue the assets
 */
wp_enqueue_style('lesson-plan-load-fa', OER_LESSON_PLAN_URL.'assets/lib/font-awesome/css/all.min.css');
wp_enqueue_style('lesson-plan-bootstrap', OER_LESSON_PLAN_URL.'assets/lib/bootstrap-3.3.7/css/bootstrap.min.css');
wp_enqueue_script('lesson-plan-frontend', OER_LESSON_PLAN_URL.'assets/js/frontend/lesson-plan.js', array('jquery'), null, true);
wp_enqueue_script( 'jquery-ui-slider' );

get_header();

global $post;
global $wpdb;
$oer_sensitive = false;
$sensitive_material = null;

$post_meta_data = get_post_meta($post->ID );
$elements_orders = isset($post_meta_data['lp_order'][0]) ? unserialize($post_meta_data['lp_order'][0]) : array();

//Grade Level
$lp_grade = isset($post_meta_data['oer_lp_grades'][0])? unserialize($post_meta_data['oer_lp_grades'][0])[0]:"";
if ($lp_grade!=="pre-k" && $lp_grade!=="k")
    $lp_grade = "Grade ".$lp_grade;
    
// Download Copy
$oer_lp_download_copy_document = (isset($post_meta_data['oer_lp_download_copy_document'][0]) ? $post_meta_data['oer_lp_download_copy_document'][0] : '');
$oer_lp_standards = isset($post_meta_data['oer_lp_standards'][0])?$post_meta_data['oer_lp_standards'][0]:"";
$tags = get_the_terms($post->ID,"post_tag");
$authors = (isset($post_meta_data['oer_lp_authors'][0]) ? unserialize($post_meta_data['oer_lp_authors'][0]) : array());

// check if there is a resource with sensitive material set
$oer_resources = (isset($post_meta_data['oer_lp_primary_resources'][0]) ? unserialize($post_meta_data['oer_lp_primary_resources'][0]) : array());

if (isset($oer_resources['sensitive_material']))
    $sensitive_material = $oer_resources['sensitive_material'];
    
if (!empty($sensitive_material) && count($sensitive_material)>0) {
    $oer_sensitive = true;
}

$lp_type_set = (get_option('oer_lp_type_label'))?true:false;
$lp_type_enabled = (get_option('oer_lp_type_enabled'))?true:false;
$related_inquiry_set = (get_option('oer_lp_related_inquiry_set_label'))?true:false;
$related_inquiry_enabled = (get_option('oer_lp_related_inquiry_set_enabled'))?true:false;
$author_set = (get_option('oer_lp_authors_label'))?true:false;
$author_enabled = (get_option('oer_lp_authors_enabled'))?true:false;
$standards_set = (get_option('oer_lp_standards_label'))?true:false;
$standards_enabled = (get_option('oer_lp_standards_enabled'))?true:false;

if (have_posts()) : while (have_posts()) : the_post();
?>
<div class="container">
    <div class="row lp-featured-section">
        <div class="row tc-lp-details-header">
            <h1 class="tc-lp-title"><?php echo the_title(); ?></h1>
        </div>
        <div class="col-md-8 col-sm-12 curriculum-detail padding-0">
            <div class="tc-lp-details">
                <?php if (($lp_type_set && $lp_type_enabled) || !$lp_type_set) { ?>
                <div class="tc-lp-type">
                    <?php
                    $oer_lp_type = (isset($post_meta_data['oer_lp_type'][0]) ? $post_meta_data['oer_lp_type'][0] : '');
                    echo $oer_lp_type;
                    ?>
                </div>
                <?php } ?>
                <div class="tc-lp-details-description">
                    <?php echo the_content(); ?>
                </div>
                <?php
                $related_inquiry_sets = (isset($post_meta_data['oer_lp_related_inquiry_set'][0]) ? unserialize($post_meta_data['oer_lp_related_inquiry_set'][0]) : array());
                $show_related_inquiry_sets = false;
                foreach($related_inquiry_sets as $rset){
                    if ($rset!=="0"){
                        $show_related_inquiry_sets = true;
                        break;
                    }
                }
                if ($show_related_inquiry_sets) {
                    if (($related_inquiry_set && $related_inquiry_enabled) || !$related_inquiry_set) {
                ?>
                <div class="tc-related-inquiry-sets">
                    <h4 class="tc-related-inquiry-sets-heading clearfix">
                        <?php echo oer_lp_get_field_label('oer_lp_related_inquiry_set'); ?>
                    </h4>
                    <div class="tc-related-inquiry-sets-details clearfix">
                        <ul class="tc-related-inquiry-sets-list">
                        <?php
                        foreach($related_inquiry_sets as $inquiry_set) {
                            if ($inquiry_set!=="0") {
                                $inquiry = oer_lp_get_inquiry_set_details($inquiry_set);
                                $inquiry_link = get_permalink($inquiry_set);
                                
                                echo '<li><a href="'.$inquiry_link.'">'.$inquiry->post_title.'</a></li>';
                            }
                        } ?>
                        </ul>
                    </div>
                </div>
                <?php }
                } ?>
                <?php if ($oer_lp_standards) {
                     if (($standards_set && $standards_enabled) || !$standards_set) {
                ?>
                <div class="tc-lp-standards">
                    <h4 class="tc-lp-field-heading clearfix">
                        <?php echo oer_lp_get_field_label('oer_lp_standards'); ?>
                    </h4>
                    <div class="tc-lp-standards-details clearfix">
                        <ul class="tc-lp-standards-list">
                            <?php
                            $stds = array();
                            $standards = array();
                            $cstandard = null;
                            $oer_lp_standards = explode(",",$oer_lp_standards);
                            if (is_array($oer_lp_standards)):
                                $current_std_id = "";
                                foreach($oer_lp_standards as $standard){
                                    if (function_exists('oer_std_get_standard_by_notation')){
                                        $core_standard = oer_std_get_standard_by_notation($standard);
                                        if ($current_std_id!==$core_standard->id){
                                            if (!empty($standards) && !empty($cstandard)) {
                                                $stds[] = array_merge(array("notation"=>$standards), $cstandard);
                                            }
                                            $standards = array();
                                            $current_std_id = $core_standard->id;
                                            $cstandard = array("core_standard_id"=>$core_standard->id,"core_standard_name"=>$core_standard->standard_name);
                                        }
                                        $standards[] = $standard;
                                    }
                                }
                                if (!empty($standards) && !empty($cstandard)) {
                                    $stds[] = array_merge(array("notation"=>$standards), $cstandard);
                                }
                                $cstd_id = array_column($stds,"core_standard_id");
                                array_multisort($cstd_id,SORT_ASC,$stds);
                                $standard_details = "";
                                foreach($stds as $std){
                                    if (isset($std['core_standard_id'])) {
                                        echo "<li>";
                                            echo '<a data-toggle="collapse" href="#core-standard-'.$std['core_standard_id'].'">'.$std['core_standard_name'].'</a>';
                                        ?>
                                        <div class="collapse tc-lp-details-standard" id="core-standard-<?php echo $std['core_standard_id']; ?>">
                                        <?php
                                        if (is_array($std['notation'])) {
                                            echo "<ul class='tc-lp-notation-list'>";
                                            foreach ($std['notation'] as $notation) {
                                                if (function_exists('was_standard_details'))
                                                    $standard_details = was_standard_details($notation);
                                                if (!empty($standard_details)){
                                                    if (isset($standard_details->description))
                                                        echo "<li>".$standard_details->description."</li>";
                                                    else
                                                        echo "<li>".$standard_details->standard_title."</li>";
                                                }
                                            }
                                            echo "</ul>";
                                        }
                                            echo "</div>";
                                        echo "</li>";
                                    }
                                }
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
                <?php
                     }
                } ?>
                <?php
                    $subject_areas = array();
                    $post_terms = get_the_terms( $post->ID, 'resource-subject-area' );
                    if (!empty($post_terms)){
                        $subjects = array();
                    foreach($post_terms as $term)
                    {
                        if($term->parent != 0)
                        {
                            $parent[] = oer_get_parent_term_list($term->term_id);
                            $subjects[] = $term;
                        }
                        else
                        {
                            $subject_areas[] = $term;
                        }
                    }
                    
                    if(!empty($parent) && array_filter($parent))
                    {
                        $recur_multi_dimen_arr_obj =  new RecursiveArrayIterator($parent);
                        $recur_flat_arr_obj =  new RecursiveIteratorIterator($recur_multi_dimen_arr_obj);
                        $flat_arr = iterator_to_array($recur_flat_arr_obj, false);
                
                        $flat_arr = array_values(array_unique($flat_arr));
                        
                        for($k=0; $k < count($flat_arr); $k++)
                        {
                            //$idObj = get_category_by_slug($flat_arr[$k]);
                            $idObj = get_term_by( 'slug' , $flat_arr[$k] , 'resource-subject-area' );
                            
                            if(!empty($idObj->name))
                                $subject_areas[] = $idObj;
                        }
                    }
                    if (count($subjects)>0)
                        $subject_areas = array_merge($subject_areas,$subjects);
                ?>
                <div class="tc-lp-subject-areas">
                   <h4 class="tc-lp-field-heading clearfix">
                        <?php _e("Subjects",OER_LESSON_PLAN_SLUG); ?>
                    </h4>
                   <div class="tc-lp-subject-details clearfix">
                        <ul class="tc-lp-subject-areas-list">
                            <?php
                            $i = 1;
                            $cnt = count($subject_areas);
                            $moreCnt = $cnt-2;
                            foreach($subject_areas as $subject){
                                $subject_url = home_url("/resource-subject-area/".$subject->slug);
                                if ($i>2)
                                    echo "<li class='hidden'><a href='".$subject_url."'>".$subject->name."</a></li>";
                                else
                                    echo "<li><a href='".$subject_url."'>".$subject->name."</a></li>";
                                if (($i==$cnt) && ($cnt>2)){
                                    echo "<li><a class='see-more-subjects'>SEE ".$moreCnt." MORE +</a></li>";
                                }
                                $i++;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <?php } ?>
                <?php if ($oer_sensitive) : ?>
                <div class="tc-sensitive-material-section">
                    <p><i class="fal fa-exclamation-triangle"></i><span class="sensitive-material-text">Sensitive Material</span></p>
                    <button class="question-popup-button"><i class="fal fa-question-circle"></i></button>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 featured-image padding-0">
            <?php the_post_thumbnail('inquiry-set-featured'); ?>
            <?php if (($author_set && $author_enabled) || !$author_set) { ?>
            <div class="tc-lp-authors-list">
                 <span class="lp-author-label"><?php echo oer_lp_get_field_label('oer_lp_authors'); ?></span>
                <?php
                if (!empty($authors)){
                    $aIndex = 0;
                    
                    foreach($authors['name'] as $author){
                        $author_url = $authors['author_url'][$aIndex];
                        
                        if (isset($author_url))
                            echo "<span class='tc-lp-author'><a href='".$author_url."'>".$authors['name'][$aIndex]."</a></span>";
                        else
                            echo "<span class='tc-lp-author'>".$authors['name'][$aIndex]."</span>";
                            
                        $aIndex++;
                    }
                } 
                ?>
            </div>
            <?php } 
            $keywords = wp_get_post_tags($post->ID);
            if(!empty($keywords))
            {
            ?>
            <div class="tc-lp-keywords">
                <div class="lp_keywords_container tagcloud">
                <?php
                    foreach($keywords as $keyword)
                    {
                            echo "<span><a href='".esc_url(get_tag_link($keyword->term_id))."' class='button'>".ucwords($keyword->name)."</a></span>";
                    }
                ?>
                </div>
            </div>
            <?php } ?>
            <div class="tc-lp-controls">
                <div class="sharethis-inline-share-buttons"></div>
                <?php if ($oer_lp_download_copy_document): ?>
                <a href="<?php echo $oer_lp_download_copy_document; ?>" target="_blank"><i class="fal fa-download"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="see-more-row">
        <p class="center"><span><a class="see-more-link"><?php _e("SEE MORE +",OER_LESSON_PLAN_SLUG); ?></a></span></p>
    </div>
    <div class="row lp-primary-sources-row">
        <?php
        $primary_resources = (isset($post_meta_data['oer_lp_primary_resources'][0]) ? unserialize($post_meta_data['oer_lp_primary_resources'][0]) : array());
        if (!empty($primary_resources) && lp_scan_array($primary_resources)) {
            if (!empty(array_filter($primary_resources['resource']))) {
                foreach ($primary_resources['resource'] as $resourceKey => $resource) {
                    $resource = get_page_by_title($resource,OBJECT,"resource");
                    $type = get_post_meta($resource->ID,"oer_mediatype");
                    $type = $type[0];
                    $resource_img = wp_get_attachment_image_url( get_post_thumbnail_id($resource), 'resource-thumbnail' );
                    $sensitiveMaterial = (isset($primary_resources['sensitive_material'][$resourceKey]) ? $primary_resources['sensitive_material'][$resourceKey]: "");
                    $sensitiveMaterialValue = (isset($primary_resources['sensitive_material_value'][$resourceKey]) ? $primary_resources['sensitive_material_value'][$resourceKey]: "");
                    if ($sensitiveMaterialValue!=="")
                        $sensitiveMaterial = $sensitiveMaterialValue;
                ?>
                <div class="col-md-3 col-sm-3 padding-0">
                    <div class="media-image">
                        <div class="image-thumbnail">
                            <?php if ($resource_img!==""):
                            $ps_url = site_url("inquiry-sets/".sanitize_title($post->post_name)."/source/".sanitize_title($resource->post_title)."-".$resource->ID);
                            ?>
                            <a href="<?php echo $ps_url;  ?>">
                                <span class="resource-overlay"></span>
                                <?php if (!empty($type)): ?>
                                <span class="lp-source-type"><?php echo $type; ?></span>
                                <?php endif; ?>
                                <div class="resource-thumbnail" style="background: url('<?php echo $resource_img ?>') no-repeat center rgba(204,97,12,.1)">
                                </div>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="lp-resource-title">
                            <?php echo $resource->post_title; ?>
                        </div>
                    </div>
                    <?php if ($sensitiveMaterial!=="" && $sensitiveMaterial!=="no"): ?>
                    <div class="sensitive-source">
                        <p><i class="fal fa-exclamation-triangle"></i></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php
                }
            }
        }
        ?>
    </div>
    <div class="row hidden">
        <ul class="nav nav-tabs tc-home-tabs" id="tc-home-tabs-section" role="tablist">
            <?php
            if (!empty($elements_orders)) {
                $col = 0;
                $keys = array(
                    "lp_introduction_order",
                    "lp_primary_resources",
                    "lp_lesson_times_order",
                    "lp_industries_order",
                    "lp_standard_order",
                    "lp_activities_order",
                    "lp_summative_order",
                    "lp_authors_order",
                    "lp_iq"
                );
                foreach($elements_orders as $element=>$order){
                    if (!in_array($element,$keys))
                        $col++;
                }
                if($col==0)
                    $_col = 12;
                else
                    $_col = floor(12/$col);
                foreach ($elements_orders as $elementKey => $value) {
                    if (strpos($elementKey, 'oer_lp_custom_editor_historical_background') !== false) {
                        $oer_lp_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                    ?>
                    <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                        <a class="nav-link active" id="tc-historical-background-tab" data-toggle="tab" href="#tc-historical-background-tab-content" role="tab" aria-controls="tc-historical-background-tab" aria-selected="true" aria-expanded="false">
                            <?php echo $oer_lp_custom_editor['title']; ?>
                        </a>
                    </li>
                    <?php  } elseif (strpos($elementKey, 'lp_oer_materials') !== false) {
                        $materials = (isset($post_meta_data['lp_oer_materials'][0]) ? unserialize($post_meta_data['lp_oer_materials'][0]) : array());
                        if (count($materials)>0) {
                    ?>
                    <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                        <a class="nav-link" id="tc-materials-tab" data-toggle="tab" href="#tc-materials-tab-content" role="tab" aria-controls="tc-materials-tab" aria-selected="false" aria-expanded="false">
                            <?php _e("Materials",OER_LESSON_PLAN_SLUG); ?>
                        </a>
                    </li>
                    <?php }
                    } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_custom_editor_') !== false) {
                        $oer_lp_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                        if(!empty($oer_lp_custom_editor)) {
                        ?>
                        <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                            <a class="nav-link" id="tc-<?php echo sanitize_title($oer_lp_custom_editor['title']); ?>-tab" data-toggle="tab" href="#tc-<?php echo sanitize_title($oer_lp_custom_editor['title']); ?>-tab-content" role="tab" aria-controls="tc-<?php echo sanitize_title($oer_lp_custom_editor['title']); ?>-tab" aria-selected="false" aria-expanded="false">
                                <?php echo $oer_lp_custom_editor['title']; ?>
                            </a>
                        </li>
                        <?php } ?>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_custom_text_list_') !== false) {
                        ?>
                        <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                            <a class="nav-link" id="tc-text-list-tab" data-toggle="tab" href="#tc-text-list-tab-content" role="tab" aria-controls="tc-text-list-tab" aria-selected="false" aria-expanded="false">
                                Text List
                            </a>
                        </li>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_vocabulary_list_title_') !== false) {
                        $oer_lp_vocabulary_list_title = (isset($post_meta_data[$elementKey][0]) ? $post_meta_data[$elementKey][0] : "");
                        $oer_keys = explode('_', $elementKey); 
                        $listOrder = end($oer_keys);
                        $oer_lp_vocabulary_details = (isset($post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0]) ? $post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0] : "");
                        if (!empty($oer_lp_vocabulary_list_title)) { ?>
                        <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                            <a class="nav-link" id="tc-<?php echo sanitize_title($oer_lp_vocabulary_list_title); ?>-tab" data-toggle="tab" href="#tc-<?php echo sanitize_title($oer_lp_vocabulary_list_title); ?>-tab-content" role="tab" aria-controls="tc-<?php echo sanitize_title($oer_lp_vocabulary_list_title); ?>-tab" aria-selected="false" aria-expanded="false">
                                <?php echo $oer_lp_vocabulary_list_title; ?>
                            </a>
                        </li>
                        <?php } ?>
                    <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'lp_oer_materials_list_') !== false) {?>
                        <li class="nav-item col-md-<?php echo $_col; ?> col-sm-<?php echo $_col; ?> padding-0">
                            <a class="nav-link" id="tc-materials-list-tab" data-toggle="tab" href="#tc-materials-list-tab-content" role="tab" aria-controls="tc-materials-list-tab" aria-selected="false" aria-expanded="false">
                                Materials
                            </a>
                        </li>
                        <?php
                        }
                    //}
                }
            }
            ?>
        </ul>
    </div>
    <div class="row tab-content tc-home-tabs-content col-md-12 padding-0 hidden">
        <?php
        if (!empty($elements_orders)) {
            foreach ($elements_orders as $elementKey => $value) {
                if (strpos($elementKey, 'oer_lp_custom_editor_historical_background') !== false || strpos($elementKey, 'oer_lp_custom_editor_teacher_background') !== false || strpos($elementKey, 'oer_lp_custom_editor_student_background') !== false || strpos($elementKey, 'oer_lp_custom_editor_') !== false) {
                    $tab_id = "";
                    $active = false;
                    $oer_lp_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                    if ($elementKey=="oer_lp_custom_editor_historical_background"){
                        $tab_id = "tc-historical-background-tab-content";
                        $active=true;
                    }
                    elseif ($elementKey=="oer_lp_custom_editor_teacher_background"){
                        $tab_id = "tc-teacher-background-tab-content";
                    }
                    elseif ($elementKey == "oer_lp_custom_editor_student_background" ){
                        $tab_id = "tc-student-background-tab-content";
                    }
                    else{
                        $tab_id = "tc-".sanitize_title($oer_lp_custom_editor['title'])."-tab-content";
                    }
                    if(!empty($oer_lp_custom_editor)) {
                    ?>
                    <div class="tab-pane clearfix fade <?php if ($active): echo "active"; endif; ?> in" id="<?php echo $tab_id; ?>" role="tabpanel" aria-labelledby="">
                        <div class="tc-tab-content">
                            <span><?php echo $oer_lp_custom_editor['description'];?></span>
                        </div>
                    <button class="tc-read-more">Read More</button>
                    </div>
                    <?php
                    } ?>
                <?php  } elseif (strpos($elementKey, 'lp_oer_materials') !== false) {
                        $materials = (isset($post_meta_data['lp_oer_materials'][0]) ? unserialize($post_meta_data['lp_oer_materials'][0]) : array());
                        $block_size = 12;
                        if (count($materials)>0) {
                            $arr_materials = array();
                            $urls = $materials['url'];
                            $titles = $materials['title'];
                            $descriptions = $materials['description'];
                            $block_size = floor(12/count($urls));
                            for($i=0;$i<count($urls);$i++){
                                $arr_materials[$i] = array("url" => $urls[$i],
                                                           "title" => $titles[$i],
                                                           "description" => $descriptions[$i]);
                            }
                    ?>
                    <div class="tab-pane clearfix fade" id="tc-materials-tab-content" role="tabpanel" aria-labelledby="tc-materials-list-tab">
                        <div class="tc-tab-content materials-tab">
                            <?php foreach($arr_materials as $material){ ?>
                                <?php if (isset($material['url']) && ($material['url']!="")): ?>
                                <a href="<?php echo $material['url']; ?>" class="tc-material-block-link" target="_blank">
                                <div class="tc-material-block">
                                    <div class="tc-material-icon">
                                        <?php
                                            $icon = "";
                                            $file_type = get_file_type_from_url($material['url']);
                                            switch ($file_type['title']){
                                                case "Image":
                                                    $icon = '<i class="far fa-file-image fa-3x"></i>';
                                                    break;
                                                case "PDF":
                                                    $icon = '<i class="far fa-file-pdf fa-3x"></i>';
                                                    break;
                                                case "Plain Text":
                                                    $icon = '<i class="far fa-file-alt fa-3x"></i>';
                                                    break;
                                                case "Archive":
                                                    $icon = '<i class="far fa-file-archive fa-3x"></i>';
                                                    break;
                                                case "Microsoft Document":
                                                    $icon = '<i class="far fa-file-word fa-3x"></i>';
                                                    break;
                                                case "Microsoft Excel":
                                                    $icon = '<i class="far fa-file-excel fa-3x"></i>';
                                                    break;
                                                case "Microsoft Powerpoint":
                                                    $icon = '<i class="far fa-file-powerpoint fa-3x"></i>';
                                                    break;
                                            }
                                        ?>
                                        <span><?php echo $icon; ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="tc-material-description">
                                        <?php if (isset($material['title']) && ($material['title']!="")): ?>
                                        <h4><?php echo $material['title']; ?></h4>
                                        <?php endif; ?>
                                        <?php if (isset($material['description']) && ($material['description']!="")): ?>
                                            <?php echo $material['description']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div></a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_custom_text_list_') !== false) {
                    $oer_lp_custom_text_list = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : array());
                    if (!empty(array_filter($oer_lp_custom_text_list))) {
                    ?>
                    <div class="tab-pane clearfix fade" id="tc-text-list-tab-content" role="tabpanel" aria-labelledby="">
                        <div class="tc-tab-content">
                            <ul>
                            <?php foreach ($oer_lp_custom_text_list as $key => $list) { ?>
                                <li><?php echo $list; ?></li>
                            <?php } ?>
                            </ul>
                        </div>
                        <button class="tc-read-more">Read More</button>
                    </div>
                    <?php
                    }
                    ?>
                <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_lp_vocabulary_list_title_') !== false) {
                    $oer_lp_vocabulary_list_title = (isset($post_meta_data[$elementKey][0]) ? $post_meta_data[$elementKey][0] : "");
                    $oer_keys = explode('_', $elementKey);
                    $listOrder = end($oer_keys);
                    $oer_lp_vocabulary_details = (isset($post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0]) ? $post_meta_data['oer_lp_vocabulary_details_'.$listOrder][0] : "");
                    if (!empty($oer_lp_vocabulary_list_title)) {
                        $tab_id = "tc-".sanitize_title($oer_lp_vocabulary_list_title)."-tab-content"
                    ?>
                    <div class="tab-pane clearfix fade" id="<?php echo $tab_id; ?>" role="tabpanel" aria-labelledby="">
                        <div class="tc-tab-content">
                            <p><?php echo $oer_lp_vocabulary_details;?></p>
                        </div>
                        <button class="tc-read-more">Read More</button>
                    </div>
                    <?php } ?>
                <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'lp_oer_materials_list_') !== false) {
                    $materials = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : array());
                    if (!empty($materials) && lp_scan_array($materials)) {
                    ?>
                    <div class="tab-pane clearfix fade" id="tc-materials-list-tab-content" role="tabpanel" aria-labelledby="">
                        <div class="tc-tab-content">
                        <?php
                         if (!empty(array_filter($materials['url']))) {
                            foreach ($materials['url'] as $materialKey => $material) {
                                $file_response = get_file_type_from_url($material);
                                ?>
                                <div class="form-group">
                                    <label>Material:</label>
                                    <a href="<?php echo $material; ?>" target="_blank"><?php echo $file_response['icon'];?></a>
                                </div>
                                <?php
                                if (isset($materials['title'][$materialKey]) &&
                                    !empty($materials['title'][$materialKey])
                                ) {?>
                                <div class="form-group">
                                    <label>Title:</label>
                                    <span><?php echo $materials['title'][$materialKey];?></span>
                                </div>
                                <?php }?>
                                <?php
                                if (isset($materials['description'][$materialKey]) &&
                                    !empty($materials['description'][$materialKey])
                                ) {?>
                                    <div class="form-group">
                                        <label>Description:</label>
                                        <span><?php echo $materials['description'][$materialKey];?></span>
                                    </div>
                                <?php }
                            }
                        }
                        ?>
                        </div>
                        <button class="tc-read-more">Read More</button>
                    </div>
                    <?php } ?>
                <?php }
            }
        }
        ?>
    </div>
</div>
<?php
	// Display Activity Objects
 	endwhile; 
endif; 
get_footer();
