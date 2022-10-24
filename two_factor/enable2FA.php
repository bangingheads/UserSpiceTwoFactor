<?php 
require_once('../../../users/init.php');
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php'; 
require_once('assets/vendor/autoload.php');

if(!isset($user) || !$user->isLoggedIn()){
Redirect::to($us_url_root.'users/login.php');
}

use PragmaRX\Google2FA\Google2FA;

$google2fa = new Google2FA();

if($user->data()->twoKey == NULL) {
	$user->update(["twoKey"=>$google2fa->generateSecretKey()],$user->data()->id);
	Redirect::to($us_url_root.'usersc/plugins/two_factor/enable2FA.php'); //It has to update so refresh the page
}

if($user->data()->twoEnabled == 1) {
	Redirect::to($us_url_root . "users/account.php");
}
$siteName = $db->query("SELECT site_name FROM settings")->first()->site_name;

$google2fa_url = $google2fa->getQRCodeUrl(
    $siteName,
    $user->data()->email,
    $user->data()->twoKey
);
?>
<script type="text/javascript" src="<?php echo $us_url_root . "usersc/plugins/two_factor/assets/qrcode.min.js"?>"></script>
<section class="cid-qABkfm0Pyl mbr-fullscreen mbr-parallax-background" id="header2-0" data-rv-view="1854">

    <div class="mbr-overlay" style="opacity: 0.4; background-color: rgb(40, 0, 60);"></div>

    <div class="container">
        <div class="row">
            <div class="mbr-white col-md-10">

                <div class="well">
                    <div class="row">
                        <div class="col-xs-12 col-md-9">
                            <h1>Enable Two Factor Authentication</h1>
                            <?php if ($user->data()->twofaforced == 1 || $settings->forcetwofa == 1) {
                                echo "<p>Your site administrator has required Two Factor Authentication.</p>";
                            }?>
                            <p>Scan this QR code with your authenticator app or input the key: <b><?php echo $user->data()->twoKey; ?></b></p>
							<p>You can use any standard TOTP MFA app like Google Authenticator or Authy.</p>
                            <p><div id="qrcode"></div></p>
                            <p>Then enter your 6 digit key here:</p>
                            <p>
                                <table border="0">
                                    <tr>
                                        <td><input class="form-control" placeholder="2FA Code" type="text" name="twoCode" id="twoCode" size="10"></td>
                                        <td><button id="twoBtn" class="btn btn-primary">Verify</button></td>
                                    </tr>
                                </table>
                            </p>

                        </div>
                    </div>
                </div>

            </div> <!-- /container -->

        </div> <!-- /#page-wrapper -->
    </div>
</section>

<!-- footers -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->
<script type="text/javascript">
new QRCode(document.getElementById("qrcode"), "<?php echo $google2fa_url?>");
</script>
<script>
    $(document).ready(function() {
        $("#twoBtn").click(function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?=$us_url_root;?>usersc/plugins/two_factor/assets/verify.php",
                data: {
                    action: "verify2FA",
                    twoCode: $("#twoCode").val()
                },
                success: function(result) {
                    if(!result.error){
                        alert('2FA verified and enabled.');
						window.location.href = '<?=$us_url_root?>users/account.php';
                    }else{
                        alert('Incorrect 2FA code.');
                    }
                },
                error: function(result) {
                    alert('There was a problem verifying 2FA.');
                }
            });
        });
    });
</script>
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; // currently just the closing /body and /html ?>
