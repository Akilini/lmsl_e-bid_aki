<?php
if(!isset($_SESSION))
  {
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

// login submit start
if(isset($_POST["btnlogin"]))
  {
    $enterusername=$_POST["txtuser_name"];
    $enterpassword=md5($_POST["txtpassword"]);
    $sqlusername="SELECT * FROM user WHERE user_name='$enterusername' ";
    $resultusername=mysqli_query($con,$sqlusername) or die("SQL insert error".mysqli_error($con));
    if(mysqli_num_rows($resultusername)==1)
      {
        //username correct
        $rowusername=mysqli_fetch_assoc($resultusername);
        $sqlpassword="SELECT * FROM user WHERE user_name='$enterusername' AND password='$enterpassword'";
        $resultpassword=mysqli_query($con,$sqlpassword) or die("SQL insert error".mysqli_error($con));
        if(mysqli_num_rows($resultpassword)==1)
          {
            //username and password both are correct
            $sqlupdate="UPDATE user SET attempt=0 WHERE user_name='$enterusername'";
            $resultupdate=mysqli_query($con,$sqlupdate) or die("sql error in sqlupdate ".mysqli_error($con));

            if($rowusername["status"]=="Active"){
              //If user is active
              $_SESSION["login_user_name"]=$rowusername["user_name"];
              $_SESSION["login_user_id"]=$rowusername["user_id"];
				      $_SESSION["login_usertype"]=$rowusername["usertype"];
              echo '<script>window.location.href="index.php";</script>';
            }
            else{
              //If user is delete
              echo '<script>alert("Sorry your account is deleted.");</script>';
            }
          }
        else if( $rowusername["attempt"]<3)
          {
            //username correct, password wrong but attempt less than three
            $sqlupdate="UPDATE user SET attempt=attempt+1 WHERE user_name='$enterusername'";
            $resultupdate=mysqli_query($con,$sqlupdate) or die("SQL insert error".mysqli_error($con));
            echo '<script>alert("The password entered is incorrect. Please try again.");</script>';
          }
        else
          { //username correct, password wrong but attempt greater than three
            $_SESSION["forgetusername"]=$rowusername["user_name"];
            echo '<script>alert("Sorry you try more than three times, please recover your password");
				    window.location.href="index.php?page=forgetpassword.php";</script>';
          }
      }
    else
      { //username wrong
        echo '<script>alert("There is no such username.");</script>';
      }        
  }
// login submit end
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
</head>

<body>
  <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<div class="error-pagewrap">
		<div class="error-page-int">
			<div class="text-center m-b-md custom-login">
				<h3>LOGIN TO e-LMSL</h3>
			</div>
			<div class="content-error">
				<div class="hpanel">
                    <div class="panel-body">
                        <form action="" id="loginForm" method="POST">
                            <div class="form-group">
                                <label class="control-label" for="username">User Name</label>
                                <input type="text" placeholder="" title="Please enter you username" required="" value="" name="txtuser_name" id="txtuser_name" class="form-control">
                                <span class="help-block small"></span>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="password">Password</label>
                                <input type="password" title="Please enter your password" placeholder="******" required="" value="" name="txtpassword" id="txtpassword" class="form-control">
                                <span class="help-block small">Enter your strong password</span>
                            </div>
                            <div class="checkbox login-checkbox">
                                <label>
										            <!-- <input type="checkbox" class="i-checks"> Remember me </label> -->
                            </div>
                            <center>
                              <button class="btn btn-danger btn-danger" type="reset" name="btnclear" id="btnclear" >Clear</button>
                              <button class="btn btn-success loginbtn" type="submit" name="btnlogin" id="btnlogin">Login</button>
                            <center>
                        </form>
                    </div>
                </div>
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