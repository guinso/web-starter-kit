<?php 
//*************************************** CORE API ********************************************
//Authentication
getApi()->get('/current-user', array('AdmLogin', 'getStatus'), EpiApi::external);
getApi()->get('/logout', array('AdmLogin', 'logout'), EpiApi::external);
getApi()->post('/login', array('AdmLogin', 'login'), EpiApi::external);

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
/*
//upload
getApi()->post('/upload-file', array('Util', 'uploadFile'), EpiApi::external);

//new file upload-download
getApi()->post('/file-upload', array('FileUtil', 'uploadFile'), EpiApi::external);
getApi()->get('/file-download/(\w+)', array('FileUtil', 'downloadFile'), EpiApi::external);
*/
//**************************** END CORE API *************************************

getApi()->get('/update-available', array('IgModUpdate', 'getAvailableUpdate'), EpiApi::external);
getApi()->post('/update-run', array('IgModUpdate', 'executeUpdate'), EpiApi::external);
?>