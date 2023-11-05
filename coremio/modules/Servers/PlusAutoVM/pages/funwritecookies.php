<?php  include_once('config.php');   ?>
<?php  include_once('funcs.php');   ?>

<?php

// get Id from iframFunc
$id = $MachineId;

if(isset($id)){
    // find infromation to send to view
    $productname = $this->template_params['module']->order['name'];
    $renewedon = $this->template_params['module']->order['duedate'];
    $renewaldate = $this->template_params['module']->order['renewaldate'];
    $recurringpayment = $this->template_params['module']->order['amount'];
    $billingcycle = $this->template_params['module']->order['period'];


    // Set Currency Symbol from config
    if(isset($DefaultCurrencySymbol)){
        $currencysymb = $DefaultCurrencySymbol;
    } else {
        $currencysymb = "$";
    }


    // set value in cookies for the first time
    $datapairs = array(
        'productname' => $productname, 
        'renewedon' => $renewedon, 
        'renewaldate' => $renewaldate, 
        'recurringpayment' => $recurringpayment, 
        'currencysymb' => $currencysymb, 
        'billingcycle' => $billingcycle
    );



    // Delet all other machines
    $allCookies = $_COOKIE;

    // Loop through all cookies and delete the ones that contain 'price' in their names
    foreach ($allCookies as $cookieName => $cookieValue) {
        if (strpos($cookieName, 'productname') !== false || strpos($cookieName, 'renewedon') !== false || strpos($cookieName, 'renewaldate') !== false || strpos($cookieName, 'recurringpayment') !== false || strpos($cookieName, 'billingcycle') !== false || strpos($cookieName, 'currencysymb') !== false) {
            // Set the cookie with an expiration time in the past to delete it
            setcookie($cookieName, '', time() - 3600, '/');
            
            // Unset the cookie from the $_COOKIE superglobal array
            unset($_COOKIE[$cookieName]);
        }
    }




    // Loop through the data keys and write cookies
    foreach ($datapairs as $key => $value) {   
        $dynamicKey = $key . $id;
        setcookie($dynamicKey, $value, time() + 3600 * 24 * 7, '/');
    }

} else {
    echo("Cookies Err: can not find ID to set cookies");
}


?>