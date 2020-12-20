<?php
session_start();

    require "config.php";
    require "function.php";
    
    if (!empty($_POST))
    {
        if(isset($_POST['email'])) {
            $msg = get_password($_POST['email']);
            
            if($msg === TRUE) {
                $_SESSION['msg'] = "Новый пароль выслан Вам на почту";
                header("Location:login.php");
            }
            else {
                $_SESSION['msg'] = $msg;
                header("Location:".$_SERVER['PHP_SELF']);
            }
            exit();
        }
    }

?>

<?php include "inc/header.php"; ?>


<div id="content">
		<div id="main">
            <h1>Авторизация</h1>
            <?=$_SESSION['msg']?>
            <?php unset($_SESSION['msg'])?>
            <form method='POST'>
            <label>
				EMAIL<br>
					<input type='text' name='email'>
				</label><br>
				<input style="float:left" type='submit' value='Вход'>
			</form>
    </div>

<?php include "inc/sidebar.php" ?>

<?php include "inc/footer.php" ?>