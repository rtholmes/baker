<?php


$code=$_POST['pastedcode'];
$file="sample.txt";
$output=null;

file_put_contents($file, $code);

exec('java -jar extract_fields.jar',$output_array);
$output="";
foreach($output_array as $c)
{
$output=$output.$c."\n";
}

echo $output;
?>
