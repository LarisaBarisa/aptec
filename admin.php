<?php
	include_once('connect.php');
	session_start();
	if($_SESSION['userstatus']!="admin")
	{
		echo '� ��� ��� ������� � ������ ��������';
		die;
	}
	if(isset($_POST['refreshprices']))
	{
		echo '<script language="javascript">document.location="get_new_price.php";</script>';
	}
/*
	echo '<a href="admin_registration.php">�������� ������ ������������</a><br>';
	echo '<a href="admin_pharmacy.php">�������� ����� ������</a><br>';
	echo '<a href="admin_drugs.php">�������� ����� ���������</a><br>';
	echo '<a href="admin_chemicals.php">�������� ����� ���������</a><br>';
	echo '<a href="admin_registration.php">�������� ������ ������������</a><br>';
*/
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>�������� ����</title>
</head>
<body>
	<form method="POST">
		<input type="submit" name="refreshprices" value="�������� ����">
	</form>
</body>
</html>