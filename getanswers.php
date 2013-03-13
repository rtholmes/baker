<?php
$name=trim($_POST['name']);
$type=$_POST['type'];
$precision=$_POST['precision'];
$name=SQLite3::escapeString($name);
$name=htmlentities($name);
//session_start(); 
//echo $precision;
$db = new SQLite3('code.db');

if (!empty($_POST['name'])) {

	if(strcmp($type,'apitype')==0)
	{
		$query="select tname,aid, codeid, charat from types where tname like '{$name}' and prob<={$precision}";
	}
	else if(strcmp($type,'apimethod')==0)
	{
		$query="select mname,aid, codeid, charat from methods where mname like '{$name}' and prob<={$precision}";
	}
}
else
{
echo"enter an API element<br>";
$query=null;
}

$result = $db->query($query);
if (!$result) 
{
	
	die("Cannot find any example.\n");
}
else
{
	$i=0;
	while ($row = $result->fetchArray()) {
		$i=$i+1;
		echo "Example ".$i." :<br>";
		if(strcmp($type,'apimethod')==0)
   		{
			echo "API method: ".$row['mname']."<br>";
		}
		if(strcmp($type,'apitype')==0)
		{
			echo "API type: ".$row['tname']."<br>";
		}
		//session_destroy();
		echo "URL: "."<a href="."http://stackoverflow.com/questions/".$row['aid'].">http://stackoverflow.com/questions/".$row['aid']."</a>"."<br>";
	   	echo "Code snippet number: ".$row['codeid']."<br>";
		echo "Start location(char): ".$row['charat']."<br>";
		echo "<form method=\"post\" action=\"test.php\">";
		echo "<input type=\"hidden\" name=\"aid\" value=\"".$row['aid']."\">";
		echo "<input type=\"hidden\" name=\"codeid\" value=\"".$row['codeid']."\">";
		echo "<input type=\"hidden\" name=\"charat\" value=\"".$row['charat']."\">";
         	//$_POST['aid'] = $row['aid'];
		//$_POST['codeid']=$row['codeid'];
		//$_POST['charat']=$row['charat'];
		echo "<input type=\"submit\" value=\"view code!\">";
		echo "</form>";
		//echo "<a href= \"test.php\"> Click to view code "."</a>";
		echo "</br></br></br>";
		}
}
?>
