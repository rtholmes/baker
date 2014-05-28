<?php
error_reporting(0);
echo "<html><body>";


/**
 * Execute a command and return it's output. Either wait until the command exits or the timeout has expired.
 *
 * @param string $cmd     Command to execute.
 * @param number $timeout Timeout in seconds.
 * @return string Output of the command.
 * @throws \Exception
 */
function exec_timeout($cmd, $timeout) {
  // File descriptors passed to the process.
  $descriptors = array(
    0 => array('pipe', 'r'),  // stdin
    1 => array('pipe', 'w'),  // stdout
    2 => array('pipe', 'w')   // stderr
  );

  // Start the process.
  $process = proc_open('exec ' . $cmd, $descriptors, $pipes);

  if (!is_resource($process)) {
    throw new \Exception('Could not execute process');
  }

  // Set the stdout stream to none-blocking.
  stream_set_blocking($pipes[1], 0);

  // Turn the timeout into microseconds.
  $timeout = $timeout * 1000000;

  // Output buffer.
  $buffer = '';

  // While we have time to wait.
  while ($timeout > 0) {
    $start = microtime(true);

    // Wait until we have output or the timer expired.
    $read  = array($pipes[1]);
    $other = array();
    stream_select($read, $other, $other, 0, $timeout);

    // Get the status of the process.
    // Do this before we read from the stream,
    // this way we can't lose the last bit of output if the process dies between these functions.
    $status = proc_get_status($process);

    // Read the contents from the buffer.
    // This function will always return immediately as the stream is none-blocking.
    $buffer .= stream_get_contents($pipes[1]);

    if (!$status['running']) {
      // Break from this loop if the process exited before the timeout.
      break;
    }

    // Subtract the number of microseconds that we waited.
    $timeout -= (microtime(true) - $start) * 1000000;
  }

  // Check if there were any errors.
  $errors = stream_get_contents($pipes[2]);

  if (!empty($errors)) {
    throw new \Exception($errors);
  }

  // Kill the process in case the timeout expired and it's still running.
  // If the process already exited this won't do anything.
  proc_terminate($process, 9);

  // Close all streams.
  fclose($pipes[0]);
  fclose($pipes[1]);
  fclose($pipes[2]);

  proc_close($process);

  return $buffer;
}

$code=rawurldecode($_REQUEST['pastedcode']);
//$file="sample.txt";
//file_put_contents($file, $code);
//exec('java -Xmx2048m -jar JavaBaker.jar',$output_array);
$output_array = exec_timeout('java -Xmx2048m -jar JavaBaker.jar', 120);
$output=$output_array;
//echo $output;

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
  echo "<font size=\"4\">";
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
echo "</font>";
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
