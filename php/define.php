<?php

###
# @name			Define
# @copyright	2015 by Tobias Reich
###

# Define root
define('LYCHEE', substr(__DIR__, 0, -3));

# Define status
define('LYCHEE_STATUS_NOCONFIG', 0);
define('LYCHEE_STATUS_LOGGEDOUT', 1);
define('LYCHEE_STATUS_LOGGEDIN', 2);

# Define dirs
define('LYCHEE_DATA', LYCHEE . 'data/');
define('LYCHEE_SRC', LYCHEE . 'src/');
define('LYCHEE_UPLOADS', LYCHEE . 'uploads/');
define('LYCHEE_UPLOADS_BIG', LYCHEE_UPLOADS . 'big/');
define('LYCHEE_UPLOADS_MEDIUM', LYCHEE_UPLOADS . 'medium/');
define('LYCHEE_UPLOADS_THUMB', LYCHEE_UPLOADS . 'thumb/');
define('LYCHEE_UPLOADS_IMPORT', LYCHEE_UPLOADS . 'import/');
define('LYCHEE_PLUGINS', LYCHEE . 'plugins/');

define('S3_BUCKET', 'dh-lychee-photos');
define('LYCHEE_S3_UPLOADS_BIG', 'big/');
define('LYCHEE_S3_UPLOADS_MEDIUM', 'medium/');
define('LYCHEE_S3_UPLOADS_THUMB', 'thumb/');
define('LYCHEE_S3_UPLOADS_IMPORT', 'import/');



# Define files
define('LYCHEE_CONFIG_FILE', LYCHEE_DATA . 'config.php');

# Define urls
define('LYCHEE_URL_UPLOADS_BIG', 'uploads/big/');
define('LYCHEE_URL_UPLOADS_MEDIUM', 'uploads/medium/');
define('LYCHEE_URL_UPLOADS_THUMB', 'uploads/thumb/');

define('LYCHEE_S3_URL_UPLOADS_BIG', 'https://s3.amazonaws.com/dh-lychee-photos/big/');
define('LYCHEE_S3_URL_UPLOADS_MEDIUM', 'https://s3.amazonaws.com/dh-lychee-photos/medium/');
define('LYCHEE_S3_URL_UPLOADS_THUMB', 'https://s3.amazonaws.com/dh-lychee-photos/thumb/');


function defineTablePrefix($dbTablePrefix) {

	# This part is wrapped into a function, because it needs to be called
	# after the config-file has been loaded. Other defines are also available
	# before the config-file has been loaded.

	# Parse table prefix
	# Old users do not have the table prefix stored in their config-file
	if (!isset($dbTablePrefix)||$dbTablePrefix==='') $dbTablePrefix = '';
	else $dbTablePrefix .= '_';

	# Define tables
	define('LYCHEE_TABLE_ALBUMS', $dbTablePrefix . 'lychee_albums');
	define('LYCHEE_TABLE_LOG', $dbTablePrefix . 'lychee_log');
	define('LYCHEE_TABLE_PHOTOS', $dbTablePrefix . 'lychee_photos');
	define('LYCHEE_TABLE_SETTINGS', $dbTablePrefix . 'lychee_settings');

}

?>
