<?php
//connect to db
$conn=mysql_connect("localhost","root","x") or die(mysql_error());
mysql_select_db("cqcom");
mysql_query("set names gbk");
$result = mysql_query("select * from company");
$ch = curl_init();
while($row=mysql_fetch_assoc($result)){
	if(!is_null($row['name']))
	{
		echo "id:$row[id] , pk:$row[pk] already inserted, dismissed\n";
		continue;
	}
	else
	{
		$options=array(
			CURLOPT_URL=>"http://202.98.60.118:8088/lhzx/xycxAction.do?pkvalue=$row[pk]&type=qy&intzt=$row[status]",
			//CURLOPT_URL=>"http://202.98.60.118:8088/lhzx/xycxAction.do?pkvalue=500000030100001063&type=qy&intzt=2",
			CURLOPT_RETURNTRANSFER=>true
		);
		curl_setopt_array($ch,$options);
		$data = curl_exec($ch);
		preg_match("/企业\(机构\)名称:&nbsp;&nbsp;\<\/td\>\<td align=\"left\"\>([\xa1-\xff:0-9\(\)]+)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\"\>\<td width=\"20%\" height=\"22\" align=\"right\"\>住/",$data,$matches);
		$name=$matches[1];
		$name_debug = $matches;
		preg_match("/住所\(经营场所\):&nbsp;&nbsp;\<\/td\>\<td align=\"left\">([\xa1-\xff:0-9\(\)]+)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\"><td width=\"20%\" height=\"22\" align=\"right\">工/",$data,$matches);
		$addr=$matches[1];
		
		preg_match("/工商注册号:&nbsp;&nbsp;\<\/td\>\<td align=\"left\">([\xa1-\xff:0-9\(\)]+)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\"><td width=\"20%\" height=\"22\" align=\"right\">法/",$data,$matches);
		$reg_id=$matches[1];
		
		preg_match("/法定代表人\(负责人\):&nbsp;&nbsp;\<\/td\>\<td align=\"left\">([\xa1-\xff:0-9\(\)]+)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\"><td width=\"20%\" height=\"22\" align=\"right\">成/",$data,$matches);
		$representative=$matches[1];
		
		preg_match("/成立日期:&nbsp;&nbsp;\<\/td\>\<td align=\"left\">(\d+-\d+-\d+)/",$data,$matches);
		$active_date=$matches[1];
		
		if($row['status'] == '1')//存活
		{
			$deactive_date='';
		}
		if($row['status'] == '2') //注销
		{
			preg_match("/注销日期:&nbsp;&nbsp;\<\/td\>\<td align=\"left\">([\xa1-\xff:0-9\(\)]+)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\"><td width=\"20%\" height=\"22\" align=\"right\">企业\(机构\)类/",$data,$matches);
			$deactive_date=$matches[1];
		}
		if($row['status'] == '3') //吊销
		{
			preg_match("/吊销日期:&nbsp;&nbsp;\<\/td\>\<td align=\"left\">([\xa1-\xff:0-9\(\)]+)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\"><td width=\"20%\" height=\"22\" align=\"right\">企业\(机构\)类/",$data,$matches);
			$deactive_date=$matches[1];
		}
		
		preg_match("/企业\(机构\)类型:&nbsp;&nbsp;\<\/td\>\<td align=\"left\">([\xa1-\xff:0-9\(\)]+)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\"><td width=\"20%\" height=\"22\" align=\"right\">注/",$data,$matches);
		$type=$matches[1];
		
		preg_match("/注册资本\(金\)\(万元\):&nbsp;&nbsp;\<\/td\>\<td align=\"left\">([\xa1-\xff:0-9\(\)]+)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\"><td width=\"20%\" height=\"22\" align=\"right\">经/",$data,$matches);
		$capital=$matches[1];
		
		preg_match("/经营范围:&nbsp;&nbsp;\<\/td\>\<td align=\"left\">([\s\S]*)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\"><td width=\"20%\" height=\"22\" align=\"right\">登记/",$data,$matches);
		$buss_scope=$matches[1];
		
		preg_match("/登记机关:&nbsp;&nbsp;\<\/td\>\<td align=\"left\">([\xa1-\xff:0-9\(\)]+)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\"><td width=\"20%\" height=\"22\" align=\"right\">企业状态/",$data,$matches);
		$register_authority=$matches[1];
		
		preg_match("/企业状态:&nbsp;&nbsp;\<\/td\>\<td align=\"left\">([\xa1-\xff:0-9\(\)]+)&nbsp;\<\/td\>\<\/tr\>\<tr bgcolor=\"\S+\" class=\"text\">\<td width=\"20%\" height=\"22\" align=\"right\">年检情况/",$data,$matches);
		$status=$matches[1];
		
		preg_match("/年检情况:&nbsp;&nbsp;\<\/td\>\<td align=\"left\"\>([\xa1-\xff:0-9\(\)]+)\<\/td\>/",$data,$matches); 
		$annual_survey=$matches[1];
		/////////////
		
		
		$info=array($row['pk'],$name,$addr,$reg_id,$representative,$active_date,$deactive_date,$type,$capital,$buss_scope,$register_authority,$row['status'],$annual_survey);  
		$sql="update `cqcom`.`company`  set  name='$name', addr='$addr', reg_id='$reg_id',representative='$representative', active_date='$active_date', deactive_date='$deactive_date', type='$type', capital='$capital', buss_scope='$buss_scope', register_authority='$register_authority',annual_survey='$annual_survey' where pk='$row[pk]' \n\n";
		print_r($info);
		if(!mysql_query($sql))
		{
			fopen($fp,"error.txt","w");
			fwrite($fp,$sql);
			fclose($fp);
			mysql_error();
		};
	}
}
mysql_close();
curl_close($ch);