<?php

namespace Geisinger\\SurveyAuth;

use ExternalModules\AbstractExternalModule;

class SurveyAuth extends AbstractExternalModule
{
	private $defaultGrace = 15;
	public $defaultItem = "HTTP_REMOTE_USER";
	
	public function redcap_survey_page_top($project_id, $record, $instrument, $event, $group, $survey)
	{
		$auth = $_GET["auth"];
		$user = $_SERVER["HTTP_REMOTE_USER"];
		$time = time();
		if (empty($auth) || empty($user))
			$this->sendToAuthPage($survey);
		$grace = int($this->getSystemSetting("grace"));
		$grace = $grace == 0 ? $this->defaultGrace : $grace;
		for ($i = 0; $i < $grace; $i++) {
			$validHashList[] = $this->makeHash($survey, $user, $time - (60 * i));
		}
		if (!in_array($auth, $validHashList))
			$this->sendToAuthPage($survey);
	}
	
	public function makeHash($survey, $user, $time)
	{
		$time = round($time / 60) * 60; // rounded to nearest min
		return hash("sha256", "$survey$user$time");
	}
	
	private function sendToAuthPage($survey)
	{
		$url = $this->getUrl("login.php");	
		header("Location: $url&s=$survey");
	}
}
