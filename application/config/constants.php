<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');



define('IS_SSL', true);
define('EDGE_WRITING_URL',	'https://www.edgewritings.com/');
#define('IS_SSL', false);
#define('EDGE_WRITING_URL',	'http://192.168.219.144/');
define('WRITING_PREMIUM_SECRET_KET', 'isdyf3584MjAI419BPuJ5V6X3YT3rU3C');

define('MUSE_PREP_URL',	'https://www.museprep.com/');
#define('MUSE_PREP_URL',	'http://jake4.iptime.org:9000/');
#define('MUSE_PREP_URL',	'http://192.168.123.131:9000/');


define('DOC_UPLOAD_PATH', './uploads/tmp/');
define('USER_DOC_PATH', './uploads/user_doc/');
define('USER_TXT_PATH', './uploads/user_txt/');
#define('DOC_UPLOAD_PATH', '/var/www/uploads/editor_doc/');
#define('USER_DOC_PATH', '/var/www/uploads/user_doc/');
#define('USER_TXT_PATH', '/var/www/uploads/user_txt/');


//define('DOC_UPLOAD_PATH', 'C:/uploads/');

define('MY_DOMAIN', 'http://ec2-54-199-7-211.ap-northeast-1.compute.amazonaws.com/');



/* End of file constants.php */
/* Location: ./application/config/constants.php */
