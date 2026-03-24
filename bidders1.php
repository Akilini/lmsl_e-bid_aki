<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["login_usertype"])) {
    $system_usertype  = $_SESSION["login_usertype"];
    $system_user_id   = $_SESSION["login_user_id"];
    $system_user_name = $_SESSION["login_user_name"];
} else {
    $system_usertype = "Guest";
}

include("config.php");

/* -----------------------------------------------------------
   COMMON PATHS
----------------------------------------------------------- */
$business_dir = "bidders/business_registration_copy/";
$vat_dir      = "bidders/vat_registration_copy/";

if (!is_dir($business_dir)) {
    mkdir($business_dir, 0777, true);
}
if (!is_dir($vat_dir)) {
    mkdir($vat_dir, 0777, true);
}

/* -----------------------------------------------------------
   FUNCTION : SAFE FILE NAME
----------------------------------------------------------- */
function makeFileName($originalName)
{
    $originalName = preg_replace("/[^A-Za-z0-9._-]/", "_", $originalName);
    return time() . "_" . $originalName;
}

/* -----------------------------------------------------------
   INSERT CODE START
----------------------------------------------------------- */
if (isset($_POST["btnsave"])) {

    $bidder_id                  = mysqli_real_escape_string($con, $_POST["txtbidder_id"]);
    $company_name               = mysqli_real_escape_string($con, $_POST["txtcompany_name"]);
    $address                    = mysqli_real_escape_string($con, $_POST["txtaddress"]);
    $mobile                     = mysqli_real_escape_string($con, $_POST["txtmobile"]);
    $email                      = mysqli_real_escape_string($con, $_POST["txtemail"]);
    $website                    = mysqli_real_escape_string($con, $_POST["txtwebsite"]);
    $land                       = mysqli_real_escape_string($con, $_POST["txtland"]);
    $fax                        = mysqli_real_escape_string($con, $_POST["txtfax"]);
    $nature_of_the_business     = mysqli_real_escape_string($con, $_POST["txtnature_of_the_business"]);
    $business_registration_no   = mysqli_real_escape_string($con, $_POST["txtbusiness_registration_no"]);
    $vat_registration_no        = mysqli_real_escape_string($con, $_POST["txtvat_registration_no"]);

    $business_registration_copy = "";
    $vat_registration_copy      = "";

    // Upload Business Registration Copy
    if (isset($_FILES["txtbusiness_registration_copy"]) && $_FILES["txtbusiness_registration_copy"]["error"] == 0) {
        $business_registration_copy = makeFileName(basename($_FILES["txtbusiness_registration_copy"]["name"]));
        $target_file1 = $business_dir . $business_registration_copy;

        if (!move_uploaded_file($_FILES["txtbusiness_registration_copy"]["tmp_name"], $target_file1)) {
            $business_registration_copy = "";
        }
    }

    // Upload VAT Registration Copy
    if (isset($_FILES["txtvat_registration_copy"]) && $_FILES["txtvat_registration_copy"]["error"] == 0) {
        $vat_registration_copy = makeFileName(basename($_FILES["txtvat_registration_copy"]["name"]));
        $target_file2 = $vat_dir . $vat_registration_copy;

        if (!move_uploaded_file($_FILES["txtvat_registration_copy"]["tmp_name"], $target_file2)) {
            $vat_registration_copy = "";
        }
    }

    $sqlinsert = "INSERT INTO bidders
    (
        bidder_id,
        company_name,
        address,
        mobile,
        email,
        website,
        land,
        fax,
        nature_of_the_business,
        business_registration_no,
        business_registration_copy,
        vat_registration_no,
        vat_registration_copy
    )
    VALUES
    (
        '$bidder_id',
        '$company_name',
        '$address',
        '$mobile',
        '$email',
        '$website',
        '$land',
        '$fax',
        '$nature_of_the_business',
        '$business_registration_no',
        '$business_registration_copy',
        '$vat_registration_no',
        '$vat_registration_copy'
    )";

    $insertresult = mysqli_query($con, $sqlinsert) or die("SQL insert error: " . mysqli_error($con));

    // Insert into user
    $password = md5($_POST["txtemail"]);
    $sqlinsertlogin = "INSERT INTO user
    (
        user_id,
        user_name,
        password,
        usertype,
        attempt,
        otp,
        status
    )
    VALUES
    (
        '$bidder_id',
        '$email',
        '" . mysqli_real_escape_string($con, $password) . "',
        'Bidders',
        '0',
        '0',
        'Active'
    )";

    $insertloginresult = mysqli_query($con, $sqlinsertlogin) or die("SQL insert user error: " . mysqli_error($con));

    if ($insertresult) {
        echo '<script>
                alert("Record inserted successfully.");
                window.location.href="index.php?page=bidders.php&option=view";
              </script>';
    }
}
/* -----------------------------------------------------------
   INSERT CODE END
----------------------------------------------------------- */


/* -----------------------------------------------------------
   UPDATE CODE START
----------------------------------------------------------- */
if (isset($_POST["btnupdate"])) {

    $bidder_id = mysqli_real_escape_string($con, $_POST["txtbidder_id"]);

    $company_name             = mysqli_real_escape_string($con, $_POST["txtcompany_name"]);
    $address                  = mysqli_real_escape_string($con, $_POST["txtaddress"]);
    $mobile                   = mysqli_real_escape_string($con, $_POST["txtmobile"]);
    $email                    = mysqli_real_escape_string($con, $_POST["txtemail"]);
    $website                  = mysqli_real_escape_string($con, $_POST["txtwebsite"]);
    $land                     = mysqli_real_escape_string($con, $_POST["txtland"]);
    $fax                      = mysqli_real_escape_string($con, $_POST["txtfax"]);
    $nature_of_the_business   = mysqli_real_escape_string($con, $_POST["txtnature_of_the_business"]);
    $business_registration_no = mysqli_real_escape_string($con, $_POST["txtbusiness_registration_no"]);
    $vat_registration_no      = mysqli_real_escape_string($con, $_POST["txtvat_registration_no"]);

    // Get existing files
    $sqlexisting = "SELECT business_registration_copy, vat_registration_copy FROM bidders WHERE bidder_id='$bidder_id'";
    $resultexisting = mysqli_query($con, $sqlexisting) or die("SQL existing file error: " . mysqli_error($con));
    $rowexisting = mysqli_fetch_assoc($resultexisting);

    $business_registration_copy = $rowexisting["business_registration_copy"];
    $vat_registration_copy      = $rowexisting["vat_registration_copy"];

    // Replace Business Registration Copy
    if (isset($_FILES["txtbusiness_registration_copy"]) && $_FILES["txtbusiness_registration_copy"]["error"] == 0 && $_FILES["txtbusiness_registration_copy"]["name"] != "") {

        $new_business_file = makeFileName(basename($_FILES["txtbusiness_registration_copy"]["name"]));
        $target_file1 = $business_dir . $new_business_file;

        if (move_uploaded_file($_FILES["txtbusiness_registration_copy"]["tmp_name"], $target_file1)) {

            if (!empty($business_registration_copy) && file_exists($business_dir . $business_registration_copy)) {
                unlink($business_dir . $business_registration_copy);
            }

            $business_registration_copy = $new_business_file;
        }
    }

    // Replace VAT Registration Copy
    if (isset($_FILES["txtvat_registration_copy"]) && $_FILES["txtvat_registration_copy"]["error"] == 0 && $_FILES["txtvat_registration_copy"]["name"] != "") {

        $new_vat_file = makeFileName(basename($_FILES["txtvat_registration_copy"]["name"]));
        $target_file2 = $vat_dir . $new_vat_file;

        if (move_uploaded_file($_FILES["txtvat_registration_copy"]["tmp_name"], $target_file2)) {

            if (!empty($vat_registration_copy) && file_exists($vat_dir . $vat_registration_copy)) {
                unlink($vat_dir . $vat_registration_copy);
            }

            $vat_registration_copy = $new_vat_file;
        }
    }

    $sqlupdate = "UPDATE bidders SET
        company_name='$company_name',
        address='$address',
        mobile='$mobile',
        email='$email',
        website='$website',
        land='$land',
        fax='$fax',
        nature_of_the_business='$nature_of_the_business',
        business_registration_no='$business_registration_no',
        business_registration_copy='$business_registration_copy',
        vat_registration_no='$vat_registration_no',
        vat_registration_copy='$vat_registration_copy'
        WHERE bidder_id='$bidder_id'";

    $resultupdate = mysqli_query($con, $sqlupdate) or die("SQL update error: " . mysqli_error($con));

    // Update user table
    $sqlloginupdate = "UPDATE user SET
        user_name='$email'
        WHERE user_id='$bidder_id'";

    $updateloginresult = mysqli_query($con, $sqlloginupdate) or die("SQL user update error: " . mysqli_error($con));

    if ($resultupdate) {
        echo '<script>
                alert("Record Updated Successfully");
                window.location.href="index.php?page=bidders.php&option=view";
              </script>';
    }
}
/* -----------------------------------------------------------
   UPDATE CODE END
----------------------------------------------------------- */


/* -----------------------------------------------------------
   DELETE CODE START
----------------------------------------------------------- */
if (isset($_GET["option"]) && $_GET["option"] == "delete" && isset($_GET["pk_bidder_id"])) {

    $bidder_id = mysqli_real_escape_string($con, $_GET["pk_bidder_id"]);

    $sqlfile = "SELECT business_registration_copy, vat_registration_copy FROM bidders WHERE bidder_id='$bidder_id'";
    $resultfile = mysqli_query($con, $sqlfile) or die("SQL file fetch error: " . mysqli_error($con));
    $rowfile = mysqli_fetch_assoc($resultfile);

    if (!empty($rowfile["business_registration_copy"]) && file_exists($business_dir . $rowfile["business_registration_copy"])) {
        unlink($business_dir . $rowfile["business_registration_copy"]);
    }

    if (!empty($rowfile["vat_registration_copy"]) && file_exists($vat_dir . $rowfile["vat_registration_copy"])) {
        unlink($vat_dir . $rowfile["vat_registration_copy"]);
    }

    $sqldelete = "DELETE FROM bidders WHERE bidder_id='$bidder_id'";
    mysqli_query($con, $sqldelete) or die("SQL delete error: " . mysqli_error($con));

    $sqldeleteuser = "DELETE FROM user WHERE user_id='$bidder_id'";
    mysqli_query($con, $sqldeleteuser) or die("SQL user delete error: " . mysqli_error($con));

    echo '<script>
            alert("Record deleted successfully.");
            window.location.href="index.php?page=bidders.php&option=view";
          </script>';
}
/* -----------------------------------------------------------
   DELETE CODE END
----------------------------------------------------------- */
?>

<body>
<?php
if (isset($_GET["option"])) {

    /* -----------------------------------------------------------
       ADD
    ----------------------------------------------------------- */
    if ($_GET["option"] == "add") {
?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <h1>Bidders Add Form</h1>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                    <form action="" method="POST" enctype="multipart/form-data">

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Bidder Id</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <?php
                                                    $sql_generatedid = "SELECT bidder_id FROM bidders ORDER BY bidder_id DESC LIMIT 1";
                                                    $result_generatedid = mysqli_query($con, $sql_generatedid) or die("SQL error in sql_generateid: " . mysqli_error($con));

                                                    if (mysqli_num_rows($result_generatedid) == 1) {
                                                        $row_generatedid = mysqli_fetch_assoc($result_generatedid);
                                                        $generatedid = ++$row_generatedid["bidder_id"];
                                                    } else {
                                                        $generatedid = "PP00000001";
                                                    }
                                                    ?>
                                                    <input type="text" name="txtbidder_id" id="txtbidder_id" class="form-control" value="<?php echo $generatedid; ?>" readonly required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Name of the Company</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <input type="text" name="txtcompany_name" id="txtcompany_name" class="form-control" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Address</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <textarea name="txtaddress" id="txtaddress" class="form-control" required></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Telephone General</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtland" id="txtland" class="form-control" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Fax</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtfax" id="txtfax" class="form-control" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Mobile</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtmobile" id="txtmobile" class="form-control" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Email</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <input type="email" name="txtemail" id="txtemail" class="form-control" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Website</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <input type="text" name="txtwebsite" id="txtwebsite" class="form-control" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Nature of the Business</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <input type="text" name="txtnature_of_the_business" id="txtnature_of_the_business" class="form-control" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Business Registration Number</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtbusiness_registration_no" id="txtbusiness_registration_no" class="form-control" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Certified copy of Valid Business Registration</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="file" name="txtbusiness_registration_copy" id="txtbusiness_registration_copy" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">VAT Registration Number</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtvat_registration_no" id="txtvat_registration_no" class="form-control" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Certified copy of Valid VAT Registration</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="file" name="txtvat_registration_copy" id="txtvat_registration_copy" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <center>
                                                    <a href="index.php?page=bidders.php&option=view"><input type="button" class="btn btn-primary" value="Go Back" /></a>
                                                    <input type="reset" class="btn btn-danger" value="Clear" />
                                                    <input type="submit" name="btnsave" class="btn btn-success" value="Save" />
                                                </center>
                                            </div>
                                        </div>

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

    /* -----------------------------------------------------------
       VIEW
    ----------------------------------------------------------- */
    else if ($_GET["option"] == "view") {
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

                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" class="table">
                                    <thead>
                                        <tr>
                                            <th>Bidder ID</th>
                                            <th>Company Name</th>
                                            <th>Address</th>
                                            <th>Nature of Business</th>
                                            <th>Business Registration Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sqlview = "SELECT bidder_id, company_name, address, nature_of_the_business, business_registration_no FROM bidders";
                                        $resultview = mysqli_query($con, $sqlview) or die("SQL view error: " . mysqli_error($con));

                                        while ($rowview = mysqli_fetch_assoc($resultview)) {
                                            echo '<tr>';
                                            echo '<td>' . $rowview["bidder_id"] . '</td>';
                                            echo '<td>' . $rowview["company_name"] . '</td>';
                                            echo '<td>' . $rowview["address"] . '</td>';
                                            echo '<td>' . $rowview["nature_of_the_business"] . '</td>';
                                            echo '<td>' . $rowview["business_registration_no"] . '</td>';
                                            echo '<td>';
                                            echo '<a href="index.php?page=bidders.php&option=fullview&pk_bidder_id=' . $rowview["bidder_id"] . '"><button class="btn btn-success">View</button></a> ';
                                            echo '<a href="index.php?page=bidders.php&option=edit&pk_bidder_id=' . $rowview["bidder_id"] . '"><button class="btn btn-info">Edit</button></a> ';
                                            echo '<a onclick="return deletedata()" href="index.php?page=bidders.php&option=delete&pk_bidder_id=' . $rowview["bidder_id"] . '"><button class="btn btn-danger">Delete</button></a> ';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
<?php
    }

    /* -----------------------------------------------------------
       FULL VIEW
    ----------------------------------------------------------- */
    else if ($_GET["option"] == "fullview") {
        $bidderid = mysqli_real_escape_string($con, $_GET["pk_bidder_id"]);

        $sqlview = "SELECT * FROM bidders WHERE bidder_id='$bidderid'";
        $resultview = mysqli_query($con, $sqlview) or die(mysqli_error($con));
        $rowview = mysqli_fetch_assoc($resultview);
?>
    <div class="static-table-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline8-list">
                        <div class="sparkline8-hd">
                            <div class="main-sparkline8-hd">
                                <h1>Bidder Full View</h1>
                            </div>
                        </div>
                        <div class="sparkline8-graph">
                            <div class="static-table-list">
                                <table class="table">
                                    <tbody>
                                        <tr><td><b>Bidder ID</b></td><td><?php echo $rowview["bidder_id"]; ?></td></tr>
                                        <tr><td><b>Company Name</b></td><td><?php echo $rowview["company_name"]; ?></td></tr>
                                        <tr><td><b>Address</b></td><td><?php echo $rowview["address"]; ?></td></tr>
                                        <tr><td><b>Telephone</b></td><td><?php echo $rowview["land"]; ?></td></tr>
                                        <tr><td><b>Fax</b></td><td><?php echo $rowview["fax"]; ?></td></tr>
                                        <tr><td><b>Mobile</b></td><td><?php echo $rowview["mobile"]; ?></td></tr>
                                        <tr><td><b>Email</b></td><td><?php echo $rowview["email"]; ?></td></tr>
                                        <tr><td><b>Website</b></td><td><?php echo $rowview["website"]; ?></td></tr>
                                        <tr><td><b>Nature of Business</b></td><td><?php echo $rowview["nature_of_the_business"]; ?></td></tr>
                                        <tr><td><b>Business Registration No</b></td><td><?php echo $rowview["business_registration_no"]; ?></td></tr>

                                        <tr>
                                            <td><b>Business Registration Copy</b></td>
                                            <td>
                                                <?php
                                                if (!empty($rowview["business_registration_copy"])) {
                                                    echo '<a href="' . $business_dir . $rowview["business_registration_copy"] . '" target="_blank">' . $rowview["business_registration_copy"] . '</a> ';
                                                    echo '<a href="' . $business_dir . $rowview["business_registration_copy"] . '" target="_blank"><button class="btn btn-primary" type="button">View</button></a>';
                                                } else {
                                                    echo "File Not Available";
                                                }
                                                ?>
                                            </td>
                                        </tr>

                                        <tr><td><b>VAT Registration No</b></td><td><?php echo $rowview["vat_registration_no"]; ?></td></tr>

                                        <tr>
                                            <td><b>VAT Registration Copy</b></td>
                                            <td>
                                                <?php
                                                if (!empty($rowview["vat_registration_copy"])) {
                                                    echo '<a href="' . $vat_dir . $rowview["vat_registration_copy"] . '" target="_blank">' . $rowview["vat_registration_copy"] . '</a> ';
                                                    echo '<a href="' . $vat_dir . $rowview["vat_registration_copy"] . '" target="_blank"><button class="btn btn-primary" type="button">View</button></a>';
                                                } else {
                                                    echo "File Not Available";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <a href="index.php?page=bidders.php&option=view">
                                    <button class="btn btn-warning">Back</button>
                                </a>

                                <?php
                                if (!isset($_GET['print'])) {
                                    echo '<a href="print.php?bidder_id=' . $rowview['bidder_id'] . '" target="_blank">
                                            <button class="btn btn-primary" type="button">Print</button>
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
<?php
    }

    /* -----------------------------------------------------------
       EDIT
    ----------------------------------------------------------- */
    else if ($_GET["option"] == "edit") {
        $bidderid = mysqli_real_escape_string($con, $_GET["pk_bidder_id"]);

        $sqledit = "SELECT * FROM bidders WHERE bidder_id='$bidderid'";
        $resultedit = mysqli_query($con, $sqledit) or die("SQL edit error: " . mysqli_error($con));
        $rowedit = mysqli_fetch_assoc($resultedit);
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

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Bidder Id</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtbidder_id" class="form-control" value="<?php echo $rowedit['bidder_id']; ?>" readonly required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Name of the Company</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtcompany_name" class="form-control" value="<?php echo $rowedit['company_name']; ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Address</label>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                    <textarea name="txtaddress" class="form-control" required><?php echo $rowedit['address']; ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Telephone General</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtland" class="form-control" value="<?php echo $rowedit['land']; ?>" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Fax</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtfax" class="form-control" value="<?php echo $rowedit['fax']; ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Mobile</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtmobile" class="form-control" value="<?php echo $rowedit['mobile']; ?>" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Email</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="email" name="txtemail" class="form-control" value="<?php echo $rowedit['email']; ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Website</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtwebsite" class="form-control" value="<?php echo $rowedit['website']; ?>" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Nature of the Business</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtnature_of_the_business" class="form-control" value="<?php echo $rowedit['nature_of_the_business']; ?>" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Business Registration Number</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtbusiness_registration_no" class="form-control" value="<?php echo $rowedit['business_registration_no']; ?>" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">Business Registration Copy</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="file" name="txtbusiness_registration_copy" class="form-control">
                                                    <br>
                                                    <?php
                                                    if (!empty($rowedit["business_registration_copy"])) {
                                                        echo '<a href="' . $business_dir . $rowedit["business_registration_copy"] . '" target="_blank">' . $rowedit["business_registration_copy"] . '</a>';
                                                    } else {
                                                        echo "File Not Available";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">VAT Registration Number</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="text" name="txtvat_registration_no" class="form-control" value="<?php echo $rowedit['vat_registration_no']; ?>" required />
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label class="login2 pull-right pull-right-pro">VAT Registration Copy</label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <input type="file" name="txtvat_registration_copy" class="form-control">
                                                    <br>
                                                    <?php
                                                    if (!empty($rowedit["vat_registration_copy"])) {
                                                        echo '<a href="' . $vat_dir . $rowedit["vat_registration_copy"] . '" target="_blank">' . $rowedit["vat_registration_copy"] . '</a>';
                                                    } else {
                                                        echo "File Not Available";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group-inner">
                                            <div class="row">
                                                <center>
                                                    <a href="index.php?page=bidders.php&option=view"><input type="button" class="btn btn-primary" value="Go Back" /></a>
                                                    <input type="submit" name="btnupdate" class="btn btn-success" value="Update" />
                                                </center>
                                            </div>
                                        </div>

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
}
?>
</body>