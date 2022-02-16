<?php

$ch = curl_init();
$page_no = 0;
$FILE_COUNT = 4739;

while($page_no <=$FILE_COUNT)
{
	if(!file_exists("$page_no.txt") && $page_no != 0)
	{	
		$page_no--;
		break;
	}
	$page_no++;
	//echo $page_no."\n";
}
$options=array(
	CURLOPT_URL=>"http://202.98.60.118:8088/lhzx/PtcxAction.do?method=creditQuery",
	CURLOPT_RETURNTRANSFER=>true
);
curl_setopt_array($ch,$options);
$data = curl_exec($ch);
while($page_no <=$FILE_COUNT)
{	
	if(file_exists("$page_no.txt"))
	{
		$page_no++;
		continue;
	}
	$url="http://202.98.60.118:8088/lhzx/PtcxAction.do;jsessionid=vTTvT2fQz6pL4GkT68wHJHJHgL1v4hNvGXFRghWFhn1RC3B7R1PZ!244762802?method=commonQuery&hqlKey=1299557938477&forward=/xycx/creditquery.jsp&totalNum=473835&totalPageNo=4739&curPageNo=$page_no&otherInfo=null&toPageState=next&pageLine=100&currentRowno=1&querysessionid=/xycx/creditquery.jsp";
	//http://202.98.60.118:8088/lhzx/PtcxAction.do?method=commonQuery&hqlKey=1299558014368&forward=/xycx/creditquery.jsp&totalNum=109551&totalPageNo=10956&curPageNo=1&otherInfo=null&toPageState=next&pageLine=10&currentRowno=1&querysessionid=/xycx/creditquery.jsp
	//
	print_r($url."\n");
	$options=array(
		CURLOPT_URL=>$url,
		CURLOPT_RETURNTRANSFER=>true
	);
	curl_setopt_array($ch,$options);
	$data = curl_exec($ch);
	if(!$data)
	{
		echo "something goes wrong!\n";
		break;
	}
	file_put_contents("$page_no.txt",$data);
	$page_no++;
	echo $page_no." finished!!\n";
}
curl_close($ch);
echo "all proccess finished\n";