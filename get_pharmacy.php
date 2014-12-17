<?php
include_once 'connect.php';
$city_id = @intval($_GET['city_id']);
 
$regs=mysql_query("SELECT address
					FROM address,pharmacy, city
					WHERE city.city_id=address.city_id
					AND address.address_id=pharmacy.address_id
					AND city.city_id=$city_id");
 
if ($regs) {
    $num = mysql_num_rows($regs);     
    $i = 0;
    while ($i < $num) {
       $pharmacys[$i] = mysql_fetch_assoc($regs);  
       $i++;
    }    
    $result = array('pharmacys'=>$pharmacys); 
}
else {
    $result = array('type'=>'error');
}
 
print json_encode($result);
?>