<?php
	include_once 'connect.php';
	session_start();
	if($_SESSION['userstatus']!="pharmacist")
	{
		echo 'У вас нет доступа к данной странице.  <a href=index.php>На главную</a>';
		die;
	}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="jquery.js"></script>
<link rel="stylesheet" href="index.css" type="text/css"/>
<title>Новые рецепты</title>
</head>
 <body>
	<div id="wrapper">
		<div id="header">Новые рецепты
		</div>
		<div id="menu">
			<ul>
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
			<?
				mysql_query("SET NAMES 'cp1251'");
				mysql_query("SET CHARACTER SET 'cp1251'");
				
				if ($_SESSION['userstatus']!="pharmacist")
				{
					die ("У Вас нет доступа к данной странице");
				}
				$getnewrecipe=mysql_query('SELECT *
											FROM recipe 
											WHERE recipe.status="new"
											AND pharmacy_id='.$_SESSION['userpharmacy'].' LIMIT 0,20') or die(mysql_error());
				$i=0;							
				echo '<form method="post" action="accept_recipe.php">';
				echo 'Всего новых рецептов: '.mysql_num_rows($getnewrecipe).'<br>';
				while($result=mysql_fetch_array($getnewrecipe))
				{
					echo "<b><br>Заказ №".$result['idRecipe']." поступил ".$result['date'].":</b><br>";
					$getrecipedrugs=mysql_query('SELECT drugs_name, drugs_id FROM recipe_has_drugs, drugs
												WHERE recipe_idRecipe='.$result['idRecipe'].'
												AND recipe_has_drugs.drugs_drugs_id=drugs.drugs_id') or die(mysql_error());
												
					while($resultdrugs=mysql_fetch_array($getrecipedrugs))
					{
						$getdrugscemicals=mysql_query('SELECT * FROM chemicals, drugs_has_chemicals, pharmacy_has_chemicals, recipe_has_drugs
														WHERE drugs_has_chemicals.drugs_id='.$resultdrugs['drugs_id'].'
														AND recipe_has_drugs.drugs_drugs_id=drugs_has_chemicals.drugs_id
														AND drugs_has_chemicals.chemicals_id=chemicals.chemicals_id
														AND pharmacy_has_chemicals.chemicals_chemicals_id=chemicals.chemicals_id
														AND recipe_idRecipe='.$result['idRecipe'].
														' AND pharmacy_pharmacy_id='.$_SESSION['userpharmacy']) or die(mysql_error());
						echo $resultdrugs['drugs_name'].':<br>';
						while($resultchem=mysql_fetch_array($getdrugscemicals))
						{
							if($resultchem['amount']*10*$resultchem['packs_number']>$resultchem['reserve']*1000000)
							{
								echo '<b style="color:#FF0000;"> — '.$resultchem['title'].' - '.$resultchem['amount'].'мг. ('.$resultchem['packs_number'].' уп)  !НЕДОСТАТОЧНО ХИМИКАТОВ!  Доступно:'.$resultchem['reserve'].'кг</b><br>';
							}
							else
							{	
								echo ' — '.$resultchem['title'].' - '.$resultchem['amount'].'-'.'мг. ('.$resultchem['packs_number'].' уп) Доступно:'.$resultchem['reserve'].'кг<br>';
							}
						}
					}
					
					echo '<input type="submit" name="take['.$result['idRecipe'].']" id="take['.$result['idRecipe'].']" value="Принять"/>
						<input type="submit" name="ready['.$result['idRecipe'].']" id="ready['.$result['idRecipe'].']" value="Выполнено"/></br>';
					$i++;
				}
				
				if (isset($_POST['take']))
				{	
					$takenrecipe=key($_POST['take']);
					echo $takenrecipe;
					
					$query=mysql_query('SELECT status FROM recipe WHERE idRecipe='.$takenrecipe);
					$result=mysql_fetch_array($query);
					if ($result['status']=="taken")
					{
						echo '<script language="javascript">alert("Рецепт уже занят")</script>';
						die;
					}
					mysql_query('UPDATE recipe SET status="taken", pharmacist_id='.$_SESSION['userid'].' WHERE idRecipe='.$takenrecipe) or die(mysql_error());
					echo '<script language="javascript">
							document.location="taken_recipes_list.php";
							</script>';
					die;
				}
				
				if (isset($_POST['ready']))
				{	
					$readyrecipe=key($_POST['ready']);
					echo $readyrecipe;
					mysql_query('UPDATE recipe SET status="ready" WHERE idRecipe='.$readyrecipe) or die(mysql_error());
					
					$changereserve=mysql_query('SELECT * FROM chemicals, drugs_has_chemicals, pharmacy_has_chemicals, recipe_has_drugs
														WHERE recipe_has_drugs.drugs_drugs_id=drugs_has_chemicals.drugs_id
														AND drugs_has_chemicals.chemicals_id=chemicals.chemicals_id
														AND pharmacy_has_chemicals.chemicals_chemicals_id=chemicals.chemicals_id
														AND pharmacy_pharmacy_id='.$_SESSION['userpharmacy'].
														' AND recipe_idRecipe='.$readyrecipe) or die(mysql_error());
					echo $readyrecipe;
					while($newreserve=mysql_fetch_array($changereserve))
					{
						//echo $newreserve['pharmacy_id'];
						echo ($newreserve['reserve']*1000000-$newreserve['amount']*10*$newreserve['packs_number'])/1000000;
						mysql_query('UPDATE pharmacy_has_chemicals SET reserve='.($newreserve['reserve']*1000000-$newreserve['amount']*10*$newreserve['packs_number'])/(1000000).' WHERE chemicals_chemicals_id='.$newreserve['chemicals_chemicals_id'].' AND pharmacy_pharmacy_id='.$_SESSION['userpharmacy']) or die(mysql_error());		
					}
					echo '<script language="javascript">
							document.location="accept_recipe.php";
							</script>';
				}
				
				echo '</form>';
			?>
		</div>
	</div>
 </body>
</html>