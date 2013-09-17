// Our Pages change every x seconds
pageSwitchTime = 30;
staticUpdateTime = 10;

var pages=new Array();


//pages[0] = "http://dialler.res.clixcloud.net/wallboard/framework/php/load.php?report=three_circles&";
pages[1] = "http://dialler.res.clixconnect.net/wallboard/framework/php/load.php?report=who_is_shining&";
pages[0] = "http://dialler.res.clixconnect.net/wallboard/framework/php/load.php?report=current_sales&";
//pages[2] = "http://dialler.resolve.local/wallboard/framework/php/load.php?report=telephone_data&";

var staticPage = "http://dialler.res.clixconnect.net/wallboard/framework/php/load.php?report=static_view&";

var currentPage = 0;
var currentErrors = 0;
var currentStaticErrors = 0;
var errorPage = -1;

function loadNextPage() {
	page = pages[currentPage];	
	
	$.getJSON( page + "callback=?" , function(data) {
		
		// Once the json has been loaded we strip out the view
		
		currentErrors = 0;
		
		pageView = data['view'];
		
		var windowHeight = $(window).height();
		var pageHolderOffest = $("#_bottomPage").offset();
		
		var bottomPageHeight = windowHeight - pageHolderOffest.top;
		
		
		$newPage = $("<div id='page"+currentPage+"Content' class='clear padBelow' style='height: "+bottomPageHeight+";'></div>").html(data['view'])
		
		// add the view to the live page
		$("#_bottomPage").append($newPage);
		
		$.each(data['content'], function(key, val) {
			$("#"+key).html(val);
		});
		
		if (errorPage > -1) {
            lastPage = errorPage;
            errorPage = -1;
		} else {
    		lastPage = currentPage-1;
    		if (lastPage < 0){
    			lastPage = pages.length-1;
    		}
		}
		
		$("#page"+lastPage+"Content").slideUp(1500,function() {
			$("#page"+lastPage+"Content").remove();
		});		
		
		currentPage++;
		
		if ( currentPage > pages.length-1 ) {
			currentPage = 0;
		}
			
		var t = setTimeout("loadNextPage()",(pageSwitchTime*1000))
		
		
	}).error(function() {
	   currentErrors++;
	   
	   if (currentErrors > 3) {
	       // Reload the Wallboard
	       location.reload();
	   }
	   
	   if (errorPage == -1) {
	       errorPage = currentPage-1;
	   }
	   
	   lastPage = errorPage;
	   
	   currentPage++;
	   
		
		if ( currentPage > pages.length-1 ) {
			currentPage = 0;
		}
	   
	   var t = setTimeout("loadNextPage()",(10))
	   
	});
}

function loadStaticContent() {
	
	$.getJSON( staticPage + "callback=?" , function(data) {
		
		$("#staticStats").html( data['view'] );
		$.each(data['content'], function(key, val) {
			$("#"+key).html(val);
		});
		
		var s = setTimeout("loadStaticContent()",(staticUpdateTime*1000))
		
	}).error(function() {
	
	   currentStaticErrors++;
	   
	   if (currentStaticErrors > 3) {
	       // Reload the Wallboard
	       location.reload();
	   } else {
	       loadStaticContent();
	   }
	
	});
	
}

function loadTickerTape() {
  
  $.get("admin/ajax/get_ticker_tape.php?dept=1", function(data) {
    if(data)
    {
      // -- There is a message to show
      $("#Ticker-Tape marquee").marquee();
      $("#_dateArea").css("display", "none");
      $("#Ticker-Tape").css("display", "block");
      $("#_timeArea").css("width", "10%");
      $("#Ticker-Tape-Marquee").html(data);
    }
    else
    {
      // -- Nothing to show, so show the default header
      // -- Dont Show Ticker Tape
      $("#_dateArea").css("display", "block");
      $("#Ticker-Tape").css("display", "none");
      $("#_timeArea").css("width", "10%");
    }
  });
}


$(document).ready(function()
{
  
	updateClock();
	loadStaticContent();

	loadNextPage();
	
	setInterval('updateClock()', 1000);
  
  //loadTickerTape();  
  //setInterval('loadTickerTape()', 15000);
   
});
