<?php include_once('funcs.php'); ?>
<?php include_once('iframefuncs.php'); ?>
<?php include_once('funwritecookies.php'); ?>


<style>
    .myiframe{
        width: 100%;
        border: 2px solid #8181814a;
        border-radius: 10px;
        padding: 0px;
        max-width: 1400px;
    }

    .mycounter{
        text-align: center;
    }


    /* iphone and smaller */
    @media only screen and (max-width: 828px) {
        .myiframe{
            height: 800px;
        }
        .mycounter{
            padding: 50px 0px;
        }
    }


    /* Tablet to LargeScreen */
    @media only screen and (min-width: 829px) and (max-width: 992px) {
        .myiframe{
            height: 600px;
        }
        .mycounter{
            padding: 70px 0px;
        }
    }


    /* LargeScreen and Larger */
    @media only screen and (min-width: 993px) {
        .myiframe{
            height: 600px;
        }
        .mycounter{
            padding: 100px 0px;
        }
    }

</style>

<div class="mycounter">
    <iframe id="myiframe"  src=<?php echo($adminIframeLink) ?>  class="myiframe"></iframe>
</div>

<div style="height:500px"></div>