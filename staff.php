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
if($system_usertype!="Guest" && $system_usertype!="Proprietor" && $system_usertype!="Bidders")
    {//authorized user
include("config.php");
//Insert code start
if(isset($_POST["btnsave"]))
    {
        $sqlinsert="INSERT INTO staff (staff_id,name,nic,role_id,mobile,department_id)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtname"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtnic"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtroleid"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtdepartment_id"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        // Insert into user
        $password=md5($_POST["txtnic"]);
        $sqlinsertlogin="INSERT INTO user (user_id,user_name,password,usertype,attempt,otp,status)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtstaffid"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtnic"])."',
                        '".mysqli_real_escape_string($con,$password)."',
                        '".mysqli_real_escape_string($con,$_POST["txtroleid"])."',
                        '".mysqli_real_escape_string($con,0)."',
                        '".mysqli_real_escape_string($con,0)."',
                        '".mysqli_real_escape_string($con,"Active")."')"; 
        $insertloginresult=mysqli_query($con,$sqlinsertlogin) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=staff.php&option=add" </script>';
            }
    }
// Insert code end
//Update code start
if(isset($_POST["btnupdate"]))
    {
    $staff_id=$_POST['txtstaffid'];
    $sqlupdate="UPDATE staff SET
                name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
                nic='".mysqli_real_escape_string($con,$_POST["txtnic"])."',
                role_id='".mysqli_real_escape_string($con,$_POST["txtroleid"])."',
                mobile='".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
                department_id='".mysqli_real_escape_string($con,$_POST["txtdepartment_id"])."'
                WHERE staff_id='$staff_id'";

    $resultupdate=mysqli_query($con,$sqlupdate) or die(mysqli_error($con));

    echo'<script>alert("Record Updated Successfully");window.location.href="index.php?page=staff.php&option=view"</script>';
    }
// Update code end
?>
<script>
    function nicnumber_check()
        {
            var nic=document.getElementById("txtnic").value;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var response_value = xmlhttp.responseText.trim();
                    
                    if(response_value=="no")
                    {
                        nicnumber();
                    }
                    else
                    {
                        alert("This NIC number already exists. Please enter a different NIC number.");
                        document.getElementById("txtnic").value = "";
                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=staff_nic&ajax_nic=" + nic, true);
            xmlhttp.send();
        }
</script>

<script>
    function phonenumber_check(mobiletxt, optionname)
        {
            var mobile=document.getElementById(mobiletxt).value;
            var staffid=document.getElementById("txtstaffid").value;
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
            xmlhttp.open("GET", "ajaxpage.php?frompage=staff_mobile&ajax_mobile=" + mobile + "&ajax_staff_id=" + staffid + "&ajax_option=" + optionname, true);
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
                                    <h1>Staff Add Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Staff Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $sql_generatedid="SELECT staff_id FROM staff ORDER BY staff_id DESC LIMIT 1";
                                                                
                                                                $result_generatedid=mysqli_query($con,$sql_generatedid) or die("SQL error in sql_generateid".mysqli_error($con));
                                                                if(mysqli_num_rows($result_generatedid)==1)
                                                                    {//for other than first time
                                                                    $row_generatedid=mysqli_fetch_assoc($result_generatedid);
                                                                    $generatedid=++$row_generatedid["staff_id"];
                                                                    }
                                                                else
                                                                    { //for first time
                                                                    $generatedid="ST0001";
                                                                    }
                                                                ?>
                                                                <input type="text" name="txtstaffid" id="txtstaffid" class="form-control" value="<?php echo $generatedid; ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Role Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtroleid" id="txtroleid" class="form-control" required >
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT role_id, name FROM role";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["role_id"].'">'.$row_load["name"].'</option>';
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
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtname" id="txtname" class="form-control" onkeypress="return isTextKey(event)" required />
                                                            </div> 
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Department Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtdepartment_id" id="txtdepartment_id" class="form-control" required>
                                                                    <option value="select">Select</option>
                                                                    <?php
                                                                    $sql_load="SELECT department_id, department_name FROM department";
                                                                    $result_load=mysqli_query($con, $sql_load) or die ("SQL error in sql_load".mysqli_error($con));
                                                                    while ($row_load=mysqli_fetch_assoc($result_load))
                                                                        {
                                                                            echo'<option value="'.$row_load["department_id"].'">'.$row_load["department_name"].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <!--  <input type="text" name="txtdepartment_id" id="txtdepartment_id" class="form-control" required /> -->
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
                                                                <label class="login2 pull-right pull-right-pro">N.I.C</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtnic" id="txtnic" class="form-control" onblur="nicnumber_check('txtnic', 'add')" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Mobile</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtmobile" id="txtmobile" class="form-control" onkeypress="return isNumberKey(event)" onblur="phonenumber_check('txtmobile', 'add')" required />
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=staff.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Staff <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=staff.php&option=add"><button class="btn btn-primary">Add Staff</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="staff_id">Staff ID</th>
                                                <th data-field="name" >Name</th>
                                                <th data-field="role_id" >Role</th>
                                                <th data-field="department" >Department</th>
                                                <th data-field="mobile">Mobile</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT staff_id,name,role_id,department_id,mobile From staff";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    $sqlrolename="SELECT name FROM role WHERE role_id='$rowview[role_id]'";
                                                    $resultrolename=mysqli_query($con,$sqlrolename) or die("SQL view error".mysqli_error($con));
                                                    $rowrolename=mysqli_fetch_assoc($resultrolename);

                                                    $sqldepartmentname="SELECT department_name FROM department WHERE department_id='$rowview[department_id]'";
                                                    $resultdepartmentname=mysqli_query($con,$sqldepartmentname) or die("SQL view error".mysqli_error($con));
                                                    $rowdepartmentname=mysqli_fetch_assoc($resultdepartmentname);
                                                    echo'<tr>';
                                                        echo'<td>'.$rowview["staff_id"].'</td>';
                                                        echo'<td>'.$rowview["name"].'</td>';
                                                        echo'<td>'.$rowrolename["name"].'</td>';
                                                        echo'<td>'.$rowdepartmentname["department_name"].'</td>';
                                                        echo'<td>'.$rowview["mobile"].'</td>';
                                                        echo'<td>';
                                                        echo'<a href="index.php?page=staff.php&option=fullview&pk_staff_id='.$rowview["staff_id"].'"><button class="btn btn-success">View</button></a> ';
                                                        echo'<a href="index.php?page=staff.php&option=edit&pk_staff_id='.$rowview["staff_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                        echo'<a onclick="return deletedata()" href="index.php?page=staff.php&option=delete&pk_staff_id='.$rowview["staff_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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
              
                $staffid=$_GET["pk_staff_id"];

                $sqlview="SELECT * FROM staff WHERE staff_id='$staffid'";
                $resultview=mysqli_query($con,$sqlview) or die("SQL error".mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);

                $sqlrole="SELECT name FROM role WHERE role_id='$rowview[role_id]'";
                $resultrole=mysqli_query($con,$sqlrole);
                $rowrole=mysqli_fetch_assoc($resultrole);

                $sqldep="SELECT department_name FROM department WHERE department_id='$rowview[department_id]'";
                $resultdep=mysqli_query($con,$sqldep);
                $rowdep=mysqli_fetch_assoc($resultdep);
            ?>
                <div class="static-table-area">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="sparkline8-list">
                                    <div class="sparkline8-hd">
                                        <div class="main-sparkline8-hd">
                                            <h1>Staff Table</h1>
                                        </div>
                                    </div>
                                    <div class="sparkline8-graph">
                                        <div class="static-table-list">
                                            <table class="table">                                        
                                                <tbody>
                                                    <tr>
                                                        <td><b>Staff ID</b></td>
                                                        <td><?php echo $rowview["staff_id"]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Name</b></td>
                                                        <td><?php echo $rowview["name"]; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>NIC</b></td>
                                                        <td><?php echo $rowview["nic"]; ?></td>
                                                    </tr>
                                                    <tr>
                                                    <td><b>Role</b></td>
                                                    <td><?php echo $rowrole["name"]; ?></td>
                                                    </tr>

                                                    <tr>
                                                    <td><b>Department</b></td>
                                                    <td><?php echo $rowdep["department_name"]; ?></td>
                                                    </tr>

                                                    <tr>
                                                    <td><b>Mobile</b></td>
                                                    <td><?php echo $rowview["mobile"]; ?></td>
                                                    </tr>
                                                </tbody>                                                
                                            </table> 
                                            <a href="index.php?page=staff.php&option=view">
                                            <button class="btn btn-warning">Back</button>
                                            </a>     
                                            <?php
                                            if(!isset($_GET['print']))
                                            {
                                            echo '<a href="print.php?staff_id='.$rowview["staff_id"].'" target="_blank">
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
                $staffid=$_GET["pk_staff_id"];

                $sqlview="SELECT * FROM staff WHERE staff_id='$staffid'";
                $resultview=mysqli_query($con,$sqlview) or die(mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);               
                ?>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Staff Edit Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Staff Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtstaffid" id="txtstaffid" class="form-control" value="<?php echo $rowview['staff_id']; ?>" readonly />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Role Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtroleid" id="txtroleid" class="form-control" value="<?php echo $rowview['role_id']; ?>" required >
                                                                    <?php
                                                                    $sqlrole="SELECT * FROM role";
                                                                    $resultrole=mysqli_query($con,$sqlrole);
                                                                    while($rowrole=mysqli_fetch_assoc($resultrole))
                                                                    {
                                                                    $selected="";
                                                                    if($rowview["role_id"]==$rowrole["role_id"])
                                                                    {
                                                                    $selected="selected";
                                                                    }
                                                                    echo "<option value='$rowrole[role_id]' $selected>$rowrole[name]</option>";
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
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtname" id="txtname" class="form-control" value="<?php echo $rowview['name']; ?>" onkeypress="return isTextKey(event)" required />
                                                            </div> 
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Department Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <select name="txtdepartment_id" id="txtdepartment_id" class="form-control" value="<?php echo $rowview['department_id']; ?>" required>
                                                                    <?php
                                                                    $sqldep="SELECT * FROM department";
                                                                    $resultdep=mysqli_query($con,$sqldep);
                                                                    while($rowdep=mysqli_fetch_assoc($resultdep))
                                                                    {
                                                                    $selected="";
                                                                    if($rowview["department_id"]==$rowdep["department_id"])
                                                                    {
                                                                    $selected="selected";
                                                                    }
                                                                    echo "<option value='$rowdep[department_id]' $selected>$rowdep[department_name]</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <!--  <input type="text" name="txtdepartment_id" id="txtdepartment_id" class="form-control" required /> -->
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
                                                                <label class="login2 pull-right pull-right-pro">N.I.C</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtnic" id="txtnic" class="form-control" value="<?php echo $rowview['nic']; ?>" onblur="nicnumber_check('txtnic', 'edit')" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Mobile</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtmobile" id="txtmobile" class="form-control" value="<?php echo $rowview['mobile']; ?>" onkeypress="return isNumberKey(event)" onblur="phonenumber_check('txtmobile','edit')" required />
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=staff.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                $staffid=$_GET["pk_staff_id"];
                $sqldelete="DELETE FROM staff WHERE staff_id='$staffid'";
                $resultdelete=mysqli_query($con,$sqldelete) or die(mysqli_error($con));

                $sqldeleteuser="DELETE FROM user WHERE user_id='$staffid'";
                mysqli_query($con,$sqldeleteuser);

            if($resultdelete)
            {
                echo'<script>alert("Record Deleted Successfully");window.location.href="index.php?page=staff.php&option=view";</script>';
            }    
            }
       }
       ?>
</body>
<?php
    }
    else
    {//redirect to index.php if the user is not authorized to access this page
        echo'<script>window.location.href="index.php";</script>';
    }
        ?>
        