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
        $sqlinsert="INSERT INTO tender_product (tender_id,product_id,available_qty,min_qty,delivery_term,bid_security_usd,bid_security_valid_days,perf_security_valid_days,comments)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txttender_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtproduct_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtavailable_qty"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtmin_qty"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtdelivery_term"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbid_security_usd"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbid_security_valid_days"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtperf_security_valid_days"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtcomments"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=tender_product.php&option=add" </script>';
            }
    }
// Insert code end
//Update code start
if(isset($_POST["btnupdate"]))
    {
        $tender_id = $_POST["txttender_id"];
        $product_id = $_POST["txtproduct_id"];

        $sqlupdate="UPDATE tender_product SET
        available_qty='".mysqli_real_escape_string($con,$_POST["txtavailable_qty"])."',
        min_qty='".mysqli_real_escape_string($con,$_POST["txtmin_qty"])."',
        delivery_term='".mysqli_real_escape_string($con,$_POST["txtdelivery_term"])."',
        bid_security_usd='".mysqli_real_escape_string($con,$_POST["txtbid_security_usd"])."',
        bid_security_valid_days='".mysqli_real_escape_string($con,$_POST["txtbid_security_valid_days"])."',
        perf_security_valid_days='".mysqli_real_escape_string($con,$_POST["txtperf_security_valid_days"])."',
        comments='".mysqli_real_escape_string($con,$_POST["txtcomments"])."'
        WHERE tender_id='$tender_id' AND product_id='$product_id'";

        $resultupdate=mysqli_query($con,$sqlupdate);

        if($resultupdate)
        {
            echo'<script>alert("Record Updated Successfully");window.location.href="index.php?page=tender_product.php&option=view"</script>';
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
                                    <h1>Tender Product Add Form</h1>
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
                                                                <select name="txttender_id" id="txttender_id" class="form-control" required>
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT tender_id FROM tender";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["tender_id"].'">'.$row_load["tender_id"].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Product Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtproduct_id" id="txtproduct_id" class="form-control" required>
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT product_id, name FROM product";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["product_id"].'">'.$row_load["product_id"].'</option>';
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
                                                                <label class="login2 pull-right pull-right-pro">Available quantity</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtavailable_qty" id="txtavailable_qty" class="form-control" onkeypress="return isNumberKey(event)" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                             <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Minimum quantity</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtmin_qty" id="txtmin_qty" class="form-control" onkeypress="return isNumberKey(event)" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Delivery Term</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select class="form-control custom-select-value" name="txtdelivery_term" id="txtdelivery_term" required >
                                                                    <option>-- Select --</option>
                                                                    <option>Head Office Rajagiriya</option>
                                                                    <option>Ex - works Pulmoddai</option>
                                                                    <option>Ex - works Trincomalee Warehouse at Trinco Harbor</option>
                                                                    <option>No Delivery Services</option>
                                                                    <option>Free Delivery</option>
                                                                    <option>Cash Delivery</option>
																</select>
                                                                <!--  <input type="text" name="txtdelivery_term" id="txtdelivery_term" class="form-control" required /> -->
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Security(USD)</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbid_security_usd" id="txtbid_security_usd" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Bid Security Valid Days</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbid_security_valid_days" id="txtbid_security_valid_days" class="form-control" onkeypress="return isNumberKey(event)" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Performance Security Valid Days</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtperf_security_valid_days" id="txtperf_security_valid_days" class="form-control" onkeypress="return isNumberKey(event)" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Comments</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtcomments" id="txtcomments" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->                                                               
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=tender_product.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Tender Product <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=tender_product.php&option=add"><button class="btn btn-primary">Add Tender Product</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="tender_id ">Tender ID</th>
                                                <th data-field="product_id" >Product ID</th>
                                                <th data-field="available_qty" >Available Quantity</th>
                                                <th data-field="min_qty" >Minimum Quantity</th>
                                                <th data-field="bid_security_usd">Bid Security</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT tender_id ,product_id,available_qty,min_qty,bid_security_usd From tender_product";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    $sqlproductname="SELECT name FROM product WHERE product_id ='$rowview[product_id]'";
                                                    $resultproductname=mysqli_query($con,$sqlproductname) or die("SQL view error".mysqli_error($con));
                                                    $rowproductname=mysqli_fetch_assoc($resultproductname);
                                                    
                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["tender_id"].'</td>';
                                                    echo'<td>'.$rowproductname["name"].'</td>';
                                                    echo'<td>'.$rowview["available_qty"].'</td>';
                                                    echo'<td>'.$rowview["min_qty"].'</td>';
                                                    echo'<td>'.$rowview["bid_security_usd"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=tender_product.php&option=fullview&pk_tender_id='.$rowview["tender_id"].'&pk_product_id='.$rowview["product_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    echo'<a href="index.php?page=tender_product.php&option=edit&pk_tender_id='.$rowview["tender_id"].'&pk_product_id='.$rowview["product_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=tender_product.php&option=delete&pk_tender_id='.$rowview["tender_id"].'&pk_product_id='.$rowview["product_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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
            $pk_tender_id=$_GET["pk_tender_id"];
            $pk_product_id=$_GET["pk_product_id"];

                $sqlview="SELECT * FROM tender_product 
                WHERE tender_id='$pk_tender_id' 
                AND product_id='$pk_product_id'";

                $sqlview="SELECT * FROM tender_product WHERE tender_id='$pk_tender_id'";
                $resultview=mysqli_query($con,$sqlview) or die("SQL error".mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);

                $sqlproduct="SELECT name FROM product WHERE product_id='$rowview[product_id]'";
                $resultproduct=mysqli_query($con,$sqlproduct);
                $rowproduct=mysqli_fetch_assoc($resultproduct);
            ?>
                    <div class="static-table-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="sparkline8-list">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <h1>Tender Product Table</h1>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table">                                        
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Tender Product ID</b></td>
                                                            <td><?php echo $rowview["tender_id"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Tender Product Name</b></td>
                                                            <td><?php echo $rowproduct["name"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Available Quantity</b></td>
                                                            <td><?php echo $rowview["available_qty"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Minimum Quantity</b></td>
                                                            <td><?php echo $rowview["min_qty"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Delivery Term</b></td>
                                                            <td><?php echo $rowview["delivery_term"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Bid Security (USD)</b></td>
                                                            <td><?php echo $rowview["bid_security_usd"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Bid Security Valid Days</b></td>
                                                            <td><?php echo $rowview["bid_security_valid_days"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Performance Security Valid Days</b></td>
                                                            <td><?php echo $rowview["perf_security_valid_days"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Comments</b></td>
                                                            <td><?php echo $rowview["comments"]; ?></td>
                                                        </tr>
                                                    </tbody>
                                                    
                                                </table> 
                                                <a href="index.php?page=tender_product.php&option=view">
                                                <button class="btn btn-warning">Back</button>
                                                </a>     
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
            $pk_tender_id=$_GET["pk_tender_id"];

            $sqlview="SELECT * FROM tender_product WHERE tender_id='$pk_tender_id'";
            $resultview=mysqli_query($con,$sqlview);
            $rowview=mysqli_fetch_assoc($resultview);
            ?>
            <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Tender Product Edit Form</h1>
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
                                                                <input type="text" name="txttender_id" id="txttender_id" class="form-control" value="<?php echo $rowview['tender_id']; ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Product Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtproduct_id" id="txtproduct_id" class="form-control" value="<?php echo $rowview['product_id']; ?>" readonly required />
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
                                                                <label class="login2 pull-right pull-right-pro">Available Quantity</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtavailable_qty" id="txtavailable_qty" class="form-control" value="<?php echo $rowview['available_qty']; ?>" required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Minimum Quantity</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtmin_qty" id="txtmin_qty" class="form-control" value="<?php echo $rowview['min_qty']; ?>" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Delivery Term</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtdelivery_term" id="txtdelivery_term" class="form-control" value="<?php echo $rowview['delivery_term']; ?>" required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Security USD</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbid_security_usd" id="txtbid_security_usd" class="form-control" value="<?php echo $rowview['bid_security_usd']; ?>" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Bid Security Valid Days</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbid_security_valid_days" id="txtbid_security_valid_days" class="form-control" value="<?php echo $rowview['bid_security_valid_days']; ?>" required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Performance Security Valid Days</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtperf_security_valid_days" id="txtperf_security_valid_days" class="form-control" value="<?php echo $rowview['perf_security_valid_days']; ?>" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Comments</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtcomments" id="txtcomments" class="form-control" value="<?php echo $rowview['comments']; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->                                                      
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=tender_product.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
            $tender_id=$_GET["pk_tender_id"];
                $sqldelete="DELETE FROM tender_product WHERE tender_id='$tender_id'";
                $resultdelete=mysqli_query($con,$sqldelete) or die(mysqli_error($con));

            if($resultdelete)
            {
                echo'<script>alert("Record Deleted Successfully");window.location.href="index.php?page=tender_product.php&option=view";</script>';
            }    
            }

       }
       ?>
</body>