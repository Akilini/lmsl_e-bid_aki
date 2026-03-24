<td>
    <?php
    if (!empty($rowview["business_registration_copy"])) {
    ?>
        <a href="bidders/business_registration_copy/<?php echo $rowview["business_registration_copy"]; ?>" target="_blank">
            <?php echo $rowview["business_registration_copy"]; ?>
        </a>
        &nbsp;&nbsp;
        <a href="bidders/business_registration_copy/<?php echo $rowview["business_registration_copy"]; ?>" target="_blank">
            <button class="btn btn-primary" type="button">View</button>
        </a>
    <?php
    } else {
        echo "File Not Available";
    }
    ?>
</td>


$business_registration_copy = "";
$vat_registration_copy = "";

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




// Upload Business Registration Copy

        if(isset($_FILES["txtbusiness_registration_copy"]) && $_FILES["txtbusiness_registration_copy"]["error"] == 0)
        {
            $file_name = basename($_FILES["txtbusiness_registration_copy"]["name"]);
            $tmp_name = $_FILES["txtbusiness_registration_copy"]["tmp_name"];

            // File extension
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed = array("pdf","jpg","jpeg","png");

            // Validate file type
            if(!in_array($ext,$allowed))
            {
                echo "<script>alert('Invalid file type');</script>";
                exit();
            }

            // Move file
            move_uploaded_file($tmp_name, $target_dir.$file_name);
        }



        // Upload VAT Registration Copy
        if(isset($_FILES["txtvat_registration_copy"]) && $_FILES["txtvat_registration_copy"]["error"] == 0)
        {
            $file_name = basename($_FILES["txtvat_registration_copy"]["name"]);
            $tmp_name = $_FILES["txtvat_registration_copy"]["tmp_name"];

            // File extension
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed = array("pdf","jpg","jpeg","png");

            // Validate file type
            if(!in_array($ext,$allowed))
            {
                echo "<script>alert('Invalid file type');</script>";
                exit();
            }

            // Move file
            move_uploaded_file($tmp_name, $target_dir.$file_name);
        }