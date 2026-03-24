<?php
if(!isset($_SESSION)){
    session_start();
}
if(isset($_SESSION["login_usertype"]))
    {
        //Your account has been accessed.
        $system_usertype=$_SESSION["login_usertype"];
        $system_user_id=$_SESSION["login_user_id"];
        $system_user_name=$_SESSION["login_user_name"];
    }
    else{
        //guest or public
        $system_usertype="Guest";
    }
include("config.php");
//Insert code start
if(isset($_POST["btnsave"]))
    {
        $sqlinsert="INSERT INTO bidders (bidder_id,company_name,address,mobile,email,website,land,fax,nature_of_the_business,
        business_registration_no,business_registration_copy,vat_registration_no,vat_registration_copy	)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtbidder_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtcompany_name"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtemail"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtwebsite"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtland"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtfax"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtnature_of_the_business"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbusiness_registration_no"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbusiness_registration_copy"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtvat_registration_no"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtvat_registration_copy"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        // Insert into user
        $password=md5(123456);
        $sqlinsertlogin="INSERT INTO user (user_id,user_name,password,usertype,attempt,otp,status)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtbidder_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtemail"])."',
                        '".mysqli_real_escape_string($con,$password)."',
                        '".mysqli_real_escape_string($con,"Bidders")."',
                        '".mysqli_real_escape_string($con,0)."',
                        '".mysqli_real_escape_string($con,0)."',
                        '".mysqli_real_escape_string($con,"Active")."')"; 
        $insertloginresult=mysqli_query($con,$sqlinsertlogin) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=bidders.php&option=add" </script>';
            }

    }
if(isset($_POST['business_registration_copy']))
{
	$bidder_id=$_POST['txtbidder_id'];
	$sqlupdate="UPDATE bidders 
				SET business_registration_copy='".mysqli_real_escape_string($con,$_FILES["business_registration_copy"]["name"])."'
				WHERE bidder_id='$bidder_id'";
	$result=mysqli_query($con,$sqlupdate)or die("Error in sqlinser:" .mysqli_error($con));
	
	
	//file upload to business_registration_copy folder
	$target_dir = "bidders/business_registration_copy/";
	$target_file = $target_dir . basename($_FILES["business_registration_copy"]["name"]);
	move_uploaded_file($_FILES["business_registration_copy"]["tmp_name"], $target_file);
}  

if(isset($_POST['vat_registration_copy']))
{
	$bidder_id=$_POST['txtbidder_id'];
	$sqlupdate="UPDATE bidders 
				SET vat_registration_copy='".mysqli_real_escape_string($con,$_FILES["vat_registration_copy"]["name"])."'
				WHERE bidder_id='$bidder_id'";
	$result=mysqli_query($con,$sqlupdate)or die("Error in sqlinser:" .mysqli_error($con));
	
	
	//file upload to vat_registration_copy folder
	$target_dir = "bidders/vat_registration_copy/";
	$target_file = $target_dir . basename($_FILES["vat_registration_copy"]["name"]);
	move_uploaded_file($_FILES["vat_registration_copy"]["tmp_name"], $target_file);
}
// Insert code end
//$br_copy_name = "";
        //if(isset($_FILES["txtbusiness_registration_copy"]) && $_FILES["txtbusiness_registration_copy"]["error"] == 0) {
            //$target_dir_br = "bidders/business_registration/";
            //$br_copy_name = basename($_FILES["txtbusiness_registration_copy"]["name"]);
           // $target_file_br = $target_dir_br . $br_copy_name;
           // move_uploaded_file($_FILES["txtbusiness_registration_copy"]["tmp_name"], $target_file_br);
       // }

       // $vat_copy_name = "";
       // if(isset($_FILES["txtvat_registration_copy"]) && $_FILES["txtvat_registration_copy"]["error"] == 0) {
           // $target_dir_vat = "bidders/vat_registration/";
           // $vat_copy_name = basename($_FILES["txtvat_registration_copy"]["name"]);
           // $target_file_vat = $target_dir_vat . $vat_copy_name;
           // move_uploaded_file($_FILES["txtvat_registration_copy"]["tmp_name"], $target_file_vat);
       // }
//Update code start
if(isset($_POST["btnupdate"]))
    {

        $sqlupdate="UPDATE bidders SET
        company_name='".mysqli_real_escape_string($con,$_POST["txtcompany_name"])."',
        address='".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
        mobile='".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
        email='".mysqli_real_escape_string($con,$_POST["txtemail"])."',
        website='".mysqli_real_escape_string($con,$_POST["txtwebsite"])."',
        land='".mysqli_real_escape_string($con,$_POST["txtland"])."',
        fax='".mysqli_real_escape_string($con,$_POST["txtfax"])."',
        nature_of_the_business='".mysqli_real_escape_string($con,$_POST["txtnature_of_the_business"])."',
        business_registration_no='".mysqli_real_escape_string($con,$_POST["txtbusiness_registration_no"])."',
        vat_registration_no='".mysqli_real_escape_string($con,$_POST["txtvat_registration_no"])."'
        WHERE bidder_id='$bidderid'";

        $resultupdate=mysqli_query($con,$sqlupdate);

        if($resultupdate)
            {
            echo '<script>alert("Record Updated Successfully");window.location.href="index.php?page=bidders.php&option=view";</script>';
            }

    }
    
// Update code end
?>
<body>
    <?php
    if(isset($_GET["option"])) 
       {
        if($_GET["option"]=="add")
            {
                ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Bidders Add Form</h1>
                                </div>
                            </div>
                            <div class="sparkline12-graph">
                                <div class="basic-login-form-ad">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="all-form-element-inner">
                                                <form action="" method="POST" enctype="multipart/form-data">
                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bidder Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $sql_generatedid="SELECT bidder_id FROM bidders ORDER BY bidder_id DESC LIMIT 1";
                                                                
                                                                $result_generatedid=mysqli_query($con,$sql_generatedid) or die("SQL error in sql_generateid".mysqli_error($con));
                                                                if(mysqli_num_rows($result_generatedid)==1)
                                                                    {//for other than first time
                                                                    $row_generatedid=mysqli_fetch_assoc($result_generatedid);
                                                                    $generatedid=++$row_generatedid["bidder_id"];
                                                                    }
                                                                else
                                                                    { //for first time
                                                                    $generatedid="PP00000001";
                                                                    }
                                                                ?>
                                                                <input type="text" name="txtbidder_id" id="txtbidder_id" class="form-control" value="<?php echo $generatedid;?>" readonly required />
                                                            </div>
                                                            <!-- One Column End-->                                                            
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->    

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Name of the Company </label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtcompany_name" id="txtcompany_name" class="form-control" onkeypress="return isTextKey(event)" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->  
                                                
                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Address</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="textarea" name="txtaddress" id="txtaddress" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                        </div>
                                                    </div>
                                                <!-- One Row End--> 

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Telephone General</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtland" id="txtland" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Fax</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtfax" id="txtfax" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Mobile</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtmobile" id="txtmobile" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->                                                               
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Email</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtemail" id="txtemail" class="form-control" onblur="emailvalidation()" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Website</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtwebsite" id="txtwebsite" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Nature of the Business</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtnature_of_the_business" id="txtnature_of_the_business" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Business Registration Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbusiness_registration_no" id="txtbusiness_registration_no" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro"> Certified copy of Valid Business Registration </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">                                                                
                                                                <input type="file" name="txtbusiness_registration_copy" id="txtbusiness_registration_copy" class="form-control" placeholder="no file selected">                                                                   
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End--> 
                                                                                                                                             
                                                

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">VAT Registration Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtvat_registration_no" id="txtvat_registration_no" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                             <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Certified copy of Valid VAT Registration</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                
                                                                        <input type="file" name="txtvat_registration_copy" id="txtvat_registration_copy" class="form-control" placeholder="no file selected">
                                                                    
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=bidders.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
                                                                <input type="reset" name="btnclear" id="btnclear" class="btn btn-danger" value="Clear" />
                                                                <input type="Submit" name="btnsave" id="btnsave" class="btn btn-success" value="Save" /> 
                                                            </center>
                                                        </div>
                                                    </div>   
                                                <!-- Button End--> 
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            else if($_GET["option"]=="view")
            {
            ?>
            <div class="data-table-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline13-list">
                            <div class="sparkline13-hd">
                                <div class="main-sparkline13-hd">
                                    <h1>Bidder <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=bidders.php&option=add"><button class="btn btn-primary">Add Bidder</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="bidder_id ">Bidder ID</th>
                                                <th data-field="company_name" >Company Name</th>
                                                <th data-field="address" >Address</th>
                                                <th data-field="nature_of_the_business" >Nature of Business</th>
                                                <th data-field="business_registration_no">Business Registration Number</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT bidder_id,company_name,address,mobile,nature_of_the_business From bidders";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    
                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["bidder_id"].'</td>';
                                                    echo'<td>'.$rowview["company_name"].'</td>';
                                                    echo'<td>'.$rowview["address"].'</td>';
                                                    echo'<td>'.$rowview["mobile"].'</td>';
                                                    echo'<td>'.$rowview["nature_of_the_business"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=bidders.php&option=fullview&pk_bidder_id='.$rowview["bidder_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    echo'<a href="index.php?page=bidders.php&option=edit&pk_bidder_id='.$rowview["bidder_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=bidders.php&option=delete&pk_bidder_id='.$rowview["bidder_id"].'"><button class="btn btn-danger">Delete</button></a> ';
                                                    echo'</td>';
                                                    echo'</tr>';
                                                }
                                            ?>
                                        </tbody>
                                        <table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <?php 
            }
            else if($_GET["option"]=="fullview")
            {
                $bidderid=$_GET["pk_bidder_id"];

                $sqlview="SELECT * FROM bidders WHERE bidder_id='$bidderid'";
                $resultview=mysqli_query($con,$sqlview) or die(mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);
                ?>

                <div class="static-table-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="sparkline8-list">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <h1>Role Table</h1>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table">                                        
                                                    <tbody>                                                      
                                                        <tr>
                                                            <td><b>Bidder ID</b></td>
                                                            <td><?php echo $rowview["bidder_id"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Company Name</b></td>
                                                            <td><?php echo $rowview["company_name"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Address</b></td>
                                                            <td><?php echo $rowview["address"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Telephone</b></td>
                                                            <td><?php echo $rowview["land"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Fax</b></td>
                                                            <td><?php echo $rowview["fax"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Mobile</b></td>
                                                            <td><?php echo $rowview["mobile"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Email</b></td>
                                                            <td><?php echo $rowview["email"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Website</b></td>
                                                            <td><?php echo $rowview["website"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Nature of Business</b></td>
                                                            <td><?php echo $rowview["nature_of_the_business"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Business Registration No</b></td>
                                                            <td><?php echo $rowview["business_registration_no"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Certified copy of Valid Business Registration</b></td>                                                            
                                                            <td><?php echo $rowview["briefly_specification_doc"]; ?></td>
                                                            <td>
                                                                <a href="bidders/business_registration_copy/<?php echo $rowfullview["business_registration_file"]; ?>" target="_blank">
                                                                <button class="btn btn-primary" name="btnview" type="button" id="btnview">View</button></a>
                                                                </a>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>VAT Registration No</b></td>
                                                            <td><?php echo $rowview["vat_registration_no"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Certified copy of Valid VAT Registration</b></td>
                                                            <td>
                                                            <?php
                                                            if(!empty($rowview['vat_registration_file']))
                                                            {
                                                            ?>
                                                            <a href="bidders/vat_registration_copy/<?php echo $rowview['vat_registration_file']; ?>" target="_blank">
                                                            <button class="btn btn-primary" type="button">View</button>
                                                            </a>
                                                            <?php
                                                            }
                                                            else
                                                            {
                                                            echo "File Not Available";
                                                            }
                                                            ?>
                                                            </td>
                                                        </tr>

                                                    </table>

                                                    <a href="index.php?page=bidders.php&option=view">
                                                    <button class="btn btn-warning">Back</button>
                                                    </a>
                                                    <?php
                                                    if(!isset($_GET['print']))
                                                    {
                                                    echo '<a href="print.php?bidder_id='.$rowview['bidder_id'].'" target="_blank">
                                                    <button class="btn btn-primary" name="btnprint" type="button" id="btnprint">Print</button>
                                                    </a>';
                                                    }
                                                    ?> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <br>    
                <?php                
            }
            else if($_GET["option"]=="edit")
            {
                $bidderid=$_GET["pk_bidder_id"];

                $sqlview="SELECT * FROM bidders WHERE bidder_id='$bidderid'";
                $resultview=mysqli_query($con,$sqlview);
                $rowview=mysqli_fetch_assoc($resultview);  
                ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Bidder Edit Form</h1>
                                </div>
                            </div>
                            <div class="sparkline12-graph">
                                <div class="basic-login-form-ad">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="all-form-element-inner">
                                                <form action="" method="POST">
                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bidder Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbidder_id" id="txtbidder_id" class="form-control" value="<?php echo $rowview['bidder_id']; ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Name of the Company</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtcompany_name" id="txtcompany_name" class="form-control" value="<?php echo $rowview['company_name']; ?>" required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            </div>
                                                    </div>
                                                <!-- One Row End--> 
                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">  
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-1">
                                                                <label class="login2 pull-right pull-right-pro">Address</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtaddress" id="txtaddress" class="form-control" value="<?php echo $rowview['address']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                             </div>
                                                    </div>
                                                <!-- One Row End--> 
                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Telephone General</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtland" id="txtland" class="form-control" value="<?php echo $rowview['land']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->  
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Fax</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtfax" id="txtfax" class="form-control" value="<?php echo $rowview['fax']; ?>" required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            </div>
                                                    </div>
                                                <!-- One Row End--> 
                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">  
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Mobile</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtmobile" id="txtmobile" class="form-control" value="<?php echo $rowview['land']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->  
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Email</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtemail" id="txtemail" class="form-control" value="<?php echo $rowview['email']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            </div>
                                                    </div>
                                                <!-- One Row End--> 
                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">  
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Website</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtwebsite" id="txtwebsite" class="form-control" value="<?php echo $rowview['website']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->  
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Nature of the Business</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtnature_of_the_business" id="txtnature_of_the_business" class="form-control" value="<?php echo $rowview['email']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            </div>
                                                    </div>
                                                <!-- One Row End--> 
                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">   
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Business Registration Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbusiness_registration_no" id="txtbusiness_registration_no" class="form-control" value="<?php echo $rowview['business_registration_no']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->  
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Certified copy of Valid Business Registration</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="file" name="txtbusiness_registration_copy" id="txtbusiness_registration_copy" class="form-control" value="<?php echo $rowview['business_registration_copy']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            </div>
                                                    </div>
                                                <!-- One Row End--> 
                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">  
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">VAT Registration Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtvat_registration_no" id="txtvat_registration_no" class="form-control" value="<?php echo $rowview['business_registration_no']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->  
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Certified copy of Valid VAT Registration</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="file" name="txtvat_registration_copy" id="txtvat_registration_copy" class="form-control" value="<?php echo $rowview['business_registration_copy']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->                                                   
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=bidders.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
                                                                <input type="Submit" name="btnupdate" id="btnupdate" class="btn btn-success" value="Update" /> 
                                                            </center>
                                                        </div>
                                                    </div>   
                                                <!-- Button End--> 
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php   
            }
            else if($_GET["option"]=="delete")
            {
                
            }

       }
       ?>
</body>