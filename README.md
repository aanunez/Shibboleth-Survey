# Shibboleth-Survey-Auth - Redcap External Module

## What does it do?

A simple Redcap external module to force the user to login prior to responding on a survey; optionally, you can verify that they are on the allow list as well. This module will work with any login method that sets a PHP-accessible header value (i.e. something in $_SERVER like HTTP_REMOTE_USER). This is usually Shibboleth, but other login methods may function similarly. Typically on a Redcap instance using some SSO solution all traffic is directed to the SSO sign-on page with the exception of end points like `/api/*` and `/surveys/*` so that they maybe accessed by everyone. When this EM is enabled all traffic to the project's surveys are redirected to a dummy loign page that is not behind the api or survey endpoints. This redirect forces a login to occur, after which the user is then directed to the typical survey page. Review system level config prior to using.

## Installing

You can install the module by droping it directly in your modules folder (i.e. `/modules/shibboleth_survey_auth_v1.0.0`).

## System-Level Configuration

* Grace Period - The length of time to allow the login to be valid. By default this is 15 minutes. This time is checked when any page initally loads, including the completion page.
* Header Field - Location in the `$_SERVER` array where your SSO solution stashes the username. Probably identical to the shibboleth configuration set for your Redcap instance.
