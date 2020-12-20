<?php
session_start();

    require "config.php";
    require "function.php";
    if (!empty($_GET))
    {
        if (isset($_GET['hash'])){
            $messages = confirm();

            if ($messages === TRUE){
                $_SESSION['msg'] = 
                "Ваша учетная запись активирована.
                Можете авторизироваться на сайте.
                ";
            }
            else
            {
                $_SESSION['msg'] = "Неверная ссылка";
            }
        }

    }

?>

<?php include "inc/header.php"; ?>


<div id="content">
		<div id="main">
            <?= $_SESSION['msg']?>
    </div>

<?php include "inc/sidebar.php" ?>

<?php include "inc/footer.php" ?>