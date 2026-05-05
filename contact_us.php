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
?>
<html>
<body>
<!-- <img src="img/Contact_us.png" width="100%"> <br> -->
<br><h3>contactus</h3><br>
<p>Address –  Lanka Mineral Sands Limited,
341 / 13, Sarana Mawatha,
Rajagiriya,
Sri Lanka.</p> 
<p>Contact Number – +94 112883951 / +94 112883952 </p>
<p>Email – minmarketing@sltnet.lk / procurementmic@gmail.com</p>
</body>
</html>