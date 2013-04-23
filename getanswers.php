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
<li><a href="onlineextractor.html">Online API Extractor</a>
</li>
<li  class="active"><a href="sodb.html">Stack Overflow Snippet Database</a>
</li>
<li><a href="#">Others</a>
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
<script type="text/javascript">

/***********************************************
* IFrame SSI script II- © Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
* Visit DynamicDrive.com for hundreds of original DHTML scripts
* This notice must stay intact for legal use
***********************************************/

//Input the IDs of the IFRAMES you wish to dynamically resize to match its content height:
//Separate each ID with a comma. Examples: ["myframe1", "myframe2"] or ["myframe"] or [] for none:
var iframeids=["myframe"]

//Should script hide iframe from browsers that don't support this script (non IE5+/NS6+ browsers. Recommended):
var iframehide="yes"

var getFFVersion=navigator.userAgent.substring(navigator.userAgent.indexOf("Firefox")).split("/")[1]
var FFextraHeight=parseFloat(getFFVersion)>=0.1? 16 : 0 //extra height in px to add to iframe in FireFox 1.0+ browsers

function resizeCaller() {
var dyniframe=new Array()
for (i=0; i<iframeids.length; i++){
if (document.getElementById)
resizeIframe(iframeids[i])
//reveal iframe for lower end browsers? (see var above):
if ((document.all || document.getElementById) && iframehide=="no"){
var tempobj=document.all? document.all[iframeids[i]] : document.getElementById(iframeids[i])
tempobj.style.display="block"
}
}
}

function resizeIframe(frameid){
var currentfr=document.getElementById(frameid)
if (currentfr && !window.opera){
currentfr.style.display="block"
if (currentfr.contentDocument && currentfr.contentDocument.body.offsetHeight) //ns6 syntax
currentfr.height = currentfr.contentDocument.body.offsetHeight+FFextraHeight; 
else if (currentfr.Document && currentfr.Document.body.scrollHeight) //ie5+ syntax
currentfr.height = currentfr.Document.body.scrollHeight;
if (currentfr.addEventListener)
currentfr.addEventListener("load", readjustIframe, false)
else if (currentfr.attachEvent){
currentfr.detachEvent("onload", readjustIframe) // Bug fix line
currentfr.attachEvent("onload", readjustIframe)
}
}
}

function readjustIframe(loadevt) {
var crossevt=(window.event)? event : loadevt
var iframeroot=(crossevt.currentTarget)? crossevt.currentTarget : crossevt.srcElement
if (iframeroot)
resizeIframe(iframeroot.id);
}

function loadintoIframe(iframeid, url){
if (document.getElementById)
document.getElementById(iframeid).src=url
}

if (window.addEventListener)
window.addEventListener("load", resizeCaller, false)
else if (window.attachEvent)
window.attachEvent("onload", resizeCaller)
else
window.onload=resizeCaller
</script>

<?php
$name      = trim($_GET['name']);
$type      = $_GET['type'];
$precision = $_GET['precision'];
$name      = SQLite3::escapeString($name);
$name      = htmlentities($name);
//session_start(); 
//echo $precision;
$db        = new SQLite3('code.db');

if (!empty($_GET['name'])) {
    
    if (strcmp($type, 'apitype') == 0) {
        $query = "select tname,aid, codeid, charat from types where tname like '{$name}' and prob<={$precision}";
    } else if (strcmp($type, 'apimethod') == 0) {
        $query = "select mname,aid, codeid, charat from methods where mname like '{$name}' and prob<={$precision}";
    }
} else {
    echo "enter an API element<br>";
    $query = null;
}

$result = $db->query($query);
if (!$result) {
    
    die("Cannot find any example.\n");
} else {

    $i = 0;
    while ($row = $result->fetchArray()) {
        $i = $i + 1;
        echo "<h3>Example " . $i . " :</h3>";
        if (strcmp($type, 'apimethod') == 0) {
            echo "API method: " . $row['mname'] . "<br>";
        }
        if (strcmp($type, 'apitype') == 0) {
            echo "API type: " . $row['tname'] . "<br>";
        }
        //session_destroy();
	$url="http://stackoverflow.com/questions/" .$row['aid'];
        echo "Code snippet number: " . $row['codeid'] . "<br>";
        echo "Start location(char): " . $row['charat'] . "<br>";
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
