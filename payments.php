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
        $sqlinsert="INSERT INTO payments (payments_id,invoices_id,amount_usd,pay_date,pay_mode,reference_no,reference_photo,enter_by)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtpayments_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtinvoices_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtamount_usd"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtpay_date"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtpay_mode"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtreference_no"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtreference_photo"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtenter_by"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=payments.php&option=add" </script>';
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
                                    <h1>Payments Add Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Payments Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $sql_generatedid="SELECT payments_id FROM payments ORDER BY payments_id DESC LIMIT 1";
                                                                $result_generatedid=mysqli_query($con,$sql_generatedid) or die("SQL error in sql_generateid".mysqli_error($con));
                                                                if(mysqli_num_rows($result_generatedid)==1)
                                                                    {//for other than first time
                                                                    $row_generatedid=mysqli_fetch_assoc($result_generatedid);
                                                                    $generatedid=++$row_generatedid["payments_id"];
                                                                    }
                                                                else
                                                                    { //for first time
                                                                    $generatedid="PAY0000001";
                                                                    }
                                                                ?>
                                                                <input type="text" name="txtpayments_id" id="txtpayments_id" class="form-control" value="<?php echo $generatedid; ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Invoices Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtinvoices_id" id="txtinvoices_id" class="form-control" required>
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT invoices_id, invoice_no FROM invoices";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["invoices_id"].'">'.$row_load["invoice_no"].'</option>';
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
                                                                <label class="login2 pull-right pull-right-pro">Amount(USD)</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtamount_usd" id="txtamount_usd" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                             <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">payment Date</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="date" name="txtpay_date" id="txtpay_date" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Pay Mode</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtpay_mode" id="txtpay_mode" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Reference Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtreference_no" id="txtreference_no" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Reference Photo</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">                                                                
                                                                <input type="file" name="txtreference_photo" id="txtreference_photo" class="form-control" placeholder="no file selected">                                                                   
                                                            </div> 
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Enter By</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtenter_by" id="txtenter_by" class="form-control" required>
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
                                                            </div>
                                                            <!-- One Column End-->                                                             
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->                                                

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=payments.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Payments <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=payments.php&option=add"><button class="btn btn-primary">Add Payments</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="payments_id ">Payments ID</th>
                                                <th data-field="invoices_id" >Invoices Id</th>
                                                <th data-field="amount_usd" >Amount</th>
                                                <th data-field="reference_no" >Reference Number</th>
                                                <th data-field="enter_by">Entered By</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT payments_id ,invoices_id,amount_usd,reference_no,enter_by From payments";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    $sqlinvoicesname="SELECT invoice_no FROM invoices WHERE invoices_id='$rowview[invoices_id]'";
                                                    $resultinvoicesname=mysqli_query($con,$sqlinvoicesname) or die("SQL view error".mysqli_error($con));
                                                    $rowinvoicesname=mysqli_fetch_assoc($resultinvoicesname);

                                                    $sqlstaffname="SELECT name FROM staff WHERE staff_id='$rowview[staff_id]'";
                                                    $resultstaffname=mysqli_query($con,$sqlstaffname) or die("SQL view error".mysqli_error($con));
                                                    $rowstaffname=mysqli_fetch_assoc($resultstaffname);

                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["payments_id "].'</td>';
                                                    echo'<td>'.$rowinvoicesname["invoice_no"].'</td>';
                                                    echo'<td>'.$rowview["amount_usd"].'</td>';
                                                    echo'<td>'.$rowview["reference_no"].'</td>';
                                                    echo'<td>'.$rowstaffname["name"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=payments.php&option=fullview&pk_payments_id='.$rowview["payments_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    echo'<a href="index.php?page=payments.php&option=edit&pk_payments_id='.$rowview["payments_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=payments.php&option=delete&pk_payments_id='.$rowview["payments_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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