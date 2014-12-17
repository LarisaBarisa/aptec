<?php
	include_once('connect.php');
	session_start();
	if($_SESSION['userstatus']!="admin")
	{
		echo 'У вас нет доступа к данной странице';
		die;
	}
	if(isset($_POST['refreshprices']))
	{
		echo '<script language="javascript">document.location="get_new_price.php";</script>';
	}
/*
	echo '<a href="admin_registration.php">Добавить нового пользователя</a><br>';
	echo '<a href="admin_pharmacy.php">Добавить новую аптеку</a><br>';
	echo '<a href="admin_drugs.php">Добавить новое лекарство</a><br>';
	echo '<a href="admin_chemicals.php">Добавить новые препараты</a><br>';
	echo '<a href="admin_registration.php">Добавить нового пользователя</a><br>';
*/
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Обновить цены</title>
</head>
<body>
	<form method="POST">
		<input type="submit" name="refreshprices" value="Обновить цены">
	</form>
</body>
</html>