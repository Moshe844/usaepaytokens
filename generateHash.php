<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the submitted API Key and PIN from the form
    $apiKey = $_POST["apiKey"];
    $pin = $_POST["pin"];

    // Call the function to generate the token
    function getToken($source, $pin) {
        $seed = 12345;
        $clear = $source . $seed . $pin;
        $hash = md5($clear);
        $token = array(
            'SourceKey' => $source,
            'PinHash' => array(
                'Type' => 'md5',
                'Seed' => $seed,
                'HashValue' => $hash
            ),
            'ClientIP' => '123.123.123.123'
        );
        return $token;
    }

    $generatedToken = getToken($apiKey, $pin);

    // Now you can use $generatedToken as needed
    print_r($generatedToken);
    // You might want to store or process the generated token further
}
?>
