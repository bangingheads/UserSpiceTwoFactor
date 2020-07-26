<?php if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted?>

<?php

$sessionName = Config::get('session/session_name');
$userID = Session::get($sessionName);
$db = DB::getInstance();
$twoEnabled = $db->query('SELECT twoEnabled FROM users WHERE id = ?',[$userID])->first()->twoEnabled;

if($twoEnabled) {
    $_SESSION['twouser'] = $userID;
    unset($_SESSION[$sessionName]);
    Redirect::to($us_url_root.'usersc/plugins/two_factor/twofactor.php?dest='.Input::get('dest').'&redirect='.Input::get('redirect'));
}

?>