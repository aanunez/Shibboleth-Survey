<?php
// Being able to access this page means the user has logged in
// http://localhost.edu/redcap_v99.9.99/ExternalModules/?prefix=shibboleth_survey_auth&page=login&pid=16&s=1234567890
$survey = $_GET["s"];
$time = time();
$item = $module->getSystemSetting("user-item");
$user = $_SERVER[empty($item) ? $module->defaultItem : $item];

// Check for bad configuration or something else went wrong
if (empty($survey) || empty($user))
    die("Unable to authenticate user. Please contact your REDCap administrator.");

// Build hash and redirect to survey
$hash = $module->makeHash($survey, $user, $time);
$base = "http" . (empty($_SERVER["HTTPS"]) ? "" : "s") . "://" . $_SERVER["HTTP_HOST"];

header("Location: $base/surveys/?s=$survey&auth=$hash");
