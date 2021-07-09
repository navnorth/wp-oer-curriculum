<?php

add_filter('body_class', function($classes){
    $classes[] = 'primary-source-template';
    return $classes;
});

get_header();

global $_css_oer;
if ($_css_oer) {
$output = "<style>"."\n";
$output .= $_css_oer."\n";
$output .="</style>"."\n";
echo esc_html($output);
}

$back_url = "";
$back_source_url = "";
$source_id = 0;
$oer_curriculum_prev_class = "";
$oer_curriculum_next_class = "";
$prev_url = "";
$next_url = "";

// Back Button URL
$curriculum = get_query_var('curriculum');

if(is_numeric($curriculum)){ //Draft Preview
  $curriculum_details = get_post($curriculum);
  $curriculum_id = $curriculum_details->ID;
  if ($curriculum)
      $back_source_url = site_url($root_slug."/".$curriculum);
      //Permalink Structure Consideration
      $back_url = site_url("/?post_type=oer-curriculum&p=".$curriculum."&preview=true");
}else{ //Published
  $curriculum_details = get_page_by_path($curriculum, OBJECT, "oer-curriculum");
  $curriculum_id = $curriculum_details->ID;  
  if ($curriculum)
      $back_source_url = site_url($root_slug."/".$curriculum);
      //Permalink Structure Consideration
      $back_url = site_url($root_slug."/".$curriculum);
}

// Get Resource ID
$psource = get_query_var('source');
$psindex = get_query_var('idx');
$sources = explode("-",$psource);
if ($sources)
    $source_id = $sources[count($sources)-1];

$resource = get_post($source_id);

// Get Featured Image Url
$featured_image_url = get_the_post_thumbnail_url($resource->ID, "full");
$resource_url = get_post_meta($resource->ID, "oer_resourceurl", true);
$oer_resource_url = get_the_permalink($resource->ID);
$youtube = oer_is_youtube_url($resource_url);
$isPDF = oer_is_pdf_resource($resource_url);

// Get Curriculum Meta for Primary Sources
$post_meta_data = get_post_meta($curriculum_id);
$primary_resources = (isset($post_meta_data['oer_curriculum_primary_resources'][0]) ? unserialize($post_meta_data['oer_curriculum_primary_resources'][0]) : array());
$index = 0;
$teacher_info = "";
$student_info = "";
$embed = "";
$prev_url = null;
$prev_image = "";
$next_url = null;
$right_class = "col-md-12";
$left_class = "col-md-12";
$new_title = "";
$new_description = "";
$prev_title = "";
$next_title = "";
$next_image = "";
$prev_resource = "";
$next_resource = "";
if (!empty($primary_resources) && oercurr_scan_array($primary_resources)) {
    if (!empty(array_filter($primary_resources['resource']))) {
        foreach ($primary_resources['resource'] as $resourceKey => $source) {            
            if ($psindex == $resourceKey){                
                if(!empty($primary_resources['image'][$resourceKey])){ // Image override
                  $featured_image_url = $primary_resources['image'][$resourceKey];
                }
                $new_title = (isset($primary_resources['title'][$resourceKey]) ? $primary_resources['title'][$resourceKey]: "");
                $new_description = (isset($primary_resources['description'][$resourceKey]) ? $primary_resources['description'][$resourceKey]: "");
                break;
            }
            $index++;
        }
        if (isset($primary_resources['resource'][$index-1])){
            $prev_resource = oercurr_get_resource_details($primary_resources['resource'][$index-1]);
            $prev_title = (isset($primary_resources['title'][$index-1]) ? $primary_resources['title'][$index-1]: "");
            $prev_image = (isset($primary_resources['image'][$index-1]) ? $primary_resources['image'][$index-1]: "");
            if (is_object($prev_resource))
                $prev_url = $back_source_url."/source/".sanitize_title($prev_resource->post_title)."-".$prev_resource->ID.'/idx/'.($index-1);
            else
                $prev_url = $back_source_url."/source/".sanitize_title($prev_title)."-0/idx/".($index-1);
        }
        if (isset($primary_resources['resource'][$index+1])){
            $next_resource = oercurr_get_resource_details($primary_resources['resource'][$index+1]);
            $next_title = (isset($primary_resources['title'][$index+1]) ? $primary_resources['title'][$index+1]: "");
            $next_image = (isset($primary_resources['image'][$index+1]) ? $primary_resources['image'][$index+1]: "");
            if (is_object($next_resource))
                $next_url = $back_source_url."/source/".sanitize_title($next_resource->post_title)."-".$next_resource->ID.'/idx/'.($index+1);
            else
                $next_url = $back_source_url."/source/".sanitize_title($next_title)."-0/idx/".($index+1);
              
        }
        if ($index==0)
            $oer_curriculum_prev_class = "ps-nav-hidden";
        if ($index==count($primary_resources['resource'])-1)
            $oer_curriculum_next_class = "ps-nav-hidden";
        if (isset($primary_resources['teacher_info']))
            $teacher_info = $primary_resources['teacher_info'][$index];
        if (isset($primary_resources['student_info']))
            $student_info = $primary_resources['student_info'][$index];
    }
}
if ($youtube || $isPDF)
    $featured_image_url = "";
if (function_exists('oer_get_resource_metadata')){
    $resource_meta = oer_get_resource_metadata($resource->ID);
}

$type = get_post_meta($resource->ID,"oer_mediatype");
$type = (isset($type[0]))?$type[0]:'textbox';
?>
<?php 
  $_oercurr_allowed_html = oercurr_allowed_html();
  //Breadcrumb trail 
  $sup = (!empty($new_title))? $new_title : $resource->post_title; 
  $ret = '<div class="wp_oer_breadcrumb">'; 
  $ret .= '<a href="'.esc_url(get_site_url()).'">Home</a>'; 
  $cur = (strlen($curriculum_details->post_title) > 30)? substr($curriculum_details->post_title, 0, 30).'...' : $curriculum_details->post_title; 
  $ret .= ' / <a href="'.esc_url($back_url).'">'.$cur.'</a>'; 
  $res = (strlen($sup) > 30)? substr($sup, 0, 30).'...' : $sup; 
  $ret .= ' / '.$res; 
  $ret .= '</div>'; 
  echo wp_kses($ret,$_oercurr_allowed_html); 
?> 
<div class="oercurr-nav-block"><a class="back-button" href="<?php echo esc_url($back_url); ?>"><i class="fas fa-arrow-left"></i><?php echo esc_html($curriculum_details->post_title); ?></a></div>
<div class="row ps-details-row">
    <?php if (!empty($featured_image_url) || $youtube || $isPDF) {
        $right_class = "col-md-8";
        $left_class = "col-md-4";

        if ($isPDF){
            $right_class = "col-md-5";
            $left_class = "col-md-7";
        }
    ?>
    <div class="ps-media-image <?php echo esc_attr($left_class); ?> col-sm-12" data-curid="<?php echo esc_attr($index); ?>">
        <?php if ($youtube): ?>
        <div class="ps-youtube-video">
            <?php
                echo '<div class="youtubeVideoWrapper">';
                if (function_exists('oer_generate_youtube_embed_code'))
                    $embed = oer_generate_youtube_embed_code($resource_url);
                echo esc_url($embed);
                echo '</div>';
            ?>
        </div>
        <?php elseif ($isPDF): ?>
        <div class="ps-pdf-block">
            <?php
                echo '<div class="psPDFWrapper">';
                if (function_exists('oer_display_pdf_embeds'))
                    oer_display_pdf_embeds($resource_url);
                echo '</div>';
            ?>
        </div>
        <?php else: ?>
        <div class="ps-image-block">
           <?php if (isset($resource_url)) { ?>
           <a href="<?php echo esc_url($resource_url); ?>" target="_blank"><img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php echo esc_attr($resource->post_title); ?>" /></a>
           <?php }  else { ?>
           <img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php echo esc_attr($resource->post_title); ?>" />
           <?php } ?>
        </div>
        <?php if ($type=="website"): ?>
        <span class="ps-expand"><a href="<?php echo esc_url($resource_url); ?>" class="oercurr-expand-img" target="_blank"><i class="fas fa-external-link-alt"></i></a></span>
        <?php endif; ?>
        <?php endif; ?>
        <div class="oercurr-center">
            <?php if (isset($resource_url)) { ?>
            <div class="ps-meta-group ps-resource-url">
                <a href="<?php echo esc_url($resource_url); ?>" class="tc-view-button" target="_blank"><?php esc_html_e("View Item", OERCURR_CURRICULUM_SLUG); ?></a>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php
    } else {
        $media_type = get_post_meta($resource->ID, "oer_mediatype")[0];
        if (!empty($resource_url)){
            $right_class = "col-md-8";
        ?>
        <div class="ps-media-image col-md-4 col-sm-12" data-curid="<?php echo esc_attr($index); ?>">
            <div class="oer-sngl-rsrc-img">
                 <?php if (empty($feature_image_url)): ?>
                 <a class="oer-featureimg" href="<?php echo esc_url($resource_url); ?>" target="_blank"><span class="dashicons <?php if (function_exists('oer_getResourceIcon')) echo esc_attr(oer_getResourceIcon($media_type,$resource_url)); ?> nofeat"></span></a>
                <?php endif; ?>
            </div>
            <div class="oercurr-center">
                <?php if (isset($resource_url)) { ?>
                <div class="ps-meta-group ps-resource-url">
                    <a href="<?php echo esc_url($resource_url); ?>" class="tc-view-button" target="_blank"><?php esc_html_e("View Item", OERCURR_CURRICULUM_SLUG); ?></a>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php  
        }
    }
    $resource_meta = null;
    $subject_areas = null;
    ?>
    <div class="ps-details <?php echo esc_attr($right_class); ?> col-sm-12">
        <div class="ps-info">
            <h1 class="ps-info-title"><?php
            if (!empty($new_title))
                echo esc_attr($new_title);
            else
                echo esc_attr($resource->post_title);
            ?></h1>
            <div class="ps-info-description">
                <?php
                if (empty($new_description))
                    echo wp_kses_post($resource->post_content);
                else
                    echo wp_kses_post($new_description);
                ?>
            </div>
        </div>
    </div>
</div>
<div class="ps-related-sources oercurr-primary-sources-row">
    <div class="oercurr-ps-nav-left-block <?php echo esc_attr($oer_curriculum_prev_class); ?> col-md-6 col-sm-12">
        <?php
        $resource_img = (empty($prev_resource))? $prev_image: wp_get_attachment_image_url( get_post_thumbnail_id($prev_resource), 'resource-thumbnail' );
        if (!empty($prev_image))
            $resource_img = $prev_image;
        ?>
        <a class="oercurr-ps-nav-left" href="<?php echo esc_url($prev_url); ?>" data-activetab="" data-id="<?php echo esc_attr($index-1); ?>" data-count="<?php echo esc_attr(count($primary_resources['resource'])); ?>" data-curriculum="<?php echo esc_attr($curriculum_id); ?>" data-prevsource="<?php echo esc_attr($primary_resources['resource'][$index-1]); ?>">
            <span class="col-md-3">&nbsp;</span>
            <span class="nav-media-icon"><i class="fas fa-arrow-left fa-2x"></i></span>
            <span class="nav-media-image col-md-8">
                <span class="nav-image-thumbnail col-md-4">
                    <?php if (!empty($resource_img)): ?>
                      <div class="resource-thumbnail" style="background: url('<?php echo esc_url($resource_img) ?>') no-repeat center rgba(204,97,12,.1); background-size:cover;"></div>
                    <?php else: ?>
                    <?php
                      if($prev_resource == null){
                        $prev_resource_icon = 'dashicons-media-text';
                      }else{
                        $prev_resource_url = get_post_meta($prev_resource->ID, "oer_resourceurl", true);
                        $prev_resource_type = get_post_meta($prev_resource->ID,"oer_mediatype")[0];
                        $prev_resource_icon = oer_getResourceIcon($prev_resource_type,$prev_resource_url);
                      }
                    ?>
                    <div class="navigation-avatar"><span class="dashicons <?php echo esc_attr($prev_resource_icon); ?>"></span></div>
                    <?php endif; ?>
                </span>
                <span class="oercurr-nav-resource-title wow1 col-md-8">
                    <?php
                    if (!empty($prev_title))
                        echo esc_html($prev_title);
                    else
                        echo esc_html($prev_resource->post_title);
                    ?>
                </span>
            </span>
        </a>
    </div>
    <div class="oercurr-ps-nav-right-block <?php echo esc_attr($oer_curriculum_next_class); ?> col-md-6 col-sm-12">
        <?php
        $resource_img = (empty($next_resource))? $next_image: wp_get_attachment_image_url( get_post_thumbnail_id($next_resource), 'resource-thumbnail' );
        if (!empty($next_image))
            $resource_img = $next_image;
        ?>
        <a class="oercurr-ps-nav-right" href="<?php echo esc_url($next_url); ?>" data-activetab="" data-id="<?php echo esc_attr($index+1); ?>" data-count="<?php echo esc_attr(count($primary_resources['resource'])); ?>" data-curriculum="<?php echo esc_attr($curriculum_id); ?>" data-nextsource="<?php echo esc_attr($primary_resources['resource'][$index+1]); ?>">
            <span class="nav-media-image col-md-8">
                <span class="nav-image-thumbnail col-md-4">
                    <?php if (!empty($resource_img)): ?>
                        <div class="resource-thumbnail" style="background: url('<?php echo esc_url($resource_img) ?>') no-repeat center rgba(204,97,12,.1); background-size:cover;"></div>
                    <?php else:
                        if($next_resource == null){
                          $next_resource_icon = 'dashicons-media-text';
                        }else{
                          $next_resource_url = get_post_meta($next_resource->ID, "oer_resourceurl", true);
                          $next_resource_type = get_post_meta($next_resource->ID,"oer_mediatype")[0];
                          $next_resource_icon = oer_getResourceIcon($next_resource_type,$next_resource_url);
                        }
                        ?>
                        <div class="navigation-avatar"><span class="dashicons <?php echo esc_url($next_resource_icon); ?>"></span></div>
                    <?php endif; ?>
                </span>
                <span class="oercurr-nav-resource-title wow2 col-md-8">
                    <?php             
                        if (!empty($next_title))
                            echo esc_html($next_title);
                        else
                            echo esc_html($next_resource->post_title);

                    ?>
                </span>
            </span>
            <span class="nav-media-icon"><i class="fas fa-arrow-right fa-2x"></i></span>
            <span class="col-md-3">&nbsp;</span>
        </a>
    </div>
</div>
<div class="oercurr-ajax-loader" role="status">
    <div class="oercurr-ajax-loader-img">
        <img src="<?php echo esc_url(OERCURR_CURRICULUM_URL."/images/load.gif"); ?>" />
    </div>
</div>
<?php
get_footer();
?>