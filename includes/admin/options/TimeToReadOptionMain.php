<?php
/**
 * Installation related hooks and actions
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\admin\options;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadOptionMain') ) {
  class TimeToReadOptionMain {

    
    
    /**
     * Render page
     * 
     * @since 1.0.0
     */
    public static function render_page() {
      $template_path = TIMETOREAD_ABSPATH . '/includes/admin/options/views/option-main.php';

      if( ! file_exists($template_path) ) {
        trigger_error(sprintf('The file %s, is missing from this plugin installation', $template_path), E_USER_ERROR);
      }

      include_once($template_path);
    }

  }
}