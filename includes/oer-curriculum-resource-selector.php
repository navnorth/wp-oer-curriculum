<div class="oer-curriculum-resource-selector-overlay animated" style="visibility:hidden;">
    <div class="oer-curriculum-resource-selector-table">
        <div class="oer-curriculum-resource-selector-cell">
            <div class="oer-curriculum-resource-selector-content">    
            <h1>Resources</h1>
            <div class="oer-curriculum-resource-selector-search">
              <input class="oer-curriculum-resource-selector-criteria" placeholder="Search page here." name="oer-curriculum-resource-selector-criteria" type="text" />
            <button class="search_std_btn" data-postid="24652"><span class="dashicons dashicons-search"></span></button>
          </div>
          <div class="oer-curriculum-resource-selector-search-result">
            <ul class="children">
            <?php 
              global $post;
              $posts = get_posts([
                  'post_type' => 'resource',
                  'post_status' => 'publish',
                  'numberposts' => -1,
                  'orderby' => 'title',
                  'order'    => 'ASC'
              ]);
              ?>
              <li>
              <label class="oer-curriculum-resource-selector-tag-p" data-postid="" data-search-term="">
              <input name="oer-curriculum-resource-selector-rad" title="" type="radio" value=""/>(Select Resource)<span class="relatedResourceSelectorImage dashicons dashicons-yes"></span>
              </label>
              </li>
              <?php
              if (count($posts)) {
                  foreach ($posts as $post) {
                      ?>
                      <li>
                      <label class="oer-curriculum-resource-selector-tag-p" data-postid="<?php echo $post->ID; ?>" data-search-term="<?php echo strtolower($post->post_title); ?>">
                      <input name="oer-curriculum-resource-selector-rad" title="<?php echo $post->post_title; ?>" type="radio" value="<?php echo $post->post_title; ?>" checked />
                               <?php echo  $post->post_title; ?>
                         <span class="relatedResourceSelectorImage dashicons dashicons-yes"></span>
                      </label>
                      </li>
                      <?php
                  }
              }
            ?>
            </ul>
          </div>
          <div class="oer-curriculum-resource-selector-nav-wrapper">
              <!--<input type="hidden" name="oer-curriculum-resource-selector-prev-selected" value='<?php echo $post->post_parent; ?>'/> -->
              <a href="#" class="oer-curriculum-resource-selector-select">Select</a>
          </div>
          <div class="oer-curriculum-resource-selector-search-close">
              <span class="fa fa-times"></span>
          </div> 
            </div>    
        </div>
    </div>
</div>