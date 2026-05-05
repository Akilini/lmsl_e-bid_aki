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

$upload_dir = "bids_securities/bid_submission_form/";
$upload_dir_fs = rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $upload_dir);

//Insert code start
if(isset($_POST["btnsave"]))
    {
        $bid_id = $_POST["txtbid_id"];
        $file_bid_submission_form = "";
        if(isset($_FILES["txtbid_submission_form"]) && $_FILES["txtbid_submission_form"]["error"] == 0)
        {
            $original_file = basename($_FILES["txtbid_submission_form"]["name"]);
            $tmp_name = $_FILES["txtbid_submission_form"]["tmp_name"];

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

            $file_bid_submission_form = $bid_id . "." . $ext;

            // If already exists, delete (safety)
            if(file_exists($upload_dir_fs.$file_bid_submission_form))
            {
                unlink($upload_dir_fs.$file_bid_submission_form);
            }

            // Move file
            if(!move_uploaded_file($tmp_name, $upload_dir_fs.$file_bid_submission_form))
            {
                echo "<script>alert('File upload failed');</script>";
                exit();
            }
        }

        $sqlinsert="INSERT INTO bids_securities (bid_id,security_type,amount_usd,valid_from,valid_date,bid_submission_form,status,verify_date)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtbid_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtsecurity_type"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtamount_usd"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtvalid_from"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtvalid_date"])."',
                        '$file_bid_submission_form',
                        '".mysqli_real_escape_string($con,$_POST["txtstatus"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtverify_date"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=bids_securities.php&option=add" </script>';
            }
    }
// Insert code end

//Update code start
if(isset($_POST["btnupdate"]))
{
    $bid_id = mysqli_real_escape_string($con, $_POST["txtbid_id"]);
    $allowed = array("pdf","jpg","jpeg","png");

    // Step 1: Get existing file
    $sqlget = "SELECT bid_submission_form FROM bids_securities WHERE bid_id='$bid_id'";
    $resultget = mysqli_query($con, $sqlget);
    $rowget = mysqli_fetch_assoc($resultget);
    $old_file = $rowget["bid_submission_form"];

    // Start update query
    $sqlupdate="UPDATE bids_securities SET
    security_type='".mysqli_real_escape_string($con,$_POST["txtsecurity_type"])."',
    amount_usd='".mysqli_real_escape_string($con,$_POST["txtamount_usd"])."',
    valid_from='".mysqli_real_escape_string($con,$_POST["txtvalid_from"])."',
    valid_date='".mysqli_real_escape_string($con,$_POST["txtvalid_date"])."',
    status='".mysqli_real_escape_string($con,$_POST["txtstatus"])."',
    verify_date='".mysqli_real_escape_string($con,$_POST["txtverify_date"])."'";

    // Step 2: Check new file upload
    if(isset($_FILES["txtbid_submission_form"]) && $_FILES["txtbid_submission_form"]["name"] != "")
    {
        $original_file = $_FILES["txtbid_submission_form"]["name"];
        $tmp_name = $_FILES["txtbid_submission_form"]["tmp_name"];

        $ext = strtolower(pathinfo($original_file, PATHINFO_EXTENSION));
       
        if(!in_array($ext,$allowed))
        {
            echo "<script>alert('Invalid file type');</script>";
            exit();
        }

        if(!is_dir($upload_dir_fs))
        {
            mkdir($upload_dir_fs, 0777, true);
        }

        // ✅ SAME NAME (overwrite)
        $new_file = $bid_id . "." . $ext;

        // Delete old file if different extension
        if(!empty($old_file) && file_exists($upload_dir_fs.$old_file))
        {
            unlink($upload_dir_fs.$old_file);
        }

        // Step 3: Upload new file
        if(move_uploaded_file($tmp_name, $upload_dir_fs.$new_file))
        {
            $sqlupdate .= ", bid_submission_form='$new_file'";
        }
        else
        {
            echo "<script>alert('File upload failed');</script>";
            exit();
        }
    }

        $sqlupdate .= " WHERE bid_id='$bid_id'";

        $resultupdate=mysqli_query($con,$sqlupdate) or die("SQL user update error: " .mysqli_error($con));

        if($resultupdate)
        {
            echo '<script>alert("Record Updated Successfully");window.location.href="index.php?page=bids_securities.php&option=view";</script>';
        }
}
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
                                    <h1>Bids Securities Add Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Bid Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtbid_id" id="txtbid_id" class="form-control" required>
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT bid_id FROM bids";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["bid_id"].'">'.$row_load["bid_id"].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Security Type</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtsecurity_type" id="txtsecurity_type" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Amount (USD)</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtamount_usd" id="txtamount_usd" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Valid From</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="date" name="txtvalid_from" id="txtvalid_from" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Valid Date</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="date" name="txtvalid_date" id="txtvalid_date" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                             <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Submission Form</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                                                              <input type="file" name="txtbid_submission_form" id="txtbid_submission_form" class="form-control" placeholder="no bid submission form selected">
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
                                                                <label class="login2 pull-right pull-right-pro">Status</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtstatus" id="txtstatus" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Verify Date</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="date" name="txtverify_date" id="txtverify_date" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=bids_securities.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Bids Securities <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=bids_securities.php&option=add"><button class="btn btn-primary">Add Bids Securities</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="bid_id">BID ID</th>
                                                <th data-field="security_type" >Security Type</th>
                                                <th data-field="amount_usd" >Amount</th>
                                                <th data-field="valid_from" >Valid From</th>
                                                <th data-field="valid_date">Valid Date</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT bid_id,security_type,amount_usd,valid_from,valid_date From bids_securities";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["bid_id"].'</td>';
                                                    echo'<td>'.$rowview["security_type"].'</td>';
                                                    echo'<td>'.$rowview["amount_usd"].'</td>';
                                                    echo'<td>'.$rowview["valid_from"].'</td>';
                                                    echo'<td>'.$rowview["valid_date"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=bids_securities.php&option=fullview&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    echo'<a href="index.php?page=bids_securities.php&option=edit&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=bids_securities.php&option=delete&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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

                // Fetch bid securities details from database
                $sqlview = "SELECT * FROM bids_securities WHERE bid_id='$pk_bid_id'";
                $resultview = mysqli_query($con, $sqlview) or die("SQL error: " . mysqli_error($con));

                if (mysqli_num_rows($resultview) == 0) {
                    echo '<p class="alert alert-danger">Bid Securities record not found</p>';
                    exit;
                }

                $rowview = mysqli_fetch_assoc($resultview);
            ?>
                    <div class="static-table-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="sparkline8-list">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <h1>Bids Securities Details</h1>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Bid ID</b></td>
                                                            <td><?php echo htmlspecialchars($rowview["bid_id"]); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Security Type</b></td>
                                                            <td><?php echo htmlspecialchars($rowview["security_type"]); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Amount (USD)</b></td>
                                                            <td><?php echo htmlspecialchars($rowview["amount_usd"]); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Valid From</b></td>
                                                            <td><?php echo htmlspecialchars($rowview["valid_from"]); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Valid Date</b></td>
                                                            <td><?php echo htmlspecialchars($rowview["valid_date"]); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Bid Submission Form</b></td>
                                                            <td>
                                                                <?php
                                                                if (!empty($rowview["bid_submission_form"]) && file_exists($upload_dir . $rowview["bid_submission_form"])) {
                                                                    echo '<a href="' . $upload_dir . $rowview["bid_submission_form"] . '" target="_blank" class="btn btn-sm btn-primary">View File</a>';
                                                                } else {
                                                                    echo 'No file uploaded';
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Status</b></td>
                                                            <td><?php echo htmlspecialchars($rowview["status"]); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Verify Date</b></td>
                                                            <td><?php echo htmlspecialchars($rowview["verify_date"]); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <a href="index.php?page=bids_securities.php&option=view">
                                                <button class="btn btn-warning">Back</button>
                                                </a>
                                                <?php
                                                if(!isset($_GET['print']))
                                                {
                                                echo '<a href="print.php?bid_id='.$rowview['bid_id'].'" target="_blank">
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
                $pk_bid_id = isset($_GET["pk_bid_id"]) ? mysqli_real_escape_string($con, $_GET["pk_bid_id"]) : '';

                if (empty($pk_bid_id)) {
                    echo '<p class="alert alert-danger">Invalid Bid ID</p>';
                    exit;
                }

                $sqledit = "SELECT * FROM bids_securities WHERE bid_id='$pk_bid_id'";
                $resultedit = mysqli_query($con, $sqledit) or die("SQL error: " . mysqli_error($con));

                if (mysqli_num_rows($resultedit) == 0) {
                    echo '<p class="alert alert-danger">Bid Securities record not found</p>';
                    exit;
                }

                $rowedit = mysqli_fetch_assoc($resultedit);
            ?>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Bids Securities Edit Form</h1>
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
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbid_id" id="txtbid_id" class="form-control" value="<?php echo htmlspecialchars($rowedit['bid_id']); ?>" readonly required />
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Security Type</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtsecurity_type" id="txtsecurity_type" class="form-control" value="<?php echo htmlspecialchars($rowedit['security_type']); ?>" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Amount (USD)</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtamount_usd" id="txtamount_usd" class="form-control" value="<?php echo htmlspecialchars($rowedit['amount_usd']); ?>" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Valid From</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="date" name="txtvalid_from" id="txtvalid_from" class="form-control" value="<?php echo htmlspecialchars($rowedit['valid_from']); ?>" required />
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Valid Date</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="date" name="txtvalid_date" id="txtvalid_date" class="form-control" value="<?php echo htmlspecialchars($rowedit['valid_date']); ?>" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Submission Form</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="file" name="txtbid_submission_form" id="txtbid_submission_form" class="form-control" />
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <?php if(!empty($rowedit['bid_submission_form'])): ?>
                                                                    <a href="<?php echo $upload_dir . htmlspecialchars($rowedit['bid_submission_form']); ?>" target="_blank" class="btn btn-sm btn-primary">Current File</a>
                                                                <?php else: ?>
                                                                    <span class="help-block">No file uploaded</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Status</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtstatus" id="txtstatus" class="form-control" value="<?php echo htmlspecialchars($rowedit['status']); ?>" required />
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Verify Date</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="date" name="txtverify_date" id="txtverify_date" class="form-control" value="<?php echo htmlspecialchars($rowedit['verify_date']); ?>" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=bids_securities.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a>
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
                $bid_id=$_GET["pk_bid_id"];
                $sqldelete="DELETE FROM bids_securities WHERE bid_id='$bid_id'";
                $resultdelete=mysqli_query($con,$sqldelete) or die(mysqli_error($con));

                if($resultdelete)
                {
                    echo'<script>alert("Record Deleted Successfully");window.location.href="index.php?page=bids_securities.php&option=view";</script>';
                }    
            }
       }
       ?>
</body>