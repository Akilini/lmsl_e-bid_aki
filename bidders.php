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
    $bidder_id = mysqli_real_escape_string($con,$_POST["txtbidder_id"]);

    // Check duplicate bidder_id
    $check = mysqli_query($con,"SELECT bidder_id FROM bidders WHERE bidder_id='$bidder_id'");
    if(mysqli_num_rows($check) > 0)
    {
        echo "<script>alert('Bidder ID already exists');</script>";
        exit();
    }

    // FILE VARIABLES
    $file_business_registration_copy = "";
    $file_vat_registration_copy = "";

    //  BUSINESS FILE 
    if(!empty($_FILES["txtbusiness_registration_copy"]["name"]))
    {
        $business_dir = "bidders/business_registration_copy/";
        $upload_dir_fs1 = __DIR__ . "/" . $business_dir;

        $ext = strtolower(pathinfo($_FILES["txtbusiness_registration_copy"]["name"], PATHINFO_EXTENSION));
        $allowed = ["pdf","jpg","jpeg","png"];

        if(!in_array($ext,$allowed))
        {
            die("<script>alert('Invalid Business File');</script>");
        }

        if(!is_dir($upload_dir_fs1)) mkdir($upload_dir_fs1,0777,true);

        $file_business_registration_copy = $bidder_id."_BR.".$ext;

        move_uploaded_file($_FILES["txtbusiness_registration_copy"]["tmp_name"], 
                           $upload_dir_fs1.$file_business_registration_copy);
    }

    // ================= VAT FILE =================
    if(!empty($_FILES["txtvat_registration_copy"]["name"]))
    {
        $vat_dir = "bidders/vat_registration_copy/";
        $upload_dir_fs2 = __DIR__ . "/" . $vat_dir;

        $ext = strtolower(pathinfo($_FILES["txtvat_registration_copy"]["name"], PATHINFO_EXTENSION));
        $allowed = ["pdf","jpg","jpeg","png"];

        if(!in_array($ext,$allowed))
        {
            die("<script>alert('Invalid VAT File');</script>");
        }

        if(!is_dir($upload_dir_fs2)) mkdir($upload_dir_fs2,0777,true);

        $file_vat_registration_copy = $bidder_id."_VAT.".$ext;

        move_uploaded_file($_FILES["txtvat_registration_copy"]["tmp_name"], 
                           $upload_dir_fs2.$file_vat_registration_copy);
    }

    //  INSERT 
    $sqlinsert="INSERT INTO bidders 
    (bidder_id,company_name,address,mobile,email,website,land,fax,nature_of_the_business,
    business_registration_no,business_registration_copy,vat_registration_no,vat_registration_copy)
    VALUES(
    '$bidder_id',
    '".mysqli_real_escape_string($con,strtoupper($_POST["txtcompany_name"]))."',
    '".mysqli_real_escape_string($con,strtoupper($_POST["txtaddress"]))."',
    '".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
    '".mysqli_real_escape_string($con,$_POST["txtemail"])."',
    '".mysqli_real_escape_string($con,$_POST["txtwebsite"])."',
    '".mysqli_real_escape_string($con,$_POST["txtland"])."',
    '".mysqli_real_escape_string($con,$_POST["txtfax"])."',
    '".mysqli_real_escape_string($con,$_POST["txtnature_of_the_business"])."',
    '".mysqli_real_escape_string($con,$_POST["txtbusiness_registration_no"])."',
    '$file_business_registration_copy',
    '".mysqli_real_escape_string($con,$_POST["txtvat_registration_no"])."',
    '$file_vat_registration_copy')";

    mysqli_query($con,$sqlinsert) or die(mysqli_error($con));

    //  USER INSERT 
    $password = password_hash($_POST["txtemail"], PASSWORD_DEFAULT);

    $sqluser="INSERT INTO user(user_id,user_name,password,usertype,attempt,otp,status)
              VALUES('$bidder_id',
              '".mysqli_real_escape_string($con,$_POST["txtemail"])."',
              '$password','Bidders',0,0,'Active')";

    mysqli_query($con,$sqluser) or die(mysqli_error($con));

    echo '<script>alert("Inserted Successfully"); window.location="index.php?page=bidders.php&option=view";</script>';
}
// Insert code end

//Update code start
if(isset($_POST["btnupdate"]))
{
    $bidder_id = mysqli_real_escape_string($con,$_POST["txtbidder_id"]);
    // OLD FILES
    $old_business_file = isset($_POST["old_business_file"]) ? $_POST["old_business_file"] : "";
    $old_vat_file = isset($_POST["old_vat_file"]) ? $_POST["old_vat_file"] : "";

    // KEEP OLD FILES BY DEFAULT
    $file_business_registration_copy = $old_business_file;
    $file_vat_registration_copy = $old_vat_file;

    //  BUSINESS FILE 
    if(!empty($_FILES["txtbusiness_registration_copy"]["name"]))
    {
        $business_dir = "bidders/business_registration_copy/";
        $upload_dir_fs1 = __DIR__ . "/" . $business_dir;

        $ext = strtolower(pathinfo($_FILES["txtbusiness_registration_copy"]["name"], PATHINFO_EXTENSION));
        $allowed = ["pdf","jpg","jpeg","png"];

        if(!in_array($ext,$allowed))
        {
            die("<script>alert('Invalid Business File');</script>");
        }

        if(!is_dir($upload_dir_fs1)) mkdir($upload_dir_fs1,0777,true);

        // DELETE OLD FILE ONLY IF NEW FILE UPLOADED
        if(!empty($old_business_file) && file_exists($upload_dir_fs1.$old_business_file))
        {
            unlink($upload_dir_fs1.$old_business_file);
        }

        $file_business_registration_copy = $bidder_id."_BR.".$ext;

        move_uploaded_file($_FILES["txtbusiness_registration_copy"]["tmp_name"],
                           $upload_dir_fs1.$file_business_registration_copy);
    }

    //  VAT FILE 
    if(!empty($_FILES["txtvat_registration_copy"]["name"]))
    {
        $vat_dir = "bidders/vat_registration_copy/";
        $upload_dir_fs2 = __DIR__ . "/" . $vat_dir;

        $ext = strtolower(pathinfo($_FILES["txtvat_registration_copy"]["name"], PATHINFO_EXTENSION));
        $allowed = ["pdf","jpg","jpeg","png"];

        if(!in_array($ext,$allowed))
        {
            die("<script>alert('Invalid VAT File');</script>");
        }

        if(!is_dir($upload_dir_fs2)) mkdir($upload_dir_fs2,0777,true);

        if(!empty($old_vat_file) && file_exists($upload_dir_fs2.$old_vat_file))
        {
            unlink($upload_dir_fs2.$old_vat_file);
        }

        $file_vat_registration_copy = $bidder_id."_VAT.".$ext;

        move_uploaded_file($_FILES["txtvat_registration_copy"]["tmp_name"],
                           $upload_dir_fs2.$file_vat_registration_copy);
    }

    //  UPDATE 
    $sqlupdate="UPDATE bidders SET
        company_name='".mysqli_real_escape_string($con,strtoupper($_POST["txtcompany_name"]))."',
        address='".mysqli_real_escape_string($con,strtoupper($_POST["txtaddress"]))."',
        mobile='".mysqli_real_escape_string($con,$_POST["txtmobile"])."',
        email='".mysqli_real_escape_string($con,$_POST["txtemail"])."',
        website='".mysqli_real_escape_string($con,$_POST["txtwebsite"])."',
        land='".mysqli_real_escape_string($con,$_POST["txtland"])."',
        fax='".mysqli_real_escape_string($con,$_POST["txtfax"])."',
        nature_of_the_business='".mysqli_real_escape_string($con,$_POST["txtnature_of_the_business"])."',
        business_registration_no='".mysqli_real_escape_string($con,$_POST["txtbusiness_registration_no"])."',
        business_registration_copy='$file_business_registration_copy',
        vat_registration_no='".mysqli_real_escape_string($con,$_POST["txtvat_registration_no"])."',
        vat_registration_copy='$file_vat_registration_copy'
        WHERE bidder_id='$bidder_id'";

    mysqli_query($con,$sqlupdate) or die(mysqli_error($con));

    echo '<script>alert("Updated Successfully");window.location="index.php?page=bidders.php&option=view";</script>';
}   
// Update code end
?>
<script>
    function emailvalidation_check()
        {
            var email=document.getElementById("txtemail").value;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var response_value = xmlhttp.responseText.trim();
                    
                    if(response_value=="no")
                    {
                        emailvalidation();
                    }
                    else
                    {
                        alert("This email address already exists. Please enter a different email address.");
                        document.getElementById("txtemail").value = "";
                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=bidders_email&ajax_email=" + email, true);
            xmlhttp.send();
        }
</script>

<script>
    function internationalphone_check(mobiletxt, optionname)
        {
            var mobile=document.getElementById(mobiletxt).value;
            var bidderid=document.getElementById("txtbidder_id").value;
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var response_value = xmlhttp.responseText.trim();
                    
                    if(response_value=="no")
                    {
                        internationalphone(mobiletxt);
                    }
                    else
                    {
                        alert("This phone number already exists. Please enter a different phone number.");
                        document.getElementById(mobiletxt).value = "";
                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=bidder_mobile&ajax_mobile=" + mobile + "&ajax_bidders_id=" + bidderid + "&ajax_option=" + optionname, true);
            xmlhttp.send();
        }
</script>
<script>
    function business_registration_number_check(business_registration_numbertxt, optionname)
        {
            var business_registration_number=document.getElementById(business_registration_numbertxt).value;
            var bidderid=document.getElementById("txtbidder_id").value;
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
                        alert("This business registration number already exists. Please enter a different business registration number.");
                        document.getElementById(business_registration_numbertxt).value = "";
                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=bidder_business_registration_no&ajax_business_registration_no=" + business_registration_number + "&ajax_bidders_id=" + bidderid + "&ajax_option=" + optionname, true);
            xmlhttp.send();
        }
</script>
<script>
    function vat_registration_number_check(vat_registration_numbertxt, optionname)
        {
            var vat_registration_number=document.getElementById(vat_registration_numbertxt).value;
            var bidderid=document.getElementById("txtbidder_id").value;
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
                        alert("This vat registration number already exists. Please enter a different vat registration number.");
                        document.getElementById(vat_registration_numbertxt).value = "";
                    }
                    
                }
            };
            xmlhttp.open("GET", "ajaxpage.php?frompage=bidders_vat_registration_no&ajax_vat_registration_no=" + vat_registration_number + "&ajax_bidders_id=" + bidderid + "&ajax_option=" + optionname, true);
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
                                    <h1>Bidders Registration Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Bidder Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $sql_generatedid="SELECT bidder_id FROM bidders ORDER BY bidder_id DESC LIMIT 1";
                                                                
                                                                $result_generatedid=mysqli_query($con,$sql_generatedid) or die("SQL error in sql_generateid".mysqli_error($con));
                                                                if(mysqli_num_rows($result_generatedid)==1)
                                                                    {//for other than first time
                                                                    $row_generatedid=mysqli_fetch_assoc($result_generatedid);
                                                                    $generatedid=++$row_generatedid["bidder_id"];
                                                                    }
                                                                else
                                                                    { //for first time
                                                                    $generatedid="BR00000001";
                                                                    }
                                                                ?>
                                                                <input type="text" name="txtbidder_id" id="txtbidder_id" class="form-control" value="<?php echo $generatedid;?>" readonly required />
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
                                                                <label class="login2 pull-right pull-right-pro">Name of the Company </label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtcompany_name" id="txtcompany_name" class="form-control" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" onkeypress="return isTextKey(event)" required />
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
                                                                <input type="textarea" name="txtaddress" id="txtaddress" class="form-control" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Telephone General</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtland" id="txtland" class="form-control"  onblur="internationalphone('txtland')"  required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Fax</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtfax" id="txtfax" class="form-control"  onblur="internationalphone('txtfax')" required />
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
                                                                <input type="text" name="txtmobile" id="txtmobile" class="form-control"  onblur="internationalphone_check('txtmobile', 'add')" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Email</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtemail" id="txtemail" class="form-control" onblur="emailvalidation_check()" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Website</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtwebsite" id="txtwebsite" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Nature of the Business</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtnature_of_the_business" id="txtnature_of_the_business" class="form-control"  required />
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
                                                                <label class="login2 pull-right pull-right-pro">Business Registration Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbusiness_registration_no" id="txtbusiness_registration_no" class="form-control" onblur="business_registration_number_check('txtbusiness_registration_no', 'add')" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro"> Certified copy of Valid Business Registration </label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">                                                                
                                                                <input type="file" name="txtbusiness_registration_copy" id="txtbusiness_registration_copy" class="form-control" placeholder="no file selected">                                                                   
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
                                                                <label class="login2 pull-right pull-right-pro">VAT Registration Number</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtvat_registration_no" id="txtvat_registration_no" class="form-control" onblur="vat_registration_number_check('txtvat_registration_no', 'add')" required />
                                                            </div>
                                                            <!-- One Column End-->
                                                             <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Certified copy of Valid VAT Registration</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">                                                                
                                                                <input type="file" name="txtvat_registration_copy" id="txtvat_registration_copy" class="form-control" placeholder="no file selected">                                                                 
                                                            </div>
                                                            <!-- One Column End-->                                                                
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=bidders.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Bidder <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=bidders.php&option=add"><button class="btn btn-primary">Add Bidder</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="bidder_id ">Bidder ID</th>
                                                <th data-field="company_name" >Company Name</th>
                                                <th data-field="address" >Address</th>
                                                <th data-field="nature_of_the_business" >Nature of Business</th>
                                                <!-- <th data-field="business_registration_no">Business Registration Number</th> -->
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT bidder_id,company_name,address,nature_of_the_business,business_registration_no From bidders";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {
                                                    
                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["bidder_id"].'</td>';
                                                    echo'<td>'.$rowview["company_name"].'</td>';
                                                    echo'<td>'.$rowview["address"].'</td>';
                                                    echo'<td>'.$rowview["nature_of_the_business"].'</td>';
                                                    // echo'<td>'.$rowview["business_registration_no"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=bidders.php&option=fullview&pk_bidder_id='.$rowview["bidder_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    echo'<a href="index.php?page=bidders.php&option=edit&pk_bidder_id='.$rowview["bidder_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    //echo'<a onclick="return deletedata()" href="index.php?page=bidders.php&option=delete&pk_bidder_id='.$rowview["bidder_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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
                $bidderid= $_GET["pk_bidder_id"];

                $sqlview="SELECT * FROM bidders WHERE bidder_id='$bidderid'";
                $resultview=mysqli_query($con,$sqlview) or die(mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);

                $business_dir = "bidders/business_registration_copy/";
                $vat_dir = "bidders/vat_registration_copy/";

                ?>

                <div class="static-table-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                                                            <td><b>Bidder ID</b></td>
                                                            <td><?php echo $rowview["bidder_id"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Company Name</b></td>
                                                            <td><?php echo $rowview["company_name"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Address</b></td>
                                                            <td><?php echo $rowview["address"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Telephone</b></td>
                                                            <td><?php echo $rowview["land"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Fax</b></td>
                                                            <td><?php echo $rowview["fax"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Mobile</b></td>
                                                            <td><?php echo $rowview["mobile"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Email</b></td>
                                                            <td><?php echo $rowview["email"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Website</b></td>
                                                            <td><?php echo $rowview["website"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Nature of Business</b></td>
                                                            <td><?php echo $rowview["nature_of_the_business"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Business Registration No</b></td>
                                                            <td><?php echo $rowview["business_registration_no"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Certified copy of Valid Business Registration</b></td>                                                      
                                                            <td>
                                                                <?php
                                                                if (!empty($rowview["business_registration_copy"])) {
                                                                ?>
                                                                    <a href="<?php echo $business_dir . $rowview["business_registration_copy"]; ?>" target="_blank">
                                                                        <button class="btn btn-primary">View File</button>
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

                                                        <tr>
                                                            <td><b>VAT Registration No</b></td>
                                                            <td><?php echo $rowview["vat_registration_no"]; ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><b>Certified copy of Valid VAT Registration</b></td> 
                                                            <td>
                                                                <?php
                                                                if (!empty($rowview["vat_registration_copy"])) {
                                                                ?>
                                                                    <a href="<?php echo $vat_dir . $rowview["vat_registration_copy"]; ?>" target="_blank">
                                                                        <button class="btn btn-primary">View File</button>
                                                                    </a>
                                                                <?php
                                                                } else {
                                                                    echo "No File";
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>

                                                    </table>

                                                    <a href="index.php?page=bidders.php&option=view"><button class="btn btn-warning">Back</button></a>
                                                    <?php
                                                    if(!isset($_GET['print']))
                                                    {
                                                    echo '<a href="print.php?bidder_id='.$rowview['bidder_id'].'" target="_blank">
                                                    <button class="btn btn-primary" name="btnprint" type="button" id="btnprint">Print</button> </a>';
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
                $bidderid=$_GET["pk_bidder_id"];
                //$bidderid = mysqli_real_escape_string($con, $_GET["pk_bidder_id"]);

                $sqlview = "SELECT * FROM bidders WHERE bidder_id='$bidderid'";
                $resultview = mysqli_query($con, $sqlview) or die("SQL view error: " . mysqli_error($con));
                $rowview = mysqli_fetch_assoc($resultview);

                $business_dir = "bidders/business_registration_copy/";
                $vat_dir = "bidders/vat_registration_copy/";
                ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Bidder Edit Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Bidder Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtbidder_id" id="txtbidder_id" class="form-control" value="<?php echo $rowview['bidder_id']; ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Name of the Company</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtcompany_name" id="txtcompany_name" class="form-control" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" value="<?php echo $rowview['company_name']; ?>" required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            </div>
                                                        </div>
                                                        <!-- One Row End--> 
                                                        <!-- One Row Start-->
                                                        <div class="form-group-inner">
                                                            <div class="row">  
                                                                <!-- One Column Start-->
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-1">
                                                                    <label class="login2 pull-right pull-right-pro">Address</label>
                                                                </div>
                                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                    <input type="text" name="txtaddress" id="txtaddress" class="form-control" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase();" value="<?php echo $rowview['address']; ?>" required />
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
                                                                    <label class="login2 pull-right pull-right-pro">Telephone General</label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <input type="text" name="txtland" id="txtland" class="form-control" value="<?php echo $rowview['land']; ?>" required />
                                                                </div>
                                                                <!-- One Column End-->  
                                                                <!-- One Column Start-->
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <label class="login2 pull-right pull-right-pro">Fax</label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <input type="text" name="txtfax" id="txtfax" class="form-control" value="<?php echo $rowview['fax']; ?>" required />
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
                                                                    <input type="text" name="txtmobile" id="txtmobile" class="form-control" value="<?php echo $rowview['mobile']; ?>" onblur="internationalphone_check('txtmobile', 'edit')" required />
                                                                </div>
                                                                <!-- One Column End-->  
                                                                <!-- One Column Start-->
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <label class="login2 pull-right pull-right-pro">Email</label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <input type="text" name="txtemail" id="txtemail" class="form-control" value="<?php echo $rowview['email']; ?>" required />
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
                                                                    <label class="login2 pull-right pull-right-pro">Website</label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <input type="text" name="txtwebsite" id="txtwebsite" class="form-control" value="<?php echo $rowview['website']; ?>" required />
                                                                </div>
                                                                <!-- One Column End-->  
                                                                <!-- One Column Start-->
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <label class="login2 pull-right pull-right-pro">Nature of the Business</label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <input type="text" name="txtnature_of_the_business" id="txtnature_of_the_business" class="form-control" value="<?php echo $rowview['nature_of_the_business']; ?>" required />
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
                                                                    <label class="login2 pull-right pull-right-pro">Business Registration Number</label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <input type="text" name="txtbusiness_registration_no" id="txtbusiness_registration_no" class="form-control" value="<?php echo $rowview['business_registration_no']; ?>" onblur="business_registration_number_check('txtbusiness_registration_no', 'edit')" required />
                                                                </div>
                                                                <!-- One Column End-->  
                                                                <!-- One Column Start-->
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <label class="login2 pull-right pull-right-pro">Certified copy of Valid Business Registration</label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <input type="file" name="txtbusiness_registration_copy" id="txtbusiness_registration_copy" class="form-control">
                                                                    <input type="hidden" name="old_business_file" value="<?php echo $rowview['business_registration_copy']; ?>" class="form-control">
                                                                    <br>
                                                                    <?php
                                                                    if(!empty($rowview["business_registration_copy"])) { ?>
                                                                        <a href="<?php echo $business_dir.$rowview["business_registration_copy"]; ?>" target="_blank">View File</a>
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
                                                        <!-- One Row Start-->
                                                        <div class="form-group-inner">
                                                            <div class="row">  
                                                                <!-- One Column Start-->
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <label class="login2 pull-right pull-right-pro">VAT Registration Number</label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <input type="text" name="txtvat_registration_no" id="txtvat_registration_no" class="form-control" value="<?php echo $rowview['vat_registration_no']; ?>" onblur="vat_registration_number_check('txtvat_registration_no', 'edit')" required />
                                                                </div>
                                                                <!-- One Column End-->  
                                                                <!-- One Column Start-->
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <label class="login2 pull-right pull-right-pro">Certified copy of Valid VAT Registration</label>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                    <input type="file" name="txtvat_registration_copy" id="txtvat_registration_copy" class="form-control">
                                                                    <input type="hidden" name="old_vat_file" value="<?php echo $rowview['vat_registration_copy']; ?>" class="form-control">
                                                                    <br>
                                                                    <?php
                                                                    if(!empty($rowview["vat_registration_copy"])) { ?>
                                                                    <a href="<?php echo $vat_dir.$rowview["vat_registration_copy"]; ?>" target="_blank">
                                                                    View File
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
                                                                <a href="index.php?page=bidders.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                
            }

       }
       ?>
</body>