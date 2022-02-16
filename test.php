<?php
$html = '<div id="biuuu">51CTO</div><div id="biuuu_2">51CTO2</div><div id="biuuu_3">51CTO3</div>'; 
preg_match_all('/<div\sid=\"([a-z0-9_]+)\">([^<>]+)<\/div>/',$html,$result); 
print_r($result);
