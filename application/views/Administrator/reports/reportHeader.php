
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">

<?php
$branchId = $this->session->userdata('BRANCHid');
$companyInfo = $this->Billing_model->company_branch_profile($branchId);
?>
<div class="container">
    <div class="row">
        <div class="col-xs-2"><img src="<?php echo base_url(); ?><?php echo $companyInfo->Company_Logo_thum; ?>" alt="Logo" style="height:80px;" /></div>
        <div class="col-xs-10" style="padding-top:20px;">
            <strong style="font-size:18px;"><?php echo $companyInfo->Company_Name; ?></strong><br>
            <p style="white-space: pre-line;"><?php echo $companyInfo->Repot_Heading; ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div style="border-bottom: 4px double #454545;margin-top:7px;margin-bottom:7px;"></div>
        </div>
    </div>
</div>
