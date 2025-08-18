<?php
/**
 * Template file for the frontend
 * 
 * @since 1.0.0
 */

if( ! defined('ABSPATH')) :
  exit; // Exit if accessed directly
endif;
?>

 <div class="simple-time-to-read-lsc <?php do_action('sttrlsc_classes'); ?> <?php echo isset($args['class']) && !empty($args['class']) ? esc_attr($args['class']) : ''; ?>" <?php echo isset($args['style']) && !empty($args['style']) ? 'style="'. esc_attr($args['style']) . '"' : ''; ?>>
   <?php do_action('sttrlsc_before'); ?>
   <p><?php echo esc_html($text); ?> <?php echo esc_html($calculation); ?></p>
   <?php do_action('sttrlsc_after'); ?>
 </div>