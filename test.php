<script type="text/javascript" src="/google-code-prettify/prettify.js"></script>
<body onload="prettyPrint()">
<pre class="prettyprint">
<?php
function printarray($array,$charat) {
   $temp=0;
   foreach ($array as $key => $value) {
	$temp=$temp+sizeof($value);
	if($temp<$charat)
		echo $value.PHP_EOL;
	else
		echo "<font color=\"red\">".$value.PHP_EOL."</font>";
   }
}

function getlines($array) {
$temp=explode(PHP_EOL,$array);
return sizeof($temp);
}
	echo "<script type=\"text/javascript\" src=\"/google-code-prettify/prettify.js\"></script><body onload=\"prettyPrint()\"><pre class=\"prettyprint\">";
	$codeid=$_GET['codeid'];
	$aid=$_GET['aid'];
	$charat=$_GET['charat'];
	require('simplehtmldom/simple_html_dom.php');
	$url = "http://stackoverflow.com/questions/".$aid;
	$html = file_get_html($url);
	//echo $html;
	foreach($html->find('div') as $div) 
	{		
    		if(strcmp('answer accepted-answer',$div->class)==0)
		{
			
			$count=0;
			foreach($div->find('code') as $code)
			{	
				if(getlines($code)>=2)
				{
				$count=$count+1;
				if($count==$codeid)
					{
					echo "<p><code>";
					//$arr=split('     ', $code);
					//traverse($arr);
					echo $code;
					$temp=explode(PHP_EOL,$code);
					
					echo "</code></p></br></br></br>";
					echo(substr($code, intval($charat), 10));
					echo getlines($code);
					printarray($temp,$charat);
					break;
					}
				}
			}

		}
	}
?>
</pre>
</body>

