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

$upload_dir = "product/briefly_specification_doc/";
$upload_dir_fs = rtrim(__DIR__, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace("/", DIRECTORY_SEPARATOR, $upload_dir);

//Insert code start
if(isset($_POST["btnsave"]))
    {
        $product_id = $_POST["txtproduct_id"];
        $file_briefly_specification_doc = "";
        
        if(isset($_FILES["txtbriefly_specification_doc"]) && $_FILES["txtbriefly_specification_doc"]["error"] == 0)
        {
            $original_file = basename($_FILES["txtbriefly_specification_doc"]["name"]);
            $tmp_name = $_FILES["txtbriefly_specification_doc"]["tmp_name"];

            // File extension
            $ext = strtolower(pathinfo($original_file, PATHINFO_EXTENSION));
            $allowed = array("pdf","jpg","jpeg","png");

            // Validate file type
            if(!in_array($ext,$allowed))
            {
                echo "<script>alert('Invalid file type');</script>";
                exit();
            }

            if(!is_dir($upload_dir_fs))
            {
                mkdir($upload_dir_fs, 0777, true);
            }

            $file_briefly_specification_doc = $product_id . "." . $ext;

            // If already exists, delete (safety)
            if(file_exists($upload_dir_fs.$file_briefly_specification_doc))
            {
                unlink($upload_dir_fs.$file_briefly_specification_doc);
            }

            // Move file
            if(!move_uploaded_file($tmp_name, $upload_dir_fs.$file_briefly_specification_doc))
            {
                echo "<script>alert('File upload failed');</script>";
                exit();
            }
        }

        $sqlinsert="INSERT INTO product (product_id,name,specification,briefly_specification_doc)
                        VALUES('".mysqli_real_escape_string($con,$_POST["txtproduct_id"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtname"])."',
                        '".mysqli_real_escape_string($con,$_POST["txtspecification"])."',
                        '$file_briefly_specification_doc')"; 
        $insertresult=mysqli_query($con,$sqlinsert) or die("SQL insert error".mysqli_error($con));
        if($insertresult)
            {
                echo'<script> alert(" Record inserted successfully."); window.location.href="index.php?page=product.php&option=view" </script>';
            }
    }
// Insert code end

//Update code start
if(isset($_POST["btnupdate"]))
    
{
    $product_id = mysqli_real_escape_string($con, $_POST["txtproduct_id"]);
    $allowed = array("pdf","jpg","jpeg","png");

    // Step 1: Get existing file
    $sqlget = "SELECT briefly_specification_doc FROM product WHERE product_id='$product_id'";
    $resultget = mysqli_query($con, $sqlget);
    $rowget = mysqli_fetch_assoc($resultget);
    $old_file = $rowget["briefly_specification_doc"];

    // Start update query
    $sqlupdate="UPDATE product SET
        name='".mysqli_real_escape_string($con,$_POST["txtname"])."',
        specification='".mysqli_real_escape_string($con,$_POST["txtspecification"])."'";

    // Step 2: Check new file upload
    if(isset($_FILES["txtbriefly_specification_doc"]) && $_FILES["txtbriefly_specification_doc"]["name"] != "")
    {
        $original_file = $_FILES["txtbriefly_specification_doc"]["name"];
        $tmp_name = $_FILES["txtbriefly_specification_doc"]["tmp_name"];

        $ext = strtolower(pathinfo($original_file, PATHINFO_EXTENSION));
       
        if(!in_array($ext,$allowed))
        {
            echo "<script>alert('Invalid file type');</script>";
            exit();
        }

        if(!is_dir($upload_dir_fs))
        {
            mkdir($upload_dir_fs, 0777, true);
        }

        // ✅ SAME NAME (overwrite)
        $new_file = $product_id . "." . $ext;

        // Delete old file if different extension
        if(!empty($old_file) && file_exists($upload_dir_fs.$old_file))
        {
            unlink($upload_dir_fs.$old_file);
        }

        // Step 3: Upload new file
        if(move_uploaded_file($tmp_name, $upload_dir_fs.$new_file))
        {
            $sqlupdate .= ", briefly_specification_doc='$new_file'";
        }
        else
        {
            echo "<script>alert('File upload failed');</script>";
            exit();
        }
    }

        $sqlupdate .= " WHERE product_id='$product_id'";

        $resultupdate=mysqli_query($con,$sqlupdate) or die("SQL user update error: " .mysqli_error($con));

        if($resultupdate)
        {
            echo '<script>alert("Record Updated Successfully");window.location.href="index.php?page=product.php&option=view";</script>';
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
                                    <h1>Product Add Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Product Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <?php
                                                                $sql_generatedid="SELECT product_id FROM product ORDER BY product_id DESC LIMIT 1";
                                                                $result_generatedid=mysqli_query($con,$sql_generatedid) or die("SQL error in sql_generateid".mysqli_error($con));
                                                                if(mysqli_num_rows($result_generatedid)==1)
                                                                    {//for other than first time
                                                                    $row_generatedid=mysqli_fetch_assoc($result_generatedid);
                                                                    $generatedid=++$row_generatedid["product_id"];
                                                                    }
                                                                else
                                                                    { //for first time
                                                                    $generatedid="P000000001";
                                                                    }
                                                                ?>
                                                                <input type="text" name="txtproduct_id" id="txtproduct_id" class="form-control" value="<?php echo $generatedid; ?>" readonly required />
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
                                                                <label class="login2 pull-right pull-right-pro">Product Name</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
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
                                                                <label class="login2 pull-right pull-right-pro">Specification</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="txtspecification" id="txtspecification" class="form-control" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Briefly Specification Document</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-3 col-sm-3 col-xs-12">                                                                
                                                                <input type="file" name="txtbriefly_specification_doc" id="txtbriefly_specification_doc" class="form-control" placeholder="no file selected">                                                                 
                                                            </div>
                                                            <!-- One Column End-->                                                              
                                                        </div>
                                                    </div>
                                                <!-- One Row End-->

                                                <!-- Button Start--> 
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <center>
                                                                <a href="index.php?page=product.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                                    <h1>Product <span class="table-project-n">Data</span> Table</h1>
                                </div>
                            </div>
                            <div class="sparkline13-graph">
                                <div class="datatable-dashv1-list custom-datatable-overright">   
                                    <a href="index.php?page=product.php&option=add"><button class="btn btn-primary">Add Product</button></a>                                 
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="false" data-show-pagination-switch="false" data-show-refresh="false" data-key-events="true" data-show-toggle="false" data-resizable="true" data-cookie="true"
                                        data-cookie-id-table="saveId" data-show-export="false" data-click-to-select="false" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="product_id">Product ID</th>
                                                <th data-field="name" >Product Name</th>
                                                <th data-field="specification" >Specification</th>
                                                <th data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                            $sqlview="SELECT product_id,name,specification From product";
                                            $resultview=mysqli_query($con,$sqlview) or die("SQL view error".mysqli_error($con));
                                            while($rowview=mysqli_fetch_assoc($resultview))
                                                {                                                    
                                                    echo'<tr>';
                                                    echo'<td>'.$rowview["product_id"].'</td>';
                                                    echo'<td>'.$rowview["name"].'</td>';
                                                    echo'<td>'.$rowview["specification"].'</td>';
                                                    echo'<td>';
                                                    echo'<a href="index.php?page=product.php&option=fullview&pk_product_id='.$rowview["product_id"].'"><button class="btn btn-success">View</button></a> ';
                                                    echo'<a href="index.php?page=product.php&option=edit&pk_product_id='.$rowview["product_id"].'"><button class="btn btn-info">Edit</button></a> ';
                                                    echo'<a onclick="return deletedata()" href="index.php?page=product.php&option=delete&pk_product_id='.$rowview["product_id"].'"><button class="btn btn-danger">Delete</button></a> ';
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
                $product_id=$_GET["pk_product_id"];

                $sqlview="SELECT * FROM product WHERE product_id='$product_id'";
                $resultview=mysqli_query($con,$sqlview) or die("SQL error".mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);
                ?>
                    <div class="static-table-area">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="sparkline8-list">
                                        <div class="sparkline8-hd">
                                            <div class="main-sparkline8-hd">
                                                <h1>Product Table</h1>
                                            </div>
                                        </div>
                                        <div class="sparkline8-graph">
                                            <div class="static-table-list">
                                                <table class="table">                                        
                                                    <tbody>
                                                        <tr>
                                                            <td><b>Product ID</b></td>
                                                            <td><?php echo $rowview["product_id"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Product Name</b></td>
                                                            <td><?php echo $rowview["name"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Specification</b></td>
                                                            <td><?php echo $rowview["specification"]; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Briefly Specification Document</b></td>
                                                            <td>
                                                                <?php
                                                                if(!empty($rowview["briefly_specification_doc"]))
                                                                {
                                                                    ?>
                                                                    <a href="<?php echo $upload_dir . rawurlencode($rowview["briefly_specification_doc"]); ?>" target="_blank">
                                                                        <button class="btn btn-primary" type="button">View File</button>
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
                                                    </tbody>
                                                    
                                                </table> 
                                                <a href="index.php?page=product.php&option=view">
                                                <button class="btn btn-warning">Back</button>
                                                </a>     
                                                <?php
                                                if(!isset($_GET['print']))
                                                {
                                                echo '<a href="print.php?product_id='.$rowview['product_id'].'" target="_blank">
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
                $product_id=$_GET["pk_product_id"];

                $sqlview="SELECT * FROM product WHERE product_id='$product_id'";
                $resultview=mysqli_query($con,$sqlview) or die(mysqli_error($con));
                $rowview=mysqli_fetch_assoc($resultview);
               
                ?>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Product Edit Form</h1>
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
                                                                <label class="login2 pull-right pull-right-pro">Product Id</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtproduct_id" id="txtproduct_id" class="form-control" value="<?php echo $rowview['product_id']; ?>" readonly required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Product Name</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtname" id="txtname" class="form-control" value="<?php echo $rowview['name']; ?>" required />
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
                                                                <label class="login2 pull-right pull-right-pro">Specification</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <input type="text" name="txtspecification" id="txtspecification" class="form-control" value="<?php echo $rowview['specification']; ?>"  required />
                                                            </div>
                                                            <!-- One Column End--> 
                                                            <!-- One Column Start-->
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Briefly Specification Document</label>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                
                                                                <input type="file" name="txtbriefly_specification_doc" id="txtbriefly_specification_doc" class="form-control">
                                                                <br>
                                                                <!-- Show existing file -->
                                                                <?php
                                                                if(!empty($rowview['briefly_specification_doc']))
                                                                {
                                                                    ?>
                                                                    Current File:
                                                                    <a href="<?php echo $upload_dir . rawurlencode($rowview['briefly_specification_doc']); ?>" target="_blank">
                                                                        <?php echo "View File"; ?>
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
                                                                <a href="index.php?page=product.php&option=view"><input type="button" name="btngoback" id="btngoback" class="btn btn-primary" value="Go Back" /></a> 
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
                $product_id = $_GET["pk_product_id"];

                // Get file name first
                $sqlget="SELECT briefly_specification_doc FROM product WHERE product_id='$product_id'";
                $resultget=mysqli_query($con,$sqlget);
                $rowget=mysqli_fetch_assoc($resultget);

                $file = $rowget["briefly_specification_doc"];

                // Delete database record
                $sqldelete="DELETE FROM product WHERE product_id='$product_id'";
                $resultdelete=mysqli_query($con,$sqldelete) or die(mysqli_error($con));

                // Delete file from folder
                if($file != "" && file_exists($upload_dir_fs.$file))
                {
                    unlink($upload_dir_fs.$file);
                }

                if($resultdelete)
                {
                    echo '<script>alert("Record Deleted Successfully");window.location.href="index.php?page=product.php&option=view";</script>';
                }
            }
       }
       ?>
</body>