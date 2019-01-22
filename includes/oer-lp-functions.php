<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Display selected dropdown
if( ! function_exists('oer_lp_show_selected'))
{
    function oer_lp_show_selected($key, $value, $type = 'selectbox')
    {
        // Check if value is not an array
        if(!is_array($value))
        {
            $value =  explode(',', $value);
        }
        if(in_array($key,$value))
        {
            if($type == 'checkbox') {
                return 'checked="checked"';
            } else {
                return 'selected="selected"';
            }
        }
        return false;
    }
}

