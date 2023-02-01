<?php 


$wsdl = "https://secure.usaepay.com/soap/gate/INBGTWZC/usaepay.wsdl";
$sourceKey = "your soruce key";
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

try {
    $custnum='customer number';
    print_r($client->getCustomer($token,$custnum));
  } catch (Exception $e) {
    // Code to handle the exception
    echo "An error occurred: " . $e->getMessage();
  }
    
  
?>