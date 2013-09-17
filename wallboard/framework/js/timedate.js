
blinkOn = 1;

function updateClock ( )
    {
    	
    	if (blinkOn == 1) {
    		blinkOn = 0;
    		blinkText=":";
    	} else {
    		blinkOn = 1;
    		blinkText="<font color='#222222' class='blink'>:</font>";
    	}
    
    	var weekday=new Array(7);
		weekday[0]="Sunday";
		weekday[1]="Monday";
		weekday[2]="Tuesday";
		weekday[3]="Wednesday";
		weekday[4]="Thursday";
		weekday[5]="Friday";
		weekday[6]="Saturday";
		
		var month=new Array(12);
		month[0]="January";
		month[1]="February";
		month[2]="March";
		month[3]="April";
		month[4]="May";
		month[5]="June";
		month[6]="July";
		month[7]="August";
		month[8]="September";
		month[9]="October";
		month[10]="November";
		month[11]="December";
    
    var currentTime = new Date ( );
    var currentHours = currentTime.getHours ( );
    var currentMinutes = currentTime.getMinutes ( );
 
    currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
    currentHours = ( currentHours < 10 ? "0" : "" ) + currentHours;
    
    var currentTimeString = currentHours + blinkText + currentMinutes;
 
 
 	var suffix = "";
 	
 	date = currentTime.getDate();
 	
 	if ( date == '1' || date == '21' || date == '31' ) {
 		suffix = "st";
 	} else if ( date == '2' || date == '22' ) {
 		suffix = "nd";
 	} else if ( date == '3' || date == '23' ) {
 		suffix = "rd";
 	} else {
 		suffix = "th";
 	}
 
 	var currentDateString = weekday[currentTime.getDay()] + " " + currentTime.getDate() + suffix + " " + month[currentTime.getMonth()];
 
    $("#_timeArea").html(currentTimeString);
    $("#_dateArea").html(currentDateString);
 
 }