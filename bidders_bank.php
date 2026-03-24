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
        $sqlinsert="INSERT INTO bidders_bank (bank_id,bidder_id,name,branch,code,swift_code,account_no,address,IBAN_no)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtbank_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbidder_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtname"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbranch"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtcode"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtswift_code"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtaccount_no"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtIBAN_no"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=bidders_bank.php&option=add" </script>';
            }
    }
// Insert code end

//Update code start
if(isset($_POST["btnupdate"]))
    {
    $bank_id=$_POST['txtbank_id'];
    $sqlupdate="UPDATE bidders_bank SET
                name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
                branch='".mysqli_real_escape_string($con,$_POST["txtbranch"])."',
                code='".mysqli_real_escape_string($con,$_POST["txtcode"])."',
                swift_code='".mysqli_real_escape_string($con,$_POST["txtswift_code"])."',
                account_no='".mysqli_real_escape_string($con,$_POST["txtaccount_no"])."',
                address='".mysqli_real_escape_string($con,$_POST["txtaddress"])."',
                IBAN_no='".mysqli_real_escape_string($con,$_POST["txtIBAN_no"])."'
                WHERE bank_id='$bank_id'";

    $resultupdate=mysqli_query($con,$sqlupdate) or die(mysqli_error($con));

    echo'<script>alert("Record Updated Successfully");window.location.href="index.php?page=bidders_bank.php&option=view"</script>';
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
                                    <h1>Bidders Bank Add Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Bank Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $sql_generatedid="SELECT bank_id FROM bidders_bank ORDER BY bank_id DESC LIMIT 1";
                                                                
                                                                $result_generatedid=mysqli_query($con,$sql_generatedid) or die("SQL error in sql_generateid".mysqli_error($con));
                                                                if(mysqli_num_rows($result_generatedid)==1)
                                                                    {//for other than first time
                                                                    $row_generatedid=mysqli_fetch_assoc($result_generatedid);
                                                                    $generatedid=++$row_generatedid["bank_id"];
                                                                    }
                                                                else
                                                                    { //for first time
                                                                    $generatedid="BB00000001";
                                                                    }
                                                                ?>
                                                                <input type="text" name="txtbank_id" id="txtbank_id" class="form-control" value="<?php echo $generatedid; ?>" readonly required />
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
                                                                <label class="login2 pull-right pull-right-pro">Name of the Bank</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtname" id="txtname" class="form-control"  required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bank Account Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtaccount_no" id="txtaccount_no" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Address of the Bank</label>
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
                                                                <label class="login2 pull-right pull-right-pro">Branch</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbranch" id="txtbranch" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Branch Code</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtcode" id="txtcode" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">IBAN Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtIBAN_no" id="txtIBAN_no" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Swift Code</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtswift_code" id="txtswift_code" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=bidders_bank.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Bidders Bank <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=bidders_bank.php&option=add"><button class="btn btn-primary">Add Bidders Bank</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="bank_id">Bank ID</th>
                                                <th data-field="bidder_id" >Bidder ID</th>
                                                <th data-field="name" >Bank Name</th>
                                                <th data-field="branch" >Branch</th>
                                                <th data-field="account_no">Account Number</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT bank_id,bidder_id,name,branch,account_no From bidders_bank";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {                                                    
                                                    echo'<tr>';
                                                        echo'<td>'.$rowview["bank_id"].'</td>';
                                                        echo'<td>'.$rowview["bidder_id"].'</td>';
                                                        echo'<td>'.$rowview["name"].'</td>';
                                                        echo'<td>'.$rowview["branch"].'</td>';
                                                        echo'<td>'.$rowview["account_no"].'</td>';
                                                        echo'<td>';
                                                        echo'<a href="index.php?page=bidders_bank.php&option=fullview&pk_bank_id='.$rowview["bank_id"].'"><button class="btn btn-success">View</button></a> ';
                                                        echo'<a href="index.php?page=bidders_bank.php&option=edit&pk_bank_id='.$rowview["bank_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                        echo'<a onclick="return deletedata()" href="index.php?page=bidders_bank.php&option=delete&pk_bank_id='.$rowview["bank_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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
            $bankid=$_GET["pk_bank_id"];

                $sqlview="SELECT * FROM bidders_bank WHERE bank_id='$bankid'";
                $resultview=mysqli_query($con,$sqlview) or die("SQL error".mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);

                $sqlbidders="SELECT company_name FROM bidders WHERE bidder_id='$rowview[bidder_id]'";
                $resultbidders=mysqli_query($con,$sqlbidders);
                $rowbidders=mysqli_fetch_assoc($resultbidders);
            ?>
            <div class="static-table-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="sparkline8-list">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <h1>Bidders Bank Table</h1>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table">                                        
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Bank ID</b></td>
                                                            <td><?php echo $rowview["bank_id"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Bidder Company Name</b></td>
                                                            <td><?php echo $rowbidders["company_name"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Name</b></td>
                                                            <td><?php echo $rowview["name"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Branch</b></td>
                                                            <td><?php echo $rowview["branch"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                        <td><b>Code</b></td>
                                                        <td><?php echo $rowview["code"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                        <td><b>Swift Code</b></td>
                                                        <td><?php echo $rowview["swift_code"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                        <td><b>Account Number</b></td>
                                                        <td><?php echo $rowview["account_no"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                        <td><b>Address</Address></b></td>
                                                        <td><?php echo $rowview["address"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                        <td><b>IBAN Number</b></td>
                                                        <td><?php echo $rowview["IBAN_no"]; ?></td>
                                                        </tr>
                                                    </tbody>
                                                    
                                                </table> 
                                                <a href="index.php?page=bidders_bank.php&option=view">
                                                <button class="btn btn-warning">Back</button>
                                                </a>     
                                                <?php
                                                if(!isset($_GET['print']))
                                                {
                                                echo '<a href="print.php?bank_id='.$rowview["bank_id"].'" target="_blank">
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
            $bankid=$_GET["pk_bank_id"];

                $sqlview="SELECT * FROM bidders_bank WHERE bank_id='$bankid'";
                $resultview=mysqli_query($con,$sqlview) or die(mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);

                
                ?>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Bidder Bank Edit Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Bank Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbank_id" id="txtbank_id" class="form-control" value="<?php echo $rowview['bank_id']; ?>" readonly />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bidder Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbidder_id" id="txtbidder_id" class="form-control" value="<?php echo $rowview['bidder_id']; ?>" readonly />
                                                                <!-- <select name="txtbidder_id" id="txtbidder_id" class="form-control" value="<?php echo $rowview['bidder_id']; ?>" required >
                                                                   
                                                                   // $sqlbidders_bank="SELECT * FROM bidders_bank";
                                                                   // $resultbidders_bank=mysqli_query($con,$sqlbidders_bank);
                                                                  //  while($rowbidders_bank=mysqli_fetch_assoc($resultbidders_bank))
                                                                   // {
                                                                   // $selected="";
                                                                   // if($rowview["bidder_id"]==$rowrole["bidder_id"])
                                                                   // {
                                                                   // $selected="selected";
                                                                  //  }
                                                                   // echo "<option value='$rowrole[bidder_id]' $selected>$rowbidders_bank[name]</option>";
                                                                    //}
                                                                    
                                                                </select> -->
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
                                                                <label class="login2 pull-right pull-right-pro">Name of the Bank</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtname" id="txtname" class="form-control" value="<?php echo $rowview['name']; ?>" onkeypress="return isTextKey(event)" required />
                                                            </div> 
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bank Account Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtaccount_no" id="txtaccount_no" class="form-control" value="<?php echo $rowview['account_no']; ?>"  required />
                                                            </div>
                                                            <!-- <div>
                                                                  <input type="text" name="txtdepartment_id" id="txtdepartment_id" class="form-control" required /> 
                                                            </div>-->
                                                            <!-- One Column End-->
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->  

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Address of the Bank</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtaddress" id="txtaddress" class="form-control" value="<?php echo $rowview['address']; ?>"  required />
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
                                                                <label class="login2 pull-right pull-right-pro">Branch</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbranch" id="txtbranch" class="form-control" value="<?php echo $rowview['branch']; ?>" required />
                                                            </div> 
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Branch Code</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtcode" id="txtcode" class="form-control" value="<?php echo $rowview['code']; ?>"  required />
                                                            <!-- </div>
                                                                  <input type="text" name="txtdepartment_id" id="txtdepartment_id" class="form-control" required /> 
                                                            </div> -->
                                                            <!-- One Column End-->
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- One Row Start-->
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">IBAN Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtIBAN_no" id="txtIBAN_no" class="form-control" value="<?php echo $rowview['IBAN_no']; ?>"  required />
                                                            </div> 
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Swift Code</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtswift_code" id="txtswift_code" class="form-control" value="<?php echo $rowview['swift_code']; ?>"  required />
                                                            </div>
                                                        <!-- </div>
                                                                  <input type="text" name="txtdepartment_id" id="txtdepartment_id" class="form-control" required /> 
                                                            </div> -->
                                                            <!-- One Column End-->
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=bidders_bank.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
            $bankid=$_GET["pk_bank_id"];
                $sqldelete="DELETE FROM bidders_bank WHERE bank_id='$bankid'";
                $resultdelete=mysqli_query($con,$sqldelete) or die(mysqli_error($con));              

            if($resultdelete)
            {
                echo'<script>alert("Record Deleted Successfully");window.location.href="index.php?page=bidders_bank.php&option=view";</script>';
            }    
            }

       }
       ?>
</body>