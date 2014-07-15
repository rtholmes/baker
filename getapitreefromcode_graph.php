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


function _format_json($json, $html = true) {
    $tabcount = 0; 
    $result = ''; 
    $inquote = false; 
    $ignorenext = false; 
 
    if ($html) { 
        //$tab = "&nbsp;&nbsp;&nbsp;"; 
        //$newline = "<br/>"; 
        $tab = "  "; 
        $newline = ""; 
    } else { 
        $tab = "\t"; 
        $newline = "\n"; 
    } 
 
    for($i = 0; $i < strlen($json); $i++) { 
        $char = $json[$i]; 
 
        if ($ignorenext) { 
            $result .= $char; 
            $ignorenext = false; 
        } else { 
            switch($char) { 
                case '{': 
                    $tabcount++; 
                    $result .= $char . $newline . str_repeat($tab, $tabcount); 
                    break; 
                case '}': 
                    $tabcount--; 
                    $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char; 
                    break; 
                case ',': 
                    $result .= $char . $newline . str_repeat($tab, $tabcount); 
                    break; 
                case '"': 
                    $inquote = !$inquote; 
                    $result .= $char; 
                    break; 
                case '\\': 
                    if ($inquote) $ignorenext = true; 
                    $result .= $char; 
                    break; 
                default: 
                    $result .= $char; 
            } 
        } 
    } 
 
    return $result; 
  }

$ip_address = $_SERVER['REMOTE_ADDR'];
$new_ip = str_replace(".", "_", $ip_address);
$code=rawurldecode($_REQUEST['pastedcode']);

$tstamp = time();
$file = "inputs/ip_".$new_ip."_".strval($tstamp).".txt";
$opfile = "outputs/op_".$new_ip."_".strval($tstamp).".txt";
file_put_contents($file, $code);

$output_array = exec_timeout('java -Xmx2048m -jar JavaBaker.jar '.$file, 300);
$output = _format_json($output_array);

echo $output;

$links = json_decode($output, true);
$count=0;
$count2=0;
$blah = "a";

echo $links['api_elements'];

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
echo "<script type='text/javascript'>alert('$links');</script>";

foreach($links['api_elements'] as $val){ 
  $count2=0;
  echo "<tr data-tt-id=\"".$count."\">";
  $blah = "b";
  if($val['precision']=="1"){	
    $blah = $val['name'];
    echo "<td align=\"left\">".htmlspecialchars($val['name'])."</td>";
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
echo "<td align=\"left\">".$val->line_number."</td>";
echo "<td align=\"left\">".$val->type."</td>";
echo "</tr>";

if($val->precision > 1)
{
  foreach($val->elements as $element)
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

echo "<script type='text/javascript'>alert('$blah');</script>";
echo "<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js\"></script><link rel=\"stylesheet\" href=\"jquery.treetable.css\" /><link rel=\"stylesheet\" href=\"jquery.treetable.theme.default.css\" />";

echo "<script src=\"jquery.treetable.js\"></script><script>$(\"#apielements\").treetable({ expandable: true }); </script></body></html>";

?>
