<?php 
session_start();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="AddInputFunc.js"></script>
<link rel="stylesheet" href="index.css" type="text/css"/>
<title>�������� ���������</title>
</head>
<body>
	<div id="wrapper">
		<div id="header">�������� ���������</div>
		<div id="menu">
			<ul>
				<li><a href="personal_cabinet.php">������ �������</a></li>
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
			<form method="post" id="recipe_form">
			<?
					session_start();
			if (!$_SESSION['userid'])
			{
				echo '����� �������� ���������, ���������� <a href="login.php">��������������</a>';
				die;
			}
			echo $_SESSION['username'].', �������� ���������, ��������� � ����� �������';
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
				echo '����� ������ ������: '.$id;
				die;
				}
			}
			?>
				<br>
				<input type="button" id="AddButton" name="AddButton" value="��������" onclick="AddInput();" />
				<input type="submit" name="submit" ></input>
				<br>
			</form>
		</div>
	</div>
</body>
</html>
