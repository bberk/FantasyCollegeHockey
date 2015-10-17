<!DOCTYPE html>
<script>
function appendPick(text)
{
	alert("attemtping to set the div..");
        jQuery('#runningList ol').append('<li>Appended item</li>');
}

function submitForm(formName,buttonID)
{
	//alert(buttonID);
	if (document.getElementById(buttonID).disabled == true)
		return;
	document.getElementById(buttonID).disabled = true;
	document.getElementById(buttonID).innerHTML		 = "Sit Tight...";
	//alert("Submitting form name = "+formName+ " Button ID " +buttonID);
	document.forms[formName].submit();

}

function showUser(str, resultPage) {
	//alert(str);
    //if (str == "") {
    //    document.getElementById("txtHint").innerHTML = "";
    //    return;
    //} else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5 
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
				return false;
            }
			return false;
        }
		//alert(str);
        xmlhttp.open("GET",resultPage+".php?q="+str,true);
        xmlhttp.send();
    //}
	return false;
}

function showSubpage(str,resultPage)
{
	showUser(str,resultPage);
}

function clearField(input)
{
	input.value = "";
}

function test()
{
	alert("hi.");
}
</script>