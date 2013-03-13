<?php
echo("<html><body>");
//echo("<link href=\"/google-code-prettify/prettify.css\" type=\"text/css\" rel=\"stylesheet\" />");
echo("<script src=\"https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js\"></script>");
echo("<pre class=\"prettyprint linenums\">");
function printarray($array,$charat) {
$temp=0;
$flag=0;
   foreach ($array as $key => $value) {
	$temp=$temp+sizeof(str_split($value));
	//echo $temp;
	if($temp<$charat-40 and $flag==0)
	{
		echo $value.PHP_EOL;
	}
	
	else if($flag==0)
	{
		//$flag=1;
		echo "<font  style=\"background-color: yellow\">".$value.PHP_EOL."</font>";
		if($temp>=$charat)
			$flag=1;
	}
	else if($flag==1)
	{
		echo $value.PHP_EOL;
	}
   }
}

function getlines($array) {
$temp=explode(PHP_EOL,$array);
return sizeof($temp);
}

	$codeid=$_POST['codeid'];
	$aid=$_POST['aid'];
	$charat=$_POST['charat'];
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
					//echo $code;
					$temp=explode(PHP_EOL,$code);
					printarray($temp,$charat);
					break;
					}
				}
			}

		}
	}
echo("</pre>");
echo("</body></html>");
?>
