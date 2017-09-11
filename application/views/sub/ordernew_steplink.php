<div class="row bs-wizard" style="border-bottom:0;">
    <div class="col-xs-4 bs-wizard-step complete">
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="<?=site_url('sales/ordernew/1')?>" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center"><b>Chọn khách hàng</b></div>
    </div>
    <div class="col-xs-4 bs-wizard-step <?=(($step>=2) || (isset($custid) && !empty($custid)))?'complete' : 'disabled'?>">
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="<?=site_url('sales/ordernew/2/'.$custid)?>" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center"><b>Chọn mặt hàng</b></div>
    </div>
    <div class="col-xs-4 bs-wizard-step <?=($step==3)?'complete' : 'disabled'?>">
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="<?=site_url('sales/ordernew/3/'.$custid)?>" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center"><b>Lưu &amp; Gửi</b></div>
    </div>
</div>
