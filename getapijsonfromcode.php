<?php
$code=rawurldecode($_REQUEST['pastedcode']);
//echo "<script>alert(\"".$code."\")</script>";
$file="sample.txt";
file_put_contents($file, $code);
exec('java -jar extract_fields.jar',$output_array);
$output="";
foreach($output_array as $c)
{
$output=$output.$c."<br>";
}

echo $output; 
?>
