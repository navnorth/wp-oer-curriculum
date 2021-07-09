<div class="oercurr-resource-selector-overlay animated" style="visibility:hidden;">
    <div class="oercurr-resource-selector-table">
        <div class="oercurr-resource-selector-cell">
            <div class="oercurr-resource-selector-content">    
            <h1>Resources</h1>
            <div class="oercurr-resource-selector-search">
              <input class="oercurr-resource-selector-criteria" placeholder="Search page here." name="oercurr-resource-selector-criteria" type="text" />
            <button class="search_std_btn" data-postid="24652"><span class="dashicons dashicons-search"></span></button>
          </div>
          <div class="oercurr-resource-selector-search-result">
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
              <label class="oercurr-resource-selector-tag-p" data-postid="" data-search-term="">
              <input name="oercurr-resource-selector-rad" title="" type="radio" value=""/>(Select Resource)<span class="relatedResourceSelectorImage dashicons dashicons-yes"></span>
              </label>
              </li>
              <?php
              if (count($posts)) {
                  foreach ($posts as $post) {
                      ?>
                      <li>
                      <label class="oercurr-resource-selector-tag-p" data-postid="<?php echo esc_attr($post->ID); ?>" data-search-term="<?php echo strtolower(esc_html($post->post_title)); ?>">
                      <input name="oercurr-resource-selector-rad" title="<?php echo esc_attr($post->post_title); ?>" type="radio" value="<?php echo esc_attr($post->post_title); ?>" checked />
                               <?php echo  esc_html($post->post_title); ?>
                         <span class="relatedResourceSelectorImage dashicons dashicons-yes"></span>
                      </label>
                      </li>
                      <?php
                  }
              }
            ?>
            </ul>
          </div>
          <div class="oercurr-resource-selector-nav-wrapper">
              <a href="#" class="oercurr-resource-selector-select">Select</a>
          </div>
          <div class="oercurr-resource-selector-search-close">
              <span class="fa fa-times"></span>
          </div> 
            </div>    
        </div>
    </div>
</div>