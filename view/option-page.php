<div class="col-md-12">
  <form method="post" class="form-horizontal" action="options.php">
    <?php
    settings_fields( 'wp_vivid_seats_settings' );
    do_settings_sections( 'wp_vivid_seats_settings' );
    ?>
    <div class="form-group">
      <label for="wp_vivid_seats_acc_id" class="col-sm-3">
      Account SID
      </label>
      <div class="col-sm-8">
          <input id="wp_vivid_seats_acc_id" class="form-control" value="<?php echo get_option('wp_vivid_seats_acc_id'); ?>" type="text" name="wp_vivid_seats_acc_id">
      </div>
    </div>

    <div class="form-group">
      <label for="wp_vivid_seats_auth_token" class="col-sm-3">
      Auth Token
      </label>
      <div class="col-sm-8">
          <input id="wp_vivid_seats_auth_token" class="form-control" value="<?php echo get_option('wp_vivid_seats_auth_token'); ?>" type="text" name="wp_vivid_seats_auth_token">
      </div>
    </div>


    <input class="btn btn-primary" type="submit" value="Save Settings">
  </form>
</div>