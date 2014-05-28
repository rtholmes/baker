


<!DOCTYPE html>
<html lang="en" >

<body>
    <?php
    $name="";
    $precision="";
    $type="";
    if (isset($_REQUEST['name'])) {
        echo "<script>alert(".$_REQUEST['name'].")</script>";
        $name      = trim(rawurldecode(html_entity_decode(($_REQUEST['name']))));

    }
    if (isset($_REQUEST['precision'])) {
        $precision = $_REQUEST['precision'];
    }
    if (isset($_REQUEST['type'])) {
        $type      = $_REQUEST['type'];
    }

    $name      = SQLite3::escapeString($name);
    $name      = htmlentities($name);
    $db        = new SQLite3('code.db');
    $query="";
    $other_top="";

    if (!empty($_REQUEST['name'])) 
    {
        if (strcmp($type, 'apitype') == 0) 
        {
            $query = "select count(*) from types where tname like '{$name}' and prob<={$precision}";
        } 
        else if (strcmp($type, 'apimethod') == 0) 
        {
            $query = "select count(*) from methods where mname like '{$name}' and prob<={$precision}";
        }
    } 
    else 
    {
        echo "Enter avalid API element.<br>";
        $query = null;
    }

    $result = $db->query($query);
    $count;
    if (!$result) 
    {
     	$count = 0;
    }

    else
    {
	$row = $result->fetchArray();
	$count = $row[0];
    }	
echo $count;
    ?>
