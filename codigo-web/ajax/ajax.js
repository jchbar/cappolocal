var xmlhttp=false;

if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
  xmlhttp = new XMLHttpRequest();
}

function ajax_call() {
	xmlhttp.open("GET", 'ajaxWork.php?num1=' + 
					document.getElementById('num1').value + 
						'&num2=' + document.getElementById('num2').value , true);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			document.getElementById('result').value = xmlhttp.responseText;
		}
	}
	xmlhttp.send(null)
	return false;
}