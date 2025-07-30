<?php
/**
 * Register mani block
 * 
 * @version 1.0.0
 */

namespace lc\timetoread\includes\blocks;

if( ! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if( ! class_exists('TimeToReadBlockMain') ) {
  class TimeToReadBlockMain extends TimeToReadAbstractBlock {

    /**
     * Block name
     * 
     * @since 1.0.0
     * @return string
     */
    public static $name = 'reading-time';

    /**
     * Callback
     * 
     * @since 1.0.0
     * @return string
     */
    public static $callback = array(self::class, 'render_block');

    /**
     * Redner callback block
     * 
     * @since 1.0.0
     */
    public static function render_block() {
      return \lc\timetoread\includes\TimeToReadIntegrate::instance()->reading_time_block(true);
    }
    
  }

}