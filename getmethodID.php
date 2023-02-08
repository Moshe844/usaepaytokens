<?php
ob_start();

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $wsdl = "https://sandbox.usaepay.com/soap/gate/43R1QPKU/usaepay.wsdl";
    $sourceKey = $_POST["sourceKey"];
    $pin = "1234";

    function getClient($wsdl)
    {
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

    function getToken($sourceKey, $pin)
    {
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
        // Load the customer numbers from the uploaded Excel file
        $file = $_FILES["file"];
        if (!$file) {
            throw new Exception("File not uploaded");
        }
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
            $file["tmp_name"]
        );
        $worksheet = $spreadsheet->getActiveSheet();
        $customer_numbers = $worksheet->toArray();

        // Generate the method ID for each customer number
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment;filename=output.csv");
        $output = fopen("php://output", "w");
        // fputcsv($output, ["Amount", "City"]);
        foreach ($customer_numbers as $customer_number) {
            try {
              $data = $client->getCustomer($token, $customer_number[0]);
              $csv_data = [ $data->CustNum,  $data->PaymentMethods[0]->MethodID];
              fputcsv($output, $csv_data);

            } catch (soapFault $e) {
                // Code to handle the exception
                if ($e->getMessage() != "40030: Customer Not Found") {
                echo "An error occurred for customer number: " .
                    $customer_number[0] .
                    " - " .
                    $e->getMessage() .
                    "\n";
            }
        }
      }
        fclose($output);
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage() . "\n";
    }
}

ob_end_flush();
?>
