<!-- Finance and Network -->
<div class="row d-flex flex-row align-items-stretch text-start m-0 p-0">

    <!-- finanace -->
    <div class="col-12 col-lg-12 col-xl-4 p-0 m-0 mb-3 order-1 order-md-3 order-lg-3 order-xl-1">
        <div class="border border-2 rounded-4 bg-white m-0 p-0 py-4 px-4 mx-0 me-xl-1  pb-5 h-100">
            <!-- top slice -->
            <div>
                <!-- title -->
                <div class="mb-4 d-flex flex-row justify-content-between m-0 p-0">
                    <span class="text-dark fw-medium m-0 p-0 fs-5 my-auto">
                        {{ lang('finance') }}
                    </span>
                </div>

                <!-- Balance -->
                <div class="row align-items-center m-0 p-0">
                    <div class="col-6 col-md-4 col-xl-7 m-0 p-0">
                        <?php if($currencysymb == 'تومان' || $currencysymb == 'ریال' || $currencysymb == 'هزار تومان'):?>
                            <?php if($recurringpayment == 0): ?>
                                <h4 class="text-primary align-middle m-0 p-0">
                                    {{ lang('free') }}
                                </h4>
                            <?php else: ?>
                                <h4 class="text-primary align-middle m-0 p-0">    
                                    <?php echo (round($recurringpayment)); ?>
                                    <?php echo ($currencysymb); ?>
                                </h4>
                            <?php endif ?>
                        <?php else: ?>
                            <h1 class="text-primary align-middle m-0 p-0" style="font-size: 35px;">
                                <?php if($recurringpayment == 0): ?>
                                    {{ lang('free') }}
                                <?php else: ?>
                                    <?php echo ($currencysymb . round($recurringpayment, 2)); ?>
                                <?php endif ?>
                            </h1>
                        <?php endif; ?>
                    </div>
                    <div class="col-5 m-0 p-0">
                        <p class="text-secondary m-0 p-0">
                        {{ lang('servicesname') }}
                        </p>
                        <p> <?php echo ($productname); ?> </p>
                    </div>
                </div>
            </div><!-- end top -->

            <div class="py-4">
                <hr class="text-secondary border-2 border-secondary m-0 p-0">
            </div>

            <!-- bottom slice -->
            <div>
                <div class="m-0 p-0 mt-0">
                    <!-- Order Date -->
                    <div class="row m-0 p-0">
                        <div class="col-6 col-md-4 col-xl-7 m-0 p-0">
                            <span class="text-secondary align-middle m-0 p-0">
                                {{ lang('registrationdate') }}
                            </span>
                        </div>
                        <div class="col-5 m-0 p-0">
                            <span class="text-dark align-middle m-0 p-0">
                                <?php echo ($renewedon); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Next Payment -->
                    <div class="row m-0 p-0">
                        <div class="col-6 col-md-4 col-xl-7 m-0 p-0">
                            <span class="text-secondary align-middle m-0 p-0">
                                {{ lang('nextpayment') }}
                            </span>
                        </div>
                        <div class="col-5 m-0 p-0">
                            <span class="text-dark align-middle m-0 p-0">
                                <?php echo ($renewaldate); ?>
                            </span>
                        </div>
                    </div>

                    <!-- Billing Cycle -->
                    <div class="row m-0 p-0">
                        <div class="col-6 col-md-4 col-xl-7 m-0 p-0">
                            <span class="text-secondary align-middle m-0 p-0">
                            {{ lang('billingcycle') }}
                            </span>
                        </div>
                        <div class="col-5 m-0 p-0">
                            <span class="text-dark align-middle m-0 p-0">
                                <?php echo ($billingcycle); ?>
                            </span>
                        </div>
                    </div>

                </div>
            </div><!-- end bottom -->
        </div>
    </div><!-- end finanace -->

    <!-- Network -->
    <div class="col-12 col-md-8 col-lg-6 col-xl-5 m-0 p-0 flex-grow-1 p-0 m-0 mb-3 order-2 order-md-2 order-lg-2 order-xl-2">
        <div class="border border-2 rounded-4 bg-white m-0 p-0 py-4 px-4 mx-0 ms-md-1 mx-xl-1 pb-5 h-100">
            <!-- top slice -->
            <div class="m-0 p-0">
                <!-- title -->
                <div class="m-0 p-0 mb-5 d-flex flex-row justify-content-between">
                    <span class="text-dark fw-medium m-0 p-0 fs-5 my-auto">
                        {{ lang('networkinformation') }}
                    </span>
                    <img src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/img/internet.svg" alt="internet">
                </div>



                <!-- ip -->
                <div class="mt-4 fs-4 d-flex flex-row justify-content-between m-0 p-0">
                    <div class="text-start m-0 p-0">
                        <span class="text-secondary fs-6 align-middle m-0 p-0">
                            {{ lang('ipaddress') }}
                        </span>

                        <span v-if="!machineIsLoaded || !address"
                            class="text-primary fw-medium m-0 p-0 ps-4 fs-2 align-middle">
                            ---
                        </span>
                        <span v-if="machineIsLoaded && address && !alias" ref="ipTag" class="text-primary fw-medium m-0 p-0 ps-4 fs-2 align-middle">
                            {{ address }}
                        </span>
                        <span v-if="machineIsLoaded && alias" ref="ipTag" class="text-primary fw-medium m-0 p-0 ps-4 fs-3 align-middle">
                            {{ alias }}
                        </span>
                    </div>
                    <div v-if="!isCopied"class="m-0 p-0" >
                        <img @click="copyToClipboard('ipTag')"
                            src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/img/ip.svg" alt="ipaddress">
                    </div>
                    <div v-if="isCopied" class="m-0 p-0">
                        <i class="bi bi-check-all text-primary"></i>
                    </div>
                </div>
            </div><!-- end top -->

            <div class="py-4">
                <hr class="text-secondary border-2 border-secondary m-0 p-0">
            </div>

            <!-- bottom slice -->
            <div>
                <div class="m-0 p-0 mt-0 fs-4">
                    <div class="d-flex flex-row justify-content-between m-0 p-0 mb-4">
                        <span class="text-secondary fs-6 m-0 p-0 align-middle">
                            {{ lang('networkstatus') }}
                        </span>
                    </div>
                    <div class="row d-flex flex-row m-0 p-0">
                        <div v-if="!detailIsLoaded" class="m-0 p-0 ps-3">
                            <div class="m-0 p-0">
                                <img src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/img/nounstatus.svg"
                                    width="20">
                                <span class="text-dark m-0 p-0 ps-4">
                                    --- 
                                </span>
                            </div>
                        </div>
                        <div v-else class="m-0 p-0 ps-3">
                            <div v-if="online" class="m-0 p-0">
                                <img src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/img/online.svg"
                                    width="20">
                                <span class="text-success ps-4">
                                    {{ lang('connected') }}
                                </span>
                            </div>

                            <div v-else-if="offline" class="m-0 p-0">
                                <img src="/coremio/modules/Servers/PlusAutoVM/pages/view/assets/img/offline.svg"
                                    width="20">
                                <span class="text-danger ps-4">
                                    {{ lang('disconnected') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end bottom -->
        </div>
    </div><!-- end Network -->

    <!-- Login -->
    <div class="col-12 col-md-4 col-lg-4 col-xl-3 p-0 m-0 mb-3 order-3 order-md-1 order-lg-1 order-xl-3">
        <div class="border border-2 rounded-4 bg-white m-0 p-0 py-4 px-4 mx-0 me-md-1 ms-xl-1 me-xl-0 pb-5 h-100">
            <div class="m-0 p-0 mb-5">
                <span class="text-dark fw-medium m-0 p-0 fs-5 my-auto">
                    {{ lang('login') }}
                </span>
            </div>

            <div class="m-0 p-0 mb-2 mt-4">
                <span class="text-secondary m-0 p-0 fs-6">
                    {{ lang('username') }}
                </span>
            </div>

            <div class="row m-0 p-0">
                <div class="input-group d-flex flex-row justify-content-between align-items-center m-0 p-0">
                    <div v-if="machineIsLoaded" class="input-group-text col-10 rounded m-0 p-0 ps-3" style="height: 45px;">
                        <span v-if="machineUserName" class="text-dark m-0 p-0 fs-6">
                            {{ machineUserName }}
                        </span>
                    </div>

                    <div v-if="!machineIsLoaded" class="input-group-text col-10 rounded m-0 p-0 ps-3" style="height: 45px;">
                        <span class="m-0 p-0">
                            ---
                        </span>
                    </div>
                    <div class="col-auto m-0 p-0">
                        <i class="bi bi-person-check-fill fs-4 col-auto m-0 p-0"></i>
                    </div>
                </div>
            </div>


            <div class="row m-0 p-0">
                <div class="m-0 p-0 mb-2 mt-4">
                    <span class="m-0 p-0 text-secondary fs-6">
                        {{ lang('password') }}
                    </span>
                </div>
            </div>



            <div class="row m-0 p-0">
                <div class="input-group d-flex flex-row justify-content-between align-items-center m-0 p-0">
                    <div v-if="machineIsLoaded && machineUserPass" class="input-group-text col-10 rounded m-0 p-0 ps-3" style="height: 45px;">
                        <span v-if="showpassword" class="text-dark m-0 p-0 fs-6">

                            <?php for ($i = 0; $i <= 10; $i++) : ?>
                                <i class="bi bi-asterisk text-secondary" style="font-size: 10px !important;"></i>
                            <?php endfor ?>

                        </span>
                        <span v-if="!showpassword" class="text-dark m-0 p-0 fs-6">{{ machineUserPass }}</span>
                    </div>

                    <div v-else class="input-group-text col-10 rounded m-0 p-0 ps-3" style="height: 45px;">
                        <span class=""> --- </span>
                    </div>

                    <!-- Icon btn visibilty -->
                    <div class="col-auto m-0 p-0">
                        <i v-if="showpassword"
                            class="col-auto m-0 p-0 bi bi-eye-slash-fill fs-4 fw-bold text-secondary btn"
                            @click="changeVisibility()"></i>
                        <i v-if="!showpassword"
                            class="col-auto m-0 p-0 bi bi-eye-fill fs-4 fw-bold text-primary btn"
                            @click="changeVisibility()"></i>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end Login -->


</div>
<!-- End Finance and Network -->