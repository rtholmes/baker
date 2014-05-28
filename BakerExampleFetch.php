<?php
    $name = "";
    $precision = "";
    $type = "";
    if (isset($_REQUEST['name'])) 
    {
        $name      = trim(rawurldecode(html_entity_decode(($_REQUEST['name']))));
    }
    if (isset($_REQUEST['precision'])) 
    {
        $precision = $_REQUEST['precision'];
    }


    $name      = SQLite3::escapeString($name);
    $name      = htmlentities($name);
    
    $db        = new SQLite3('code.db');
    //$db        = new SQLite3('javadb.db');
    $postgresdb = pg_connect("host=localhost dbname=stackoverflow user=s23subra password=coldplay123");
    $query1 = "";
    $query2 = "";
    


    if(!empty($_REQUEST['name'])) 
    {
        //$query1 = "select map.qid, map.aid, types.codeid, line, charat from types, map where tname like '%{$name}%' and prob<={$precision} and types.aid = map.aid";
        //$query2 = "select map.qid, map.aid, methods.codeid, line, charat from methods, map where mname like '%{$name}%' and prob<={$precision} and methods.aid = map.aid";
        $query1 = "select qid, aid, codeid, line, charat from types where tname like '%{$name}%' and prob<={$precision}";
        $query2 = "select qid, aid, codeid, line, charat from methods where mname like '%{$name}%' and prob<={$precision}";
    } 
    else 
    {
        echo "Cannot identify API Element.<br>";
        $query = null;
    }

    $result1 = $db->query($query1);
    $result2 = $db->query($query2);
    if(!$result1 && !$result2) 
    {
        die("Cannot find examples in DB.\n");
        $stack = array();
        $json = json_encode($stack);
        echo $json;
    } 
    else 
    {
        $i = 0;
        $stack = array();
        //echo "<table>";
        //echo "<tr><th>#</th><th>Post</th><th>Date</th></tr>";
        while ($row = $result1->fetchArray()) 
        {
            $i = $i + 1;
            $pid = $row[0];
            $postgresQuery = "select title, last_activity_date from posts where id = {$pid} LIMIT 1";
            $title = "";
            $date = "";
		      $line = $row[3];
		      $codeid = $row[2];
            $postgresResult = pg_query($postgresdb, $postgresQuery);
            while ($pgRow = pg_fetch_row($postgresResult)) 
            {
                $title = $pgRow[0];
                $date = $pgRow[1];
                break;
            }
            $url="http://stackoverflow.com/questions/" .$row[1];
            //echo "<tr>";
            //echo "<td>".$i."</td>";
            //echo "<td><a href = \"".$url."\">".$title."</a></td>";
            //echo "<td>".$date."</td>";
            //echo "</tr>";
            $data = array("index" => $i, "url" => $url, "line" => $line, "codeid" => $codeid, "title" => $title, "type" => "api_type", "date" => $date);
            array_push($stack, $data);
        }
        while ($row = $result2->fetchArray()) 
        {
            $i = $i + 1;
            $pid = $row[0];
            $postgresQuery = "select title, last_activity_date from posts where id = {$pid} LIMIT 1";
            $title = "";
            $date = "";
		$line = $row[3];
		$codeid = $row[2];
            $postgresResult = pg_query($postgresdb, $postgresQuery);
            while ($pgRow = pg_fetch_row($postgresResult)) 
            {
                $title = $pgRow[0];
                $date = $pgRow[1];
                break;
            }
            $url="http://stackoverflow.com/questions/" .$row[1];
            //echo "<tr>";
            //echo "<td>".$i."</td>";
            //echo "<td><a href = \"".$url."\">".$title."</a></td>";
            //echo "<td>".$date."</td>";
            //echo "</tr>";
            $data = array("index" => $i, "url" => $url, "line" => $line, "codeid" => $codeid, "title" => $title, "type" => "api_method", "date" => $date);
            array_push($stack, $data);
        }
        //echo "</table>";
        $json = json_encode($stack);
        echo $json;
    }

?>
