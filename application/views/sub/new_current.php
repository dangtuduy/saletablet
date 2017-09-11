<div class="panel panel-info">
<div class="panel-heading">Đơn hàng hiện tại</div>
<div class="panel-body">
<h5><b>Khách hàng:</b> <a class="btn btn-none" href="<?=site_url('sales/ordernew/1')?>">Thay đổi</a> <span style="color: green;"><?php echo (isset($ordercust['custid']) ? '['.$ordercust['custid'].'] ' : '').(isset($ordercust['name']) ? $ordercust['name'].'; '.$ordercust['street'] : '').';{'.(isset($ordercust['custclass'])? $ordercust['custclass']:'').'}'?></span></h5>
<div class="clearfix"><b>SKU đang bán</b><a class="btn btn-primary" href="<?=site_url('sales/ordernew/2/')?>">Chọn SKU</a></div>
<?php
$cisell = 0; 
if(isset($sellingItems))
{
	//ksort($sellingItems);
	foreach($sellingItems as $name=>$value)
	{
		$cisell++;
		echo '<div style="border-bottom:1pt solid #dfdfdf; height:22px; padding-top:3px; clear:both"><div class="col-xs-10">'.$name.'-'.$value['name'].(empty($value['note']) ? '': '<br/><i style="color:#aaa">'.$value['note'].'</i>').'</div><div class="col-xs-1 center"><b>'.$value['qty'].'</b></div></div>';
	}   
}  
?>
<div class=" clearfix text-center" style="margin-top:10px;"><?php if(isset($cisell) && $cisell > 0) echo '<a href="'.site_url('sales/ordernew/3/edit/').'" class="btn btn-danger">Thay đổi</a><a href="'.site_url('sales/ordernew/3/').'" class="btn btb-ver">Chọn xong</a>';?></div>
</div>
<br class="clearfix"/>
</div>
