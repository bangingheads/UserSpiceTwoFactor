<?php

if(currentPage() == "join.php") {
    require_once($abs_us_root.$us_url_root.'usersc/plugins/two_factor/assets/vendor/autoload.php');
    $google2fa = new PragmaRX\Google2FA\Google2FA();
}
