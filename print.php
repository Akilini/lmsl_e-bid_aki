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
<!doctype html>
<html class="no-js" lang="en">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dashboard V.1 | Kiaalap - Kiaalap Admin Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
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
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- educate icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/educate-custon-icon.css">
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
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <link rel="stylesheet" href="css/data-table/bootstrap-table.css">
    <link rel="stylesheet" href="css/data-table/bootstrap-editable.css">
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>

    <title>Report</title>

    <script language="javascript">
    document.onmousedown=disableclick;
    status="Right Click Disabled";
    function disableclick(event)
    {
      if(event.button==2)
       {
         alert(status);
         return false;    
       }
    }
    </script>

    <script type="text/javascript">
    // for print button
        function printpage() {
            //Get the print button and put it into a variable
            var printButton = document.getElementById("printpagebutton");
            //Set the print button visibility to 'hidden' 
            printButton.style.visibility = 'hidden';
            //Print the page content
            window.print()
            //Set the print button to 'visible' again 
            //[Delete this line if you want it to stay hidden after printing]
            printButton.style.visibility = 'visible';
        }
    </script>
    </head>
    <body oncontextmenu="return false" style="background-color:white">
        <table width="1200" border="0" align="center">
            <tr>
                <td>
                    <img src=letter_head.png>
                </td>
            </tr>
            <tr>
                <td>
                <input id="printpagebutton" type="button" value="Print Report" onclick="printpage()"/>  <!-- print button, but it was not visible in printed paper  -->
            <br>
                <?php

                    if(isset($_GET['print'])) // if get print
                    {
                        $filename=$_GET['print'];
                        include($filename);
                    }
                ?>
                </td>
            </tr>
        </table>
    </body>
</html>