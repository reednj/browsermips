/*
 * browsermips js. handles various ui tasks, and does the work
 * of submitting the users result to the server
 */
function mip_init()
{
	// set the browser information
	$('bm-b-data-b').innerHTML = BrowserDetect.browser + ' ' + BrowserDetect.version;
	$('bm-b-data-os').innerHTML = BrowserDetect.OS;

	// calcuate the mip rating, and show it to the user. We wait 500ms for the intial rating
	// to make it more accurate. Then update every 5 seconds after that.
	setTimeout("MipsUi.setMips(BrowserMips.calcuate());", 500);
	setInterval("MipsUi.setMips(BrowserMips.calcuate());", 5000);
	
	// submit the score automatically after 10 seconds.
	setTimeout("MipsUi.submitScoreAuto();", 10000);

}

// controls the ui, handles user events...
var MipsUi = {
	AUTO_COOKIE: 'bm_auto',

	setMips: function(str) {
		$('bm-mip-data').innerHTML = str;
	},
	
	insertMip: function(str) {
		$('bm-mip-r').innerHTML = ('<div>'+str+'</div>' + $('bm-mip-r').innerHTML);
	},
	
	showSubmit: function() {
		$('bm-sub-form').show();
		$('bm-f-txt').focus();
		$('bm-f-txt').select();
	},
	
	hideSubmit: function() {
		$('bm-sub-form').hide();
	},
	
	showLoading: function() {
		$('bm-f-loading').show();
	},
	
	hideLoading: function() {
		$('bm-f-loading').hide();
	},

	submitScoreAuto: function() {
	
		// we only want to do this if it hasnt been done before
		if(Cookie.read(this.AUTO_COOKIE) == null) {
			name = ' ';
			mips = parseInt($('bm-mip-data').innerHTML);
			os = BrowserDetect.OS;
			browser = BrowserDetect.browser + ' ' + BrowserDetect.version;
			
			// set a cookie so it doesnt go auto-submit next time.
			// set the exipre in one day
			Cookie.create(this.AUTO_COOKIE, '1', 1);
			
			MipServer.submitScore(name, mips, os, browser);
		}
	},

	
	submitScore: function() {
		this.hideSubmit();
		MipsUi.showLoading();
		
		name = $('bm-f-txt').value;
		mips = parseInt($('bm-mip-data').innerHTML);
		os = BrowserDetect.OS;
		browser = BrowserDetect.browser + ' ' + BrowserDetect.version;
		
		MipServer.submitScore(name, mips, os, browser);
	}
	
	
}

// talks to the server to submit the users results etc..
var MipServer = {
	ADD_MIP_URL: './api/addmip.php?',
	
	submitScore: function(namedata, mipdata, platformdata, browserdata) {
		
		new Ajax.Request(
			this.ADD_MIP_URL, {
			method: 'get',
			parameters: {name:namedata, mips:mipdata, platform:platformdata, browser:browserdata},
			
			onSuccess: function(transport, json) {
				MipsUi.hideLoading();
				MipsUi.insertMip(mipdata+' - '+namedata+' ('+browserdata+', '+platformdata+')');
			},
			
			onFailure: function() {
				MipsUi.hideLoading();
				alert('http connection error!');
			}
			
		});
	}
	
}

// does that actual mip rating calculation
var BrowserMips = {
	BM_LOOP_TIME: 150,
	BM_DATE_MOD: 11,
	
	// 'calcuate' the speed of the browser by counting the interations 
	// of a busy wait loop over ~150ms. The numbers it returns vary massivly
	// depending on circumstances, so its not really and sort of useful bench
	// mark. Still interesting though
	calcuate: function() {
		var mipCount = 0;
		var now = new Date();
		var shadata = 'datastring2008'

		startTime =  now.valueOf();
		curTime =  startTime;

		while((curTime-startTime) < this.BM_LOOP_TIME ) {
			// we dont want to calcuate this everytime, it makes things tooo slow
			// and increases the difference between browsers
			if(mipCount%this.BM_DATE_MOD == 0) {
				var now = new Date();
				curTime =  now.valueOf();
				shadata = sha1(shadata + '-' + startTime);
			}
			
			mipCount++;	
		}
		
		// reduce the # of significant figures, makes the numbers more
		// friendly
		return Math.round(mipCount); 
	}
}

var Cookie = {
	create: function(name,value,days) {
		if (days) {									   
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}						   
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	},					   
																				   
	read: function(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	},

	erase: function(name) {
		createCookie(name,"",-1);
	}
}

