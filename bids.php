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
date_default_timezone_set('Asia/Colombo');
include("config.php");

$upload_dir = "bids/certifieddocument/";
$upload_dir_fs = rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $upload_dir);

//Insert code start
if(isset($_POST["btnsave"]))
    {
        $bid_id = $_POST["txtbid_id"];
        $file_certifieddocument = "";

        if(isset($_FILES["txtcertifieddocument"]) && $_FILES["txtcertifieddocument"]["error"] == 0)
        {
            $original_file = basename($_FILES["txtcertifieddocument"]["name"]);
            $tmp_name = $_FILES["txtcertifieddocument"]["tmp_name"];

            // File extension
            $ext = strtolower(pathinfo($original_file, PATHINFO_EXTENSION));
            $allowed = array("pdf","jpg","jpeg","png");

            // Validate file type
            if(!in_array($ext,$allowed))
            {
                echo "<script>alert('Invalid file type');</script>";
                exit();
            }

            if(!is_dir($upload_dir_fs))
            {
                mkdir($upload_dir_fs, 0777, true);
            }
            $bid_id1 = str_replace('/', '_', $bid_id);
            $file_certifieddocument = $bid_id1 . "." . $ext;

            // If already exists, delete (safety)
            if(file_exists($upload_dir_fs.$file_certifieddocument))
            {
                unlink($upload_dir_fs.$file_certifieddocument);
            }

            // Ensure the target directory exists
            $target_path = $upload_dir_fs . $file_certifieddocument;
            $target_dir = dirname($target_path);
            if(!is_dir($target_dir))
            {
                mkdir($target_dir, 0777, true);
            }

            // Move file
            if(!move_uploaded_file($tmp_name, $target_path))
            {
                echo "<script>alert('File upload failed');</script>";
                exit();
            }

        }

        $sqlinsert="INSERT INTO bids (bid_id,tender_ref_no,bidder_id,proprietor_id,status,delivery_method,delivery_place,bid_currency,bid_valide_date,submit_date,certifieddocument,open_key	)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtbid_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txttender_ref_no"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbidder_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtproprietor_id"])."',
                        '".mysqli_real_escape_string($con,"Pending")."',
                        '".mysqli_real_escape_string($con,$_POST["txtdelivery_method"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtdelivery_place"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbid_currency"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbid_valide_date"])."',
                        '".mysqli_real_escape_string($con,date("Y-m-d H:i:s"))."',
                        '$file_certifieddocument',
                        '".mysqli_real_escape_string($con,base64_encode($_POST["txtopen_key"]))."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=bids_product.php&option=add&bid_id=' . $_POST["txtbid_id"] . '" </script>';
            }
    }
// Insert code end

//Update code start
// if(isset($_POST["btnupdate"]))
// {
//     $bid_id = mysqli_real_escape_string($con,$_POST["txtbid_id"]);
//     // OLD FILES
//     $old_certifieddocument_file = isset($_POST["old_certifieddocument_file"]) ? $_POST["old_certifieddocument_file"] : "";

//     // KEEP OLD FILES BY DEFAULT
//     $file_certifieddocument = $old_certifieddocument_file;

//     //  CERTIFIED DOCUMENT FILE 
//     if(!empty($_FILES["txtcertifieddocument"]["name"]))
//     {
//         $upload_dir = "bids/certifieddocument/";
//         $upload_dir_fs = rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $upload_dir);

//         // File extension
//         $ext = strtolower(pathinfo($original_file, PATHINFO_EXTENSION));
//         $allowed = array("pdf","jpg","jpeg","png");

//         // Validate file type
//         if(!in_array($ext,$allowed))
//         {
//             echo "<script>alert('Invalid file type');</script>";
//             exit();
//         }

//         if(!is_dir($upload_dir_fs))
//         {
//             mkdir($upload_dir_fs, 0777, true);
//         }

//         $bid_id1 = str_replace('/', '_', $bid_id);
//         $file_certifieddocument = $bid_id1 . "." . $ext;

//         // DELETE OLD FILE ONLY IF NEW FILE UPLOADED
//         if(!empty($old_certifieddocument_file) && file_exists($upload_dir_fs.$old_certifieddocument_file))
//         {
//             unlink($upload_dir_fs.$old_certifieddocument_file);
//         }

//         $file_certifieddocument = $bid_id."_CD.".$ext;

//         move_uploaded_file($_FILES["txtcertifieddocument"]["tmp_name"], $upload_dir_fs.$file_certifieddocument);
//     }
//     //  UPDATE 
//     $sqlupdate="UPDATE bid SET 
//         delivery_method='".mysqli_real_escape_string($con,$_POST["txtwebsite"])."',
//         delivery_place='".mysqli_real_escape_string($con,$_POST["txtland"])."',
//         bid_currency='".mysqli_real_escape_string($con,$_POST["txtfax"])."',
//         bid_valide_date='".mysqli_real_escape_string($con,$_POST["txtnature_of_the_business"])."',
//         submit_date='".mysqli_real_escape_string($con,date("Y-m-d H:i:s"))."',
//         certifieddocument='$file_certifieddocument',
//         open_key='".mysqli_real_escape_string($con,base64_encode($_POST["txtopen_key"]))."'
//         WHERE bid_id='$bid_id'";

//     mysqli_query($con,$sqlupdate) or die(mysqli_error($con));

//     echo '<script>alert("Updated Successfully");window.location="index.php?page=bids.php&option=fullview&pk_bid_id=' . $_POST["txtbid_id"] . '";</script>';
// }

?>
<script>
    function assign_bid_date()
    {
        var tender_ref_no=document.getElementById("txttender_ref_no").value;
        document.getElementById("txtbid_valide_date").value="";
        document.getElementById("bid_currency_instruction").innerHTML="";
        if(tender_ref_no!="select")
        {
            var xhttp=new XMLHttpRequest();
            xhttp.onreadystatechange=function(){
                if(this.readyState==4 && this.status==200)
                {
                    var response = this.responseText.split("&&&&");
                    document.getElementById("txtbid_valide_date").value=response[0];
                    if(response[1]=="Procurement")
                    {
                        document.getElementById("txtdelivery_method").innerHTML='<option value="">-- Select --</option><option value="Free Delivery to Head Office Rajagiriya">Free Delivery to Head Office Rajagiriya</option><option value="No Free Delivery">No Free Delivery</option>';
                        document.getElementById("txtdelivery_place").innerHTML='<option value="">-- Select --</option><option>Head Office Rajagiriya</option>';
                        document.getElementById("bid_currency_instruction").innerHTML="Bid currency should be in LKR for procuring goods.";
                    }
                    else
                    {
                        document.getElementById("txtdelivery_method").innerHTML='<option value="">-- Select --</option><option value="Delivery Arrange By Bidder Own">Delivery Arrange By Bidder Own</option>';
                        document.getElementById("txtdelivery_place").innerHTML='<option value="">-- Select --</option><option>Ex - works Pulmoddai</option><option>Ex - works Trincomalee Warehouse at Trinco Harbor</option>';
                        document.getElementById("bid_currency_instruction").innerHTML="Bid currency should be in $ for sales of goods.";
                    }
                }
            };
            xhttp.open("GET","ajaxpage.php?frompage=bids_bid_valide_date&ajax_tender_ref_no="+tender_ref_no,true);
            xhttp.send();
        }
    }
</script>
<body>
    <?php
    if(isset($_GET["option"])) 
       {
        if($_GET["option"]=="add")
            {
                ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="banner-image" style="background: url('img/logo/form_header.png') no-repeat center center; background-size: cover; height: 180px; border-radius: 6px; margin-bottom: 20px;">
                                <div style="background: rgba(0,0,0,0.35); height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <h2 style="color: #ffffff; margin: 0; font-size: 28px; text-shadow: 0 1px 6px rgba(0,0,0,0.6);"></h2>
                                </div>
                            </div>
                        </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Bids Add Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Bid Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $currentYear = date('Y');
                                                                $sql_generatedid = "SELECT bid_id FROM bids WHERE bid_id LIKE 'BID/$currentYear/%' ORDER BY bid_id DESC LIMIT 1";
                                                                $result_generatedid = mysqli_query($con, $sql_generatedid) or die("SQL error in sql_generateid".mysqli_error($con));
                                                                if(mysqli_num_rows($result_generatedid) > 0)
                                                                    {
                                                                        $row = mysqli_fetch_assoc($result_generatedid);
                                                                        $lastBidId = $row['bid_id'];
                                                                        $parts = explode('/', $lastBidId);
                                                                        $nextSeq = '00000000001';
                                                                        if(count($parts) === 3 && ctype_digit($parts[2]))
                                                                        {
                                                                            $nextSeq = str_pad((int)$parts[2] + 1, 11, '0', STR_PAD_LEFT);
                                                                        }
                                                                        $bid_id = "BID/$currentYear/$nextSeq";
                                                                    }
                                                                    else
                                                                    {
                                                                        $bid_id = "BID/$currentYear/00000000001";
                                                                    }
                                                                    ?>                                                            
                                                                <input type="text" name="txtbid_id" id="txtbid_id" class="form-control" value="<?php echo $bid_id; ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Tender Reference Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txttender_ref_no" id="txttender_ref_no" class="form-control" onblur="assign_bid_date()" required>
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT tender_ref_no FROM tender WHERE status='Active'";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            $sqlcheck="SELECT bid_id FROM bids WHERE tender_ref_no='".$row_load["tender_ref_no"]."' AND bidder_id='".$system_user_id."'"; 
                                                                            $resultcheck=mysqli_query($con, $sqlcheck) or die ("SQL error in sqlcheck".mysqli_error($con));
                                                                            if(mysqli_num_rows($resultcheck)==0)
                                                                            {
                                                                                echo'<option value="'.$row_load["tender_ref_no"].'">'.$row_load["tender_ref_no"].'</option>';
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select> 
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
                                                                <label class="login2 pull-right pull-right-pro">Bidder Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtbidder_id" id="txtbidder_id" class="form-control" required>
                                                                    <?php
                                                                    $sql_load="SELECT bidder_id, company_name FROM bidders WHERE bidder_id='".$system_user_id."'";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["bidder_id"].'">'.$row_load["company_name"].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Proprietor Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtproprietor_id" id="txtproprietor_id" class="form-control" required>
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT proprietor_id,name FROM proprietor WHERE bidder_id='".$system_user_id."'";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["proprietor_id"].'">'.$row_load["name"].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
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
                                                                <label class="login2 pull-right pull-right-pro">Bid Valide Date</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="datetime-local" name="txtbid_valide_date" id="txtbid_valide_date" class="form-control" readonly required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Status</label>
                                                            </div> -->
                                                            <!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <div class="form-select-list">
                                                                    <select class="form-control custom-select-value" name="txtstatus"  id="txtstatus" required >
																			<option>-- Select --</option>
                                                                            <option>Approved</option>
																			<option>Pending</option>
																			<option>Reject</option>
																		</select>
                                                                </div>
                                                            </div> -->
                                                            <!-- One Column End-->  
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Delivery Method</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <div class="form-select-list">
                                                                    <select class="form-control custom-select-value" name="txtdelivery_method" id="txtdelivery_method" required >
																			<option value="">-- Select --</option>
																		</select>
                                                                </div>
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
                                                                <label class="login2 pull-right pull-right-pro">Delivery Place</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <div class="form-select-list">
                                                                    <select class="form-control custom-select-value" name="txtdelivery_place" id="txtdelivery_place" required >
                                                                        <option value="">-- Select --</option>
																	</select>
                                                                </div>
                                                            </div>
                                                            <!-- One Column End--> 
                                                             <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Currency</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbid_currency" id="txtbid_currency" class="form-control" placeholder="$ for sales / LKR for procuring goods" required />
                                                                <div id="bid_currency_instruction" class="text-danger"></div>

                                                                
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
                                                                <label class="login2 pull-right pull-right-pro">Certified Document </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">                                                                
                                                                <input type="file" name="txtcertifieddocument" id="txtcertifieddocument" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->    
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Open Key</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="password" name="txtopen_key" id="txtopen_key" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->                                                         
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->


                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=bids.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Bids <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=bids.php&option=add"><button class="btn btn-primary">Add Bids</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="bid_id">Bid ID</th>
                                                <th data-field="bidder_id" >Company Name</th>
                                                <th data-field="proprietor_id" >Proprietor Name</th>
                                                <th data-field="status" >Status</th>
                                                <th data-field="open_key">Open Key</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT bid_id,bidder_id,proprietor_id,status,open_key From bids";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    $sqlbiddersname="SELECT company_name FROM bidders WHERE bidder_id ='$rowview[bidder_id]'";
                                                    $resultbiddersname=mysqli_query($con,$sqlbiddersname) or die("SQL view error".mysqli_error($con));
                                                    $rowbiddersname=mysqli_fetch_assoc($resultbiddersname);

                                                    $sqlproprietorname="SELECT name FROM proprietor WHERE proprietor_id ='$rowview[proprietor_id]'";
                                                    $resultproprietorname=mysqli_query($con,$sqlproprietorname) or die("SQL view error".mysqli_error($con));
                                                    $rowproprietorname=mysqli_fetch_assoc($resultproprietorname);
                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["bid_id"].'</td>';
                                                    echo'<td>'.$rowbiddersname["company_name"].'</td>';
                                                    echo'<td>'.$rowproprietorname["name"].'</td>';
                                                    echo'<td>'.$rowview["status"].'</td>';
                                                    echo'<td>'.$rowview["open_key"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=bids.php&option=fullview&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    // echo'<a href="index.php?page=bids.php&option=edit&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    // echo'<a onclick="return deletedata()" href="index.php?page=bids.php&option=delete&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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
                // Get and sanitize the bid_id parameter
                $pk_bid_id = isset($_GET["pk_bid_id"]) ? mysqli_real_escape_string($con, $_GET["pk_bid_id"]) : '';

                if (empty($pk_bid_id)) {
                echo '<p class="alert alert-danger">Invalid Bid ID</p>';
                exit;
                }

                // Fetch bid details from database
                $sqlview = "SELECT * FROM bids WHERE bid_id='$pk_bid_id'";
                $resultview = mysqli_query($con, $sqlview) or die("SQL error: " . mysqli_error($con));
                
                if (mysqli_num_rows($resultview) == 0) {
                    echo '<p class="alert alert-danger">Bid not found</p>';
                    exit;
                }
                
                $rowview = mysqli_fetch_assoc($resultview);

                // Get bidder company name
                $bidder_id = mysqli_real_escape_string($con, $rowview['bidder_id']);
                $sqlbidder = "SELECT company_name,address,land,fax,mobile,email,website FROM bidders WHERE bidder_id='$bidder_id'";
                $resultbidder = mysqli_query($con, $sqlbidder) or die("SQL error: " . mysqli_error($con));
                $bidder_name = '';
                $bidder_address = '';
                $bidder_land = '';
                $bidder_fax = '';
                $bidder_mobile = '';
                $bidder_email = '';
                $bidder_website = '';
                if (mysqli_num_rows($resultbidder) > 0) {
                    $rowbidder = mysqli_fetch_assoc($resultbidder);
                    $bidder_name = htmlspecialchars($rowbidder['company_name']);
                    $bidder_address = htmlspecialchars($rowbidder['address']);
                    $bidder_land = htmlspecialchars($rowbidder['land']);
                    $bidder_fax = htmlspecialchars($rowbidder['fax']);
                    $bidder_mobile = htmlspecialchars($rowbidder['mobile']);
                    $bidder_email = htmlspecialchars($rowbidder['email']);
                    $bidder_website = htmlspecialchars($rowbidder['website']);
                } else {
                    $bidder_name = htmlspecialchars($rowview['bidder_id']);
                }

                // Get proprietor name
                $proprietor_id = mysqli_real_escape_string($con, $rowview['proprietor_id']);
                $sqlproprietor = "SELECT name,nic_passport FROM proprietor WHERE proprietor_id='$proprietor_id'";
                $resultproprietor = mysqli_query($con, $sqlproprietor) or die("SQL error: " . mysqli_error($con));
                $proprietor_name = '';
                if (mysqli_num_rows($resultproprietor) > 0) {
                    $rowproprietor = mysqli_fetch_assoc($resultproprietor);
                    $proprietor_name = htmlspecialchars($rowproprietor['name']);
                    $proprietor_nic_passport = htmlspecialchars($rowproprietor['nic_passport']);
                } else {
                    $proprietor_name = htmlspecialchars($rowview['proprietor_id']);
                }

                // Get tender title and open date safely
                $tender_ref_no = mysqli_real_escape_string($con, $rowview['tender_ref_no']);
                $sqltender = "SELECT tender_id,tender_ref_no,bid_open_date,tender_type FROM tender WHERE tender_ref_no='$tender_ref_no'";
                $resulttender = mysqli_query($con, $sqltender) or die("SQL error: " . mysqli_error($con));
                $tender_title = '';
                $bid_open_date = null;
                $tender_id = '';
                if (mysqli_num_rows($resulttender) > 0) {
                    $rowtender = mysqli_fetch_assoc($resulttender);
                    $tender_title = htmlspecialchars($rowtender['tender_ref_no']);
                    $bid_open_date = $rowtender['bid_open_date'];
                    $tender_type = $rowtender['tender_type'];
                    $tender_id = mysqli_real_escape_string($con, $rowtender['tender_id']);
                } else {
                    $tender_title = htmlspecialchars($rowview['tender_ref_no']);
                }
                $show_open_details = ($bid_open_date && strtotime($bid_open_date) < time());

                // Get bidder bank details
                $bank_id = '';
                if (!empty($rowview['bank_id'])) {
                    $bank_id = mysqli_real_escape_string($con, $rowview['bank_id']);
                } else {
                    $sqlfallback = "SELECT bank_id FROM bids_product WHERE bid_id='$pk_bid_id' LIMIT 1";
                    $resultfallback = mysqli_query($con, $sqlfallback) or die("SQL error: " . mysqli_error($con));
                    if (mysqli_num_rows($resultfallback) > 0) {
                        $rowfallback = mysqli_fetch_assoc($resultfallback);
                        $bank_id = mysqli_real_escape_string($con, $rowfallback['bank_id']);
                    }
                }
                $name = 'N/A';
                $address = 'N/A';
                $account_no = 'N/A';
                $IBAN_no = 'N/A';
                $swift_code = 'N/A';

                if ($bank_id !== '') {
                    $sqlbidder = "SELECT * FROM bidders_bank WHERE bank_id='$bank_id'";
                    $resultbidders_bank = mysqli_query($con, $sqlbidder) or die("SQL error: " . mysqli_error($con));
                    if (mysqli_num_rows($resultbidders_bank) > 0) {
                        $rowbidder = mysqli_fetch_assoc($resultbidders_bank);
                        $name = htmlspecialchars($rowbidder['name']);
                        $address = htmlspecialchars($rowbidder['address'] ?? 'N/A');
                        $account_no = htmlspecialchars($rowbidder['account_no'] ?? 'N/A');
                        $IBAN_no = htmlspecialchars($rowbidder['IBAN_no'] ?? 'N/A');
                        $swift_code = htmlspecialchars($rowbidder['swift_code'] ?? 'N/A');
                    }
                }
            ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="banner-image" style="background: url('img/logo/form_header.png') no-repeat center center; background-size: cover; height: 180px; border-radius: 6px; margin-bottom: 20px;">
                                <div style="background: rgba(0,0,0,0.35); height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <h2 style="color: #ffffff; margin: 0; font-size: 28px; text-shadow: 0 1px 6px rgba(0,0,0,0.6);"></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="static-table-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="sparkline8-list">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <h1>DETAILS OF THE BIDDER</h1>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table">                                        
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Bid Reference Number</b></td>
                                                            <td><?php echo htmlspecialchars($rowview['tender_ref_no']); ?></td>                                                            
                                                        </tr>
                                                        <tr>
                                                            <td><b>Bid Id</b></td>
                                                            <td><?php echo htmlspecialchars($rowview['bid_id']); ?></td>
                                                        </tr>           
                                                        <tr>
                                                            <td><b>Proprietor Name</b></td>
                                                            <td><?php echo $proprietor_name; ?></td>
                                                            <td><b>Proprietor NIC/Passport</b></td>
                                                            <td><?php echo $proprietor_nic_passport; ?></td>
                                                        </tr> 
                                                        <!-- <tr>
                                                            <td><b>Proprietor Name</b></td>
                                                            <td><?php echo $proprietor_name; ?></td>
                                                        </tr> -->                                                        
                                                        <tr>
                                                            <td><b>Bidder Company</b></td>
                                                            <td><?php echo $bidder_name; ?></td>
                                                        </tr>      
                                                        <tr>
                                                            <td><b>Address</b></td>
                                                            <td><?php echo $bidder_address; ?></td>
                                                        </tr>   
                                                        <tr>
                                                            <td><b>Telephone General</b></td>
                                                            <td><?php echo $bidder_land; ?></td>
                                                            <td><b>Fax</b></td>
                                                            <td><?php echo $bidder_fax; ?></td>
                                                            
                                                        </tr>    
                                                        <tr>
                                                            <td><b>e-mail</b></td>
                                                            <td><?php echo $bidder_email; ?></td>
                                                            <td><b>Website</b></td>
                                                            <td><?php echo $bidder_website; ?></td>
                                                        </tr>  
                                                        <tr>
                                                            <td><b>Mobile</b></td>
                                                            <td><?php echo $bidder_mobile; ?></td>
                                                            <td><b>WhatsApp/WeChat of the Head of Company</b></td>
                                                            <td><?php echo $bidder_mobile; ?></td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td><b>Status</b></td>
                                                            <td><?php echo htmlspecialchars($rowview['status']); ?></td>
                                                        </tr>   
                                                        <?php
                                                        if($rowtender['bid_open_date'] < date('Y-m-d H:i:s'))
                                                        {
                                                        ?>         
                                                        <tr>
                                                            <td><b>Delivery Method</b></td>
                                                            <td><?php echo htmlspecialchars($rowview['delivery_method']); ?></td>
                                                        </tr>       
                                                        <tr>
                                                            <td><b>Delivery Place</b></td>
                                                            <td><?php echo htmlspecialchars($rowview['delivery_place']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Bid Currency</b></td>
                                                            <td><?php echo htmlspecialchars($rowview['bid_currency']); ?></td>
                                                        </tr>                                                               
                                                        <tr>
                                                            <td><b>Bid Valid Date</b></td>
                                                            <td><?php echo htmlspecialchars($rowview['bid_valide_date']); ?></td>
                                                        </tr>             
                                                        <tr>
                                                            <td><b>Submit Date</b></td>
                                                            <td><?php echo htmlspecialchars($rowview['submit_date']); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Certified Document</b></td>
                                                            <td><?php
                                                                if(!empty($rowview["certifieddocument"]))
                                                                {
                                                                    echo '<a href="' . $upload_dir . rawurlencode($rowview["certifieddocument"]) . '" target="_blank">';
                                                                    echo '<button class="btn btn-primary" type="button">View File</button>';
                                                                    echo '</a>';
                                                                }
                                                                else
                                                                {
                                                                    echo "No File";
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>     
                                                        <tr>
                                                            <td><b>Open Key</b></td>
                                                            <td><?php echo htmlspecialchars($rowview['open_key']); ?></td>
                                                        </tr> 
                                                        <?php
                                                        }
                                                        ?>           
                                                    </tbody>
                                                </table> 
                                                <a href="index.php?page=bids.php&option=view"><button class="btn btn-warning">Back</button></a>     
                                                <?php
                                                if(!isset($_GET['print']))
                                                {
                                                echo '<a href="print.php?bid_id='.$rowview['bid_id'].'" target="_blank"><button class="btn btn-primary" name="btnprint" type="button" id="btnprint">Print</button></a>';
                                                }
                                                ?>                            
                                                </div>                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        if($rowtender['bid_open_date'] < date('Y-m-d H:i:s'))
                        {
                                        ?>
                        <div class="data-table-area mg-b-15">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="sparkline13-list">
                                        <div class="sparkline13-hd">
                                            <div class="main-sparkline13-hd">
                                                <h1>Bid Product <span class="table-project-n">Data</span> Table</h1>
                                            </div>
                                        </div>
                                        <div class="sparkline13-graph">
                                            <div class="datatable-dashv1-list custom-datatable-overright">   
                                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                                    data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                                    <thead>
                                                        <tr>
                                                            <th data-field="bid_id">Bid ID</th>
                                                            <th data-field="product_id" >Product Name</th>
                                                            <!-- <th data-field="bank_id" >Bank Name</th>
                                                            <th data-field="swift_code" >SWIFT Code</th>
                                                            <th data-field="IBAN_no" >IBAN Number</th>                                                               -->
                                                            <!-- <th data-field="credit_Period_facility" >Credit Period Facility</th> -->
                                                            <th>
                                                                <?php
                                                                if($tender_type === "Procurement")
                                                                {
                                                                    echo "Required Quantity";
                                                                }
                                                                else if($tender_type === "Sales")
                                                                {
                                                                    echo "QUANTITY OF <br>PRODUCTS<br> ON SALE(MT)";
                                                                }
                                                               ?>
                                                            </th>
                                                            <th>
                                                                <?php
                                                                if($tender_type === "Procurement")
                                                                {
                                                                    echo "Applied Quantity";
                                                                }
                                                                else if($tender_type === "Sales")
                                                                {
                                                                    echo "REQUIRED <br>QUANTITY (MT)<br> (PLEASE MENTION<br> THE QTY)";
                                                                }
                                                               ?>
                                                            </th>
                                                            <th data-field="qty" >THE AMOUNT<br>OF BID <br>SECURITY <br>IN USD</th>  
                                                            <th>
                                                                <?php
                                                                if($tender_type === "Procurement")
                                                                {
                                                                    echo "Unit Price";
                                                                }
                                                                else if($tender_type === "Sales")
                                                                {
                                                                    echo "BID PRICE <br>PER MT<br> IN USD";
                                                                }
                                                               ?>
                                                            </th>
                                                            <th data-field="line_total" >Line Total</th>
                                                        </tr>
                                                    </thead>
                                                    
                                                    <tbody>
                                                        <?php
                                                        $sqlview="SELECT * From bids_product WHERE bid_id='$pk_bid_id'";
                                                        $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                                        while($rowview=mysqli_fetch_assoc($resultview))
                                                            {
                                                                $sqlproductname="SELECT name FROM product WHERE product_id ='$rowview[product_id]'";
                                                                $resultproductname=mysqli_query($con,$sqlproductname) or die("SQL view error".mysqli_error($con));
                                                                $rowproductname=mysqli_fetch_assoc($resultproductname);

                                                                $tender_product_tender_id = $tender_id;
                                                                $tender_product_product_id = mysqli_real_escape_string($con, $rowview['product_id']);
                                                                $available_qty_display = 'N/A';
                                                                $bid_security_usd = 'N/A';
                                                                if ($tender_product_tender_id !== '') {
                                                                    $sqltender_product_avaliable_qty = "SELECT avaliable_qty, bid_security_usd FROM tender_product WHERE tender_id='$tender_product_tender_id' AND product_id='$tender_product_product_id'";
                                                                    $resulttender_product_avaliable_qty = mysqli_query($con, $sqltender_product_avaliable_qty) or die("SQL view error".mysqli_error($con));
                                                                    if ($rowtender_product_avaliable_qty = mysqli_fetch_assoc($resulttender_product_avaliable_qty)) {
                                                                        $available_qty_display = htmlspecialchars($rowtender_product_avaliable_qty['avaliable_qty']);
                                                                        $bid_security_usd = htmlspecialchars($rowtender_product_avaliable_qty['bid_security_usd']);
                                                                    }
                                                                }

                                                                $sqlbidders_bankname="SELECT name FROM bidders_bank WHERE bank_id='$rowview[bank_id]'";
                                                                $resultbidders_bankname=mysqli_query($con,$sqlbidders_bankname) or die("SQL view error".mysqli_error($con));
                                                                $rowbidders_bankname=mysqli_fetch_assoc($resultbidders_bankname);
                                                                echo'<tr>';
                                                                    echo'<td>'.htmlspecialchars($rowview["bid_id"]).'</td>';
                                                                    echo'<td>'.htmlspecialchars($rowproductname["name"]).'</td>';
                                                                    // echo'<td>'.htmlspecialchars($rowbidders_bankname["name"]).'</td>';
                                                                    // echo'<td>'.htmlspecialchars($rowview["swift_code"]).'</td>';
                                                                    // echo'<td>'.htmlspecialchars($rowview["IBAN_no"]).'</td>';
                                                                    // echo'<td>'.htmlspecialchars($rowview["credit_Period_facility"]).'</td>';
                                                                    echo'<td>'.$available_qty_display.'</td>';
                                                                    
                                                                    echo'<td>'.htmlspecialchars($rowview["qty"]).'</td>';
                                                                    echo'<td>'.$bid_security_usd.'</td>';
                                                                    echo'<td>'.htmlspecialchars($rowview["unit_price"]).'</td>';
                                                                    echo'<td>'.htmlspecialchars($rowview["line_total"]).'</td>';
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

                            
                        <div class="static-table-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="sparkline8-list">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <h1>FINANCIAL INFORMATIONS</h1>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table">                                        
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Name of the Account Holder</b></td>
                                                            <td><?php echo $bidder_name; ?></td>
                                                        </tr>            
                                                        <tr>
                                                            <td><b>Name of the Bank</b></td>
                                                            <td><?php echo $name; ?></td>
                                                        </tr>                                                                      
                                                        <tr>
                                                            <td><b>Address of Bank</b></td>
                                                            <td><?php echo $address; ?></td>
                                                        </tr>   
                                                        <?php
                                                        if($show_open_details)
                                                        {
                                                        ?>         
                                                        <tr>
                                                            <td><b>Bank Account No</b></td>
                                                            <td><?php echo $account_no; ?></td>
                                                        </tr>       
                                                        <tr>
                                                            <td><b>IBAN No</b></td>
                                                            <td><?php echo $IBAN_no; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Swift Code</b></td>
                                                            <td><?php echo $swift_code; ?></td>
                                                        </tr>
                                                        <?php
                                                        }
                                                        ?>           
                                                    </tbody>
                                                </table> 
                                                                            
                                                </div>                                    
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
                    ?> 
            </div>
            <br>
            <?php   
            }
            else if($_GET["option"]=="edit")
            {
                
            }
            else if($_GET["option"]=="delete")
            {
                
            }

       }
       ?>
</body>