<script type="text/javascript" src="/google-code-prettify/prettify.js"></script>
<body onload="prettyPrint()">
<pre class="prettyprint">
<?php
	//echo "<script type=\"text/javascript\" src=\"/google-code-prettify/prettify.js\"></script><body onload=\"prettyPrint()\"><pre class=\"prettyprint\">";
	$codeid=$_GET['codeid'];
	$aid=$_GET['aid'];
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
				$count=$count+1;
				if($count==$codeid)
					{
					echo "<p><code class>";
					//$arr = preg_split('/\s+/', $code);
					//var_dump($arr);
					echo $code;
					echo "</code></p>";
					break;
					}
				
			}

		}
	}

//echo "</pre></body>";
?>
</pre>
</body>

