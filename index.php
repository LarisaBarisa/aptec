<?php
		mysql_connect('localhost','root') or die(mysql_error());
		mysql_select_db('pharmacy') or die(mysql_error());
		mysql_query("SET NAMES 'cp1251'");
		mysql_query("SET CHARACTER SET 'cp1251'");
		session_start();
		
	?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="index.css" type="text/css"/>
<link rel="stylesheet" href="login.css" type="text/css"/>
<title>Аптека</title>
</head>
 <body>
 <?
	if(isset($_SESSION['userid'])) 
	{
		echo 'Здравствуйте, '.$_SESSION['username'].' [<a href="login.php">Выйти</a>]';
	}	
?>
	<div id="wrapper">
		<div id="menu">
			<ul>
				<li style="background:#EAF4F9;"><a href="index.php">На главную</a></li>
				<li><a href="recipe_request.php">Заказать препарат</a></li>
				<?
					if(isset($_SESSION['userid'])) echo '<li><a href="personal_cabinet.php">Личный кабинет</a></li>';
					else echo '<li><a href="login.php">Войти</a></li>';
				?>
				<li><a>Адреса и контакты</a></li>
			</ul>
		</div>
		<div id="image"><img src="images/doctor_logo.jpg" height=168 width=450>
		</div>
		<div style="clear:both;border:1px solid #000; text-align:center;">
			<p >
			<br>
			<br>
			Приветствуем вас на главной странице фармацевтической компании "Студентик". Для заказа вам необходимо зарегистрироваться и 
			перейти по ссылке заказать препорат. Наш интерфейс легок и понятен и далее вы без труда соорентриуетесь сами.
			<br>
			<br>
			</p>
		</div>
		<!--<div id="login_container">
			<div id="form_container">
				<p class="login-text">Авторизация на сайте</p>
				<form method='POST'>
					<input type='text' placeholder="e-mail" id='email' name='email' class='text_input' required/>
					<input type='password' placeholder="Пароль" id='password' name='password' class='text_input' required/>
					<input type='submit' value='Войти' id='login' name='login' />
				</form>
			</div>
		</div>-->
		
	</div>
 </body>
</html>