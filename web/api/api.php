<?php 
//*************************************** CORE API ********************************************
//Authentication
getApi()->get('/current-user', array('LoginUtil', 'getCurrentUser'), EpiApi::external);
getApi()->get('/logout', array('LoginUtil', 'logoutUser'), EpiApi::external);
getApi()->post('/login', array('LoginUtil', 'loginUser'), EpiApi::external);
getApi()->get('/login-log', array('UserAccount', 'getActivityLog'), EpiApi::external);
getApi()->get('/login-log-count', array('UserAccount', 'getActivityLogCount'), EpiApi::external);

//Authorization
getApi()->get('/access-right', array('Access', 'getAccessRight'), EpiApi::external);
getApi()->get('/access-right-list', array('Access', 'getAccessList'), EpiApi::external);
getApi()->get('/access-matrix', array('Access', 'getMatrix'), EpiApi::external);
getApi()->put('/access-matrix/(\w+)', array('Access', 'updateMatrixGroup'), EpiApi::external);
getApi()->get('/access-matrix-rebuild', array('Access', 'rebuildAccessMatrix'), EpiApi::external);

//role
getApi()->get('/role', array('Role', 'get'), EpiApi::external);
getApi()->post('/role-bulk', array('Role', 'putBulk'), EpiApi::external);

//user account
getApi()->get('/user', array('UserAccount', 'get'), EpiApi::external);
getApi()->get('/user-cnt', array('UserAccount', 'getCount'), EpiApi::external);
getApi()->get('/user-log', array('UserAccount', 'getLog'), EpiApi::external);
getApi()->get('/user-log-cnt', array('UserAccount', 'getLogCount'), EpiApi::external);
getApi()->get('/file-user/(\w+)', array('UserAccount', 'downloadFile'), EpiApi::external);
getApi()->get('/user/(\w+)', array('UserAccount', 'getById'), EpiApi::external);
getApi()->post('/user', array('UserAccount', 'post'), EpiApi::external);
getApi()->put('/user/(\w+)', array('UserAccount', 'put'), EpiApi::external);
getApi()->delete('/user/(\w+)', array('UserAccount', 'delete'), EpiApi::external);
getApi()->post('/change-pwd', array('UserAccount', 'changePwd'), EpiApi::external);

//function
getApi()->get('/function', array('Func', 'get'), EpiApi::external);

//language
getApi()->get('/lan-code', array('Lan', 'get'), EpiApi::external);
getApi()->post('/lan-code', array('Lan', 'post'), EpiApi::external);

//scheduler Task
getApi()->get('/sch', array('Schedule', 'get'), EpiApi::external);
getApi()->get('/sch-cnt', array('Schedule', 'getCount'), EpiApi::external);
getApi()->get('/sch/(\w+)', array('Schedule', 'getById'), EpiApi::external);
getApi()->post('/sch-bulk', array('Schedule', 'updateBulk'), EpiApi::external);
getApi()->get('/sch-run', array('Schedule', 'run'), EpiApi::external);
getApi()->get('/sch-run/(\w+)', array('Schedule', 'runById'), EpiApi::external);
//schedule log
getApi()->get('/sch-log', array('ScheduleLog', 'get'), EpiApi::external);
getApi()->get('/sch-log-cnt', array('ScheduleLog', 'getCount'), EpiApi::external);

//upload
getApi()->post('/upload-file', array('Util', 'uploadFile'), EpiApi::external);

//new file upload-download
getApi()->post('/file-upload', array('FileUtil', 'uploadFile'), EpiApi::external);
getApi()->get('/file-download/(\w+)', array('FileUtil', 'downloadFile'), EpiApi::external);

//**************************** END CORE API *************************************
?>