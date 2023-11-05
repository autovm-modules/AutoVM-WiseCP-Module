<?php
$id = $_GET['id'];

// Defaul value for situation which there is no data:
$productname = "---";
$renewedon = "---";
$renewaldate = "---";
$recurringpayment = '---';
$currencysymb = '-';
$billingcycle = "---";


// Define $datapairs as an associative array with keys and variables
$datapairs = array(

    'productname' => $productname,
    'renewedon' => $renewedon,
    'renewaldate' => $renewaldate,
    'recurringpayment' => $recurringpayment,
    'currencysymb' => $currencysymb,
    'billingcycle' => $billingcycle,

);


// Loop through the data keys and check if the cookies exist
foreach ($datapairs as $key => $value) {

    $dynamicKey = $key . $id;
    if (isset($_COOKIE[$dynamicKey])) {
        
        // Cookie exists
        $datapairs[$key] = $_COOKIE[$dynamicKey];
        // echo "Cookie '$key' has value: $datapairs[$dynamicKey] <br>";

    } else {

        $datapairs[$key] = '---';
        // echo "Cookie '$key' has value: $datapairs[$dynamicKey] <br>";

    }
}

// set parameters ready for html file
$productname = $datapairs['productname'];
$renewedon = $datapairs['renewedon'];
$renewaldate = $datapairs['renewaldate'];
$recurringpayment = $datapairs['recurringpayment'];
$currencysymb = $datapairs['currencysymb'];
$billingcycle = $datapairs['billingcycle'];


?>