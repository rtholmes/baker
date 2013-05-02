<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="utf-8" />
        <title>Android API search</title>

</head>

<link href="style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="jqcloud/jqcloud.css" />
<script type="text/javascript" src="jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="jqcloud/jqcloud-1.0.3.min.js"></script>

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
<li class="active"><a href="sodb.html">Snippet Search</a>
</li>
<!--<li><a href="#">Others</a>-->
</li>
</ul>
</div>
</div>

<style>
#frame {
    width: 800px;
    height: 520px;
    border: none;
    -moz-transform: scale(0.2);
    -moz-transform-origin: 0 0;
    -o-transform: scale(0.2);
    -o-transform-origin: 0 0;
    -webkit-transform: scale(0.2);
    -webkit-transform-origin: 0 0;
}
</style>

<?php
$name="";
$precision="";
$type="";
if (isset($_REQUEST['name'])) {
$name      = trim($_REQUEST['name']);
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

if (!empty($_REQUEST['name'])) {
    
    if (strcmp($type, 'apitype') == 0) {
        $query = "select tname,aid, codeid, charat,prob from types where tname like '{$name}' and prob<={$precision}";
 	$other_top=" select count(tname),types.tname from types, (select aid, codeid from types where tname=\"".$name."\" and prob=1) as temp where types.aid=temp.aid and types.codeid=temp.codeid group by types.tname order by count(tname) DESC limit 15";
    } else if (strcmp($type, 'apimethod') == 0) {
        $query = "select mname,aid, codeid, charat,prob from methods where mname like '{$name}' and prob<={$precision}";
	$other_top="select count(mname),methods.mname from methods, (select aid, codeid from methods where mname=\"".$name."\" and prob=1) as temp where methods.aid=temp.aid and methods.codeid=temp.codeid group by methods.mname order by count(mname) DESC limit 15";
    }
} else {
    echo "Enter avalid API element.<br>";
    $query = null;
}

$result = $db->query($query);
$result1=$db->query($other_top);
if (!$result1) {
    die("Cannot find any other commonly used API.\n");
}
else{
echo "<div id=\"wordcloud\" style=\"width:800px; height: 400px; position: relative;float:right;\">";
echo "<h2><center>Other API commonly used with ".$name."</center></h2></div>";
echo "<script type=\"text/javascript\">";

echo   "var word_array = [";
while ($row = $result1->fetchArray()) {
if($row[1]!=$name && $row[1]!="int" && $row[1]!="float" && $row[1]!="char" && $row[1]!="byte" && $row[1]!="int[]" && $row[1]!="byte[]" && $row[1]!="char[]")
	echo "{text: \"".$row[1]."\", weight:".$row[0].", link:\"http://localhost/snippet/getanswers.php?type=".$type."&name=".$row[1]."&precision=5\"},";
}
echo "{}];";


echo "$(function() {  $(\"#wordcloud\").jQCloud(word_array); });";
echo "</script>";

}

if (!$result) {
    
    die("Cannot find any example.\n");
} else {

    $i = 0;
    while ($row = $result->fetchArray()) {
        $i = $i + 1;
        echo "<h3>Example " . $i . " :</h3>";
        if (strcmp($type, 'apimethod') == 0) {
            //echo "API method: " . $row['mname'] . "<br>";
        }
        if (strcmp($type, 'apitype') == 0) {
            //echo "API type: " . $row['tname'] . "<br>";
        }
	$url="http://stackoverflow.com/questions/" .$row['aid'];
        echo "Precision: " . $row['prob'] . "<br>";
        //echo "Start location(char): " . $row['charat'] . "<br>";
	echo "URL: <a href=\"".$url."\">".$url."</a><br>";
	
        echo "<form method=\"post\" action=\"test.php\" >";
        echo "<input type=\"hidden\" name=\"aid\" value=\"" . $row['aid'] . "\">";
        echo "<input type=\"hidden\" name=\"codeid\" value=\"" . $row['codeid'] . "\">";
        echo "<input type=\"hidden\" name=\"charat\" value=\"" . $row['charat'] . "\">";
        echo "<input type=\"hidden\" name=\"ftype\" value=\"" . $type . "\"><br>";
        echo "<input type=\"submit\" id =\"submit\" value=\"view code!\"  style=\"width: 100px; height: 30px\">";
        echo "</form>";

	
        echo "</br></br></br>";
    }
}
?>
