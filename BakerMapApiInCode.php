<?php
    $aid = "";
    $code = "";
    if (isset($_REQUEST['aid'])) 
    {
        $aid = trim(rawurldecode(html_entity_decode(($_REQUEST['aid']))));
    }
    if (isset($_REQUEST['code'])) 
    {
        $code = $_REQUEST['code'];
    }


    $db = new SQLite3('code.db');
    $postgresdb = pg_connect("host=localhost dbname=stackoverflow user=s23subra password=coldplay123");
    $query1 = "";
    $query2 = "";



    if($aid != "") 
    {
        $query1 = "select charat, tname, cutype, line from types where aid = '{$aid}' order by charat";
        $query2 = "select charat, mname, cutype, line from methods where aid = '{$aid}' order by charat";
    } 
    else 
    {
        echo "Cannot identify aid.<br>";
        $query = null;
    }

    $result1 = $db->query($query1);
    $result2 = $db->query($query2);
    if(!$result1 && !$result2) 
    {
        //die("Cannot find examples in DB.\n");
        $stack = array();
        $json = json_encode($stack);
        echo $json;
    } 
    else 
    {
        $i = 0;
        $stack = array();
        while ($row = $result1->fetchArray()) 
        {
            $i = $i + 1;
            $charat = $row[0];
            $element = $row[1];
            $cutype = $row[2];
            $line = $row[3];
            $data = array("charat" => $charat, "element" => $element, "cutype" => $cutype, "line" => $line, "apitype" => "class");
            array_push($stack, $data);
        }
        while ($row = $result2->fetchArray()) 
        {
            $i = $i + 1;
            $charat = $row[0];
            $element = $row[1];
            $cutype = $row[2];
            $line = $row[3];
            $data = array("charat" => $charat, "element" => $element, "cutype" => $cutype, "line" => $line, "apitype" => "method");
            array_push($stack, $data);
        }
        $json = json_encode($stack);
        echo $json;
    }
?>
