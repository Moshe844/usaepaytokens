<?php 

$file = $_FILES["file"];
if (!$file) {
    throw new Exception("File not uploaded");
}
$handle = fopen($file["tmp_name"], "r");
$header = fgetcsv($handle);
$data = [];
while ($row = fgetcsv($handle)) {
    $data[] = [$row[0], $row[1]];
}
fclose($handle);

header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment;filename=output.csv");
$output = fopen("php://output", "w");
fputcsv($output, ["CustNum", "MethodID", "Token"]);


$wsdl = "https://secure.usaepay.com/soap/gate/43R1QPKU/usaepay.wsdl";
$sourceKey = $_POST["sourceKey"];
$pin = $_POST["pin"];

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



foreach ($data as $item) {
  $CustNum = $item[0];
  $MethodID = $item[1];

  try {
    $result = $client->convertPaymentMethodToToken($token, $CustNum, $MethodID);
    // var_dump($result);
    if (property_exists($result, "CardRef")) {
        fputcsv($output, [$CustNum, $MethodID, $result->CardRef]);
    } else {
        fputcsv($output, [$CustNum, $MethodID, "Error: ResultToken not found"]);
    }
  } catch (Exception $e) {
    fputcsv($output, [$CustNum, $MethodID, "Error: " . $e->getMessage()]);
  }
}
fclose($output);
  
?>