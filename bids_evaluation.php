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
        $sqlinsert="INSERT INTO bids_evaluation (bid_id,responsive,evaluation_score,world_market_ref,past_performance_notes,committee_notes,evaluated_by,evaluated_date)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtbid_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtresponsive"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtevaluation_score"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtworld_market_ref"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtpast_performance_notes"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtcommittee_notes"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtevaluated_by"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtevaluated_date"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=bids_evaluation.php&option=add" </script>';
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
                                    <h1>Bids Evaluation Add Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Responsive</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtresponsive" id="txtresponsive" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Benchmark Price</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtworld_market_ref" id="txtworld_market_ref" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Evaluation Score</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtevaluation_score" id="txtevaluation_score" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Past Performance Notes</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtpast_performance_notes" id="txtpast_performance_notes" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Committee Notes</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtcommittee_notes" id="txtcommittee_notes" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Evaluated By</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtevaluated_by" id="txtevaluated_by" class="form-control" required>
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
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Evaluated Date</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="date" name="txtevaluated_date" id="txtevaluated_date" class="form-control" required />
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=bids_evaluation.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Bids Evaluation <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=bids_evaluation.php&option=add"><button class="btn btn-primary">Add Bids Evaluation</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="bid_id">Bid ID</th>
                                                <th data-field="evaluation_score" >Evaluation Score</th>
                                                <th data-field="world_market_ref" >Benchmark Price</th>
                                                <th data-field="evaluated_by" >Evaluated By</th>
                                                <th data-field="evaluated_date">Evaluated  Date</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT bid_id,evaluation_score,world_market_ref,evaluated_by,evaluated_date From bids_evaluation";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    $sqlstaffname="SELECT name FROM staff WHERE staff_id='$rowview[staff_id]'";
                                                    $resultstaffname=mysqli_query($con,$sqlstaffname) or die("SQL view error".mysqli_error($con));
                                                    $rowstaffname=mysqli_fetch_assoc($resultstaffname);

                                                    echo'<tr>';
                                                        echo'<td>'.$rowview["bid_id"].'</td>';
                                                        echo'<td>'.$rowview["evaluation_score"].'</td>';
                                                        echo'<td>'.$rowview["world_market_ref"].'</td>';
                                                        echo'<td>'.$rowstaffname["evaluated_by"].'</td>';
                                                        echo'<td>'.$rowview["evaluated_date"].'</td>';
                                                        echo'<td>';
                                                        echo'<a href="index.php?page=bids_evaluation.php&option=fullview&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-success">View</button></a> ';
                                                        echo'<a href="index.php?page=bids_evaluation.php&option=edit&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                        echo'<a onclick="return deletedata()" href="index.php?page=bids_evaluation.php&option=delete&pk_bid_id='.$rowview["bid_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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