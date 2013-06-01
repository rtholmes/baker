<?php
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br>";
  }
else
  {
  echo "Upload: " . $_FILES["file"]["name"] . "<br>";
  echo "Type: " . $_FILES["file"]["type"] . "<br>";
  echo "Size: " . ($_FILES["file"]["size"] / 1048576) . " MB<br>";
  //echo "Stored in: " . $_FILES["file"]["tmp_name"];
if (file_exists($_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],$_FILES["file"]["name"]);
      }

exec('java -jar xml_zip_gen.jar '.$_FILES["file"]["name"],$output_array);
$output="";
foreach($output_array as $c)
{
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
}
?>
