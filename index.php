<?php
 /*
  * Connect to the database and generate the page. Basically the only dynamic data, is the
  * recent results panel.
  */
include './api/dbconn.php';

$dbconn = rybadb_conn();
$recentMips = getRecentMips($dbconn);
rybadb_close($dbconn);

function getRecentMips($dbconn)
{
	return sqlQuery($dbconn, 'select name, mips from mipdata order by time desc limit 10');
}

?>
<html>
<head>
<title>BrowserMips Calcuation</title>
<script src='./js/prototype.js' type='text/javascript' ></script>
<script src='./js/browser.js' type='text/javascript' ></script>
<script src='./js/mips.js' type='text/javascript' ></script>

<link rel="stylesheet" type="text/css" href="./css/default.css"/>
<link rel="stylesheet" type="text/css" href="./css/mips.css"/>
</head>

<body onload='init()'>

<div class='bm-header'>
	<h1 class='bm-h-title'>browser-mips</h1>
	<span class='bm-sub-header'>how fast is your browser?</span>	
</div>

<div class='bm-body'>
	<div class='bm-mip-box left'>
		<span>Your Speed:</span>
		<div id='bm-mip-data' class='bm-mip-count'>...</div>
		<div class='bm-mip-box-foot'><a href='javascript:calcMips();'> recalculate </a> | <a href='javascript:MipsUi.showSubmit();'> submit </a></div>
		
		<div id='bm-sub-form' class='bm-form' style='display:none'>
			<form onsubmit='MipsUi.submitScore(); return false;'>
				name: <input id='bm-f-txt' type='text' size='12'/>
				<input type='submit' value='submit'/>
				<input type='button' value='cancel' onclick='MipsUi.hideSubmit();'/>
			</form>
		</div>
		
		<div id='bm-f-loading' class='bm-form' style='display:none'>saving...</div>
		
		<span>System Information:</span>
		<div class='bm-indent'>
			<div><i>Browser:</i> <span id='bm-b-data-b'></span></div>
			<div><i>Operating System:</i> <span id='bm-b-data-os'></span></div>
		</div>
	</div>
	
	<div class='bm-top-mips left'>
		<span>Recent Results:</span>		
		<div class='bm-indent'>
			<?php
				if($recentMips != false) {
					foreach($recentMips as $mip) {
						$name = $mip['name'];
						$mipval = $mip['mips'];
						print "<div>$name - $mipval</div>";
					}
				}
			?>
		</div>
	</div>
	
	<!--
	<div class='bm-recent-mips left'>
		<span>Top Speeds:</span>		
		<div class='bm-indent'>
			<ol class='bm-top-list'>
				<li>10123 - Firefox3.0.1</li>
				<li>9645 - Internet Explorer 7.0.1</li>
				<li>5436 - Firefox2.0.0.16</li>
				<li>4352 - Firefox3.0.1</li>
				<li>356 - Firefox3.0.1</li>
				<li>37 - Firefox3.0.1</li>
			</ol>
		</div>
	</div>
-->
	
	<div class='clear'></div>
	
</div>

<div class='bm-footer'>

	<div class='bm-footer-row'>
		<div class='bm-footer-col'>
			<span class='bm-footer-col-head'>What is this?</span>
		
			<p>
			A completely unscientific way of measuring the speed your browser executes javascript. It is 
			based on the linux concept of BogoMips.
			</p>
		
		</div>
	
		<div class='bm-footer-col'>
			<span class='bm-footer-col-head'>What does my number mean?</span>
		
			<p>How fast your browser can execute javascript. Sort of.</p>
			<p>
			The number represents how many times your browser can execute a loop in 150ms. The rating will
			change dramatically depending on your browser, the load on your system and other random things, 
			but generally a higher number indicates a faster computer.
			</p>
		</div>
	
		<div class='bm-footer-col'>
			<span class='bm-footer-col-head'>Why should I care?</span>
			<p>
			If you're a web developer, then you can use this number to work out if the user can handle 
			visual effects. This is a good thing, and if you've ever seen an iPhone try to handle a poorly written fade
			effect, you'll know what I mean. 
			</p>
			<p>
			Generally if the rating is under 100, then hardcore javascript is probably not a good idea.
			</p>
		</div>
	
		<div class='clear'></div>
	</div>

</div>

<div class='bm-sig'>Nathan Reed (c) 2008 | <a href='http://www.servralert.com'>servralert.com</a></div>	

</body>

</html>