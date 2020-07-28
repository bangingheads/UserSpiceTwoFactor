<?php if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted ?>
<?php
global $settings;
global $user;
global $us_url_root;
if($settings->twofa) {
    if($user->data()->twoEnabled) { ?>
        <p><a href="<?=$us_url_root?>/usersc/plugins/two_factor/disable2FA.php" class="btn btn-primary btn-block">Disable 2FA</a></p>
   <?php } else { ?>
    <p><a href="<?=$us_url_root?>/usersc/plugins/two_factor/enable2FA.php" class="btn btn-primary btn-block">Enable 2FA</a></p>
   <?php }
}
