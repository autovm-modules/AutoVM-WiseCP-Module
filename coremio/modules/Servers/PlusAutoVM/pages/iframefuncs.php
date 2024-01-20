<?php  include_once('config.php');      ?>

<?php 

// FInd Admin or Client from URL
    $u = 'client';

    if(isset($_GET['u']) && $_GET['u'] == 'admin'){
        $u = 'admin';
    } else {
        $u = 'client';
    }
// end





  

// Create Backlink URL Client from config
    if($DefaultClientBackLink){
        $BackLinkClient = $DefaultClientBackLink;
    } else {
        $BackLinkClient = '/myaccount/myproducts-detail/' . $id;
    }

    // create Backlink URL Admin
    if($DefaultAdminBackLink){
        $BackLinkAdmin = $DefaultAdminBackLink;
    } else {
        $BackLinkAdmin = '/admin/orders/detail/' . $id;
    }
// end


?>