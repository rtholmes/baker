<?php

$code=$_POST['pastedcode'];
$file="sample.txt";
$output=null;
echo file_put_contents($file, $code)."<br>";
file_put_contents($file, $code);
//exec('cd snippetjar');
//echo exec('whoami');

exec('java -jar extract_fields.jar',$output);
//$op=explode("\n",$output);
foreach($output as $c)
{
echo $c."<br>";
}
//echo $output[2];
//var_dump($output);
?>
