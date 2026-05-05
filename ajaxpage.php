<?php
if(!isset($_SESSION)) 
{
	session_start();
}
include("config.php");
if(isset($_GET["frompage"]))
{
	if($_GET["frompage"]=="dob")
	{
		$selnic = $_GET["dobcal"];
		if(strlen($selnic)==10)
		{
			$bdayyear=substr($selnic, 0,2);
			$bdayyear=$bdayyear+1900;
			$bdaynum=substr($selnic, 2,3);
		}
		else if(strlen($selnic)==12)
		{
			$bdayyear=substr($selnic, 0,4);
			$bdaynum=substr($selnic, 4,3);
		}
		
		$bdaynum1=0;
		if($bdaynum>500)
		{
			$bdaynum1=$bdaynum-500;
			
		}
		else
		{
			$bdaynum1=$bdaynum;
		}
		
		$bdaydate;
		
		$month=array(31,29,31,30,31,30,31,31,30,31,30,31);
		$day_cal=0;//add total days of months
		$bdaymonth=0;
		$bdayday=0;
		for($x=0;$x<count($month);$x++)
		{
			$day_cal=$day_cal+$month[$x];
			if($day_cal>=$bdaynum1)
			{
				$bdayday=$bdaynum1-(($day_cal)-($month[$x]));
				$bdaymonth=++$x;
				break;
			}
		}
		$bdaydate=$bdayyear."-".$bdaymonth."-".$bdayday;
		$bdaydate=date("Y-m-d", strtotime($bdaydate));
		echo $bdaydate;
	}
	else if($_GET["frompage"]=="staff_nic")
	{
		$get_ajax_nic = $_GET["ajax_nic"];
		$sqlchecknic="SELECT user_name FROM user WHERE user_name='$get_ajax_nic'";
		$resultchecknic=mysqli_query($con,$sqlchecknic);
		if(mysqli_num_rows($resultchecknic)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}

	}

	else if($_GET["frompage"]=="proprietor_nic")
	{
		$get_ajax_nic = $_GET["ajax_nic"];
		$sqlchecknic="SELECT user_name FROM user WHERE user_name='$get_ajax_nic'";
		$resultchecknic=mysqli_query($con,$sqlchecknic);
		if(mysqli_num_rows($resultchecknic)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}
	
	else if($_GET["frompage"]=="bidders_email")
	{
		$get_ajax_email = $_GET["ajax_email"];
		$sqlcheckemail="SELECT user_name FROM user WHERE user_name='$get_ajax_email'";
		$resultcheckemail=mysqli_query($con,$sqlcheckemail);
		if(mysqli_num_rows($resultcheckemail)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="staff_mobile")
	{
		$get_ajax_mobile = $_GET["ajax_mobile"];
		$get_ajax_staff_id = $_GET["ajax_staff_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckmobile="SELECT mobile FROM staff WHERE mobile='$get_ajax_mobile' ";
		}
		else 
		{
			$sqlcheckmobile="SELECT mobile FROM staff WHERE mobile='$get_ajax_mobile' AND staff_id!='$get_ajax_staff_id'";
		}
		
		$resultcheckmobile=mysqli_query($con,$sqlcheckmobile);
		if(mysqli_num_rows($resultcheckmobile)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="proprietor_mobile")
	{
		$get_ajax_mobile = $_GET["ajax_mobile"];
		$get_ajax_proprietor_id = $_GET["ajax_proprietor_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckmobile="SELECT mobile FROM proprietor WHERE mobile='$get_ajax_mobile' ";
		}
		else 
		{
			$sqlcheckmobile="SELECT mobile FROM proprietor WHERE mobile='$get_ajax_mobile' AND proprietor_id!='$get_ajax_proprietor_id'";
		}
		
		$resultcheckmobile=mysqli_query($con,$sqlcheckmobile);
		if(mysqli_num_rows($resultcheckmobile)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="bidder_mobile")
	{
		$get_ajax_mobile = $_GET["ajax_mobile"];
		$get_ajax_bidders_id = $_GET["ajax_bidders_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckmobile="SELECT mobile FROM bidders WHERE mobile='$get_ajax_mobile' ";
		}
		else 
		{
			$sqlcheckmobile="SELECT mobile FROM bidders WHERE mobile='$get_ajax_mobile' AND bidder_id!='$get_ajax_bidders_id'";
		}
		
		$resultcheckmobile=mysqli_query($con,$sqlcheckmobile);
		if(mysqli_num_rows($resultcheckmobile)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="department_name")
	{
		$get_ajax_department_name = $_GET["ajax_department_name"];
		$get_ajax_department_id = $_GET["ajax_department_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckdepartment_name="SELECT department_name FROM department WHERE department_name='$get_ajax_department_name' ";
		}
		else 
		{
			$sqlcheckdepartment_name="SELECT department_name FROM department WHERE department_name='$get_ajax_department_name' AND department_id!='$get_ajax_department_id'";
		}
		
		$resultcheckdepartment_name=mysqli_query($con,$sqlcheckdepartment_name);
		if(mysqli_num_rows($resultcheckdepartment_name)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="name")
	{
		$get_ajax_name = $_GET["ajax_name"];
		$get_ajax_role_id = $_GET["ajax_role_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckname="SELECT name FROM role WHERE name='$get_ajax_name' ";
		}
		else 
		{
			$sqlcheckname="SELECT name FROM role WHERE name='$get_ajax_name' AND role_id!='$get_ajax_role_id'";
		}
		
		$resultcheckname=mysqli_query($con,$sqlcheckname);
		if(mysqli_num_rows($resultcheckname)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="bidders_bank_account_no")
	{
		$get_ajax_account_no = $_GET["ajax_account_no"];
		$get_ajax_bank_id = $_GET["ajax_bank_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckaccount_no="SELECT account_no FROM bidders_bank WHERE account_no='$get_ajax_account_no' ";
		}
		else 
		{
			$sqlcheckaccount_no="SELECT account_no FROM bidders_bank WHERE account_no='$get_ajax_account_no' AND bank_id!='$get_ajax_bank_id'";
		}
		
		$resultcheckaccount_no=mysqli_query($con,$sqlcheckaccount_no);
		if(mysqli_num_rows($resultcheckaccount_no)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="bidders_bank_IBAN_no")
	{
		$get_ajax_IBAN_no = $_GET["ajax_IBAN_no"];
		$get_ajax_bank_id = $_GET["ajax_bank_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckIBAN_no="SELECT IBAN_no FROM bidders_bank WHERE IBAN_no='$get_ajax_IBAN_no' ";
		}
		else 
		{
			$sqlcheckIBAN_no="SELECT IBAN_no FROM bidders_bank WHERE IBAN_no='$get_ajax_IBAN_no' AND bank_id!='$get_ajax_bank_id'";
		}
		
		$resultcheckIBAN_no=mysqli_query($con,$sqlcheckIBAN_no);
		if(mysqli_num_rows($resultcheckIBAN_no)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="bidders_bank_SWIFT_no")
	{
		$get_ajax_SWIFT_no = $_GET["ajax_SWIFT_no"];
		$get_ajax_bank_id = $_GET["ajax_bank_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckSWIFT_no="SELECT swift_code FROM bidders_bank WHERE swift_code='$get_ajax_SWIFT_no' ";
		}
		else 
		{
			$sqlcheckSWIFT_no="SELECT swift_code FROM bidders_bank WHERE swift_code='$get_ajax_SWIFT_no' AND bank_id!='$get_ajax_bank_id'";
		}
		
		$resultcheckSWIFT_no=mysqli_query($con,$sqlcheckSWIFT_no);
		if(mysqli_num_rows($resultcheckSWIFT_no)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="bidder_business_registration_no")
	{
		$get_ajax_business_registration_no = $_GET["ajax_business_registration_no"];
		$get_ajax_bidders_id = $_GET["ajax_bidders_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckbusiness_registration_no="SELECT business_registration_no FROM bidders WHERE business_registration_no='$get_ajax_business_registration_no' ";
		}
		else 
		{
			$sqlcheckbusiness_registration_no="SELECT business_registration_no FROM bidders WHERE business_registration_no='$get_ajax_business_registration_no' AND bidder_id!='$get_ajax_bidders_id'";
		}
		
		$resultcheckbusiness_registration_no=mysqli_query($con,$sqlcheckbusiness_registration_no);
		if(mysqli_num_rows($resultcheckbusiness_registration_no)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="bidders_vat_registration_no")
	{
		$get_ajax_vat_registration_no = $_GET["ajax_vat_registration_no"];
		$get_ajax_bidders_id = $_GET["ajax_bidders_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckvat_registration_no="SELECT vat_registration_no FROM bidders WHERE vat_registration_no='$get_ajax_vat_registration_no' ";
		}
		else 
		{
			$sqlcheckvat_registration_no="SELECT vat_registration_no FROM bidders WHERE vat_registration_no='$get_ajax_vat_registration_no' AND bidder_id!='$get_ajax_bidders_id'";
		}
		
		$resultcheckvat_registration_no=mysqli_query($con,$sqlcheckvat_registration_no);
		if(mysqli_num_rows($resultcheckvat_registration_no)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}

	else if($_GET["frompage"]=="proprietor_land")
	{
		$get_ajax_land = $_GET["ajax_land"];
		$get_ajax_proprietor_id = $_GET["ajax_proprietor_id"];
		$get_ajax_option = $_GET["ajax_option"];
		if($get_ajax_option=="add")
		{
			$sqlcheckland="SELECT land FROM proprietor WHERE land='$get_ajax_land' ";
		}
		else 
		{
			$sqlcheckland="SELECT land FROM proprietor WHERE land='$get_ajax_land' AND proprietor_id!='$get_ajax_proprietor_id'";
		}
		
		$resultcheckland=mysqli_query($con,$sqlcheckland);
		if(mysqli_num_rows($resultcheckland)==0)
		{
			echo "no";
		}
		else
		{
			echo "yes";
		}
	}
	else if($_GET["frompage"]=="tender_enable_close_date")
	{
		$get_ajax_publish_date = $_GET["ajax_publish_date"];
		$max_date = date("Y-m-d 00:00:00", strtotime($get_ajax_publish_date . " +60 days"));
		$min_date = date("Y-m-d 00:00:00", strtotime($get_ajax_publish_date));
		echo $min_date . "&&&&" . $max_date;
	}
	else if($_GET["frompage"]=="tender_enable_open_date")
	{
		$get_ajax_bid_close_date = $_GET["ajax_bid_close_date"];
		$max_date = date("Y-m-d H:i:s", strtotime($get_ajax_bid_close_date . " +15 minutes"));
		echo $max_date;
	}
	else if($_GET["frompage"]=="bids_bid_valide_date")
	{
		$get_ajax_tender_ref_no = $_GET["ajax_tender_ref_no"];

		$sql_tender="SELECT bid_close_date, tender_type FROM tender WHERE tender_ref_no='$get_ajax_tender_ref_no'";
		$result_tender=mysqli_query($con,$sql_tender);
		$row_tender=mysqli_fetch_assoc($result_tender);
		echo $row_tender['bid_close_date']."&&&&".$row_tender['tender_type'];
	}
	else if($_GET["frompage"]=="bid_product")
	{
		$get_ajax_tender_id = $_GET["ajax_tender_id"];
		$get_ajax_product_id = $_GET["ajax_product_id"];


		$sql_tender="SELECT avaliable_qty, min_qty FROM tender_product WHERE tender_id='$get_ajax_tender_id' AND product_id='$get_ajax_product_id'";
		$result_tender=mysqli_query($con,$sql_tender);
		$row_tender=mysqli_fetch_assoc($result_tender);
		echo $row_tender['min_qty']."&&&&".$row_tender['avaliable_qty'];
	}



}
?>