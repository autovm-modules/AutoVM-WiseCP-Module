const app = Vue.createApp({
    data() {
        return {

            loading: false,
            detailIsLoaded: false,
            machineIsLoaded: false,
            doingAction: '',
            showBTN: true,
            showPendingMsg: false,
            isVisibe: true,
            isValidMachine: true,

            isBetweenPending: false,
            machine: {},
            detail: {},
            uptimeformated: {},
            softwares: {},

            thereisnodata: true,
            memoryChart: {
                data: [],
                month: 'Jan',
                min: 0,
                max: 100,
            },

            cpuChart: {
                data: [],
                month: 'Jan',
                min: 0,
                max: 100,
            },

            cpuLinearHasData: true,
            RamLinearHasData: true,

            isCopied: false,
            showpassword: 'false',
            actionIsPended: false,

            bandwidth: {},
            confirmDialog: false,
            confirmTitle: '---',
            confirmText: '---',


            messageDialog: false,
            messageText: 'text',

            templateId: null,
            softwareId: null,
            tempNameSetup: '',
            tempIconSetup: '',

            templates: [],
            section: 3000,

            hasMemoryLiniar: false,
            hasCPULiniar: false,


            hasCPUradial: false,
            hasRAMradial: false,
            hasDISKradial: false,
            hasBandwidthradial: false,
            cpuRadial: null,
            ramRadial: null,
            diskRadial: null,
            bandwidthRadial: null,

            config: {
                cpu: 0,
                memory: 0,
                storage: 0,
                storagestyle: "width: 100%; height:20px",
            },
        }
    },

    mounted() {

        // Load detail
        this.loadMachine()

        // Load detail
        this.loadDetail()

        // Load bandwidth
        this.loadBandwidth()

        // Load templates
        this.loadTemplates()

        // Load polling
        this.loadPolling()

        // Load Softwares
        this.loadSoftwares()

        // my modules
        this.changeTimeVisibilty()


        // Radial Charts
        if (this.$refs.cpuRadial) {
            this.createCPURadialGraph()
        }

        if (this.$refs.ramRadial) {
            this.createRAMRadialGraph()
        }

        if (this.$refs.diskRadial) {
            this.createDISKRadialGraph()
        }





        // Fetch Linear Charts Data
        this.getMemoryLinearData()
        this.getCPULinearData()

        // Linear Charts
        if (this.$refs.RAMLinear) {
            this.createMemoryLinearChart()
        }

        if (this.$refs.CPULinear) {
            this.createCPULinearChart()
        }



    },

    watch: {
        machine() {

        },

        hasMemoryLiniar() {
            if (this.$refs.RAMLinear) {
                this.createMemoryLinearChart()
            }
        },

        hasCPULiniar() {
            if (this.$refs.CPULinear) {
                this.createCPULinearChart()
            }
        },

        detail() {
            this.loadconfig()
            this.formateduptime()

            // Radial Graph
            if (this.$refs.cpuRadial) {
                this.createCPURadialGraph()
            }
            if (this.$refs.ramRadial) {
                this.createRAMRadialGraph()
            }

            if (this.$refs.diskRadial) {
                this.createDISKRadialGraph()
            }


        },
    },

    computed: {

        actionMethod() {
            let actionMethod = this.getMachineProperty('action.method');

            if (actionMethod == 'reboot') {
                return "rebootaction";
            }
            else if (actionMethod == 'stop') {
                return "stopaction";
            }
            else if (actionMethod == 'start') {
                return "startaction";
            }
            else if (actionMethod == 'setup') {
                return "setupaction";
            }
            else if (actionMethod == 'console') {
                return "consoleaction";
            }
            else if (actionMethod == 'destroy') {
                return "destroyaction";
            }
            else if (actionMethod == 'suspend') {
                return "suspend";
            }
            else if (actionMethod == 'unsuspend') {
                return "unsuspend";
            }
            else if (actionMethod == 'snapshot') {
                return "snapshot";
            }
            else {
                return actionMethod;
            }
        },

        actionStatus() {
            let actionStatus = this.getMachineProperty('action.status');

            if (actionStatus == 'completed') {
                return 'completed';
            }
            else if (actionStatus == 'pending') {
                return 'pending';
            }
            else if (actionStatus == 'processing') {
                return 'processing';
            }
            else if (actionStatus == 'failed') {
                return 'failed';
            }
            else if (actionStatus == 'canceled') {
                return 'canceled';
            }
            else {
                return actionStatus;
            }
        },

        serviceId() {
            let params = new URLSearchParams(window.location.search)
            if (params.get('id')) {
                return (params.get('id'))
            }
        },

        baseApiUrl() {
            // find site address
            let url = window.location.origin;
            if (this.userType == 'client') {
                url = url + '/myaccount/myproducts-detail/' + this.serviceId
            }

            if (this.userType == 'admin') {
                url = url + '/admin/orders/detail/' + this.serviceId
            }
            return url
        },

        userType() {
            let params = new URLSearchParams(window.location.search)
            user = params.get('u')

            if (user == 'admin') {
                return 'admin'
            } else {
                return 'client'
            }
        },

        findTemplate() {

            let cats = this.categories

            let id = this.templateId

            for (let i = 0; i < cats.length; i++) {

                let temp = cats[i].templates

                for (let j = 0; j < temp.length; j++) {
                    if (temp[j].id == id) {
                        return temp[j].name;
                    }
                }
            }

            return 'er';

        },

        findTemplateName() {

            let cats = this.categories

            let id = this.templateId

            for (let i = 0; i < cats.length; i++) {

                let temp = cats[i].templates

                for (let j = 0; j < temp.length; j++) {
                    if (temp[j].id == id) {
                        this.tempNameSetup = temp[j].name
                        return temp[j].name;
                    }
                }
            }

            return 'er';

        },

        findTemplateIcon() {

            let cats = this.categories

            let id = this.templateId

            for (let i = 0; i < cats.length; i++) {

                let temp = cats[i].templates

                for (let j = 0; j < temp.length; j++) {
                    if (temp[j].id == id) {
                        this.tempIconSetup = temp[j].icon
                        return temp[j].icon;
                    }
                }
            }

            return 'er';

        },

        findSoftware() {

            let list = this.softwares

            let id = this.softwareId

            for (let i = 0; i < list.length; i++) {

                let softs = list[i].softwares

                for (let j = 0; j < softs.length; j++) {
                    if (softs[j].id == id) {
                        return softs[j].name;
                    }
                }
            }

            return 'er';

        },

        actionisprocessing() {

            let status = this.getMachineProperty('action.status')

            if (status == 'processing') {

                return true

            } else {

                return false

            }
            return false

        },

        online() {

            let value = this.getDetailProperty('powerStatus.value')

            if (this.isOnline(value)) {
                return true
            } else {
                return false
            }
        },

        offline() {

            let value = this.getDetailProperty('powerStatus.value')

            if (this.isOffline(value)) {
                return true
            } else {
                return false
            }
        },

        address() {

            let listOfReserves = []

            if (this.isNotEmpty(this.machine)) {

                listOfReserves = _.filter(this.machine.reserves, reserve => this.isActive(reserve.status))
            }

            let listOfIPs = []

            _.forEach(listOfReserves, function (reserve) {

                listOfIPs.push(reserve.address.address)
            })

            return listOfIPs.shift()
        },

        categories() {

            let listOfTemplates = []

            if (this.isNotEmpty(this.templates)) {

                listOfTemplates = _.filter(this.templates, template => this.isNotEmpty(template.category))
            }

            let listOfCategories = _(listOfTemplates).groupBy('category.id').map(function (templates) {

                let template = _.head(templates)

                return { 'name': template.category.name, 'icon': template.category.icon, 'templates': templates }
            }).value()

            return listOfCategories
        },

        console() {

            return this.machine.console

        },

        consoleIsPending() {

            let status = this.getMachineProperty('console.status')

            if (this.isPending(status)) {
                return true
            } else {
                return false
            }
        },

        consoleIsProcessing() {

            let status = this.getMachineProperty('console.status')

            if (this.isProcessing(status)) {
                return true
            } else {
                return false
            }
        },

        consoleIsCompleted() {

            let status = this.getMachineProperty('console.status')

            if (this.isCompleted(status)) {
                return true
            } else {
                return false
            }
        },

        consoleIsFailed() {

            let status = this.getMachineProperty('console.status')

            if (this.isFailed(status)) {
                return true
            } else {
                return false
            }
        },

        action() {
            let theaction = this.getMachineProperty('action')
            return theaction
        },

        total() {

            let value = this.getBandwidthProperty('value')

            if (value) {
                return Number(value / 1000 / 1000 / 1000).toFixed(2)
            }
            return value
        },

        transmitted() {

            let value = this.getBandwidthProperty('sent')

            if (value) {
                value = Number(value / 1000 / 1000 / 1000).toFixed(2)
            }

            return value
        },

        received() {

            let value = this.getBandwidthProperty('received')

            if (value) {
                return Number(value / 1000 / 1000 / 1000).toFixed(2)
            }

            return value
        },

        tempName() {
            let tempName = null
            tempName = this.getMachineProperty('template.name')
            return tempName
        },

        tempIcon() {
            let tempIcon = null;
            tempIcon = this.getMachineProperty('template.icon.address')
            return tempIcon
        },

        softIcon() {
            let softIcon = null;
            softIcon = this.getMachineProperty('software.template.icon.address')
            return softIcon
        },

        softName() {
            let softName = null
            softName = this.getMachineProperty('software.name')
            return softName
        },

        machineUserName() {
            let username = ''
            username = this.getMachineProperty('template.username')

            if (username) {
                return username
            } else {
                return '---'
            }
        },

        machineUserPass() {
            let userpass = ''
            userpass = this.getMachineProperty('password')
            if (userpass) {
                return userpass
            } else {
                return '*********'
            }
        },

        actions() {
            return this.getMachineProperty('actions')
        },

        traffics() {
            let traffics = [];
            traffics = this.getMachineProperty('traffics')
            return traffics
        },

        hasalias() {
            let alias = this.getMachineProperty('reserve.address.alias')
            if (alias) {
                return true
            } else {
                return false
            }
        },

        alias() {
            return this.getMachineProperty('reserve.address.alias')
        },

        theMemoryLimit() {
            let memoryLimit = this.getMachineProperty('memoryLimit')
            return memoryLimit
        },

        theCpuLimit() {
            let cpuLimit = this.getMachineProperty('cpuLimit')
            return cpuLimit
        },

        diskSize() {
            let diskSize = this.getMachineProperty('diskSize')
            return diskSize
        },

        memoryUsage() {
            let memoryUsage = this.getDetailProperty('memoryUsage.value')
            return memoryUsage
        },

        cpuUsage() {
            let cpuUsage = this.getDetailProperty('cpuUsage.value')
            return cpuUsage
        },

        diskUsage() {
            let diskUsage = this.getDetailProperty('diskUsage.value')
            return diskUsage
        },


    },

    methods: {

        changeTimeVisibilty() {
            setTimeout(() => {
                this.isVisibe = false;
            }, 7000); // 10 seconds later
        },

        loadconfig() {

            this.config.cpu = this.getMachineProperty('cpuCore')
            this.config.memory = this.getMachineProperty('memorySize')
            this.config.storage = this.getMachineProperty('diskSize')
            this.config.storagestyle = 'width: ' + this.getMachineProperty('diskSize') + '%; height:20px'

        },

        getMachineProperty(name, empty = null) {

            let value = _.get(this.machine, name)

            if (value) {
                return value
            } else {
                return empty
            }

        },

        getDetailProperty(name, empty = null) {

            let value = _.get(this.detail, name)

            if (value) {
                return value
            } else {
                return empty
            }
        },

        getBandwidthProperty(name, empty = null) {

            let value = _.get(this.bandwidth, name)

            if (value) {
                return value
            } else {
                return empty
            }
        },

        showSection(section) {

            this.section = section
        },

        changeVisibility() {
            this.showpassword = !this.showpassword
        },

        isSection(section) {

            if (this.section == section) {
                return true
            } else {
                return false
            }
        },

        isActive(status) {

            if (status == 'active') {
                return true
            } else {
                return false
            }
        },

        isPassive(status) {

            if (status == 'passive') {
                return true
            } else {
                return false
            }
        },

        isOnline(status) {

            if (status == 'online') {
                return true
            } else {
                return false
            }
        },

        isOffline(status) {

            if (status == 'offline') {
                return true
            } else {
                return false
            }
        },

        isEmpty(value) {

            if (_.isEmpty(value)) {
                return true
            } else {
                return false
            }
        },

        isNotEmpty(value) {

            if (_.isEmpty(value)) {
                return false
            } else {
                return true
            }
        },

        isPending(status) {

            if (status == 'pending') {
                return true
            } else {
                return false
            }
        },

        isProcessing(status) {

            if (status == 'processing') {
                return true
            } else {
                return false
            }
        },

        isCompleted(status) {

            if (status == 'completed') {
                return true
            } else {
                return false
            }
        },

        isFailed(status) {

            if (status == 'failed') {
                return true
            } else {
                return false
            }
        },

        openConfirmDialog(title, text) {


            // Open dialog
            this.confirmDialog = true

            // Content
            this.confirmText = text
            this.confirmTitle = title.toLowerCase()

            // Promise
            return new Promise((resolve) => this.confirmResolve = resolve)
        },

        acceptConfirmDialog() {

            this.confirmResolve(true)

            // Close dialog
            this.confirmDialog = false
            this.isBetweenPending = true

        },

        closeConfirmDialog() {

            this.confirmResolve(false)

            // Close dialog
            this.confirmDialog = false
        },

        openMessageDialog(text) {

            // Open dialog
            this.messageDialog = true

            // Content
            this.messageText = text

            // Promise
            return new Promise((resolve) => this.messageResolve = resolve)
        },

        closeMessageDialog() {

            this.messageResolve(false)

            // Close dialog
            this.messageDialog = false
        },

        loadPolling() {

            // Load machine
            setInterval(this.loadMachine, 30000)

            // Load detail
            setInterval(this.loadDetail, 35000)
        },

        // checked 01
        async loadMachine() {
            try {
                const response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "machine"
                    },
                });

                if (response.data.data) {
                    this.machineIsLoaded = true
                    this.machine = response.data.data;
                }

                if (response.data.message == 'There is nothing.') {
                    this.machineIsLoaded = true
                    this.isValidMachine = false;
                    console.log(response.data.message);
                }

            } catch (error) {
                this.machineIsLoaded = false
                console.error('loadMachine in vue didnt work:', error);
            }
        },

        // checked 02
        async loadDetail() {
            try {
                const response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "detail"
                    },
                });

                if (response.data.data) {
                    this.detailIsLoaded = true
                    this.detail = response.data.data;
                    this.setDetailLoadStatus()
                }
            } catch (error) {
                this.detailIsLoaded = false
                console.error('loadDetail in vue didnt work:', error);
            }
        },

        // checked 03
        async loadCategories() {
            try {
                const response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "categories"
                    },
                });

                if (response.data.data) {
                    this.categories = response.data.data;
                }
            } catch (error) {
                console.error('LoadCategories in vue didnt work:', error);
            }
        },

        // checked 04
        async loadSoftwares() {
            try {
                const response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "softwares"
                    },
                });

                if (response.data.data) {
                    this.softwares = response.data.data;
                }
            } catch (error) {
                console.error('LoadSoftwares in vue didnt work:', error);
            }
        },

        // checked 05
        async doReboot() {
            let accept = await this.openConfirmDialog(this.lang('Reboot'), this.lang('Are you sure about this?'))
            if (accept) {
                let response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "reboot"
                    },
                });

                response = response.data
                if (response.message) {
                    this.openMessageDialog(this.lang(response.message))
                }
                if (response.data) {
                    this.doingAction = 'Reboot'
                    this.machine = response.data
                }
            }
        },

        // checked 06
        async doStop() {
            let accept = await this.openConfirmDialog(this.lang('Stop'), this.lang('Are you sure about this?'))
            if (accept) {
                let response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "stop"
                    },
                });

                response = response.data
                if (response.message) {
                    this.openMessageDialog(this.lang(response.message))
                }
                if (response.data) {
                    this.doingAction = 'Stop'
                    this.machine = response.data
                }
            }
        },

        // checked 07
        async doStart() {
            let accept = await this.openConfirmDialog(this.lang('Start'), this.lang('Are you sure about this?'))
            if (accept) {
                let response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "start"
                    },
                });

                response = response.data
                if (response.message) {
                    this.openMessageDialog(this.lang(response.message))
                }
                if (response.data) {
                    this.doingAction = 'Start'
                    this.machine = response.data
                }
            }
        },

        // checked 08
        async doSetup() {
            let accept = await this.openConfirmDialog(this.lang('Setup'), this.lang('Are you sure about this?'))
            if (accept) {
                let response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "setup"
                    },
                });

                response = response.data
                if (response.message) {

                    this.openMessageDialog(this.lang(response.message))
                }

                if (response.data) {
                    this.doingAction = 'SetUp'
                    this.machine = response.data
                }
            }
        },

        // checked 09
        async loadTemplates() {
            try {
                const response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "templates"
                    },
                });

                if (response.data.data) {
                    this.templates = response.data.data;
                }
            } catch (error) {
                console.error('Load Template in vue didnt work:', error);
            }
        },

        // checked 10
        async loadBandwidth() {
            try {
                const response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "currentBandwidthUsage"
                    },
                });

                if (response.data.data) {
                    this.bandwidth = response.data.data;
                }
            } catch (error) {
                console.error('Load Bandwidth in vue didnt work:', error);
            }
        },

        // checked 11
        async doConsole() {
            let accept = await this.openConfirmDialog(this.lang('Console'), this.lang('Are you sure about this?'))
            if (accept) {
                let response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "console"
                    },
                });

                response = response.data
                if (response.message) {
                    this.openMessageDialog(this.lang(response.message))
                }
                if (response.data) {
                    this.doingAction = 'Console'
                    this.machine = response.data
                    console.log(this.machine)
                }
            }
        },

        // checked 12
        openConsole() {

            let address = 'https://console.autovm.net'

            let params = new URLSearchParams({
                'host': this.machine.console.proxy.proxy, 'port': this.machine.console.proxy.port, 'ticket': this.machine.console.ticket
            }).toString()

            return window.open([address, params].join('?'))
        },

        // checked 13    
        async doChange(event) {
            event.preventDefault()
            let accept = await this.openConfirmDialog(this.lang('Setup'), this.lang('Are you sure about this?'))
            if (accept) {
                let response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "change",
                        templateid: this.templateId
                    },
                });

                response = response.data
                if (response.message) {
                    this.openMessageDialog(this.lang(response.message))
                }
                if (response.data) {
                    this.machine = response.data
                }
            }
        },

        // 14
        async doSoftware(event) {
            event.preventDefault()
            let accept = await this.openConfirmDialog(this.lang('Softinstall'), this.lang('Are you sure about this?'))
            if (accept) {
                let response = await axios.get(this.baseApiUrl, {
                    params: {
                        inc: "panel_operation_method",
                        method: "changeSoftware",
                        templateid: this.softwareId
                    },
                });

                response = response.data
                if (response.message) {

                    this.openMessageDialog(this.lang(response.message))
                }
                if (response.data) {

                    this.machine = response.data
                }
            }
        },

        formateduptime() {

            seconds = this.getDetailProperty('uptime.value')

            if (seconds < 61) {
                seconds = 62
            }

            let days = Math.floor(seconds / (3600 * 24));

            seconds -= days * 3600 * 24;
            let hours = Math.floor(seconds / 3600);

            seconds -= hours * 3600;
            let minutes = Math.floor(seconds / 60);
            seconds -= minutes * 60;

            let result = {
                day: '',
                hr: '',
                minuts: ''
            };
            if (days > 0) {
                result.day = days;
            }

            if (hours > 0 || days > 0) {
                result.hr = hours;
            }

            if (minutes > 0 || hours > 0 || days > 0) {
                result.minuts = minutes;
            }
            this.uptimeformated = result

        },

        // Option for Radial
        createOptionRadials(series, colors, labels) {
            let options = {
                chart: {
                    height: 230,
                    type: "radialBar"
                },

                series: series,
                colors: colors,

                plotOptions: {
                    radialBar: {
                        track: {
                            background: '#F9F5FF',
                        },
                        hollow: {
                            margin: 20,
                            size: "63%"
                        },

                        dataLabels: {
                            showOn: "always",
                            name: {
                                offsetY: -10,
                                show: true,
                                color: "#667085",
                                fontWeight: 300,
                                fontSize: "17px"
                            },
                            value: {
                                color: "#414755",
                                fontSize: "33px",
                                show: true
                            }
                        }
                    }
                },

                stroke: {
                    curve: 'smooth',
                    lineCap: "round",
                },
                labels: labels
            };
            return options
        },

        // RAM Radial
        getMemoryPercent() {

            // Memory limit
            let memoryLimit = this.getMachineProperty('memoryLimit')


            if (!memoryLimit) {
                memoryLimit = 0 // Default value
            }

            // Memory usage
            let memoryUsage = this.getDetailProperty('memoryUsage.value')

            if (!memoryUsage) {
                memoryUsage = 0 // Default value
            }

            // Calculate
            let percent = 0

            if (memoryLimit) {
                percent = (memoryUsage / memoryLimit) * 100
            }

            // Format
            return Number(percent).toFixed()
        },

        createRAMRadialGraph() {
            let element = document.querySelector('.ramRadial')
            let percent = this.getMemoryPercent();
            // create
            if (!this.hasRAMradial) {
                if (percent == 0) {
                    let options = {};
                    options = this.createOptionRadials(
                        series = [100],
                        colors = ["#7F56D9"],
                        labels = ["RAM Usage"],
                    );
                    this.ramRadial = new ApexCharts(element, options)
                    this.ramRadial.render()
                    this.hasRAMradial = true

                } else {

                    let options = {};
                    options = this.createOptionRadials(
                        series = [percent],
                        colors = ["#7F56D9"],
                        labels = ["RAM Usage"],
                    );
                    this.ramRadial = new ApexCharts(element, options)
                    this.ramRadial.render()
                    this.hasRAMradial = true
                }

            } else {
                if (percent != 0) {
                    // Update
                    this.ramRadial.updateSeries([percent], true)
                } else {
                    this.ramRadial.updateSeries([0], true)
                }
            }
        },

        // CPU Radial
        getCPUPercent() {

            // CPU limit
            let cpuLimit = this.getMachineProperty('cpuLimit')

            if (!cpuLimit) {
                cpuLimit = 0 // Default value
            }

            // CPU usage
            let cpuUsage = this.getDetailProperty('cpuUsage.value')

            if (!cpuUsage) {
                cpuUsage = 0 // Default value
            }

            // Calculate
            let percent = 0

            if (cpuLimit) {
                percent = (cpuUsage / cpuLimit) * 100
            }

            // Format            
            return Number(percent).toFixed()
        },

        createCPURadialGraph() {
            let element = document.querySelector('.cpuRadial')
            let percent = this.getCPUPercent();
            // create
            if (!this.hasCPUradial) {
                if (percent == 0) {
                    let options = {};
                    options = this.createOptionRadials(
                        series = [100],
                        colors = ["#2A4DD1"],
                        labels = ["CPU Usage"],
                    );
                    this.cpuRadial = new ApexCharts(element, options)
                    this.cpuRadial.render()
                    this.hasCPUradial = true

                } else {

                    let options = {};
                    options = this.createOptionRadials(
                        series = percent,
                        colors = ["#2A4DD1"],
                        labels = ["CPU Usage"],
                    );
                    this.cpuRadial = new ApexCharts(element, options)
                    this.cpuRadial.render()
                    this.hasCPUradial = true
                }

            } else {
                if (percent != 0) {
                    // Update
                    this.cpuRadial.updateSeries([percent], true)
                } else {
                    this.cpuRadial.updateSeries([0], true)
                }
            }
        },

        // Disk Radial
        getDiskPercent() {

            // Disk size
            let diskSize = this.getMachineProperty('diskSize')

            if (!diskSize) {
                diskSize = 0 // Default value
            }

            // Disk usage
            let diskUsage = this.getDetailProperty('diskUsage.value')

            if (!diskUsage) {
                diskUsage = 0 // Default value
            }

            // Calculate
            let percent = 0

            if (diskSize) {
                percent = ((diskUsage / 1024) / diskSize) * 100
            }

            // Format
            return Number(percent).toFixed()
        },

        createDISKRadialGraph() {
            let element = document.querySelector('.diskRadial')
            let percent = this.getDiskPercent();
            // create
            if (!this.hasDISKradial) {
                if (percent == 0) {
                    let options = {};
                    options = this.createOptionRadials(
                        series = [100],
                        colors = ["#56D9C1"],
                        labels = ["DISK Usage"],
                    );
                    this.diskRadial = new ApexCharts(element, options)
                    this.diskRadial.render()
                    this.hasDISKradial = true

                } else {

                    let options = {};
                    options = this.createOptionRadials(
                        series = percent,
                        colors = ["#56D9C1"],
                        labels = ["DISK Usage"],
                    );
                    this.diskRadial = new ApexCharts(element, options)
                    this.diskRadial.render()
                    this.hasDISKradial = true
                }

            } else {
                if (percent != 0) {
                    // Update
                    this.diskRadial.updateSeries([percent], true)
                } else {
                    this.diskRadial.updateSeries([0], true)
                }
            }
        },

        // Bandwidth Radial
        getBandwidthPercent() {

            // Machine Bandwidth size
            let bandwidthSize = this.getMachineProperty('bandwidth')

            if (!bandwidthSize) {
                bandwidthSize = 0 // Default value
            }

            // Disk usage
            let bandwidthUsage = this.getMachineProperty('bandwidth.value')

            if (!bandwidthUsage) {
                bandwidthUsage = 0 // Default value
            }

            // Calculate
            let percent = 0

            if (bandwidthSize) {
                percent = ((bandwidthUsage / 1073741824) / bandwidthSize) * 100
            }

            // Format
            return Number(percent).toFixed()
        },

        createBandwidthRadialGraph() {
            let element = document.querySelector('.bandwidthRadial')
            let percent = this.getBandwidthPercent();
            // create
            if (!this.hasBandwidthradial) {
                if (percent == 0) {
                    let options = {};
                    options = this.createOptionRadials(
                        series = [100],
                        colors = ["#F2BC6B"],
                        labels = ["Bandwidth"],
                    );
                    this.bandwidthRadial = new ApexCharts(element, options)
                    this.bandwidthRadial.render()
                    this.hasBandwidthradial = true

                } else {

                    let options = {};
                    options = this.createOptionRadials(
                        series = percent,
                        colors = ["#F2BC6B"],
                        labels = ["Bandwidth"],
                    );
                    this.bandwidthRadial = new ApexCharts(element, options)
                    this.bandwidthRadial.render()
                    this.hasBandwidthradial = true
                }

            } else {
                if (percent != 0) {
                    // Update
                    this.bandwidthRadial.updateSeries([percent], true)
                } else {
                    this.bandwidthRadial.updateSeries([0], true)
                }
            }
        },

        // Creator Option for Linear
        createoption(chartname, data, colors, text) {
            let options = {
                "series": [
                    {
                        "name": chartname,
                        "data": data
                    }
                ],
                "chart": {
                    "animations": {
                        "enabled": false,
                        "easing": "swing"
                    },
                    "background": "#fff",
                    "dropShadow": {
                        "blur": 3
                    },
                    "foreColor": "#373D3F",
                    "fontFamily": "Barlow",
                    "height": 370,
                    "id": "o4Rem",
                    "toolbar": {
                        "show": false,
                        "tools": {
                            "selection": true,
                            "zoom": true,
                            "zoomin": true,
                            "zoomout": true,
                            "pan": true,
                            "reset": true
                        }
                    },
                    "fontUrl": null
                },
                "colors": colors,
                "plotOptions": {
                    "bar": {
                        "borderRadius": 10
                    },
                    "radialBar": {
                        "hollow": {
                            "background": "#fff"
                        },
                        "dataLabels": {
                            "name": {},
                            "value": {},
                            "total": {}
                        }
                    },
                    "pie": {
                        "donut": {
                            "labels": {
                                "name": {},
                                "value": {},
                                "total": {}
                            }
                        }
                    }
                },
                "dataLabels": {
                    "enabled": true,
                    "offsetY": 6,
                    "style": {
                        "fontWeight": 300
                    },
                    "background": {
                        "borderRadius": 5,
                        "borderWidth": 1
                    }
                },
                "fill": {
                    "opacity": 1
                },
                "grid": {
                    "xaxis": {
                        "lines": {
                            "show": true
                        }
                    },
                    "column": {},
                    "padding": {
                        "right": 20,
                        "bottom": 6,
                        "left": 16
                    }
                },
                "legend": {
                    "showForSingleSeries": true,
                    "position": "top",
                    "horizontalAlign": "left",
                    "fontSize": 14,
                    "offsetX": 9,
                    "offsetY": 7,
                    "markers": {
                        "width": 30,
                        "height": 16,
                        "strokeWidth": 8,
                        "radius": 13,
                        "offsetY": 3,
                    },
                    "itemMargin": {
                        "horizontal": 10
                    }
                },


                "tooltip": {},
                "xaxis": {
                    "offsetY": -2,
                    "labels": {
                        "rotate": -45,
                        "trim": true,
                        "style": {
                            "fontSize": 12,
                            "fontWeight": 300
                        }
                    },
                    "axisBorder": {
                        "show": false
                    },
                    "tickAmount": 4,
                    "title": {
                        "text": "",
                        "style": {
                            "fontSize": 12,
                            "fontWeight": 300
                        }
                    }
                },
                "yaxis": {
                    "tickAmount": 6,
                    "min": 0,
                    "labels": {
                        "style": {
                            "fontSize": 12
                        },
                        offsetX: -12,
                        offsetY: 5,
                    },
                    "title": {
                        "text": "",
                        "style": {
                            "fontSize": 12,
                            "fontWeight": 300
                        }
                    }
                }

            };
            return options
        },

        // checked 16
        async getMemoryLinearData() {
            let response = await axios.get(this.baseApiUrl, {
                params: {
                    inc: "panel_operation_method",
                    method: "memoryUsage"
                },
            });

            // similiar from here
            let memoryChart = [{ x: '8/1', y: 0 }, { x: '8/2', y: 0 }]

            if (response.data?.data) {
                if (response.data.data.length > 0) {
                    memoryChart = []
                    response = response.data.data

                    for (let item of response) {
                        memoryChart.push({
                            x: item.month + '/' + item.day,
                            y: item.value,
                        })
                    }
                }
            }

            if (response.data?.message) {
                if (response.data.message == "There is nothing.") {
                    this.RamLinearHasData = false
                    console.log("Linear Memory has no data")
                }
            }

            this.memoryChart.data = memoryChart
            this.hasMemoryLiniar = true
        },

        // checked 17
        async getCPULinearData() {
            let response = await axios.get(this.baseApiUrl, {
                params: {
                    inc: "panel_operation_method",
                    method: "cpuUsage"
                },
            });

            // similiar from here
            // similiar from here
            let CPUChart = [{ x: '8/1', y: 0 }, { x: '8/2', y: 0 }]

            if (response.data?.data) {
                if (response.data.data.length > 0) {
                    CPUChart = []
                    response = response.data.data
                    for (let item of response) {
                        CPUChart.push({
                            x: item.month + '/' + item.day,
                            y: item.value,
                        })
                    }
                }
            }

            if (response.data?.message) {
                if (response.data.message == "There is nothing.") {
                    this.cpuLinearHasData = false
                    console.log("Linear CPU has no data")
                }
            }

            this.cpuChart.data = CPUChart
            this.hasCPULiniar = true
        },

        // Make a Memory Linear chart 
        createMemoryLinearChart() {

            let lenghtofdata = this.memoryChart.data.length;
            if (lenghtofdata > 4) {
                this.thereisnodata = false
            }

            // create option
            let options = this.createoption(
                chartname = ["Ram Usage"],
                data = this.memoryChart.data,
                colors = ['#7F56D9'],
                text = 'Ram Usage',
            )

            // Element
            let element = document.querySelector('.RAMLinear')

            // Create Chart
            var chart = new ApexCharts(element, options);
            chart.render();
        },

        //  Make a CPU Linear chart
        createCPULinearChart() {

            let lenghtofdata = this.cpuChart.data.length;
            if (lenghtofdata > 4) {
                this.thereisnodata = false
            }

            // create option
            let options = this.createoption(
                chartname = ["CPU Usage"],
                data = this.cpuChart.data,
                colors = ['#2A4DD1'],
                text = 'CPU Usage',
            )

            // Element
            let element = document.querySelector('.CPULinear')

            // Create Chart
            var chart = new ApexCharts(element, options);
            chart.render();

        },

        lang(name) {

            let output = name
            if (name != '') {
                _.forEach(words, function (first, second) {

                    if (second.toLowerCase() == name.toLowerCase()) {

                        output = first
                    }
                })
            }
            return output
        },

        setDetailLoadStatus() {
            this.detailIsLoaded = true
        },

        copyToClipboard(the_ref) {
            let pTag = this.$refs[the_ref];
            let range = document.createRange();
            range.selectNode(pTag);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand("copy");
            window.getSelection().removeAllRanges();

            this.isCopied = true;

            setTimeout(() => {
                this.isCopied = false;
            }, 1000);
        },

        getSetupOS() {
            let templates = this.templates
            let templateId = this.templateId
            function findname(template) {
                if (template.id == templateId) {
                    return template.name
                } else {
                    return false
                }
            }
            let templateName = templates.find(findname).name
            let templateIcon = templates.find(findname).icon.address
            this.tempNameSetup = templateName
            this.tempIconSetup = templateIcon
        },

        formatdate(time) {

            if (this.machineIsLoaded) {
                let year = time.slice(0, 4);
                let month = time.slice(5, 7);
                let day = parseInt(time.slice(8, 10), 10);
                let hour = time.slice(11, 13);
                let minutes = time.slice(14, 16);
                let seconds = time.slice(17, 19);

                const monthNameList = [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'June',
                    'July', 'August', 'Sep', 'Oct', 'Nov', 'Dec'
                ];

                let monthName = monthNameList[parseInt(month, 10) - 1];

                function formatday(day) {
                    switch (day) {
                        case 1:
                            return 'st';
                        case 2:
                            return 'nd';
                        case 3:
                            return 'rd';
                        default:
                            return ('th');
                    }
                }


                result = '<div class="d-flex flex-row justify-content-center align-items-center"><span class="text-secondary p-0 m-0 me-1">' + day + '<sup>' + formatday(day) + '</sup> <span class="ps-1">' + monthName + '</span></span><br class="py-2 my-2"><span class="fs-2 d-none d-md-block"> | </span><span class="text-body-secondary m-0 p-0 d-none d-md-block"><i class="bi bi-clock-fill px-1"></i>' + hour + ':' + minutes + ':' + seconds + '</span></div>';

                return (result)
            }
        }
    },
});

app.mount('#app')