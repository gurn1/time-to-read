<?php
/**
 * General helpers
 * 
 * @since 1.0.0
 */

/**
 * Generate admin settings input field path 
 * 
 * @since 1.0.0
 */
if( ! function_exists('ttr_generate_admin_settings_field_path')) {
  function ttr_generate_admin_settings_field_path($field_name) {
    return esc_html('time_to_read_' . $field_name . '_section');
  }
}
