<?php

if(currentPage() == "join.php") {
    require_once($abs_us_root.$us_url_root.'usersc/plugins/two_factor/assets/vendor/autoload.php');
    $google2fa = new PragmaRX\Google2FA\Google2FA();
}

if(isset($user) && $user->isLoggedIn() && currentPage() != "enable2FA.php" && (($user->data()->twoEnabled != 1 && $user->data()->twofaforced == 1) || ($user->data()->twoEnabled == 0 && $settings->forcetwofa == 1))) {
    Redirect::to($us_url_root . "usersc/plugins/two_factor/enable2FA.php");
} 