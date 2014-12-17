<?session_start();?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="index.css" type="text/css"/>
<link rel="stylesheet" href="login.css" type="text/css"/>
<title>Личный кабинет</title>
</head>
 <body>
	<div id="wrapper">
		<div id="header">
			Личный кабинет
		</div>
		<div id="menu">
			<ul>
				<li style="background:#EAF4F9;"><a href="index.php">На главную</a></li>
				<li><a href="recipe_request.php">Заказать препарат</a></li>
				<?if($_SESSION['userstatus']=='pharmacist')
				{	
					echo'<li><a href="taken_recipes_list.php">Взятые заказы</a></li>';
					echo'<li><a href="recipe_to_patient.php">Выдать заказ</a></li>';
					echo'<li><a href="accept_recipe.php">Новые заказы</a></li>';
				}?>
				<li><a>Адреса и контакты</a></li>
			</ul>
		</div>
		<div style="width:485px; margin:0; float:right; padding:5px;">
			<?php
				if(!$_SESSION['userid'])
				{
					echo 'Чтобы просматривать данную страницу, необходимо <a href=login.php>авторизоваться</a>';
					die;
				}
				include_once('connect.php');
				mysql_query("SET NAMES 'cp1251'");
				mysql_query("SET CHARACTER SET 'cp1251'");
				$query=mysql_query('SELECT * FROM patient WHERE patient_id='.$_SESSION['userid']) or die(mysql_error());
				$result=mysql_fetch_array($query);
					echo 'Ваши данные: <b><br>e-mail</b>: '.$result['email'].'<br>';
					echo '<b>Имя</b>: '.$result['first_name'].'<br>';
					echo '<b>Фамилия</b>: '.$result['last_name'].'<br>';
					echo '<b>Телефон</b>: '.$result['mobile_phone'].'<br><br>';
				
				$query=mysql_query('SELECT * FROM recipe WHERE patient_id='.$_SESSION['userid'].' AND status="ready"') or die(mysql_error());
				echo 'У Вас готовы новые заказы (<b>всего:'.mysql_num_rows($query).'</b>): <br>';
				while($result=mysql_fetch_array($query))
				{
					echo '<br>Заказ №'.$result['idRecipe'].':<br>';
					$getrecipedrugs=mysql_query('SELECT drugs_name, drugs_id FROM recipe_has_drugs, drugs
												WHERE recipe_idRecipe='.$result['idRecipe'].'
												AND recipe_has_drugs.drugs_drugs_id=drugs.drugs_id') or die(mysql_error());
					
					while($resultdrugs=mysql_fetch_array($getrecipedrugs))
						echo ' — '.$resultdrugs['drugs_name'].'<br>';
				}
				if($_SESSION['userstatus']=='pharmacist')
			?>
		</div>
	</div>
 </body>
</html>