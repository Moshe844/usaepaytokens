<?php
// for directions on how to set up the
// WSDL link and create "$token" and "$client,"
// see: http://wiki.usaepay.com/developer/soap/howto/php
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
  //print_r($token);
  return $token;
}
function getClient($domain="sandbox",$wsdlkey="43R1QPKU"){
  // 1.6 WSDL CD942BE3
  $url="https://sandbox.usaepay.com/soap/gate/43R1QPKU/usaepay.wsdl";
   $client = new SoapClient($url);
   return $client;
  }; 
$token=getToken("_g6BALVW9vpPZ3jEqf5kwe4pIrqyvabY","1234");
$client=getClient();

try {
    $RefNum = '3152904856';


    // $updateData = array(
    //     array('Field'=>"FirstName",'Value'=>"Oleh")
    // );

  $Response=$client->convertTranToCust($token, $RefNum);
}
  catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

print_r($Response);
