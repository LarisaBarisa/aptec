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
			echo '�� ��� �����, '.$_SESSION['username'];
			echo '<form method="POST">
					�� �������, ��� ������ �����?
					<input type="submit" value="�����" id="logout" name="logout" />
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
					echo '������������, '.$_SESSION['username']."<br>";
					echo '<a href=index.php>�� �������</a>';
					die;
				}
				else {echo '�������� ����� ��� ������'; die;}
			}
		}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="login.css" type="text/css"/>
<title>������</title>
</head>
 <body>
 
	<div id="login_container">
			<div id="form_container">
				<p class="login-text">����������� �� �����</p>
				<form method='POST' action="#">
					<input type='text' placeholder="e-mail" id='email' name='email' class='text_input' required/>
					<input type='password' placeholder="������" id='password' name='password' class='text_input' required/>
					<input type='submit' value='�����' id='login' name='login' />
					<br><a href="registration.php">�����������</a>
				</form>
			</div>
	</div>
</body>
</html>