<?php if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted?>

<?php

global $user;
if($user->data()->twoEnabled) {
    $twouser = $user->data()->id;
    $_SESSION['twouser'] = $twouser;
    $sessionName = Config::get('session/session_name');
    unset($_SESSION[$sessionName]);
    Redirect::to($us_url_root.'usersc/plugins/two_factor/twofactor.php?dest='.Input::get('dest').'&redirect='.Input::get('redirect'));
}

?>