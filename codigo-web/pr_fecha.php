<html>
<head>
<title>CodeThatCalendar</title>
<link href="ctc.css" rel="stylesheet" type="text/css">
<script language="javascript" src="codethatcalendarstd.js"></script>
<script language="javascript">
<!--
var params = {
	firstday:0,
	dtype:'dd/MM/yyyy',
	width:275,
	windoww:500,
	windowh:500,
	border_width:0,
	border_color:'White',
	headerstyle: {
		type:"buttons",
		css:'clsDayName',
		imgnextm:'imagenes/forward.jpg',
		imgprevm:'imagenes/back.jpg'
	},
	monthnames:["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
	daynames:["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"]
};
t=false;
function ie_ua(){
	t=ua;
}
function show(id){
	var c1 = new CodeThatCalendar(params);
	c1.popup(id);
}
//-->
</script>
</head>
<body>
	<form>
		<input name="id3">
		<input type="button" onClick="show('id3');" value="...">
	</form>
</body>
</html>