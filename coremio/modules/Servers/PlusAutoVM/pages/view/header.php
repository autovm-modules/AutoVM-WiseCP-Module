<!doctype html>    
    <html lang="<?php echo($templatelang) ?>" <?php if($templatelang == 'fa'){ echo("dir='rtl'"); } ?> style="font-size: 0.8em !important;">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Product details</title>

        <!-- Bootstsrap Icon -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/img/autovm.svg" sizes="32x32">

        <!-- Bootsrtap Bundle -->
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/bootstrap.bundle.min.js"></script>

        <!-- RTL && LTR -->
        <?php if ($templatelang == 'fa'): ?>
            <link href="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/css/bootstrap.rtl.min.css" rel="stylesheet">
            <link href="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/style.css" rel="stylesheet">
            <style> * {font-family: 'Vazirmatn' !important;}</style>
        <?php else: ?> 
            <link href="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/css/bootstrap.min.css" rel="stylesheet">    
            <!-- FONT: Plus Jakarta Sans  -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300&display=swap" rel="stylesheet">
            <style> * {font-family: 'Plus Jakarta Sans', sans-serif !important;}</style>
        <?php endif ?>
        <!-- END RTL  -->
        

        <style>
            [v-cloak] { display: none; }
            .border {border-color: #ededed !important;}
            @media (min-width: 1400px) { .mycontainer{padding: 77px 51px !important;} }
        </style>

        

    </head>

    <body class="mt-5" style="background-color: #fafafc;">
        <div id="app" class="px-3 px-lg-5">
        <!-- main container -->
            <div class="container-fluid p-1 p-md-3" style="max-width: 1350px; padding-bottom: 200px !important;" v-cloak>

                <!-- BackLinks for client and admin view  -->
                <?php if($_GET['u'] == 'admin'): ?>
                    <?php include_once('adminbackbtn.php'); ?>
                <?php else: ?>
                    <?php include_once('clientbackbtn.php'); ?>
                <?php endif ?>

                <div class="row bg-white border border-1 border-secondar rounded-4 py-5 my-5 px-2 px-md-4 px-lg-5 mycontainer">
            
            
            
















