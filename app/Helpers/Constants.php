<?php

// Connection Request Status
define('STATUS_REQUEST_SENT', 1);
define('STATUS_REQUEST_WITHDRAWN', 2);
define('STATUS_REQUEST_ACCEPTED', 3);

// Response Status Codes
define('ERROR_401', 401);
define('ERROR_400', 400);
define('SUCCESS_200', 200);
define('ERROR_500', 500);

// Messages
define('GENERAL_ERROR_MESSAGE', 'Operation Failed');
define('GENERAL_SUCCESS_MESSAGE', 'Data Saved Successfully');
define('GENERAL_FETCHED_MESSAGE', 'Data Fetched Successfully');
define('GENERAL_UPDATED_MESSAGE', 'Data Updated Successfully');
define('GENERAL_DELETED_MESSAGE', 'Data Deleted Successfully');
define('USERNAME_NOT_AVAILABLE_MESSAGE', 'Username is not available');
define('USERNAME_AVAILABLE_MESSAGE', 'Username is available');
