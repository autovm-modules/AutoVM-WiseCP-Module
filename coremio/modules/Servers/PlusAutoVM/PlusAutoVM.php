<?php

use PG\Request\Request;

$path = dirname(__FILE__);

require $path . '/vendor/autoload.php';

class PlusAutoVM_Module extends ServerModule
{
    protected $address, $token;

    public function __construct($server,$options=[])
    {
        $this->_name = __CLASS__;
        parent::__construct($server, $options);
    }

    protected function define_server_info($server = [])
    {
        // Address
        $this->address = $this->getArray('ip', $server);

        // Token
        $this->token = $this->getArray('password', $server);
    }

    public function getTemplateIdentity($name)
    {
        $response = $this->sendTemplatesRequest();

        if (empty($response)) {

            return null; // We dont need log anything here
        }

        $message = property_exists($response, 'message');

        if ($message) {

            return null; // We dont need to log anything here
        }

        $templateId = null;

        foreach ($response->data as $template) {

            if ($template->name == $name) $templateId = $template->id;
        }

        return $templateId;
    } 

    public function sendCreateRequest($poolId, $templateId, $memorySize, $memoryLimit, $diskSize, $cpuCore, $cpuLimit, $email, $hostname, $totalReserves, $publicKey)
    {

        $params = [
            'poolId' => $poolId, 
            'templateId' => $templateId, 
            'memorySize' => $memorySize, 
            'diskSize' => $diskSize, 
            'cpuCore' => $cpuCore, 
            'email' => $email, 
            'name' => $hostname,
            'totalReserves' => $totalReserves,
            'autoSetup' => 1
        ];

        if(isset($memoryLimit) && $memoryLimit != 0){
            $params['memoryLimit'] = $memoryLimit;
        } 


        if(isset($cpuLimit) && $cpuLimit != 0){
            $params['cpuLimit'] = $cpuLimit;
        }

        if(!is_null($publicKey)){
            $params['publicKey'] = $publicKey;
        }

        $headers = ['token' => $this->token];

        $address = [
            $this->address, 'candy', 'backend', 'machine', 'smart', 'pool'
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->setParams($params)->getResponse()->asObject();
    }
    
    // create
        public function create($params)
        {
            // Find options
                $options = $this->getArray('options', $this->order);
                if (empty($options)) {
                    $this->error = 'Could not find options';
                    return false;
                }
            //
            
            // Find addons
                $addons = $this->addons; 
                if (empty($addons)) {
                    $this->error = 'Could not find addons';
                    return false;
                }
            //            

            // Find Requirments
                $requirements = $this->requirements; 
            //

            // Find hostname for both (client & admin)
                $hostname = null;

                // Admin side, hostname set by options
                $hostname = $this->getArray('hostname', $options);
                
                // Client side, hostname set by Requirments
                if($hostname == null){
                    if (empty($requirements)) {
                        $this->error = 'Could not find requirements';
                        return false;
                    }
                    foreach ($requirements as $requirement) {
                        if(strcasecmp($requirement['requirement_name'], 'hostname') === 0){
                            $hostname = $requirement['response'];
                        }
                    }
                }

                if ($hostname == null) {
                    $this->error = 'Could not find hostname from client view';
                    return false;
                }

            //
            
            // Find templateName from Addons
                $templateName = null;
                
                foreach ($addons as $addon) {
                    if(strcasecmp($addon['addon_name'], 'template') === 0){
                        $templateName = $addon['option_name'];
                    }
                }

                if (empty($templateName)) {
                    $this->error = 'Could not find templateName';
                    return false;
                }
            //
            
            // Find Extra Memory from Addons
                $extraMemory = 0;
                
                foreach ($addons as $addon) {
                    if(strcasecmp($addon['addon_name'], 'extramemory') === 0){
                        $optionText = $addon['option_name'];
                        if(preg_match('/(\d+)\s*GB/i', $optionText, $matches)){
                            $optionValue = $matches[1];
                            $optionValue = intval($optionValue);                            
                            if (is_int($optionValue) && $optionValue > 0) {
                                $extraMemory = $optionValue * 1024;
                            } else {
                                $this->error = 'can fetch extra memory';
                                return false;
                            }
                        } else {
                            $this->error = 'Extra memory is not in format';
                            return false;
                        }
                    }
                }
            //

            // Find Extra Disk from Addons
                $extraDisk = 0;
                    
                foreach ($addons as $addon) {
                    if(strcasecmp($addon['addon_name'], 'extradisk') === 0){
                        $optionText = $addon['option_name'];
                        if(preg_match('/(\d+)\s*GB/i', $optionText, $matches)){
                            $optionValue = $matches[1];
                            $optionValue = intval($optionValue);
                            if (is_int($optionValue) && $optionValue > 0) {
                                $extraDisk = $optionValue;
                            } else {
                                $this->error = 'can fetch extra Disk';
                                return false;
                            }
                        } else {
                            $this->error = 'Extra Disk is not in format';
                            return false;
                        }
                    }
                }
            //

            // Find Extra CPU Core from Addons
                $ExtraCPUCore = 0;
                    
                foreach ($addons as $addon) {
                    if(strcasecmp($addon['addon_name'], 'extracpucore') === 0){
                        $optionText = $addon['option_name'];
                        if(preg_match('/(\d+)\s*extra/i', $optionText, $matches)){
                            $optionValue = $matches[1];
                            $optionValue = intval($optionValue);
                            if (is_int($optionValue) && $optionValue > 0) {
                                $ExtraCPUCore = $optionValue;
                            } else {
                                $this->error = 'can fetch extra CPU Core';
                                return false;
                            }
                        } else {
                            $this->error = 'Extra CPU Core is not in format';
                            return false;
                        }
                    }
                }
            //
            
            // Find Extra IP's from Addons
                $extraIP = 0;
                    
                foreach ($addons as $addon) {
                    if(strcasecmp($addon['addon_name'], 'extraip') === 0){
                        $optionText = $addon['option_name'];
                        if(preg_match('/(\d+)\s*extra/i', $optionText, $matches)){
                            $optionValue = $matches[1];
                            $optionValue = intval($optionValue);
                            if (is_int($optionValue) && $optionValue > 0) {
                                $extraIP = $optionValue;
                            } else {
                                $this->error = 'can fetch extra IP';
                                return false;
                            }
                        } else {
                            $this->error = 'Extra IP is not in format';
                            return false;
                        }
                    }
                }
            //
            
            // Find TemplateID
                $templateId = $this->getTemplateIdentity($templateName);
                if (empty($templateId)) {
                    // $this->error = 'Could not find template ID';
                    // return false;
                    $templateId = 1;
                }    
            //

            // Find creation_info from Option for Options 
                $creationinfo = $this->getArray('creation_info', $options);
                if (empty($creationinfo)) {
                    $this->error = 'Could not find creation_info';
                    return false;
                }
            //

            // Find poolId 
                $poolId = $this->getArray('poolId', $creationinfo);
                if (empty($poolId)) {
                    $this->error = 'Could not find poolId';
                    return false;
                }
            //

            // Find memorySize 
                $memorySize = $this->getArray('memorySize', $creationinfo);
                if (empty($memorySize)) {
                    $this->error = 'Could not find memorySize';
                    return false;
                }
                // add extra add on
                $memorySize = $memorySize + $extraMemory;
            //

            // Find memoryLimit
                $memoryLimit = $this->getArray('memoryLimit', $creationinfo);


                // add extra add on
                if ($memoryLimit != 0) {
                $memoryLimit = $memoryLimit + $extraMemory;
                }
                
            //

            // Find diskSize
                $diskSize = $this->getArray('diskSize', $creationinfo);
                if (empty($diskSize)) {
                    $this->error = 'Could not find diskSize';
                    return false;
                }
                // add extra disk
                $diskSize = $diskSize + $extraDisk;
            //

            // Find cpuCore
                $cpuCore = $this->getArray('cpuCore', $creationinfo);
                if (empty($cpuCore)) {
                    $this->error = 'Could not find cpuCore';
                    return false;
                }
                // add extra add on
                $cpuCore = $cpuCore + $ExtraCPUCore;
            // 

            // Find cpuLimit
                $cpuLimit = $this->getArray('cpuLimit', $creationinfo);
            //
            
            // Find traffic
                // $traffic = $this->getArray('traffic', $creationinfo);
                // if (empty($traffic)) {
                //     $this->error = 'Could not find traffic';
                //     return false;
                // }
            //
            
            // Find duration
                // $duration = $this->getArray('duration', $creationinfo);
                // if (empty($duration)) {
                //     $this->error = 'Could not find duration';
                //     return false;
                // }
            //
            
            // Find email
                $email = $this->getArray('email', $this->user);
                if (empty($email)) {
                    $this->error = 'Could not find email';
                    return false;
                }
            //

            // find SSH from Requirment (if exist)
                $publicKey = null;
                if (isset($requirements) && is_array($requirements)) {
                    foreach ($requirements as $requirement) {
                        if(strcasecmp($requirement['requirement_name'], 'sshkey') === 0){
                            $publicKey = $requirement['response'];
                        }
                    }
                }
            //    

            // add extra IP
            $totalReserves = $extraIP + 1;

            // Send request
                $response = $this->sendCreateRequest($poolId, $templateId, $memorySize, $memoryLimit, $diskSize,  $cpuCore, $cpuLimit, $email, $hostname, $totalReserves, $publicKey);

                if (empty($response)) {
                    $this->error = 'Could not get response';
                    return false;
                }

                $message = property_exists($response, 'message');
                if ($message) {
                    $this->error = $response->message;
                    return false;
                }
            //

            // Find order
                $orderId = $this->getArray('id', $this->order);
                if (empty($orderId)) {
                    $this->error = 'Could not find order';
                    return false;
                }
            //  

            // Save machine
            $params = [
                'order_id' => $orderId, 'machine_id' => $response->data->id
            ];

            $MachineAlias = isset($response->data->reserve->address->alias) ? $response->data->reserve->address->alias : '';
            $MachineAddress = isset($response->data->reserve->address->address) ? $response->data->reserve->address->address : '';
            $MachineAddress = !empty($MachineAlias) ? $MachineAlias : (!empty($MachineAddress) ? $MachineAddress : '');
            $MachineUsername = isset($response->data->template->username) ? $response->data->template->username : '';
            $MachinePass = isset($response->data->password) ? $response->data->password : '';
            $ThisMachineID = isset($response->data->id) ? $response->data->id : '';

            // $dataPrint = array(
            //     'MachineAddress' => $MachineAddress,
            //     'MachineUsername' => $MachineUsername,
            //     'MachinePass' => $MachinePass,
            //     'ThisMachineID' => $ThisMachineID,
            // );

            // $this->createthefile($dataPrint);
            
            // Insert to DataBase
                try {
                    WDB::insert('autovm_order', $params);
                    return [
                        'ip'           => $MachineAddress,
                        'assigned_ips' => [],
                        'login'        => [
                            'username' => $MachineUsername,
                            'password' => $MachinePass,
                        ],
                        'config' => ['machineId' => $ThisMachineID],
                    ];
                    
                    
                } catch (Exception $e) {
                    $this->error = 'Could not Database for Order';
                    return false;
                }
            //

            return true;
        }
    //

    // templates 
        public function sendTemplatesRequest()
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'frontend', 'common', 'templates'
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_templates()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendTemplatesRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_templates()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendTemplatesRequest($machineId);

            $this->response($response);
        }
    // 

    // machine 
        public function sendMachineRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'machine', 'show', $machineId
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_machine()
        {
            $machineId = $this->getMachineIdFromService();
            
            // Send request
            $response = $this->sendMachineRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_machine()
        {
            $machineId = $this->getMachineIdFromService();
            
            // Send request
            $response = $this->sendMachineRequest($machineId);

            $this->response($response);
        }
    //

    // detail 
        public function sendDetailRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'machine', 'detail', $machineId
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_detail()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendDetailRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_detail()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendDetailRequest($machineId);

            $this->response($response);
        }
    //
    
    // Current Traffic 
        public function sendCurrentTrafficUsageRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'graph', 'machine', $machineId, 'traffic', 'current'
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_currenttrafficusage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendCurrentTrafficUsageRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_currenttrafficusage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendCurrentTrafficUsageRequest($machineId);

            $this->response($response);
        }
    // 
    
    // memoryUsage 
        public function sendMemoryUsageRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'graph', 'machine', $machineId, 'memory', 'daily'
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_memoryUsage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendMemoryUsageRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_memoryUsage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendMemoryUsageRequest($machineId);

            $this->response($response);
        }
    // 
    
    // CPU Usage 
        public function sendCpuUsageRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'graph', 'machine', $machineId, 'cpu', 'daily'
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_cpuUsage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendCpuUsageRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_cpuUsage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendCpuUsageRequest($machineId);

            $this->response($response);
        }
    // 
    
    // Traffic Usage 
        public function sendTrafficUsageRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'graph', 'machine', $machineId, 'traffic', 'daily'
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_trafficusage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendTrafficUsageRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_trafficusage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendTrafficUsageRequest($machineId);

            $this->response($response);
        }
    // 
    
    // bandwidth Usage 
        public function sendBandwidthUsageRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'graph', 'machine', $machineId, 'bandwidth', 'daily'
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_bandwidthusage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendBandwidthUsageRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_bandwidthusage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendBandwidthUsageRequest($machineId);

            $this->response($response);
        }
    // 
    
    // current Bandwidth Usage 
        public function sendCurrentBandwidthUsageRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'graph', 'machine', $machineId, 'bandwidth', 'current'
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_currentbandwidthusage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendCurrentBandwidthUsageRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_currentbandwidthusage()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendCurrentBandwidthUsageRequest($machineId);

            $this->response($response);
        }
    // 

    // Categories 
        public function sendCategoriesRequest()
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'frontend', 'common', 'template', 'categories'
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_categories()
        {
            $response = $this->sendCategoriesRequest();

            $this->response($response);
        }
        
        public function use_adminArea_categories()
        {
            $response = $this->sendCategoriesRequest();

            $this->response($response);
        }
    //
     
    // softwares 
        public function sendSoftwaresRequest()
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'frontend', 'common', 'software', 'categories'
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_softwares()
        {
            $response = $this->sendSoftwaresRequest();

            $this->response($response);
        }
        
        public function use_adminArea_softwares()
        {
            $response = $this->sendSoftwaresRequest();

            $this->response($response);
        }
    // 
    
    //  setup  
        public function sendSetupRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'machine', 'setup', $machineId
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_setup()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendSetupRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_setup()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendSetupRequest($machineId);

            $this->response($response);
        }
    //  

    // Change 
        public function sendChangeRequest($machineId, $templateId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'machine', 'change', $machineId
            ];

            $params = ['templateId' => $templateId];

            return Request::instance()->setAddress($address)->setHeaders($headers)->setParams($params)->getResponse()->asObject();
        }

        public function use_clientArea_change()
        {
            $machineId = $this->getMachineIdFromService();
            
            if($_GET['templateid']){
                $templateId = $_GET['templateid'];
            }

            // Send request
            $response = $this->sendChangeRequest($machineId, $templateId);

            $this->response($response);
        }
        
        public function use_adminArea_change()
        {
            $machineId = $this->getMachineIdFromService();
            
            if($_GET['templateid']){
                $templateId = $_GET['templateid'];
            }

            // Send request
            $response = $this->sendChangeRequest($machineId, $templateId);

            $this->response($response);
        }
    // 

    // start 
        public function sendStartRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'machine', 'start', $machineId
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_start()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendStartRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_start()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendStartRequest($machineId);

            $this->response($response);
        }
    // 
    
    // stop 
        public function sendStopRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'machine', 'stop', $machineId
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_stop()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendStopRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_stop()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendStopRequest($machineId);

            $this->response($response);
        }
    // 

    // reboot 
        public function sendRebootRequest($machineId)
        {
            $headers = ['token' => $this->token];

            $address = [
                $this->address, 'candy', 'backend', 'machine', 'reboot', $machineId
            ];

            return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
        }

        public function use_clientArea_reboot()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendRebootRequest($machineId);

            $this->response($response);
        }
        
        public function use_adminArea_reboot()
        {
            $machineId = $this->getMachineIdFromService();

            // Send request
            $response = $this->sendRebootRequest($machineId);

            $this->response($response);
        }
    // 
    

    public function sendSnapshotRequest($machineId)
    {
        $headers = ['token' => $this->token];

        $address = [
            $this->address, 'candy', 'backend', 'machine', 'snapshot', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function use_clientArea_snapshot()
    {
        $machineId = $this->getMachineIdFromService();

        // Send request
        $response = $this->sendSnapshotRequest($machineId);

        $this->response($response);
    }

    public function sendRevertRequest($machineId)
    {
        $headers = ['token' => $this->token];

        $address = [
            $this->address, 'candy', 'backend', 'machine', 'revert', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function use_clientArea_revert()
    {
        $machineId = $this->getMachineIdFromService();

        // Send request
        $response = $this->sendRevertRequest($machineId);

        $this->response($response);
    }
    
    // as before
    public function sendConsoleRequest($machineId)
    {
        $headers = ['token' => $this->token];

        $address = [
            $this->address, 'candy', 'backend', 'machine', 'console', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function use_clientArea_console()
    {
        $machineId = $this->getMachineIdFromService();

        // Send request
        $response = $this->sendConsoleRequest($machineId);

        $this->response($response);
    }

    // as before
    public function getMachineIdFromService()
    {
        // Find order
        $orderId = $this->getArray('id', $this->order);

        // Find machine
        $machine = WDB::select('machine_id')
            ->from('autovm_order')
            ->where('order_id', '=', $orderId);

        $machine = $machine->build(true)
            ->getObject();
        
        // The first value
        return $machine->machine_id;
    }

    public function response($response)
    {
        header('Content-Type: application/json');

        $response = json_encode($response);

        exit($response);
    }

    public function hasArray($name, $array)
    {
        if (array_key_exists($name, $array)) {

            return true;
        }

        return false;
    }

    public function getArray($name, $array)
    {
        if (array_key_exists($name, $array)) {

            return $array[$name];
        }

        return null;
    }

    // Dlet Machine
    public function use_adminArea_DestroyMachine()
    {
        $machineId = $this->getMachineIdFromService();
        $response = $this->sendDestroyRequest($machineId);
        $this->response($response); 
    }

    public function sendDestroyRequest($machineId)
    {
        $headers = ['token' => $this->token];

        $address = [
            $this->address, 'candy', 'backend', 'machine', 'destroy', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    
    // GET machineid admin only
    public function use_adminArea_getMachineidFromDatabase()
    {
        $machineId = $this->getMachineIdFromService();
        
        $response =  $machineId;
        $this->response($response);
        
    }
    
    // SET machineid admin only
    public function use_adminArea_changeMachineID()
    {
        // Receive newID from reguest
        if($_GET['newId']){
            $newId = $_GET['newId'];
        } else {
            $response = "Error: New Id did not find";
        }
        
        // check new ID
        if($newId && is_numeric($newId)){
            // Find order
            $orderId = $this->getArray('id', $this->order);
            
            $set = [
                'machine_id' => $newId,
            ];
            
            $operation = WDB::update("autovm_order");
            $operation->set($set);
            $operation->where("order_id","=", $orderId);
            $save = $operation->save();
            
            if($save)
            {
                $response = "Successful";
            }
            else
            {
                $set = [
                    'machine_id' => $newId,
                    'order_id' => $orderId,
                ];
                $insert = WDB::insert("autovm_order",$set);
                $response = "Order Did not find";
            }

        } else {
            $response = "Error: New Id is not valid";
        }

        $this->response($response);

    }

    public function suspend()
    {
        
        try
        {
            $machineId = $this->getMachineIdFromService();
            $response = $this->sendSuspendRequest($machineId);
            // $this->createthefile($response);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return false;
        }

        
        if(property_exists($response, 'message')){
            $message = $response->message;
        }

        if (!empty($message)) {
            $this->error = $message;
            return false;
        }

        return true;

    }
    
    public function sendSuspendRequest($machineId)
    {
        $headers = ['token' => $this->token];
        $address = [ $this->address, 'candy', 'backend', 'machine', 'suspend', $machineId ];
        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }


    public function unsuspend()
    {   
        try
        {
            $machineId = $this->getMachineIdFromService();
            $response = $this->sendUnsuspendRequest($machineId);
            // $this->createthefile($response);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return false;
        }

        
        if(property_exists($response, 'message')){
            $message = $response->message;
        }
            

        if (!empty($message)) {
            $this->error = $message;
            return false;
        }
        return true;
    }


    public function sendUnsuspendRequest($machineId)
    {
        $headers = ['token' => $this->token];
        $address = [ $this->address, 'candy', 'backend', 'machine', 'unsuspend', $machineId ];
        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function terminate()
    {   
        try
        {
            $machineId = $this->getMachineIdFromService();
            $response = $this->sendDestroyRequest($machineId);
            // $this->createthefile($response);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return false;
        }

        
        if(property_exists($response, 'message')){
            $message = $response->message;
        }

        if (!empty($message)) {
            $this->error = $message;
            return false;
        }
        return true;
    }

    public function sendTerminateRequest($machineId)
    {
        $headers = ['token' => $this->token];
        $address = [ $this->address, 'candy', 'backend', 'machine', 'destroy', $machineId ];
        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }


    // crate file to show data
    public function createthefile($text)
    {
        $filePath = __DIR__ . '/file.txt';
        $result = file_put_contents($filePath, var_export($text, true));
    }

}

















