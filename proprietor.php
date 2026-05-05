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

$upload_dir = "proprietor/nic_copy/";
$upload_dir_fs = rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $upload_dir);



//Insert code start
if(isset($_POST["btnsave"]))
{
    $proprietor_id = $_POST["txtproprietor_id"];
    $file_nic_copy = "";

    if(isset($_FILES["txtnic_copy"]) && $_FILES["txtnic_copy"]["error"] == 0)
    {
        $original_file = $_FILES["txtnic_copy"]["name"];
        $tmp_name = $_FILES["txtnic_copy"]["tmp_name"];

        $ext = strtolower(pathinfo($original_file, PATHINFO_EXTENSION));
        $allowed = array("pdf","jpg","jpeg","png");

        if(!in_array($ext,$allowed))
        {
            echo "<script>alert('Invalid file type');</script>";
            exit();
        }

        if(!is_dir($upload_dir_fs))
        {
            mkdir($upload_dir_fs, 0777, true);
        }

        // ✅ SAME NAME USING ID
        $file_nic_copy = $proprietor_id . "." . $ext;

        // If already exists, delete (safety)
        if(file_exists($upload_dir_fs.$file_nic_copy))
        {
            unlink($upload_dir_fs.$file_nic_copy);
        }

        if(!move_uploaded_file($tmp_name, $upload_dir_fs.$file_nic_copy))
        {
            echo "<script>alert('File upload failed');</script>";
            exit();
        }
    }

    // INSERT QUERY
    $sqlinsert="INSERT INTO proprietor (proprietor_id,name,nic_passport,designation,address,mobile,land,nic_copy,bidder_id)
    VALUES('$proprietor_id',
    '".mysqli_real_escape_string($con,strtoupper($_POST["txtname"]))."',
    '".mysqli_real_escape_string($con,$_POST["txtnic_passport"])."',
    '".mysqli_real_escape_string($con,strtoupper($_POST["txtdesignation"]))."',
    '".mysqli_real_escape_string($con,strtoupper($_POST["txtaddress"]))."',
    '".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
    '".mysqli_real_escape_string($con,$_POST["txtland"])."',
    '$file_nic_copy',
    '".mysqli_real_escape_string($con,$_POST["txtbidder_id"])."')";
    
    mysqli_query($con,$sqlinsert) or die(mysqli_error($con));

    // Insert into user
        $password=md5(123456);
        $sqlinsertlogin="INSERT INTO user (user_id,user_name,password,usertype,attempt,otp,status)
        VALUES('".mysqli_real_escape_string($con,$_POST["txtproprietor_id"])."',
        '".mysqli_real_escape_string($con,$_POST["txtnic_passport"])."',
        '".mysqli_real_escape_string($con,$password)."',
        '".mysqli_real_escape_string($con,"Proprietor")."',
        '".mysqli_real_escape_string($con,0)."',
        '".mysqli_real_escape_string($con,0)."',
        '".mysqli_real_escape_string($con,"Active")."')"; 
        $insertloginresult=mysqli_query($con,$sqlinsertlogin) or die("SQL insert error".mysqli_error($con));

    echo '<script>alert("Record Inserted");window.location.href="index.php?page=proprietor.php&option=view";</script>';
}
// Insert code end

//Update code start
if(isset($_POST["btnupdate"]))
{
    $proprietor_id = $_POST["txtproprietor_id"];
    $allowed = array("pdf","jpg","jpeg","png");

    // Get old file
    $sqlget = "SELECT nic_copy FROM proprietor WHERE proprietor_id='$proprietor_id'";
    $resultget = mysqli_query($con,$sqlget);
    $rowget = mysqli_fetch_assoc($resultget);
    $old_file = $rowget["nic_copy"];

    $sqlupdate="UPDATE proprietor SET
        name='".mysqli_real_escape_string($con,strtoupper($_POST["txtname"]))."',
        nic_passport='".mysqli_real_escape_string($con,$_POST["txtnic_passport"])."',
        designation='".mysqli_real_escape_string($con,strtoupper($_POST["txtdesignation"]))."',
        address='".mysqli_real_escape_string($con,strtoupper($_POST["txtaddress"]))."',
        mobile='".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
        land='".mysqli_real_escape_string($con,$_POST["txtland"])."',
        bidder_id='".mysqli_real_escape_string($con,$_POST["txtbidder_id"])."'";

    // FILE UPDATE
    if(isset($_FILES["txtnic_copy"]) && $_FILES["txtnic_copy"]["error"] == 0)
    {
        $original_file = $_FILES["txtnic_copy"]["name"];
        $tmp_name = $_FILES["txtnic_copy"]["tmp_name"];

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
        $new_file = $proprietor_id . "." . $ext;

        // Delete old file if different extension
        if(!empty($old_file) && file_exists($upload_dir_fs.$old_file))
        {
            unlink($upload_dir_fs.$old_file);
        }

        if(move_uploaded_file($tmp_name, $upload_dir_fs.$new_file))
        {
            $sqlupdate .= ", nic_copy='$new_file'";
        }
        else
        {
            echo "<script>alert('File upload failed');</script>";
            exit();
        }
    }

    $sqlupdate .= " WHERE proprietor_id='$proprietor_id'";

    mysqli_query($con,$sqlupdate) or die(mysqli_error($con));

    echo '<script>alert("Record Updated");window.location.href="index.php?page=proprietor.php&option=view";</script>';
}
//Update code end
?>
<script>
    function validateNICPassport()
        {
           
            var nic_passport=document.getElementById("txtnic_passport").value;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var response_value = xmlhttp.responseText.trim();
                    
                    if(response_value=="no")
                    {
                        
                    }
                    else
                    {
                        alert("This nic/passport already exists. Please enter a different nic/passport.");
                        document.getElementById("txtnic_passport").value = "";
                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=proprietor_nic&ajax_nic=" + nic_passport, true);
            xmlhttp.send();
        }
</script>

<script>
    function phonenumber_check(mobiletxt, optionname)
        {
            var mobile=document.getElementById(mobiletxt).value;
            var proprietorid=document.getElementById("txtproprietor_id").value;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var response_value = xmlhttp.responseText.trim();
                    
                    if(response_value=="no")
                    {
                        phonenumber(mobiletxt);
                    }
                    else
                    {
                        alert("This phone number already exists. Please enter a different phone number.");
                        document.getElementById(mobiletxt).value = "";
                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=proprietor_mobile&ajax_mobile=" + mobile + "&ajax_proprietor_id=" + proprietorid + "&ajax_option=" + optionname, true);
            xmlhttp.send();
        }
</script>
<script>
    function landphonenumber_check(landtxt, optionname)
        {
            var land=document.getElementById(landtxt).value;
            var proprietorid=document.getElementById("txtproprietor_id").value;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var response_value = xmlhttp.responseText.trim();
                    
                    if(response_value=="no")
                    {
                        landphonenumber(landtxt);
                    }
                    else
                    {
                        alert("This telephone number already exists. Please enter a different telephone number.");
                        document.getElementById(landtxt).value = "";
                    }    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=proprietor_land&ajax_land=" + land + "&ajax_proprietor_id=" + proprietorid + "&ajax_option=" + optionname, true);
            xmlhttp.send();
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
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Proprietor Add Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Proprietor Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $sql_generatedid="SELECT proprietor_id FROM proprietor ORDER BY proprietor_id DESC LIMIT 1";
                                                                
                                                                $result_generatedid=mysqli_query($con,$sql_generatedid) or die("SQL error in sql_generateid".mysqli_error($con));
                                                                if(mysqli_num_rows($result_generatedid)==1)
                                                                    {//for other than first time
                                                                    $row_generatedid=mysqli_fetch_assoc($result_generatedid);
                                                                    $generatedid=++$row_generatedid["proprietor_id"];
                                                                    }
                                                                else
                                                                    { //for first time
                                                                    $generatedid="PP00000001";
                                                                    }
                                                                ?>
                                                                <input type="text" name="txtproprietor_id" id="txtproprietor_id" class="form-control" value="<?php echo $generatedid; ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bidder Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtbidder_id" id="txtbidder_id" class="form-control" required>
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT bidder_id, company_name FROM bidders";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["bidder_id"].'">'.$row_load["company_name"].'</option>';
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
                                                                <label class="login2 pull-right pull-right-pro">Name</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtname" id="txtname" class="form-control" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" onkeypress="return isTextKey(event)" required />
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
                                                                <input type="text" name="txtaddress" id="txtaddress" class="form-control" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required />
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
                                                                <label class="login2 pull-right pull-right-pro">N.I.C / Passport</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtnic_passport" id="txtnic_passport" class="form-control" onblur="validateNICPassport('txtnic_passport', 'add')" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Designation</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtdesignation" id="txtdesignation" class="form-control" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" onkeypress="return isTextKey(event)" required />
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
                                                                <input type="text" name="txtmobile" id="txtmobile" class="form-control" onkeypress="return isNumberKey(event)" onblur="phonenumber_check('txtmobile', 'add')" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Telephone</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtland" id="txtland" class="form-control" onkeypress="return isNumberKey(event)" onblur="landphonenumber_check('txtland', 'add')" required />
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
                                                                <label class="login2 pull-right pull-right-pro">NIC / Passport Copy</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="file" name="txtnic_copy" id="txtnic_copy" class="form-control" placeholder="no file selected" />
                                                            </div>
                                                            <!-- One Column End-->                                                              
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=proprietor.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Proprietor <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=proprietor.php&option=add"><button class="btn btn-primary">Add Proprietor</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="proprietor_id">Proprietor ID</th>
                                                <th data-field="name" >Name</th>
                                                <th data-field="designation" >Designation</th>
                                                <th data-field="mobile" >Mobile</th>
                                                <th data-field="bidder_id">Company Name</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT proprietor_id,name,designation,mobile,bidder_id From proprietor";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    $sqlbiddersname="SELECT company_name FROM bidders WHERE bidder_id='$rowview[bidder_id]'";
                                                    $resultbiddersname=mysqli_query($con,$sqlbiddersname) or die("SQL view error".mysqli_error($con));
                                                    $rowbiddersname=mysqli_fetch_assoc($resultbiddersname);

                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["proprietor_id"].'</td>';
                                                    echo'<td>'.$rowview["name"].'</td>';
                                                    echo'<td>'.$rowview["designation"].'</td>';
                                                    echo'<td>'.$rowview["mobile"].'</td>';
                                                    echo'<td>'.$rowbiddersname["company_name"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=proprietor.php&option=fullview&pk_proprietor_id='.$rowview["proprietor_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    echo'<a href="index.php?page=proprietor.php&option=edit&pk_proprietor_id='.$rowview["proprietor_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=proprietor.php&option=delete&pk_proprietor_id='.$rowview["proprietor_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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
            $proprietor_id=$_GET["pk_proprietor_id"];

                $sqlview="SELECT * FROM proprietor WHERE proprietor_id='$proprietor_id'";
                $resultview=mysqli_query($con,$sqlview) or die("SQL error".mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);

                $sqlbidder="SELECT company_name FROM bidders WHERE bidder_id='$rowview[bidder_id]'";
                $resultbidders=mysqli_query($con,$sqlbidder);
                $rowbidders=mysqli_fetch_assoc($resultbidders);
                ?>
                    <div class="static-table-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="sparkline8-list">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <h1>Proprietor Table</h1>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table">                                        
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Company Name</b></td>
                                                            <td><?php echo $rowbidders["company_name"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Proprietor Id</b></td>
                                                            <td><?php echo $rowview["proprietor_id"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b> Name</b></td>
                                                            <td><?php echo $rowview["name"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Address</b></td>
                                                            <td><?php echo $rowview["address"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>N.I.C / Passport</b></td>
                                                            <td><?php echo $rowview["nic_passport"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Designation</b></td>
                                                            <td><?php echo $rowview["designation"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Mobile</b></td>
                                                            <td><?php echo $rowview["mobile"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Telephone</b></td>
                                                            <td><?php echo $rowview["land"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>NIC / Passport Copy</b></td>
                                                            <td>
                                                                <?php
                                                                if(!empty($rowview["nic_copy"]))
                                                                {
                                                                    ?>
                                                                    <a href="<?php echo $upload_dir . rawurlencode($rowview["nic_copy"]); ?>" target="_blank">
                                                                        <button class="btn btn-primary" type="button">View File</button>
                                                                    </a>
                                                                    <?php
                                                                }
                                                                else
                                                                {
                                                                    echo "No File";
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    
                                                </table> 
                                                <a href="index.php?page=proprietor.php&option=view">
                                                <button class="btn btn-warning">Back</button>
                                                </a>     
                                                <?php
                                                if(!isset($_GET['print']))
                                                {
                                                echo '<a href="print.php?proprietor_id='.$rowview['proprietor_id'].'" target="_blank">
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
            $proprietor_id=$_GET["pk_proprietor_id"];

                $sqlview="SELECT * FROM proprietor WHERE proprietor_id='$proprietor_id'";
                $resultview=mysqli_query($con,$sqlview) or die(mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);
            ?>   
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Proprietor Edit Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Proprietor Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtproprietor_id" id="txtproprietor_id" class="form-control" value="<?php echo $rowview['proprietor_id']; ?>" readonly />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bidder Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtbidder_id" id="txtbidder_id" class="form-control" value="<?php echo $rowview['bidder_id']; ?>" required >
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT bidder_id, company_name FROM bidders";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            if($row_load["bidder_id"]==$rowview["bidder_id"])
                                                                            {
                                                                                echo'<option value="'.$row_load["bidder_id"].'" selected>'.$row_load["company_name"].'</option>';
                                                                            }
                                                                            else
                                                                            {
                                                                                echo'<option value="'.$row_load["bidder_id"].'">'.$row_load["company_name"].'</option>';
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
                                                                <label class="login2 pull-right pull-right-pro">Name</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtname" id="txtname" class="form-control" value="<?php echo $rowview['name']; ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" onkeypress="return isTextKey(event)" required />
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
                                                                <input type="text" name="txtaddress" id="txtaddress" class="form-control" value="<?php echo $rowview['address']; ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();"  required />
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
                                                                <label class="login2 pull-right pull-right-pro">N.I.C / Passport</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtnic_passport" id="txtnic_passport" class="form-control" value="<?php echo $rowview['nic_passport']; ?>" onblur="validateNICPassport('txtnic_passport', 'edit')"  required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Designation</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtdesignation" id="txtdesignation" class="form-control" value="<?php echo $rowview['designation']; ?>" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required />
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
                                                                <input type="text" name="txtmobile" id="txtmobile" class="form-control" value="<?php echo $rowview['mobile']; ?>" onkeypress="return isNumberKey(event)" onblur="phonenumber_check('txtmobile', 'edit')" required />
                                                            </div>
                                                            <!-- One Column End-->     
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Telephone</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtland" id="txtland" class="form-control" value="<?php echo $rowview['land']; ?>" onkeypress="return isNumberKey(event)" onblur="landphonenumber_check('txtland', 'edit')" required />
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
                                                                <label class="login2 pull-right pull-right-pro">NIC / Passport Copy</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="file" name="txtnic_copy" id="txtnic_copy" class="form-control">
                                                                <br>
                                                                <!-- Show existing file -->
                                                                <?php
                                                                if(!empty($rowview['nic_copy']))
                                                                {
                                                                    ?>
                                                                    Current File:
                                                                    <a href="<?php echo $upload_dir . rawurlencode($rowview['nic_copy']); ?>" target="_blank">
                                                                        <?php echo "View File"; ?>
                                                                    </a>
                                                                    <?php
                                                                }
                                                                else
                                                                {
                                                                    echo "No file uploaded";
                                                                }
                                                                ?>
                                                            </div>
                                                            <!-- One Column End-->                                                           
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=proprietor.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
            $proprietor_id = $_GET["pk_proprietor_id"];

                // Get file name first
                $sqlget="SELECT nic_copy FROM proprietor WHERE proprietor_id='$proprietor_id'";
                $resultget=mysqli_query($con,$sqlget);
                $rowget=mysqli_fetch_assoc($resultget);

                $file = $rowget["nic_copy"];

                // Delete database record
                $sqldelete="DELETE FROM proprietor WHERE proprietor_id='$proprietor_id'";
                $resultdelete=mysqli_query($con,$sqldelete) or die(mysqli_error($con));

                // Delete file from folder
                if($file != "" && file_exists($upload_dir_fs.$file))
                {
                    unlink($upload_dir_fs.$file);
                }

                if($resultdelete)
                {
                    echo '<script>alert("Record Deleted Successfully");window.location.href="index.php?page=proprietor.php&option=view";</script>';
                }  
            }

       }
       ?>
</body>