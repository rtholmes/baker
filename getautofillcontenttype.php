<?php

sleep(3);
// no term passed - just exit early with no response
if (empty($_GET['term']))
    exit;
$q = strtolower($_GET["term"]);
// remove slashes if they were magically added
if (get_magic_quotes_gpc())
    $q = stripslashes($q);


$db = new SQLite3('code.db');

$query   = "select tname from type_names where tname like \"" . $_GET['term'] . "%\"";
$result1 = $db->query($query);
$items   = array();
if ($result1==null) {
} 

else {
    while ($row = $result1->fetchArray()) {
        //$temp="\"".$row['tname']."\"=>\"".$row['tname']."\"";
        //echo $temp;
        array_push($items, $row['tname']);
    }
}

if($items==null)
{
	$query1   = "select tname from type_names where tname like \"%" . $_GET['term'] . "%\"";
	$result2 = $db->query($query1);
	if (!$result2) {
	} 
	else {
	    while ($row = $result2->fetchArray()) {
       	 	array_push($items, $row['tname']);
    	   }
	}	
}
echo json_encode($items);
//echo json_encode(array_slice($items, 0, 10));

?>
