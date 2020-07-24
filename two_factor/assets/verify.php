<?php

require_once('../../../../users/init.php');
require_once('vendor/autoload.php');

use PragmaRX\Google2FA\Google2FA;
$google2fa = new Google2FA();

$responseAr = array();

$action = "";

//if (!securePage($_SERVER['PHP_SELF'])){die();}

if(isset($_REQUEST['action'])){
    $action = $_REQUEST['action'];
    $responseAr['error'] = false;
    $db = DB::getInstance();
}else{
    returnError('No API action specified.');
}

$currentUser = $user->data();
$loggedIn = (isset($user) && $user->isLoggedIn()) ? true : false;

if(Input::get('action') == "login") {
	if(!isset($_SESSION['twoKeyUsername'])) {
		returnError('Invalid Session');
	}
}

switch($action){
    case "pingEndpoint":
        $responseAr['testResponse'] = 'testData';
        break;
    case "verify2FA":
        $requestAr = requestCheck(['twoCode']);
        $twoQ = $db->query("select twoKey, twoEnabled from users where id = ?", [$currentUser->id]);
        if($twoQ->count() > 0){
            $twoO = $twoQ->results()[0];
            $twoVal = $google2fa->verifyKey($twoO->twoKey, $requestAr['twoCode']);
            if($twoVal){
                $responseAr['2FAValidated'] = true;
                if($twoO->twoEnabled == 0){
                    $db->query("update users set twoEnabled = 1 where id = ?", [$currentUser->id]);
                }
            }else{
                returnError('Incorrect 2FA code.');
            }
        }else{
            returnError('Invalid user or not logged in.');
        }
        break;
    default:
        returnError('Invalid API action specified.');
        break;
}

header('Content-Type: application/json');
echo json_encode($responseAr);

?>