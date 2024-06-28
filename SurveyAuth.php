<?php

namespace Geisinger\SurveyAuth;

use ExternalModules\AbstractExternalModule;

class SurveyAuth extends AbstractExternalModule
{
	private $defaultGrace = 15;
	public $defaultItem = "HTTP_REMOTE_USER";

	public function redcap_module_system_enable($version)
	{
		$salt = $this->getSystemSetting("salt");
		$pepper = $this->getSystemSetting("pepper");
		if (empty($salt))
			$this->setSystemSetting("salt",  base64_encode(random_bytes(32)));
		if (empty($pepper))
			$this->setSystemSetting("pepper", base64_encode(random_bytes(32)));
	}

	public function redcap_survey_page_top($project_id, $record, $instrument, $event, $group, $survey)
	{
		$auth = $_GET["auth"];
		$item = $this->getSystemSetting("user-item");
		$user = $_SERVER[empty($item) ? $this->defaultItem : $item];
		$time = time();
		if (empty($auth) || empty($user))
			$this->sendToAuthPage($survey);
		$grace = intval($this->getSystemSetting("grace"));
		$grace = $grace == 0 ? $this->defaultGrace : $grace;
		for ($i = 0; $i < $grace; $i++) {
			$validHashList[] = $this->makeHash($survey, $user, $time - (60 * $i));
		}
		if (!in_array($auth, $validHashList))
			$this->sendToAuthPage($survey);
	}

	public function makeHash($survey, $user, $time)
	{
		$salt = $this->getSystemSetting("salt");
		$pepper = $this->getSystemSetting("pepper");
		$time = round($time / 60) * 60; // rounded to nearest min
		return hash("sha256", "$salt$survey$user$time$pepper");
	}

	private function sendToAuthPage($survey)
	{
		$url = $this->getUrl("login.php");
		header("Location: $url&s=$survey");
	}
}
