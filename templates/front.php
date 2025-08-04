<?php
/**
 * Template file for the frontend
 * 
 * @since 1.0.0
 */

 ?>

 <div class="time-to-read <?php do_action('time_to_read_classes'); ?> <?php echo isset($args['class']) && !empty($args['class']) ? esc_attr($args['class']) : ''; ?>" <?php echo isset($args['style']) && !empty($args['style']) ? 'style="'. esc_attr($args['style']) . '"' : ''; ?>>
   <?php do_action('time_to_read_before'); ?>
   <p><?php echo esc_html($text); ?> <?php echo esc_html($calculation); ?></p>
   <?php do_action('time_to_read_after'); ?>
 </div>