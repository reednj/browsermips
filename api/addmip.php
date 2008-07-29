<?php
/*
 * Adds a mip data point to the database.
 *
 * Nathan Reed
 */

include 'dbconn.php';
define('NAME_MAX_LEN', 12);

$status = 'error';

if(isset($_REQUEST['name']) && isset($_REQUEST['mips']) && isset($_REQUEST['platform']) && isset($_REQUEST['browser']) && is_numeric($_REQUEST['mips'])) {
	$dbconn = rybadb_conn();
	
	// need to massage the name a little bit to make sure it is safe
	$name = $_REQUEST['name'];
	$platform = $_REQUEST['platform'];
	$browser = $_REQUEST['browser'];
	$mips = $_REQUEST['mips'];
	
	$name = substr($name, 0, NAME_MAX_LEN);
	$name = str_replace(' ', '', $name);
	$name = str_replace("\n", '', $name);
	$name = str_replace("\r", '', $name);
	
	// remove angle brackets that might be used for html injection
	$name = str_replace('<', '', $name); $name = str_replace('>', '', $name);
	$platform = str_replace('<', '', $platform); $platform = str_replace('>', '', $platform);
	$browser = str_replace('<', '', $browser); $browser = str_replace('>', '', $browser);
	
	

	
	// does this mips value look ok? then we are
	// good to go
	if($mips%1000 != 0 && $mips < 15000) {
		if(addMip($dbconn, $name, $platform, $browser, $mips) != false) {
			$status = 'done';
		} else {
			$status = 'error-sql';
		}
	} else {
		$status = 'error-mip';
	}

	rybadb_close($dbconn);	
} 


print "{'status' : '$status'}";

function addMip($dbconn, $name, $platform, $browser, $mips)
{
	$name = mysql_real_escape_string($name);
	$mips = mysql_real_escape_string($mips);
	$platform = mysql_real_escape_string($platform);
	$browser = mysql_real_escape_string($browser);
	
	// use the ip the date and the users string to generate a key for that user,
	// so they cant flood the site with results
	$userkey = sha1($_SERVER["REMOTE_ADDR"].$_SERVER["HTTP_USER_AGENT"].date('Y-m-d-H'));
	
	$query = "insert mipdata (name, platform, browser, `key`, mips) values ('$name', '$platform', '$browser', '$userkey', $mips)";
	return sqlQuery($dbconn, $query);
}
?>
