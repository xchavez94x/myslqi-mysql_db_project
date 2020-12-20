<?php


	function registration($post)
	{
		global $db;

		$login = clean_data($post['reg_login']);
		$password = trim($post['reg_password']);
		$conf_pass= trim($post['reg_password_confirm']);
		$email = clean_data($post['reg_email']);
		$name = clean_data($post['reg_name']);

		$msg = '';
		if(empty($login)) {
			$msg = $msg . "Введите логин <br />";
		}
		if(empty($password)) {
			$msg .= "Введите пароль <br />";
		}
		if(empty($email)) {
			$msg .= "Введите адресс почтового ящика <br />";
		}
		if(empty($name)) {
			$msg .= "Введите имя <br />";
		}

		if ($msg)
		{
			$_SESSION['reg']['login'] = $login;
			$_SESSION['reg']['email'] = $email;
			$_SESSION['reg']['name'] = $name;
			return $msg;
		}

		if ($password == $conf_pass)
		{
			/* $sql = "SELECT * FROM users WHERE login='"
					.$login."'"; */
			$sql = "SELECT * FROM users WHERE login='%s'";
			$sql = sprintf(
				$sql,
				mysqli_real_escape_string($db, $login));

			$result = mysqli_query($db, $sql);
			if (mysqli_num_rows($result) > 0)
			{
				$_SESSION['reg']['email'] = $email;
				$_SESSION['reg']['name'] = $name;
				
				return "Пользователь с данным логином  уже зарегистрирован!!!";
			}
			$password = md5($password);
			$hash = md5(microtime());

			$query = "INSERT INTO users (
				name,
				email,
				password,
				login,
				hash
				)
			VALUES (
				'%s',
				'%s',
				'%s',
				'%s',
				'$hash'
			)";

			$query = sprintf(
				$query,
				mysqli_real_escape_string($db, $name),
				mysqli_real_escape_string($db, $email),
				$password,
				mysqli_real_escape_string($db, $login)
			);
			$result2 = mysqli_query($db, $query);
			if (!$result2)
			{
						$_SESSION['reg']['login'] = $login;
						$_SESSION['reg']['email'] = $email;
						$_SESSION['reg']['name'] = $name;
				return
				"Ошибка при регистрации пользователя. ".mysqli_error($db);
			}

		$headers = '';
		$headers .= "From: Admin <admin@ukr.net> \r\n";
		$headers .= "Content-Type: text/plain; charset=utf8";

		$tema = "Регистрация пользователя";

		$mail_body = "Спасибо за регистрацию на сайте. "
					."Ваша ссылка для подтверждения  учетной записи: "
					."http://".$_SERVER['HTTP_HOST']."//confirm.php?hash=".$hash;
					//http://ckweb27l07/

		mail($email,$tema,$mail_body,$headers);

			return true;
		}
		else{
			$msg = "Ваши пароли не совпадают";
			$_SESSION['reg']['login'] = $login;
			$_SESSION['reg']['email'] = $email;
			$_SESSION['reg']['name'] = $name;
			return $msg;
		}



	}

	function confirm()
	{
		global $db;
		$hash = clean_data($_GET['hash']);

		$query = "UPDATE users set confirm='1' where hash = '%s'";

		$query = sprintf($query, mysqli_real_escape_string($db, $hash));

		$result = mysqli_query($db, $query);

		if (mysqli_affected_rows($db) == 1)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function login($post)
	{
		if (empty($post['login']) || empty($post['password'])){
			return "Заполните поля!!!";
		}

		$login = clean_data($post['login']);
		$password = md5(trim($post['password']));

		global $db;
		$sql = "SELECT user_id,confirm
				FROM users
				WHERE login = '%s'
				AND password = '%s'";
		$sql = sprintf(
			$sql,
			mysqli_real_escape_string($db, $login),
			$password);

		$result = mysqli_query($db,$sql);

		if(!$result || mysqli_num_rows($result) < 1) {
			return "Не правильный логи или пароль";
		}

		$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
		if ($data[0]['confirm'] == 0){
			return "Пользователь не окончил регистрацию!";
		}


		$sess = md5(microtime());
		///
		//Ваш код
		//UPDATE users SET sess='' WHERE user_id=
		$sql = "UPDATE users SET sess='%s' WHERE user_id='%s'";
		$sql = sprintf(
			$sql,
			$sess,
			$data[0]['user_id']);
		$result = mysqli_query($db, $sql);
		if (!$result)
		{
			return "Ошибка авторизации пользователя!";
		}
		$_SESSION['sess'] = $sess;

		if ($post['member'] == '1')
		{
			setcookie('login', $login, time() + 10*24*60*60);
			setcookie('password', $password, time() + 10*24*60*60);
		}


		///
		return TRUE;

	}

	function get_password($email){
		global $db;
		$email = clean_data($email);

		$sql = "SELECT user_id
				FROM users
				WHERE email = '%s'";
		$sql = sprintf($sql,mysqli_real_escape_string($db, $email));

		$result = mysqli_query($db, $sql);

		if(!$result) {
			return "не возможно сгенерировать новый пароль";
		}

		if(mysqli_num_rows($result) == 1) {
			$str = "234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";

			$pass = '';

			for($i = 0; $i < 6; $i++) {
				$x = mt_rand(0,(strlen($str)-1));

				if($i != 0) {
					if($pass[strlen($str)-1] == $str[$x]) {
						$i--;
						continue;
					}
				}
				$pass .= $str[$x];
			}

			$md5pass = md5($pass);

			$data = mysqli_fetch_all($result, MYSQLI_ASSOC);

			$query = "UPDATE users
						SET password='$md5pass'
						WHERE user_id = '".$data[0]['user_id']."'";
			$result2 = mysqli_query($db, $query);

			if(!$result2) {
				return "Не возможно сгенерировать новый пароль";
			}

			$headers = '';
			$headers .= "From: Admin <admin@mail.ru> \r\n";
			$headers .= "Content-Type: text/plain; charset=utf8";

			$subject = 'new password';
			$mail_body = "Ваш новый пароль: ".$pass;

			mail($email,$subject,$mail_body,$headers);

			return TRUE;
		}
		else {
			return "Пользователя с таким почтовым ящиком нет";
		}
	}

	function check_user(){
		global $db;
		if(isset($_SESSION['sess'])) {
			$sess = $_SESSION['sess'];

			$sql = "SELECT user_id
					FROM users
					WHERE sess='$sess'";
			$result = mysqli_query($db, $sql);

			if(!$result || mysqli_num_rows($result) < 1) {
				return FALSE;
			}

			return TRUE;
		}elseif(isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
			$login = $_COOKIE['login'];
			$password = $_COOKIE['password'];

			$sql = "SELECT user_id
					FROM users
					WHERE login='$login'
					AND password='$password'
					AND confirm = '1'";
			$result = mysqli_query($db, $sql);

			if(!$result || mysqli_num_rows($result) < 1) {
				return FALSE;
			}

			$sess = md5(microtime());

			$sql_update = "UPDATE users SET sess='$sess' WHERE login='%s'";
			$sql_update = sprintf($sql_update,mysqli_real_escape_string($db, $login));

			if(!mysqli_query($db, $sql_update)) {
				return "Ошибка авторизации пользователя";
			}

			$_SESSION['sess'] = $sess;

			return TRUE;
		}
		else {
			return FALSE;
		}
	}

	function logout() {
		unset($_SESSION['sess']);

		setcookie('login','',time()-3600);
		setcookie('password','',time()-3600);

		return TRUE;
	}


	function clean_data($str) {
		return strip_tags(trim($str));
	}
