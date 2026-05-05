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

function generateTenderRefNo($con, $type)
{
    $yearFull = date("Y");
    $yearShort = date("y");

    if ($type == "Sales")
    {
        $sql = "SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(tender_ref_no, '/', -1) AS UNSIGNED)), 0) AS last_num
                FROM tender
                WHERE tender_type='Sales'
                AND YEAR(publish_date)='$yearFull'";

        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($res);
        $num = (int)$row['last_num'] + 1;

        return "LMS/MKT/TDR/$yearShort/" . str_pad($num,2,"0",STR_PAD_LEFT);
    }

    if ($type == "Procurement")
    {
        $sql = "SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(tender_ref_no, '/', 4), '/', -1) AS UNSIGNED)), 0) AS last_num
                FROM tender
                WHERE tender_type='Procurement'
                AND YEAR(publish_date)='$yearFull'";

        $res = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($res);
        $num = (int)$row['last_num'] + 1;

        return "LMSL/SUP/DPC/" . str_pad($num,2,"0",STR_PAD_LEFT) . "/$yearFull";
    }

    return "";
}

if (isset($_GET['action']) && $_GET['action'] === 'next_ref_no')
{
    $type = isset($_GET['type']) ? $_GET['type'] : 'Procurement';
    $refNo = generateTenderRefNo($con, $type);

    header('Content-Type: application/json');
    echo json_encode(array('tender_ref_no' => $refNo));
    exit;
}

if(isset($_POST["btnsave"]))
    {
    $type = $_POST["txttender_type"];

    $tender_id = $_POST["txttender_id"];
    $tender_ref_no = generateTenderRefNo($con, $type);

    $sqlinsert="INSERT INTO tender (tender_id,tender_ref_no,title,tender_type,currency,
        publish_date,bid_open_date,bid_close_date,bid_validity,status,create_by)
        VALUES(
        '".mysqli_real_escape_string($con,$_POST["txttender_id"])."',
        '$tender_ref_no',
        '".mysqli_real_escape_string($con,$_POST["txttitle"])."',
        '$type',
        '".mysqli_real_escape_string($con,$_POST["txtcurrency"])."',
        '".mysqli_real_escape_string($con,$_POST["txtpublish_date"])."',
        '".mysqli_real_escape_string($con,$_POST["txtbid_open_date"])."',
        '".mysqli_real_escape_string($con,$_POST["txtbid_close_date"])."',
        '".mysqli_real_escape_string($con,$_POST["txtbid_validity"])."',
        '".mysqli_real_escape_string($con,"Pending")."',
        '".mysqli_real_escape_string($con,$system_user_id)."')";

    $insertresult=mysqli_query($con,$sqlinsert) or die(mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=tender_product.php&option=add&tender_id=' . $_POST["txttender_id"] . '" </script>';
            }
    }

// Handle Update
if(isset($_POST["btnupdate"]))
{
    $tender_id = mysqli_real_escape_string($con, $_POST["txttender_id"]);

    $sqlupdate="UPDATE tender SET
        title='".mysqli_real_escape_string($con,$_POST["txttitle"])."',
        currency='".mysqli_real_escape_string($con,$_POST["txtcurrency"])."',
        publish_date='".mysqli_real_escape_string($con,$_POST["txtpublish_date"])."',
        bid_open_date='".mysqli_real_escape_string($con,$_POST["txtbid_open_date"])."',
        bid_close_date='".mysqli_real_escape_string($con,$_POST["txtbid_close_date"])."',
        bid_validity='".mysqli_real_escape_string($con,$_POST["txtbid_validity"])."'
    WHERE tender_id='$tender_id'";

    $updateresult=mysqli_query($con,$sqlupdate) or die(mysqli_error($con));
    if($updateresult)
    {
        echo'<script> alert(" Record updated successfully."); window.location.href="index.php?page=tender.php&option=fullview&pk_tender_id=' . $_POST["txttender_id"] . '" </script>';
    }
}
// Insert code end
?>
<script>
function enable_bid_close_date()
{
    var publish_date=document.getElementById("txtpublish_date").value;
    document.getElementById("txtbid_close_date").value="";
    document.getElementById("txtbid_open_date").vale ="";
    if(publish_date!="")
        {
            document.getElementById("txtbid_close_date").removeAttribute("readonly");
            document.getElementById("txtbid_close_date").min=publish_date;

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var response_value = xmlhttp.responseText.trim();
                    var dates = response_value.split("&&&&");
                    document.getElementById("txtbid_close_date").max = dates[1];
                    document.getElementById("txtbid_close_date").min = dates[0];
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=tender_enable_close_date&ajax_publish_date=" + publish_date, true);
            xmlhttp.send();

        }
     else
        {
            document.getElementById("txtbid_close_date").setAttribute("readonly", true);
        }
}
</script>

<script>
function validateBidCloseDate()
{
    var bid_close_date=document.getElementById("txtbid_close_date").value;
    if(bid_close_date!="")
    {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() 
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
            {
                var response_value = xmlhttp.responseText.trim();
                document.getElementById("txtbid_open_date").value = response_value;
            }
        };
        xmlhttp.open("GET", "ajaxpage.php?frompage=tender_enable_open_date&ajax_bid_close_date=" + bid_close_date, true);
        xmlhttp.send();
    }
}
</script>
<body>
    <?php
    if(isset($_GET["option"])) 
       {
        if($_GET["option"]=="add")
            {
                $get_type=$_GET["type"];
                ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Tender Add Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Tender Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $sql_generatedid="SELECT tender_id FROM tender ORDER BY tender_id DESC LIMIT 1";
                                                                
                                                                $result_generatedid=mysqli_query($con,$sql_generatedid) or die("SQL error in sql_generateid".mysqli_error($con));
                                                                if(mysqli_num_rows($result_generatedid)==1)
                                                                    {//for other than first time
                                                                    $row_generatedid=mysqli_fetch_assoc($result_generatedid);
                                                                    $generatedid=++$row_generatedid["tender_id"];
                                                                    }
                                                                else
                                                                    { //for first time
                                                                    $generatedid="T000000001";
                                                                    }
                                                                ?>
                                                                <input type="text" name="txttender_id" id="txttender_id" class="form-control" value="<?php echo $generatedid; ?>" readonly required />
                                                                
                                                            </div>
                                                            <!-- One Column End-->                                                           
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Tender Type </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <div class="bt-df-checkbox pull-left">				
                                                                    <div class="row">
                                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                            <div class="i-checks pull-left">
                                                                                <label><input type="radio" name="txttender_type" id="txttender_type" value="<?php echo $get_type; ?>" required checked> <?php echo $get_type; ?> </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                            <div class="i-checks pull-left">
                                                                                <label>
                                                                                <!--<input type="radio" name="txttender_type" id="txttender_type" value="Procurement" checked> Procurement </label>-->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- One Row End--> 
                                                    <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                             <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Tender Reference Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txttender_ref_no" id="txttender_ref_no" class="form-control" readonly />
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
                                                                <label class="login2 pull-right pull-right-pro">Title</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txttitle" id="txttitle" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->                                                            
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Currency</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select class="form-control custom-select-value" name="txtcurrency" id="txtcurrency" required >
                                                                    <option>-- Select --</option>
                                                                    <option>LKR</option>
                                                                    <option>$</option>
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
                                                                <label class="login2 pull-right pull-right-pro">Publish Date</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $current_date = date("Y-m-d");
                                                                $max_date = date("Y-m-d", strtotime("+7 days"));
                                                                ?>
                                                                <input type="date" min="<?php echo $current_date; ?>" max="<?php echo $max_date; ?>" name="txtpublish_date" id="txtpublish_date" onblur="enable_bid_close_date()" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                             <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Close Date & Time</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="datetime-local" name="txtbid_close_date"  id="txtbid_close_date" class="form-control" readonly onblur="validateBidCloseDate()" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Bid Open Date & Time</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="datetime-local" name="txtbid_open_date" id="txtbid_open_date" class="form-control" readonly required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Valid Days</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbid_validity" id="txtbid_validity" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->                                                                 
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=tender.php&option=view&type=<?php echo $get_type; ?>"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                $get_type=$_GET["type"];
               ?>
            <div class="data-table-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline13-list">
                            <div class="sparkline13-hd">
                                <div class="main-sparkline13-hd">
                                    <h1>Tender <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=tender.php&option=add&type=<?php echo $get_type; ?>"><button class="btn btn-primary">Add Tender</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="tender_id">Tender ID</th>
                                                <th data-field="tender_ref_no">Tender Reference Number</th>
                                                <th data-field="title" >Title</th>
                                                <th data-field="publish_date" >Publish Date</th>
                                                <th data-field="bid_close_date" >Bid Close Date</th>
                                                <th data-field="status">Status</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT tender_id,tender_ref_no,title,publish_date,bid_close_date,status From tender WHERE tender_type='$get_type'";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["tender_id"].'</td>';
                                                    echo'<td>'.$rowview["tender_ref_no"].'</td>';
                                                    echo'<td>'.$rowview["title"].'</td>';
                                                    echo'<td>'.$rowview["publish_date"].'</td>';
                                                    echo'<td>'.$rowview["bid_close_date"].'</td>';
                                                    echo'<td>'.$rowview["status"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=tender.php&option=fullview&pk_tender_id='.$rowview["tender_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    if($rowview["status"]=="Pending")
                                                        {
                                                    echo'<a href="index.php?page=tender.php&option=edit&pk_tender_id='.$rowview["tender_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=tender.php&option=delete&pk_tender_id='.$rowview["tender_id"].'"><button class="btn btn-danger">Delete</button></a> ';
                                                        }
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
                // Get and sanitize the tender_id parameter
                $pk_tender_id = isset($_GET["pk_tender_id"]) ? mysqli_real_escape_string($con, $_GET["pk_tender_id"]) : '';
                if (empty($pk_tender_id)) {
                    echo '<p class="alert alert-danger">Invalid Tender ID</p>';
                    exit;
                }

                // Fetch tender details from database
                $sqlview = "SELECT * FROM tender WHERE tender_id='$pk_tender_id'";
                $resultview = mysqli_query($con, $sqlview) or die("SQL error: " . mysqli_error($con));
                if (mysqli_num_rows($resultview) == 0) {
                    echo '<p class="alert alert-danger">Tender not found</p>';
                    exit;
                }
                
                $rowview = mysqli_fetch_assoc($resultview);

                // Get staff name for Create By field
                $create_by_id = mysqli_real_escape_string($con, $rowview['create_by']);
                $sqlstaff = "SELECT name FROM staff WHERE staff_id='$create_by_id'";
                $resultstaff = mysqli_query($con, $sqlstaff) or die("SQL error: " . mysqli_error($con));
                
                $staff_name = '';
                if (mysqli_num_rows($resultstaff) > 0) {
                    $rowstaff = mysqli_fetch_assoc($resultstaff);
                    $staff_name = htmlspecialchars($rowstaff['name']);
                } else {
                    $staff_name = htmlspecialchars($rowview['create_by']); // fallback to ID if not found
                }

                $sql_tender_type = "SELECT tender_type,status FROM tender WHERE tender_id='$pk_tender_id'";
                $result_tender_type = mysqli_query($con, $sql_tender_type) or die("SQL error: " . mysqli_error($con));
                $row_tender_type = mysqli_fetch_assoc($result_tender_type); 
            ?>
                    <div class="static-table-area">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="sparkline8-list">
                                    <div class="sparkline8-hd">
                                        <div class="main-sparkline8-hd">
                                            <h1>Tender Table</h1>
                                        </div>
                                    </div>
                                    <div class="sparkline8-graph">
                                        <div class="static-table-list">
                                            <table class="table">                                        
                                                <tbody>
                                                    <tr>
                                                        <td><b>Tender Id</b></td>
                                                        <td><?php echo htmlspecialchars($rowview['tender_id']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Tender Type</b></td>
                                                        <td><?php echo htmlspecialchars($rowview['tender_type']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Tender Reference Number</b></td>
                                                        <td><?php echo htmlspecialchars($rowview['tender_ref_no']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Title</b></td>
                                                        <td><?php echo htmlspecialchars($rowview['title']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Currency</b></td>
                                                        <td><?php echo htmlspecialchars($rowview['currency']); ?></td>
                                                    </tr>                                                                          
                                                    <tr>
                                                        <td><b>Publish Date</b></td>
                                                        <td><?php echo htmlspecialchars($rowview['publish_date']); ?></td>
                                                    </tr> 
                                                    <tr>
                                                        <td><b>Bid Close Date & Time</b></td>
                                                        <td><?php echo htmlspecialchars(str_replace(' ', 'T', $rowview['bid_close_date'])); ?></td>
                                                    </tr>             
                                                    <tr>
                                                        <td><b>Bid Open Date & Time</b></td>
                                                        <td><?php echo htmlspecialchars(str_replace(' ', 'T', $rowview['bid_open_date'])); ?></td>
                                                    </tr>           
                                                    
                                                    <tr>
                                                        <td><b>Bid Valid Days</b></td>
                                                        <td><?php echo htmlspecialchars($rowview['bid_validity']); ?></td>
                                                    </tr>                                                                  
                                                    <tr>
                                                        <td><b>Status</b></td>
                                                        <td><?php echo htmlspecialchars($rowview['status']); ?></td>
                                                    </tr>                 
                                                    <tr>
                                                        <td><b>Create By</b></td>
                                                        <td><?php echo htmlspecialchars($staff_name); ?></td>
                                                    </tr>
                                                </tbody>                                                
                                            </table>
                                            <a href="index.php?page=tender.php&option=view&type=<?php echo $rowview['tender_type']; ?>"><button class="btn btn-warning">Back</button></a>     
                                            <?php
                                            if(!isset($_GET['print']))
                                            {
                                            echo '<a href="print.php?tender_id='.$rowview["tender_id"].'" target="_blank">
                                            <button class="btn btn-primary" name="btnprint" type="button" id="btnprint">Print</button>
                                            </a>';
                                            }
                                            if($rowview["status"] == "Pending") {
                                            echo '<a href="index.php?page=tender.php&option=publish&tender_id='.$rowview["tender_id"].'">
                                            <button class="btn btn-success">Publish</button>
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
                
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline13-list">
                            <div class="sparkline13-hd">
                                <div class="main-sparkline13-hd">
                                    <h1>Tender Product <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">  
                                    <?php
                                    if($rowview["status"]=="Pending")
                                        {
                                    ?>
                                    <a href="index.php?page=tender_product.php&option=add&tender_id=<?php echo $rowview['tender_id']; ?>"><button class="btn btn-primary">Add Tender Product</button></a>     
                                    <?php
                                        }
                                    ?>                            
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="tender_id ">Tender ID</th>
                                                <th data-field="product_id" >Product ID</th>
                                                <th data-field="avaliable_qty" >Available Quantity</th>
                                                <?php
                                                if($row_tender_type["tender_type"]=="Sales")
                                                    {
                                                ?>
                                                <th data-field="min_qty" >Minimum Quantity</th>
                                                <?php
                                                    }
                                                ?>
                                                <th data-field="bid_security_usd">Bid Security</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT tender_id ,product_id,avaliable_qty,min_qty,bid_security_usd From tender_product WHERE tender_id='$rowview[tender_id]'";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    $sqlproductname="SELECT name FROM product WHERE product_id ='$rowview[product_id]'";
                                                    $resultproductname=mysqli_query($con,$sqlproductname) or die("SQL view error".mysqli_error($con));
                                                    $rowproductname=mysqli_fetch_assoc($resultproductname);
                                                    
                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["tender_id"].'</td>';
                                                    echo'<td>'.$rowproductname["name"].'</td>';
                                                    echo'<td>'.$rowview["avaliable_qty"].'</td>';
                                                    if($row_tender_type["tender_type"]=="Sales")
                                                    {
                                                    echo'<td>'.$rowview["min_qty"].'</td>';
                                                    }
                                                    echo'<td>'.$rowview["bid_security_usd"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=tender_product.php&option=fullview&pk_tender_id='.$rowview["tender_id"].'&pk_product_id='.$rowview["product_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    if($row_tender_type["status"]=="Pending")
                                                        {
                                                    echo'<a href="index.php?page=tender_product.php&option=edit&pk_tender_id='.$rowview["tender_id"].'&pk_product_id='.$rowview["product_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=tender_product.php&option=delete&pk_tender_id='.$rowview["tender_id"].'&pk_product_id='.$rowview["product_id"].'"><button class="btn btn-danger">Delete</button></a> ';
                                                        }
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
            <br>                
            <?php   
            }
            else if($_GET["option"]=="edit")
            {
                // Get and sanitize the tender_id parameter
                $pk_tender_id = isset($_GET["pk_tender_id"]) ? mysqli_real_escape_string($con, $_GET["pk_tender_id"]) : '';

                if (empty($pk_tender_id)) {
                    echo '<p class="alert alert-danger">Invalid Tender ID</p>';
                    exit;
                }

                // Fetch tender details from database
                $sqledit = "SELECT * FROM tender WHERE tender_id='$pk_tender_id'";
                $resultedit = mysqli_query($con, $sqledit) or die("SQL error: " . mysqli_error($con));
                
                if (mysqli_num_rows($resultedit) == 0) {
                    echo '<p class="alert alert-danger">Tender not found</p>';
                    exit;
                }
                
                $rowedit = mysqli_fetch_assoc($resultedit);

                // Get staff name for Create By field
                $create_by_id = mysqli_real_escape_string($con, $rowedit['create_by']);
                $sqlstaff_edit = "SELECT name FROM staff WHERE staff_id='$create_by_id'";
                $resultstaff_edit = mysqli_query($con, $sqlstaff_edit) or die("SQL error: " . mysqli_error($con));
                
                $staff_name_edit = '';
                if (mysqli_num_rows($resultstaff_edit) > 0) {
                    $rowstaff_edit = mysqli_fetch_assoc($resultstaff_edit);
                    $staff_name_edit = htmlspecialchars($rowstaff_edit['name']);
                } else {
                    $staff_name_edit = htmlspecialchars($rowedit['create_by']);
                }
            ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Edit Tender Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Tender Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txttender_id" id="txttender_id" class="form-control" value="<?php echo htmlspecialchars($rowedit['tender_id']); ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End-->                                                           
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Tender Type </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($rowedit['tender_type']); ?>" readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- One Row End--> 
                                                    <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                             <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Tender Reference Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txttender_ref_no" id="txttender_ref_no" class="form-control" value="<?php echo htmlspecialchars($rowedit['tender_ref_no']); ?>" readonly />
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
                                                                <label class="login2 pull-right pull-right-pro">Title</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txttitle" id="txttitle" class="form-control" value="<?php echo htmlspecialchars($rowedit['title']); ?>" required />
                                                            </div>
                                                            <!-- One Column End-->                                                            
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Currency</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select class="form-control custom-select-value" name="txtcurrency" id="txtcurrency" required readonly>
                                                                    <option value="<?php echo htmlspecialchars($rowedit['currency']); ?>"><?php echo htmlspecialchars($rowedit['currency']); ?></option>
                                                                    
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
                                                                <label class="login2 pull-right pull-right-pro">Publish Date</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $current_date = date("Y-m-d");
                                                                $max_date = date("Y-m-d", strtotime("+7 days"));
                                                                ?>
                                                                <input type="date" min="<?php echo $current_date; ?>" max="<?php echo $max_date; ?>" name="txtpublish_date" id="txtpublish_date" onblur="enable_bid_close_date()"  class="form-control" value="<?php echo htmlspecialchars($rowedit['publish_date']); ?>" required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Close Date & Time</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="datetime-local" name="txtbid_close_date" id="txtbid_close_date" class="form-control" value="<?php echo htmlspecialchars(str_replace(' ', 'T', $rowedit['bid_close_date'])); ?>" onblur="validateBidCloseDate()" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Bid Open Date & Time</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="datetime-local" name="txtbid_open_date" id="txtbid_open_date" class="form-control" value="<?php echo htmlspecialchars(str_replace(' ', 'T', $rowedit['bid_open_date'])); ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Valid Days</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbid_validity" id="txtbid_validity" class="form-control" value="<?php echo htmlspecialchars($rowedit['bid_validity']); ?>" required />
                                                            </div>
                                                            <!-- One Column End-->                                                                 
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                               

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=tender.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
                                                                <input type="reset" name="btnclear" id="btnclear" class="btn btn-danger" value="Clear" />
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
            $tenderid=$_GET["pk_tender_id"];
            $sqldelete="UPDATE tender SET status='Deleted' WHERE tender_id='$tenderid'";
            $resultdelete=mysqli_query($con,$sqldelete) or die(mysqli_error($con));

            $sql_tender_type = "SELECT tender_type FROM tender WHERE tender_id='$tenderid'";
            $result_tender_type = mysqli_query($con, $sql_tender_type) or die("SQL error: " . mysqli_error($con));
            $row_tender_type = mysqli_fetch_assoc($result_tender_type);

            if($resultdelete)
            {
                echo'<script>alert("Record Deleted Successfully");window.location.href="index.php?page=tender.php&option=view&type=' . $row_tender_type["tender_type"] . '";</script>';
            }
            }
            else if($_GET["option"]=="publish")
            {
                $tenderid=$_GET["tender_id"];
                $sqlpublish="UPDATE tender SET status='Active' WHERE tender_id='$tenderid'";
                $resultpublish=mysqli_query($con,$sqlpublish) or die(mysqli_error($con));

                if($resultpublish)
                {
                    echo'<script>alert("Tender Published Successfully");window.location.href="index.php?page=tender.php&option=fullview&pk_tender_id=' . $tenderid . '";</script>';
                }
            }
       }
       ?>
</body>
<script>
function loadNextTenderRefNo(type) {
    const endpoint = "tender.php?action=next_ref_no&type=" + encodeURIComponent(type);

    fetch(endpoint)
        .then(response => {
            const contentType = response.headers.get("content-type") || "";
            if (!contentType.includes("application/json")) {
                throw new Error("Non-JSON response");
            }
            return response.json();
        })
        .then(data => {
            document.getElementById("txttender_ref_no").value = data.tender_ref_no || "";
        })
        .catch(() => {
            const yearFull = new Date().getFullYear();
            const yearShort = yearFull.toString().slice(-2);
            document.getElementById("txttender_ref_no").value = type === "Sales"
                ? "LMS/MKT/TDR/" + yearShort + "/XX"
                : "LMSL/SUP/DPC/XX/" + yearFull;
        });
}

document.querySelectorAll("input[name='txttender_type']").forEach(el => {
    el.addEventListener("change", function(){
        loadNextTenderRefNo(this.value);
    });
});

const selectedTenderType = document.querySelector("input[name='txttender_type']:checked");
if (selectedTenderType) {
    loadNextTenderRefNo(selectedTenderType.value);
}
</script>