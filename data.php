<?php
include('vendor/autoload.php');

$currency1 = $_POST['currency-1'];
$currency2 = $_POST['currency-2'];
$quantity = floatval($_POST['quantity']);

//Firstly, you need to create an HTTP client:
$client = new \Guzzle\Http\Client();

//Then you can create a provider:
$google = new \Swap\Provider\GoogleFinance($client);
$yahoo = new \Swap\Provider\YahooFinance($client);
$provider = array(
  'google' => $google,
  'yahoo' => $yahoo
);

//Create a Swap instance and add the provider:
$swap = new Swap\Swap();
$swap->addProvider($provider[$_POST['provider']]);

// Create the currency pair EUR/USD
$pair = new \Swap\Model\CurrencyPair($currency1, $currency2);

// Quotes the pair
$swap->quote($pair);

$rate = floatval($pair->getRate());

// Result
$result = array(
  'quantity' => $quantity,
  'currencies' => array(
    'origin' => array('code' => $currency1, 'value' => $quantity),
    'destiny' => array('code' => $currency2, 'value' => round(($quantity * $rate), 2))
  ),
  'rate' => $rate
);
header('Content-type: text/json'); 
echo json_encode($result);
?>