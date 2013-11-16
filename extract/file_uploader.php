<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="utf-8" />
        <title>Android API search</title>

</head>

<link href="../style.css" rel="stylesheet" type="text/css" />

<body>
<div class="style3"></div><div class="style_2"><span class="style3"><a href="" title="Android API Search"><strong>Android API Search</strong></a></span></div>
<div id="wrap">
<div id="topbar">
<h1 id="sitename"><a href="../index.html">Android API Search</a></h1>
<div id="menus">
<ul id="topmenu">
<li><a href="../index.html">Home</a>
</li>
<li><a href="../onlineextractor.html">Snippet Parser</a>
</li>
<li><a href="../sodb.html">Snippet Search</a>
</li>
<li class="active"><a href="fileupload.html">Oracle Generator</a>
</li>
<!--<li><a href="#">Others</a>-->
</li>
</ul>
</div>
</div>
<?php
$file=$_POST['filename'];
//$file = 'https://dl.dropboxusercontent.com/u/67596885/android.jar';
$pieces=explode('/',$file);
foreach($pieces as $piece)
	$filename=$piece;
$newfile = $_SERVER['DOCUMENT_ROOT'] .'/snippet/extract/jars/'.$filename.'';
exec('mkdir -p jars');
if (copy($file, $newfile) ) {
    echo "File download from URL success!";
}else{
    echo "File download from URL failed.";
}
echo "<br><br>Building file for download.....";
exec('mkdir -p output');
exec('java -jar xml_zip_gen.jar jars/'.$filename,$output_array);
$output="";
foreach($output_array as $c)
{
//echo $c;
$output=$output.$c;
}


$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$pieces=explode('/',$actual_link,-1);
$link="";
foreach($pieces as $piece)
{
$link=$link.$piece.'/';
}
echo "<script> alert(\"The file is now ready!\") </script>";
echo "<br><br><a href=\"".$link."output/".$output."\">"."Click here to download the zippled XML for ".$filename."</a>";
?>
</body>
</html>
