<script type="text/javascript">
//ask delete confirmation
function deletedata()
{
	var x=confirm("Are you sure do you want to delete this data?");
	if(x)
	{
		return true;	
	}
	else
	{
		return false;	
	}
}

//this is for number validation
function isNumberKey(evt) // only numbers to allow the input field
{
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

		return true;
}
   
//this is for text validation
function isTextKey(evt) // only text to allow the input field
{
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (((charCode >64 && charCode < 91)||(charCode >96 && charCode < 123)||charCode ==08 || charCode ==127||charCode ==32||charCode ==46)&&(!(evt.ctrlKey&&(charCode==118||charCode==86))))
		return true;

		return false;
}
   
//mobile number validation
function phonenumber(mobile_text_box_name) // Mobile No 
{
	var phoneno = /^\d{10}$/;
	if(document.getElementById(mobile_text_box_name).value=="")
	{
	}
	else
	{
		if( document.getElementById(mobile_text_box_name).value.match(phoneno))
		{
			hand(mobile_text_box_name);
		}
		else
		{
			alert("Enter 10 digit Mobile Number");
			document.getElementById(mobile_text_box_name).value="";
			document.getElementById(mobile_text_box_name).focus()=true;		
			return false;
		}
	}	 
}
function hand(mobile_text_box_name)
{
	var str = document.getElementById(mobile_text_box_name).value;
	var res = str.substring(0, 2);
	if(res=="07")
	{
		return true;
	}
	else
	{
		alert("Enter 10 digit of Mobile Number start with 07xxxxxxxx");
		document.getElementById(mobile_text_box_name).value="";
		document.getElementById(mobile_text_box_name).focus()=true;			
		return false;
	}	
}

//land phone number validation
function landphonenumber(lanphone_text_box_name) // Land No 
{
	var landno = /^\d{10}$/;
	if(document.getElementById(lanphone_text_box_name).value=="")
	{
	}
	else
	{
		if( document.getElementById(lanphone_text_box_name).value.match(landno))
		{
			land(lanphone_text_box_name);
		}
		else
		{
			alert("Enter 10 digit Land Phone Number");
			document.getElementById(lanphone_text_box_name).value="";
			document.getElementById(lanphone_text_box_name).focus()=true;		
			return false;
		}
	}	 
}
function land(lanphone_text_box_name)
{
	var str = document.getElementById(lanphone_text_box_name).value;
	var res = str.substring(0, 2);
	if(res!="07")
	{
		return true;
	}
	else
	{
		alert("Enter 10 digit of Land Phone Number Ex 021xxxxxxx");
		document.getElementById(lanphone_text_box_name).value="";
		document.getElementById(lanphone_text_box_name).focus()=true;			
		return false;
	}	
}

//check email validation format
function emailvalidation()
{
	var email=document.getElementById("txt_email").value;
	var emailformat=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	if (email.match(emailformat))
	{
		
	}
	else if(email.length==0)
	{
		
	}
	else
	{
		alert("Email Address is Invalid");
		document.getElementById("txt_email").value="";
		document.getElementById("txt_email").focus()=true;
	}		
}

//nic format validation
function nicnumber()
{
	var nic=document.getElementById("txtnic").value;
	if(nic.length==10)
	{
		var nicformat1=/^[0-9]{9}[a-zA-Z0-9]{1}$/;
		if(nic.match(nicformat1))
		{
			var nicformat2=/^[0-9]{9}[vVxX]{1}$/;
			if(nic.match(nicformat2))
			{
				calculatedob(nic);
			}
			else
			{
				alert("last character must be V/v/X/x");
				document.getElementById("txtnic").value="";
				document.getElementById("txtnic").focus();
				document.getElementById("txtdob").value="";
			}
		}
		else
		{
			alert("First 9 characters must be numbers");
			document.getElementById("txtnic").value="";	
			document.getElementById("txtnic").focus();
			document.getElementById("txtdob").value="";
		}	
	}
	else if(nic.length==12)
	{	
		var nicformat3=/^[0-9]{12}$/;
		if(nic.match(nicformat3))
		{
			calculatedob(nic);
		}
		else
		{
			alert("All 12 characters must be number");
			document.getElementById("txtnic").value="";
			document.getElementById("txtnic").focus();
			document.getElementById("txtdob").value="";
		}
	}
	else if(nic.length==0)
	{
			
	}
	else
	{
		alert("NIC No must be 10 or 12 Characters");
		document.getElementById("txtnic").value="";
		document.getElementById("txtnic").focus();	
		document.getElementById("txtdob").value="";
	}
}

//send nic to ajaxpage for get dob
function calculatedob(nic)
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() 
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
		{
			document.getElementById("txtdob").value = xmlhttp.responseText.trim();
			if(nic.length==10)
			{
				var bday_num = nic.substring(2, 5);
			}
			else
			{
				var bday_num = nic.substring(4, 7);
			}
			if(bday_num>500)
			{
				var gender="Female";
			}
			else
			{
				var gender="Male";
			}
			document.getElementById("txtgender").value = gender;
		}
	};
	xmlhttp.open("GET", "ajaxpage.php?frompage=dob&dobcal=" + nic, true);
	xmlhttp.send();
}
</script>