<?php

global $db, $userdetails;

$checked = isset($_POST['twofa']);

$db->update("users", $userdetails->id, ["twofaforced"=>$checked]);