	<?php
		mysql_connect('localhost','root') or die(mysql_error());
		mysql_select_db('pharmacy');
		mysql_query("SET NAMES 'cp1251'");
		mysql_query("SET CHARACTER SET 'cp1251'");
		$res1 = mysql_query('SELECT * FROM region') or die(mysql_error());

		if (isset($_POST['submit'])){
			$email=$_POST['email'];
			$first_name=$_POST['first_name'];
			$last_name=$_POST['last_name'];
			$mobile_phone=$_POST['mobile_phone'];
			$region=$_POST['region_id'];
			$city=$_POST['city_id'];
			$pharmacy=$_POST['pharmacy_id'];
			$address=$_POST['street']." ".$_POST['house']." ".$_POST['flat'];
			$password=$_POST['password'];
			$r_password=$_POST['r_password'];
			if($password==$r_password){
				$emailquery=mysql_query("SELECT email FROM patient WHERE email='$email'")or die(mysql_error());
				if(mysql_num_rows($emailquery)>0)
				{
					echo 'Данный email уже зарегистрирован';
					die;
				}
				
				$password=md5($password);
				printf("%s %s %s %s %s %s %s", $email, $first_name, $last_name, $region, $city, $password, $pharmacy);
				$getpharmid=mysql_query("SELECT pharmacy_id FROM pharmacy,address WHERE address.address_id=pharmacy.address_id AND address.address='$pharmacy'")or die(mysql_error());
				echo 'success';
				$row = mysql_fetch_array($getpharmid);
				$query=mysql_query("INSERT INTO address VALUES('','$address','$city')")or die(mysql_error());
				$id=mysql_insert_id();
				$pharm_id=$row[0];
				$query=mysql_query("INSERT INTO patient VALUES('','$email','$password','$first_name', '$last_name','$mobile_phone','$id','$pharm_id', 'patient')")or die(mysql_error());
				
				}
			else{
				die('Пароли должны совпадать');
			}
		}
	?>
<html>
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="selects.js"></script>
<link rel="stylesheet" href="login.css" type="text/css"/>
<title>Регистрация</title>
</head>
<body>
<div id="login_container" style="width:300px;">
<br>Регистрация<br>
	<form method="post" action="#">
		<input type="text" name="email" placeholder="email" required /></br>
		<input type="text" name="first_name" placeholder="Имя" required /></br>
		<input type="text" name="last_name" placeholder="Фамилия" required /></br>
		<input type="text" name="mobile_phone" placeholder="Мобильный телефон" /></br>
		<?
			$res1=mysql_query("SELECT * FROM region") or die(mysql_error());
			
			echo '<select  name="region_id" list="region_list" id="region_id" >
					<option value="0">- выберите регион -</option>';
					
			while ($row = mysql_fetch_array($res1)) 
			{
				echo '<option name="regions" value="' .$row['region_id'].'">';
				echo "$row[region_name]";
				echo '</option>'; // Format for adding options 
			}
			echo '</select><br>';
		?>
		<select name="city_id" id="city_id" disabled="disabled" required>
			<option value="0">- выберите город -</option>
		</select><br>
		<select name="pharmacy_id" id="pharmacy_id" disabled="disabled" required>
			<option value="0">- выберите удобную для вас аптеку -</option>
		</select><br>

		<input type="text" name="street" placeholder="Улица" required /></br>
		<input type="text" name="house" placeholder="Дом" /></br>
		<input type="text" name="flat" placeholder="Квартира" /></br>
		<input type="password" name="password" placeholder="Пароль" required /></br>
		<input type="password" name="r_password" placeholder="Повторите пароль" required /></br>
		<input type="submit" name="submit" value="Готово" />
	</form>
	<a href="index.php">На главную</a>
</div>
</body>
</html>