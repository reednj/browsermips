<?php
/* 
 * Generic include file for connecting to the db server and selecting
 * a database. The only really useful varible that the outside world
 * needs to know about is $dbconn
 *
 * Nathan Reed, 27/09/2007
 */

include 'dbpass.php';

function rybadb_conn()
{

	if($_SERVER['SERVER_NAME'] == 'www.rybazoom.com') {
		$dbhost = 'mysql.rybazoom.com';
		$dbname = 'linkdb';
		$dbuser = RZ_DB_USER;
		$dbpass = RZ_DB_PASS;
	} else {
		$dbhost = 'localhost';
		$dbname = 'linkdb';
		$dbuser = 'linkuser';
		$dbpass = '';
	}

	$dbconn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
	mysql_select_db($dbname) or die ('Error connecting to db');

	return $dbconn;
	
}

function rybadb_close($dbconn)
{
	mysql_close($dbconn);
}

function sqlQuery($dbconn, $query)
{
	$data = null;
	$qr = mysql_query($query, $dbconn);
	
	// if we got some data back then put it into the data
	// structure ready to return
	if(gettype($qr) == 'resource') {
		for($i=0; $i < mysql_num_rows($qr); $i++) {
			$data[$i] = mysql_fetch_array($qr, MYSQL_ASSOC);
		}
	} else {
		// no data back, but it might have been an INSERT or something, so 
		// check that it was successful
		if($qr == true) {
			$data = true;
		}
	}

	return $data;
}

?>