<?php
$conn=mysql_connect("localhost","root","x") or die(mysql_error());
mysql_select_db("cqcom");
mysql_query("set names gbk");
$fp=fopen("out.txt","r");
while($arr=fscanf($fp,"%s	%s\n")){
	list($pk,$intzt)=$arr;
	$sql="INSERT INTO `cqcom`.`company` (`id`, `pk`,   `status` ) VALUES (NULL, '$pk',  '$intzt' ) ";
	print_r($pk."\n");
	mysql_query($sql)	or die(mysql_error());
}