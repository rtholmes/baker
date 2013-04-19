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

$query   = "select mname from method_names where mname like \"" . $_GET['term'] . "%\"";
$result1 = $db->query($query);
$items   = array();
if (!$result1) {
} else {
    while ($row = $result1->fetchArray()) {
        //$temp="\"".$row['mname']."\"=>\"".$row['mname']."\"";
        //echo $temp;
        array_push($items, $row['mname']);
    }
}
if($items==null)
{
	$query1   = "select mname from method_names where mname like \"%" . $_GET['term'] . "%\"";
	$result2 = $db->query($query1);
	if (!$result2) {
	} 
	else {
	    while ($row = $result2->fetchArray()) {
       	 	array_push($items, $row['mname']);
    	   }
	}	
}

//echo json_encode(array_slice($items, 0, 10));
echo json_encode($items);
?>
