<?php
echo "<html><body>";

$code=rawurldecode($_REQUEST['pastedcode']);
//echo "<script>alert(\"".$code."\")</script>";
$file="sample.txt";


file_put_contents($file, $code);

exec('java -jar extract_fields.jar',$output_array);
$output="";
foreach($output_array as $c)
{
$output=$output.$c."\n";
}

/*$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($output, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);
foreach ($jsonIterator as $key => $val) {

    if(is_array($val)) 
    {
        echo "$key:<br>";
    } 
    else 
    {
        echo "$key => $val<br>";
    }
}*/
//echo $output;
echo "<input type=\"button\" onclick=\"jQuery('#apielements').treetable('expandAll'); return false;\" value=\"Expand All\"/>";
echo "<input type=\"button\" onclick=\"jQuery('#apielements').treetable('collapseAll'); return false;\" value=\"Collapse All\"/>";
echo "<table id=\"apielements\" border=\"1\">";
echo "<caption>API Listing</caption>
        <thead>
          <tr>
	    <th>Element name</th>
            <th>FQN</th>
	    <th>Line number</th>
	    <th>Method/Type</th>
          </tr>
        </thead>
        <tbody>";
$links = json_decode($output, TRUE);
$count=0;
$count2=0;
foreach($links['api_elements'] as $key=>$val){ 
$count2=0;
echo "<tr data-tt-id=\"".$count."\">";

if($val['precision']=="1")
{	echo "<td align=\"left\">".htmlspecialchars($val['name'])."</td>";
	echo "<td align=\"left\">".htmlspecialchars($val['elements'][0])."</td>";
}
else
{
	if($val['type']=="api_method")
		echo "<td align=\"left\"> *.".htmlspecialchars($val['name'])."    (IMPRECISE)</td>";
	else
		echo "<td align=\"left\">*IMPRECISE*</td>";
	echo "<td align=\"left\">*</td>";
}
echo "<td align=\"left\">".$val['line_number']."</td>";
echo "<td align=\"left\">".$val['type']."</td>";
echo "</tr>";

if($val['precision']>1)
{
foreach($val['elements'] as $element)
	{		
		echo "<tr data-tt-id=\"".$count.$count2."\" data-tt-parent-id=\"".$count."\">";
		echo "<td align=\"left\" colspan = \"4\">".htmlspecialchars($element)."</td>";
		echo "</tr>";
		$count2=$count2+1;
	}
}
$count=$count+1;
}
echo "</tbody></table>";

/*
echo "<table id=\"examplebasic\">
        <caption>Basic jQuery treetable Example</caption>
        <thead>
          <tr>
            <th>Tree column</th>
            <th>Additional data</th>
          </tr>
        </thead>
        <tbody>
          <tr data-tt-id=\"1\">
            <td>Node 1: Click on the icon in front of me to expand this branch.</td>
            <td>I live in the second column.</td>
          </tr>
          <tr data-tt-id=\"1.1\" data-tt-parent-id=\"1\">
            <td>Node 1.1: Look, I am a table row <em>and</em> I am part of a tree!</td>
            <td>Interesting.</td>
          </tr>
          <tr data-tt-id=\"1.1.1\" data-tt-parent-id=\"1.1\">
            <td>Node 1.1.1: I am part of the tree too!</td>
            <td>That's it!</td>
          </tr>
          <tr data-tt-id=\"2\">
            <td>Node 2: I am another root node, but without children</td>
            <td>Hurray!</td>
          </tr>
        </tbody>
</table>";
*/

echo "<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js\"></script><link rel=\"stylesheet\" href=\"jquery.treetable.css\" /><link rel=\"stylesheet\" href=\"jquery.treetable.theme.default.css\" />";

echo "<script src=\"jquery.treetable.js\"></script><script>$(\"#apielements\").treetable({ expandable: true }); </script></body></html>";

?>
