web-starter-kit
===============

This is general purpose client-server web server model. 
- Backend service server: Implement in-house Ioc style (agile development) Hx library
- Frontend web server: Implement AngularJS library

This project consists of two main web sites:
- Production site _/_, (for enduser, this is end product)
- Admistration site _/adm_, (for administrator, configure system setting like database, email, etc.)

Crontab Setup
-------------

Set crontab run once per minute

```scala
/usr/bin/php path-of-project/api/cron.php
```

Build Hx Phar Package
---------------------

Run *script/buildCorePhar.php**, **script/buildVendorPhar.php*, and *script/buildHxExtraPhar.php* respectively 

Credits
-------

###Backend Library
- *[notORM](https://github.com/vrana/notorm)* database tool [Apache 2.0](https://www.apache.org/licenses/LICENSE-2.0.html)
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

* Add basic database creation scripting
* Support database backup to _DropBox_ with encrypted content
* Support REST request from server side
* Add central data repository with distributed micro-server configuration
* Build side project which support android development
* Migrate Ig library implementation to Hx library

License
-------

Copyright (c) 2014-2015 Benjamin Ching

Published under the [MIT](http://opensource.org/licenses/MIT) license.