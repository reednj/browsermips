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
	$('mipdata').innerHTML = BrowserMips.calcuate();
	setTimeout('calcMips()', 5000);
}

var BrowserMips = {
	BM_LOOP_TIME: 150,
	BM_DATE_MOD: 17,
	
	// 'calcuate' the speed of the browser by counting the interations 
	// of a busy wait loop over ~150ms. The numbers it returns vary massivly
	// depending on circumstances, so its not really and sort of useful bench
	// mark. Still interesting though
	calcuate: function() {
		var mipCount = 0;
		var now = new Date();

		startTime =  now.valueOf();
		curTime =  startTime;

		while((curTime-startTime) < this.BM_LOOP_TIME ) {
			// we dont want to calcuate this everytime, it makes things tooo slow
			// and increases the difference between browsers
			if(mipCount%this.BM_DATE_MOD == 0) {
				var now = new Date();
				curTime =  now.valueOf();
			}

			mipCount++;	
		}
		
		// reduce the # of significant figures, makes the numbers more
		// friendly
		return Math.round(mipCount/100); 
	}
}

var MipsUi = {
	showSubmit: function() {
		$('bm-sub-form').show();
	},
	
	hideSubmit: function() {
		$('bm-sub-form').hide();
	}
}
