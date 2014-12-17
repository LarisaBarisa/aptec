<?php
	include_once 'connect.php';
/*
	mysql_query("SET NAMES 'cp1251'");
	mysql_query("SET CHARACTER SET 'cp1251'");
*/

	$regs=mysql_query("SELECT drugs_name,drugs_id,cost FROM drugs");
	if ($regs)
{
    $num = mysql_num_rows($regs);     
    $i = 0;
    while ($i < $num) {
       $drug_arr[$i] = mysql_fetch_array($regs);  
       $i++;
    }    
    $result = array('drugs'=>$drug_arr); 
}
else {
    $result = array('type'=>'error');
}
print json_encode($result);
?>