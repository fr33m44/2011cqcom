@echo off
for /l %%a in (0,1,4723) do (
	curl --connect-timeout 99999 -m 99999999999 "http://202.98.60.118:8088/lhzx/PtcxAction.do;jsessionid=NwXDSZpw87mvJLVwyvB1GjW9kGlLLwpsRjwndpQmVRQ2mLN48sLq!-107079357?method=commonQuery&hqlKey=1299224390047&forward=/xycx/creditquery.jsp&totalNum=472170&totalPageNo=4722&curPageNo=%%a&otherInfo=null&toPageState=next&pageLine=100&currentRowno=&querysessionid=/xycx/creditquery.jsp" >%%a.txt
)
