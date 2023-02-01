<?php 


$wsdl = "https://secure.usaepay.com/soap/gate/43R1QPKU/usaepay.wsdl";
$sourceKey = "your source key";
$pin = "1234";

function getClient($wsdl) {
  return new SoapClient($wsdl, array(
    'trace' => 1,
    'exceptions' => 1,
    'stream_context' => stream_context_create(
      array(
        'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        )
      )
    )
  ));
}

function getToken($sourceKey, $pin) {
  $seed = time() . rand();

  return array(
    'SourceKey' => $sourceKey,
    'PinHash' => array(
      'Type' => 'sha1',
      'Seed' => $seed,
      'HashValue' => sha1($sourceKey . $seed . $pin)
    ),
    'ClientIP' => $_SERVER['REMOTE_ADDR']
  );
}

$client = getClient($wsdl);
$token = getToken($sourceKey, $pin);

$CustNum = 'your customer number';
$MethodID = 'your Method ID';


try {
    $result = $client->convertPaymentMethodToToken($token, $CustNum, $MethodID);
    print_r($result);
  } catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
  }
    
  
?>