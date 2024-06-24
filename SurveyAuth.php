<?php

namespace Geisinger\\SurveyAuth;

use ExternalModules\AbstractExternalModule;

class SurveyAuth extends AbstractExternalModule
{
	public function redcap_survey_page_top($project_id, $record, $instrument, $event, $group, $survey)
	{
		$auth = $_GET["auth"];
		$user = $_SERVER["HTTP_REMOTE_USER"];
		$time = time();
		if (empty($auth) || empty($user))
			$this->sendToAuthPage($survey);
		$validHashList = [ // 30 min total grace period
			$this->makeHash($survey, $user, $time),
			$this->makeHash($survey, $user, $time-600),
			$this->makeHash($survey, $user, $time-1200)
		];
		if (!in_array($auth, $validHashList))
			$this->sendToAuthPage($survey);
	}
	
	public function makeHash($survey, $user, $time)
	{
		$time = round($time / 60, -1) * 60; // rounded to 10mins
		return hash('sha256', "$survey$user$time");
	}
	
	private function sendToAuthPage($survey)
	{
		$url = $this->getUrl("login.php");	
		header("Location: $url&s=$survey");
	}
}
