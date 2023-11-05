    </div>
        </div>
        <!-- end container -->
    </div>
    
    <!-- Fotter file -->
    <!-- scripts vue -->
    <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/apexcharts.js"></script>
    <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/lodash.min.js"></script>
    <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/axios.min.js"></script>
    <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/vue.global.js"></script>
    
    


    <!-- Language file -->
    <?php if ($templatelang == 'fa'): ?>
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/lang/fa.js"></script>
    <?php elseif($templatelang == 'fr'): ?> 
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/lang/fr.js"></script>
    <?php elseif($templatelang == 'du'): ?> 
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/lang/du.js"></script>
    <?php elseif($templatelang == 'ru'): ?> 
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/lang/ru.js"></script>
    <?php elseif($templatelang == 'tr'): ?> 
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/lang/tr.js"></script>
    <?php elseif($templatelang == 'br'): ?> 
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/lang/br.js"></script>
    <?php elseif($templatelang == 'it'): ?> 
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/lang/it.js"></script>
    <?php elseif($templatelang == 'en'): ?> 
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/lang/defaulten.js"></script>
    <?php else: ?> 
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/lang/defaulten.js"></script>
    <?php endif ?>
    
    
    
    
    <!-- main js for client and admin  -->
    <?php if($_GET['u'] == 'admin'): ?>
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/mainadmin.js"></script>
    <?php else: ?>
        <script src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/js/mainclient.js"></script>
    <?php endif ?>

    </body>
</html>
 