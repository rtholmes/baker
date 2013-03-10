<?php
$name=trim($_GET['name']);
$type=$_GET['type'];
$precision=$_GET['precision'];
//echo $precision;
$db = new SQLite3('code.db');
if(strcmp($type,'apitype')==0)
{
$query="select tname,aid, codeid, charat from types where tname like '{$name}' and prob<={$precision}";
}
else if(strcmp($type,'apimethod')==0)
{
$query="select mname,aid, codeid, charat from methods where mname like '{$name}' and prob<={$precision}";
}
$result = $db->query($query);
if (!$result) 
{
echo "not a valid API element";
die("Cannot find any example.");
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
	echo "URL: "."<a href="."http://stackoverflow.com/questions/".$row['aid'].">http://stackoverflow.com/questions/".$row['aid']."</a>"."<br>";
   	echo "Code snippet number: ".$row['codeid']."<br>";
	echo "Start location(char): ".$row['charat']."<br>";
	echo "<a href= test.php/?aid=".$row['aid']."&codeid=".$row['codeid']."> Click to view code "."</a>";
	echo "<br>";
}
}
?>
