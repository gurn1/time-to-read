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
function sttr_generate_admin_settings_field_path($field_name) {
  return esc_html('stime_to_read_lsc_' . $field_name . '_section');
}
