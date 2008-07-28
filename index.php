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
	return sqlQuery($dbconn, 'select name, platform, browser, mips from mipdata order by time desc limit 9');
}

?>
<html>
<head>
<title>BrowserMips - how fast is your browser? </title>
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
				name: <input id='bm-f-txt' type='text' size='12' value='anonymous'/>
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
		<div id='bm-mip-r' class='bm-indent'>
			<?php
				if($recentMips != false) {
					foreach($recentMips as $mip) {
						$name = $mip['name'];
						$mipval = $mip['mips'];
						$platform = $mip['platform'];
						$browser = $mip['browser'];
						print "<div>$mipval - $name ($browser, $platform)</div>";
					}
				}
			?>
		</div>
	</div>
	
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

<?php if($_SERVER['SERVER_NAME'] != 'localhost') { ?>
	
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-57284-5");
pageTracker._initData();
pageTracker._trackPageview();
</script>

<?php } ?>
</body>

</html>