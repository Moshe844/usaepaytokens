<?php
function getToken($source='Your source key', $pin='1234'){
$seed=12345;
$clear=$source.$seed.$pin;
//echo "\n$clear\n";
$hash=md5($clear);
//echo "\n$hash\n";
$token=array(
'SourceKey'=>"$source",
'PinHash'=>array(
'Type'=>'md5',
'Seed'=>$seed,
'HashValue'=>$hash
),
'ClientIP'=>'123.123.123.123'
);
print_r($token);
return $token;
}

getToken();
