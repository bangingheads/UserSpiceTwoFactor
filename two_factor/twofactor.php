<?php
require_once '../../../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
require_once 'assets/vendor/autoload.php';
use PragmaRX\Google2FA\Google2FA;
$google2fa = new Google2FA();
?>
<?php
$errors = [];
$successes = [];
if (@$_REQUEST['err']) $errors[] = $_REQUEST['err']; // allow redirects to display a message
if($user->isLoggedIn()) Redirect::to($us_url_root.'index.php');

if (isset($_POST['twoCode'])) {
  $token = Input::get('csrf');
  if(!Token::check($token)){
    include($abs_us_root.$us_url_root.'usersc/scripts/token_error.php');
  }
	$twoPassed = false;
  $twoQ = $db->query("SELECT twoKey FROM users WHERE id = ? and twoEnabled = 1", [$_SESSION['twouser']]);
  if($twoQ->count() > 0){
    $twoKey = $twoQ->results()[0]->twoKey;
    $twoCode = trim(Input::get('twoCode'));
    if($google2fa->verifyKey($twoKey, $twoCode)){
      $twoPassed = true;
    }
  }
	else { //Two Factor is Disabled
	  $twoPassed = true;
	}
  //Finish Login Process
    if($twoPassed){
      $sessionName = Config::get('session/session_name');
      Session::put($sessionName, $_SESSION['twouser']);
      unset($_SESSION['twouser']);
      $dest = sanitizedDest('dest');
      # if user was attempting to get to a page before login, go there

      if (!empty($dest)) {
        $redirect=htmlspecialchars_decode(Input::get('redirect'));
        if(!empty($redirect) || $redirect!=='') Redirect::to($redirect);
        else Redirect::to($dest);
      } elseif (file_exists($abs_us_root.$us_url_root.'usersc/scripts/custom_login_script.php')) {

        # if site has custom login script, use it
        # Note that the custom_login_script.php normally contains a Redirect::to() call
        require_once $abs_us_root.$us_url_root.'usersc/scripts/custom_login_script.php';
      } else {
        if (($dest = Config::get('homepage')) ||
        ($dest = 'account.php')) {
          #echo "DEBUG: dest=$dest<br />\n";
          #die;
          Redirect::to($dest);
        }
      }

      } else {
        $msg = lang("SIGNIN_FAIL");
        $msg2 = lang("SIGNIN_PLEASE_CHK");
        $errors[] = '<strong>'.$msg.'</strong> Please check your mobile device for the correct 6 digit code.';
      }
}
    if (empty($dest = sanitizedDest('dest'))) {
      $dest = '';
    }
    $token = Token::generate();
    ?>
    <div id="page-wrapper">
      <div class="container">
        <?=resultBlock($errors,$successes);?>
        <div class="row">
          <div class="col-sm-12">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <form name="login" id="login-form" class="form-signin" action="twofactor.php" method="post" onsubmit="return validateInput()">
              <h2 class="form-signin-heading"></i>Two Factor Authentication</h2>
              <input type="hidden" name="dest" value="<?= $dest ?>" />
				<div class="form-group">
					<label for="twoCode">2 Factor Authentication is enabled for your account. Please enter the 6 digit code from your mobile device.</label>
					<input type="text" class="form-control" name="twoCode" id="twoCode"  placeholder="2FA Code" autocomplete="off" onkeyup="this.value=this.value.replace(/[^\d]/,'')" maxlength="6" size="6">
			</div>
                <input type="hidden" name="csrf" value="<?=$token?>">
                <input type="hidden" name="redirect" value="<?=Input::get('redirect')?>" />
                <button class="submit  btn  btn-primary" id="next_button" type="submit"><i class="fa fa-sign-in"></i> <?=lang("SIGNIN_BUTTONTEXT","");?></button>
              </form>
            </div>
          </div>
          </div>
        </div>

        <?php require_once $abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/container_close.php'; //custom template container ?>

        <!-- footers -->
        <?php require_once $abs_us_root.$us_url_root.'users/includes/page_footer.php'; // the final html footer copyright row + the external js calls ?>

        <!-- Place any per-page javascript here -->
        <script>
		    function validateInput(){
          var result = false;
          if (($('#twoCode').val().length == 6)) {
            result = true;
          }
          return result;
        }
        </script>

        <?php require_once $abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/footer.php'; //custom template footer?>
