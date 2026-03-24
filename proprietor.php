<?php
if(!isset($_SESSION)){
    session_start();
}
if(isset($_SESSION["login_usertype"]))
    {
        //Your account has been accessed.
        $system_usertype=$_SESSION["login_usertype"];
        $system_username=$_SESSION["login_username"];
    }
    else{
        //guest or public
        $system_usertype="Guest";
    }
include("config.php");
//Insert code start
if(isset($_POST["btnsave"]))
    {
        $sqlinsert="INSERT INTO proprietor (proprietor_id,name,nic_passport,designation,address,mobile,land,bidder_id)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtproprietor_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtname"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtnic_passport"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtdesignation"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtland"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbidder_id"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
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
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=proprietor.php&option=add" </script>';
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
                                    <h1>Proprietor Add Form</h1>
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
                                                                <input type="text" name="txtname" id="txtname" class="form-control" onkeypress="return isTextKey(event)" required />
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
                                                                <input type="text" name="txtaddress" id="txtaddress" class="form-control" required />
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
                                                                <input type="text" name="txtnic_passport" id="txtnic_passport" class="form-control"  required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Designation</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtdesignation" id="txtdesignation" class="form-control" onkeypress="return isTextKey(event)" required />
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
                                                                <input type="text" name="txtmobile" id="txtmobile" class="form-control" onkeypress="return isNumberKey(event)" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Telephone</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtland" id="txtland" class="form-control" required />
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