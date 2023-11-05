<?php 
// Create iframe links resources

// Find machine ID
    $MachineId = $this->template_params["order"]["id"];


// Find BaseUrl (domain)
    $BaseUrl = $_SERVER["HTTP_ORIGIN"];


// Set Iframe Link
    if(isset($MachineId) && isset($BaseUrl)){    
        
        // Create Source Url for iframe
        $IframeLink = $BaseUrl . "/coremio/modules/Servers/PlusAutoVM/pages/broverview.php?id=" . $MachineId;
        
        // iframe Client
        $clientIframeLink = $IframeLink .  "&u=client";
        
        // Iframe Admin
        $adminIframeLink = $IframeLink .  "&u=admin";
        // end
    } 
// end


// Check errors
    if(!isset($MachineId)) {
        echo('<h3 style="color:red; padding: 30px;">Can not fine ==> machine ID</h3>');
    }

    
    if(!isset($BaseUrl)) {
        echo('<h3 style="color:red; padding: 30px;">Can not fine ==> BaseUrl</h3>');
    }
// end







// Set admin or Client
    if(isset($_SESSION['admin_login']) && !empty($_SESSION['admin_login'])){
        $isadmin = true;
    } else {
        $isadmin = false;
    }

    if(isset($_SESSION['member_login']) && !empty($_SESSION['member_login'])){
        $isclient = true;
    } else {
        $isclient = false;
    }

//





?>

