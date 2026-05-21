<html>
<head>

<title>AA.Com | Calendario</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="/content/common/styles/global.css">
<LINK REL="stylesheet" TYPE="text/css" HREF="/content/common/styles/main.css">
<LINK REL="stylesheet" TYPE="text/css" HREF="/content/common/styles/coreStyles.css">

<!-- Use IE conditional statements to address specific IE-related hacks for IE7 and below. -->
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="/content/common/styles/ieN-only.css" media="all"/>
<![endif]-->

<STYLE TYPE="text/css">
@import url(/content/common/styles/new.css); /*IE and NN6x styles*/
@import url(/content/common/styles/modules.css); /* New styles */


	


	
	

#border_none, #border_right, #border_right_bottom, #border_bottom,
#border_bottom_print, #border_bottom_none, #border_right_top, 
#border_top, #border_top_only, #border_right_top_print, #border_right_print, 
#border_top_print, #border_top_bottom_print, #border_top_double{border-color:#000066;border-style:solid;border-width: 0 0 0 0;} 
#border_right {border-width: 0 1px 0 0;}
#border_right_bottom {border-width: 0 1px 1px 0;}
#border_bottom {border-width: 0 0 1px 0;}
#border_bottom_print {border-width: 0;}
#border_right_top {border-width: 1px 1px 0 0;}
#border_top {border-width: 0;}
#border_top_only {border-width: 1px 0 0 0;}
#border_right_top_print {border-width: 0;}
#border_right_print {border-width: 0;}
#border_top_print {border-width: 0;}
#border_top_bottom_print {border-width: 0;}
#border_top_double {border-width: 2px 0 0 0;}

div.scrollBox     {
      background-color: #ffffff; 
      text-align: left; 
      padding: 0; 
      border:1px solid #cccccc; 
      height: 100px; 
      overflow: auto; 
      font-size: 11px; 
      color: #222; 
      line-height: 15px;
      }
      
div.scrollBox h3 {
                font-size:12px;
                font-weight:bold;
                padding:3px 5px;
                margin:0;
}
div.scrollBox h4 {
                font-size:11px;
                font-weight:bold;
                padding:3px 5px 0 5px;
                margin:0;
}
div.scrollBox p {
                margin:0;
                padding:5px
}
div.scrollBox dl {
                margin:0 5px;
                padding:0
}
div.scrollBox dl dt {
                font-size:11px;
                font-weight:bold;
                padding:3px 0
}
div.scrollBox dl dd {
                margin: 0 0 5px 5px;
                line-height:1.2
}
div.scrollBox ol {
                margin:0 5px 0 25px;
                padding:0
}

div.scrollBox h2  {
      font-size: 12px;
      font-weight:bold;
      color:#222222;
      padding:0;
      margin:0;
      border:none;
      line-height: 15px;
}
div.scrollBox ul  {
      margin-left: 35px;
      list-style-type: square;
      color: #222;
      }


</STYLE>
<style type="text/css">
.month {font-family:arial, helvetica, sans-serif; font-size:8pt;color:#000077;}
.day {font-family:arial, helvetica, sans-serif; font-size:8pt;font-weight:600;color:#000077;}
.date {font-family:arial, helvetica, sans-serif; font-size:8pt;}
td,input, select,option {font-family:arial, helvetica, sans-serif; font-size:8pt;}
a:link{color:#CC0000;text-decoration:underline; font-family:arial,helvetica,sans-serif;}
a:visited{color:#990000;text-decoration:underline; font-family:arial,helvetica,sans-serif;}
a:active{color:#CC0000;text-decoration:underline; font-family:arial,helvetica,sans-serif;}
a:hover{color:#CC0000;text-decoration:none; font-family:arial,helvetica,sans-serif;}

#calBack{
   background-image:url(/content/images/common/calendar_back.jpg);
   background-color: #00007c;
   background-repeat:no-repeat;
   background-position:center;
   height:18px;
}

#calForward{
   background-image:url(/content/images/common/calendar_forward.jpg);
   background-color: #00007c;
   background-repeat:no-repeat;
   background-position:center;
   height:18px;
}

#errorMessage{
   background-image: url(/content/images/common/bang.gif);
   background-repeat: no-repeat;
   padding-left: 17px;
   color:#CC0000;
   font-family: Arial, Helvetica, sans-serif;
   font-size:12px;
   font-weight:bold;
   line-height:13px;
}

.calHeader{
   font-family:arial,helvetica,sans-serif;
   color: #ffffff;
   background-color: #00007c;
   height: 20px;
   text-align:center;
   vertical-align: middle;
   font-weight: bold;
   font-size: 10px;
}

.days{
    background-color: #cccccc;
}

.calendarTable {
   font-family:arial,helvetica,sans-serif;
   color: #000000;
   font-size: 10px;
   font-weight: normal;
   text-align: center;
   border: 1px solid #cccccc;
   float: left;
}


.disabledDate{
   color: #000000;
   background-color: #999999;
   border: 1px solid #cccccc;
   font-size:10px;
}


.selectedDate{
   color: #ffffff;
   background-color: #FF0000;
   cursor: pointer;
   cursor: hand;
   border: 1px solid #cccccc;
   font-size:10px;
}

.markedDate{
   color: #FF0000;
   background-color: #ffffff;
   cursor: pointer;
   cursor: hand;
   border: 1px solid #FF0000;
   font-size:10px;
}

.hoverDate{
   color: #ffffff;
   background-color: #FF0000;
   cursor: pointer;
   border: 1px solid #FF0000;
   font-size:10px;
}

.activeDate{
   cursor: pointer;
   cursor: hand;
   border: 1px solid #cccccc;
   font-size:10px;
}

.previousDateGrey{
   color: #cccccc;
   background-color: #999999;
   border: 1px solid #cccccc;
   font-size:10px;
}

.previousDateWhite{
   color: #cccccc;
   background-color: #ffffff;
   border: 1px solid #cccccc;
   font-size:10px;
}

</style>
<script language="JavaScript1.2" src="/aa/common/js/calendar.jsp"
	type="text/javascript"></script>
<script language="JavaScript">
<!-- Begin
var dateLimit = 90; //new
var cutOff = 0; //new
var backDate = 0; //new
// QuickHits ER#21788 Changed to work with DIV tag
var formReturn = "reservationFlightSearchForm";

var n = (document.layers) ? 1:0
var ie = (document.all) ? 1:0
var n6 = (document.getElementById&&!document.all) ? 1:0

var agt = navigator.userAgent.toLowerCase();
var is_ie4 = (agt.indexOf("msie 4")!=-1);
var mac    = (agt.indexOf("mac")!=-1);

var monthnames = new Array();
var days = new Array();
var monthShortNames = new Array();
var daysNum = new Array();
var daysLongNames = new Array();


  monthnames[1 - 1] = "Enero";

  monthnames[2 - 1] = "Febrero";

  monthnames[3 - 1] = "Marzo";

  monthnames[4 - 1] = "Abril";

  monthnames[5 - 1] = "Mayo";

  monthnames[6 - 1] = "Junio";

  monthnames[7 - 1] = "Julio";

  monthnames[8 - 1] = "Agosto";

  monthnames[9 - 1] = "Septiembre";

  monthnames[10 - 1] = "Octubre";

  monthnames[11 - 1] = "Noviembre";

  monthnames[12 - 1] = "Diciembre";



  days[0] = "Dom";

  days[1] = "Lun";

  days[2] = "Mar";

  days[3] = "Mie";

  days[4] = "Jue";

  days[5] = "Vie";

  days[6] = "Sab";



  monthShortNames[1 - 1] = "Ene";

  monthShortNames[2 - 1] = "Feb";

  monthShortNames[3 - 1] = "Mar";

  monthShortNames[4 - 1] = "Abr";

  monthShortNames[5 - 1] = "May";

  monthShortNames[6 - 1] = "Jun";

  monthShortNames[7 - 1] = "Jul";

  monthShortNames[8 - 1] = "Ago";

  monthShortNames[9 - 1] = "Sep";

  monthShortNames[10 - 1] = "Oct";

  monthShortNames[11 - 1] = "Nov";

  monthShortNames[12 - 1] = "Dic";



  daysNum[0] = "1";

  daysNum[1] = "2";

  daysNum[2] = "3";

  daysNum[3] = "4";

  daysNum[4] = "5";

  daysNum[5] = "6";

  daysNum[6] = "7";

  daysNum[7] = "8";

  daysNum[8] = "9";

  daysNum[9] = "10";

  daysNum[10] = "11";

  daysNum[11] = "12";

  daysNum[12] = "13";

  daysNum[13] = "14";

  daysNum[14] = "15";

  daysNum[15] = "16";

  daysNum[16] = "17";

  daysNum[17] = "18";

  daysNum[18] = "19";

  daysNum[19] = "20";

  daysNum[20] = "21";

  daysNum[21] = "22";

  daysNum[22] = "23";

  daysNum[23] = "24";

  daysNum[24] = "25";

  daysNum[25] = "26";

  daysNum[26] = "27";

  daysNum[27] = "28";

  daysNum[28] = "29";

  daysNum[29] = "30";

  daysNum[30] = "31";

  daysNum[31] = "Día=-1000";



  daysLongNames[0] = "???list.days.long???";


var linkcount=0;
var calCode1
function addlink(month, day, href) {
var entry = new Array(3);
entry[0] = month;
entry[1] = day;
entry[2] = href;
this[linkcount++] = entry;
}
Array.prototype.addlink = addlink;
linkdays = new Array();
monthdays = new Array(12);
monthdays[0]=31;
monthdays[1]=28;
monthdays[2]=31;
monthdays[3]=30;
monthdays[4]=31;
monthdays[5]=30;
monthdays[6]=31;
monthdays[7]=31;
monthdays[8]=30;
monthdays[9]=31;
monthdays[10]=30;
monthdays[11]=31;
var previousMonth = ''
var nextMonth = ''
var yearsFuture = 1

//22784 - This attibute says if the selected date needs to be highlighted
var isNeededHighLigthDate = true;

function makeStop(){ //new function
	currentMonth = 0;
	backDate = 0;
	todayDate=new Date();
	year=todayDate.getYear();
	day = todayDate.getDate();
	backDate = (day -1);
	if (currentMonth==0){
		month = todayDate.getMonth();
	} else {
		month = currentMonth;
	}
	if (month > 11){
		monthMultiplier = Math.floor(month/12)
		month = month-(12*monthMultiplier)
		year = year+monthMultiplier
	}

	year = year % 100;
	year = ((year < 50) ? (2000 + year) : (1900 + year));

	if (month == 0){
		stopMonth = 11;
	}
	else {
		stopMonth = todayDate.getMonth() - 1;
	}
	stopYear = year + 1;
}

makeStop(); //new

function makeMonth(which, errOrder){
gotoMonth = which;

todayDate=new Date();
year=todayDate.getYear();

month = gotoMonth;

if(month == 0)
	previousMonth = 11;
else
	previousMonth = month-1;

nextMonth = month+1;

if (month > 11){
	monthMultiplier = Math.floor(month/12)
	month = month-(12*monthMultiplier)
//	year = year+monthMultiplier
}

year = year % 100;
year = ((year < 50) ? (2000 + year) : (1900 + year));

/* QuickHits ER#21788 (+) Data from combobox*/
   var selectedMonth = 6 - 1;
   var selectedDay = 16;
/*(-) Data from combobox*/

/// QuickHits, this logic alow to show previus month on left
/// if diff days beetwen today and selectedDay is greater of 30
var origMonth = month;

var limitDate = calculateLimitDate();
if(selectedMonth == limitDate.getMonth())
{
  if( month == 0 ){
    month = 12;
    year = year - 1;
  }
/// QuickHits ER#21788, decrease month to show previus month on left
  month = month - 1;
}

/* QuickHits ER#21788,
* Function to define is monthToShow has to show on rigth
*/
function showPreviusMonth(monthToHighLigth, limitMonth)
{
  if (monthToHighLigth < today.getMonth()){
    yearToShow = yearToShow + 1;
  }
  var dateToShow = new Date (yearToShow, monthToHighLigth, (dayToHighLigth - 30));
  if ( dateToShow < today )
    return false;
  return true;
}

/// QuickHits ER#21788, this logic identified is month to show
/// is from next year
var nextYear = showNextYear(origMonth, todayDate.getMonth(), year);
if ( nextYear != year ){
  year = year + 1;
}

var firstOfMonth = new Date (year, month, 1);
var startspaces  = firstOfMonth.getDay();

// QuickHits ER#21788, drawing calendar on left
calCode1 = drawLeftCalendar(month, year, selectedDay, selectedMonth, previousMonth, startspaces);

if (month==11){
    month2 = 0;
    year2 = year+1;
}
else {
    month2 = month+1;
    year2 = year;
}

//if year is leap, then february = 29 days
if (((year % 4 == 0) && !(year % 100 == 0)) || (year % 400 == 0)) monthdays[1]=29;
	else(monthdays[1] = 28);

var firstOfMonth2 = new Date (year2, month2, 1);
var startspaces2  = firstOfMonth2.getDay();
// QuickHits ER#21788, drawing calendar on right
var calCode2 = drawRightCalendar(month2, year2, selectedDay, selectedMonth, nextMonth, startspaces2);
var formControl = eval('parent.document.'+formReturn+'.currentCalForm.value');
if((formControl == 'returnDate.travel' || formControl == 'return') && errOrder == false){
	errOrder = !validateOrder(selectedDay-1, selectedMonth, false);
}
var errorMs = "";

// QuickHits ER#21788, Showing error message on bottom if errOrder = true
// building all html to show
if(errOrder == true){
		errorMs = errorMs + "<div id='errorMessage'>Las fechas no están en orden cronológico.</div>";
}
calCode3 = "<table><tr valign='top'><td valign='top'>" + calCode1 + "</td><td valign='top'></td><td valign='top'>" + calCode2 + "</td></tr></table>";
calCode3 = calCode3 + "<table align = 'left' width = '100%'><tr><td width = '70%'>" + errorMs + "</td><td vlaign='top' align='right' ><input type='button' class='aaBtnAutoCold' onclick='closeCalendarDiv(true);return false;' value='CLOSE'></input></td></tr></table>";

if (n) {
	document.calendar1.document.write(calCode3);
	document.calendar1.document.close();
}
if (ie) {
	if(mac){
	calendar1mac.innerHTML = calCode1;
	calendar2mac.innerHTML = calCode2;
	}
	else{
    calendar1.innerHTML = calCode3;
//	calendar1.innerHTML = calCode1+calCode2
		}
    }
if (n6) {
    document.getElementById('calendar1').innerHTML = calCode3;
    }
}

function setDates(gMonth, gDay, gYear) {
    xMonth = gMonth;
	xDay = gDay-1;
    xYear = gYear - 2001;

// QuickHits ER#21788, retriving values from form
    preCal = eval('parent.document.'+formReturn+'.currentCalForm.value');
    isFlex = eval('parent.document.'+formReturn+'.isDatesFlexible != null');

   //chalsted - need to call updateReturnDate() per TD #9510.
   var myForm = eval('parent.document.'+formReturn);


   if(preCal == 'return'){
	 var today = new Date();
	 var threeDaysInfuture = new Date();
	 threeDaysInfuture.setDate(today.getDate() + 3);
	 var rDy = myForm.returnDay.selectedIndex;
	 var rMon = myForm.returnMonth.selectedIndex;

	 if((rDy != xDay) || (rMon != xMonth))
	    if(myForm.dateChanged){
  	   	  myForm.dateChanged.value = true; //only change if user did not select current day
  	   	}
   }
// QuickHits ER#21788, if return date < departure date
// send correct month to makeMonth function
	if(preCal == 'returnDate.travel' || preCal == 'return'){
		var validation = validateOrder(xDay, xMonth, true);
		if (validation == false){
	              var rMon;
                     if (!parent.document.getElementById(formReturn + '.' + preCal + 'Month')){
	                 rMon = eval('parent.document.'+formReturn+'.'+preCal+'Month.selectedIndex');
                     }
                     else{
                       rMon = eval( parent.document.getElementById(formReturn + '.' + preCal + 'Month').selectedIndex);
                     }
			//ER22784
			if(rMon > 11)
				makeMonth(xMonth, true);
			else
				makeMonth(rMon, true);
			return false;
	  }
	  if(myForm.dateChanged){
	  	  myForm.dateChanged.value = true; 
  	  }
    }

    if (!parent.document.getElementById(formReturn + '.' + preCal + 'Month')){
	eval('parent.document.'+formReturn+'.'+preCal+'Month.selectedIndex = xMonth');
       eval('parent.document.'+formReturn+'.'+preCal+'Day.selectedIndex = xDay');
    }
    else{
	eval( parent.document.getElementById(formReturn + '.' + preCal + 'Month').selectedIndex = xMonth );
       eval( parent.document.getElementById( formReturn + '.' + preCal + 'Day').selectedIndex = xDay );
    }

	//bcronk 3/4
	//The system is keeping track of the year for all functionality
	//So because of TD 1806 "Create FSN calendar is populating date field with wrong year"
	//I'm commenting out setting this value to see if it causes problems
	//eval('window.opener.document.'+formReturn+'.'+preCal+'Year.selectedIndex = xYear');

// QuickHits ER#21788, Updated to work with jsp and jhtml
	  if (preCal == 'flightParams.flightDateParams.travel' || preCal == 'departure') {
//TS 22059: I've added the formReturn reservationFlightSearchForm when the updateReturnDate function is called.

			  if ( (formReturn == 'depRetDates') ||
			       (formReturn == 'flightSearchForm') ||
			       (formReturn == 'datesFlexibleFlightSearchForm') ||
			       (formReturn == 'awardFlightSearchForm') ||
			       (formReturn == 'makeReservationForm') ||
			       (formReturn == 'roundTrip')   ||
			       (formReturn == 'awards') ||
			       (formReturn == 'reservationFlightSearchForm') ) {
					updateReturnDate(myForm);
			  }
		}
    closeCalendarDiv(true);
}

// QuickHits ER#21788, Add function to validate that
// return date > departure date
function validateOrder(retSelDay, retSelMonth, callFromSetDate)
{
	var depMonth;
	var depMonthElement;
	var depDay;
	var depDayElement;

	var retDay = retSelDay;
	var retMonth = retSelMonth;

	var todayDate = new Date();
	var todayMonth = todayDate.getMonth();

	var myForm = eval('parent.document.'+formReturn);

	depDay = getCorrectValue(myForm, '.flightParams.flightDateParams.travelDay');
	depMonth = getCorrectValue(myForm, '.flightParams.flightDateParams.travelMonth');
	
	//ER 22784
	if(depDay == 31 || depMonth == 12)
	{
		return true;
	}

	// ER 22784
	if(isNeededHighLigthDate == false && callFromSetDate == false) {return true;}

	var errOrder = false;

	var depYear = todayDate.getYear();
	var retYear = todayDate.getYear();
	if(retMonth < todayMonth){
		retYear = retYear + 1;
	}
	if(depMonth < todayMonth){
		depYear = depYear + 1;
	}
	var depDate = new Date(depYear, depMonth, depDay);
	var retDate = new Date(retYear, retMonth, retDay);
	if(retDate < depDate){
		return false;
	}
	return true;

}

/* QuickHits ER#21788
 * This JS function is the responsable to draw the calendar located on the left side
 */
function drawLeftCalendar(monthToShow, yearToShow, dayToHighLigth, monthToHighLigth, previousMonth, startspaces)
{
   var todayDate = new Date();
   var calendarCode = "<table width='205' border='0' cellspacing='0' class='calendarTable'><tr>";
   if (monthToShow > todayDate.getMonth())
   {
       calendarCode = calendarCode + "<td class='calHeader'><div id='calBack' onclick='makeMonth(" + previousMonth + "," + false + ");return false;'></div></td>";
   }
   else if (monthToShow < todayDate.getMonth() && yearToShow > todayDate.getFullYear())
   {
	calendarCode = calendarCode + "<td class='calHeader'><div id='calBack' onclick='makeMonth(" + previousMonth + "," + false + ");return false;'></div></td>";
   }
   else
   {
	calendarCode = calendarCode + "<td class='calHeader' >&nbsp;</td>";
   }
   calendarCode = calendarCode + "<td  class='calHeader' colspan='5'>" + monthnames[monthToShow] + " " + yearToShow + "</td><td class='calHeader' >&nbsp;</td></tr>";
   calendarCode = drawCalendar(calendarCode, monthToShow, yearToShow, dayToHighLigth, monthToHighLigth, startspaces);
   return calendarCode;
}

/*/ QuickHits ER#21788
 * This JS function is the responsable to draw the calendar located on the rigth side
 */
function drawRightCalendar(monthToShow, yearToShow, dayToHighLigth, monthToHighLigth, nextMonth, startspaces)
{
   var limitDate = calculateLimitDate();
   var calendarCode = "<table border=0 cellpadding=1 cellspacing=0 class='calendarTable' width='205'><tr><td class='calHeader'>&nbsp;</td><td class='calHeader' colspan='5'>" + monthnames[monthToShow] + " " + yearToShow + "</td>";
   if (monthToShow != limitDate.getMonth())
   {
     calendarCode = calendarCode + "<td class='calHeader'><div id='calForward' onclick='makeMonth(" + nextMonth + "," + false + ");return false;'></div></td></tr>";
   }
   else
   {
     calendarCode = calendarCode + "<td class='calHeader'>&nbsp;</td></tr>";
   }
   calendarCode = drawCalendar(calendarCode, monthToShow, yearToShow, dayToHighLigth, monthToHighLigth, startspaces);
   return calendarCode;
}

/*/ QuickHits ER#21788
 * This is a generic JS function that returns the HTML code for specific calendar
 */
function drawCalendar(code, monthToShow, yearToShow, dayToHighLigth, monthToHighLigth, startspaces)
{
  var calenName = eval('parent.document.'+formReturn+'.currentCalForm.value');
  var tripType = 'tripType';
// do this to ensure that object exist on page
  if(eval('parent.document.'+formReturn+'.tripType')){
    var objects = parent.document.getElementsByName("tripType");
    var auxElement;
    for(var i=0; i<objects.length;i++)
    {
       auxElement = objects[i];
       if(auxElement.type =='radio' && auxElement.checked)
          tripType = auxElement.value;
       else if(auxElement.type =='hidden')
          tripType = auxElement.value;
    }
 }

  var dayToMark;
  var monthToMark;
  var myForm = eval('parent.document.'+formReturn);

//QuickHits, when show only 1 calendar not show de return date selected
  if(tripType.indexOf("oneWay")<0 && tripType.indexOf("multi")<0 ){
    try{
      if(calenName.indexOf("return")>=0)
      {
	  dayToMark = getCorrectValue(myForm, '.flightParams.flightDateParams.travelDay') + 1;
	  monthToMark= getCorrectValue(myForm, '.flightParams.flightDateParams.travelMonth');
      }
      else
      {
	  dayToMark = getCorrectValue(myForm, '.returnDate.travelDay') + 1;
	  monthToMark= getCorrectValue(myForm, '.returnDate.travelMonth');
      }
    }catch(e){}/*this try-catch solves the situation when don´t exist a return DATE*/
  }

  var todayDate = new Date();
  var currentDay = todayDate.getDate();
  var currentMonth = todayDate.getMonth();
  var currentYear = todayDate.getFullYear();
  var nextDate = 0;

  //To show the correct name of the day
  var diplayedDate = new Date(yearToShow, monthToHighLigth, dayToHighLigth);

  var calendarCode = code + "<tr>";
  calendarCode = calendarCode + "<th style='width:25px;' scope='col' class='days' >" + days[0] + "</th>";
  calendarCode = calendarCode + "<th style='width:25px;' scope='col' class='days' >" + days[1] + "</th>";
  calendarCode = calendarCode + "<th style='width:25px;' scope='col' class='days' >" + days[2] + "</th>";
  calendarCode = calendarCode + "<th style='width:25px;' scope='col' class='days' >" + days[3] + "</th>";
  calendarCode = calendarCode + "<th style='width:25px;' scope='col' class='days' >" + days[4] + "</th>";
  calendarCode = calendarCode + "<th style='width:25px;' scope='col' class='days' >" + days[5] + "</th>";
  calendarCode = calendarCode + "<th style='width:25px;' scope='col' class='days' >" + days[6] + "</th>";
  calendarCode = calendarCode + "</tr>";
  calendarCode = calendarCode + "<tr>";

  for (s=0;s<startspaces;s++)
  {
    previousDate = new Date(yearToShow, monthToShow, 1 - startspaces + s);
    if(previousDate <= todayDate){
	calendarCode = calendarCode + "<td class='previousDateGrey'>" + previousDate.getDate() + "</td>"
    }
    else{
	calendarCode = calendarCode + "<td class='previousDateWhite'>" + previousDate.getDate() + "</td>"
    }
  }

  var limitDate = calculateLimitDate();

  count=1;
  while (count + s + nextDate <= 42)
  {
	for (b = startspaces;b<7;b++)
       {
	   linktrue=false;
	   if (count <= monthdays[monthToShow])
          {
		if((count < currentDay && monthToShow == currentMonth && yearToShow == currentYear) || (monthToShow < currentMonth && yearToShow < currentYear) || (count > limitDate.getDate() && monthToShow == limitDate.getMonth() && yearToShow == limitDate.getFullYear()) || (monthToShow > limitDate.getMonth() && yearToShow == limitDate.getFullYear()))
		{
	    	   calendarCode = calendarCode + "<td class='disabledDate' >"+ count +"</td>";
		   count++;
		}
		else
		{
		   if(count == dayToHighLigth && monthToShow == monthToHighLigth && isNeededHighLigthDate == true)
		   {
			calendarCode = calendarCode + "<td class='selectedDate' onclick='setDates("+monthToShow+","+count+","+yearToShow+");return false;'>"+ count +"</td>";
			count++;
		   }
		   else if(count == dayToMark  && monthToShow == monthToMark)
		   {
			calendarCode = calendarCode + "<td class='markedDate' onclick='setDates("+monthToShow+","+count+","+yearToShow+");return false;' onmouseover=this.className='hoverDate' onmouseout=this.className='markedDate'>"+ count +"</td>";
			count++;
		   }
		   else
		   {
			calendarCode = calendarCode + "<td class='activeDate' onclick='setDates("+monthToShow+","+count+","+yearToShow+");return false;' onmouseover=this.className='hoverDate' onmouseout=this.className='activeDate'>"+ count +"</td>";
			count++;
		   }
		}
	   }
	   else{
		nextDate = nextDate + 1;
		calendarCode = calendarCode + "<td class='previousDateWhite'>" + nextDate + "</td>";
	   }
	}
	calendarCode = calendarCode + "</tr>";
	startspaces=0;

  }
  calendarCode = calendarCode + "</table>";
  return calendarCode;
}


/* // QuickHits ER#21788,
* Function to define is monthToShow is from next year
*/
function showNextYear(monthToShow, monthToday, year)
{
  var returnYear = year;
  if (monthToShow < monthToday){
    return (returnYear + 1);
  }
  return returnYear;
}


/* QuickHits ER#21788
 * This function calculates the limit date until which we have a range of available dates.
 */
function calculateLimitDate()
{
   var todayDate = new Date();
   var limitDate = new Date (todayDate.getFullYear(), todayDate.getMonth(), (todayDate.getDate() + 330));
   return limitDate;
}

/* QuickHits ER#21788,
* Function to retrieve de object value
*/
function getCorrectValue(myForm, objectToFind)
{
return getElement(parent.document.getElementById(myForm.name + objectToFind), objectToFind, myForm).selectedIndex;
}

function getTripType()
{
  var tripType = 'tripType';
// do this to ensure that object exist on page
  if(eval('parent.document.'+formReturn+'.tripType')){
    tripType = eval('parent.document.'+formReturn+'.tripType.value');
    if(!tripType)
    {
      /// this is work for awardFlightSearchForm.tripType.oneWay radioButton
      var element = eval('parent.document.'+formReturn+'.tripType.oneWay');
      if(element && element.checked){
        tripType = 'oneWay';
      }
      else
        tripType = 'tripType';
    }
  }
  return tripType;
}

// End -->
</script>

</head>
<body bgcolor="#FFFFFF" marginheight="0" marginwidth="0" topmargin="0"
	leftmargin="0"
	onload="makeMonth(6 - 1, false);focus();">

<script language="JavaScript">
<!-- Begin
if (ie || n || n6) {
	if(mac){
		document.write('<div style="position:absolute;left:1px;top:1px;" id="calendar1"></div>');
		document.write('<div style="position:absolute;left:1px;top:1px;" id="calendar1mac"></div>');
		document.write('<div style="position:absolute;left:19px;top:1px;" id="calendar2mac"></div>');
		}
	else{
		document.write('<div style="position:absolute;left:1px;top:1px;" id="calendar1"></div>');
		}
    }
// End -->
</script>

</body>
</html>
