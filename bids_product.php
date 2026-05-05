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
        $sqlinsert="INSERT INTO bids_product (bid_id,product_id,bank_id,swift_code,IBAN_no,qty,unit_price,credit_Period_facility,line_total)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtbid_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtproduct_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbank_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtswift_code"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtIBAN_no"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtqty"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtunit_price"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtcredit_Period_facility"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtline_total"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=bids_product.php&option=add&bid_id=' . $_POST["txtbid_id"] . '" </script>';
            }
    }
// Insert code end
?>

<script>
    function get_product_details()
    {
        var product_id=document.getElementById("txtproduct_id").value;
        var tender_id=document.getElementById("txttender_id").value;
        document.getElementById("txtqty").min="";
        document.getElementById("txtqty").max="";
        document.getElementById("txtqty").value="";
        if(product_id !="")
        {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var response = this.responseText.split("&&&&");
                    
                    document.getElementById("txtqty").min=response[0];

                    document.getElementById("txtqty").max=response[1];
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=bid_product&ajax_tender_id=" + tender_id+"&ajax_product_id=" + product_id,  true);
            xmlhttp.send();
        }

    }
</script>

<script>
    function calculateLineTotal()
    {
        var qty=document.getElementById("txtqty").value;
        var unit_price=document.getElementById("txtunit_price").value;
        if(qty !="" && unit_price !="")
        {
            document.getElementById("txtline_total").value=(parseFloat(qty)*parseFloat(unit_price)).toFixed(2);
        }
        else
        {
            document.getElementById("txtline_total").value="";
        }
    }
</script>

<body>
    <?php
    if(isset($_GET["option"])) 
       {
        if($_GET["option"]=="add")
            {
                $get_bid_id=$_GET["bid_id"];
                $sql_bid="SELECT tender_ref_no, bidder_id FROM bids WHERE bid_id='$get_bid_id'";
                $result_bid=mysqli_query($con,$sql_bid) or die("SQL error in sql_bid".mysqli_error($con));
                $row_bid=mysqli_fetch_assoc($result_bid);

                $sql_tender="SELECT tender_id,bid_open_date FROM tender WHERE tender_ref_no='$row_bid[tender_ref_no]'";
                $result_tender=mysqli_query($con,$sql_tender) or die("SQL error in sql_tender".mysqli_error($con));
                $row_tender=mysqli_fetch_assoc($result_tender);

                $min_date=date("Y-m-d",strtotime($row_tender["bid_open_date"]));
                $max_date=date("Y-m-d",strtotime($row_tender["bid_open_date"]."+90 days"));
                ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Bids Product Add Form</h1>
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
                                                                <input type="hidden" name="txttender_id" id="txttender_id" class="form-control" value="<?php echo $row_tender["tender_id"]; ?>" readonly />
                                                                <select name="txtbid_id" id="txtbid_id" class="form-control" required>
                                                                    
                                                                    <?php
                                                                    $sql_load="SELECT bid_id FROM bids WHERE bid_id='$get_bid_id'"; 
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
                                                                <label class="login2 pull-right pull-right-pro">Product Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtproduct_id" id="txtproduct_id" class="form-control" required onchange="get_product_details()">
                                                                    <option value="">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT product_id, name FROM product WHERE product_id IN (SELECT product_id FROM tender_product WHERE tender_id='$row_tender[tender_id]') ";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            $sqlcheck="SELECT bid_id FROM bids_product WHERE bid_id='".$get_bid_id."' AND product_id='".$row_load["product_id"]."'"; 
                                                                            $resultcheck=mysqli_query($con, $sqlcheck) or die ("SQL error in sqlcheck".mysqli_error($con));
                                                                            if(mysqli_num_rows($resultcheck)==0)
                                                                            echo'<option value="'.$row_load["product_id"].'">'.$row_load["name"].'</option>';
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
                                                                <label class="login2 pull-right pull-right-pro">Bank Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtbank_id" id="txtbank_id" class="form-control" required>
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT bank_id, name, account_no FROM bidders_bank WHERE bidder_id='$row_bid[bidder_id]'"; 
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["bank_id"].'">'.$row_load["name"].' '.$row_load["account_no"].'</option>';
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
                                                                <label class="login2 pull-right pull-right-pro">IBAN Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtIBAN_no" id="txtIBAN_no" class="form-control" onblur="IBAN_no_check('txtIBAN_no', 'add')" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Swift Code</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtswift_code" id="txtswift_code" class="form-control" onblur="SWIFT_no_check('txtswift_code', 'add')" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Quantity</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="number" name="txtqty" id="txtqty" class="form-control" onkeypress="return isNumberKey(event)" step="0.01" oninput="calculateLineTotal()" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Credit Period Facility</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="date" name="txtcredit_Period_facility" id="txtcredit_Period_facility" class="form-control" min="<?php echo $min_date; ?>" max="<?php echo $max_date; ?>"   required />
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
                                                                <label class="login2 pull-right pull-right-pro">Unit Price</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="number" name="txtunit_price" id="txtunit_price" class="form-control" step="0.01" min="0.01" oninput="calculateLineTotal()" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Line Total</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="number" name="txtline_total" id="txtline_total" class="form-control" required readonly />
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=bids.php&option=fullview&pk_bid_id=<?php echo $get_bid_id; ?>"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Bid Product <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=bids_product.php&option=add"><button class="btn btn-primary">Add Bid Product</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="bid_id">Bid ID</th>
                                                <th data-field="product_id" >Product Name</th>
                                                <th data-field="bank_id" >Bank Name</th>
                                                <th data-field="qty" >Quantity</th>
                                                <th data-field="unit_price">Unit Price</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT bid_id,product_id,bank_id,qty,unit_price From bids_product";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    $sqlproductname="SELECT name FROM product WHERE product_id ='$rowview[product_id]'";
                                                    $resultproductname=mysqli_query($con,$sqlproductname) or die("SQL view error".mysqli_error($con));
                                                    $rowproductname=mysqli_fetch_assoc($resultproductname);

                                                    $sqlbidders_bankname="SELECT name FROM bidders_bank WHERE bank_id='$rowview[bank_id]'";
                                                    $resultbidders_bankname=mysqli_query($con,$sqlbidders_bankname) or die("SQL view error".mysqli_error($con));
                                                    $rowbidders_bankname=mysqli_fetch_assoc($resultbidders_bankname);
                                                    echo'<tr>';
                                                        echo'<td>'.$rowview["bid_id"].'</td>';
                                                        echo'<td>'.$rowproductname["name"].'</td>';
                                                        echo'<td>'.$rowbidders_bankname["name"].'</td>';
                                                        echo'<td>'.$rowview["qty"].'</td>';
                                                        echo'<td>'.$rowview["unit_price"].'</td>';
                                                        echo'<td>';
                                                        echo'<a href="index.php?page=bids_product.php&option=fullview&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-success">View</button></a> ';
                                                        echo'<a href="index.php?page=bids_product.php&option=edit&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                        echo'<a onclick="return deletedata()" href="index.php?page=bids_product.php&option=delete&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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