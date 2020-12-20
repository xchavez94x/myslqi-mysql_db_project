<?php
session_start();

    require "config.php";
    require "function.php";
    
    if (!check_user())
    {
        header("Location:login.php");
        exit();
    }

?>

<?php include "inc/header.php"; ?>


<div id="content">
		<div id="main">
            <h1>Секретный контент</h1>
            
    </div>

<?php include "inc/sidebar.php" ?>

<?php include "inc/footer.php" ?>