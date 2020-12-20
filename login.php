<?php
session_start();

    require "config.php";
    require "function.php";

    /* echo "<pre>";
    print_r($_SERVER);
    echo "</pre>"; */
    if (!empty($_POST))
    {
        if (
            isset($_POST['login']) 
            && 
            (isset($_POST['password']))
            )
            {
                $messages = login($_POST);

                if ($messages === TRUE){
                    $_SESSION['msg'] = "Вы авторизированы!";
                }
                else
                {
                    $_SESSION['msg'] = $messages;
                }

                var_dump($_POST);
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
				login<br>
					<input type='text' name='login'>
				</label><br>
				Password<br>
				<label>
					<input type='password' name='password'>
				</label><br>
				<label>Member
					<input type="checkbox" name='member' value="1">
				</label><br>
				<input style="float:left" type='submit' value='Вход'>
			</form>
    </div>

<?php include "inc/sidebar.php" ?>

<?php include "inc/footer.php" ?>