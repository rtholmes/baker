<?php
	$interval = 3000 * 60; // 30 minutes * 60 seconds per minute
	$filename = "cache/test/".$_REQUEST['aid']."-".$_REQUEST['codeid']."-".$_REQUEST['charat'];
	//$filename = "cache/".basename(rtrim($_SERVER["REQUEST_URI"], '/')).".cache";
// serve from the cache if less than 30 minutes have passed since the file was created
	if ( file_exists($filename) && (time() - $interval) < filemtime($filename) ) {
		//echo "<script>alert(\"cache\")</script>";
		readfile($filename);
		exit(); // Terminate so we don't regenerate the page.
	}
ob_start(); // This function saves all output to a buffer instead of outputting it directly.

	// PHP page generation code goes here
?>
<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="utf-8" />
        <title>Android API search</title>

</head>

<link href="style.css" rel="stylesheet" type="text/css" />

<body>
<div class="style3"></div><div class="style_2"><span class="style3"><a href="" title="Android API Search"><strong>Android API Search</strong></a></span></div>
<div id="wrap">
<div id="topbar">
<h1 id="sitename"><a href="index.html">Android API Search</a></h1>
<div id="menus">
<ul id="topmenu">
<li><a href="index.html">Home</a>
</li>
<li><a href="onlineextractor.html">Snippet Parser</a>
</li>
<li><a href="sodb.html">Snippet Search</a>
</li>
<!--<li><a href="#">Others</a>-->
</li>
</ul>
</div>
</div>

<?php
echo("<html><body>");
echo("<script src=\"google-code-prettify/run_prettify.js\"></script>");
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
	$codeid=$_POST['codeid'];
	$aid=$_POST['aid'];
	
	
	if (isset($_POST['charat'])) {
	    $charat=$_POST['charat'];
	}
	else{
		$charat=0;
	}
	if (isset($_POST['type'])) {
	    $type=$_POST['type'];
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
$query1="select tname,charat,prob,line from types where aid= '{$aid}' and codeid={$codeid} order by prob";
$query2="select mname,charat,prob,line from methods where aid= '{$aid}' and codeid={$codeid} order by prob";
$query3="select mname,charat,prob,line,1 from methods where aid= '{$aid}' and codeid={$codeid} UNION ALL select tname,charat,prob,line,2 from types where aid= '{$aid}' and codeid={$codeid} ORDER BY charat";
$result1 = $db->query($query1);
$result2 = $db->query($query2);
//$result3 = $db->query($query3);
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
		echo "<tr><td><a href=\"getanswers.php?type=apitype&name=".htmlentities(rawurlencode($row['tname']))."&precision=5\">".htmlspecialchars($row['tname'])."</a></td><td>".$row['prob']."</td><td>".$row['line']."</td></tr>";	
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
		echo "<tr><td><a href=\"getanswers.php?type=apimethod&name=".htmlentities(rawurlencode($row['mname']))."&precision=5\">".htmlspecialchars($row['mname'])."</a></td><td>".$row['prob']."</td><td>".$row['line']."</td></tr>";
	}
	echo "</table><br><br>";
	/*echo "Methods and Types sorted by line number";
	echo "<table border=\"1\">";
	echo "<th>API Element</th><th>Method/Type</th><th>Precision</th><th>Location (line no.)</th>";
	while ($row = $result3->fetchArray()) {
		$lno_end=getlineforchar($temp,$row['charat'],$code);
		$lno_start=getlineforchar($temp,$row['charat'],$code)-2;
		if($lno_start<0)
			$lno_start=0;
		if($row[4]==1)
			$type1="type";
		if($row[4]==2)
			$type1="method";

		echo "<tr><td>".$row['mname']."</td><td>".$type1."</td><td>".$row['prob']."</td><td>".$row['line']."</td></tr>";	
	}
	echo "</table><br><br>";*/
}
echo("</body></html>");
$buff = ob_get_contents(); // Retrive the content from the buffer

	// Write the content of the buffer to the cache file
	//$file = fopen( $filename, \"w\"" );
if (!file_exists('cache')) {
	    mkdir('cache');
	}
if (!file_exists('cache/test')) {
	    mkdir('cache/test');
	}

	file_put_contents($filename, $buff);

	ob_end_flush(); // Display the generated page.

?>
