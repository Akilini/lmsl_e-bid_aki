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
function generateTenderID($con)
{
    $sql = "SELECT tender_id FROM tender ORDER BY tender_id DESC LIMIT 1";
    $res = mysqli_query($con, $sql);

    if(mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_assoc($res);
        $last_id = $row["tender_id"];

        // Extract number (remove T)
        $num = (int)substr($last_id, 1);
        $num++;

        return "T" . str_pad($num, 9, "0", STR_PAD_LEFT);
    }
    else
    {
        return "T000000001";
    }
}
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

    $tender_id = generateTenderID($con);
    $tender_ref_no = generateTenderRefNo($con, $type);

    $sqlinsert="INSERT INTO tender (tender_id,tender_ref_no,title,tender_type,currency,
        publish_date,bid_open_date,bid_close_date,bid_validity,
        status,create_by)
    VALUES(
        '$tender_id',
        '$tender_ref_no',
        '".mysqli_real_escape_string($con,$_POST["txttitle"])."',
        '$type',
        '".mysqli_real_escape_string($con,$_POST["txtcurrency"])."',
        '".mysqli_real_escape_string($con,$_POST["txtpublish_date"])."',
        '".mysqli_real_escape_string($con,$_POST["txtbid_open_date"])."',
        '".mysqli_real_escape_string($con,$_POST["txtbid_close_date"])."',
        '".mysqli_real_escape_string($con,$_POST["txtbid_validity"])."',
        '".mysqli_real_escape_string($con,$_POST["txtstatus"])."',
        '".mysqli_real_escape_string($con,$_POST["txtcreate_by"])."')";

    $insertresult=mysqli_query($con,$sqlinsert) or die(mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=tender.php&option=add" </script>';
            }
    }
// Insert code end
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
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                                                <label class="login2 pull-right pull-right-pro">Tender Type </label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                                                <div class="bt-df-checkbox pull-left">				
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="i-checks pull-left">
                                                                                <label>
                                                                                <input type="radio" name="txttender_type" id="txttender_type" value="Sales" required> Sales </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="i-checks pull-left">
                                                                                <label>
                                                                                <input type="radio" name="txttender_type" id="txttender_type" value="Procurement" checked> Procurement </label>
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
                                                            
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Tender Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txttender_id" class="form-control" readonly value="" />
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
                                                                <input type="date" name="txtpublish_date" id="txtpublish_date" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Open Date & Time</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="datetime-local" name="txtbid_open_date" id="txtbid_open_date" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">bid_close_date & Time</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="datetime-local" name="txtbid_close_date" id="txtbid_close_date" class="form-control" required />
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

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Status</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select class="form-control custom-select-value" name="txtstatus" id="txtstatus" required >
                                                                    <option>-- Select --</option>
                                                                    <option>Active</option>
                                                                    <option>Expaired</option>
                                                                </select>
                                                                <!-- <input type="text" name="txtstatus" id="txtstatus" class="form-control" required />-->
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Create By</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtcreate_by" id="txtcreate_by" class="form-control" required>
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT staff_id, name FROM staff";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["staff_id"].'">'.$row_load["name"].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <!-- <input type="text" name="txtcreate_by" id="txtcreate_by" class="form-control" required /> -->  
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
                                    <h1>Tender <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=tender.php&option=add"><button class="btn btn-primary">Add Tender</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="tender_id">Tender ID</th>
                                                <th data-field="tender_ref_no">Tender Reference Number</th>
                                                <th data-field="title" >Title</th>
                                                <th data-field="bid_open_date" >Bid Open Date & Time</th>
                                                <th data-field="bid_close_date" >Bid Close Date</th>
                                                <th data-field="status">Status</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT tender_id,tender_ref_no,title,bid_open_date,bid_close_date,status From tender";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["tender_id"].'</td>';
                                                    echo'<td>'.$rowview["tender_ref_no"].'</td>';
                                                    echo'<td>'.$rowview["title"].'</td>';
                                                    echo'<td>'.$rowview["bid_open_date"].'</td>';
                                                    echo'<td>'.$rowview["bid_close_date"].'</td>';
                                                    echo'<td>'.$rowview["status"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=tender.php&option=fullview&pk_tender_id='.$rowview["tender_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    echo'<a href="index.php?page=tender.php&option=edit&pk_tender_id='.$rowview["tender_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=tender.php&option=delete&pk_tender_id='.$rowview["tender_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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
                $pk_tender_ref_no=$_GET["pk_tender_ref_no"];
                $pktender_id=$_GET["pk_tender_id"];

                $sqlview="SELECT * FROM tender
                WHERE tender_id='$pk_tender_id'";

                $sqlview="SELECT * FROM tender WHERE tender_id='$pk_tender_id'";
                $resultview=mysqli_query($con,$sqlview) or die("SQL error".mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);
            ?>
                    <div class="static-table-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                                                            <td><b>Tender  ID</b></td>
                                                            <td><?php echo $rowview["tender_id"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Tender Reference Number</b></td>
                                                            <td><?php echo $rowview["tender_ref_no"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Tender Type</b></td>
                                                            <td><?php echo $rowview["tender_type"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Title</b></td>
                                                            <td><?php echo $rowview["title"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Currency</b></td>
                                                            <td><?php echo $rowview["currency"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Publish Date</b></td>
                                                            <td><?php echo $rowview["publish_date"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>bid_close_date & Time</b></td>
                                                            <td><?php echo $rowview["bid_close_date"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Bid Open Date & Time</b></td>
                                                            <td><?php echo $rowview["bid_open_date"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Bid Valid Days</b></td>
                                                            <td><?php echo $rowview["bid_validity"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Status</b></td>
                                                            <td><?php echo $rowview["status"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Create By</b></td>
                                                            <td><?php echo $rowview["create_by"]; ?></td>
                                                        </tr>
                                                    </tbody>
                                                    
                                                </table> 
                                                <a href="index.php?page=tender_product.php&option=view"> <button class="btn btn-warning">Back</button> </a>     
                                                <?php
                                                if(!isset($_GET['print']))
                                                {
                                                echo '<a href="print.php?tender_id='.$rowview['tender_id'].'" target="_blank">
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
                
            }
            else if($_GET["option"]=="delete")
            {
                
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