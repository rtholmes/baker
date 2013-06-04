<?php

$file = 'https://dl.dropboxusercontent.com/u/67596885/android.jar';
$pieces=explode('/',$file);
foreach($pieces as $piece)
	$filename=$piece;
//echo $filename
$newfile = $_SERVER['DOCUMENT_ROOT'] .'/snippet/extract/jars/'.$filename.'';
exec('mkdir jars');
echo $newfile;
if (copy($file, $newfile) ) {
    echo "Copy success!";
}else{
    echo "Copy failed.";
}
//  echo "Upload: " . $_FILES["file"]["name"] . "<br>";
//  echo "Type: " . $_FILES["file"]["type"] . "<br>";
//  echo "Size: " . ($_FILES["file"]["size"] / 1048576) . " MB<br>";
//echo "Stored in: " . $_FILES["file"]["tmp_name"];

exec('java -jar xml_zip_gen.jar jars/'.$filename,$output_array);
$output="";
foreach($output_array as $c)
{
echo $c;
$output=$output.$c;
}


$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$pieces=explode('/',$actual_link,-1);
$link="";
foreach($pieces as $piece)
{
$link=$link.$piece.'/';
}
echo "<br><br><a href=\"".$link.$output."\">"."Click here to download the file"."</a>";
?>
