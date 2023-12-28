<?php 

// echo '<pre>';
// print_r($product['module_data']);
// echo '</pre>';

if(!empty($product['module_data']))
{
    $params = $module->getArray('module_data', $product);
    
    // Pool
    $poolId = $module->getArray('poolId', $params);
    
    // Memory
    $memorySize = $module->getArray('memorySize', $params);
    
    // Memory
    $memoryLimit = $module->getArray('memoryLimit', $params);
    
    // Disk
    $diskSize = $module->getArray('diskSize', $params);
    
    // CPU
    $cpuCore = $module->getArray('cpuCore', $params);
    
    // CPU Limit
    $cpuLimit = $module->getArray('cpuLimit', $params);

} else {
   
   $poolId = '1';
   $memorySize = '1024';
   $memoryLimit = '1024';
   $diskSize = '20';
   $cpuCore = '1';
   $cpuLimit = '2000';

}



// set all variable in one Arr
$coreVariables = array(
    'poolId' => $poolId,
    'memorySize' => $memorySize ,
    'memoryLimit' => $memoryLimit ,
    'diskSize' => $diskSize ,
    'cpuCore' => $cpuCore ,
    'cpuLimit' => $cpuLimit 
);

?>







<!-- show in html -->
<?php foreach($coreVariables as $key => $value):?>
    
    <div class="formcon">
        <div class="yuzde30">
            <?php echo $key; ?>
        </div>
        <div class="yuzde70">
            <input type="text" name="module_data[<?php echo $key; ?>]" value="<?php echo $value;?>">
        </div>
    </div>

<?php endforeach ?>



