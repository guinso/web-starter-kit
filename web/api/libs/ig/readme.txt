version 0.4.1	- support secure connection
				- fix error message capture bug

version 0.4
EmailUtil		- revise send email API
				- update <queue_email> data column
AuthorizeUtil	- add check authorization by user ID and function names

version 0.3.4
LogUtil			- add support using user defined db and pdo (optional)
Util			- add optional DB and PDO to 'getNextRunningNumber(...)' and 'getNextJobId(...)'

version 0.3.3
Util			- add run sql script routine

version 0.3.2
AuthorizeUtil 	- add support pass function names as array
ExcelUtil 		- add support save excel to local
FileUtil
				- better handling on upload directory
				- add support on checking attachment validity
				- add support on recursive delete directory or a single file
LogUtil			
				- support get last written record
				- support check new record difference
Util 			- store temporary directory and template directory

version 0.3.1
Add checktimeout() routime for ScheduleUtil to handle non-recoverable exception

version 0.3
Add FileUtil module

version 0.2.4
ScheduleUtil = fix bugs on */x

version 0.2.3
AuthorizationUtil - add 'get related authorized users' function

version 0.2.2
fix authorization bugs

version 0.2.1
optimize checkAuthorization performance

version 0.2a