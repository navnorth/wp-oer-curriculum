<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

// Display selected dropdown
if( ! function_exists('oercurr_show_selected')) {
    function oercurr_show_selected($key, $value, $type = 'selectbox') {
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

if (! function_exists('oercurr_addSchemeToUrl')) {
    function oercurr_addSchemeToUrl($url) {
        if (strpos($url, '://') === false) {
            return 'http://' . $url;
        }
    }
}

if (! function_exists('oercurr_display_standards')) {
    /**
     * Get the list of standards and display it
     */
    function oercurr_display_standards() {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "oer_core_standards",ARRAY_A);
        if ($results) { ?>
            <ul class="oercurr-standards-wrapper">
                <?php
                foreach ($results as $result) {
                $coreStandardId = "core_standards-" . $result['id'];
                ?>
                    <li>
                        <a data-toggle="collapse"
                           data-target="#<?php echo esc_attr($coreStandardId); ?>"
                        ><?php echo esc_html($result['standard_name']); ?></a>
                        <?php esc_html(oercurr_children_standards($coreStandardId)); ?>
                    </li>
                <?php }?>
            </ul>
        <?php }
    }
}

if (! function_exists('oercurr_children_standards')) {
    /**
     * Get the list all children standards
     * @param $coreStandardId
     */
    function oercurr_children_standards($coreStandardId) {
        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s" , $coreStandardId ) ,ARRAY_A);
        if ($results) {?>
            <div class="collapse oercurr-standard-child-element" id="<?php echo esc_attr($coreStandardId); ?>">
                <ul>
                    <?php
                    foreach ($results as $result) {
                        $subStandard = "sub_standards-" . $result['id'];
                        $isNotationsAvailable = oercurr_is_child_notations_available($subStandard);
                        $isChildStandardAvailable = oercurr_is_child_standards_available($subStandard);
                        ?>
                        <li>
                            <!--Check if child standards available then get all the children standards-->
                            <?php if (!empty($isChildStandardAvailable)) {?>
                                <a data-toggle="collapse"
                                   data-target="#<?php echo esc_attr($subStandard); ?>"
                                ><?php echo esc_html($result['standard_title']); ?></a>
                                <?php esc_html(oercurr_children_standards($subStandard)); ?>
                            <?php }?>

                            <!--Check if notations available for standard then display -->
                            <?php if (!empty($isNotationsAvailable)) {?>
                                <a data-toggle="collapse"
                                   data-target="#<?php echo esc_attr($subStandard); ?>"
                                ><?php echo esc_html($result['standard_title']); ?></a>
                                <?php esc_html(oercurr_get_standard_notations($subStandard)); ?>
                            <?php }?>
                        </li>

                    <?php }?>
                </ul>
            </div>
        <?php }
    }
}

if (! function_exists('oercurr_get_standard_notations')) {
    /**
     * Get the standard notations with standard id
     * Display notations recursively
     * @param $parentId
     */
    function oercurr_get_standard_notations($parentId) {
        global $wpdb;
        global $post;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where parent_id = %s" , $parentId ) , ARRAY_A);
        if ($results) {?>
            <div class="collapse oercurr-standard-child-element" id="<?php echo esc_attr($parentId); ?>">
                <ul class="oer_curriculum_notations">
                    <?php
                    // Check if data already saved for current editing post
                    $post_standards = get_post_meta($post->ID, 'oer_curriculum_standards' );
                    $post_standards_array = array();
                    if (isset($post_standards[0]) && !empty($post_standards[0])){
                        $post_standards_array = explode(',', $post_standards[0]);
                    }
                    foreach ($results as $result) {
                    $notationId = "standard_notation-" . $result['id'];
                    $isChildrenAvailable = oercurr_is_child_notations_available($notationId);
                    ?>
                        <li>
                            <?php
                            if(empty($isChildrenAvailable)) {?>
                                <input type="checkbox"
                                       name="oer_curriculum_oer_notations[]"
                                       class="oercurr-sck"
                                       id="oercurr-standard-check-<?php echo esc_attr($notationId); ?>"
                                       value="<?php echo esc_attr($notationId); ?>"
                                       <?php echo (in_array($notationId, $post_standards_array) ? 'checked="checked"' : "")?>
                                >
                                <label for="oercurr-standard-check-<?php echo esc_attr($notationId); ?>" class="oercurr-scl"><?php echo esc_html($result['standard_notation']); ?></label>
                                <div class="oercurr-notation-description"><?php echo esc_html($result['description']); ?></div>
                            <?php } else {?>
                                <a data-toggle="collapse" data-target="#<?php echo esc_attr($notationId); ?>" ><?php echo esc_html($result['standard_notation']); ?></a>
                                <div><?php echo esc_html($result['description']); ?></div>
                            <?php }?>
                            <?php esc_html(oercurr_get_standard_notations($notationId)); ?>
                        </li>
                    <?php }?>
                </ul>
            </div>
        <?php }
    }
}

if (! function_exists('oercurr_is_child_standards_available')) {
    /**
     * Check if child standard available or not
     * If child is not available then no need to display that standards
     * @param $standardId
     * @return array|object|null
     */
    function oercurr_is_child_standards_available($standardId) {
        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s" , $standardId ) , ARRAY_A);
        return $results;
    }
}

if (!function_exists('oercurr_is_child_notations_available')) {
    /**
     * Check if children standard notations available of a notation
     * @param $notationId
     * @return array|object|null
     */
    function oercurr_is_child_notations_available($notationId)
    {
        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where parent_id = %s" , $notationId ) , ARRAY_A);
        return $results;
    }
}

if (! function_exists('oercurr_get_standard_notations_from_ids')) {

    /**
     * Get all standards notations with ids
     * @param $ids
     * @param bool $admin
     */
    function oercurr_get_standard_notations_from_ids($ids, $admin = false) {
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
                        <?php echo esc_html($result['description']); ?>
                        <?php if ($admin) {?>
                            <a href="javascript:void(0)"
                               class="remove-ss-pill"
                               data-id="standard_notation-<?php echo esc_attr($result['id']); ?>"
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
                        <?php echo esc_html($result['standard_title']); ?>
                        <?php if ($admin) {?>
                            <a href="javascript:void(0)"
                               class="remove-ss-pill"
                               data-id="sub_standards-<?php echo esc_attr($result['id']); ?>"
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

if (! function_exists('oercurr_get_file_type_from_url')) {
    /**
     * Check the file type form the url
     * @param $url
     * @param string $class
     * @return array|bool
     */
    function oercurr_get_file_type_from_url($url, $class = 'fa-1x') {
        if(empty($url)) {
            return false;
        }

        $response = array();
        $oer_urls = explode('.', $url);
        $file_type = strtolower(end($oer_urls));
        if(in_array($file_type, ['jpg', 'jpeg', 'gif', 'png'])) {
            $response['title'] = 'Image';
            $response['icon'] = '<i class="fa fa-file-image '.esc_attr($class).'"></i>';
        } elseif($file_type == 'pdf') {
            $response['title'] = 'PDF';
            $response['icon'] = '<i class="fa fa-file-pdf '.esc_attr($class).'"></i>';
        } elseif(in_array($file_type, ['txt'])) {
            $response['title'] = 'Plain Text';
            $response['icon'] = '<i class="fa fa-file-alt '.esc_attr($class).'"></i>';
        } elseif(in_array($file_type, ['7z', 'zip', 'rar'])) {
            $response['title'] = 'Archive';
            $response['icon'] = '<i class="fa fa-file-archive '.esc_attr($class).'"></i>';
        } elseif(in_array($file_type, ['docx', 'doc'])) {
            $response['title'] = 'Microsoft Document';
            $response['icon'] = '<i class="fa fa-file-word '.esc_attr($class).'"></i>';
        } elseif(in_array($file_type, ['xls'])) {
            $response['title'] = 'Microsoft Excel';
            $response['icon'] = '<i class="fa fa-file-excel '.esc_attr($class).'"></i>';
        } elseif(in_array($file_type, ['ppt'])) {
            $response['title'] = 'Microsoft Powerpoint';
            $response['icon'] = '<i class="fa fa-file-powerpoint '.esc_attr($class).'"></i>';
        }
        return $response;
    }
}


if (! function_exists('oercurr_get_file_type_from_url_text_only')) {
    /**
     * Check the file type form the url
     * @param $url
     * @param string $class
     * @return array|bool
     */
    function oercurr_get_file_type_from_url_text_only($url, $class = 'fa-1x') {
        if(empty($url)) {
            return false;
        }

        $response = array();
        $oer_urls = explode('.', $url);
        $file_type = strtolower(end($oer_urls));
        if(in_array($file_type, ['jpg', 'jpeg', 'gif', 'png'])) {
            $response['title'] = 'Image';
            $response['icon'] = 'fa fa-file-image '.esc_attr($class);
        } elseif($file_type == 'pdf') {
            $response['title'] = 'PDF';
            $response['icon'] = 'fa fa-file-pdf '.esc_attr($class);
        } elseif(in_array($file_type, ['txt'])) {
            $response['title'] = 'Plain Text';
            $response['icon'] = 'fa fa-file-alt '.esc_attr($class);
        } elseif(in_array($file_type, ['7z', 'zip', 'rar'])) {
            $response['title'] = 'Archive';
            $response['icon'] = 'fa fa-file-archive '.esc_attr($class);
        } elseif(in_array($file_type, ['docx', 'doc'])) {
            $response['title'] = 'Microsoft Document';
            $response['icon'] = 'fa fa-file-word '.esc_attr($class);
        } elseif(in_array($file_type, ['xls'])) {
            $response['title'] = 'Microsoft Excel';
            $response['icon'] = 'fa fa-file-excel '.esc_attr($class);
        } elseif(in_array($file_type, ['ppt'])) {
            $response['title'] = 'Microsoft Powerpoint';
            $response['icon'] = 'fa fa-file-powerpoint '.esc_attr($class);
        }
        return $response;
    }
}


if (! function_exists('oercurr_scan_array')) {
    /**
     * multi array scan
     *
     * @param $array array
     *
     * @return bool
     */
    function oercurr_scan_array($array = array()){
        if (empty($array)) return false;

        foreach ($array as $sarray) {
            if (!empty(array_filter($sarray))) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('oercurr_primary_resource_dropdown')){
    function oercurr_primary_resource_dropdown(){
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
                $resource_options .= '<option value="' . esc_attr($post->post_title) . '" ' . (($resource == $post->post_title) ? 'selected="selected"' : "") . '>' . esc_html($post->post_title) . '</option>';
            }
        }
        return $resource_options;
    }
}

if (! function_exists('oercurr_get_resource_details')){
    function oercurr_get_resource_details($source_title){
        $resource = get_page_by_title($source_title,OBJECT,"resource");
        return $resource;
    }
}

if (! function_exists('oercurr_related_curriculum')){
    function oercurr_related_curriculum($id=null){
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

if (! function_exists('oercurr_get_inquiry_set_details')){
    function oercurr_get_inquiry_set_details($id){
        return get_post($id);
    }
}

if (! function_exists('oercurr_get_inquiry_set_metadata')){
    function oercurr_get_inquiry_set_metadata($id){
        return get_post_meta($id);
    }
}

if (! function_exists('oercurr_title_from_slug')){
    function oercurr_title_from_slug($slug){
        return str_replace("-"," ",$slug);
    }
}

if (! function_exists('oercurr_grade_level')){
    function oercurr_grade_level($inquiry_set_id){
        $grades = get_post_meta($inquiry_set_id, "oer_curriculum_grades", true);
        if(empty($grades)){
          $_tmp = '';
        }else{
          $_tmp = oercurr_grade_levels($grades);
        }  
        return $_tmp;
    }
}

function oercurr_sort_grade_level($a, $b) {
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

function oercurr_grade_levels($grade_levels){
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
    
    usort($grade_levels, "oercurr_sort_grade_level");

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

if (! function_exists('oercurr_is_oer_plugin_installed')){
    function oercurr_is_oer_plugin_installed(){
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

if (! function_exists('oercurr_is_standards_plugin_installed')){
    function oercurr_is_standards_plugin_installed(){
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
if (! function_exists('oercurr_display_loader')){
    function oercurr_display_loader(){
    ?>
    <div class="oercurr-loader"><div class="loader-img"><div><img src="<?php echo esc_url(OERCURR_CURRICULUM_URL); ?>images/load.gif" align="center" valign="middle" /></div></div></div>
    <?php
    }
}
// Get Meta Label
if (!function_exists('oercurr_get_meta_label')){
    function oercurr_get_meta_label($key){
            $label = "";
            switch ($key){
            case "oer_curriculum_authors":
                $label = esc_html__("Author", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_primary_resources":
                $label = esc_html__("Primary Resources", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_iq":
                $label = esc_html__("Investigative Question", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_related_objective":
                $label = esc_html__("Related Instructional Objectives (SWBAT...)", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_custom_editor_historical_background":
                $label = esc_html__("Historical Background", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_download_copy":
                $label = esc_html__("Download Copy", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_download_copy_document":
                $label = esc_html__("Download Copy Document", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_related_curriculum":
                $label = esc_html__("Related Curriculum", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_related_curriculum_1":
                $label = esc_html__("Related Curriculum 1", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_related_curriculum_2":
                $label = esc_html__("Related Curriculum 2", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_related_curriculum_3":
                $label = esc_html__("Related Curriculum 3", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_required_materials":
                $label = esc_html__("Required Equipment Materials", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_grades":
                $label = esc_html__("Grade Level", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_oer_materials":
                $label = esc_html__("Materials", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_type":
                $label = esc_html__("Type", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_type_other":
                $label = esc_html__("Other Type", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_age_levels":
                $label = esc_html__("Appropriate Age Levels", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_suggested_instructional_time":
                $label = esc_html__("Suggested Instructional Time", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_standards":
                $label = esc_html__("Standards", OERCURR_CURRICULUM_SLUG);
                break;
            case "oer_curriculum_additional_sections":
                $label = esc_html__("Additional Sections", OERCURR_CURRICULUM_SLUG);
                break;
        }
        return $label;
    }
}
// Get All Post Meta
if (!function_exists('oercurr_get_all_meta')){
    function oercurr_get_all_meta($type){
        global $wpdb;
        $tablename = $wpdb->prefix."options";
        $sql = $wpdb->prepare("SELECT option_id, option_name, option_value FROM {$tablename} WHERE option_name LIKE %s",'%_curmetset_%');
        $result = $wpdb->get_results( $sql , ARRAY_A );
        return $result;      
    }
}
// Save Metadata options
if (!function_exists('oercurr_save_metadata_options')){
    function oercurr_save_metadata_options($post_data){
        foreach($post_data as $key=>$value){  
            if (strpos($key,"oer_curriculum_")!==false && strpos($key,"_curmetset_label")!==false){
                $savevalue = (empty($value))?' ':trim($value,' ');
                update_option($key, sanitize_text_field($savevalue), true);
                //Do enabled option
                $enb_key = str_replace("_label","_enable",$key);
                $enb_val = (isset($post_data[$enb_key]))? 'checked': 'unchecked';
                update_option($enb_key, sanitize_text_field($enb_val), true);
            }
        }
    }
}

// Get Field Label
if (! function_exists('oercurr_get_field_label')){
    function oercurr_get_field_label($field){
        $field = $field.'_curmetset';
        $label = null;
        
        if (get_option($field.'_label'))
            $label = get_option($field.'_label');
        else
            $label = oercurr_get_meta_label($field);
         
        return $label;
    }
}

// Get Curriculum Type
if (! function_exists('oercurr_get_curriculum_type')){
    function oercurr_get_curriculum_type($value = ""){
        $html = '<option value="">Select Type</option>';
        $types = array(
            "Brief Activity",
            "Full Lesson",
            "Short Project",
            "Extended Project",
            "Comprehensive Unit",
            "Other"
        );
        $type_other_enabled = (get_option('oer_curriculum_type_other_curmetset_enable')=='checked')?true:false;
        if(!$type_other_enabled){
            unset($types[array_search("Other", $types)]);
        }
        foreach ($types as $type){
            $html .= '<option value="'.esc_attr($type).'" '.esc_attr(selected($type,$value,false)).'>'.esc_html($type).'</option>';
        }
        return $html;
    }
}

// Limit Content Display
if (!function_exists('oercurr_limit_content')){
    function oercurr_limit_content($limit) {
        global $post; 
        $content = get_the_content($post->ID);
        if (strlen($content)>=$limit) {
          $content = substr($content, 0, $limit);
        }
        
        $content = preg_replace('/[.+]/','', $content);
        $content = preg_replace('/<!--.*?-->/ms', '', $content);
        //$content = apply_filters('the_content', $content); 
        $content = str_replace(']]>', ']]>', $content);
        $content .= '... <a href="javascript:void(0);" class="oercurr-read-more">(read more)</a>';
        return $content;
    }
}

// Get Modules
if (!function_exists('oercurr_get_modules')){
    function oercurr_get_modules($curriculum_id) {
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
                    if (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_vocabulary_list_title_') === false) 
                        $module = (isset($post_meta_data[$elementKey][0]) ? unserialize($post_meta_data[$elementKey][0]) : "");
                    
                    if (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_custom_text_list_') !== false){
                        $module['title'] = "Text List";
                    } elseif (isset($post_meta_data[$elementKey]) && strpos($elementKey, 'oer_curriculum_vocabulary_list_title_') !== false) {
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

// Add fields label/enable options
if (!function_exists('oercurr_add_setting_options')){
    function oercurr_add_setting_options($key,$typ,$val) {
        update_option($key.'_curmetset_'.$typ,$val);
    }
}

function oercurr_grades_allowed_html(){
  $allowed_tags = array(
    'div' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
    'input' => array(
      'type' => array(),
      'name' => array(),
      'value' => array(),
      'id' => array(),
      'checked' => array(),
    ),
    'label' => array(
      'class' => array(),
      'for' => array(),
    ),
  );
}

function oercurr_allowed_html() {

	$allowed_tags = array(
		'a' => array(
			'class' => array(),
			'href'  => array(),
			'rel'   => array(),
			'title' => array(),
		),
		'abbr' => array(
			'title' => array(),
		),
		'b' => array(),
		'blockquote' => array(
			'cite'  => array(),
		),
		'cite' => array(
			'title' => array(),
		),
		'code' => array(),
		'del' => array(
			'datetime' => array(),
			'title' => array(),
		),
		'dd' => array(),
		'div' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'dl' => array(),
		'dt' => array(),
		'em' => array(),
		'h1' => array(),
		'h2' => array(),
		'h3' => array(),
		'h4' => array(),
		'h5' => array(),
		'h6' => array(),
		'i' => array(),
		'img' => array(
			'alt'    => array(),
			'class'  => array(),
			'height' => array(),
			'src'    => array(),
			'width'  => array(),
		),
		'li' => array(
			'class' => array(),
		),
		'ol' => array(
			'class' => array(),
		),
		'p' => array(
			'class' => array(),
		),
		'q' => array(
			'cite' => array(),
			'title' => array(),
		),
		'span' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'strike' => array(),
		'strong' => array(),
		'ul' => array(
			'class' => array(),
		),
	);
	
	return $allowed_tags;
}