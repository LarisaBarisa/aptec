<?php
	include_once('connect.php');
	session_start();
	if($_SESSION['userstatus']!="pharmacist")
	{
		echo '� ��� ��� ������� � ������ ��������.  <a href=index.php>�� �������</a>';
		die;
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="index.css" type="text/css"/>
<title>�������� �������</title>
</head>
 <body>
	<div id="wrapper">
		<div id="header">
			������ ������
		</div>
		<div id="menu">
			<ul>
				<li><a href="index.php">�� �������</a></li>
				<li><a href="recipe_request.php">�������� ��������</a></li>
				<?if($_SESSION['userstatus']=='pharmacist')
				{	
					echo'<li><a href="taken_recipes_list.php">������ ������</a></li>';
					echo'<li><a href="recipe_to_patient.php">������ �����</a></li>';
					echo'<li><a href="accept_recipe.php">����� ������</a></li>';
				}?>
			</ul>
		</div>
		<div style="float:right; width:485px;">
			<?
				mysql_query("SET NAMES 'cp1251'");
				mysql_query("SET CHARACTER SET 'cp1251'");
				if($_SESSION['userstatus']=="patient")
				{
					echo '� ��� ��� ������� � ������ ��������';
					die;
				}
				
				$query=mysql_query('SELECT * FROM recipe') or die(mysql_error());
				
				$getnewrecipe=mysql_query('SELECT *
											FROM recipe 
											WHERE recipe.status="taken"
											AND pharmacist_id='.$_SESSION['userid'].
											' AND pharmacy_id='.$_SESSION['userpharmacy']) or die(mysql_error());
				$i=0;							
				echo '<form method="post" >';
				echo '�� ������� �� ��������� ������ (����� '.mysql_num_rows($getnewrecipe).'):<br>';
				while($result=mysql_fetch_array($getnewrecipe))
				{
					echo "<b><br>����� �".$result['idRecipe']." �������� ".$result['date'].":</b><br>";
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
								echo '<b style="color:#FF0000;"> � '.$resultchem['title'].' - '.$resultchem['amount'].'��. ('.$resultchem['packs_number'].' ��)  !������������ ���������!  ��������:'.$resultchem['reserve'].'��</b><br>';
							}
							else
							{	
								echo ' � '.$resultchem['title'].' - '.$resultchem['amount'].'-'.'��. ('.$resultchem['packs_number'].' ��) ��������:'.$resultchem['reserve'].'��<br>';
							}
						}
					}
					
					echo '<input type="submit" name="ready['.$result['idRecipe'].']" id="ready['.$result['idRecipe'].']" value="���������"/></br>';
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
					$i++;
				}
			?>
		</div>
	</div>
 </body>
</html>