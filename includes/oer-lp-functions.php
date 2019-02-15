<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

// Display selected dropdown
if( ! function_exists('oer_lp_show_selected')) {
    function oer_lp_show_selected($key, $value, $type = 'selectbox') {
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

if (! function_exists('lp_oer_display_standards')) {
    /**
     * Get the list of standards and display it
     */
    function lp_oer_display_standards() {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * from " . $wpdb->prefix. "oer_core_standards",ARRAY_A);
        if ($results) { ?>
            <ul class="lp-standards-wrapper">
                <?php
                foreach ($results as $result) {
                $coreStandardId = "core_standards-" . $result['id'];
                ?>
                    <li>
                        <a data-toggle="collapse"
                           data-target="#<?php echo $coreStandardId;?>"
                        ><?php echo $result['standard_name']?></a>
                        <?php lp_children_standards($coreStandardId);?>
                    </li>
                <?php }?>
            </ul>
        <?php }
    }
}

if (! function_exists('lp_children_standards')) {
    /**
     * Get the list all children standards
     * @param $coreStandardId
     */
    function lp_children_standards($coreStandardId) {
        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_sub_standards where parent_id = %s" , $coreStandardId ) ,ARRAY_A);
        if ($results) {?>
            <div class="collapse lp-standard-child-element" id="<?php echo $coreStandardId?>">
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
                                <?php lp_children_standards($subStandard);?>
                            <?php }?>

                            <!--Check if notations available for standard then display -->
                            <?php if (!empty($isNotationsAvailable)) {?>
                                <a data-toggle="collapse"
                                   data-target="#<?php echo $subStandard;?>"
                                ><?php echo $result['standard_title']?></a>
                                <?php lp_get_standard_notations($subStandard);?>
                            <?php }?>
                        </li>

                    <?php }?>
                </ul>
            </div>
        <?php }
    }
}

if (! function_exists('lp_get_standard_notations')) {
    /**
     * Get the standard notations with standard id
     * Display notations recursively
     * @param $parentId
     */
    function lp_get_standard_notations($parentId) {
        global $wpdb;
        global $post;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where parent_id = %s" , $parentId ) , ARRAY_A);
        if ($results) {?>
            <div class="collapse lp-standard-child-element" id="<?php echo $parentId;?>">
                <ul class="lp_notations">
                    <?php
                    // Check if data already saved for current editing post
                    $post_standards = get_post_meta($post->ID, 'oer_lp_standards' );
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
                                       name="lp_oer_notations[]"
                                       class="lp-sck"
                                       id="lp-standard-check-<?php echo $notationId;?>"
                                       value="<?php echo $notationId;?>"
                                       <?php echo (in_array($notationId, $post_standards_array) ? 'checked="checked"' : "")?>
                                >
                                <label for="lp-standard-check-<?php echo $notationId;?>" class="lp-scl"><?php echo $result['standard_notation'];?></label>
                                <div class="lp-notation-description"><?php echo $result['description'];?></div>
                            <?php } else {?>
                                <a data-toggle="collapse" data-target="#<?php echo $notationId;?>" ><?php echo $result['standard_notation']?></a>
                                <div><?php echo $result['description'];?></div>
                            <?php }?>
                            <?php lp_get_standard_notations($notationId);?>
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
        if(!is_array($ids)) {
            $ids = str_replace('standard_notation-', '', $ids);
            $ids = explode(',', $ids);
        }

        // Count the number of ids
        $idsCount = count($ids);

        // Prepare the right amount of placeholders, in an array

        // For strings, you would use, ‘%s’
        $stringPlaceholders = array_fill(0, $idsCount, '%s');

        // Put all the placeholders in one string ‘%s, %s, %s, %s, %s,…’
        $placeholdersForIds = implode(',', $stringPlaceholders);

        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * from " . $wpdb->prefix. "oer_standard_notation where id in (" . $placeholdersForIds .")" , $ids ) , ARRAY_A);
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
        } else {
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
    function get_file_type_from_url($url, $class = 'fa-2x') {
        if(empty($url)) {
            return false;
        }

        $response = array();
        $file_type = strtolower(end(explode('.', $url)));
        if(in_array($file_type, ['jpg', 'jpeg', 'gif', 'png'])) {
            $response['title'] = 'Image';
            $response['icon'] = '<i class="fa '.$class.' fa-file-image-o"></i>';
        } elseif($file_type == 'pdf') {
            $response['title'] = 'PDF';
            $response['icon'] = '<i class="fa '.$class.' fa-file-pdf-o"></i>';
        } elseif(in_array($file_type, ['txt'])) {
            $response['title'] = 'Plain Text';
            $response['icon'] = '<i class="fa '.$class.' fa-file-text-o"></i>';
        } elseif(in_array($file_type, ['7z', 'zip', 'rar'])) {
            $response['title'] = 'Archive';
            $response['icon'] = '<i class="fa '.$class.' fa-file-archive-o"></i>';
        } elseif(in_array($file_type, ['docx', 'doc', 'xls'])) {
            $response['title'] = 'Microsoft Document';
            $response['icon'] = '<i class="fa '.$class.' fa-file-word-o"></i>';
        }

        return $response;
    }
}

if (! function_exists('lp_scan_array')) {
    /**
     * multi array scan
     *
     * @param $array array
     *
     * @return bool
     */
    function lp_scan_array($array = array()){
        if (empty($array)) return false;

        foreach ($array as $sarray) {
            if (!empty(array_filter($sarray))) {
                return true;
            }
        }

        return false;
    }
}