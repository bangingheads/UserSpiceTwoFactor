<?php
require_once('../../../users/init.php');
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php'; 
if(!isset($user) || !$user->isLoggedIn()){
    Redirect::to($us_url_root.'users/login.php');
}

if($user->data()->twoEnabled == 0) {
	Redirect::to($us_url_root . "users/account.php");
}

if(Input::get('confirmed')) {
    $token = Input::get('csrf');
    if(!Token::check($token)){
      include($abs_us_root.$us_url_root.'usersc/scripts/token_error.php');
    }
    $db->query('UPDATE users SET twoEnabled = 0 WHERE id = ?', [$user->data()->id]);
    Redirect::to($us_url_root . "users/account.php");
}
$token = Token::generate();
?>
<div class="mbr-overlay" style="opacity: 0.4; background-color: rgb(40, 0, 60);"></div>
<div class="container">
<div class="row">
    <div class="mbr-white col-md-10">

        <div class="well">
            <div class="row">
                <div class="col-xs-12 col-md-9">
                    <h1>Disable Two Factor</h1>
                    <p>Are you sure?</b></p>
                    <p>You will be leaving your account less secure and will have to reenable through the account portal to use two factor again.</p><br><br>
                    <a class="btn btn-primary" href="<?=$us_url_root?>users/account.php" role="button">Leave 2FA Enabled</a>
                    <a class="btn btn-danger" href="<?=$us_url_root?>usersc/plugins/two_factor/disable2FA.php?confirmed=1&csrf=<?=$token?>" role="button">Disable 2FA</a>
                    
                </div>
            </div>
        </div>

    </div> <!-- /container -->

</div> <!-- /#page-wrapper -->
</div>