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
  if($system_usertype!="Guest")
    {

include("config.php");
if(isset($_POST["btnchangepassword"]))
  {
    $currentpassword=md5($_POST["txtpassword"]);
    $newpassword=md5($_POST["txtnewpassword"]);
    $confirmnewpassword=md5($_POST["txtconfirmnewpassword"]);

    $sqlpassword="SELECT password FROM user WHERE user_name='$system_user_name'";
    $resultpassword=mysqli_query($con,$sqlpassword) or die("SQL insert error".mysqli_error($con));
    $rowpassword=mysqli_fetch_assoc($resultpassword);

    if($rowpassword["password"]==$currentpassword)
      {
        //The current password is correct

        if($newpassword==$confirmnewpassword)
          {
            //The new password is correct
            $sqlupdate="UPDATE user SET password='$newpassword' WHERE user_name='$system_user_name'";
            $resultupdate=mysqli_query($con,$sqlupdate) or die("SQL insert error".mysqli_error($con));

            session_destroy();
            echo '<script>alert("Your password has been updated successfully. Please log in using your new password.");
            window.location.href="index.php?page=login.php";</script>';

          }
          else{
            //The new password is not match
             echo '<script>alert("Sorry, The new password is not match with confirm new password");</script>';
          }
      }
      else
      {
        //The current password is incorrect
        echo '<script>alert("Sorry, Your current password incorrect");</script>';
      }
  }
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login | Kiaalap - Kiaalap Admin Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.css">
    <link rel="stylesheet" href="css/owl.transitions.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- morrisjs CSS
		============================================ -->
    <link rel="stylesheet" href="css/morrisjs/morris.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="css/metisMenu/metisMenu.min.css">
    <link rel="stylesheet" href="css/metisMenu/metisMenu-vertical.css">
    <!-- calendar CSS
		============================================ -->
    <link rel="stylesheet" href="css/calendar/fullcalendar.min.css">
    <link rel="stylesheet" href="css/calendar/fullcalendar.print.min.css">
    <!-- forms CSS
		============================================ -->
    <link rel="stylesheet" href="css/form/all-type-forms.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <script>
      function check_password(){
        var newpassword=document.getElementById("txtnewpassword").value;
        var confirmnewpassword=document.getElementById("txtconfirmnewpassword").value;
        if(newpassword==confirmnewpassword)
        {
          return true;
        }
        else{
          alert("Sorry, The passwords do not match!!! Please re-enter and try again.");
          document.getElementById("txtnewpassword").value="";
          document.getElementById("txtconfirmnewpassword").value="";
          return false;
        }
      }
    </script>
</head>

<body>
    <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<div class="error-pagewrap">
		<div class="error-page-int">
			<div class="text-center m-b-md custom-login">
				<h3>PLEASE LOGIN TO APP</h3>
			</div>
			<div class="content-error">
				<div class="hpanel">
                    <div class="panel-body">
                        <form action="" id="loginForm" onsubmit="return check_password()" method="POST">
                            <div class="form-group">
                                <label class="control-label" for="username">User Name</label>
                                <input type="text" placeholder="" title="Please enter you username" required="" value="<?php echo $system_user_name;?>" name="txtuser_name" id="txtuser_name" class="form-control" readonly>
                                <span class="help-block small"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="password">Current Password</label>
                                <input type="password" title="Please enter your password" placeholder="******" required="" value="" name="txtpassword" id="txtpassword" class="form-control">
                                <span class="help-block small"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="password">New Password</label>
                                <input type="password" title="Please enter your password" placeholder="******" required="" value="" name="txtnewpassword" id="txtnewpassword" class="form-control">
                                <span class="help-block small"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="password">Confirm New Password</label>
                                <input type="password" title="Please enter your password" placeholder="******" required="" value="" name="txtconfirmnewpassword" id="txtconfirmnewpassword" class="form-control">
                                <span class="help-block small"></span>
                            </div>
                            <div class="checkbox login-checkbox">
                                <label>
										            <input type="checkbox" class="i-checks"> Remember me </label>
                            </div>
                            <center>
                              <button class="btn btn-danger btn-danger" type="reset" name="btnclear" id="btnclear" >Clear</button>
                              <button class="btn btn-success loginbtn" type="submit" name="btnchangepassword" id="btnchangepassword">Change Password</button>
                            </center>
                        </form>
                    </div>
                </div>
			</div>
			<div class="text-center login-footer">
				<p>Copyright © 2018. All rights reserved. Template by <a href="https://colorlib.com/wp/templates/">Colorlib</a></p>
			</div>
		</div>   
    </div>
    <!-- jquery
		============================================ -->
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="js/jquery-price-slider.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="js/jquery.meanmenu.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- sticky JS
		============================================ -->
    <script src="js/jquery.sticky.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/scrollbar/mCustomScrollbar-active.js"></script>
    <!-- metisMenu JS
		============================================ -->
    <script src="js/metisMenu/metisMenu.min.js"></script>
    <script src="js/metisMenu/metisMenu-active.js"></script>
    <!-- tab JS
		============================================ -->
    <script src="js/tab.js"></script>
    <!-- icheck JS
		============================================ -->
    <script src="js/icheck/icheck.min.js"></script>
    <script src="js/icheck/icheck-active.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
    <!-- tawk chat JS
		============================================ 
    <script src="js/tawk-chat.js"></script> -->
</body>


</html>
<?php
}
else{
  echo'<script> window.location.href="index.php"; </script>';
}
?>