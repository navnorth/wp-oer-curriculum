<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

// Display selected dropdown
if( ! function_exists('oer_curriculum_show_selected')) {
    function oer_curriculum_show_selected($key, $value, $type = 'selectbox') {
        // Check if value is not an array
        if(!is_array($value)) {
            $value =  explode(',', $value);
        }

        if(in_array($key,$value)) {
            if($type == 'checkbox') {
                return 'checked="checked"';
            } else {
                return 'selected="selected"';
            }
        }
        return false;
    }
}

if(!function_exists('prepare_subject_areas')) {
    function prepare_subject_areas($terms) {
        foreach ($terms as $key => $term) {
            //echo "<pre>"; echo $key;print_r($term);
        }
    }
}

if (! function_exists('addSchemeToUrl')) {
    function addSchemeToUrl($url) {
        if (strpos($url, '://') === false) {
            return 'http://' . $url;
        }
    }
}

if (! function_exists('oer_curriculum_display_standards')) {
    /**
     * Get the list of standards and display it
     */
    function oer_curriculum_display_standards() {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "oer_core_standards",ARRAY_A);
        if ($results) { ?>
            <ul class="oer-curriculum-standards-wrapper">
                <?php
                foreach ($results as $result) {
                $coreStandardId = "core_standards-" . $result['id'];
                ?>
                    <li>
                        <a data-toggle="collapse"
                           data-target="#<?php echo $coreStandardId;?>"
                        ><?php echo $result['standard_name']?></a>
                        <?php oer_curriculum_children_standards($coreStandardId);?>
                    </li>
                <?php }?>
            </ul>
        <?php }
    }
}

if (! function_exists('oer_curriculum_children_standards')) {
    /**
     * Get the list all children standards
     * @param $coreStandardId
     */
    function oer_curriculum_children_standards($coreStandardId) {
        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s" , $coreStandardId ) ,ARRAY_A);
        if ($results) {?>
            <div class="collapse oer-curriculum-standard-child-element" id="<?php echo $coreStandardId?>">
                <ul>
                    <?php
                    foreach ($results as $result) {
                        $subStandard = "sub_standards-" . $result['id'];
                        $isNotationsAvailable = is_child_notations_available($subStandard);
                        $isChildStandardAvailable = is_child_standards_available($subStandard);
                        ?>
                        <li>
                            <!--Check if child standards available then get all the children standards-->
                            <?php if (!empty($isChildStandardAvailable)) {?>
                                <a data-toggle="collapse"
                                   data-target="#<?php echo $subStandard;?>"
                                ><?php echo $result['standard_title']?></a>
                                <?php oer_curriculum_children_standards($subStandard);?>
                            <?php }?>

                            <!--Check if notations available for standard then display -->
                            <?php if (!empty($isNotationsAvailable)) {?>
                                <a data-toggle="collapse"
                                   data-target="#<?php echo $subStandard;?>"
                                ><?php echo $result['standard_title']?></a>
                                <?php oer_curriculum_get_standard_notations($subStandard);?>
                            <?php }?>
                        </li>

                    <?php }?>
                </ul>
            </div>
        <?php }
    }
}

if (! function_exists('oer_curriculum_get_standard_notations')) {
    /**
     * Get the standard notations with standard id
     * Display notations recursively
     * @param $parentId
     */
    function oer_curriculum_get_standard_notations($parentId) {
        global $wpdb;
        global $post;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where parent_id = %s" , $parentId ) , ARRAY_A);
        if ($results) {?>
            <div class="collapse oer-curriculum-standard-child-element" id="<?php echo $parentId;?>">
                <ul class="oer_curriculum_notations">
                    <?php
                    // Check if data already saved for current editing post
                    $post_standards = get_post_meta($post->ID, 'oer_oer_curriculum_standards' );
                    $post_standards_array = array();
                    if (isset($post_standards[0]) && !empty($post_standards[0])){
                        $post_standards_array = explode(',', $post_standards[0]);
                    }
                    foreach ($results as $result) {
                    $notationId = "standard_notation-" . $result['id'];
                    $isChildrenAvailable = is_child_notations_available($notationId);
                    ?>
                        <li>
                            <?php
                            if(empty($isChildrenAvailable)) {?>
                                <input type="checkbox"
                                       name="oer_curriculum_oer_notations[]"
                                       class="oer-curriculum-sck"
                                       id="oer-curriculum-standard-check-<?php echo $notationId;?>"
                                       value="<?php echo $notationId;?>"
                                       <?php echo (in_array($notationId, $post_standards_array) ? 'checked="checked"' : "")?>
                                >
                                <label for="oer-curriculum-standard-check-<?php echo $notationId;?>" class="oer-curriculum-scl"><?php echo $result['standard_notation'];?></label>
                                <div class="oer-curriculum-notation-description"><?php echo $result['description'];?></div>
                            <?php } else {?>
                                <a data-toggle="collapse" data-target="#<?php echo $notationId;?>" ><?php echo $result['standard_notation']?></a>
                                <div><?php echo $result['description'];?></div>
                            <?php }?>
                            <?php oer_curriculum_get_standard_notations($notationId);?>
                        </li>
                    <?php }?>
                </ul>
            </div>
        <?php }
    }
}

if (! function_exists('is_child_standards_available')) {
    /**
     * Check if child standard available or not
     * If child is not available then no need to display that standards
     * @param $standardId
     * @return array|object|null
     */
    function is_child_standards_available($standardId) {
        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s" , $standardId ) , ARRAY_A);
        return $results;
    }
}

if (!function_exists('is_child_notations_available')) {
    /**
     * Check if children standard notations available of a notation
     * @param $notationId
     * @return array|object|null
     */
    function is_child_notations_available($notationId)
    {
        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where parent_id = %s" , $notationId ) , ARRAY_A);
        return $results;
    }
}

if (! function_exists('get_standard_notations_from_ids')) {

    /**
     * Get all standards notations with ids
     * @param $ids
     * @param bool $admin
     */
    function get_standard_notations_from_ids($ids, $admin = false) {
        global $wpdb;
        
        $stds = null;
        $substds = null;
        $notations = null;
        $empty = true;
       
        if(!is_array($ids)) {
            $stds = explode(',', $ids);
            foreach ($stds as $std) {
                if (strpos($std, "standard_notation")!== false){
                    $notations[] = str_replace('standard_notation-', '', $std);
                } else {
                    $substds[] = str_replace('sub_standards-', '', $std);
                }
            }
        } else {
            foreach($ids as $id){
                if (strpos($id, "standard_notation")){
                    $notations[] = str_replace('standard_notation-', '', $id);
                } else {
                    $substds[] = str_replace('sub_standards-', '', $id);
                }
            }
        }
        
        if ($notations) {
            // Count the number of ids
            $idsCount = count($notations);
    
            // Prepare the right amount of placeholders, in an array
    
            // For strings, you would use, ‘%s’
            $stringPlaceholders = array_fill(0, $idsCount, '%s');
    
            // Put all the placeholders in one string ‘%s, %s, %s, %s, %s,…’
            $placeholdersForIds = implode(',', $stringPlaceholders);
            
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where id in (" . $placeholdersForIds .")" , $notations ) , ARRAY_A);
            if (!empty($results)) {
                foreach ($results as $result) {?>
                    <span class="selected-standard-pill">
                        <?php echo $result['description'];?>
                        <?php if ($admin) {?>
                            <a href="javascript:void(0)"
                               class="remove-ss-pill"
                               data-id="standard_notation-<?php echo $result['id']?>"
                            ><i class="fa fa-times"></i></a>
                        <?php }?>
                    </span>
                <?php }
                $empty = false;
            }
        }
        
        if ($substds){
            // Count the number of ids
            $idsCount = count($substds);
    
            // Prepare the right amount of placeholders, in an array
    
            // For strings, you would use, ‘%s’
            $stringPlaceholders = array_fill(0, $idsCount, '%s');
    
            // Put all the placeholders in one string ‘%s, %s, %s, %s, %s,…’
            $placeholdersForIds = implode(',', $stringPlaceholders);
            
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where id in (" . $placeholdersForIds .")" , $substds ) , ARRAY_A);
            if (!empty($results)) {
                foreach ($results as $result) {?>
                    <span class="selected-standard-pill">
                        <?php echo $result['standard_title'];?>
                        <?php if ($admin) {?>
                            <a href="javascript:void(0)"
                               class="remove-ss-pill"
                               data-id="sub_standards-<?php echo $result['id']?>"
                            ><i class="fa fa-times"></i></a>
                        <?php }?>
                    </span>
                <?php }
                $empty = false;
            }
        }
        
        if ($empty==true) {
            if ($admin) {
                echo "<p>You have not selected any academic standards</p>";
            }
        }
    }
}

if (! function_exists('get_file_type_from_url')) {
    /**
     * Check the file type form the url
     * @param $url
     * @param string $class
     * @return array|bool
     */
    function get_file_type_from_url($url, $class = 'fa-1x') {
        if(empty($url)) {
            return false;
        }

        $response = array();
        $oer_urls = explode('.', $url);
        $file_type = strtolower(end($oer_urls));
        if(in_array($file_type, ['jpg', 'jpeg', 'gif', 'png'])) {
            $response['title'] = 'Image';
            $response['icon'] = '<i class="fa fa-file-image-o '.$class.'"></i>';
        } elseif($file_type == 'pdf') {
            $response['title'] = 'PDF';
            $response['icon'] = '<i class="fa fa-file-pdf-o '.$class.'"></i>';
        } elseif(in_array($file_type, ['txt'])) {
            $response['title'] = 'Plain Text';
            $response['icon'] = '<i class="fa fa-file-text-o '.$class.'"></i>';
        } elseif(in_array($file_type, ['7z', 'zip', 'rar'])) {
            $response['title'] = 'Archive';
            $response['icon'] = '<i class="fa fa-file-archive-o '.$class.'"></i>';
        } elseif(in_array($file_type, ['docx', 'doc'])) {
            $response['title'] = 'Microsoft Document';
            $response['icon'] = '<i class="fa fa-file-word-o '.$class.'"></i>';
        } elseif(in_array($file_type, ['xls'])) {
            $response['title'] = 'Microsoft Excel';
            $response['icon'] = '<i class="fa fa-file-excel-o '.$class.'"></i>';
        } elseif(in_array($file_type, ['ppt'])) {
            $response['title'] = 'Microsoft Powerpoint';
            $response['icon'] = '<i class="fa fa-file-powerpoint-o '.$class.'"></i>';
        }
        return $response;
    }
}

if (! function_exists('oer_curriculum_scan_array')) {
    /**
     * multi array scan
     *
     * @param $array array
     *
     * @return bool
     */
    function oer_curriculum_scan_array($array = array()){
        if (empty($array)) return false;

        foreach ($array as $sarray) {
            if (!empty(array_filter($sarray))) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('oer_curriculum_primary_resource_dropdown')){
    function oer_curriculum_primary_resource_dropdown(){
        $resource_options = "";
        $posts = get_posts([
            'post_type' => 'resource',
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'title',
            'order'    => 'ASC'
        ]);
        
        $resource_options .= "<option>Select Resource</option>";
        
        if (count($posts)) {
            foreach ($posts as $post) {
                $resource_options .= '<option value="' . $post->post_title . '" ' . (($resource == $post->post_title) ? 'selected="selected"' : "") . '>' . $post->post_title . '</option>';
            }
        }
        return $resource_options;
    }
}

if (! function_exists('oer_curriculum_get_resource_details')){
    function oer_curriculum_get_resource_details($source_title){
        $resource = get_page_by_title($source_title,OBJECT,"resource");
        return $resource;
    }
}

if (! function_exists('oer_curriculum_related_inquiry_sets')){
    function oer_curriculum_related_inquiry_sets($id=null){
        $args = [
            'post_type' => 'oer-curriculum',
            'post_status' => 'publish',
            'numberposts' => 250,
            'orderby' => 'title',
            'order'    => 'ASC'
        ];
        if ($id)
            $args['exclude'] = array($id);
        $posts = get_posts($args);
        return $posts;
    }
}

if (! function_exists('oer_curriculum_get_inquiry_set_details')){
    function oer_curriculum_get_inquiry_set_details($id){
        return get_post($id);
    }
}

if (! function_exists('oer_curriculum_get_inquiry_set_metadata')){
    function oer_curriculum_get_inquiry_set_metadata($id){
        return get_post_meta($id);
    }
}

if (! function_exists('oer_curriculum_title_from_slug')){
    function oer_curriculum_title_from_slug($slug){
        return str_replace("-"," ",$slug);
    }
}

if (! function_exists('oer_curriculum_grade_level')){
    function oer_curriculum_grade_level($inquiry_set_id){
        $grades = get_post_meta($inquiry_set_id, "oer_oer_curriculum_grades", true);
        if(empty($grades)){
          $_tmp = '';
        }else{
          $_tmp = oer_curriculum_grade_levels($grades);
        }  
        return $_tmp;
    }
}

function oer_curriculum_sort_grade_level($a, $b) {
    if ( $a == $b )
        return 0;

    if (is_numeric($a) && is_numeric($b))
        return ($a<$b) ? -1 : 1;
    elseif (is_numeric($a) && !is_numeric($b))
        return 1;
    elseif (!is_numeric($a) && is_numeric($b))
        return -1;
    else {
        if ($a=="pre-k" && $b=="k")
            return -1;
        else
            return 1;
    }


}

function oer_curriculum_grade_levels($grade_levels){
    $default_arr = [
                    "pre-k",
                    "k",
                    "1",
                    "2",
                    "3",
                    "4",
                    "5",
                    "6",
                    "7",
                    "8",
                    "9",
                    "10",
                    "11",
                    "12"
                    ];

    $elmnt = 0;
    $def_index = 0;
    
    usort($grade_levels, "oer_curriculum_sort_grade_level");

    for($x=0; $x < count($grade_levels); $x++)
    {
        $grade_levels[$x];
    }

    $fltrarr = array_filter($grade_levels, 'strlen');
    
    $flag = array();
    if (is_array($fltrarr) && count($fltrarr)>0)
        $elmnt = $fltrarr[min(array_keys($fltrarr))];
    
    for($y=0; $y < count($default_arr); $y++){
        if ($default_arr[$y]==$elmnt){
            $def_index = $y;
            break;
        }
    }

    for($i =0; $i < count($fltrarr); $i++)
    {
        if($elmnt == $fltrarr[$i] || $default_arr[$def_index+$i] == strtolower($fltrarr[$i]))
        {
            $flag[] = 1;
        }
        else
        {
            $flag[] = 0;
        }
        if (strtolower($fltrarr[$i])=="k")
            $fltrarr[$i] = "K";
        if (strtolower($fltrarr[$i])=="pre-k")
            $fltrarr[$i] = "Pre-K";
        $elmnt++;
    }

    if(in_array('0',$flag))
    {
        return implode(", ",array_unique($fltrarr));
    }
    else
    {
        $arr_flt = array_keys($fltrarr);
        
        $end_filter = end($arr_flt);
        
        if (count($fltrarr)>1) {
            if (strtolower($fltrarr[0])=="pre-k" || strtolower($fltrarr[$end_filter])=="k")
                return $fltrarr[0]." &ndash; ".$fltrarr[$end_filter];
            else
                return $fltrarr[0]."-".$fltrarr[$end_filter];
        }
        else{
            if (isset($fltrarr[0]))
                return $fltrarr[0];
        }
    }
}

if (! function_exists('is_oer_plugin_installed')){
    function is_oer_plugin_installed(){
        $activeOER = false;
        $active_plugins_basenames = get_option( 'active_plugins' );
        foreach ( $active_plugins_basenames as $plugin_basename ) {
		if ( false !== strpos( $plugin_basename, '/open-educational-resources.php' ) ) {
                $activeOER = true;
            }
        }
        return $activeOER;
    }
}

if (! function_exists('is_standards_plugin_installed')){
    function is_standards_plugin_installed(){
        $activeWAS = false;
        $active_plugins_basenames = get_option( 'active_plugins' );
        foreach ( $active_plugins_basenames as $plugin_basename ) {
		if ( false !== strpos( $plugin_basename, '/wp-academic-standards.php' ) ) {
                $activeWAS = true;
            }
        }
        return $activeWAS;
    }
}
/** Display loader image **/
if (! function_exists('oer_curriculum_display_loader')){
    function oer_curriculum_display_loader(){
    ?>
    <div class="oer-curriculum-loader"><div class="loader-img"><div><img src="<?php echo OER_LESSON_PLAN_URL; ?>images/load.gif" align="center" valign="middle" /></div></div></div>
    <?php
    }
}
// Get Meta Label
if (!function_exists('oer_curriculum_get_meta_label')){
	function oer_curriculum_get_meta_label($key){
            $label = "";
            switch ($key){
            case "oer_oer_curriculum_authors":
                $label = __("Author", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_primary_resources":
                $label = __("Primary Resources", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_iq":
                $label = __("Investigative Question", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_related_objective":
                $label = __("Related Instructional Objectives (SWBAT...)", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_custom_editor_historical_background":
                $label = __("Historical Background", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_download_copy":
                $label = __("Download Copy", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_download_copy_document":
                $label = __("Download Copy Document", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_related_inquiry_set":
                $label = __("Related Inquiry Sets", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_related_inquiry_set_1":
                $label = __("Inquiry Set 1", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_related_inquiry_set_2":
                $label = __("Inquiry Set 2", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_related_inquiry_set_3":
                $label = __("Inquiry Set 3", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_required_materials":
                $label = __("Required Equipment Materials", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_grades":
                $label = __("Grade Level", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_curriculum_oer_materials":
                $label = __("Materials", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_type":
                $label = __("Type", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_type_other":
                $label = __("Other Type", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_age_levels":
                $label = __("Appropriate Age Levels", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_suggested_instructional_time":
                $label = __("Suggested Instructional Time", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_standards":
                $label = __("Standards", OER_LESSON_PLAN_SLUG);
                break;
            case "oer_oer_curriculum_additional_sections":
                $label = __("Additional Sections", OER_LESSON_PLAN_SLUG);
                break;
	    }
	    return $label;
	}
}
// Get All Post Meta
if (!function_exists('oer_curriculum_get_all_meta')){
	function oer_curriculum_get_all_meta($type){
		global $wpdb;
		$result = $wpdb->get_results($wpdb->prepare(
		"SELECT post_id, meta_key, meta_value FROM ".$wpdb->prefix."posts,".$wpdb->prefix."postmeta WHERE post_type=%s
			AND ".$wpdb->prefix."posts.ID=".$wpdb->prefix."postmeta.post_id", $type
		), ARRAY_A);
		return $result;
	}
}
// Save Metadata options
if (!function_exists('oer_curriculum_save_metadata_options')){
	function oer_curriculum_save_metadata_options($post_data){
		foreach($post_data as $key=>$value){
			if (strpos($key,"oer_")!==false || strpos($key,"oer_curriculum_oer_")!==false){
				update_option($key, $value, true);
			}
		}
	}
}

// Get Field Label
if (! function_exists('oer_curriculum_get_field_label')){
    function oer_curriculum_get_field_label($field){
        $label = null;
        
        if (get_option($field.'_label'))
            $label = get_option($field.'_label');
        else
            $label = oer_curriculum_get_meta_label($field);
         
        return $label;
    }
}

// Get Curriculum Type
if (! function_exists('oer_curriculum_get_curriculum_type')){
    function oer_curriculum_get_curriculum_type($value = ""){
        $html = '<option value="">Select Type</option>';
        $types = array(
            "Brief Activity",
            "Full Lesson",
            "Short Project",
            "Extended Project",
            "Comprehensive Unit",
            "Other"
        );
        
        foreach ($types as $type){
            $html .= '<option value="'.$type.'" '.selected($type,$value,false).'>'.$type.'</option>';
        }
        return $html;
    }
}

// Limit Content Display
if (!function_exists('oer_curriculum_content')){
    function oer_curriculum_content($limit) {
        global $post; 
        $content = get_the_content($post->ID);
        if (strlen($content)>=$limit) {
          $content = substr($content, 0, $limit);
        }
        
        $content = preg_replace('/[.+]/','', $content);
        $content = preg_replace('/<!--.*?-->/ms', '', $content);
        //$content = apply_filters('the_content', $content); 
        $content = str_replace(']]>', ']]>', $content);
        $content .= '... <a href="javascript:void(0);" class="oer-curriculum-read-more">(read more)</a>';
        return $content;
    }
}

// Get Modules
if (!function_exists('oer_curriculum_modules')){
    function oer_curriculum_modules($curriculum_id) {
        $modules = null;
        $post_meta_data = get_post_meta($curriculum_id);
        $elements_orders = isset($post_meta_data['oer_curriculum_order'][0]) ? unserialize($post_meta_data['oer_curriculum_order'][0]) : array();
        
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
                    if (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_oer_curriculum_vocabulary_list_title_') === false) 
                        $module = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                    
                    if (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_oer_curriculum_custom_text_list_') !== false){
                        $module['title'] = "Text List";
                    } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_oer_curriculum_vocabulary_list_title_') !== false) {
                        $oer_curriculum_vocabulary_list_title = (isset($post_meta_data[$elementKey][0]) ? $post_meta_data[$elementKey][0] : "");
                        $module['title'] = $oer_curriculum_vocabulary_list_title;
                    } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_oer_materials_list_') !== false) {
                        $module['title'] = "Materials";
                    }
                    $modules[] = $module;
                }
            }
        }
        return $modules;
    }
}