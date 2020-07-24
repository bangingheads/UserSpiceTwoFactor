<?php if (!in_array($user->data()->id, $master_account)) {
    Redirect::to($us_url_root.'users/admin.php');
} //only allow master accounts to manage plugins!?>

<?php
include "plugin_info.php";
pluginActive($plugin_name);
if (!empty($_POST['plugin_two_factor'])) {
    $token = $_POST['csrf'];
    if (!Token::check($token)) {
        include($abs_us_root.$us_url_root.'usersc/scripts/token_error.php');
    }
}
$token = Token::generate();
?>
<div class="content mt-3">
  <div class="row">
    <div class="col-6 offset-3">
      <h2>Two Factor Authentication Settings</h2><br>
<strong>Please note:</strong> After enabling the plugin, users will need to go to their account.php to enable 2FA on their account.<br><br>

<?php
if(!extension_loaded('imagick')) {
?>
Imagick PHP extension is not loaded, this will cause issues with generating QR code images. For installation instructions please see <a href="https://www.php.net/manual/en/imagick.setup.php">here</a><br><br>
<?php
}
?>

<!-- left -->
<div class="form-group">
  <label for="twofa">Enable Two Factor Authentiction</label>
    <span style="float:right;">
      <label class="switch switch-text switch-success">
      <input id="twofa" type="checkbox" class="switch-input toggle" data-desc="Two Factor" <?php if ($settings->twofa==1) {
       echo 'checked="true"';
      } ?>>
      <span data-on="Yes" data-off="No" class="switch-label"></span>
      <span class="switch-handle"></span>
      </label>
      </span>
</div>


    </div>
  		</div>
  		</div>