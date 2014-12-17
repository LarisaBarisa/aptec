<?php
	session_start();
	include_once('connect.php');
	if($_SESSION['userstatus']!="pharmacist")
	{
		echo 'У вас нет доступа к данной странице.  <a href=index.php>На главную</a>';
		die;
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="index.css" type="text/css"/>
<link rel="stylesheet" href="login.css" type="text/css"/>
<title>Выдача заказа</title>
</head>
 <body>
	<div id="wrapper">
		<div id="header">Выдача заказа
		</div>
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
			<form method="POST">
				<input type="text" name="recipe_id" id="recipe_id" placeholder="Введите номер заказа" required/>
				<input type="submit" name="submit" id="submit"/>
			</form>
			<?
				mysql_query("SET NAMES 'cp1251'");
				mysql_query("SET CHARACTER SET 'cp1251'");

				global $id;
				if(isset($_POST['submit']))
				{
					$recipe_id=$_POST['recipe_id'];
					$query=mysql_query('SELECT address,idRecipe,recipe.status, first_name, last_name FROM recipe, address, patient WHERE recipe.pharmacy_id='.$_SESSION['userpharmacy'].
										' AND idRecipe='.$recipe_id.
										' AND patient.patient_id=recipe.patient_id
										 AND address.address_id=patient.address_id') or die(mysql_error());
					$cost=0;
					($result=mysql_fetch_array($query));
					{
						$id=$recipe_id;
						echo '№'.$result['idRecipe'].' '.$result['last_name'].' '.$result['first_name'].' '.$result['address'].' - '.$result['status'].'<br>';
						$getrecipedrugs=mysql_query('SELECT drugs_name, drugs_id, cost, packs_number FROM recipe_has_drugs, drugs
												WHERE recipe_idRecipe='.$result['idRecipe'].'
												AND recipe_has_drugs.drugs_drugs_id=drugs.drugs_id') or die(mysql_error());
												
						while($resultdrugs=mysql_fetch_array($getrecipedrugs))
						{
							$cost+=$resultdrugs['cost']*$resultdrugs['packs_number'];
							echo $resultdrugs['drugs_name'].' - '.$resultdrugs['packs_number'].'(уп) - '.$resultdrugs['cost']*$resultdrugs['packs_number'].'р.<br>';
						}
						
						echo '<b>Итоговая стоимость: '.$cost.'р.<b><br>';
						switch ($result['status'])
						{
							case "new":
								echo 'Заказ поступил';
								break;
							
							case "taken":
								echo 'Заказ готовится';
								break;
							
							case "ready":
									echo '<form method="POST"><input type="submit" name="success['.$recipe_id.']" value="выдать заказ"/></form>';
								break;
								
							case "success":
								echo 'Заказ уже выдан';
								break;
						}
					//if($result['status']=="ready")
						//echo '<form method="POST"><input type="submit" name="success['.$recipe_id.']" value="выдать заказ"/></form>';
					
					}
				}
				if(isset($_POST['success']))
				{	
					mysql_query("set names 'utf8'");
					mysql_query ("set character_set_client='utf8'");
					mysql_query ("set character_set_results='utf8'");
					mysql_query ("set collation_connection='utf8_general_ci'");
					$success=key($_POST['success']);
					echo 'success'.$id;
					$query=mysql_query('UPDATE recipe SET status="success" WHERE idRecipe='.$success) or die(mysql_error());
					$query=mysql_query('SELECT * FROM recipe,patient WHERE idRecipe='.$success.'
										 AND recipe.patient_id=patient.patient_id') or die(mysql_error());
					$res=mysql_fetch_array($query);
					$pharmacist=$res['pharmacist_id'];
					$pharmacy=$res['pharmacy_id'];
					$filerecipe='';
					$filerecipe.= '<?xml version="1.0" encoding="utf-8" ?>
							<information>
								<recipe>
									<recipe_id>'.$res['idRecipe'].'</recipe_id>
									<date>'.$res['date'].'</date>
									<patient>'.$res['first_name'].' '.$res['last_name'].'</patient>
								</recipe>';
					$getrecipedrugs=mysql_query('SELECT drugs_name, drugs_id, cost, packs_number FROM recipe_has_drugs, drugs
												WHERE recipe_idRecipe='.$success.'
												AND recipe_has_drugs.drugs_drugs_id=drugs.drugs_id') or die(mysql_error());
					while($res=mysql_fetch_array($getrecipedrugs))
					{		$filerecipe.='<drugs>
									<name>'.$res['drugs_name'].'</name>
									<packs_number>'.$res['packs_number'].'</packs_number>
									<cost>'.$res['cost'].'</cost>';
								
							
							$getdrugscemicals=mysql_query('SELECT * FROM chemicals, drugs_has_chemicals, pharmacy_has_chemicals, recipe_has_drugs
														WHERE drugs_has_chemicals.drugs_id='.$res['drugs_id'].'
														AND recipe_has_drugs.drugs_drugs_id=drugs_has_chemicals.drugs_id
														AND drugs_has_chemicals.chemicals_id=chemicals.chemicals_id
														AND pharmacy_has_chemicals.chemicals_chemicals_id=chemicals.chemicals_id
														AND recipe_idRecipe='.$success.
														' AND pharmacy_pharmacy_id='.$pharmacy) or die(mysql_error());
							while($res=mysql_fetch_array($getdrugscemicals))
							{
								$filerecipe.='<chemical>
										<name>'.$res['title'].'</name>
										<amount>'.$res['amount'].'</amount>
									</chemical>';
							}
							$filerecipe.= '</drugs>';
					}
					$query=mysql_query('SELECT * FROM pharmacy,address WHERE pharmacy.address_id=address.address_id
										AND pharmacy_id='.$pharmacy) or die(mysql_error());
					$query1=mysql_query('SELECT * FROM patient WHERE patient_id='.$pharmacist) or die(mysql_error());
					$res=mysql_fetch_array($query);
					$res1=mysql_fetch_array($query1);
					$filerecipe.= '<pharmacy>
							<address>'.$res['address'].'</address>
							<pharmacist>'.$res1['first_name'].' '.$res1['last_name'].'</pharmacist>
						</pharmacy>
					</information>';
					
					$fp = fopen ('recipe.xml', "w");
					fwrite($fp, $filerecipe);
					fclose($fp);
				}
			?>
		</div>
	</div>
</body>
</html>