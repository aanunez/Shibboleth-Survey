# Survey-Auth
Geisinger Specific Redcap EM 


## Notes

* Enable on a project, rather than use the public link you use a link that points at the EM's index page w/ same survey ID attached
* Offer a shorter version of the URL too
* When user visits the old link, block them unless they have a hash set
* When visiting new page they will hit the PING id page which will force a login
* After login we should be able to grab user name from headers, construct some hash and redirect them to the normal link with that hash
* Hash should be based on time, user, and survey.
