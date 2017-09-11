<div class="text-right"><b><?=lang('Revenue report')?>:</b>&nbsp;
	<?php
		$curMth = date('Y-m-d', strtotime(nowdatetime()));
		$fromdate = date('Y-m', strtotime($curMth)).'-01';
		$preMth = date('Y-m-d', strtotime('-1 day', strtotime($fromdate)));
	?>
	<a href="<?=site_url('sales/reports/revenue-now?todate=').($preMth)?>" class="btn btn-warning">Tháng trước <?=date('m.Y', strtotime($preMth))?></a>&nbsp;
    <a href="<?=site_url('sales/reports/revenue-now?todate=').($curMth)?>" class="btn btn-warning">Hiện tại <?=date('m.Y', strtotime($curMth))?></a>&nbsp;
    <a href="<?=site_url('sales/reports/revenue-one-customer')?>" class="btn btn-success">Theo từng khách hàng</a>&nbsp;
    <a href="<?=site_url('sales/reports/revenue-by-month')?>" class="btn btn-info">4 tháng gần nhất</a>&nbsp;
    <a href="<?=site_url('sales/reports/revenue-all-customer')?>" class="btn btn-default">Tất cả khách hàng</a>&nbsp;
</div>
<br />