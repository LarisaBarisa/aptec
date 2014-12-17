<?php
	include_once('connect.php');
	session_start();
	if($_SESSION['userstatus']!="admin")
	{
		echo 'Вы не имеете доступа к данной странице';
		die;
	}
//	mysql_query("SET NAMES 'cp1251'");
//	mysql_query("SET CHARACTER SET 'cp1251'");
	header("Content-Type: text/html; charset=utf-8");
	
	class drugs {
	var $drugs_id;  // номер лекарства
    var $drugs_name;  // название лекартсва
    var $medical_use;    // назначение
    var $cost;  		// цена
    var $adverse_effects;  // побочные эффекты
    
    function drugs ($aa) 
    {
		foreach ($aa as $k=>$v)
            $this->$k = $aa[$k];
    }
}

function readDatabase($filename) 
{
    // чтение XML базы данных лекарств
    $data = implode("", file($filename));
    $parser = xml_parser_create("UTF-8");
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $data, $values, $tags);
    xml_parser_free($parser);

    // проход через структуры
    foreach ($tags as $key=>$val) {
        if ($key == "row") {
            $drugranges = $val;
            // каждая смежная пара значений массивов является верхней и
            // нижней границей определения лекарства
            for ($i=0; $i < count($drugranges); $i+=2) {
                $offset = $drugranges[$i] + 1;
                $len = $drugranges[$i + 1] - $offset;
                $tdb[] = parseMol(array_slice($values, $offset, $len));
            }
        } else {
            continue;
        }
    }
    return $tdb;
}

function parseMol($mvalues) 
{
    for ($i=0; $i < count($mvalues); $i++) {
        $mol[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
    }
    return new drugs($mol);
}

$db = readDatabase("pharmacy.xml");
echo "** База данных фармацевтики:\n";
echo $size=sizeof($db);
for ($i=0; $i<$size; $i++)
{
	echo $db[$i]->cost;
	/*$query=mysql_query('SELECT drugs_id FROM drugs WHERE drugs_id='.$db[$i]->drugs_id) or die(mysql_error());
	if(mysql_num_rows($query)==0)
	{
		print_r($db[$i]->drugs_name);
		$query=mysql_query('INSERT INTO drugs VALUES("'.$db[$i]->drugs_id.'","'.$db[$i]->drugs_name.'","'.$db[$i]->medical_use.'","'.$db[$i]->cost.'","'.$db[$i]->adverse_effects.'")') or die(mysql_error());
	}
	else*/
	{
		$query=mysql_query('UPDATE drugs SET cost='.$db[$i]->cost.' WHERE drugs_id='.$db[$i]->drugs_id) or die(mysql_error());
	}
}
echo 'Новые данные успешно добавлены';


?>
