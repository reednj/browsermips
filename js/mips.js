function init()
{
	// set the browser information
	$('bm-b-data-b').innerHTML = BrowserDetect.browser + ' ' + BrowserDetect.version;
	$('bm-b-data-os').innerHTML = BrowserDetect.OS;

	// calculate the speed, and show it to the user, use the delay to get
	// a more consistent result
	setTimeout("calcMips()", 500);

}

function calcMips()
{
	$('bm-mip-data').innerHTML = BrowserMips.calcuate();
	setTimeout('calcMips()', 5000);
}

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

var MipsUi = {
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
