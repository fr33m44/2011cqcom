<?php
for($i=0;$i<=4738;$i++)
{
	$data=file_get_contents("$i.txt");
	preg_match_all("/<input type='hidden' value='(\d+)' id='intzt_hrow(\d+)' name='intzt'\/\>\<input type='hidden' value='(\d+)' id='gsj_qybase_index_hrow(\d+)' name='gsj_qybase_index'\/\>/",$data,$matches);
	//print_r($matches[3]); //工商注册号
	//print_r($matches[3]); //企业状态：1：存活 2：注销 3：吊销
	for($j=0;$j<count($matches[3]);$j++)
	{
		file_put_contents("out.txt",$matches[3][$j]."	".$matches[1][$j]."\r\n",FILE_APPEND );
	}
	echo $i." finished\n";
	
}