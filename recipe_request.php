<?php 
session_start();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="AddInputFunc.js"></script>
<link rel="stylesheet" href="index.css" type="text/css"/>
<title>Заказать лекарство</title>
</head>
<body>
	<div id="wrapper">
		<div id="header">Заказать лекарство</div>
		<div id="menu">
			<ul>
				<li><a href="personal_cabinet.php">Личный кабинет</a></li>
				<li><a href="index.php">На главную</a></li>
				<li><a href="recipe_request.php">Заказать препарат</a></li>
				<?if($_SESSION['userstatus']=='pharmacist')
				{	
					echo'<li><a href="taken_recipes_list.php">Взятые заказы</a></li>';
					echo'<li><a href="recipe_to_patient.php">Выдать заказ</a></li>';
					echo'<li><a href="accept_recipe.php">Новые заказы</a></li>';
				}?>
			</ul>
		</div>
		<div style="float:right; width:485px;">
			<form method="post" id="recipe_form">
			<?
					session_start();
			if (!$_SESSION['userid'])
			{
				echo 'Чтобы заказать лекарство, необходимо <a href="login.php">авторизоваться</a>';
				die;
			}
			echo $_SESSION['username'].', выберете лекарства, указанные в Вашем рецепте';
			include_once 'connect.php';
			if (isset($_POST['submit']))
			{
				$i=0;
				$d = getdate();
				$isrecipe=false;
				for($i=0; $i<10; $i++)
				{
					$pack[$i]=$_POST['drugs_pack'.$i];
					if($pack[$i]!=0) $isrecipe=true;
				}
				$i=0;
				if($isrecipe)
				{
					$createrecipe=mysql_query("INSERT INTO recipe VALUES('','$d[year].$d[mon].$d[mday]', '$_SESSION[userid]','new', '$_SESSION[userpharmacy]','0')") or die(mysql_error());
					$id=mysql_insert_id();
					while ($i<=10)
					{
						//echo $_POST['drugs_pack'.$i];
						$pack[$i]=$_POST['drugs_pack'.$i];
						$numofpack[$i]=$_POST['packs'.$i];
						if($pack[$i]!=0)
						{
							$query=mysql_query("INSERT INTO recipe_has_drugs VALUES('$id','$pack[$i]', '$numofpack[$i]')") or die(mysql_error());
						}
						$i++;
					}
				echo 'Номер вышего заказа: '.$id;
				die;
				}
			}
			?>
				<br>
				<input type="button" id="AddButton" name="AddButton" value="Добавить" onclick="AddInput();" />
				<input type="submit" name="submit" ></input>
				<br>
			</form>
		</div>
	</div>
</body>
</html>
