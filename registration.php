<?php
session_start();

    require "config.php";
    require "function.php";

    if (isset($_POST['reg']))
    {
        $messages = registration($_POST);
        if ($messages === TRUE)
        {
            $_SESSION['msg'] = 
            "Вы успешно зарегистрировались!!! Для подтверждения регистрации Вам
							на почту отправлено письмо с инструкциями";
        }else {
            $_SESSION['msg'] = $messages;
        }
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }

?>
<?php include "inc/header.php"; ?>


<div id="content">
		<div id="main">
        <h1>Регистрация пользователя</h1>
        <?= $_SESSION['msg'] ?>
        <?php unset($_SESSION['msg']); ?>
        <form action="" method="post">
            Логин <br>
            <input type="text" name="reg_login" id="" 
                    value="<?= $_SESSION['reg']['login'] ?>"
                    >
            <br>
            Пароль <br>
            <input type="password" name="reg_password" id="">
            <br>
            Подтвердите Пароль <br>
            <input type="password" name="reg_password_confirm" id="">
            <br>
            Email <br>
            <input type="email" name="reg_email" id=""
                    value="<?= $_SESSION['reg']['email'] ?>"
            >
            <br>
            Имя <br>
            <input type="text" name="reg_name" id=""
                        value="<?= $_SESSION['reg']['name'] ?>"
            >
            <br>

            <button type="submit" name='reg' value="reg_form">Регистрация</button>
        </form>
    </div>

<?php include "inc/sidebar.php" ?>

<?php include "inc/footer.php" ?>

<?php unset($_SESSION['reg']);?>