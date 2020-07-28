<?php if(count(get_included_files()) ==1) die(); //Direct Access Not Permitted?>

<?php
//This hook is here because of core code I have asked to remove
global $abs_us_root;
global $us_url_root;
require_once($abs_us_root.$us_url_root.'usersc/plugins/two_factor/assets/vendor/autoload.php');
?>