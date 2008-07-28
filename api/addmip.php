<?php
/*
 * Adds a mip data point to the database.
 *
 * Nathan Reed
 */

include 'dbconn.php';

define('NAME_MAX_LEN', 12);

if(isset($_REQUEST['name']) && isset($_REQUEST['mips']) && is_numeric($_REQUEST['mips'])) {
	$dbconn = rybadb_conn();
	
	// need to massage the name a little bit to make sure it is safe
	$name = $_REQUEST['name'];
	$mips = $_REQUEST['mips'];
	
	$name = substr($name, 0, NAME_MAX_LEN);
	$name = str_replace(' ', '', $name);
	$name = str_replace("\n", '', $name);
	$name = str_replace("\r", '', $name);
	
	// does this mips value look ok? then we are
	// good to go
	if($mips%1000 != 0 && $mips < 15000) {
		addMip($dbconn, $name, $mips);
	}

	rybadb_close($dbconn);	
} 


print "{'status':'done'}";

function addMip($dbconn, $name, $mips)
{
	$name = mysql_real_escape_string($name);
	$mips = mysql_real_escape_string($mips);
	sqlQuery($dbconn, "insert mipdata (name, mips) values ('$name', $mips)");
}
?>