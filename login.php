<?php
		session_start();
		mysql_connect('localhost','root') or die(mysql_error());
		mysql_query("SET NAMES 'cp1251'");
		mysql_query("SET CHARACTER SET 'cp1251'");
		mysql_select_db('pharmacy') or die(mysql_error());

		if (isset($_POST['logout']))
			{
				unset($_SESSION);
				session_destroy();
			}
		if (isset($_SESSION['username']))
		{
			echo 'Вы уже вошли, '.$_SESSION['username'];
			echo '<form method="POST">
					Вы уверены, что хотите выйти?
					<input type="submit" value="Выйти" id="logout" name="logout" />
				</form>';
			
			die;
		}
		else{
			if (isset($_POST['login']))
			{
				$email=$_POST['email'];
				$password=$_POST['password'];
				$getuser=mysql_query("SELECT * FROM patient WHERE email='$email'") or die(mysql_error());
				$getuserpass=mysql_fetch_array($getuser);
				if (md5($password)==$getuserpass['password'])
				{
					session_start();
					$_SESSION['username']=$getuserpass['first_name'];
					$_SESSION['userid']=$getuserpass['patient_id'];
					$_SESSION['userpharmacy']=$getuserpass['pharmacy_id'];
					$_SESSION['userstatus']=$getuserpass['status'];
					echo 'Здравствуйте, '.$_SESSION['username']."<br>";
					echo '<a href=index.php>На главную</a>';
					die;
				}
				else {echo 'Неверный логин или пароль'; die;}
			}
		}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="login.css" type="text/css"/>
<title>Аптека</title>
</head>
 <body>
 
	<div id="login_container">
			<div id="form_container">
				<p class="login-text">Авторизация на сайте</p>
				<form method='POST' action="#">
					<input type='text' placeholder="e-mail" id='email' name='email' class='text_input' required/>
					<input type='password' placeholder="Пароль" id='password' name='password' class='text_input' required/>
					<input type='submit' value='Войти' id='login' name='login' />
					<br><a href="registration.php">регистрация</a>
				</form>
			</div>
	</div>
</body>
</html>