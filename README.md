# PingID-Survey

## What does it do?

A simple Redcap EM to force the user to login prior to responding on a survey. This module will work with any login method that sets a PHP-accessible header value (i.e. something in $_SERVER like HTTP_REMOTE_USER). This is usually Shibboleth, but other login methods may function similarly. Review system level config prior to using.

## Installing

You can install the module by droping it directly in your modules folder (i.e. `/modules/survey_auth_v1.0.0`).

## TODO

* When the module is enabled we need to one-time generate a salt string to use in the hash
* Do we need to move to the before render hook?
* Testing
