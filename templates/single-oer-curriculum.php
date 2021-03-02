  <?php
  /**
   * The Template for displaying all single Curriculum
   */

  /**
   * Enqueue the assets
   */
  wp_enqueue_style('oer-curriculum-load-fa', OER_LESSON_PLAN_URL.'lib/font-awesome/css/all.min.css');
  wp_enqueue_style('oer-curriculum-bootstrap', OER_LESSON_PLAN_URL.'lib/bootstrap-3.3.7/css/bootstrap.min.css');
  wp_enqueue_script('oer-curriculum-frontend', OER_LESSON_PLAN_URL.'js/frontend/oer-curriculum.js', array('jquery'), null, true);
  wp_enqueue_script( 'jquery-ui-slider' );

  get_header();

  global $_css_oer;
  global $root_slug;
  if ($_css_oer) {
  $output = "<style>"."\n";
  $output .= $_css_oer."\n";
  $output .="</style>"."\n";
  echo $output;
  }

  global $post;
  global $wpdb;

  $oer_sensitive = false;
  $sensitive_material = null;

  $post_meta_data = get_post_meta($post->ID );
  $elements_orders = isset($post_meta_data['oer_curriculum_order'][0]) ? unserialize($post_meta_data['oer_curriculum_order'][0]) : array();

  //Grade Level
  $oer_curriculum_grade = isset($post_meta_data['oer_curriculum_grades'][0])? unserialize($post_meta_data['oer_curriculum_grades'][0])[0]:"";
  if ($oer_curriculum_grade!=="pre-k" && $oer_curriculum_grade!=="k")
      $oer_curriculum_grade = "Grade ".$oer_curriculum_grade;
      
  // Download Copy
  $oer_curriculum_download_copy_document = (isset($post_meta_data['oer_curriculum_download_copy_document'][0]) ? $post_meta_data['oer_curriculum_download_copy_document'][0] : '');
  $oer_curriculum_standards = isset($post_meta_data['oer_curriculum_standards'][0])?$post_meta_data['oer_curriculum_standards'][0]:"";
  $oer_curriculum_related_objectives = isset($post_meta_data['oer_curriculum_related_objective'][0])? unserialize($post_meta_data['oer_curriculum_related_objective'][0]): array('');
  $tags = get_the_terms($post->ID,"post_tag");
  $authors = (isset($post_meta_data['oer_curriculum_authors'][0]) ? unserialize($post_meta_data['oer_curriculum_authors'][0]) : array());

  // check if there is a resource with sensitive material set
  $oer_resources = (isset($post_meta_data['oer_curriculum_primary_resources'][0]) ? unserialize($post_meta_data['oer_curriculum_primary_resources'][0]) : array());

  if (isset($oer_resources['sensitive_material']))
      $sensitive_material = $oer_resources['sensitive_material'];
      
  if (!empty($sensitive_material) && count($sensitive_material)>0) {
      $oer_sensitive = true;
  }

  $oer_curriculum_type_set = (get_option('oer_curriculum_type_label'))?true:false;
  $oer_curriculum_type_enabled = (get_option('oer_curriculum_type_enabled'))?true:false;
  $related_curriculum_label = (get_option('oer_curriculum_related_curriculum_label'))?true:false;
  $related_curriculum_enabled = (get_option('oer_curriculum_related_curriculum_enabled'))?true:false;
  $author_set = (get_option('oer_curriculum_authors_label'))?true:false;
  $author_enabled = (get_option('oer_curriculum_authors_enabled'))?true:false;
  $standards_set = (get_option('oer_curriculum_standards_label'))?true:false;
  $standards_enabled = (get_option('oer_curriculum_standards_enabled'))?true:false;
  $objectives_set = (get_option('oer_curriculum_related_objective_label'))?true:false;
  $objectives_enabled = (get_option('oer_curriculum_related_objective_enabled'))?true:false;
  $age_levels_set = (get_option('oer_curriculum_age_levels_label'))?true:false;
  $age_levels_enabled = (get_option('oer_curriculum_age_levels_enabled'))?true:false;
  $suggested_time_set = (get_option('oer_curriculum_suggested_instructional_time_label'))?true:false;
  $suggested_time_enabled = (get_option('oer_curriculum_suggested_instructional_time_enabled'))?true:false;
  $req_materials_set = (get_option('oer_curriculum_required_materials_label'))?true:false;
  $req_materials_enabled = (get_option('oer_curriculum_required_materials_enabled'))?true:false;
  $addtl_materials_set = (get_option('oer_curriculum_required_materials_label'))?true:false;
  $addtl_materials_enabled = (get_option('oer_curriculum_required_materials_enabled'))?true:false;
  $iq_set = (get_option('oer_curriculum_required_materials_label'))?true:false;
  $iq_enabled = (get_option('oer_curriculum_required_materials_enabled'))?true:false;

  if (have_posts()) : while (have_posts()) : the_post();
      
  ?>
  <div class="container">
      <div class="row oer-curriculum-featured-section">
          
          <div class="row tc-oer-curriculum-details-content">
              <div class="col-md-8 col-sm-12 col-xs-12 curriculum-detail padding-left-0">
                <div class="row tc-oer-curriculum-details-header">
                    <h1 class="tc-oer-curriculum-title"><?php echo the_title(); ?></h1>
                </div>
              </div>
              <div class="col-md-4 col-sm-12 featured-image padding-right-0">
                <?php
                if (function_exists('oer_breadcrumb_display'))
                    echo oer_breadcrumb_display();
                ?>
              </div>
            
              <div class="col-md-8 col-sm-12 col-xs-12 curriculum-detail padding-left-0">
                  <div class="tc-oer-curriculum-details">
                      <?php if (($oer_curriculum_type_set && $oer_curriculum_type_enabled) || !$oer_curriculum_type_set) { ?>
                      <div class="tc-oer-curriculum-type">
                          <?php
                          $_tclptype='';
                          if(isset($post_meta_data['oer_curriculum_type'][0])){
                            $_tclptype = $post_meta_data['oer_curriculum_type'][0];
                            if($_tclptype=='Other' && !empty(trim($post_meta_data['oer_curriculum_type_other'][0], ' '))){
                              $_tclptype = $post_meta_data['oer_curriculum_type_other'][0];
                            }
                          }
                          $oer_curriculum_type = $_tclptype;
                          //$oer_curriculum_type = (isset($post_meta_data['oer_curriculum_type'][0]) ? $post_meta_data['oer_curriculum_type'][0] : '');
                          echo $oer_curriculum_type;
                          ?>
                      </div>
                      <?php } ?>
                      <div class="tc-oer-curriculum-details-description">
                          <?php if (strlen($post->post_content)>360) : ?>
                          <div class="oer-curriculum-excerpt"><?php echo oer_curriculum_content(360); ?></div>
                          <div class="oer-curriculum-full-content"><?php echo the_content(); ?> <a href="javascript:void(0);" class="oer-curriculum-read-less">(read less)</a></div>
                          <?php else : ?>
                          <div class="oer-curriculum-content"><?php echo the_content(); ?></div>
                          <?php endif; ?>
                      </div>
                      <?php
                      $related_curriculum_collection = (isset($post_meta_data['oer_curriculum_related_curriculum'][0]) ? unserialize($post_meta_data['oer_curriculum_related_curriculum'][0]) : array());
                      $show_curriculum_section = false;
                      foreach($related_curriculum_collection as $rset){
                          if ($rset!=="0"){
                              $show_curriculum_section = true;
                              break;
                          }
                      }
                      if ($show_curriculum_section) {
                          if (($related_curriculum_label && $related_curriculum_enabled) || !$related_curriculum_label) {
                      ?>
                      <div class="tc-related-curriculum-section">
                          <a href="#collapse_oer_curriculum_related_curriculum" data-toggle="collapse" class="tc_oer_curriculum_collapse_button collapsed" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <h4 class="tc-related-curriculum-section-heading clearfix">
                                <span class="oer_curriculum_related_fields"><?php echo oer_curriculum_get_field_label('oer_curriculum_related_curriculum'); ?></span><span class="oer_curriculum_acicon"></span>
                            </h4>
                          </a>
                          <div class="tc-related-curriculum-details clearfix collapse" id="collapse_oer_curriculum_related_curriculum">
                              <ul class="tc-related-curriculum-list">
                              <?php
                              foreach($related_curriculum_collection as $inquiry_set) {
                                  if ($inquiry_set!=="0") {
                                      $inquiry = oer_curriculum_get_inquiry_set_details($inquiry_set);
                                      $inquiry_link = get_permalink($inquiry_set);
                                      
                                      echo '<li><a href="'.$inquiry_link.'">'.$inquiry->post_title.'</a></li>';
                                  }
                              } ?>
                              </ul>
                          </div>
                      </div>
                      <?php }
                      } ?>
                      <?php if (!empty($oer_curriculum_standards)) {
                           if (($standards_set && $standards_enabled) || !$standards_set) {
                      ?>
                      <div class="tc-oer-curriculum-standards">
                          <a href="#collapse_oer_curriculum_standards" data-toggle="collapse" class="tc_oer_curriculum_collapse_button collapsed" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <h4 class="tc-oer-curriculum-field-heading clearfix">
                                <span class="oer_curriculum_related_fields"><?php echo oer_curriculum_get_field_label('oer_curriculum_standards'); ?></span><span class="oer_curriculum_acicon"></span>
                            </h4>
                          </a>
                          <div class="tc-oer-curriculum-standards-details clearfix collapse" id="collapse_oer_curriculum_standards">
                              <ul class="tc-oer-curriculum-standards-list">
                                  <?php
                                  $stds = array();
                                  $standards = array();
                                  $cstandard = null;
                                  $oer_curriculum_standards = explode(",",$oer_curriculum_standards);
                                  if (is_array($oer_curriculum_standards)):
                                      $current_std_id = "";
                                      foreach($oer_curriculum_standards as $standard){
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
                                                  echo '<a class="oer-curriculum-standard-toggle" data-toggle="collapse" href="#core-standard-'.$std['core_standard_id'].'">'.$std['core_standard_name'].' <i class="fas fa-caret-right"></i></a>';
                                              ?>
                                              <div class="collapse tc-oer-curriculum-details-standard" id="core-standard-<?php echo $std['core_standard_id']; ?>">
                                              <?php
                                              if (is_array($std['notation'])) {
                                                  echo "<ul class='tc-oer-curriculum-notation-list'>";
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
                          $post_terms = get_the_terms( $post->ID, 'resource-subject-area' );
                          if (!empty($post_terms)) {
                      ?>
                      <div class="tc-oer-curriculum-subject-areas">
                         <a href="#collapse_oer_curriculum_subjects" class="tc_oer_curriculum_collapse_button collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
                           <h4 class="tc-oer-curriculum-field-heading clearfix">
                                <span class="oer_curriculum_related_fields"><?php _e("Subjects",OER_LESSON_PLAN_SLUG); ?></span><span class="oer_curriculum_acicon"></span>
                            </h4>
                         </a>
                         <div class="tc-oer-curriculum-subject-details clearfix collapse" id="collapse_oer_curriculum_subjects">
                              <ul class="tc-oer-curriculum-subject-areas-list">
                                  <?php
                                  $i = 1;
                                  $cnt = count($post_terms);
                                  $moreCnt = $cnt - 2;
                                  foreach($post_terms as $term){
                                      $subject_parent = get_term_parents_list($term->term_id,'resource-subject-area', array('separator' => ' <i class="fas fa-angle-double-right"></i> ', 'inclusive' => false));
                                      $subject = $subject_parent . '<a href="'.get_term_link($term->term_id).'">'.$term->name.'</a>';
                                      if ($i>2)
                                          echo '<li class="collapse oer-curriculum-subject-hidden">'.$subject.'</li>';
                                      else
                                          echo '<li>'.$subject.'</li>';
                                      if (($i==2) && ($cnt>2))
                                          echo '<li><a class="see-more-subjects" data-toggle="collapse" data-count="'.$moreCnt.'" href=".oer-curriculum-subject-hidden">SEE '.$moreCnt.' MORE +</a></li>';
                                      $i++;
                                  }
                                  ?>
                              </ul>
                          </div>
                      </div>
                      <?php } ?>
                      
                      
                      <?php 
                      if (($objectives_set && $objectives_enabled) || !$standards_set):
                      
                        $_tmp_html = ''; $_cnt = 1;
                        foreach($oer_curriculum_related_objectives as $_obj):
                          if(trim($_obj,' ') > ''):
                            $_tmp_html .= '<li>'.$_cnt++.') '.$_obj.'</li>';
                          endif;
                        endforeach;            
                        if ($_tmp_html > ''): ?>
                        <div class="tc-oer-curriculum-objectives">
                          <a href="#collapse_oer_curriculum_objectives" class="tc_oer_curriculum_collapse_button collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <h4 class="tc-oer-curriculum-field-heading clearfix"><span class="oer_curriculum_related_fields"><?php echo oer_curriculum_get_field_label('oer_curriculum_related_objective'); ?></span><span class="oer_curriculum_acicon"></span></h4>
                          </a>                
                          <div class="tc-oer-curriculum-objectives-details clearfix collapse" id="collapse_oer_curriculum_objectives">
                              <ul class="tc-oer-curriculum-objectives-list"><?php echo $_tmp_html; ?></ul>
                          </div>
                        </div>
                        <?php endif; ?>
                      <?php endif; ?>
                      
                      <div id="tcHiddenFields" class="tc-hidden-fields collapse">
                          <?php
                          // Grade Level Display
                          $oer_curriculum_grade = oer_curriculum_grade_level($post->ID);
                          if (!empty($oer_curriculum_grade)){
                              ?>
                              <div class="form-field">
                                  <span class="tc-oer-curriculum-label">Grade Level:</span> <span class="tc-oer-curriculum-value"><?php echo $oer_curriculum_grade; ?></span>
                              </div>
                              <?php
                          }

                          // Investigative Question
                          if (($iq_set && $iq_enabled) || !$iq_set) {
                              $iq_label = oer_curriculum_get_field_label('oer_curriculum_iq_label');
                              $iq_data = (isset($post_meta_data['oer_curriculum_iq'][0]) ? unserialize($post_meta_data['oer_curriculum_iq'][0]) : "");
                              if (!empty($iq_data)){
                              ?>
                              <div class="form-field">
                                  <div><span class="tc-oer-curriculum-label"><?php echo $iq_data['question']; ?></span></div>
                                  <div><span class="tc-oer-curriculum-value"><?php echo $iq_data['excerpt']; ?></span></div>
                              </div>
                              <?php
                              }
                          }

                          // Appropriate Age Levels Display
                          if (($age_levels_set && $age_levels_enabled) || !$age_levels_set) {
                              $age_label = oer_curriculum_get_field_label('oer_curriculum_age_levels');
                              $age_levels = (isset($post_meta_data['oer_curriculum_age_levels'][0]) ? $post_meta_data['oer_curriculum_age_levels'][0] : "");
                              if (!empty($age_levels)){
                              ?>
                              <div class="form-field">
                                  <span class="tc-oer-curriculum-label"><?php echo $age_label; ?>:</span> <span class="tc-oer-curriculum-value"><?php echo $age_levels; ?></span>
                              </div>
                              <?php
                              }
                          }
                          
                          // Suggested Instructional Time Display
                         if (($suggested_time_set && $suggested_time_enabled) || !$suggested_time_set) {
                              $suggested_label = oer_curriculum_get_field_label('oer_curriculum_suggested_instructional_time');
                              $suggested_time = (isset($post_meta_data['oer_curriculum_suggested_instructional_time'][0]) ? $post_meta_data['oer_curriculum_suggested_instructional_time'][0] : "");
                              if (!empty($suggested_time)){
                              ?>
                              <div class="form-field">
                                  <span class="tc-oer-curriculum-label"><?php echo $suggested_label; ?>:</span> <span class="tc-oer-curriculum-value"><?php echo $suggested_time; ?></span>
                              </div>
                              <?php
                              }
                          }
                          
                          
                          // Required Equipment Materials Display
                          if (($req_materials_set && $req_materials_enabled) || !$req_materials_set) {
                              $req_materials_label = (isset($post_meta_data['oer_curriculum_required_materials_label'][0]) ? $post_meta_data['oer_curriculum_required_materials_label'][0] : "Required Materials");
                              $req_materials = (isset($post_meta_data['oer_curriculum_required_materials'][0]) ? unserialize($post_meta_data['oer_curriculum_required_materials'][0]) : "");
                              if (!empty($req_materials)){
                                $cnt = 0;
                                if (isset($req_materials['label']))
                                    $cnt = count($req_materials['label']);
                                if (isset($req_materials['editor'])){
                                    $cnt = (count($req_materials['editor'])>$cnt) ? count($req_materials['editor']) : $cnt;
                                }
                                for ($i=0;$i<$cnt;$i++){
                                  if (!empty($req_materials['label'][$i]) || !empty($req_materials['editor'][$i])) {
                                  ?>
                                  <div class="form-field">
                                      <span class="tc-oer-curriculum-label-heading"><?php echo $req_materials['label'][$i]; ?>:</span> <span class="tc-oer-curriculum-value"><?php echo $req_materials['editor'][$i]; ?></span>
                                  </div>
                                  <?php
                                  }
                                }
                              }
                          }

                          // Additional Materials Display
                          if (($addtl_materials_set && $addtl_materials_enabled) || !$addtl_materials_set) {
                              $addtl_materials_label = (isset($post_meta_data['oer_curriculum_oer_materials_label'][0]) ? $post_meta_data['oer_curriculum_oer_materials_label'][0] : "Additional Materials");
                              $addtl_materials = (isset($post_meta_data['oer_curriculum_oer_materials'][0]) ? unserialize($post_meta_data['oer_curriculum_oer_materials'][0]) : array());
                              if (!empty($addtl_materials)){
                              ?>
                              <div class="form-field">
                                  <span class="tc-oer-curriculum-label"><?php echo $addtl_materials_label; ?>:</span>
                                  <?php 
                                  $cnt = 0;
                                  if (isset($addtl_materials['title']))
                                    $cnt = count($addtl_materials['title']);
                                  if (isset($addtl_materials['url'])){
                                    $cnt = (count($addtl_materials['url'])>$cnt) ? count($addtl_materials['url']) : $cnt;
                                  }
                                  if (isset($addtl_materials['description'])){
                                    $cnt = (count($addtl_materials['description'])>$cnt) ? count($addtl_materials['description']) : $cnt;
                                  }
                                  echo "<ul>";
                                  for ($i=0;$i<$cnt;$i++){
                                    if (!empty($addtl_materials['title'][$i]) || !empty($addtl_materials['url'][$i]) || !empty($addtl_materials['description'][$i])) {
                                    ?>
                                    <li>
                                    <div class="form-field">
                                      <span class="tc-oer-curriculum-label"><a href="<?php echo $addtl_materials['url'][$i]; ?>"><?php echo $addtl_materials['title'][$i]; ?></a></span>
                                    </div>
                                    <div>
                                      <span class="tc-oer-curriculum-value"><p><?php echo $addtl_materials['description'][$i]; ?></p></span>
                                    </div>
                                    </li>
                                    <?php
                                    }
                                  } 
                                  echo "</ul>";
                                  ?>
                              </div>
                              <?php
                              }
                          }
                          
                          // Additional Section
                          $additional_sections = isset($post_meta_data['oer_curriculum_additional_sections'][0]) ? unserialize($post_meta_data['oer_curriculum_additional_sections'][0]) : array();
                           if (is_array($additional_sections)){
                              $cnt = 0;
                              if (isset($additional_sections['label']))
                                  $cnt = count($additional_sections['label']);
                              if (isset($additional_sections['editor'])){
                                  $cnt = (count($additional_sections['editor'])>$cnt) ? count($additional_sections['editor']) : $cnt;
                              }
                              for ($i=0;$i<$cnt;$i++){
                                  if (!empty($additional_sections['label'][$i]) || !empty($additional_sections['editor'][$i])) {
                                    ?>
                                  <div class="form-field">
                                      <span class="tc-oer-curriculum-label-heading"><?php echo $additional_sections['label'][$i]; ?>:</span> <span class="tc-oer-curriculum-value"><?php echo $additional_sections['editor'][$i]; ?></span>
                                  </div>
                                    <?php
                                  }
                              }
                           }
                          ?>
                      </div>
                  </div>
              </div>
              <div class="col-md-4 col-sm-12 featured-image padding-right-0">
                  <?php the_post_thumbnail('inquiry-set-featured'); ?>
                  <?php $_feat_info_padding = ($oer_curriculum_download_copy_document)? 'padded-right' : ''; ?>
                  <div class="tc-oer-curriculum-authors-list <?php echo $_feat_info_padding ?>">
                  <?php if (($author_set && $author_enabled) || !$author_set) { ?>
                      <?php
                      $author_display = false;
                      foreach($authors as $author){
                          if (!empty($author[0])){
                              $author_display = true;
                              break;
                          }
                      }
                      if ($author_display){
                          ?>
                           <span class="oer-curriculum-author-label"><?php echo oer_curriculum_get_field_label('oer_curriculum_authors'); ?></span>
                          <?php 
                          $aIndex = 0;
                          
                          foreach($authors['name'] as $author){
                              $author_url = $authors['author_url'][$aIndex];
                              if ($aIndex>0)
                                  echo ", ";
                              if (isset($author_url))
                                  echo "<span class='tc-oer-curriculum-author'><a href='".$author_url."'>".$authors['name'][$aIndex]."</a></span>";
                              else
                                  echo "<span class='tc-oer-curriculum-author'>".$authors['name'][$aIndex]."</span>";
                                  
                              $aIndex++;
                          }
                      } 
                      ?>
                      <?php if ($oer_curriculum_download_copy_document): ?>
                      <div class="tc-oer-curriculum-controls">
                          <div class="sharethis-inline-share-buttons"></div>
                          <a href="<?php echo $oer_curriculum_download_copy_document; ?>" target="_blank"><i class="fa fa-download"></i></a>
                      </div>
                      <?php endif; ?>
                  <?php } ?>
                  </div>
                  <?php if ($oer_sensitive) : ?>
                  <div class="tc-sensitive-material-section">
                      <p><i class="fa fa-exclamation-triangle"></i><span class="sensitive-material-text">Potentially Sensitive Material</span></p>
                      <!--<button class="question-popup-button"><i class="fal fa-question-circle"></i></button>-->
                  </div>
                  <?php endif;
                  
                  $keywords = wp_get_post_tags($post->ID);
                  if(!empty($keywords))
                  {
                  ?>
                  <div class="tc-oer-curriculum-keywords <?php echo $_feat_info_padding ?>">
                      <div class="oer_curriculum_keywords_container tagcloud">
                      <?php
                          foreach($keywords as $keyword)
                          {
                                  echo "<span><a href='".esc_url(get_tag_link($keyword->term_id))."' class='button'>".ucwords($keyword->name)."</a></span>";
                          }
                      ?>
                      </div>
                  </div>
                  <?php } ?>
              </div>
              
          </div>
      </div>
      <div class="see-more-row">
          <p class="center"><span><a id="see-more-link" class="see-more-link" role="button" data-toggle="collapse" href="#tcHiddenFields" aria-expanded="false" aria-controls="tcHiddenFields"><?php _e("SEE MORE +",OER_LESSON_PLAN_SLUG); ?></a></span></p>
      </div>
      <div class="row oer-curriculum-primary-sources-row">
          <?php
          $primary_resources = (isset($post_meta_data['oer_curriculum_primary_resources'][0]) ? unserialize($post_meta_data['oer_curriculum_primary_resources'][0]) : array());
          if (!empty($primary_resources) && oer_curriculum_scan_array($primary_resources)) {
              if (!empty(array_filter($primary_resources['resource']))) {
                  $_idx = 0;
                  foreach ($primary_resources['resource'] as $resourceKey => $resource) {
                      $resource = get_page_by_title($resource,OBJECT,"resource");
                      $resource_id = 0;
                      $resource_img = '';
                      $title = '';
                      if (!empty($resource)){
                          $resource_id = $resource->ID;
                          $url = get_post_meta($resource->ID, "oer_resourceurl", true);
                          $type = get_post_meta($resource->ID,"oer_mediatype")[0];
                          $title = $resource->post_title;
                          $_hasimage = has_post_thumbnail($resource);
                          if($_hasimage) $resource_img = wp_get_attachment_image_url( get_post_thumbnail_id($resource), 'resource-thumbnail' );
                          $oer_authorname = get_post_meta($resource->ID, "oer_authorname", true);	
                          $oer_authorurl = get_post_meta($resource->ID, "oer_authorurl", true);	
                          $oer_authorname2 = get_post_meta($resource->ID, "oer_authorname2", true);	
                          $oer_authorurl2 = get_post_meta($resource->ID, "oer_authorurl2", true);
                      }
                          $resource_img = (isset($primary_resources['image'][$resourceKey]) && !empty($primary_resources['image'][$resourceKey])  ? $primary_resources['image'][$resourceKey]: $resource_img);
                          $sensitiveMaterial = (isset($primary_resources['sensitive_material'][$resourceKey]) ? $primary_resources['sensitive_material'][$resourceKey]: "");
                          $sensitiveMaterialValue = (isset($primary_resources['sensitive_material_value'][$resourceKey]) ? $primary_resources['sensitive_material_value'][$resourceKey]: "");
                          $_resource_field_type = (isset($primary_resources['field_type'][$resourceKey]) ? $primary_resources['field_type'][$resourceKey]: "");
                          if(trim($title,' ')=='') $type = 'text';
                          $title = (isset($primary_resources['title'][$resourceKey]) ? $primary_resources['title'][$resourceKey]: "");
                          $description = (isset($primary_resources['description'][$resourceKey]) ? $primary_resources['description'][$resourceKey]: "");
                          if($_resource_field_type == 'resource'){
                            if(trim($primary_resources['title'][$resourceKey],' ')!='' ){
                              $title = $primary_resources['title'][$resourceKey];
                            } else {
                              if (!empty($resource))
                                  $title = $resource->post_title;
                            }
                          }
                          if($_resource_field_type == 'resource'){
                            if(trim($primary_resources['description'][$resourceKey],' ')!='' ){
                              $description = $primary_resources['description'][$resourceKey];
                            }else{
                              $description = $resource->post_content;
                            }
                          }
                          //$description = ($_resource_field_type != 'textbox' && trim($primary_resources['description'][$resourceKey],' ')!='' ) ? $primary_resources['description'][$resourceKey]: $resource->post_content;
                          if ($sensitiveMaterialValue!=="") $sensitiveMaterial = $sensitiveMaterialValue;  
                          
                          
                        //  echo print_r($primary_resources);
                  ?>
                      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 padding-0">
                          <div class="media-image">
                              <div class="image-thumbnail">
                                  <?php $ps_url = site_url($root_slug."/".sanitize_title($post->post_name)."/source/".sanitize_title($title)."-".$resource_id)."/idx/".$_idx++; ?>
                                  <a href="<?php echo $ps_url;  ?>">
                                      <?php if($resource_img==''): $_avtr = getResourceIcon($type,$url); ?>	
                                        <div class="resource-avatar"><span class="dashicons <?php echo $_avtr; ?>"></span></div>	
                                      <?php endif; ?>
                                      <span class="resource-overlay"></span>
                                      <?php if (!empty($type)): ?>
                                      <span class="oer-curriculum-source-type"><?php echo ucwords($type); ?></span>
                                      <?php endif; ?>
                                      <div class="resource-thumbnail" style="background: url('<?php echo $resource_img ?>') no-repeat center rgba(204,97,12,.1); background-size:cover;">
                                      </div>
                                      <?php if ($sensitiveMaterial!=="" && $sensitiveMaterial!=="no"): ?>
                                      <div class="sensitive-source">
                                          <p><i class="fa fa-exclamation-triangle"></i></p>
                                      </div>
                                      <?php endif; ?>
                                  </a>
                                  
                              </div>
                              <div class="oer-curriculum-resource-info">
                                <div class="oer-curriculum-resource-title"><?php echo $title; ?></div>	
                                <div class="oer-curriculum-resource-author">	
                                  <?php if( $oer_authorname != ''):?>	
                                    <div class="oer-curriculum-resource-author_block"><a href="<?php echo $oer_authorurl; ?>" target="_new"><?php echo $oer_authorname; ?></a></div>	
                                  <?php endif; ?>	
                                  <?php /* if( $oer_authorname2 != ''):?>	
                                    <div class="oer-curriculum-resource-author_block"><a href=""><?php echo $oer_authorname2; ?></a></div>	
                                  <?php endif;*/ ?>
                                </div>
                                <div class="oer-curriculum-resource-excerpt"><?php echo oer_get_related_resource_content(strip_tags($description), 50); ?></div>
                              </div>
                          </div>
                      </div>
                  <?php //}
                  }
              }
          }
          if (!empty($elements_orders)) {
              $keys = array(
                  "oer_curriculum_introduction_order",
                  "oer_curriculum_primary_resources",
                  "oer_curriculum_lesson_times_order",
                  "oer_curriculum_industries_order",
                  "oer_curriculum_standard_order",
                  "oer_curriculum_activities_order",
                  "oer_curriculum_summative_order",
                  "oer_curriculum_authors_order",
                  "oer_curriculum_iq",
                  "oer_curriculum_oer_materials"
              );
              foreach($elements_orders as $elementKey=>$order){
                  if (!in_array($elementKey,$keys)){
                      if (strpos($elementKey, 'oer_curriculum_custom_editor_historical_background') !== false) {
                          $oer_curriculum_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                          if(!empty($oer_curriculum_custom_editor)) {
                              
                          ?>
                          <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 padding-0">
                              <div class="media-image">
                                  <div class="image-thumbnail">
                                      <?php  $ps_url = site_url($root_slug."/".sanitize_title($post->post_name)."/module/".sanitize_title($oer_curriculum_custom_editor['title'])); ?>
                                      <a href="<?php echo $ps_url;  ?>">
                                          <div class="resource-avatar"><span class="dashicons dashicons-media-text"></span></div>
                                          <span class="resource-overlay"></span>
                                          <?php if (!empty($type)): ?>
                                          <span class="oer-curriculum-source-type"><?php _e("Text", OER_LESSON_PLAN_SLUG); ?></span>
                                          <?php endif; ?>
                                          <div class="resource-thumbnail text-thumbnail"></div>
                                      </a>
                                  </div>
                                  <div class="oer-curriculum-resource-info">
                                    <div class="oer-curriculum-resource-title">
                                        <?php echo $oer_curriculum_custom_editor['title']; ?>
                                    </div>
                                  </div>
                              </div>
                          </div>
                          <?php
                          }
                      } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_custom_editor_') !== false) {
                          $oer_curriculum_custom_editor = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                          if(!empty($oer_curriculum_custom_editor)) {
                          ?>
                          <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 padding-0">
                              <div class="media-image">
                                  <div class="image-thumbnail">
                                      <?php  $ps_url = site_url($root_slug."/".sanitize_title($post->post_name)."/module/".sanitize_title($oer_curriculum_custom_editor['title'])); ?>
                                      <a href="<?php echo $ps_url;  ?>">
                                          <div class="resource-avatar"><span class="dashicons dashicons-media-text"></span></div>
                                          <span class="resource-overlay"></span>
                                          <?php if (!empty($type)): ?>
                                          <span class="oer-curriculum-source-type"><?php _e("Text", OER_LESSON_PLAN_SLUG); ?></span>
                                          <?php endif; ?>
                                          <div class="resource-thumbnail text-thumbnail"></div>
                                      </a>
                                  </div>
                                  <div class="oer-curriculum-resource-info">
                                    <div class="oer-curriculum-resource-title">
                                        <?php echo $oer_curriculum_custom_editor['title']; ?>
                                    </div>
                                  </div>
                              </div>
                          </div>
                          <?php } ?>
                      <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_custom_text_list_') !== false) {
                      ?>
                      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 padding-0">
                          <div class="media-image">
                              <div class="image-thumbnail">
                                  <?php  $ps_url = site_url($root_slug."/".sanitize_title($post->post_name)."/module/".sanitize_title("Text List")); ?>
                                  <a href="<?php echo $ps_url;  ?>">
                                      <div class="resource-avatar"><span class="dashicons dashicons-media-text"></span></div>
                                      <span class="resource-overlay"></span>
                                      <?php if (!empty($type)): ?>
                                      <span class="oer-curriculum-source-type"><?php _e("Text", OER_LESSON_PLAN_SLUG); ?></span>
                                      <?php endif; ?>
                                      <div class="resource-thumbnail text-thumbnail"></div>
                                  </a>
                              </div>
                              <div class="oer-curriculum-resource-info">
                                <div class="oer-curriculum-resource-title">
                                    <?php _e("Text List", OER_LESSON_PLAN_SLUG); ?>
                                </div>
                              </div>
                          </div>
                      </div>
                      <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_vocabulary_list_title_') !== false) {
                          $oer_curriculum_vocabulary_list_title = (isset($post_meta_data[$elementKey][0]) ? $post_meta_data[$elementKey][0] : "");
                          $oer_keys = explode('_', $elementKey); 
                          $listOrder = end($oer_keys);
                          $oer_curriculum_vocabulary_details = (isset($post_meta_data['oer_curriculum_vocabulary_details_'.$listOrder][0]) ? $post_meta_data['oer_curriculum_vocabulary_details_'.$listOrder][0] : "");
                          if (!empty($oer_curriculum_vocabulary_list_title)) { ?>
                          <div class="col-md-3 col-sm-3 padding-0">
                              <div class="media-image">
                                  <div class="image-thumbnail">
                                      <?php  $ps_url = site_url($root_slug."/".sanitize_title($post->post_name)."/module/".sanitize_title($oer_curriculum_vocabulary_list_title)); ?>
                                      <a href="<?php echo $ps_url;  ?>">
                                          <div class="resource-avatar"><span class="dashicons dashicons-media-text"></span></div>
                                          <span class="resource-overlay"></span>
                                          <?php if (!empty($type)): ?>
                                          <span class="oer-curriculum-source-type"><?php _e("Text", OER_LESSON_PLAN_SLUG); ?></span>
                                          <?php endif; ?>
                                          <div class="resource-thumbnail text-thumbnail"></div>
                                      </a>
                                  </div>
                                  <div class="oer-curriculum-resource-info">
                                    <div class="oer-curriculum-resource-title">
                                        <?php echo $oer_curriculum_vocabulary_list_title; ?>
                                    </div>
                                  </div>
                              </div>
                          </div>
                          <?php } ?>
                      <?php } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_oer_materials_list_') !== false) {?>
                      <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 padding-0">
                          <div class="media-image">
                              <div class="image-thumbnail">
                                  <?php  $ps_url = site_url($root_slug."/".sanitize_title($post->post_name)."/module/".sanitize_title($oer_curriculum_vocabulary_list_title)); ?>
                                  <a href="<?php echo $ps_url;  ?>">
                                      <div class="resource-avatar"><span class="dashicons dashicons-media-text"></span></div>
                                      <span class="resource-overlay"></span>
                                      <?php if (!empty($type)): ?>
                                      <span class="oer-curriculum-source-type"><?php _e("Text", OER_LESSON_PLAN_SLUG); ?></span>
                                      <?php endif; ?>
                                      <div class="resource-thumbnail text-thumbnail"></div>
                                  </a>
                              </div>
                              <div class="oer-curriculum-resource-info">
                                <div class="oer-curriculum-resource-title">
                                    <?php _e("Materials", OER_LESSON_PLAN_SLUG); ?>
                                </div>
                              </div>
                          </div>
                      </div>
                      <?php
                      }
                  }
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
