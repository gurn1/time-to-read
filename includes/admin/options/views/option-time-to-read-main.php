<?php
/**
 * Main admin view for option page time to read
 * 
 * @since 1.0.0
 */

?>

<div class="wrap">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

  <form method="post" action="options.php">
    <?php
      settings_fields($settings_section_path);
      do_settings_sections($settings_section_path); // ← simplified helper
      submit_button();
    ?>
  </form>
</div>