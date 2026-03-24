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
        $sqlinsert="INSERT INTO bids (bid_id,tender_id,bidder_id,proprietor_id,status,delivery_method,delivery_place,bid_currency,bid_valide_date,submit_date,document,open_key	)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtbid_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txttender_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbidder_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtproprietor_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtstatus"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtdelivery_method"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtdelivery_place"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbid_currency"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtbid_valide_date"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtsubmit_date"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtdocument"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtopen_key"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=bids.php&option=add" </script>';
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
                                    <h1>Bids Add Form</h1>
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
                                                                <input type="text" name="txtbid_id" id="txtbid_id" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
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
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Proprietor Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtproprietor_id" id="txtproprietor_id" class="form-control" required>
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT proprietor_id,name FROM proprietor";
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
                                                                <input type="datetime-local" name="txtbid_valide_date" id="txtbid_valide_date" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Status</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <div class="form-select-list">
                                                                    <select class="form-control custom-select-value" name="txtstatus"  id="txtstatus" required >
																			<option>-- Select --</option>
                                                                            <option>Approved</option>
																			<option>Pending</option>
																			<option>Reject</option>
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
                                                                <label class="login2 pull-right pull-right-pro">Delivery Method</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <div class="form-select-list">
                                                                    <select class="form-control custom-select-value" name="txtdelivery_method" id="txtdelivery_method" required >
																			<option>-- Select --</option>
                                                                            <option>Free Delivery to Head Office Rajagiriya</option>
																			<option>No Free Delivery</option>
																			<option>Delivery Arrange By Bidder Own</option>
																		</select>
                                                                </div>
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Delivery Place</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <div class="form-select-list">
                                                                    <select class="form-control custom-select-value" name="txtdelivery_place" id="txtdelivery_place" required >
                                                                        <option>-- Select --</option>
                                                                        <option>Head Office Rajagiriya</option>
                                                                        <option>Ex - works Pulmoddai</option>
                                                                        <option>Ex - works Trincomalee Warehouse at Trinco Harbor</option>
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
                                                                <label class="login2 pull-right pull-right-pro">Bid Currency</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbid_currency" id="txtbid_currency" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Submit Date</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="datetime-local" name="txtsubmit_date" id="txtsubmit_date" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Document</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">                                                                
                                                                <input type="text" name="txtdocument" id="txtdocument" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Bid Open Key</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtopen_key" id="txtopen_key" class="form-control" required />
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
                                                    echo'<a href="index.php?page=bids.php&option=edit&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=bids.php&option=delete&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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