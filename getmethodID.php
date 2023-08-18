<?php
ob_start();

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $wsdl = "https://secure.usaepay.com/soap/gate/43R1QPKU/usaepay.wsdl";
    $sourceKey = $_POST["sourceKey"];
    $pin  = $_POST["pin"];

    function getClient($wsdl) {
        return new SoapClient($wsdl, [
            "trace" => 1,
            "exceptions" => 1,
            "stream_context" => stream_context_create([
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true,
                ],
            ]),
        ]);
    }

    function getToken($sourceKey, $pin) {
        $seed = time() . rand();

        return [
            "SourceKey" => $sourceKey,
            "PinHash" => [
                "Type" => "sha1",
                "Seed" => $seed,
                "HashValue" => sha1($sourceKey . $seed . $pin),
            ],
            "ClientIP" => $_SERVER["REMOTE_ADDR"],
        ];
    }

    $client = getClient($wsdl);
    $token = getToken($sourceKey, $pin);

    // Load the customer numbers from the uploaded Excel file
    try {
        $file = $_FILES["file"];
        if (!$file) {
            throw new Exception("File not uploaded");
        }
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file["tmp_name"]);
        $worksheet = $spreadsheet->getActiveSheet();
        $customer_numbers = array_column($worksheet->toArray(), 0);

        // Generate the method ID for each customer number
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment;filename=output.csv");
        $output = fopen("php://output", "w");
        fputcsv($output, ["CustNum", "MethodID"]);
        
        foreach ($customer_numbers as $customer_number) {
            try {
                $data = $client->getCustomer($token, $customer_number);
                if ($data && isset($data->PaymentMethods)) {
                    foreach ($data->PaymentMethods as $paymentMethod) {
                        if (isset($paymentMethod->MethodID)) {
                            $csv_data = [$data->CustNum, $paymentMethod->MethodID];
                            fputcsv($output, $csv_data);
                        }
                    }
                }
            } catch (soapFault $e) {
                if ($e->getMessage() != "40030: Customer Not Found") {
                    echo "An error occurred for customer number: {$customer_number} - {$e->getMessage()}\n";
                }
            }
        }
        
        fclose($output);
    } catch (Exception $e) {
        echo "An error occurred: {$e->getMessage()}\n";
    }
}

ob_end_flush();
?>
