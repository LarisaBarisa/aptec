<?session_start();?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="index.css" type="text/css"/>
<link rel="stylesheet" href="login.css" type="text/css"/>
<title>������ �������</title>
</head>
 <body>
	<div id="wrapper">
		<div id="header">
			������ �������
		</div>
		<div id="menu">
			<ul>
				<li style="background:#EAF4F9;"><a href="index.php">�� �������</a></li>
				<li><a href="recipe_request.php">�������� ��������</a></li>
				<?if($_SESSION['userstatus']=='pharmacist')
				{	
					echo'<li><a href="taken_recipes_list.php">������ ������</a></li>';
					echo'<li><a href="recipe_to_patient.php">������ �����</a></li>';
					echo'<li><a href="accept_recipe.php">����� ������</a></li>';
				}?>
				<li><a>������ � ��������</a></li>
			</ul>
		</div>
		<div style="width:485px; margin:0; float:right; padding:5px;">
			<?php
				if(!$_SESSION['userid'])
				{
					echo '����� ������������� ������ ��������, ���������� <a href=login.php>��������������</a>';
					die;
				}
				include_once('connect.php');
				mysql_query("SET NAMES 'cp1251'");
				mysql_query("SET CHARACTER SET 'cp1251'");
				$query=mysql_query('SELECT * FROM patient WHERE patient_id='.$_SESSION['userid']) or die(mysql_error());
				$result=mysql_fetch_array($query);
					echo '���� ������: <b><br>e-mail</b>: '.$result['email'].'<br>';
					echo '<b>���</b>: '.$result['first_name'].'<br>';
					echo '<b>�������</b>: '.$result['last_name'].'<br>';
					echo '<b>�������</b>: '.$result['mobile_phone'].'<br><br>';
				
				$query=mysql_query('SELECT * FROM recipe WHERE patient_id='.$_SESSION['userid'].' AND status="ready"') or die(mysql_error());
				echo '� ��� ������ ����� ������ (<b>�����:'.mysql_num_rows($query).'</b>): <br>';
				while($result=mysql_fetch_array($query))
				{
					echo '<br>����� �'.$result['idRecipe'].':<br>';
					$getrecipedrugs=mysql_query('SELECT drugs_name, drugs_id FROM recipe_has_drugs, drugs
												WHERE recipe_idRecipe='.$result['idRecipe'].'
												AND recipe_has_drugs.drugs_drugs_id=drugs.drugs_id') or die(mysql_error());
					
					while($resultdrugs=mysql_fetch_array($getrecipedrugs))
						echo ' � '.$resultdrugs['drugs_name'].'<br>';
				}
				if($_SESSION['userstatus']=='pharmacist')
			?>
		</div>
	</div>
 </body>
</html>