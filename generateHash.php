<?php
function getToken($source='_g6BALVW9vpPZ3jEqf5kwe4pIrqyvabY', $pin='1234'){
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
