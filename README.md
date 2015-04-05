web-starter-kit
===============

This is general purpose client-server web server model. 
This project collect various libraries to unify and simplified all services into single library package (**Ig lib**) for developers:
* **REST API**	headless data (_JSON_) communication, general service for all modern application client (html, android, ios, .NET, etc.)
* **Database**	one time setup, no need to hardcord database username and password during development (audit trail ready, key-value service)
* **Email**		SMTP based email agent to sent email, independent from site server email configuration
* **PDF**		Template based markup scripting for easier PDF creation without using PHP coding instead of simple template scripting (sandbox model, reusable JSON data from REST)
* **MS Excel**	Provide Excel generator (.xlsx) for general data export (need PHP scripting)
* **HTML**		Using _AngularJS_ to develop dynamic front-end single page design web application

This project consists of two main web sites:
- Production site _/_, (for enduser, this is end product)
- Admistration site _/adm_, (for administrator, configure system setting like database, email, etc.)

Project Structure
-----------------

* **Web** _(targeted web deploy directory)_
	* **adm** _(back office)_
		* **api** _(back office REST API)_
			* **update** _(back office update script)_
			* **module** _(back office REST API module)_
		* **module** _(back office html module)_
	* **api** _(REST API)_
		* **lib** _(PHP libraries collection)_
			* **Ig** _(unified 2nd tier library to access PHP library, recommend to use this instead of direct access primitive PHP libraries)_
		* **module** _(REST API module)_
		* **template** _(default template directory, configurable)_
		* **upload** _(default upload directory, configurable)_
		* **tmp** _(default cache directory, configuration)_
		* config.php _(system configuration file, used for keeping production and back office setup)_
	* **lib** _(HTML library)_
	* **module** _(html module)_
	* **page** _(main page design, this where you design main page frame layout)_
	* **partial** _(AngularJS reusable components)_
	* log.md _(write change log or any info for end user to view)_
	
Crontab Setup
-------------

Set crontab run once per minute

```scala
/usr/bin/php path-of-project/api/cron.php
```

Credits
-------

###Backend Library
- *[notORM](https://github.com/vrana/notorm)* database tool [Apache 2.0](https://www.apache.org/licenses/LICENSE-2.0.html)
- *[epiphany](https://github.com/jmathai/epiphany)* mirco PHP framework [BSD](http://opensource.org/licenses/BSD-3-Clause)
- *[phpExcel](https://github.com/PHPOffice/PHPExcel)* spreadsheet library [LGPL](https://github.com/PHPOffice/PHPExcel/blob/master/license.md)
- *[phpMailer](https://github.com/PHPMailer/PHPMailer)* email library [LGPL 2.1](http://www.gnu.org/licenses/lgpl-2.1.html)
- *[jShrink](https://github.com/tedious/JShrink)* javasrcipt compressor library [BSD](http://opensource.org/licenses/BSD-3-Clause)
- *[guzzle](https://github.com/guzzle/guzzle)* interserver communication framework [MIT](http://opensource.org/licenses/MIT)
- *[parsedown](https://github.com/erusev/parsedown)* markdown parser tool [MIT](http://opensource.org/licenses/MIT)
- *[phpjasperXML](https://github.com/SIMITGROUP/phpjasperxml)* jasper report generator __open source, not decided yet__
- *[tcpdf](http://www.tcpdf.org/)* pdf generator [LGLP v3](https://www.gnu.org/licenses/lgpl.html)
- *[RainTpl](http://www.raintpl.com) Template engine [MIT](http://opensource.org/licenses/MIT)

###Frontend Library
- *[Animate CSS](http://daneden.github.io/animate.css/)* collection of CSS animation library [MIT](http://opensource.org/licenses/MIT)
- *[Font Awesome](http://fontawesome.io)* font library [SIL OFL](http://fontawesome.io/license/)
- *[AngularJS](https://angularjs.org)* databinding tool [MIT](http://opensource.org/licenses/MIT)
- *[Bootstrap](http://getbootstrap.com)* web GUI library [MIT](http://opensource.org/licenses/MIT)
- *[UI Bottstrap](https://angular-ui.github.io/bootstrap/)* web GUI library [MIT](http://opensource.org/licenses/MIT)
- *[Jquery](https://jquery.org)* javascript library [MIT](http://opensource.org/licenses/MIT)
- *[JqueryUI](https://jqueryui.com)* jquery web GUI library [Jquery License](https://jquery.org/license/)
- *[moment](http://momentjs.com)* javascript time library [MIT](http://opensource.org/licenses/MIT)
- *[bootstrap datepicker](https://bootstrap-datepicker.readthedocs.org/en/latest/)* web GUI library [Apache 2.0](https://www.apache.org/licenses/LICENSE-2.0.html)
- *[bootstrap datetimepicker](http://eonasdan.github.io/bootstrap-datetimepicker/)* web GUI library [MIT](http://opensource.org/licenses/MIT)
- *[bootstrap notify](https://github.com/mouse0270/bootstrap-notify)* web GUI notification library [MIT](http://opensource.org/licenses/MIT)
- *[metis menu](https://github.com/onokumus/metisMenu)* web GUI interactive menu builder library [MIT](http://opensource.org/licenses/MIT)

###Special Thanks
- *[SB Admin 2](http://startbootstrap.com/template-overviews/sb-admin-2/)* Bootstrap Theme for inspiring sidebar menu design
- *[Wifeo CSS artwork](http://www.wifeo.com/code/22-pure-css-loader.html)* Used as page initial loading component

Milestone
---------

* Support server update from version server
* Add basic database creation scripting
* Support database backup to _DropBox_ with encrypted content
* Support REST request from server side
* Add central data repository with distributed micro-server configuration
* Build side project which support android development

License
-------

Copyright (c) 2014-2015 Benjamin Ching

Published under the [MIT](http://opensource.org/licenses/MIT) license.