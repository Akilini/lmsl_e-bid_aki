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
        $sqlinsert="INSERT INTO role (role_id,name)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtroleid"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtname"])."')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=role.php&option=add" </script>';
            }
    }
// Insert code end
//Update code start
if(isset($_POST["btnupdate"]))
    {
    $role_id=$_POST['txtroleid'];
    $sqlupdate="UPDATE role SET
                name='".mysqli_real_escape_string($con,$_POST["txtname"])."'
                WHERE role_id='$role_id'";

    $resultupdate=mysqli_query($con,$sqlupdate) or die(mysqli_error($con));

    echo'<script>alert("Record Updated Successfully");window.location.href="index.php?page=role.php&option=view"</script>';
    }
// Update code end
?>
<script>
    function rolename_check(rolenametxt, optionname)
    {
        var name = document.getElementById(rolenametxt).value;
        var roleid = document.getElementById("txtroleid").value;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() 
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
            {
                var response_value = xmlhttp.responseText.trim();

                if(response_value == "no")
                {
                    
                }
                else
                {
                    alert("This role name already exists. Please enter a different role name.");
                    document.getElementById(rolenametxt).value = "";
                }
            }
        };

        xmlhttp.open("GET", "ajaxpage.php?frompage=role_name&ajax_name=" + name + "&ajax_role_id=" + roleid + "&ajax_option=" + optionname, true);
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
                                    <h1>Role Add Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Role Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $sql_generatedid="SELECT role_id FROM role ORDER BY role_id DESC LIMIT 1";
                                                                $result_generatedid=mysqli_query($con,$sql_generatedid) or die("SQL error in sql_generateid".mysqli_error($con));
                                                                if(mysqli_num_rows($result_generatedid)==1)
                                                                    {//for other than first time
                                                                    $row_generatedid=mysqli_fetch_assoc($result_generatedid);
                                                                    $generatedid=++$row_generatedid["role_id"];
                                                                    }
                                                                else
                                                                    { //for first time
                                                                    $generatedid="ROL01";
                                                                    }
                                                                ?>
                                                                <input type="text" name="txtroleid" id="txtroleid" class="form-control" value="<?php echo $generatedid; ?>" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                                 <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Role Name</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtname" id="txtname" class="form-control" onkeypress="return isTextKey(event)" onblur="rolename_check('txtname', 'add')" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->    

                                                

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=role.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
                                                                <input type="reset" name="btnclear" id="btnclear" class="btn btn-danger" value="Clear" />
                                                                <input type="submit" name="btnsave" id="btnsave" class="btn btn-success" value="Save" /> 
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
                                    <h1>Role <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=role.php&option=add"><button class="btn btn-primary">Add Role</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="role_id ">Staff ID</th>
                                                <th data-field="name" >Name</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT role_id ,name From role";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["role_id"].'</td>';
                                                    echo'<td>'.$rowview["name"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=role.php&option=fullview&pk_role_id='.$rowview["role_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    echo'<a href="index.php?page=role.php&option=edit&pk_role_id='.$rowview["role_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=role.php&option=delete&pk_role_id='.$rowview["role_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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
                $role_id=$_GET["pk_role_id"];

                $sqlview="SELECT * FROM role WHERE role_id='$role_id'";
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
                                                <h1>Role Table</h1>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table">                                        
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Role ID</b></td>
                                                            <td><?php echo $rowview["role_id"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Role Name</b></td>
                                                            <td><?php echo $rowview["name"]; ?></td>
                                                        </tr>

                                                        
                                                    </tbody>
                                                    
                                                </table> 
                                                <a href="index.php?page=role.php&option=view">
                                                <button class="btn btn-warning">Back</button>
                                                </a>     
                                                <?php
                                                if(!isset($_GET['print']))
                                                {
                                                echo '<a href="print.php?role_id='.$rowview['role_id'].'" target="_blank">
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
                $role_id=$_GET["pk_role_id"];

                $sqlview="SELECT * FROM role WHERE role_id='$role_id'";
                $resultview=mysqli_query($con,$sqlview) or die(mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);

                
                ?>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Role Edit Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Role Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtroleid" id="txtroleid" class="form-control" value="<?php echo $rowview['role_id']; ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Role Name</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtname" id="txtname" class="form-control" value="<?php echo $rowview['name']; ?>"  onblur="rolename_check('txtname','edit')" required />
                                                            </div>
                                                            <!-- One Column End-->                                                          
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=role.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
            $role_id=$_GET["pk_role_id"];
                $sqldelete="DELETE FROM role WHERE role_id='$role_id'";
                $resultdelete=mysqli_query($con,$sqldelete) or die(mysqli_error($con));

            if($resultdelete)
            {
                echo'<script>alert("Record Deleted Successfully");window.location.href="index.php?page=role.php&option=view";</script>';
            }    
            }

       }
       ?>
</body>