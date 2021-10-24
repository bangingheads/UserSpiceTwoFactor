<?php if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted?>

<?php

global $us_url_root;
$sessionName = Config::get('session/session_name');
$userID = Session::get($sessionName);
$db = DB::getInstance();
$twoEnabled = $db->query('SELECT twoEnabled FROM users WHERE id = ?',[$userID])->first()->twoEnabled;

if($twoEnabled) {

    // Compatibility with remember me plugin
    if (Input::get('remember') == 'on') {
        $db->query('DELETE FROM users_session WHERE user_id = ? AND uagent = ?', [$userID, Session::uagent_no_version()]);
        Cookie::delete(Config::get('remember/cookie_name'));
        $_SESSION['rememberme'] = true;
    } else {
        $_SESSION['rememberme'] = false;
    }

    $_SESSION['twouser'] = $userID;
    unset($_SESSION[$sessionName]);
    if (isset($_SESSION['google_token'])) {
        unset($_SESSION['google_token']);
    }
    Redirect::to($us_url_root.'usersc/plugins/two_factor/twofactor.php?dest='.Input::get('dest').'&redirect='.Input::get('redirect'));
}

?>