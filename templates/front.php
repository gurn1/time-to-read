<?php
/**
 * Template file for the frontend
 * 
 * @since 1.0.0
 */

 ?>

 <div class="time-to-read <?php do_action('time_to_read_classes'); ?>">
   <?php do_action('time_to_read_before'); ?>
   <p><?php echo esc_html($text); ?> <?php echo esc_html($calculation); ?></p>
   <?php do_action('time_to_read_after'); ?>
 </div>