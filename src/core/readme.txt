Hx Core is intended to replace Ig library to solve:

1. Information hiding from content developer (more secure and less side effect)
2. Extensibile without modify base code via implement interface class defined by Hx library (less debug)
3. Able to perform unit test
4. Less maintenance in terms of code complexity and high loosely coupled code design

Hx core is implement SOLID principle

System Requirements:
1. PHP 5.4 or above
2. Apache must enable mod_rewrite
4. All code is process in UTF-8
5. PHP modules:
	a. mbstring
	b. soap
	c. pdo
	d. pdo_mysql
	e. fileinfo
	f. gd
	g. phar
	h. uuid	