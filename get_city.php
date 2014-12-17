<?php
include_once 'connect.php';
$region_id = @intval($_GET['region_id']);
 
$regs=mysql_query("SELECT city_name, city_id  FROM city WHERE region_id=$region_id");
 
if ($regs)
{
    $num = mysql_num_rows($regs);     
    $i = 0;
    while ($i < $num) {
       $citys[$i] = mysql_fetch_assoc($regs);  
       $i++;
    }    
    $result = array('citys'=>$citys); 
}
else {
    $result = array('type'=>'error');
}
print json_encode($result);
?>