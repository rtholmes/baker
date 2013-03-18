<?php
echo("<html><body>");
echo("<script src=\"https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js\"></script>");
echo("<pre class=\"prettyprint linenums\">");

function printarray($array,$charat) 
{
	$temp=0;
	$flag=0;
	foreach ($array as $key => $value) {
	$temp=$temp+sizeof(str_split($value));
	if($temp<$charat-40 and $flag==0)
	{
		echo $value.PHP_EOL;
	}
	
	else if($flag==0)
	{
		//$flag=1;
		echo "<font  style=\"background-color: yellow\">".$value.PHP_EOL."</font>";
		if($temp>=$charat)
			$flag=1;
	}
	else if($flag==1)
	{
		echo $value.PHP_EOL;
	}
   }
}

function getlines($array) 
{
	$temp=explode(PHP_EOL,$array);
	return sizeof($temp);
}

function getlineforchar($array,$charat,$code)
{
	$temp=0;
	$flag=0;
	$line=0;
	foreach ($array as $key => $value) {
	$temp=$temp+sizeof(str_split($value));
	$line=$line+1;
	if($temp<$charat and $flag==0)
	{
		//echo $value.PHP_EOL;
	}
	
	else if($flag==0)
	{
		//$flag=1;
		//echo "<font  style=\"background-color: yellow\">".$value.PHP_EOL."</font>";
		if($temp>=$charat)
			$flag=1;
		return $line;
	}
   }
return getlines($code);
}

///////////////////////////////main////////////////////////////////////////////////////////

	$db = new SQLite3('code.db');
	$codeid=$_GET['codeid'];
	$aid=$_GET['aid'];
	
	
	if (isset($_GET['charat'])) {
	    $charat=$_GET['charat'];
	}
	else{
		$charat=0;
	}
	if (isset($_GET['type'])) {
	    $type=$_GET['ftype'];
	}
	else{
		$type='';
	}
	require('simplehtmldom/simple_html_dom.php');
	$url = "http://stackoverflow.com/questions/".$aid;
	$html = file_get_html($url);
	//echo $html;
	foreach($html->find('div') as $div) 
	{		
    		if(strcmp('answer accepted-answer',$div->class)==0)
		{
			$count=0;
			foreach($div->find('code') as $code)
			{	
				//echo getlines($code);
				if(getlines($code)>2)
				{
				$count=$count+1;
				if($count==$codeid)
					{
					//echo $code;
					$temp=explode(PHP_EOL,$code);
					printarray($temp,$charat);
					break;
					}
				}
			}

		}
	}
echo("</pre>");
$query1="select tname,charat,prob from types where aid= '{$aid}' and codeid={$codeid} order by prob";
$query2="select mname,charat,prob from methods where aid= '{$aid}' and codeid={$codeid} order by prob";
$query3="select mname,charat,prob,1 from methods where aid= '{$aid}' and codeid={$codeid} UNION ALL select tname,charat,prob,2 from types where aid= '{$aid}' and codeid={$codeid} ORDER BY charat";
$result1 = $db->query($query1);
$result2 = $db->query($query2);
$result3 = $db->query($query3);
if (!$result1) 
{
	
	die("Cannot find any example.\n");
}
else
{	echo "Other Android API types:<br>";
	echo "<table border=\"1\">";
	echo "<th>API Type</th><th>Precision</th><th>Location (line no.)</th>";
	while ($row = $result1->fetchArray()) {
		$lno_end=getlineforchar($temp,$row['charat'],$code);
		$lno_start=getlineforchar($temp,$row['charat'],$code)-2;
		if($lno_start<0)
			$lno_start=0;
		echo "<tr><td>".$row['tname']."</td><td>".$row['prob']."</td><td>".$lno_start."-".$lno_end."</td></tr>";	
	}
	echo "</table><br><br>";
	echo "Other Android API methods:<br>";
	echo "<table border=\"1\">";
	echo "<th>API Method</th><th>Precision</th><th>Location (line no.)</th>";
	while ($row = $result2->fetchArray()) {
		$lno_end=getlineforchar($temp,$row['charat'],$code);
		$lno_start=getlineforchar($temp,$row['charat'],$code)-2;
		if($lno_start<0)
			$lno_start=0;
		echo "<tr><td>".$row['mname']."</td><td>".$row['prob']."</td><td>".$lno_start."-".$lno_end."</td></tr>";
	}
	echo "</table><br><br>";
	echo "Methods and Types sorted by line number";
	echo "<table border=\"1\">";
	echo "<th>API Element</th><th>Method/Type</th><th>Precision</th><th>Location (line no.)</th>";
	while ($row = $result3->fetchArray()) {
		$lno_end=getlineforchar($temp,$row['charat'],$code);
		$lno_start=getlineforchar($temp,$row['charat'],$code)-2;
		if($lno_start<0)
			$lno_start=0;
		if($row[3]==1)
			$type1="type";
		if($row[3]==2)
			$type1="method";

		echo "<tr><td>".$row['mname']."</td><td>".$type1."</td><td>".$row['prob']."</td><td>".$lno_start."-".$lno_end."</td></tr>";	
	}
	echo "</table><br><br>";
}
echo "</table>";
echo("</body></html>");


?>
